<?php

/**
 * Model_MessageTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Model_MessageTable extends Ext_Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object Model_MessageTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Model_Message');
    }
}