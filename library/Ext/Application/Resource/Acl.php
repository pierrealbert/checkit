<?php

/**
 * Resource for settings ACL options
 *
 * @category    Ext
 * @package     Ext_Application
 * @subpackage  Ext_Application_Resource
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Application_Resource_Acl extends Zend_Application_Resource_ResourceAbstract
{
    const DEFAULT_REGISTRY_KEY = 'Acl';

    /**
     * @var Ext_Acl
     */
    protected $_acl = null;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Ext_Acl
     */
    public function init()
    {
        return $this->getAcl();
    }

    /**
     * Retrieve ACL object
     *
     * @return Ext_Acl
     */
    public function getAcl()
    {
        if ($this->_acl === null) {

            $options = $this->getOptions();

            $this->_acl = new Ext_Acl();

            $this->_acl->addConfig($options);
            
            $key = (isset($options['registry_key']) && !is_numeric($options['registry_key']))
                ? $options['registry_key']
                : self::DEFAULT_REGISTRY_KEY;

            Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($this->_acl);
            
            Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole(
                Ext_Acl::DEFAULT_ROLE
            );       
            
            Zend_Registry::set($key, $this->_acl);
        }
       return $this->_acl;
    }
}
