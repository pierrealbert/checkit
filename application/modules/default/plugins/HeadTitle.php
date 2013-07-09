<?php

/**
 * 
 */
class Plugin_HeadTitle extends Zend_Controller_Plugin_Abstract
{
    public function postDispatch(Zend_Controller_Request_Abstract $request)
    {
        if ($request->getModuleName() != 'default') {
            return false;
        }
        
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');

        $navigation = Zend_Registry::get('Zend_Navigation');

        $pages = $navigation->findAllByController($this->getRequest()->getControllerName());

        foreach ($pages as $page) {
            if ($page->getAction() == $this->getRequest()->getActionName()) {
                $label = $page->getLabel();
                if ($label) {
                    $viewRenderer->view->headTitle($viewRenderer->view->translate($label), 'PREPEND');
                }
            }
        }
    }
}
