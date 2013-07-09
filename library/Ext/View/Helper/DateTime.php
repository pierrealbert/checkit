<?php

/**
 * View helper for datetime output
 *
 * @category    Ext
 * @package     View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_DateTime extends Ext_View_Helper_Date
{
    /**
     *
     * @param DateTime|int|string $value date
     * @param string $format output date format
     * @return string date
     */
    public function dateTime($value, $format = Zend_Date::DATETIME_MEDIUM)
    {
        return parent::date($value, $format);
    }
}
