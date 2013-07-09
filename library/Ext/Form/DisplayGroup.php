<?php

/**
 * Form with jQuery UI tabs support
 *
 * @category    Ext
 * @package     Ext_Form
 * @since       1.0
 * @version     $Revision: 1.0 $
 *
 */
class Ext_Form_DisplayGroup extends Zend_Form_DisplayGroup
{
    public function loadDefaultDecorators()
    {
        $this->addDecorator('FormElements')
             ->addDecorator('HtmlTag', array(
                 'tag'=> 'dl',
                 'class' => 'form-display-group'
             ))
             ->addDecorator('Fieldset');
    }
}