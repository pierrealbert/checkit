<?php

/**
 * jQuery UI tabs support decorator
 *
 * @category    Ext
 * @package     Ext_Form
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Form_Decorator_UiTabs extends Zend_Form_Decorator_Abstract
{
    protected $_tabs = array();

    public function setTabs(Array $tabs)
    {
        $this->_tabs = $tabs;
    }
    
    public function appendTab(Array $options)
    {
        $this->_tabs[] = $options;
    }

    public function render($content)
    {
        $form = $this->getElement();
        $view = $form->getView();
        
        if (null === $view) {
            return $content;
        }

        $xhtml = '';

        // output the UI tabs
        $xhtml .= $view->uiTabs($this->_tabs, $content/*, $attribs*/);

        return $xhtml;
    }
}