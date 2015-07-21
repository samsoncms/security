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

            trace($userRights, true);
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
        $parsedRights = array();

        /** @var \samsonframework\orm\Record[] $groupRights Collection of user rights */
        $groupRights = array();
        // Retrieve all user group rights
        if (dbQuery('groupright')->join('right')->cond('GroupID', $groupID)->exec($groupRights)) {
            // Iterate all group rights
            foreach ($groupRights as $groupRight) {
                foreach ($groupRight->onetomany['_right'] as $userRight) {
                    // Parse application access rights
                    $matches = array();
                    if (preg_match(self::RIGHT_APPLICATION_KEY, $userRight->Name, $matches)) {
                        $parsedRights['application'][] = $matches['application'];
                    }
                }
            }
        }

        return $parsedRights;
    }

    /** Application initialization */
    public function init(array $params = array())
    {
        // Subscribe to core security event
        \samsonphp\event\Event::subscribe('core.security', array($this, 'handle'));
    }
}
