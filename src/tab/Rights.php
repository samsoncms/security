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

    /**
     * Render checkboxes selection list
     * @param array $availableValues Collection of available entities for selection
     * @param array $selectedValueIDs Collection of selected entity identifiers
     * @param string $controller Select/Un-select controller action route
     * @param string $showField Entity field name for showing
     * @return string HTML rendered checkboxes list
     */
    public function renderList(array $availableValues, array $selectedValueIDs, $controller, $showField = 'Name')
    {
        // Iterate all available values
        $html = '';
        foreach ($availableValues as $availableValue) {
            // Define if this value is selected
            $checked = in_array($availableValue->id, $selectedValueIDs) ? 'checked' : '';

            $html .= '<div class="input-container select-checkboxes-list-item">';
            // Render checkbox with label
            $html .= '<label><input type="checkbox" '.$checked.' href="'.url_build($controller, $availableValue->id).'" value="'.$availableValue->id.'">' . $availableValue->$showField . '</label>';
            $html .= '</div>';
        }

        return $html;
    }

    /** @inheritdoc */
    public function content()
    {
        // Render tab content
        $content = $this->renderer
            ->view('form/tab_item')
            ->set('chbView', $this->renderList(
                $this->query->className('right')->exec(),
                $this->query->className('groupright')->cond('GroupID', $this->entity->id)->fields('RightID'),
                $this->renderer->id().'/change/'.$this->entity->id
            ))
            ->output();

        return $this->renderer
            ->view($this->contentView)
            ->set('content', $content)
            ->output();
    }
}
