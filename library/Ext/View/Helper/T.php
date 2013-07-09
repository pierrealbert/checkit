<?php

/**
 * Short proxy for translate helper
 *
 * @category    Ext
 * @package     View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_T extends Zend_View_Helper_Translate
{
    public function t($messageId)
    {
        $args = func_get_args();
        if (isset($args[1]) && is_string($args[1]) && strlen($args[1]) == 2) {
            $args[1] .= ' '; //this is hack because translate doesn't work with short strings
        }
        return call_user_func_array(array($this, 'translate'), $args);
    }

}