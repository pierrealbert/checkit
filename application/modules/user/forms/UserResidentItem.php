<?php

class User_Form_UserResidentItem extends Ext_Form_SubForm
{

	protected $rowNumber = 1;

	public function init()
    {
		$this->setElementsBelongTo("member[{$this->rowNumber}]");

		$this->addElement('select', 'resident_type', array(
			'label' => 'resident_type',
			'required' => true,
			'multiOptions' => Model_UserResident::getTypes(),
			'attribs' => array('class' => 'resident-type')
		));

		$this->addElement('text', 'job_title', array(
            'label'      => 'job_title',
            'required'   => true,
            'filters'    => array('StringTrim', 'StripTags'),
			'attribs'	 => array('class' => 'job-title')
        ));

		$this->addElement('radio', 'employee_type', array(
			'label' => 'employee_type',
			'separator' => '',
			'value' => Model_UserResident::EMPLOYEE_TYPE_CDI,
			'multiOptions' => Model_UserResident::getEmployeeTypes()
		));

		$this->addElement('text', 'monthly_income', array(
            'label'      => 'monthly_income',
            'required'   => true,
            'filters'    => array('StringTrim', 'StripTags'),
			'validators' => array('Float')
        ));

		$this->addElement('text', 'monthly_income_guaranteed', array(
            'label'      => 'monthly_income_guaranteed',
            'filters'    => array('StringTrim', 'StripTags'),
			'validators' => array('Float')
        ));
	}

	public function setRowNumber($num)
	{
		$this->rowNumber = (int)$num;
		return $this;
	}
}