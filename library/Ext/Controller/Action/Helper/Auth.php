<?php

/**
 * Helper_Auth
 *
 * @category    Ext
 * @package     Ext_Controller
 * @subpackage  Ext_Controller_Action_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Controller_Action_Helper_Auth extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Doctrine model name
     *
     * @var string
     */
    protected $_modelName = 'Model_User';

    /**
     * Current user instance
     *
     * @var Doctrine_Record
     */
    protected $_currUser = null;

    /**
     * Auth Hash instance
     * 
     * @var Ext_Auth_Hash_Interface
     */
    protected $_authHash = null;

    /**
     * Checking if user logged in
     *
     * @return bool
     */
    public function isLoggedIn()
    {
        return Zend_Auth::getInstance()->hasIdentity();
    }

    /**
     * Return ID of logged in user
     *
     * @return int|null
     */
    public function getCurrUserId()
    {
        if ($this->isLoggedIn()) {
            return Zend_Auth::getInstance()->getIdentity();
        }
        return null;
    }
    
    /**
     * Return current user instance
     *
     * @return int|null
     */
    public function getCurrUser()
    {
        if ($this->isLoggedIn()) {
            if (!$this->_currUser) {
                $this->_currUser =  Doctrine::getTable($this->_modelName)
                                                ->find($this->getCurrUserId());
            }
        }
        return $this->_currUser;
    }

    /**
     *
     * @return Ext_Auth_Hash_Interface
     */
    public function getAuthHash()
    {
        if (!$this->_authHash) {
            $this->_authHash = new Ext_Auth_Hash();

            $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

            $authSettings = $settings->get('security');
            
            if ($authSettings) {
                $this->_authHash->setOptions($authSettings);
            }
        }
        return $this->_authHash;
    }

    /**
     *
     * @param Ext_Auth_Hash_Interface $hash
     * @return Doctrine_Record
     */
    public function setAuthHash(Ext_Auth_Hash_Interface $hash)
    {
        $this->_authHash = $authHash;
        return $this;
    }
}