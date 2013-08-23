<?php

class Form_Issue extends Zend_Form
{
	public function init(){
	    
	    $this->addElement('textarea', 'issueText', array(
		'cols'	     => 40,
		'rows'	     => 3,
		'label'      => 'text',
		'required'   => true,
		'filters'    => array('StringTrim', 'StripTags'),
		'validators' => array('NotEmpty')
	    ));
	


	    $this->addElement('hidden', 'property_id', array(
		'value' =>  $this->fillPropertyId(),
		'required' => true,
		'filters'    => array(
		   'int', 
		),
		'validators' => array(
		    'NotEmpty', 'int'
		)
	    ));
	    $this->addElement('hidden', 'subject_id', array(
		'value' => "",
		'required' => true,
		'filters'    => array(		
		     'int', 
		),
		'validators' => array(
		     'NotEmpty', 'int'
		)
	    ));
 
	}
	/**
	 * get current item value from url and set it to input 
	 * @return type integer
	 */
	public function fillPropertyId()
	{
	    return  Zend_Controller_Front::getInstance()->getRequest()->getParam( 'item', null ) ;
	}
}

?>
