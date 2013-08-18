<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр
 * Date: 15.08.13
 * Time: 21:31
 * To change this template use File | Settings | File Templates.
 */

interface User_Calendar_EventBroker_Interface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param DateTime $dateStart
     * @param DateTime $dateStop
     * @return array
     */
    public function getEvents(DateTime $dateStart, DateTime $dateStop);
}