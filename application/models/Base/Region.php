<?php

/**
 * Model_Base_Region
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $name
 * @property integer $zoom_level
 * @property text $path
 * @property string $type
 * @property integer $region_city_id
 * @property integer $region_district_id
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_Region extends Ext_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('region');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('name', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('zoom_level', 'integer', 1, array(
             'type' => 'integer',
             'length' => '1',
             ));
        $this->hasColumn('path', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('type', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('region_city_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('region_district_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));

        $this->setSubClasses(array(
             'Model_RegionCity' => 
             array(
              'type' => 'RegionCity',
             ),
             'Model_RegionDistrict' => 
             array(
              'type' => 'RegionDistrict',
             ),
             'Model_RegionBlock' => 
             array(
              'type' => 'RegionBlock',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}