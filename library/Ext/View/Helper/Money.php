<?php

/**
 * Money view helper
 *
 * @category    Ext
 * @package     Ext_View
 * @subpackage  Ext_View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_Money extends Zend_View_Helper_Abstract
{
    /**
     * Output numeric values in money format ($12.50)
     *
     * @param numeric $money
     * @param bool $showCurrency show currency flag
     * @param string $currency currency
     * @return string 
     */
    public function money($money, $showCurrency = true, $currency = '$')
    {        
        $money =  number_format($money, 2);
        
        if ($showCurrency) {
            $money = $currency . ' ' . $money;
        }
        return $money;
    }
}