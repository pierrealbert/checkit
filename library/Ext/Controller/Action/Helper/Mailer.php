<?php

/**
 * Manage application messages
 *
 * Example:
 *  $this->_helper->mailer->send('some@email.com', 'template_name');
 *
 * @category    Ext
 * @package     Ext_Controller
 * @subpackage  Ext_Controller_Action_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Controller_Action_Helper_Mailer extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Mail manager instance
     *
     * @var Ext_Mail_Manager
     */
    protected $_mailManager = null;

    /**
     *
     * @param Ext_Mail_Manager $mailManager
     * @return Ext_Controller_Action_Helper_Mailer
     */
    public function setMailManager(Ext_Mail_Manager $mailManager)
    {
        $this->_mailManager = $mailManager;
        return $this;
    }

    /**
     *
     * @return Ext_Mail_Manager
     */
    public function getMailManager()
    {
        if (!$this->_mailManager) {

            $frontController = Zend_Controller_Front::getInstance();
            
            $bootstrap = $frontController->getParam('bootstrap');

            if ($bootstrap) {
                if ($bootstrap->hasResource('mailer')) {
                    $this->_mailManager = $bootstrap->getResource('mailer');
                } else {
                    throw new Ext_Exception('Unable to find mailer resource');
                }
            } else {
                throw new Ext_Exception('Unable to find bootstrap');
            }
        }
        return $this->_mailManager;
    }

    /**
     * Add message to mailer queue
     *
     * @param string $toAddress
     * @param string $template
     * @param array  $data
     * @return Ext_Controller_Action_Helper_Mailer
     */
    public function send($toAddress, $template, $data = array())
    {
        $tempalte = new Ext_Mail_Template($template, $data);
        
        $this->getMailManager()->addMessage(array(
            'toAddress' => $toAddress,
            'subject'   => $tempalte->getSubject(),
            'message'   => $tempalte->getBody()
        ));

        return $this;
    }
}
