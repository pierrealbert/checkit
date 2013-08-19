<?php
/**
 * Class User_Calendar_EventManager_Doctrine
 */
class User_Calendar_EventManager
{
    /**
     * @var DateTime
     */
    private $timeStart;

    /**
     * @var DateTime
     */
    private $timeStop;

    private $_brokers = array();

    private $isActualData = true;

    /**
     * @var array
     */
    private $_events = array();

    /**
     * @var array
     */
    private $_eventsPerMonths = array();

    public function __construct($brokers = null)
    {
        if ($brokers) {

            $this->setBrokers($brokers);
        }
    }

    public function setBrokers($brokers)
    {
        if (is_array($brokers)) {

            $this->_brokers = array();
            foreach ($brokers as $broker) {

                $this->addBroker($broker);
            }
        } else if ($brokers instanceof User_Calendar_EventBroker_Interface) {

            $this->_brokers = array($brokers);
        }
        return $this;
    }

    public function addBroker($broker)
    {
        if ($broker instanceof User_Calendar_EventBroker_Interface) {

            $this->_brokers[] = $broker;
        }
        return $this;
    }

    public function setTimeStart(DateTime $dateTime)
    {
        $dateTime->setTime(0, 0, 0);
        if (!$this->timeStart || $this->timeStart > $dateTime) {

            $this->isActualData = false;
            $this->timeStart = $dateTime;
        }
        return $this;
    }

    public function setTimeStop(DateTime $dateTime)
    {
        $dateTime->setTime(23, 59, 59);
        if (!$this->timeStop || $this->timeStop < $dateTime) {

            $this->isActualData = false;
            $this->timeStop = $dateTime;
        }
        return $this;
    }

    public function addTime(DateTime $dateTime)
    {
        $this->_actualizeInterval($dateTime);
    }

    private function _actualizeInterval(DateTime $dateTime)
    {
        if ($this->timeStart > $dateTime) {

            $this->isActualData = false;
            $this->timeStart = $dateTime;
        } else if ($this->timeStop < $dateTime) {

            $this->isActualData = false;
            $this->timeStop = $dateTime;
        } elseif (!$this->timeStart) {

            $this->timeStart = $dateTime;
        } elseif (!$this->timeStop) {

            $this->isActualData = false;
            $this->timeStop = $dateTime;
        }
        return $this;
    }

    public function getEvents()
    {
        if (!$this->isActualData) {

            if (!$this->timeStop) {

                $this->setTimeStop($this->timeStart);
            } else if (!$this->timeStart) {

                $this->setTimeStart($this->timeStop);
            }
            /** @var User_Calendar_EventBroker_Interface $broker */
            if (!empty($this->_events)) {

                reset($this->_events);
                $firstKey = key($this->_events);
                $prevDay = new DateTime();
                $prevDay->setTimestamp($firstKey - 86400);
                if ($prevDay > $this->timeStart) {

                    foreach ($this->_brokers as $broker) {

                        $this->_mergeEvents($broker->getEvents($this->timeStart, $prevDay), $broker->getName());
                    }
                } else {

                    end($this->_events);
                    $lastDay = new DateTime();
                    $lastDay->setTimestamp(key($this->_events) + 86400);
                    if ($lastDay < $this->timeStop) {

                        foreach ($this->_brokers as $broker) {

                            $this->_mergeEvents($broker->getEvents($lastDay, $this->timeStop), $broker->getName());
                        }

                    }
                }
            } else {

                foreach ($this->_brokers as $broker) {

                    $this->_mergeEvents($broker->getEvents($this->timeStart, $this->timeStop), $broker->getName());
                }
            }
            ksort($this->_events);
            $this->isActualData = true;
        }
        return $this->_events;
    }

    private function _mergeEvents(array $events, $name)
    {
        foreach ($events as $timestamp => $arr) {

            if (!isset($this->_events[$timestamp])) {

                $this->_events[$timestamp] = array();
            }
            if (!isset($this->_events[$timestamp][$name])) {

                $this->_events[$timestamp][$name] = array();
            }
            $this->_events[$timestamp][$name] = array_merge($this->_events[$timestamp][$name], $arr);
        }
        return $this;
    }

    /**
     * @param DateTime $dateTime
     * @return array
     */
    public function getEventsPerDay(DateTime $dateTime)
    {
        $this->_actualizeInterval($dateTime);
        $events = $this->getEvents();
        if (isset($events[$dateTime->getTimestamp()])) {

            return $events[$dateTime->getTimestamp()];
        }
        return array();
    }

    /**
     * @param DateTime $dateTime
     * @return array
     */
    public function getEventsPerMonth(DateTime $dateTime)
    {
        $this->_actualizeInterval($dateTime);
        $nextMonth = clone $dateTime;
        $nextMonth->modify('+1 month');
        $this->_actualizeInterval($nextMonth);
        if (!isset($this->_eventsPerMonths[$dateTime->getTimestamp()])) {

            $_events = array();
            $timestamp = $nextMonth->getTimestamp();
            foreach ($this->getEvents() as $key => $events) {

                if ($key >= $timestamp) {

                    break;
                }
                if ($key > $dateTime->getTimestamp()) {

                    $_events = array_merge($_events, $events);
                }
            }
            $this->_eventsPerMonths[$dateTime->getTimestamp()] = $_events;
        }
        return $this->_eventsPerMonths[$dateTime->getTimestamp()];
    }

    public function getEventsPerYear(DateTime $dateTime)
    {
        $this->_actualizeInterval($dateTime);
        throw new Exception('Not implemented');
    }
}