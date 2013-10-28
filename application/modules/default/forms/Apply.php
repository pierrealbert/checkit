<?php

class Form_Apply extends Zend_Form
{
    protected $_disabledPropertyVisitDates = array();
    protected $_propertyId = Null;
    protected $_propertyVisitDates = array();
    
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
}
