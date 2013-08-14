<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр
 * Date: 11.08.13
 * Time: 15:40
 * To change this template use File | Settings | File Templates.
 */

class User_Calendar_Day
{

//    public static $days = array(
//        array('Sunday', 'Sun'),
//        array('Monday', 'Mon'),
//        array('Tuesday', 'Tue'),
//        array('Wednesday', 'Wed'),
//        array('Thursday', 'Thu'),
//        array('Friday', 'Fri'),
//        array('Saturday', 'Sat')
//    );

    public function getEvents()
    {

    }

    /**
     * @var User_Calendar_Day
     */
    private $_prev;

    /**
     * @var User_Calendar_Day
     */
    private $_next;

    /**
     * @var DateTime
     */
    private $_dateTime;

    /**
     * @var array
     */
    private $_options = array();

    public function __construct(DateTime $dateTime)
    {
        $dateTime->setTime(0, 0, 0);
        $this->_dateTime = $dateTime;
    }

    public function getDateTime()
    {
        return $this->_dateTime;
    }

    public function setOptions($options)
    {
        if ($options instanceof Zend_Config) {

            $options = $options->toArray();
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

    public function prev()
    {
        if (!$this->_prev) {

            $dt = clone $this->_dateTime;
            $dt->add(DateInterval::createFromDateString('-1 day'));
            $this->_prev = new User_Calendar_Day($dt);
            $this->_prev->setOptions($this->getOptions());
        }
        return $this->_prev;
    }

    public function next()
    {
        if (!$this->_next) {

            $dt = clone $this->_dateTime;
            $dt->add(DateInterval::createFromDateString('+1 day'));
            $this->_next = new User_Calendar_Day($dt);
            $this->_next->setOptions($this->getOptions());
        }
        return $this->_next;
    }


    public function render()
    {
        return '<td>' . $this->_dateTime->format('d') . '</td>';
    }


    public function __toString()
    {
        return $this->render();
    }
}