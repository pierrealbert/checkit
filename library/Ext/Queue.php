<?php

/**
 * Zend_Queue extencion
 *
 * @category    Ext
 * @package     Queue
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Queue extends Zend_Queue
{
    protected $_messageClass = 'Ext_Queue_Message';

    public function __construct($spec, $options = array())
    {
        if (is_array($spec)) {
            $options    = $spec;
            $spec       = 'Db';
        }

        parent::__construct($spec, $options);
    }

    public function receiveAll()
    {
        return $this->receive(1000000);
    }
}
