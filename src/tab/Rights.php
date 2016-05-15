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
    public function renderList(array $availableValues, array $selectedValueIDs, $controller, $showField = 'Description')
    {
        // Iterate all available values
        $html = '';
        foreach ($availableValues as $availableValue) {
            // Define if this value is selected
            $checked = in_array($availableValue->id, $selectedValueIDs) ? 'checked' : '';

            // Translate all fields
            $finishTranslateParts = $this->translateCustomFields($availableValue->$showField);

            $html .= '<div class="input-container select-checkboxes-list-item">';
            // Render checkbox with label
            $html .= '<label><input type="checkbox" ' . $checked . ' href="' . url_build($controller, $availableValue->id) . '" value="' . $availableValue->id . '">' . $finishTranslateParts . '</label>';
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Function which translated custom fields for application "Rights"
     * @param array $inputData
     * @return string
     */
    public function translateCustomFields($inputData)
    {
        // Search all part this text block
        $allTranslateParts = explode("\"", $inputData);
        // Remove empty elements
        $allTranslateParts = array_filter($allTranslateParts);

        // First value empty
        $finishTranslateParts = '';
        // Counter elements in this array
        $count = 0;
        foreach ($allTranslateParts as $oneTranslateParts) {
            // First part (not have quotes)
            if ($count == 0) {
                $finishTranslateParts .= t($oneTranslateParts, true);
                // Last part with quotes
            } else {
                $finishTranslateParts .= ' "' . t($oneTranslateParts, true) . '"';
            }
            // Increment count
            $count++;
        }

        return $finishTranslateParts;
    }

    /** @inheritdoc */
    public function content()
    {
        // Translate header
        $this->name = t($this->name, true);

        // Access to the application

        // Render tab content
        $content = $this->renderer
            ->view('form/tab_item')
            ->set($this->renderList(
                $this->query->className('right')->exec(),
                $this->query->className('groupright')->cond('GroupID', $this->entity->id)->fields('RightID'),
                $this->renderer->id() . '/change/' . $this->entity->id
            ), 'chbView')
            ->output();

        return $this->renderer
            ->view($this->contentView)
            ->set($content, 'content')
            ->output();
    }
}
