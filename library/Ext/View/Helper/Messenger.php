<?php

/**
 * Manage application messages
 *
 * @category    Ext
 * @package     Ext_View
 * @subpackage  Ext_View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_Messenger extends Zend_View_Helper_Abstract
{
    /**
     *
     * @param IteratorAggregate
     * @return string
     */
    protected $_container;

    /**
     * Render the message box
     * @param  string $partial View partial
     * @return string
     * @throws Zend_View_Exception
     */
    public function messenger($partial = null)
    {
        if ($partial === null) {
            $this->view->addBasePath(dirname(__FILE__) . '/files');

            $partial = 'messenger/default.phtml';
        }

        return $this->view->partial($partial, array(
            'messages' => $this->getContainer()->getIterator(),
            'namespaces' => $this->getContainer()->getNamespaces()
        ));
    }

    /**
     *
     * @return IteratorAggregate
     */
    public function getContainer()
    {
        if (!$this->_container) {
            $this->_container = Zend_Controller_Action_HelperBroker::getStaticHelper(
                'messenger'
            );
        }
        return $this->_container;
    }

    /**
     * @param IteratorAggregate $container
     */
    public function setContainer(IteratorAggregate $container)
    {
        $this->_container = $container;
    }
}
