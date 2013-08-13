<?php

/**
 * Model_Base_RegionCity
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property Doctrine_Collection $RegionDistrict
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_RegionCity extends Model_Region
{
    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Model_RegionDistrict as RegionDistrict', array(
             'local' => 'id',
             'foreign' => 'region_city_id'));
    }
}