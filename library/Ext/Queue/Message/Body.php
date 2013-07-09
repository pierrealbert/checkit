<?php

/**
 * Message Body
 *
 * @category    Ext
 * @package     Queue
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Queue_Message_Body implements Ext_Queue_Message_Body_Interface
{
    protected $_data = array();

    public function set($name, $value)
    {        
        $this->_data[$name] = $value;
    }

    public function get($name)
    {
        if (isset($this->_data[$name])) {
            return $this->_data[$name];
        }
    }

    public function  __toString()
    {
        return serialize($this);
    }
}
