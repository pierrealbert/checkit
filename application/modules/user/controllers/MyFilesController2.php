<?php
class User_MyFilesController extends Zend_Controller_Action
{
    protected $translator;

    public function init()
    {
        parent::init();

        $this->_helper->AjaxContext()
            ->addActionContext('ajax-infos-submit', 'html')

            ->initContext();

        $this->translator = Zend_Registry::get('Zend_Translate');
    }

    public function indexAction()
    {
        $this->view->headTitle('Publier une annonce');
        $this->view->contentTitle = 'Publier une annonce';
    }

    public function infosAction()
    {
        $this->view->headTitle('Publier une annonce', 'PREPEND');
        $this->view->contentTitle = 'Publier une annonce';

        $user = $this->_helper->auth->getCurrUser();

        $form = new User_Form_UserResident();

        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                echo "Good";
            } else {
                echo "Bad";
            }
        }

        $this->view->form = $form;

    }

    public function ajaxInfosSubmitAction()
    {
        $mainForm = new User_Form_UserResident();
        //valid form
        if ($this->_request->isPost()) {
            if ($mainForm->isValid($this->_request->getPost())) {
                echo "Good";
            } else {
                Zend_Debug::dump($mainForm->getMessages());
            }
        }

        $this->_helper->layout()->disableLayout();
    }
}