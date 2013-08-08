<?php

/**
 * Helper to prevent multi submit of forms
 * 
 * @category    Ext
 * @package     Ext_View
 * @subpackage  Helper_JQuery
 * @since       1.0
 * @version     $Revision: 1.0 $ 
 */
class Ext_View_Helper_DisableMultiSubmit extends Zend_View_Helper_Abstract
{
    /**
     * DisableMultiSubmit...
     *
     * @param string $selector Attach event to which element?
     */
    public function disableMultiSubmit($selector = '#buttons-element input')
    {
        $selector = (string) $selector;

        $this->view->JQuery()->addOnload("
            $('{$selector}').click(function(event) {    
                $(event.target).parents('form').find('{$selector}').attr('disabled', true);                   
            });");
    }
}
