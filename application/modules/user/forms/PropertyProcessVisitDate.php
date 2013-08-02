<?php


class User_Form_PropertyProcessVisitDate extends Ext_Form
{
    public function init()
    {
        $this->addElement('text', 'availability', array(
            'allowEmpty' => false,
            'filters'    => array('StringTrim'),
            'validators' => array(new Zend_Validate_Date('yyyy-MM-dd')),
        ));

        $this->addElement('text', 'at_time', array(
            'required'   => true,
            'filters'    => array('StringTrim'),
        ));

        $this->addElement('text', 'visitors', array(
            'required'   => true,
            'filters'    => array(new Zend_Filter_Int()),
        ));
    }
}