<?php

class ContentController extends Zend_Controller_Action
{
    public function viewAction()
    {
        $pageSlug = $this->_getParam('page', '');

        $pageContent = Doctrine::getTable('Model_PageContent')->findOneBySlug($pageSlug);

        if (!$pageContent) {
            $this->_helper->error->notFound();
        }

        $this->view->pageContent = $pageContent;
    }
}

