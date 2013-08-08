<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @category    Ext
 * @package     Ext_View
 * @subpackage  Ext_View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_FileUrl extends Zend_View_Helper_Abstract
{
    /**
     * Settings
     * 
     * @var array
     */
    protected $_settings = array();

    /**
     * URL to download file
     *
     * @var string
     */
    protected $_baseUrl = null;

    /**
     * Absolute path to files storage
     *
     * @var string
     */
    protected $_basePath = null;
    
    /**
     * Return download URL
     * 
     * @param string $file
     * @return string
     */
    public function fileUrl($file)
    {
        $fileUrl = $this->getBaseUrl()  . '/' . trim($file, '/');

        return $fileUrl;
    }

    public function getBaseUrl()
    {
        if (!$this->_baseUrl) {
            $settings = $this->getSettings();
            if (isset($settings['baseUrl'])) {
                $this->_baseUrl = $settings['baseUrl'];
            }
        }
        return $this->_baseUrl;
    }

    public function setBaseUrl($baseUrl)
    {
        $this->_baseUrl = $baseUrl;
        return $this;
    }

    public function getSettings()
    {
        if (!$this->_settings) {
            $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

            $this->_settings = $settings->get('files');
        }
        return $this->_settings;
    }

    public function getBasePath()
    {
        if (!$this->_basePath) {
            $settings = $this->getSettings();
            if (isset($settings['basePath'])) {
                $this->_basePath = $settings['basePath'];
            }
        }
        return $this->_basePath;
    }

    public function setBasePath($basePath)
    {
        $this->_basePath = $basePath;
        return $this;
    }
}