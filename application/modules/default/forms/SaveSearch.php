<?php

class Form_SaveSearch extends Ext_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'form-save-search');
        $this->setAction($this->getView()->url(array(
            'controller' => 'search',
            'action'     => 'ajax-save-search'
        ), null, true));
        
        $this->addElement('hidden', 'search_id', array(
            'required' => true,
            'filters'  => array('StringTrim')
        ));
        
        $this->getElement('search_id')
                ->removeDecorator('label')
                ->removeDecorator('HtmlTag');
        
        $this->addElement('text', 'name', array(
            'label'    => 'search_name',
            'required' => true,
            'filters'  => array('StringTrim')
        ));
        
        $this->addElement('submit', 'save', array(
            'label'     => 'save_search',
            'class'     => 'btn btn-blue',
            'style'     => 'width: 460px;'
        ));
	}

}
