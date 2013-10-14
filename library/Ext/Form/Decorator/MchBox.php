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
            $elHtml .= "<input type='checkbox' class='input-pretty' id='".$element->getFullyQualifiedName()."_".$indx."' name='".$element->getFullyQualifiedName()."' value='".$indx."' ".($this->getValue($element) === $indx ? 'checked="checked"' : '').">\n".
                       "<label for='".$element->getFullyQualifiedName()."_".$indx."' class='btn-input-gray-dark btn-input-number'>".$itemValue."</label>";
        }
        $separator     = $this->getSeparator();
/*

        $helper        = $this->getHelper();
        $separator     = $this->getSeparator();
        $value         = $this->getValue($element);
        $attribs       = $this->getElementAttribs();
        $name          = $element->getFullyQualifiedName();
        $id            = $element->getId();
        $attribs['id'] = $id;

        $helperObject  = $view->getHelper($helper);
        if (method_exists($helperObject, 'setTranslator')) {
            $helperObject->setTranslator($element->getTranslator());
        }

        // Check list separator
        if (isset($attribs['listsep'])
            && in_array($helper, array('formMulticheckbox', 'formRadio', 'formSelect'))
        ) {
            $listsep = $attribs['listsep'];
            unset($attribs['listsep']);

            $elementContent = $view->$helper($name, $value, $attribs, $element->options, $listsep);
        } else {
            $elementContent = $view->$helper($name, $value, $attribs, $element->options);
        }
*/
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
