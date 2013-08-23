<?php

class Form_Issue extends Zend_Form
{
	public function init(){
	    
	    $this->addElement('textarea', 'issueText', array(
		'cols'	     => 40,
		'rows'	     => 4,
		'label'      => 'text',
		'required'   => true,
		'filters'    => array('StringTrim', 'StripTags'),
		'validators' => array('NotEmpty')
	    ));
	

//	   $this->addElement('submit', 'submit', array(
//		'label'    => 'send',
//	    ));
// 
	}
}

?>
