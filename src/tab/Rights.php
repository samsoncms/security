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

/**
 * SamsonCMS application form tab for group security rights selection
 * @package samsoncms\app\security\tab
 */
class Rights extends Generic
{
    /** @var string Tab name or identifier */
    protected $name = 'Доступные права';

    /** @var \samsoncms\app\security\Controller */
    protected $renderer;

    /** @var string Tab identifier */
    protected $id = 'Entity_right_tab';

    /** @inheritdoc */
    public function content()
    {
        // right for current entity
        $entityRightIDs = $this->query->className('groupright')->cond('GroupID', $this->entity->id)->fields('RightID');

        // all rights
        $right = $this->query->className('right')->exec();

        $chbView = '';
        foreach ($right as $item) {
            if (in_array($item->id, $entityRightIDs)) {
                $chbView .= "<div class='input-container'>";
                $chbView .= '<label><input type="checkbox" checked value="1">' . $item->Name . '</label>';
                $chbView .= "<input type='hidden' name='__action' value='/'>";
                $chbView .= "</div>";
            } else {
                $chbView .= "<div class='input-container'>";
                $chbView .= '<label><input type="checkbox" value="1">' . $item->Name . '</label>';
                $chbView .= "<input type='hidden' name='__action' value='".module_url('change_entity_right')."'>";
                $chbView .= "</div>";
            }
        }

        $content = $this->renderer->view('form/tab_item')->set('chbView', $chbView)->output();

        return $this->renderer
            ->view($this->contentView)
            ->set('content', $content)
            ->output();
    }
}
