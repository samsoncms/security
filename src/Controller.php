<?php
/**
 * Created by PhpStorm.
 * User: egorov
 * Date: 17.07.2015
 * Time: 9:20
 */
namespace samsoncms\security;

/**
 * SamsonCMS security controller
 * @package samsoncms\security
 */
class Controller extends \samsoncms\Application
{
    /** Application access right name pattern */
    const RIGHT_APPLICATION_KEY = '/^APPLICATION_(?<application>.*)/ui';

    /** @var array User group rights cache */
    protected $rightsCache = array();

    /** @var \samsonframework\orm\QueryInterface */
    protected $db;

    /** @var bool Do not show this application in main menu */
    public $hide = true;

    /**
     * Core routing(core.routing) event handler
     * @param \samson\Core $core
     * @param bollean $securityResult
     */
    public function handle(&$core, &$securityResult)
    {
        // Remove URL base from current URL, split by '/'
        $parts = explode('/', str_ireplace(__SAMSON_BASE__, '', $_SERVER['REQUEST_URI']));

        // Get module identifier
        $module = isset($parts[0]) ? $parts[0] : '';
        // Get action identifier
        $action = isset($parts[1]) ? $parts[1] : '';
        // Get parameter values collection
        $params = sizeof($parts) > 2 ? array_slice($parts, 2) : array();

        // If we have are authorized
        if (m('social')->authorized()) {
            /**@var \samson\avticerecord\user Get authorized user object */
            $authorizedUser = m('social')->user();

            // Try to load security group rights from cache
            $userRights = & $this->rightsCache[$authorizedUser->group_id];
            if (!isset($userRights)) {
                // Parse security group rights and store it to cache
                $userRights = $this->parseGroupRights($authorizedUser->group_id);
            }

            // If we have full right to access all applications
            if (in_array(Right::APPLICATION_ACCESS_ALL, $userRights['application'])) {
                return $securityResult = true;
            } else if (in_array($module, $userRights['application'])) { // Try to find right to access current application
                return $securityResult = true;
            } else if ($module == '' && in_array('template', $userRights['application'])) {// Main page(empty url)
                return $securityResult = true;
            } else { // We cannot access this application
                return $securityResult = false;
            }
        }
    }

    /**
     * Parse application access right
     * @param string $rightName Right name
     * @return string Application name
     */
    private function matchApplicationAccessRight($rightName, &$applicationName)
    {
        // Parse application access rights
        $matches = array();
        if (preg_match(Right::APPLICATION_ACCESS_PATTERN, $rightName, $matches)) {
            // Return application name
            $applicationName = strtolower($matches['application']);
            return true;
        }

        return false;
    }

    /**
     * Parse database application user group rights
     * @param integer $groupID Security group identifier
     * @return array Parsed user group rights
     */
    public function parseGroupRights($groupID)
    {
        /** @var array $parsedRights Parsed rights */
        $parsedRights = array('application' => array());

        /** @var \samsonframework\orm\Record[] $groupRights Collection of user rights */
        $groupRights = array();
        // Retrieve all user group rights
        if ($this->db->className('groupright')->join('right')->cond('GroupID', $groupID)->exec($groupRights)) {
            // Iterate all group rights
            foreach ($groupRights as $groupRight) {
                // If we have rights for this group
                if (isset($groupRight->onetomany['_right'])) {
                    foreach ($groupRight->onetomany['_right'] as $userRight) {
                        // Parse application access rights
                        $applicationID = '';
                        if ($this->matchApplicationAccessRight($userRight->Name, $applicationID)) {
                            $parsedRights['application'][] = $applicationID;
                        }
                    }
                }
            }
        }

        return $parsedRights;
    }

    /** Application initialization */
    public function init(array $params = array())
    {
        // Create database query language
        $this->db = dbQuery('right');

        // Find all applications that needs access rights to it
        $accessibleApplications = array(
            'template' => 'template',   // Main application
            Right::APPLICATION_ACCESS_ALL => Right::APPLICATION_ACCESS_ALL // All application
        );

        // Iterate all loaded applications
        foreach (self::$loaded as $application) {
            // Iterate only applications with names
            $accessibleApplications[$application->id] = $application->name;
        }

        // Go throw all rights and remove unnecessary
        foreach ($this->db->className('right')->exec() as $right) {
            // Match application access rights
            $applicationID = '';
            if ($this->matchApplicationAccessRight($right->Name, $applicationID)) {
                // If there is no such application that access right exists
                if(!isset($accessibleApplications[$applicationID])) {
                    $right->delete();
                }
            }
        }

        // Iterate all applications that needs access rights
        foreach ($accessibleApplications as $accessibleApplicationID => $accessibleApplicationName) {
            // Try to find this right in db
            if (!$this->db->className('right')->cond('Name', Right::APPLICATION_ACCESS_TEMPLATE.$accessibleApplicationID)->first()) {
                $right = new Right();
                $right->Name = Right::APPLICATION_ACCESS_TEMPLATE.strtoupper($accessibleApplicationID);
                $right->Active = 1;
                $right->save();
            }
        }

        // Subscribe to core security event
        \samsonphp\event\Event::subscribe('core.security', array($this, 'handle'));
    }
}
