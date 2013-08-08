<?php

/**
 * Transfer variables from php code to javascript as global variables
 *
 * @category    Ext
 * @package     View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_AddJsVars extends Zend_View_Helper_Abstract
{
    protected $_phpVars = array();

    /**
     * Add array of php variables to the javascript global scope through the phpVars variable
     *
     * @param array $vars associative array where keys are names of javascript methods of phpVars object
     * @return string Generated Javascript Code
     */
    public function addJsVars($vars = array())
    {
        $this->_phpVars = array_merge($this->_phpVars, $vars);
        return $this->_generateJs();
    }

    /**
     * Generate js code
     *
     * @return string
     */
    protected function _generateJs()
    {
        return $this->_phpVars ? '<script type="text/javascript">var phpVars = ' .
            json_encode($this->_phpVars) . "\n</script>" : '';
    }

}
