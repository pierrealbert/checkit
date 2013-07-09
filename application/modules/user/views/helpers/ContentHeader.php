<?php

class User_View_Helper_ContentHeader extends Zend_View_Helper_Abstract
{
    public function contentHeader($title, $buttons = array(), $formId = null)
    {
        return $this->view->partial('_partials/content-header.phtml', array(
            'title'   => $title,
            'buttons' => $buttons,
            'formId'  => $formId
        ));
    }
}