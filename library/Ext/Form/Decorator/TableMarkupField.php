<?php

/**
 * Decorator
 *
 * @category    Ext
 * @package     Ext_From
 * @subpackage  Decorator
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Form_Decorator_TableMarkupField extends Zend_Form_Decorator_Abstract
{
    public function buildLabel()
    {
        $element = $this->getElement();
        $label = $element->getLabel();

        if ($element instanceof Zend_Form_Element_Submit) {
            return '&nbsp;';
        }
        if ($translator = $element->getTranslator()) {
            $label = $translator->translate($label);
        }
        $output = $element->getView()
                       ->formLabel($element->getName(), $label);
        if ($element->isRequired()) {
            $output .= ' <span class="required">*</span>';
        }
        return $output;
    }

    public function buildErrors()
    {
        $element  = $this->getElement();
        $messages = $element->getMessages();
        if (empty($messages)) {
            return '';
        }
        return '<div class="errors">' .
               $element->getView()->formErrors($messages) . '</div>';
    }

    public function buildDescription()
    {
        $element = $this->getElement();
        $desc    = $element->getDescription();
        if (empty($desc)) {
            return '';
        }
        if (null !== ($translator = $element->getTranslator())) {
            $desc = $translator->translate($desc);
        }             
        return '<div class="description">' . $desc . '</div>';
    }

    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element) {
            return $content;
        }
        if (null === $element->getView()) {
            return $content;
        }

        $separator = $this->getSeparator();
        $placement = $this->getPlacement();
        $label     = $this->buildLabel();
        $errors    = $this->buildErrors();
        $desc      = $this->buildDescription();

        $output = '<tr class="form-field">'
                . '<td class="label">' . $label . '</td>'
                . '<td class="value">' . $content
                . $errors
                . $desc
                . '</td></tr>';
        return $output;
    }
}