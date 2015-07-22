<?php
namespace samsoncms\security;

use samsonframework\core\RenderInterface;
use samsonframework\orm\QueryInterface;
use samsonframework\pager\PagerInterface;
use samsoncms\field\Generic;
use samsoncms\field\Control;

/**
 * Collection of SamsonCMS users
 * @package samsoncms\app\user
 */
class Collection extends \samsoncms\Collection
{
    /**
     * Overload default constructor
     * @param RenderInterface $renderer View renderer
     * @param QueryInterface $query Database query
     * @param PagerInterface $pager Paging
     */
    public function __construct(RenderInterface $renderer, QueryInterface $query, PagerInterface $pager)
    {
        // Fill collection fields
        $this->fields = array(
            new Generic('GroupID', '#', 0, 'id', false),
            new Generic('Name', t('Название группы', true), 0, 'group_name', true),
            new Generic('Active', t('Активный', true), 11),
            new Control(),
        );

        // Call parent
        parent::__construct($renderer, $query, $pager);

        // Fill collection on creation
        $this->fill();
    }
}
