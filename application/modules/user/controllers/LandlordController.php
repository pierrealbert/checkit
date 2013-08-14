<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Александр
 * Date: 11.08.13
 * Time: 13:45
 * To change this template use File | Settings | File Templates.
 */

class User_LandlordController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $t = (int)$this->_getParam('t');
        $options = array(
            'month' => array(
                'render_label' => true,
                'first_day' => 1
            )
        );
        if ($t) {

            $dt = new DateTime();
            $dt->setTimestamp($t);
            $options['date_start'] = $dt;
        }
        $calendar = new  User_Calendar_Calendar($options);
        $this->view->assign(array(
            'calendar' => $calendar
        ));
    }
}