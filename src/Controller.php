<?php
/**
 * Created by PhpStorm.
 * User: egorov
 * Date: 17.07.2015
 * Time: 9:20
 */
namespace samsoncms\app\security;

use samson\activerecord\dbQuery;
use samson\activerecord\groupright;
use samsonframework\orm\Relation;

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

    /** Application name */
    public $name = 'Права';

    /** Application description */
    public $description = 'Права доступа';

    /** Application icon*/
    public $icon = 'unlock';

    /** Identifier */
    public $id = 'security';

    /** @var string Module identifier */
    protected $entity = '\samson\activerecord\group';

    /** @var string SamsonCMS application form class */
    protected $formClassName = '\samsoncms\app\security\Form';

    /**
     * Asynchronous change group right controller action
     * @param string $groupID Group identifier
     * @param string $rightID Right identifier
     * @return array Asynchronous response array
     */
    public function __async_change($groupID, $rightID)
    {
        $group = null;
        if($this->findEntityByID($groupID, $group)) {
            $right = null;
            if($this->findEntityByID($rightID, $right, 'right')) {
                /** @var \samsonframework\orm\Record  Try to find this right for a specific group */
                $groupRight = null;
                if ($this->query->className('groupright')->cond('GroupID', $groupID)->cond('RightID', $rightID)->first($groupRight)) {
                    // Remove existing
                    $groupRight->delete();
                } else { // Create new
                    $groupRight = new groupright();
                    $groupRight->Active = 1;
                    $groupRight->GroupID = $groupID;
                    $groupRight->RightID = $rightID;
                    $groupRight->save();
                }

                return array('status' => '1');
            }

            return array('status' => '0', 'error' => 'Right #'.$rightID.' was not found');
        }

        return array('status' => '0', 'error' => 'Group #'.$rightID.' was not found');
    }

    /**
     * Core routing(core.routing) event handler
     * @param \samson\core\Core $core
     * @param boolean $securityResult
     * @return boolean True if security passed
     */
    public function handle(&$core, &$securityResult)
    {
        // Remove URL base from current URL, split by '/'
        $parts = explode('/', str_ireplace(__SAMSON_BASE__, '', $_SERVER['REQUEST_URI']));

        // Get module identifier
        $module = isset($parts[0]) ? $parts[0] : '';
        // Get action identifier
        //$action = isset($parts[1]) ? $parts[1] : '';
        // Get parameter values collection
        //$params = sizeof($parts) > 2 ? array_slice($parts, 2) : array();

        // If we have are authorized
        if (m('social')->authorized()) {
            /**@var \samson\activerecord\user Get authorized user object */
            $authorizedUser = m('social')->user();

            // Try to load security group rights from cache
            $userRights = & $this->rightsCache[$authorizedUser->group_id];
            if (!isset($userRights)) {
                // Parse security group rights and store it to cache
                $userRights = $this->parseGroupRights($authorizedUser->group_id);
            }

            // Hide all applications except with access rights
            foreach (self::$loaded as $application) {
                if (!in_array($application->id, $userRights['application'])
                    && !in_array(Right::APPLICATION_ACCESS_ALL, $userRights['application'])
                    && $authorizedUser->group_id != 1
                ) {
                    $application->hide = true;
                }
            }

            // If we have full right to access all applications or admin
            if (in_array(Right::APPLICATION_ACCESS_ALL, $userRights['application']) || $authorizedUser->group_id == 1) {
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
     * Clear all database security rights records that do not match current application list
     * @param array $accessibleApplications Collection of loaded applications
     */
    private function clearUnmatchedRights(array $accessibleApplications)
    {
        // Go throw all rights and remove unnecessary
        foreach ($this->query->className('right')->exec() as $right) {
            // Match application access rights
            $applicationID = '';
            if ($this->matchApplicationAccessRight($right->Name, $applicationID)) {
                // If there is no such application that access right exists
                if(!isset($accessibleApplications[$applicationID])) {
                    $right->delete();
                }
            }
        }
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
        if ($this->query->className('groupright')->join('right')->cond('GroupID', $groupID)->exec($groupRights)) {
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
        // Find all applications that needs access rights to it
        $accessibleApplications = array(
            'template' => 'Главная страница',   // Main application
            Right::APPLICATION_ACCESS_ALL => Right::APPLICATION_ACCESS_ALL // All application
        );

        // Iterate all loaded applications
        foreach (self::$loaded as $application) {
            // Iterate only applications with names
            $accessibleApplications[$application->id] = $application->name;
        }

        // Iterate all applications that needs access rights
        foreach ($accessibleApplications as $accessibleApplicationID => $accessibleApplicationName) {
            // Try to find this right in db
            if (!$this->query->className('right')->cond('Name', Right::APPLICATION_ACCESS_TEMPLATE.$accessibleApplicationID)->first()
                && isset($accessibleApplicationName{0}) // Name not empty
            ) {
                $right = new Right();
                $right->Name = Right::APPLICATION_ACCESS_TEMPLATE.strtoupper($accessibleApplicationID);
                $right->Description = $accessibleApplicationID != Right::APPLICATION_ACCESS_ALL
                    ? t('Доступ к приложению', true).' "'.$accessibleApplicationName.'"'
                    : t('Полный доступ ко всем приложениям', true);
                $right->Active = 1;
                $right->save();
            }
        }

        // Subscribe to core security event
        \samsonphp\event\Event::subscribe('core.security', array($this, 'handle'));
    }
}
