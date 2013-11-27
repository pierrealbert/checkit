<?php

class User_Form_User extends Ext_Form
{
    protected $_currentUser = null;
    
    public function init()
    {
        $this->_currentUser = Zend_Controller_Action_HelperBroker::getStaticHelper('auth')
                ->getCurrUser();
        ///PersonalInfoForm///
        $this->addElement('radioButtons', 'title', array(
            'label'        => 'title',
            'multiOptions' => Model_User::getTitles()
        ));         

        $this->addElement('text', 'first_name', array(
            'label'      => 'first_name',
            'required'   => true,
            'placeholder'=> 'Prénom actuel',
            'filters'    => array('StringTrim')
        ));

        $this->addElement('text', 'last_name', array(
            'label'      => 'last_name',
            'required'   => true,
            'placeholder'=> 'Nom actuel',
            'filters'    => array('StringTrim')
        ));
        
        $this->addElement('text', 'address', array(
            'label'      => 'address',
            'required'   => true,
            'placeholder'=> 'Numéro et voie',
            'filters'    => array('StringTrim')
        ));        
        
        $this->addElement('text', 'zip', array(
            'label'         => 'zip',
            'placeholder'	=> 'Code postal',
            'style'			=> 'width:100px',
            'filters'       => array('StringTrim')
        ));        

        $this->addElement('text', 'city', array(
            'label'         => 'city',
            'placeholder'	=> 'Ville',
            'style'			=> 'width:210px',
            'filters'       => array('StringTrim')
        ));
        
        $this->addElement('text', 'phone', array(
            'label'         => 'phone',
            'placeholder'	=> 'Numéro de téléphone',
            'filters'       => array('StringTrim')
        )); 
        ///
        /** ChangePassword **/			
		$this->addElement('password', 'current_password', array(
            'label' => 'current_password',
            'placeholder'	=> '••••••••',
            'autocomplete' => 'off',
        ));         
        $this->addElement('password', 'new_password', array(
            'label' => 'new_password',
            'placeholder'	=> '••••••••',
            'autocomplete' => 'off',
        ));   
        $this->addElement('password', 'confirm_password', array(
            'label' => 'confirm_password',
            'placeholder'	=> '••••••••',
            'autocomplete' => 'off',
            'validators'   => array (
                new Zend_Validate_Identical('new_password')
            )            
        ));           
			
		$this->addElement('text', 'new_email', array(
            'label'      => 'new_email',
            'filters'    => array('StringTrim'),
            'placeholder'	=> 'Nouvelle adresse email',
            'validators' => array(
                'EmailAddress',
                array(
                    'entityNotExists',
                    true,
                    array('Model_User', 'email')
                )
            )
        ));      
            
		$this->addElement('password', 'email_current_password', array(
            'label' => 'current_password',
            'placeholder'	=> '••••••••',
            'autocomplete' => 'off',
        ));           
        
        $this->setAttrib('id', 'form-profile');

        $this->addElement('submit', 'save', array(
            'label'     => 'Enregistrer les modifications',
            'class'     => 'btn btn-red right'
        ));
        /* ui-state-default ui-corner-all*/
    }

    public function setDefaults(array $defaults)
    {
        if (!empty($defaults['id'])) {
            $this->getElement('new_email')
                    ->getValidator('entityNotExists')
                    ->addExclusion('id', $defaults['id']);
        }

        return parent::setDefaults($defaults);
    }
    
    public function isValid($data) 
    {
        $currPasswordValidator = new Zend_Validate_Callback(array($this->_currentUser, 'isValidPassword'));
        $currPasswordValidator->setMessage('wrong_current_password');
                
        if (!empty($data['new_email'])) {           
            $this->getElement('email_current_password')
                    ->setRequired(true)
                    ->addValidator($currPasswordValidator);
        }
        
        if (!empty($data['new_password'])) {
            
            $this->getElement('new_password')->setRequired(true);
            $this->getElement('current_password')
                    ->setRequired(true)
                    ->addValidator($currPasswordValidator);
            $this->getElement('confirm_password')->setRequired(true);           
        }        
            
        return parent::isValid($data);
    }
}
