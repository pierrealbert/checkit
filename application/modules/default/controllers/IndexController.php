<?php

class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->view->homeHeader = true;

        $form = new Form_SearchMain();
        $this->view->form = $form;
    }

	public function markupAction()
	{
		$file = $this->_request->getParam('file');

		// simple validation
		if ($file && !preg_match('/^[\w- ]+\.phtml$/', $file))
			$file = null;

		$tpl = $file ? $file : 'index';
		$this->render('markup/'.str_replace('.phtml', '', $tpl));
	}
}

