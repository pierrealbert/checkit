<?php

class User_Form_PaymentDirect extends Ext_Form
{
    public function init()
    {
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        
        $this->setMethod('post');
        
        $this->addElement('text', 'credit_card_number', array(
            'label'      => 'credit_card_number',
            'required'   => true,
            'filters'    => array('StringTrim'),
        ));
        
        $expirationMonthOptions = array(
            '' => $this->getTranslator()->translate('--')
        );
        $expirationMonthOptions = array_merge($expirationMonthOptions, range(1, 12));
        $this->addElement('select', 'expiration_month', array(
            'label'        => 'expiration_month',
            'multiOptions' => $expirationMonthOptions,
            'required'     => true,
            'filters'      => array('StringTrim'),
        ));
        
        $expirationYearOptions = array(
            '' => $this->getTranslator()->translate('----')
        );
        foreach (range(date('Y'), date('Y') + $settings->get('payment.maxExpirationYear')) as $year) {
            $expirationYearOptions[$year] = $year;
        }
        $this->addElement('select', 'expiration_year', array(
            'label'        => 'expiration_year',
            'multiOptions' => $expirationYearOptions,
            'required'     => true,
            'filters'      => array('StringTrim'),
        ));
        
        $this->addElement('text', 'cvv2', array(
            'label'      => 'cvv2',
            'required'   => true,
            'filters'    => array('StringTrim'),
        ));
        
        // TODO: fetch from profile info {{{
        $this->addElement('text', 'first_name', array(
            'label'      => 'first_name',
            'required'   => true,
            'filters'    => array('StringTrim'),
        ));
        
        $this->addElement('text', 'last_name', array(
            'label'      => 'last_name',
            'required'   => true,
            'filters'    => array('StringTrim'),
        ));
        
        $this->addElement('text', 'address1', array(
            'label'      => 'address1',
            'required'   => true,
            'filters'    => array('StringTrim'),
        ));
        
        $this->addElement('text', 'address2', array(
            'label'      => 'address2',
            'filters'    => array('StringTrim'),
        ));
        
        $this->addElement('text', 'city', array(
            'label'      => 'city',
            'required'   => true,
            'filters'    => array('StringTrim'),
        ));
        
        $this->addElement('text', 'state', array(
            'label'      => 'state',
            'required'   => true,
            'filters'    => array('StringTrim'),
        ));
        
        $this->addElement('text', 'zip', array(
            'label'      => 'zip',
            'required'   => true,
            'filters'    => array('StringTrim'),
        ));
        
        $countries = array(
            '' => $this->getTranslator()->translate('please_select')
        );
        $countries = $countries + Zend_Locale::getTranslationList('territory', null, 2);

        $this->addElement('select', 'country', array(
            'label'         => 'country',
            'required'      => true,
            'multiOptions'  => $countries

        ));
        // }}}
        
        $this->addElement('submit', 'save', array(
            'label'    => 'save',
        ));
	}
}
