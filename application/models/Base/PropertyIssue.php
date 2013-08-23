<?php

/**
 * Model_Base_PropertyIssue
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $property_id
 * @property integer $user_id
 * @property integer $subject_id
 * @property string $message
 * @property datetime $created_at
 * @property datetime $updated_at
 * @property Model_User $User
 * @property Model_Property $Property
 * @property Model_Subjects $Subjects
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_PropertyIssue extends Ext_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('property_issue');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('property_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => '4',
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => '4',
             ));
        $this->hasColumn('subject_id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => '4',
             ));
        $this->hasColumn('message', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('created_at', 'datetime', null, array(
             'type' => 'datetime',
             ));
        $this->hasColumn('updated_at', 'datetime', null, array(
             'type' => 'datetime',
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

        $this->hasOne('Model_Subjects as Subjects', array(
             'local' => 'Subject_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));
    }
}