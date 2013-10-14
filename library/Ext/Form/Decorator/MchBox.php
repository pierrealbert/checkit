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

class Ext_Form_Decorator_MchBox extends Zend_Form_Decorator_ViewHelper
{
    protected $_labelClass = 'btn-input-gray-dark btn-input-number';
    protected $_needAll = true;
    protected $_brAfter = -1;

    public function setOptions(array $options)
    {
        if (isset($options['labelClass'])) {
            $this->_labelClass = $options['labelClass'];
        }
        if (isset($options['needAll'])) {
            $this->_needAll = $options['needAll'];
        }
        if (isset($options['brAfter'])) {
            $this->_brAfter = intval($options['brAfter']);
        }

        return $this;
    }

    public function setOption($key, $value)
    {
        if ($key == 'labelClass') {
            $this->_labelClass = $value;
        } elseif ($key == 'needAll') {
            $this->_needAll = $value;
        } elseif ($key == 'brAfter') {
            $this->_brAfter = intval($value);
        }

        return $this;
    }

    public function getLabelClass() {
        return $this->_labelClass;
    }

    public function getNeedAll() {
        return $this->_needAll;
    }

    public function getBrAfter() {
        return $this->_brAfter > 0 ? $this->_brAfter : -1;
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
        $count = 0;
        foreach ($items as $indx => $itemValue) {
            $elHtml .= "<input type='checkbox' class='input-pretty' id='".$element->getFullyQualifiedName()."_".$indx."' name='".$element->getFullyQualifiedName()."' value='".$indx."' ".($this->getValue($element) === $indx ? 'checked="checked"' : '').">\n".
                       "<label for='".$element->getFullyQualifiedName()."_".$indx."' class='".$this->getLabelClass()."'>".$itemValue."</label>";
            $count++;
            if ($this->getBrAfter() > 0) {
                if ($count % $this->getBrAfter() == 0) {
                    $elHtml .= '<br />';
                }
            }
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

        return '<div>'.($this->getNeedAll() ? '<input type="button" value="Tous" class="btn-tous" />' : '').$result.'</div>';
    }
}
