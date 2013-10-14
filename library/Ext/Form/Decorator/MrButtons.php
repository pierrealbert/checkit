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

class Ext_Form_Decorator_MrButtons extends Ext_Form_Decorator_MchBox
{
    protected $_labelClass = 'btn-input-gray-dark';

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
            $elHtml .= '<input type="radio" class="input-pretty" value="'.$indx.'" name="'.$element->getFullyQualifiedName().'" id ="'.$element->getFullyQualifiedName().'_'.$indx.'" '.($this->getValue($element) === $indx ? 'checked="checked"' : '').' /> '.
                       '<label for="'.$element->getFullyQualifiedName().'_'.$indx.'" class="'.$this->getLabelClass().'">'.$itemValue.'</label>';
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
