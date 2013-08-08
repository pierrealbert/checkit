<?php

/**
 * Mail manager component. Using for mass mail sending. 
 *
 * @category    Ext
 * @package     Mail
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Mail_Manager
{
    /**
     *
     * @var Ext_Queue
     */
    protected $_queue;

    /**
     *
     * @var Zend_Mail
     */
    protected $_sender;

    /**
     * Whether or not emails should be sent to recipients. Should be set to true in debug mode.
     *
     * @var bool
     */
    protected $_sendToRecipients = true;

    /**
     * Email that duplications of all emails should be sent to. Can be used in purpose of monitoring.
     *
     * @var string
     */
    protected $_duplicateTo = null;

    /**
     * Options
     * 
     * @var array
     */
    protected $_options = array(        
        'queue' => array(
            'driverOptions' => array(
                'host'       => null,
                'username'   => null,
                'password'   => null,
                'dbname'     => null,
                'type'       => 'pdo_mysql',
                'charset'    => 'UTF8',
                'persistent' => false
            ),
            'name' => 'mails'
        )
    );

    public function setOptions($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        $this->_options = array_merge($this->_options, $options);
    }

    /**
     *
     * @param $queue Zend_Queue
     */
    public function setQueue(Zend_Queue $queue)
    {
        $this->_queue = $queue;
        return $this;
    }

    /**
     *
     * @return Zend_Queue
     */
    public function getQueue()
    {
        if (!$this->_queue) {
            $queueOptions = array();

            if (isset($this->_options['queue'])) {
                $queueOptions = $this->_options['queue'];
            }
            $this->_queue = new Ext_Queue($queueOptions);
        }
        return $this->_queue;
    }

    /**
     *
     * @param $sender Zend_Mail
     */
    public function setSender(Zend_Mail $sender)
    {
        $this->_sender = $sender;
        return $this;
    }

    /**
     *
     * @return Zend_Mail
     */
    public function getSender()
    {
        if (!$this->_sender) {
            $this->_sender = new Zend_Mail('UTF-8');
        }
        return $this->_sender;
    }

    /**
     * Set the sendToRecipients flag and retrieve current status
     *
     * @param boolean $flag
     * @return boolean
     */
    public function sendToRecipients($flag = null)
    {
        if ($flag !== null) {
            $this->_sendToRecipients = (bool) $flag;
            return $this;
        }

        return $this->_sendToRecipients;
    }

    /**
     * Set the email used as receiver for email duplications
     *
     * @param string|null $email
     */
    public function setDuplicateTo($email)
    {
        $this->_duplicateTo = $email;
        return $this;
    }

    /**
     * Return the email used as receiver for email duplications
     *
     * @retrun string|null
     */
    public function getDuplicateTo()
    {
        return $this->_duplicateTo;
    }

    /**
     * Add a message into the queue
     * $data = array(
     *
     * )
     * @param array $data
     */
    public function addMessage($data)
    {
        $messageBody = new Ext_Queue_Message_Body();

        foreach ($data as $key => $value) {
            $messageBody->set($key, $value);
        }

        $this->getQueue()->send($messageBody);
    }

    /**
     * Send all messages from the queue
     */
    public function sendMessages()
    {
        while ($message = $this->getQueue()->receive()->current()) {

            $messageBody = $message->getBody();

            $sender = $this->getSender();

            if ($this->sendToRecipients() && $toAddress = $messageBody->get('toAddress')) {
                $sender->addTo($toAddress);
            }
            if ($duplicateTo = $this->getDuplicateTo()) {
                $sender->addBcc($duplicateTo);
            }

            if ($sender->getRecipients()) {

                $sender->setSubject($messageBody->get('subject'));
                $sender->setBodyHtml($messageBody->get('message'));

                if ($from = $messageBody->get('from')) {
                    $sender->setFrom($from['email'], $from['name']);
                } elseif ($sender->getDefaultFrom()) {
                    $sender->setFromToDefaultFrom();
                }

                if ($replyTo = $messageBody->get('reply-to')) {
                    $sender->setReplyTo($replyTo['email'], $replyTo['name']);
                } elseif ($sender->getDefaultReplyTo()) {
                    $sender->setReplyToFromDefault();
                }

                $sender->send();
            }

            $this->_clearSender($sender);

            $this->getQueue()->deleteMessage($message);
        }
    }

    protected function _clearSender(Zend_Mail $sender)
    {
        $sender->clearRecipients();
        $sender->clearSubject();
        $sender->clearDate();
        $sender->clearFrom();
        $sender->clearReplyTo();
    }
}
