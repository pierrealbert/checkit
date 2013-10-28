<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр
 * Date: 11.08.13
 * Time: 15:31
 * To change this template use File | Settings | File Templates.
 */

class User_Calendar_Calendar extends User_Calendar_Abstract
{
    public function __construct($options = array())
    {
        $this->setOptions($options);
    }

    /**
     * @return array
     */
    public function getEvents()
    {
        return array();
    }

    /**
     * @return DateTime
     */
    public function getDateTime()
    {
        return $this->getDateStart();
    }

    /**
     * @return DateTime
     */
    public function getDateStart()
    {
        if (is_string($this->_options['date_start'])) {

            $this->_options['date_start'] = new DateTime($this->_options['date_start']);

        } else if (!$this->_options['date_start'] instanceof DateTime) {

            $this->_options['date_start'] = new DateTime();
        }

        return $this->_options['date_start'];
    }

    /**
     * @return DateTime
     */
    public function getDateStop()
    {
        if (is_string($this->_options['date_stop'])) {

            $this->_options['date_stop'] = new DateTime($this->_options['date_stop']);

        } else if (!$this->_options['date_stop'] instanceof DateTime) {

            $this->_options['date_stop'] = new DateTime();
        }

        return $this->_options['date_stop'];
    }

    /**
     * @param $dateTime
     * @param array|null $options
     * @return User_Calendar_Month
     */
    public function createMonth($dateTime, $options = null)
    {
        $month = new User_Calendar_Month($dateTime);
        if (!$options && !empty($this->_options['month'])) {

            $options = $this->_options['month'];
        }
        if ($options) {

            $month->setOptions($options);
        }
        if ($this->hasEventManager()) {

            $month->setEventManager($this->getEventManager());
        }
        return $month;
    }

    public function render()
    {
        $result = '<div>';
        $month = $this->createMonth($this->getDateStart(), !empty($this->_options['month'])
            ? $this->_options['month'] : null);
        $lastId = (int)$this->getDateStop()->format('Ym');
        while ($lastId >= $month->getId()) {

            $result .= $month->render();
            $month = $month->next();
        }
        return $result . '</div>';
    }

    public function __toString()
    {
        return $this->render();
    }
}