<?php

/**
 * @category    Ext
 * @package     Ext_Auth
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
interface Ext_Auth_Hash_Interface
{
    public function getHash($password, $salt = null);

    public function validateHash($password, $hash);
}