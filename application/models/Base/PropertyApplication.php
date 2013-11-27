<?php

/**
 * Model_Base_PropertyApplication
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $visitor_id
 * @property integer $property_id
 * @property integer $property_visit_date_id
 * @property time $visit_time
 * @property time $visit_time_end
 * @property integer $rate
 * @property text $message
 * @property boolean $is_read
 * @property boolean $is_accepted
 * @property boolean $is_declined
 * @property Model_PropertyVisitDates $PropertyVisitDates
 * @property Model_User $User
 * @property Model_Property $Property
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_PropertyApplication extends Ext_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('property_application');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('visitor_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('property_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('property_visit_date_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('visit_time', 'time', null, array(
             'type' => 'time',
             ));
        $this->hasColumn('visit_time_end', 'time', null, array(
             'type' => 'time',
             ));
        $this->hasColumn('rate', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('message', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('is_read', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('is_accepted', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
        $this->hasColumn('is_declined', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Model_PropertyVisitDates as PropertyVisitDates', array(
             'local' => 'property_visit_date_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $this->hasOne('Model_User as User', array(
             'local' => 'visitor_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $this->hasOne('Model_Property as Property', array(
             'local' => 'visitor_id',
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