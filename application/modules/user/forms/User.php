<?php

class User_Form_User extends Ext_Form
{
    protected $_currentUser = null;
    
    public function init()
    {
        $this->_currentUser = Zend_Controller_Action_HelperBroker::getStaticHelper('auth')
                ->getCurrUser();
        
        $this->setAttrib('id', 'form-profile');
        
        $this->_addPersonalInfoForm();
        $this->_addChangeEmail();
        $this->_addChangePassword();

        $this->addElement('submit', 'save', array(
            'label'     => 'update',
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }

    public function setDefaults(array $defaults)
    {
        if (!empty($defaults['id'])) {
            $this->getSubForm('change_email')->getElement('new_email')
                    ->getValidator('entityNotExists')
                    ->addExclusion('id', $defaults['id']);
        }

        return parent::setDefaults($defaults);
    }
    
    public function isValid($data) 
    {
        $currPasswordValidator = new Zend_Validate_Callback(array($this->_currentUser, 'isValidPassword'));
        $currPasswordValidator->setMessage('wrong_current_password');
                
        if (!empty($data['change_email']['new_email'])) {
            $changeEmailForm = $this->getSubForm('change_email');
            
            $changeEmailForm->getElement('current_password')
                    ->setRequired(true)
                    ->addValidator($currPasswordValidator);
        }
        
        if (!empty($data['change_pass']['new_password'])) {
            
            $changePassForm = $this->getSubForm('change_pass');
            
            $changePassForm->getElement('new_password')->setRequired(true);
            $changePassForm->getElement('current_password')
                    ->setRequired(true)
                    ->addValidator($currPasswordValidator);
            $changePassForm->getElement('confirm_password')->setRequired(true);           
        }        
            
        return parent::isValid($data);
    }

    protected function _addPersonalInfoForm()
    {        
        $profile = new Zend_Form_SubForm();       
        $profile->setLegend('my_profile');
        $profile->addElement('radio', 'title', array(
            'label'        => 'title',
            'multiOptions' => Model_User::getTitles()
        ));         

        $profile->addElement('text', 'first_name', array(
            'label'      => 'first_name',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));

        $profile->addElement('text', 'last_name', array(
            'label'      => 'last_name',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));
        
        $profile->addElement('text', 'address', array(
            'label'      => 'address',
            'required'   => true,
            'filters'    => array('StringTrim')
        ));        
        
        $profile->addElement('text', 'zip', array(
            'label'         => 'zip',
            'filters'       => array('StringTrim')
        ));        

        $profile->addElement('text', 'city', array(
            'label'         => 'city',
            'filters'       => array('StringTrim')
        ));
        
        $profile->addElement('text', 'phone', array(
            'label'         => 'phone',
            'filters'       => array('StringTrim')
        ));      
        
        $this->addSubForm($profile, 'profile');
    }
    
    protected function _addChangePassword()
    {
        $changePass = new Zend_Form_SubForm();       
        $changePass->setLegend('my_password');
        $changePass->addElement('password', 'current_password', array(
            'label' => 'current_password',
            'autocomplete' => 'off',
        ));         
        $changePass->addElement('password', 'new_password', array(
            'label' => 'new_password',
            'autocomplete' => 'off',
        ));   
        $changePass->addElement('password', 'confirm_password', array(
            'label' => 'confirm_password',
            'autocomplete' => 'off',
            'validators'   => array (
                new Zend_Validate_Identical('new_password')
            )            
        ));           
        
        $this->addSubForm($changePass, 'change_pass');        
    }
    
    protected function _addChangeEmail()
    {
        $changeEmail = new Zend_Form_SubForm();       
        $changeEmail->setLegend('my_email');
        $changeEmail->addElement('text', 'new_email', array(
            'label'      => 'new_email',
            'filters'    => array('StringTrim'),
            'validators' => array(
                'EmailAddress',
                array(
                    'entityNotExists',
                    true,
                    array('Model_User', 'email')
                )
            )
        ));          
        $changeEmail->addElement('password', 'current_password', array(
            'label' => 'current_password',
            'autocomplete' => 'off',
        ));         
        
        $this->addSubForm($changeEmail, 'change_email');           
    }
}
