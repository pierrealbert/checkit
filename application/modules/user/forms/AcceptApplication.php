<?php

class User_Form_AcceptApplication extends Ext_Form
{
    protected $_applicationId = Null;
    
    public function init()
    {
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        $translator = $this->getTranslator();

        $this->setMethod('post');
        $this->setAttrib('id', 'application-accept-form');
        $this->setAction($this->getView()->url(array(
            'module'     => 'user',
            'controller' => 'my-account',
            'action'     => 'ajax-accept-candidate',
            'application'=> $this->_applicationId,
        ), null, true));
        
        $applicationStartTime = new Zend_Date($settings->get('applicationStartTime'), Zend_Date::TIMES);
        $applicationEndTime = new Zend_Date($settings->get('applicationEndTime'), Zend_Date::TIMES);
        $iterTime = $applicationStartTime;
        while ($applicationEndTime->compare($iterTime) == 1) {
            $startTimeOptions[$iterTime->toString('HH:mm:ss', 'iso')] = $iterTime->toString(Zend_Date::TIME_SHORT);
            $iterTime->add('00:15:00', Zend_Date::TIMES);
        }
        
        $this->addElement('select', 'visit_time', array(
            'label' => 'visit_time',
            'multiOptions' => $startTimeOptions,
            'decorators' => array(),
            'data-placeholder' => $translator->_('Form_ReserveCourt__start_time_placeholder'),
            'class' => 'chzn-select',
            'style' => 'width:110px;',
        ));

        $this->addElement('submit', 'accept', array(
            'label' => 'accept',
            'class' => 'btn btn-blue'
        ));
    }
    
    public function setApplicationId($value)
    {
        $this->_applicationId = (int)$value;
    }
}
