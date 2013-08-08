<?php

/**
 * Model_UserTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Model_UserTable extends Ext_Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return Model_UserTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Model_User');
    }

    /**
     * @param Ext_Doctrine_Query $query
     * @param bool $value 
     * @return void
     */
    protected function _isActiveFilterCallback(Ext_Doctrine_Query $query, $value)
    {
        $query->andWhere($this->_tableAlias . '.is_active = ?', (bool) $value);
    }

    /**
     * @param Ext_Doctrine_Query $query
     * @param string $value 
     * @return void
     */
    protected function _emailFilterCallback(Ext_Doctrine_Query $query, $value)
    {
        if ($value != '') {
            $query->andWhere($this->_tableAlias . '.email LIKE ?', '%' . $value . '%');
        }
    }
}