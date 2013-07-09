<?php

/**
 * Initialize navigation container based on module name
 * 
 * @category    Ext_Controller_Plugin
 * @package     Navigation
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Controller_Plugin_Navigation extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_View_Abstract
     */
    protected $_view;
    
    /**
     * Initialize default navigation container for the whole site,
     * pass the container to navigation view helpers.
     *
     * @todo   Cache the navigation
     * @return Zend_Navigation
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        try {
            $moduleName = $this->getRequest()->getModuleName();

            $modulePath = Zend_Controller_Front::getInstance()
                ->getModuleDirectory($moduleName);

            $config = new Zend_Config_Ini($modulePath . '/configs/navigation.ini');

            $this->getView()->navigation(new Zend_Navigation($config));
        } catch (Zend_Config_Exception $e) {
            // TODO: log the error
        }
    }

    /**
     * @return Zend_View_Abstract
     */
    public function getView()
    {
        if (null == $this->_view) {
            $layout = Zend_Layout::getMvcInstance();
            $this->_view = $layout->getView();
        }

        return $this->_view;
    }

    /**
     * @param Zend_View_Abstract $view
     * @return void
     */
    public function setView(Zend_View_Abstract $view)
    {
        $this->_view = $view;
    }
}
