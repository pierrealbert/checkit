<?php

/**
 * Model_Base_Alert
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $user_id
 * @property integer $property_id
 * @property enum $msg_type
 * @property string $title
 * @property text $message
 * @property boolean $is_read
 * @property Model_User $User
 * @property Model_Property $Property
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_Alert extends Ext_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('alert');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('property_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('msg_type', 'enum', 10, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'info',
              1 => 'notice',
              2 => 'success',
              3 => 'warning',
              4 => 'error',
             ),
             'size' => 10,
             'length' => 10,
             ));
        $this->hasColumn('title', 'string', 250, array(
             'type' => 'string',
             'size' => 250,
             'length' => 250,
             ));
        $this->hasColumn('message', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('is_read', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Model_User as User', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $this->hasOne('Model_Property as Property', array(
             'local' => 'property_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $timestampable0 = new Doctrine_Template_Timestampable(array(
             'created' => 
             array(
              'name' => 'created_at',
             ),
             'updated' => 
             array(
              'name' => 'updated_at',
             ),
             ));
        $this->actAs($timestampable0);
    }
}