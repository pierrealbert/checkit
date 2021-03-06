<?php

/**
 * Model_Property
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Model_Property extends Model_Base_Property
{
    const TYPE_APARTMENT        = 1;
    const TYPE_HOUSE            = 2;
    const TYPE_LOFT_OR_WORKSHOP = 3;
    const TYPE_STUDETTE         = 4;
    const TYPE_COLOCATION       = 5;
    const TYPE_AUTRE            = 6;

    const STATE_RENTAL         = 1;
    const STATE_DESCRIPTION    = 2;
    const STATE_PHOTOS         = 3;
    const STATE_HUNTED_PROFILE = 4;
    const STATE_VISIT_DATES    = 5;
    const STATE_PUBLISH_AD     = 6;
    
    public function getPrice()
    {
        return ($this->amount_of_rent_excluding_charges + $this->amount_of_charges);
    }
    
    public function getSimilar($limit)
    {
        return Doctrine::getTable('Model_Property')->createQuery('p')
            ->select('p.*')
            ->where('p.is_published = ?', 1)
            ->limit($limit)
            ->execute();
    }

    static public function getTypes()
    {
        return array(
            self::TYPE_APARTMENT        => 'Appartement',
            self::TYPE_HOUSE            => 'Maison',
            self::TYPE_LOFT_OR_WORKSHOP => 'Loft / Atelier',
            self::TYPE_STUDETTE         => 'Studette',
            self::TYPE_COLOCATION       => 'Colocation',
            self::TYPE_AUTRE            => 'Autre',
        );
    }
    
    public function getType()
    {
        $types = $this->getTypes();
        return $types[$this->property_type];     
    }

    static public function getPlanningOptions()
    {
        return array(
            'is_separate_restrooms' => 'separate_restrooms',
            'is_parquet_floor' => 'parquet_floor',
            'is_molding' => 'molding',
            'is_double_glazing' => 'double_glazing',
            'is_storage_area' => 'storage_are',
            'is_fireplace' => 'fireplace',
            'is_conditioner' => 'air_conditioner',
        );
    }
    
    static public function getOutbuildingOptions()
    {
        return array(
            'is_cave'        => 'cave',
            'is_parking_lot' => 'parking_lot',
            'is_garage'      => 'garage',
        );
    }
    
    static public function getExteriorOptions()
    {
        return array(
            'is_garden'         => 'garden',
            'is_balcony'        => 'balcony',
            'is_terrace'        => 'terrace',
            'is_swimming_pool'  => 'swimming_pool',
        );
    }

    static public function getBuildingFeatureOptions()
    {
        return array(
            'is_lift' => 'lift',
            'is_guardian' => 'guardian',
            'is_digicode' => 'digicode',
            'is_old_building' => 'old_building',
            'is_renove' => 'is_renove',
            'is_new' => 'is_new',
            'is_very_old_building' => 'very_old_building',
        );
    }
    
    static public function getHeatingSystemOptions()
    {
        return array(
            'is_central' => 'central_heating',
            'is_individuel' => 'individual_heating',
            'is_au_sol' => 'au_sol_heating',
            'is_gaz' => 'gaz_heating',
            'is_electrique' => 'electrique_heating',
            'is_autre' => 'autre_heating',
        );
    }

    public function getAcceptedAppsCount()
    {
        $table = Model_PropertyApplicationTable::getInstance();
        return $table->createQuery()
            ->andWhere('pa.property_id=?', $this->id)
            ->andWhere('pa.is_declined=?', 0)
            ->andWhere('pa.is_accepted=?', 1)
            ->execute()
            ->count();
    }

    public function getDeclinedAppsCount()
    {
        $table = Model_PropertyApplicationTable::getInstance();
        return $table->createQuery()
            ->andWhere('pa.property_id=?', $this->id)
            ->andWhere('pa.is_declined=?', 1)
            ->andWhere('pa.is_accepted=?', 0)
            ->execute()
            ->count();
    }
   
    public function getApplicationsCount()
    {
        $table = Model_PropertyApplicationTable::getInstance();
        return $table->createQuery()
                ->andWhere('property_id = ?', $this->id)
                ->count();
    }
        
    
    protected function _assignCoordinates()
    {
        if (!$this->address or !$this->city) {
            return;
        }
            try {
                $googleMaps = new Ext_Service_GoogleMaps();
                $coordinates = $googleMaps->addressToCoordinates(array('street' => $this->address,
                                                                       'city' => $this->city,
                                                                       'postal_code' => $this->postcode,
                                                                       'state' => ''));
                if ($coordinates) {
                    $this->longitude = $coordinates['lng'];
                    $this->latitude = $coordinates['lat'];
                }
            } catch (Exception $e) {
                return;
            }
    }

    protected function _assignRegion()
    {
        if (!$this->longitude or !$this->latitude) {
            return;
        }
        $geo = new Ext_Geo();
        $blockRegions = Model_RegionBlockTable::getInstance()->findAll();
        foreach ($blockRegions as $blockRegion) {
            if ($geo->isPointInPolygon(array($this->latitude, $this->longitude), $blockRegion->path)) {
                $this->region_block_id = $blockRegion;
            }
        }
    }

    protected function _assignMetroStations()
    {
        if (!$this->longitude or !$this->latitude) {
            return;
        }
        $stations = Model_MetroStationTable::getInstance()->getOrderedByDistance(array($this->latitude, $this->longitude), 0.65);
        $collection = new Doctrine_Collection('Model_PropertyXMetroStation');
        foreach ($stations as $station) {
            $pXm = new Model_PropertyXMetroStation();
            $pXm->metro_station_id = $station->id;
            $pXm->property_id = $this->id;
            $pXm->distance = $station->kilometers;
            $collection->add($pXm);
        }
        $collection->save();
    }

    public function preSave($event)
    {
        $this->_assignCoordinates();
        $this->_assignRegion();
    }

    public function postSave($event)
    {
        $this->_assignMetroStations();
    }

    public function getStatesInfo()
    {
        return array(
            self::STATE_RENTAL         => array(
                'action' => 'rental',
                'name'   => 'rental',
            ),
            self::STATE_DESCRIPTION    => array(
                'action' => 'description',
                'name'   => 'description',
            ),
            self::STATE_PHOTOS         => array(
                'action' => 'photos',
                'name'   => 'photos',
            ),
            self::STATE_HUNTED_PROFILE => array(
                'action' => 'hunted-profile',
                'name'   => 'hunted_profile',
            ),
            self::STATE_VISIT_DATES    => array(
                'action' => 'visit-dates',
                'name'   => 'visit_dates',
            ),
            self::STATE_PUBLISH_AD     => array(
                'action' => 'publish-ad',
                'name'   => 'publish_ad',
            ),
        );
    }
    
    public function getNearbyMetroStation()
    {
        return Model_MetroStationTable::getInstance()
                ->createQuery('Model_MetroStation')
                ->innerJoin('Model_MetroStation.PropertyXMetroStation pxms')
                ->where('pxms.property_id = ?', $this->id)
                ->orderBy('pxms.distance ASC')
                ->limit(1)
                ->fetchOne();
    }
    
    public function getStateAction()
    {
        $states_info = $this->getStatesInfo();

        return $states_info[$this->state]['action'];
    }

    public function getShortTitle($length = 20)
    {
        $len = (mb_strlen($this->title) > $length) ? mb_strripos(mb_substr($this->title, 0, $length), ' ') : $length;
        $cutStr = mb_substr($this->title, 0, $len);
        return (mb_strlen($this->title) > $length) ? $cutStr . '...' : $cutStr;
    }

    static public function getNumberOfRooms1Info()
    {
        return array(
            1 => "1",
            2 => "2",
            3 => "3",
            4 => "4",
            5 => "5",
            6 => "6",
            7 => "7 et+",
        );
    }

    static public function getNumberOfRooms2Info()
    {
        return array(
            1 => "1",
            2 => "2",
            3 => "3",
            4 => "4",
            5 => "4 et+",
        );
    }

    static public function getNumberOfBathroomsInfo()
    {
        return array(
            1 => "1",
            2 => "2",
            3 => "3",
            4 => "4 et+",
        );
    }

    public function setOnCreateDefaults($data, $user)
    {
        $data['owner_id'] = $user->id;

        return $data;
    }

    public function getPhotos()
    {
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $photos = array();

        $path = $settings->get('propertyImages.basePath') . "/{$this->id}";

        if (!is_dir($path)) return $photos;

        if ($dh = opendir($path)) {
            while (($file = readdir($dh)) !== false) {
                if (is_file($path . '/'. $file)) {
                    $photos[] = array(
                        'name' => "{$this->id}_{$file}",
                        'link' => $settings->get('propertyImages.baseUrl') . "/{$this->id}/{$file}",
                    );
                }
            }
            closedir($dh);
        }

        return $photos;
    }

    public static function getValuesGroup($key)
    {
        $groups = self::getValuesGroups();

        if (isset($groups[$key])) return $groups[$key];

        return array();
    }

    public static function getValuesGroups()
    {
        return array(
            'planning'          => self::getPlanningOptions(),
            'outbuilding'       => self::getOutbuildingOptions(),
            'exterior'          => self::getExteriorOptions(),
            'building'          => self::getBuildingFeatureOptions(),            
            'heating_system'    => self::getHeatingSystemOptions(),
        );
    }
}
