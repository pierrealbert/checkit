<?php

/**
 * Error controller.
 *
 * @category    Ext
 * @package     Controllers
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
abstract class Ext_Controller_Action_ErrorAbstract extends Zend_Controller_Action
{
    public function errorAction()
    {
        if ($this->getRequest()->isXmlHttpRequest() || $this->getRequest()->getParam('is_ajax_file_upload')) {
            Zend_Controller_Action_HelperBroker::getStaticHelper('Layout')->disableLayout();
        } else {
            Zend_Layout::getMvcInstance()->setLayout('light');
        }
        $errors = $this->_getParam('error_handler');

        $this->view->message = $errors->exception->getMessage();

        if ($errors->exception->getCode() == 404
                || $errors->type == Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER
                || $errors->type == Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION
        ) {
             // Page not found
            $this->getResponse()->setHttpResponseCode(404);
            $this->view->code = 404;
            $this->view->title = 'page_not_found';
        } elseif ($errors->exception->getCode() == 403) {
            // Access denied
            $this->getResponse()->setHttpResponseCode(403);
            $this->view->code = 403;
            $this->view->title = 'access_denied';
        } elseif ($errors->exception->getCode() == 401) {
            // Authentication required
            $this->_helper->ServiceMessenger->error('please_login');
            $this->_redirect('/');
        } else {
            // Application error
            $this->getResponse()->setHttpResponseCode(500);
            $this->view->code = 500;
            $this->view->title = 'an_error_occurred';
        }
    }
}