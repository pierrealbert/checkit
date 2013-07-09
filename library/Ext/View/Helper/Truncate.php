<?php

/**
 * Truncate view helper
 *
 * @category    Ext
 * @package     Ext_View
 * @subpackage  Ext_View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_Truncate extends Zend_View_Helper_Abstract
{
    /**
     * Encoding
     * 
     * @var string
     */
    protected $_encoding = 'UTF-8';

    /**
     *
     * @param string $str
     * @param int $length truncate string to $length chars
     * @param string $endChar
     * @return string
     */
    public function truncate($str, $length = 500, $endChar = '&#8230;', $exact = false)
    {
        if (mb_strlen($str, $this->_encoding) < $length) {
            return $str;
        }

        $replace = array(
            "\r\n", "\r", "\n"
        );
        
        $str = preg_replace("/\s+/", ' ', str_replace($replace, ' ', $str));

        if (mb_strlen($str, $this->_encoding) <= $length) {
            return $str;
        }

        if ($exact) {
            return mb_substr($str, 0, $length, $this->_encoding) . $endChar;
        }

        $out = '';
        foreach (explode(' ', trim($str)) as $val) {
            $out .= $val . ' ';

            if (mb_strlen($out, $this->_encoding) >= $length) {
                $out = trim($out);
                return (mb_strlen($out, $this->_encoding) == mb_strlen($str, $this->_encoding)) ? $out : $out . $endChar;
            }
        }
    }
}