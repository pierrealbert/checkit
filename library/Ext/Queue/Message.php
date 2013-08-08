<?php

/**
 * Ext Queue exception
 *
 * @category    Ext
 * @package     Queue
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Queue_Message extends Zend_Queue_Message
{
    /**
     * We adjusted the constructor to accept both an array and an object.
     */
    public function __construct($mixed)
    {
        // we still have to support the code in Zend_Queue::receive that passes in an array
        if (is_array($mixed)) {
            parent::__construct($mixed);
        } elseif (is_object($mixed)) {
            $this->setBody($mixed);
            $this->_connected = false;
        }
    }

    /**
     * We need to get the underlying body as a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->_data['body'];
    }

    /**
     * Sets the message body
     *
     * @param serializable $mixed
     */
    public function setBody($mixed)
    {
        $this->_data['body'] = serialize($mixed);
    }

    /**
     * Gets the message body
     *
     * @return $mixed
     */
    public function getBody()
    {
        return unserialize($this->_data['body']);
    }

    /**
     * Deletes the message.
     *
     * Note you cannot be disconnected from queue.
     *
     * $throw is set to to true, because most of the time you want to know if
     * there is an error.  However, in Custom_Messages::__destruct() exceptions
     * cannot be thrown.
     *
     * These does not create a circular reference loop. Because deleteMessage
     * asks the queue service to delete the message, the message located here
     * is NOT deleted.
     *
     * @param boolean $throw defaults to true.  Throw a message if there is an error
     *
     * @throws Zend_Queue_Exception if not connected
     */
    public function delete($throw = true)
    {
        if ($this->_connected) {
            if ($this->getQueue()->getAdapter()->isSupported('deleteMessage')) {
                $this->getQueue()->deleteMessage($this);
            }
        } elseif ($throw) {
            throw new Core_Queue_Exception('Disconnected from queue. Cannot delete message from queue.');
        }
    }
}
