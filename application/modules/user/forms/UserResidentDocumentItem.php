<?php

class User_Form_UserResidentDocumentItem extends Ext_Form_SubForm
{
    protected $key = 1;
    protected $residentId;
    protected $garantId;

    public function init()
    {
        $this->setElementsBelongTo("document[{$this->residentId}][{$this->garantId}][{$this->key}]");

        $this->addElement('hidden', 'id', array(
            'value' => $this->key
        ));

        $this->addElement('file', 'passport', array(
            'label' => 'Passport',
            'required' => true,
        ));

        $this->addElement('file', 'contract', array(
            'label' => 'Contract',
            'required' => false,

        ));

        $this->addElement('file', 'student_id', array(
            'label' => 'Student Id',
            'required' => true,

        ));

        $this->addElement('radio', 'support', array(
            'label' => 'Supporting',
            'required' => true,
            'separator' => '',
            'multiOptions' => array(
                Model_UserResidentDocument::TYPE_PAYSLIP_GUARANTEED => 'Payslip',
                Model_UserResidentDocument::TYPE_TAX_NOTICE_GUARANTEED => 'Tax Notice'),
            'value' => ''
        ));
        $this->getElement('support')->setDecorators(array(
            array('MrButtons', array('needAll' => false, 'labelClass' => 'btn-input-lite', 'inputClass' => 'document_support input-pretty')),
            array('Label', array('tag'=>'label', 'separator'=>' ')),
            array('HtmlTag', array('tag' => 'div', 'class'=>'')),
            array('Errors'),
        ));

        $this->addElement('file', 'paycheck1', array(
            'label' => 'Paycheck1',
            'required' => true,

        ));

        $this->addElement('file', 'paycheck2', array(
            'label' => 'Paycheck1',
            'required' => true,

        ));

        $this->addElement('file', 'paycheck3', array(
            'label' => 'Paycheck1',
            'required' => true,

        ));

        $this->addElement('file', 'tax_notice', array(
            'label' => 'Paycheck1',
            'required' => true,

        ));

        $this->addElement('file', 'payslip', array(
            'label' => 'Paycheck1',
            'required' => true,

        ));
    }
    /*

    public function init()
    {
        $this->setElementsBelongTo("doc[{$this->key}]");

        $this->addElement('hidden', 'id');

        $this->addElement('radio', 'type', array(
            'label' => 'document_type',
            'required' => true,
            'separator' => '',
            'multiOptions' => Model_UserResidentDocument::getTypes(),
            'attribs' => array('class' => 'type'),
            'value' => '',
        ));
        $this->getElement('type')->setDecorators(array(
            array('MrButtons', array('needAll' => false, 'labelClass' => 'btn-input-lite', 'inputClass' => 'document_type input-pretty')),
            array('Label', array('tag'=>'label', 'separator'=>' ')),
            array('HtmlTag', array('tag' => 'div', 'class'=>'')),
            array('Errors'),
        ));

        $this->addElement('text', 'original_name', array(
            'label' => 'document_original_name',
            'required' => true,
            'attribs' => array('class' => 'original_name', 'style' => ''),
            'filters'    => array('StringTrim', 'StripTags'),
        ));
        $this->getElement('original_name')->setDecorators(array('ViewHelper', 'Label', 'Errors'));

        $this->addElement('file', 'file', array(
            'label'      => 'document_file',
            'filters'    => array('StringTrim', 'StripTags'),
            'attribs'	 => array('class' => 'document_file', 'style' => '')
        ));
        $this->getElement('job_title')->setDecorators(array('ViewHelper', 'Label', 'Errors'));
    }

    public function setKey($num)
    {
        $this->key = (int) $num;
        return $this;
    }*/
} 