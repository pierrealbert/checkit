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

    protected function _assignCoordinates()
    {
        if (!$this->address or !$this->city) {
            return;
        }
            $googleMaps = new Ext_Service_GoogleMaps();
            $coordinates = $googleMaps->addressToCoordinates(array('street' => $this->address,
                                                                   'city' => $this->city,
                                                                   'postal_code' => $this->postcode,
                                                                   'state' => ''));
            if ($coordinates) {
                $this->longitude = $coordinates['lng'];
                $this->latitude = $coordinates['lat'];
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
            if ($geo->isPointInPolygon(array(48.829967,2.358284), $blockRegion->path)) {
                $this->region_block_id = $blockRegion;
            }
        }
    }

    public function preSave($event)
    {
        $this->_assignCoordinates();
        $this->_assignRegion();
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

    public function getStateAction()
    {
        $states_info = $this->getStatesInfo();

        return $states_info[$this->state]['action'];
    }

    static public function getNumberOfRooms1Info()
    {
        return array(
            1 => "1",
            2 => "2",
            3 => "3",
            4 => "4",
            5 => "5",
            6 => "6 et+",
        );
    }

    static public function getNumberOfRooms2Info()
    {
        return array(
            1 => "1",
            2 => "2",
            3 => "3",
            4 => "4 et+",
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
}
