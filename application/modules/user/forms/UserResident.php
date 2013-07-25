<?php

class User_Form_UserResident extends Ext_Form
{
	protected $_count = 1;

	protected $_type = Model_UserResident::RENT_TYPE_SINGLE;

	public function __construct($count = null, $type = null) {
		if ($count) {
			$this->setCount($count);
		}

		if ($type) {
			$this->setType($type);
		}

		$this->setAttrib('id', 'form-user-resident');

		parent::__construct();
		
		$this->addMemberForms();

		$this->addElement('submit', 'register', array(
            'label' => 'submit'
        ));
	}
	
	public function addMemberForms()
	{
		for($i=0; $i < $this->_count; $i++) {
			$num = $i + 1;
			$key = "member_" . $num;
			
			$subform = new User_Form_UserResidentItem(array("RowNumber" => $num));
			$this->addSubForm($subform, $key)
				->getSubForm($key)
				->setLegend($this->_generateLegend($i));
		}
	}

	public function setCount($count)
	{
		$this->_count = $count;
		return $this;
	}

	public function setType($type)
	{
		$this->_type = $type;
		return $this;
	}

	private function _generateLegend($residentNumber)
	{
		if ($residentNumber === 0) {
			return 'You';
		} else {
			if ($this->_type === Model_UserResident::RENT_TYPE_COUPLE)
				return 'Spouse';
			else
				return 'Roommate #' . $residentNumber;
		}
	}

	private function _preValidation($data)
	{
		$members = $data['member'];
		foreach ($members as $key => $val) {
			$subform = $this->getSubForm('member_' . $key);
			$residentType = $val['resident_type'];
			// if student
			if ($residentType === Model_UserResident::TYPE_STUDENT) {
				$subform->getElement('job_title')->setRequired(false);
				
				if (Zend_Validate::is($val['monthly_income_guaranteed'], 'Float')) {
					$subform->getElement('monthly_income')->setRequired(false);
				}
			// if employee
			} elseif ($residentType === Model_UserResident::TYPE_EMPLOYEE) {
				$subform->getElement('employee_type')->setRequired(true);
			}
		}
	}

	public function isValid($data)
	{
		$this->_preValidation($data);
		
		return parent::isValid($data);
	}
}