<?php
/**
 * Created by PhpStorm.
 * User: onysko
 * Date: 27.05.2015
 * Time: 13:07
 */

namespace samsoncms\app\security\form;


use samsoncms\app\security\tab\EntityRights;
use samsonframework\core\RenderInterface;
use samsonframework\orm\QueryInterface;
use samsonframework\orm\Record;

class Form extends \samsoncms\form\Form
{
    /** @inheritdoc */
    public function __construct(RenderInterface $renderer, QueryInterface $query, Record $entity)
    {
        $this->tabs = array(
            new EntityRights($renderer, $query, $entity)
        );

        parent::__construct($renderer, $query, $entity);
    }
}
