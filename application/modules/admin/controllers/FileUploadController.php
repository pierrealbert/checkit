<?php

class Admin_FileUploadController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $form       = new Admin_Form_File();
        $fileInfo   = array();

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
            $fileInfo = $this->_uploadFile($form);
        }

        $this->view->fileInfo   = $fileInfo;
        $this->view->form       = $form;
    }

    protected function _uploadFile(Zend_Form $form)
    {
        $form->file->receive('file');

        $fileInfo = $form->file->getFileInfo('file');

        return $fileInfo;
    }
}