<?php
/**
 * Created by PhpStorm.
 * User: onysko
 * Date: 10.06.2015
 * Time: 16:43
 */

namespace samsoncms\app\security\tab;


use samson\cms\Navigation;
use samsoncms\form\tab\Generic;
use samsonframework\core\RenderInterface;
use samsonframework\orm\QueryInterface;
use samsonframework\orm\Record;

class EntityRights extends Generic
{
    /** @var string Tab name or identifier */
    protected $name = 'EntityRight Tab';

    protected $id = 'Entity_right_tab';


    /** @inheritdoc */
    public function __construct(RenderInterface $renderer, QueryInterface $query, Record $entity)
    {
        // Call parent constructor to define all class fields
        parent::__construct($renderer, $query, $entity);
    }

    /** @inheritdoc */
    public function content()
    {
        $content = $this->renderer->changeRights($this->entity);

        return $this->renderer->view($this->contentView)->content($content)->output();
    }
}
