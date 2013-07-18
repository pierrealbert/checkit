<?php

class User_Form_UserResident extends Ext_Form
{
	protected $_count = 1;

	public function __construct($count = null) {
		if ($count) {
			$this->setCount($count);
		}

		$this->setAttrib('id', 'form-user-resident');

		parent::__construct();

		/*$this->addElement('radio', 'rent_type', array(
			'label' => 'rent_type',
			'required' => true,
			'separator' => '',
			'value' => Model_UserResident::RENT_TYPE_SINGLE,
			'multiOptions' => Model_UserResident::getRentTypes()
		));*/
		
		$this->addMemberForms();

		$this->addElement('submit', 'register', array(
            'label' => 'submit'
        ));
	}
	
	public function addMemberForms()
	{
		for($i=0; $i < $this->_count; $i++) {
			$key = "member_". $i;
			
			$subform = new User_Form_UserResidentItem(array("RowNumber" => ($i + 1)));
			$this->addSubForm($subform, $key)
				->getSubForm($key)
				->setLegend('Resident ' . ($i + 1));
		}
	}

	public function setCount($count)
	{
		$this->_count = $count;
		return $this;
	}
}