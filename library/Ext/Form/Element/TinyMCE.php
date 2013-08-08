<?php

/**
 * TinyMCE form element
 * 
 * Example:
 * 
 * $form->addElement('tinyMCE', 'field_name', array(
 *    'label' => 'Field Lable',
 *    'cols'  => '50',
 *    'rows'  => '5',
 *    'required' => true,
 *    'filters' => array('StringTrim'),
 *    'tinyMCE' => array(
 *        'mode' => "textareas",
 *        'theme' => "simple",
 *    ),
 * ));
 *
 * @category    Ext
 * @package     Ext_From
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Form_Element_TinyMCE extends Zend_Form_Element
{
    /**
     * View helper by default
     * @var string
     */
    public $helper = 'formTinyMCE';
}
