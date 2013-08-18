<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр
 * Date: 17.08.13
 * Time: 17:10
 * To change this template use File | Settings | File Templates.
 */

abstract class User_Calendar_Abstract
{
    /**
     * @return array
     */
    abstract public function getEvents();

    /**
     * @return DateTime
     */
    abstract public function getDateTime();

    /**
     * @var User_Calendar_EventManager
     */
    private $_em;

    /**
     * @var array
     */
    protected $_options = array();

    public function setEventManager(User_Calendar_EventManager $em)
    {
        $em->addTime($this->getDateTime());
        $end = clone $this->getDateTime();
        $end->modify('+1 month')->modify('-1 day');
        $em->addTime($end);
        $this->_em = $em;
    }

    /**
     * @return bool
     */
    public function hasEventManager()
    {
        return !empty($this->_em);
    }

    /**
     * @return User_Calendar_EventManager
     * @throws Exception
     */
    public function getEventManager()
    {
        if ($this->_em) {

            return $this->_em;
        }
        throw new Exception('Event manager is not set');
    }

    public function setOptions($options)
    {
        if ($options instanceof Zend_Config) {

            $options = $options->toArray();
        }
        if (isset($options['event_manager'])) {

            $this->setEventManager($options['event_manager']);
        }
        $this->_options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->_options;
    }


}