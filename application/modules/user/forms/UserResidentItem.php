<?php

class User_Form_UserResidentItem extends Ext_Form_SubForm
{

	protected $rowNumber = 1;

	public function init()
    {
		$this->setElementsBelongTo("member[{$this->rowNumber}]");

		$this->addElement('hidden', 'id');
        /*$this->getElement('id')->removeDecorator('label')
            ->removeDecorator('HtmlTag');*/
        
		$this->addElement('radio', 'resident_type', array(
			'label' => 'resident_type',
			'required' => true,
            'separator' => '',
			'multiOptions' => Model_UserResident::getTypes(),
            'value' => '',
		));
        $this->getElement('resident_type')->setDecorators(array(
            array('MrButtons', array('needAll' => false, 'labelClass' => 'btn-input-lite', 'inputClass' => 'resident-type input-pretty')),
            array('Label', array('tag'=>'label', 'separator'=>' ')),
            array('HtmlTag', array('tag' => 'div', 'class'=>'')),
            array('Errors'),
        ));

        $this->addElement('text', 'resident_name', array(
                'label' => 'resident_name',
                'required' => true,
                'filters'    => array('StringTrim', 'StripTags'),
                'attribs' => array('class' => 'resident_name', 'style' => '')
        ));
        $this->getElement('resident_name')->setDecorators(array('ViewHelper', 'Label', 'Errors'));

		$this->addElement('text', 'job_title', array(
            'label'      => 'job_title',
            /*'required'   => true,*/
            'filters'    => array('StringTrim', 'StripTags'),
			'attribs'	 => array('class' => 'job-title', 'style' => 'width: 400px')
        ));
        $this->getElement('job_title')->setDecorators(array('ViewHelper', 'Label', 'Errors'));

		$this->addElement('radio', 'employee_type', array(
			'label' => 'employee_type',
            'required' => true,
			'separator' => '',
			'value' => '',
			'multiOptions' => Model_UserResident::getEmployeeTypes()
		));
        $this->getElement('employee_type')->setDecorators(array(
            array('MrButtons', array('needAll' => false, 'labelClass' => 'btn-input-lite', 'inputClass' => 'employee-type input-pretty')),
            array('Label', array('tag'=>'label', 'separator'=>' ')),
            array('HtmlTag', array('tag' => 'div', 'class'=>'')),
            array('Errors'),
        ));

		$this->addElement('text', 'monthly_income', array(
            'label'      => 'monthly_income',
            'required'   => true,
            'attribs' => array('class' => 'monthly-income input-money'),
            'filters'    => array('StringTrim', 'StripTags'),
			'validators' => array('Float')
        ));
        $this->getElement('monthly_income')->setDecorators(array('ViewHelper', 'Label', 'Errors'));

		$this->addElement('text', 'monthly_income_guaranteed', array(
            'label'      => 'monthly_income_guaranteed',
            'attribs' => array('class' => 'input-money'),
            'filters'    => array('StringTrim', 'StripTags'),
			'validators' => array('Float')
        ));
        $this->getElement('monthly_income_guaranteed')->setDecorators(array('ViewHelper', 'Label', 'Errors'));
	}

	public function setRowNumber($num)
	{
		$this->rowNumber = (int)$num;
		return $this;
	}
}