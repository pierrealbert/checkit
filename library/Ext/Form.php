<?php

/**
 * Base class for all the forms in the application
 *
 * @category    Ext
 * @package     Ext_Form
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
abstract class Ext_Form extends Zend_Form
{
    /**
     * Initialize form (used by extending classes)
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->setAttrib('id', strtolower(get_class($this)));
    }

    public function  __construct($options = null)
    {
        $this->addPrefixPath('Ext_Form_', dirname(__FILE__) . '/Form/');
        $this->addPrefixPath('ZendX_JQuery_Form', dirname(__FILE__) . '/../ZendX/JQuery/Form');

        // add prefix path for Ext elements
        $this->addElementPrefixPath('Ext_Form_Element_', dirname(__FILE__) . '/Form/Element/');

        // add prefix path for Ext decorators
        $this->addElementPrefixPath('Ext_Form_Decorator_', dirname(__FILE__) . '/Form/Decorator/', Zend_Form_Element::DECORATOR);

        // add prefix path for Ext validators
        $this->addElementPrefixPath('Ext_Validate_', dirname(__FILE__) . '/Validate/', Zend_Form_Element::VALIDATE);

        // add prefix path for Ext filters
        $this->addElementPrefixPath('Ext_Filter_', dirname(__FILE__) . '/Filter/', Zend_Form_Element::FILTER);

        parent::__construct($options);
    }

    public function addDisplayGroupButtons($buttons, $groupName = 'buttons', $decorators = array())
    {
        foreach ($buttons as $button) {
            $this->getElement($button)->setDecorators(array('ViewHelper'));
        }

        $defaultDecorators = array(
            'FormElements',
            'DtDdWrapper',
        );

        if (!$decorators) {
            $decorators = $defaultDecorators;
        }

        $this->addDisplayGroup($buttons, $groupName, array(
            'decorators' => $decorators
        ));
    }
    
    public function isValid($data) 
    {
        $isValid = parent::isValid($data);
        $this->highlightErrorElements();
        return $isValid;
    }


    public function highlightErrorElements()
    {
        foreach($this->getElements() as $element) {
            if ($element->hasErrors()) {
                $currentClass = $element->getAttrib('class');
                $element->setAttrib('class', trim($currentClass . ' input-error'));
            }
        }
    }    
}
