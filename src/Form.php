<?php
/**
 * Created by PhpStorm.
 * User: onysko
 * Date: 27.05.2015
 * Time: 13:07
 */
namespace samsoncms\app\security;

use samsoncms\app\security\tab\Main;
use samsoncms\app\security\tab\Rights;
use samsonframework\core\RenderInterface;
use samsonframework\orm\QueryInterface;
use samsonframework\orm\Record;

/**
 * Security group edition form
 * @package samsoncms\app\security
 */
class Form extends \samsoncms\form\Form
{
    /** @inheritdoc */
    public function __construct(RenderInterface $renderer, QueryInterface $query, Record $entity)
    {
        $this->tabs = array(
            // Add main tab
            new Main($renderer, $query, $entity),
            // Add security group rights edition tab
            new Rights($renderer, $query, $entity)
        );

        parent::__construct($renderer, $query, $entity);
    }
}
