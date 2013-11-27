<?php

class User_Form_UserResident extends Ext_Form
{
    protected $_count = 1;
    protected $_type = Model_UserResident::RENT_TYPE_SINGLE;

    public function __construct($count = null, $type = null) {

        if ($type) {
            $this->setType($type);
        }

        $this->setAttrib('id', 'form-user-resident');

        parent::__construct();

        $this->addElement('text', 'phone', array(
            'label' => 'phone',
            'required' => true,
            'attribs' => array('class' => 'input-phone', 'style' => 'width: 300px;'),
        ));

        $this->addMemberForms($count);

        $this->addElement('hidden', 'rent_type', array(
            'value' => $type,
        ));
        $this->getElement('rent_type')->removeDecorator('label')
            ->removeDecorator('HtmlTag');

        $this->addElement('submit', 'register', array(
            'label' => 'submit'
        ));

    }

    public function addMemberForms($count)
    {
        for ($i = 0; $i < $count; $i++) {
            $num = $i + 1;
            $key = "member_" . $num;

            $subform = new User_Form_UserResidentItem(array("RowNumber" => $num));
            $this->addSubForm($subform, $key)
                ->getSubForm($key);
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

    public function setResidents($residents)
    {
        if (!$residents) {
            return false;
        }

        if (is_object($residents)) {
            $residents = $residents->toArray();
        }

        foreach ($residents as $key => $resident) {
            $subform = $this->getSubForm('member_' . ($key + 1));
            if ($subform) {
                $subform->setDefaults($resident);
            }
        }
    }
    /* old method */
    /*private function _generateLegend($residentNumber)
    {
        if ($residentNumber === 0) {
            $header = 'Moi';
        } else {
            if ($this->_type === Model_UserResident::RENT_TYPE_COUPLE)
                $header = 'Mon conjoint';
            else
                $header = 'Colocataire ' . $residentNumber;
        }

        return '<h3 class="white">'.$header.'</h3>';
    }*/

    private function _preValidation($data)
    {
        $members = $data['member'];

        foreach ($members as $key => $member) {
            $subform = $this->getSubForm('member_' . $key);
            if (!$subform) {
                continue;
            }
            $subform->getElement('monthly_income')->getValidator('Float')->setLocale('en');
            $subform->getElement('monthly_income_guaranteed')->getValidator('Float')->setLocale('en');

            if (isset($member['resident_type'])) {
                $residentType = $member['resident_type'];
            } else {
                $residentType = '';
            }

            // if student
            if ($residentType === Model_UserResident::TYPE_STUDENT) {
                $subform->getElement('job_title')->setRequired(false);
                $subform->getElement('employee_type')->setRequired(false);

                if (Zend_Validate::is($member['monthly_income_guaranteed'], 'Float')) {
                    $subform->getElement('monthly_income')->setRequired(false);
                }
            }

            // if employee
            if ($residentType === Model_UserResident::TYPE_EMPLOYEE) {
                $subform->getElement('employee_type')->setRequired(true);
            } else {
                $subform->getElement('employee_type')->setRequired(false);
            }

            if ($residentType === Model_UserResident::TYPE_OTHER) {
                $subform->getElement('resident_name')->setRequired(true);
            } else {
                $subform->getElement('resident_name')->setRequired(false);
            }
        }
    }

    public function isValid($data)
    {
        $this->_preValidation($data);

        return parent::isValid($data);
    }
}