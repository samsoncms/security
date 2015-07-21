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
            // Get authorized user object
            $authorizedUser = m('social')->user();
        }
    }

    public function getUserRights(&$user)
    {
        /** @var \samsonframework\orm\Record[] $userRights Collection of user rights */
        $userRights = array();
        // Retrieve all user group rights
        if(dbQuery('groupright')->join('right')->cond('group_id', $user->group_id)->exec($userRights)){
            
        }
    }

    public function init(array $params = array())
    {
        // Subscribe to core security event
        \samsonphp\event\Event::subscribe('core.security', array($this, 'handle'));
    }
}
