<?php

class Form_Apply extends Zend_Form
{
    protected $_disabledPropertyVisitDates = array();
    protected $_propertyId = Null;
    protected $_propertyVisitDates = array();
	protected $_checkedVisit = array();
    
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'apply-form');
        $this->setAction($this->getView()->url(array(
            'controller' => 'property',
            'action'     => 'ajax-apply',
            'item'       => $this->_propertyId,
        ), null, true));
        
        $this->addElement('radio', 'property_visit_date_id', array(
            'label'         => 'select_visit_date',
            'required'      => true,
            'multiOptions'  => $this->_propertyVisitDates,
            'disable'       => $this->_disabledPropertyVisitDates,
			'value'         => $this->_checkedVisit,
        ));

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $applicationStartTime = new Zend_Date($settings->get('applicationStartTime'), Zend_Date::TIMES);
        $applicationEndTime = new Zend_Date($settings->get('applicationEndTime'), Zend_Date::TIMES);
        $iterTime = $applicationStartTime;
        $startTimeOptions = array();
        $startTimeOptions['--'] = '--:--';
        while ($applicationEndTime->compare($iterTime) == 1) {
            $startTimeOptions[$iterTime->toString('HH:mm:ss', 'iso')] = $iterTime->toString(Zend_Date::TIME_SHORT);
            $iterTime->add('00:15:00', Zend_Date::TIMES);
        }

        $this->addElement('select', 'visit_time_begin', array(
            'multiOptions' => $startTimeOptions,
            'decorators' => array(array('ViewHelper'), array('HtmlTag', array('tag' => 'div', 'class' => 'select-performed'))),
            'class' => 'pretty',
        ));

        $this->addElement('select', 'visit_time_end', array(
            'multiOptions' => $startTimeOptions,
            'decorators' => array(array('ViewHelper'), array('HtmlTag', array('tag' => 'div', 'class' => 'select-performed'))),
            'class' => 'pretty',
        ));

        $this->addElement('submit', 'login', array(
            'label'    => 'sign_in',
            'id'       => 'login-button',
            'class'    => 'btn btn-blue'
        ));
    }

    public function setPropertyVisitDates($values)
    {
        $this->_propertyVisitDates = array();
        $this->_disabledPropertyVisitDates = array(); // array of ids
        foreach($values as $visitDate){
            $this->_propertyVisitDates[$visitDate->id] = $visitDate->availability;
            $availabilityDate = new Zend_Date($visitDate->availability);
            if (Zend_Date::NOW() > $availabilityDate) {
                $this->_disabledPropertyVisitDates[] = $visitDate->id;
            }
        }
    }

    public function setPropertyId($value)
    {
        $this->_propertyId = (int)$value;
    }

	public function setChecked($value)
	{
		$this->_checkedVisit = $value;
	}
}
