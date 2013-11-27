<?php


class User_Form_PropertyProcessVisitDate extends Ext_Form
{
    public function init()
    {
        $this->addElement('text', 'availability', array(
            'allowEmpty' => false,
            'filters'    => array('StringTrim'),
            'validators' => array(new Zend_Validate_Date('dd/MM/yyyy')),
        ));
    }
}