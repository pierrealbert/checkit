<?php

/**
 * Model_SearchTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Model_SearchTable extends Ext_Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object Model_SearchTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Model_Search');
    }
}