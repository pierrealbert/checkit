<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр
 * Date: 15.08.13
 * Time: 21:21
 * To change this template use File | Settings | File Templates.
 */

class User_Calendar_EventBroker_Doctrine_Visit implements User_Calendar_EventBroker_Interface
{
    public function getName()
    {
        return 'visit';
    }

    public function getEvents(DateTime $timeStart, DateTime $timeStop)
    {
        $data = Doctrine_Query::create()
            ->from('Model_PropertyVisitDates d, d.Property p')
            ->where('d.availability >= :start and d.availability <= :stop', array(
                'start' => $timeStart->format('Y-m-d'),
                'stop' => $timeStop->format('Y-m-d')
            ))
            ->orderBy('d.availability')
            ->execute();
        $result = array();
        /** @var Model_PropertyVisitDates $vd */
        foreach ($data as $vd) {

            $dt = new DateTime($vd->availability);
            $dt->setTime(0, 0, 0);
            $timestamp = $dt->getTimestamp();
            if (!isset($result[$timestamp])) {

                $result[$timestamp] = array();
            }
            $result[$timestamp][] = $vd;
        }
        return $result;
    }
}