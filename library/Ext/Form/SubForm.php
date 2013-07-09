<?php

/**
 * Base SubForm
 *
 * @category    Ext
 * @package     Ext_Form
 * @subpackage  Ext_Form_SubForm
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Form_SubForm extends Zend_Form_SubForm
{
    public function  __construct($options = null)
    {
        $this->addPrefixPath('Ext_Form_', dirname(__FILE__) . '/Form/');

        // add prefix path for Ext elements
        $this->addElementPrefixPath('Ext_Form_Element_', dirname(__FILE__) . '/Form/Element/');

        // add prefix path for Ext decorators
        $this->addElementPrefixPath('Ext_Form_Decorator_', dirname(__FILE__) . '/Form/Decorator/', Zend_Form_Element::DECORATOR);

        // add prefix path for Ext validators
        $this->addElementPrefixPath('Ext_Validate_', dirname(__FILE__) . '/Validate/', Zend_Form_Element::VALIDATE);

        // add prefix path for Ext filters
        $this->addElementPrefixPath('Ext_Filter_', dirname(__FILE__) . '/Filter/', Zend_Form_Element::FILTER);

        parent::__construct($options);
    }
}
