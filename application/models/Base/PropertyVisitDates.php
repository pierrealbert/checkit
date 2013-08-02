<?php

/**
 * Model_Base_PropertyVisitDates
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $property_id
 * @property date $availability
 * @property time $at_time
 * @property integer $visitors
 * @property Model_Property $Property
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_PropertyVisitDates extends Ext_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('property_visit_dates');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('property_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('availability', 'date', null, array(
             'type' => 'date',
             ));
        $this->hasColumn('at_time', 'time', null, array(
             'type' => 'time',
             ));
        $this->hasColumn('visitors', 'integer', 4, array(
             'type' => 'integer',
             'default' => 0,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
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