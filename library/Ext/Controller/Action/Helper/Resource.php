<?php

/**
 * Allow access to bootstrap resource
 *
 * Example:
 *   $this->_helper->resource('acl');
 *
 * @category    Ext
 * @package     Ext_Controller
 * @subpackage  Action_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Controller_Action_Helper_Resource extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * @throws Exception
     * @param string $name
     * @return mixed
     */
    public function resource($name)
    {
        $frontController = Zend_Controller_Front::getInstance();
        
        $bootstrap = $frontController->getParam('bootstrap');

        if ($bootstrap) {
            if ($bootstrap->hasResource($name)) {
                $resource = $bootstrap->getResource($name);
            } else {
                throw new Ext_Exception('Unable to find ' . $name . ' resource');
            }
        } else {
            throw new Ext_Exception('Unable to find bootstrap');
        }

        return $resource;
    }

    /**
     * @param string $name
     * @return
     */
    public function direct($name)
    {
        return $this->resource($name);
    }
}
