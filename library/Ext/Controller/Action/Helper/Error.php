<?php

/**
 * Manage application errors
 *
 * @category    Ext
 * @package     Ext_Controller
 * @subpackage  Ext_Controller_Action_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Controller_Action_Helper_Error extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Throws exception when the page is not found
     *
     * @param  string $message message
     * @return void
     * @throws Zend_Controller_Action_Exception
     */
    public function notFound($message = null)
    {
        throw new Zend_Controller_Action_Exception($message, 404);
    }

    /**
     * Throws exception when the page is unauthorized
     *
     * @param  string $message message
     * @return void
     * @throws Zend_Controller_Action_Exception
     */
    public function unauthorized($message = null)
    {
        throw new Zend_Controller_Action_Exception($message, 401);
    }

    /**
     * Throws exception when user does not have required permissions
     *
     * @param  string $message message
     * @return void
     * @throws Zend_Controller_Action_Exception
     */
    public function noPermissions($message = 'No Permissions')
    {
        throw new Zend_Controller_Action_Exception($message, 403);
    }
}