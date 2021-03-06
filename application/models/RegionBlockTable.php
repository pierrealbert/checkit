<?php

/**
 * Model_RegionBlockTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Model_RegionBlockTable extends Model_RegionTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object Model_RegionBlockTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Model_RegionBlock');
    }

    public function getAllWithDiscricts()
    {
        $regionBlockTN = $this->getTableName(); // used as alias
        $regionDistrictTN = $this->getTableName(); // used as alias

        return $this->createQuery('region_block')
                    ->leftJoin("region_block.RegionDistrict region_district")
                    ->orderBy("region_district.name, region_block.name")
                    ->execute();
        
    }

    public function getByRegionDistrictIds(array $ids)
    {
        return $this->createQuery('region_block')
                    ->whereIn("region_block.region_district_id", $ids)
                    ->execute();
    }
}
