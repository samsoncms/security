<?php
/**
 * Created by PhpStorm.
 * User: onysko
 * Date: 10.06.2015
 * Time: 16:43
 */
namespace samsoncms\app\security\tab;

use samsoncms\form\field\Generic;
use samsoncms\form\tab\Entity;
use samsonframework\core\RenderInterface;
use samsonframework\orm\QueryInterface;
use samsonframework\orm\Record;

/**
 * SamsonCMS application main form tab for security group
 * @package samsoncms\app\security\tab
 */
class Main extends Entity
{
    /** @var string Tab name or identifier */
    protected $name = 'Главная';

    /** @inheritdoc */
    public function __construct(RenderInterface $renderer, QueryInterface $query, Record $entity)
    {
        $this->fields = array(
            new Generic('Name', t('Название', true), 0),
        );

        // Call parent constructor to define all class fields
        parent::__construct($renderer, $query, $entity);
    }
}
