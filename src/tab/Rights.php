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

class Rights extends Generic
{
    /** @var string Tab name or identifier */
    protected $name = 'EntityRight Tab';

    /** @var \samsoncms\app\security\Controller */
    protected $renderer;

    /** @var string Tab identifier */
    protected $id = 'Entity_right_tab';

    /** @inheritdoc */
    public function content()
    {
        return $this->renderer
            ->view($this->contentView)
            ->set('content', $this->renderer->changeRights($this->entity))
            ->output();
    }
}
