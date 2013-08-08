<?php

/**
 * Settings view helper
 *
 * @category Ext
 * @package  View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_Settings extends Zend_View_Helper_Abstract
{
    /**
     *
     * @param string $section 
     * @param string $default default value if config option is not set     
     * @return mixed config option
     */
    public function settings($section = null, $default = null)
    {        
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        return $settings->get($section, $default);
    }
}
