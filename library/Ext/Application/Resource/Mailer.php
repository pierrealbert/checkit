<?php

/**
 * Resource for settings Mailer options. Mailer queue and mail template paths.
 *
 * @category    Ext
 * @package     Ext_Application
 * @subpackage  Ext_Application_Resource
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Application_Resource_Mailer extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Mail manager instance
     *
     * @var Ext_Mail_Manager
     */
    protected $_mailer;

    /**
     * Defined by Zend_Application_Resource_Resource
     *
     * @return Ext_Mail_Manager
     */
    public function init()
    {
        return $this->getMailer();
    }

    /**
     * Retrieve Mail Manager object
     *
     * @return Ext_Mail_Manager
     */
    public function getMailer()
    {
        if (!$this->_mailer) {

            $options = $this->getOptions();

            $this->_mailer = new Ext_Mail_Manager();
            $this->_mailer->setOptions($options);

            $bootstrap = $this->getBootstrap();

            if ($bootstrap->hasResource('mail') || $bootstrap->hasPluginResource('mail')) {
                $bootstrap->bootstrap('mail');
            }

            if ($bootstrap->hasResource('view') || $bootstrap->hasPluginResource('view')) {
                $bootstrap->bootstrap('view');

                $view = $bootstrap->getResource('view');
            } else {
                $view = new Zend_View();
            }

            if (isset($options['templatePaths'])) {
                $templates = (array) $options['templatePaths'];

                foreach ($templates as $template) {
                    $view->addScriptPath($template);
                }
            }

            Ext_Mail_Template::setView($view);
        }
        
        return $this->_mailer;
    }
}
