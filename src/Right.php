<?php
/**
 * Created by PhpStorm.
 * User: egorov
 * Date: 21.07.2015
 * Time: 12:26
 */
namespace samsoncms\app\security;

/** Security access right */
class Right extends \samson\activerecord\Right
{
    /** Application access right name template for generation */
    const APPLICATION_ACCESS_TEMPLATE = 'APPLICATION_';

    /** Application access right name pattern */
    const APPLICATION_ACCESS_PATTERN = '/^APPLICATION_(?<application>.*)/ui';

    /** All application access right */
    const APPLICATION_ACCESS_ALL = 'all';
}
