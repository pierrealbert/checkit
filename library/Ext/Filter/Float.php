<?php

class Ext_Filter_Float implements Zend_Filter_Interface
{
    public function filter($value)
    {
        $value = round(floatval($value), 2);

        return $value;
    }
}
