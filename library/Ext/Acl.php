<?php

/**
 * @see Zend_Acl
 */
require_once 'Zend/Acl.php';

/**
 * @see Zend_Acl_Exception
 */
require_once 'Zend/Acl/Exception.php';

/**
 * Extension for Zend Acl component. Allow to set user permissions settings via config settings
 *
 * @category    Ext
 * @package     Acl
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Acl extends Zend_Acl
{
    const ALLOW = 'allow';
    const DENY  = 'deny';

    /**
     * Default role when unable to define current role
     */
    const DEFAULT_ROLE = 'guest';
    
    /**
     * Add ACL config
     *
     * Example INI:
     *
     * roles[] = "member"
     * roles[] = "admin"
     *
     * resourses[] = login
     * resourses[] = account
     *
     * rules.guest.allow = ""
     * rules.guest.deny.myaccount  = ""
     * rules.guest.deny.products[] = "edit"
     * rules.guest.deny.products[] = "delete"
     *
     * @param  Zend_Config|array $config  Configurations
     * @param  string            $section Name of the config section containing ACL definitions
     * @return void
     */
    public function addConfig($config, $section = null)
    {
        if ($config instanceof Zend_Config) {
            $config = $config->toArray();
        }

        if ($section !== null) {
            if (!isset($config[$section])) {
                throw new Zend_Acl_Exception("No configuration in section '{$section}'");
            }
            $config = $config[$section];
        }

        if (!empty($config['resources'])) {
            $this->_addResources($config['resources']);
        }

        if (!empty($config['roles'])) {
            $this->_addRoles($config['roles']);
        }

        if (!empty($config['rules'])) {
            $this->_addRules($config['rules']);
        }
    }

    /**
     * Add ACL roles
     *
     * @param  array $roles
     * @return void
     */
    protected function _addRoles($roles)
    {
        foreach ($roles as $role) {
            $this->addRole(new Zend_Acl_Role($role));            
        }
    }

    /**
     * Add ACL resources
     *
     * @param  array $resources
     * @return void
     */
    protected function _addResources($resources)
    {        
        foreach ($resources as $resource) {
            $this->add(new Zend_Acl_Resource($resource));
        }
    }
    
    /**
     * Add ACL rules
     *
     * @param  array $rules
     * @return void
     */
    protected function _addRules($rules)
    {
       foreach ($rules as $role => $roleRules) {

            if (!$this->hasRole($role)) {
                throw new Zend_Acl_Exception("Role '{$role}' dosn't exists");
            }
            
            foreach ($roleRules as $type => $typeRules) {

                if (is_array($typeRules)) {
                    foreach ($typeRules as $resourse => $privileges) {
                        if (!$this->has($resourse)) {
                            throw new Zend_Acl_Exception("Resourse '{$resourse}' dosn't exists");
                        }
                        if (!$privileges) {
                            $privileges = null;
                        }
                        $this->_addRule($type, $role, $resourse, $privileges);
                    }
                } else {
                    $this->_addRule($type, $role);
                }
            }
        }
    }

    /**
     * Add ACL rule 
     *
     * @param  string $type        Rule type (allow or deny)
     * @param  string $role
     * @param  string $resource
     * @param  array  $privileges
     * @throws Zend_Acl_Exception
     * @return void
     */
    protected function _addRule($type, $role, $resourse = null, $privileges = null)
    {
        if ($type == self::ALLOW) {
            return $this->allow($role, $resourse, $privileges);
        } elseif ($type == self::DENY) {
            return $this->deny($role, $resourse, $privileges);
        }        
        throw new Zend_Acl_Exception("Rule type '{$type}' dosn't exists");
    }
}
