<?php

/**
 * Manage application messages
 *
 * Example:
 *  $this->_helper->message->error('message_translation_key', array('some values'));
 *  $this->_helper->message->warning('message_translation_key', array('some values'));
 *  $this->_helper->message->success('message_translation_key', array('some values'));
 *
 * @category    Ext
 * @package     Ext_Controller
 * @subpackage  Ext_Controller_Action_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Controller_Action_Helper_Messenger extends Zend_Controller_Action_Helper_Abstract implements IteratorAggregate
{
    /**
     * @var Zend_Controller_Action_Helper_FlashMessenger
     */
    protected $_flashMessenger = null;

    /**
     * Translation object
     * @var Zend_Translate
     */
    protected $_translator = null;

    const ERROR     = 'error';
    const SUCCESS   = 'success';
    const WARNING   = 'warning';
    const NOTICE    = 'notice';

    /**
     * Message namespaces
     *
     * @var array
     */
    protected $_namespaces = array(
        self::ERROR,
        self::SUCCESS,
        self::NOTICE,
        self::WARNING
    );

    /**
     * @return array of strings
     * @see $_namespaces
     */
    public function getNamespaces()
    {
        return $this->_namespaces;
    }

    /**
     * Constructor. Sets Zend_Controller_Action_Helper_FlashMessenger object.
     *
     * @return void
     */
    public function  __construct()
    {
        $this->_flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'FlashMessenger'
        );
    }

    /**
     * Returns all messages
     *
     * @return array
     */
    public function getMessages()
    {
        $messages = array();
        foreach ($this->_namespaces as $namespace) {

            $this->_flashMessenger->setNamespace($namespace);

            $namespaceMessages = array_merge(
                $this->_flashMessenger->getMessages(),
                $this->_flashMessenger->getCurrentMessages()
            );

            $this->_flashMessenger->clearCurrentMessages();
            if ($namespaceMessages) {
                $messages[$namespace] = $namespaceMessages;
            }
        }
        return $messages;
    }

    /**
     * getIterator() - complete the IteratorAggregate interface, for iterating
     *
     * @return ArrayObject
     */
    public function getIterator()
    {
        return new ArrayObject($this->getMessages());
    }

    /**
     * Set translation object
     *
     * @param  Zend_Translate_Adapter|null $translator
     * @return Zend_Validate_Abstract
     */
    public function setTranslator(Zend_Translate_Adapter $translator = null)
    {
        $this->_translator = $translator;
        return $this;
    }

    /**
     * Return translation object
     *
     * @return Zend_Translate_Adapter|null
     */
    public function getTranslator()
    {
        if (null === $this->_translator) {
            require_once 'Zend/Registry.php';
            if (Zend_Registry::isRegistered('Zend_Translate')) {
                $this->_translator = Zend_Registry::get('Zend_Translate');
            }
        }

        return $this->_translator;
    }

    /**
     * Adds a error message
     *
     * @param string|array  $messages
     * @param array         $messageVariables
     * @return void
     */
    public function error($messages = null, $messageVariables = array())
    {
        return $this->addMessage($messages, self::ERROR, $messageVariables);
    }

    /**
     * Adds a success message
     *
     * @param string|array  $messages
     * @param array         $messageVariables
     * @return void
     */
    public function success($messages = null, $messageVariables = array())
    {
        return $this->addMessage($messages, self::SUCCESS, $messageVariables);
    }

    /**
     * Adds a warning message
     *
     * @param string|array  $messages
     * @param array         $messageVariables
     * @return void
     */
    public function warning($messages = null, $messageVariables = array())
    {
        return $this->addMessage($messages, self::WARNING, $messageVariables);
    }

    /**
     * Adds a notice message
     *
     * @param string|array  $messages
     * @param array         $messageVariables
     * @return void
     */
    public function notice($messages = null, $messageVariables = array())
    {
        return $this->addMessage($messages, self::NOTICE, $messageVariables);
    }
    
    /**
     * Adds a message
     *
     * @param string|array  $messages
     * @param array         $messageVariables
     * @param string        $namespace Message namespase
     * @return void
     */
    public function addMessage($messages = null, $namespace = self::NOTICE, $messageVariables = array())
    {
        if (!is_array($messages)) {
            $messages = array($messages);
        }

        foreach ($messages as $message) {
            $message = $this->_createMessage($message, $messageVariables);

            $this->_flashMessenger->setNamespace($namespace)
                    ->addMessage($message);
        }
    }

    public function direct($messages = null, $namespace = self::NOTICE, $messageVariables = array())
    {
        $this->addMessage($messages, $namespace, $messageVariables);

        return $this;
    }

    /**
     * Adds a message
     *
     * @param string $message
     * @param array  $messageVariables
     * @return string
     */
    protected function _createMessage($message, $messageVariables = array())
    {

        if (null !== ($translator = $this->getTranslator())) {
            $message = $translator->translate($message);

            foreach ($messageVariables as $key => $property) {
                $messageVariables[$key] = $translator->translate($property);
            }
        }

        foreach ($messageVariables as $ident => $property) {
            $message = str_replace("%$ident%", (string)$property, $message);
        }


        return $message;
    }
}
