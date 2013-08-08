<?php

/**
 * Determine whether the currently logged user has access to this resource
 *
 * @category    Ext
 * @package     Ext_Controller
 * @subpackage  Action_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Controller_Action_Helper_Acl extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var Zend_Acl_Role_Inferface|string $role
     */
    protected $_role;

    /**
     * @var Zend_Acl
     */
    protected $_acl;

    public function direct($resource, $privilege = null)
    {
        return $this->isAllowed($resource, $privilege);
    }

    public function isAllowed($resource, $privilege = null)
    {
        $allowed = $this->getAcl()->isAllowed($this->getRole(), $resource, $privilege);

        return $allowed;
    }

    /**
     * @param Zend_Acl_Role_Inferface|string $role
     * @return Ext_Controller_Action_Helper_Acl
     */
    public function setRole($role)
    {
        $this->_role = $role;

        return $this;
    }

    public function getRole()
    {
        if (null === $this->_role) {
            $auth = Zend_Controller_Action_HelperBroker::getStaticHelper('auth');
            
            if ($auth->isLoggedIn()) {
                $user = $auth->getCurrUser();
                if ($user && !empty($user->role)) {
                    $role = $user->role;
                }
            } else {
                $role = Ext_Acl::DEFAULT_ROLE;
            }
            $this->setRole($role);
        }
        return $this->_role;
    }

    /**
     * @throws Exception
     * @return Zend_Acl
     */
    public function getAcl()
    {
        if (null === $this->_acl) {
            $front = $this->getFrontController();
            $bootstrap = $front->getParam('bootstrap');
            if ($bootstrap) {
                $resourceName = 'acl';
                if ($bootstrap->hasResource($resourceName)) {
                    $acl = $bootstrap->getResource($resourceName);
                } else {
                    throw new Exception('Unable to find ' . $resourceName . ' resource');
                }
            } else {
                throw new Exception('Unable to find bootstrap');
            }

            $this->_acl = $acl;
        }

        return $this->_acl;
    }

    /**
     * @param Zend_Acl $acl
     * @return Ext_Controller_Action_Helper_Acl
     */
    public function setAcl(Zend_Acl $acl)
    {
        $this->_acl = $acl;
        return $this;
    }
}
