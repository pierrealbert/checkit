<?php

/**
 * View helper for date output
 *
 * @category    Ext
 * @package     View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_Date extends Zend_View_Helper_Abstract
{
    /**
     *
     * @var Zend_Locale
     */
    protected $_locale = null;

    /**
     *
     * @param DateTime|int|string $value date
     * @param string $format output date format
     * @return string date
     */
    public function date($value, $format = 'd/m/Y')
    {
        if ($value) {

            if ($value instanceof DateTime) {
                $value = $value->getTimestamp();
            } elseif (!is_numeric($value)) {
                $value = strtotime($value);
            }
            
            return date($format, $value);
        }
        return null;
    }

    /**
     * Set locale
     *
     * @param Zend_Locale $locale locale instance
     */
    public function setLocale(Zend_Locale $locale)
    {
        $this->_locale = $locale;
    }

    /**
     * Get locale
     *
     * @return Zend_Locale
     */
    public function getLocale()
    {
        if (!$this->_locale) {
            if (Zend_Registry::isRegistered('Zend_Locale')) {
                $this->_locale = Zend_Registry::get('Zend_Locale');
            }
        }
        return $this->_locale;
    }
}
