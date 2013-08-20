<?php

class Ext_View_Helper_jsDate extends Zend_View_Helper_Abstract
{
    public function jsDate($date)
    {
        $date = explode('-', $date);

        return "{$date[1]}/{$date[2]}/{$date[0]}";
    }
}