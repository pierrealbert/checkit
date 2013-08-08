<?php

class Admin_View_Helper_SfMenu extends Zend_View_Helper_Abstract
{
    public function sfMenu()
    {
        $this->view->headLink()->appendStylesheet('/css/superfish.css');
        
        $this->view->jQuery()->addJavascriptFile('/js/hover-intent.js');
        $this->view->jQuery()->addJavascriptFile('/js/superfish.js');
 
        $this->view->jQuery()->addOnload("
            $(\"ul.sf-menu\").supersubs({
                minWidth:    12,
                maxWidth:    15,
                extraWidth:  1
            }).superfish({
                delay: 100,
                autoArrows: false,
                speed: 'fast'
            });
        ");

        return $this->view->navigation()->menu()->setUlClass('sf-menu');
    }
}