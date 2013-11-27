<?php

class User_Form_UserResident extends Ext_Form
{
    public function init()
    {
        $this->addElement('submit', 'infosSubmit', array(
            'label' => 'infosSubmit',
            'attribs' => array('class' => 'btn btn-medium btn-blue btn-arrow-next right'),
        ));

        $this->addElement('text', 'phone', array(
            /*'label' => 'Téléphone',*/
            'attribs' => array('class' => 'input-phone', 'style' => 'width: 300px;'),
            'required' => true,
        ));

        $this->addElement('radio', 'rent_type', array(
            'label' => 'Vous souhaitez candidater',
            'attribs' => array('class' => ''),
            'required' => true,
            'separator' => '',
            'multiOptions' => Model_UserResident::getRentTypes()
        ));

        $this->addElement('radio', 'resident_type', array(
            'label' => 'Profil',
            'attribs' => array('class' => ''),
            'required' => true,
            'separator' => '',
            'multiOptions' => Model_UserResident::getTypes()
        ));

        $this->addElement('text', 'monthly_income', array(
            'label' => 'Revenu brut mensuel',
            'attribs' => array('class' => 'input-money'),
        ));

        $this->addElement('text', 'job_title', array(
            'label' => 'Profession',
            'attribs' => array('class' => '', 'style' => 'width: 400px;'),
        ));

        $this->addElement('text', 'specify', array(
            'label' => 'Veuillez préciser',
            'attribs' => array('class' => '', 'style' => 'width: 400px;'),
        ));

        $this->addElement('radio', 'employee_type', array(
            'label' => 'Contrat',
            'separator' => '',
            'required' => true,
            'attribs' => array('class' => ''),
            'multiOptions' => Model_UserResident::getEmployeeTypes(),
        ));

    }
}