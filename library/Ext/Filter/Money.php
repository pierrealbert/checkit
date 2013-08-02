<?php

class Ext_Filter_Money implements Zend_Filter_Interface
{
    public function filter($value)
    {
        $value = round(floatval($value), 2);
        return $value;
    }
}
