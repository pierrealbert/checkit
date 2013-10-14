<?php

/**
 * File Image Element Decorator
 *
 * @category    Ext
 * @package     Ext_From
 * @subpackage  Decorator
 * @since       1.0
 * @version     $Revision: 1.0 $
 */

require_once 'Zend/Form/Decorator/Abstract.php';

class Ext_Form_Decorator_MrButtons extends Zend_Form_Decorator_ViewHelper
{
    protected $_labelClass = 'btn-input-gray-dark';

    public function setOptions(array $options)
    {
        if (isset($options['labelClass'])) {
            $this->_labelClass = $options['labelClass'];
        }

        return $this;
    }

    public function setOption($key, $value)
    {
        if ($key == 'labelClass') {
            $this->_labelClass = $value;
        }

        return $this;
    }

    public function getLabelClass() {
        return $this->_labelClass;
    }

    public function render($content)
    {
        $element = $this->getElement();

        $view = $element->getView();
        if (null === $view) {
            require_once 'Zend/Form/Decorator/Exception.php';
            throw new Zend_Form_Decorator_Exception('Ext_Form_Decorator_MrButtons decorator cannot render without a registered view object');
        }

        if (method_exists($element, 'getMultiOptions')) {
            $items = $element->getMultiOptions();
        }

        $elHtml = '';
        foreach ($items as $indx => $itemValue) {
            $elHtml .= '<input type="radio" class="input-pretty" value="'.$indx.'" name="'.$element->getFullyQualifiedName().'" id ="'.$element->getFullyQualifiedName().'_'.$indx.'" '.($this->getValue($element) === $indx ? 'checked="checked"' : '').' /> '.
                       '<label for="'.$element->getFullyQualifiedName().'_'.$indx.'" class="'.$this->getLabelClass().'">'.$itemValue.'</label>';
        }
        $separator     = $this->getSeparator();

        switch ($this->getPlacement()) {
            case self::APPEND:
                $result = $content . $separator . $elHtml;
            case self::PREPEND:
                $result = $elHtml . $separator . $content;
            default:
                $result = $elHtml;
        }

        return '<div><input type="button" value="Tous" class="btn-tous" />'.$result.'</div>';
    }
}
