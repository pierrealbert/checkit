<?php

/**
 * Replace view helper
 *
 * @category Ext
 * @package  View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_Replace extends Zend_View_Helper_Abstract
{
    /**
     *
     * @param mixed $value
     * @param array $array Replace data
     * @param bool $useTranslator use translator view halper
     * @return string
     */
    public function replace($value, $array, $useTranslator = true)
    {
        if (!isset($array[$value])) {
            return '';
        } elseif ($useTranslator) {
            return $this->view->translate($array[$value]);
        } else {
            return $array[$value];
        }
    }
}
