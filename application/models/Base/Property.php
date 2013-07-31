<?php

/**
 * Model_Base_Property
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $owner_id
 * @property string $title
 * @property decimal $amount_of_rent_excluding_charges
 * @property decimal $amount_of_charges
 * @property integer $is_furnished
 * @property integer $lease_duration
 * @property integer $deposit
 * @property date $availability
 * @property string $address
 * @property string $postcode
 * @property string $city
 * @property decimal $size
 * @property integer $property_type
 * @property integer $number_of_rooms1
 * @property integer $number_of_rooms2
 * @property integer $is_separate_restrooms
 * @property integer $is_parquet_floor
 * @property integer $is_molding
 * @property integer $is_double_glazing
 * @property integer $is_storage_area
 * @property integer $is_fireplace
 * @property integer $floor
 * @property integer $is_lift
 * @property integer $is_balcony
 * @property integer $is_terrace
 * @property integer $is_garden
 * @property integer $is_yard
 * @property integer $is_attic
 * @property integer $is_basement
 * @property integer $is_garage
 * @property integer $is_parking_lot
 * @property integer $is_swimming_pool
 * @property integer $is_digicode
 * @property integer $is_watchman
 * @property integer $is_old_building
 * @property integer $is_very_old_building
 * @property integer $is_renove
 * @property integer $number_of_bathrooms
 * @property integer $is_individuel
 * @property integer $is_central
 * @property integer $is_au_sol
 * @property integer $is_gaz
 * @property integer $is_electrique
 * @property integer $is_autre
 * @property string $main_photo
 * @property integer $is_published
 * @property Model_User $Owner
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_Property extends Ext_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('property');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('owner_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('amount_of_rent_excluding_charges', 'decimal', 14, array(
             'type' => 'decimal',
             'scale' => 2,
             'length' => '14',
             ));
        $this->hasColumn('amount_of_charges', 'decimal', 14, array(
             'type' => 'decimal',
             'scale' => 2,
             'length' => '14',
             ));
        $this->hasColumn('is_furnished', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('lease_duration', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('deposit', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('availability', 'date', null, array(
             'type' => 'date',
             ));
        $this->hasColumn('address', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('postcode', 'string', 8, array(
             'type' => 'string',
             'length' => '8',
             ));
        $this->hasColumn('city', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('size', 'decimal', 14, array(
             'type' => 'decimal',
             'scale' => 2,
             'length' => '14',
             ));
        $this->hasColumn('property_type', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('number_of_rooms1', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('number_of_rooms2', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('is_separate_restrooms', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_parquet_floor', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_molding', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_double_glazing', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_storage_area', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_fireplace', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('floor', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('is_lift', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_balcony', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_terrace', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_garden', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_yard', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_attic', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_basement', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_garage', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_parking_lot', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_swimming_pool', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_digicode', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_watchman', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_old_building', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_very_old_building', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_renove', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('number_of_bathrooms', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('is_individuel', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_central', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_au_sol', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_gaz', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_electrique', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('is_autre', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('main_photo', 'string', 255, array(
             'type' => 'string',
             'default' => '',
             'length' => '255',
             ));
        $this->hasColumn('is_published', 'integer', 1, array(
             'type' => 'integer',
             'default' => 0,
             'length' => '1',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Model_User as Owner', array(
             'local' => 'owner_id',
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