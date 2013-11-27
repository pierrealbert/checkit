<?php

class SearchController extends Zend_Controller_Action
{
    const COUNT_PER_PAGE = 10;
    const MAX_SIZE = 1500;
    const MAX_BUDGET = 3000;

    protected $_exampleRegions = array(array('name' => 'Le Marais',
                                             'path' => array(array(48.864460,2.355150),
                                                             array(48.864891,2.355330),
                                                             array(48.864491,2.357810),
                                                             array(48.865028,2.358160),
                                                             array(48.864750,2.359670),
                                                             array(48.863560,2.361620),
                                                             array(48.864071,2.362260),
                                                             array(48.863960,2.362760),
                                                             array(48.864529,2.363310),
                                                             array(48.865250,2.362970),
                                                             array(48.865749,2.364470),
                                                             array(48.865849,2.365010),
                                                             array(48.865189,2.365370),
                                                             array(48.864731,2.364780),
                                                             array(48.863628,2.364760),
                                                             array(48.862518,2.363430),
                                                             array(48.861931,2.364680),
                                                             array(48.861389,2.364660),
                                                             array(48.861198,2.365700),
                                                             array(48.860199,2.365730),
                                                             array(48.860191,2.366500),
                                                             array(48.857990,2.367310),
                                                             array(48.857948,2.367440),
                                                             array(48.857689,2.367360),
                                                             array(48.857639,2.367870),
                                                             array(48.856209,2.368360),
                                                             array(48.855770,2.368360),
                                                             array(48.854809,2.368490),
                                                             array(48.854771,2.368170),
                                                             array(48.853870,2.368210),
                                                             array(48.853130,2.368880),
                                                             array(48.851238,2.362400),
                                                             array(48.852558,2.360040),
                                                             array(48.853703,2.357080),
                                                             array(48.854324,2.355400),
                                                             array(48.855270,2.352980),
                                                             array(48.856239,2.350730),
                                                             array(48.864460,2.355150))),
                                       array('name' => 'Barbès [no data]',
                                             'path' => array()),
                                       array('name' => 'Paris Ouest [no data]',
                                             'path' => array()),
                                       array('name' => 'Les Ambassades [no data]',
                                             'path' => array()),
                                       array('name' => 'Paris Est [no data]',
                                             'path' => array()),
                                       array('name' => 'Rive Gauche [no data]',
                                             'path' => array()),
                                       array('name' => 'Périphérique Est [no data]',
                                             'path' => array()),
                                       array('name' => 'Rive Droite [no data]',
                                             'path' => array()));
                                                           
    public function init()
    {
        $this->view->jQuery()->addJavascriptFile('/js/search.js');
    }

    public function indexAction()
    {
       $this->_helper->redirector->gotoSimple('standard', 'search', 'default');
    }

    protected function _processCommonData($form, $values) {
        foreach ($form->getElements() as $element) {
            if ($element instanceof Zend_Form_Element_Checkbox and $values[$element->getName()] == 0) {
                unset ($values[$element->getName()]);
            }
        }

        // convert values with signs to appropriate format
        foreach ($values as &$value) {
            if (is_string($value)) {
                if ($value != '') {
                    $found = false;
                    foreach (Model_PropertyTable::getSearchAllowedSignes() as $allowedSign) {
                        if (substr($value, 0, strlen($allowedSign)) == $allowedSign) {
                            $value = array('sign' => $allowedSign, 'value' => substr($value, mb_strlen($allowedSign)));
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $value = array('sign' => '=', 'value' => $value);
                    }
                }
            } elseif (is_array($value) && !isset($value['sign'])) {
                foreach ($value as $key => &$subvalue) {
                    if ($subvalue != '') {
                        $found = false;
                        foreach (Model_PropertyTable::getSearchAllowedSignes() as $allowedSign) {
                            if (!is_array($subvalue) && mb_substr($subvalue, 0, strlen($allowedSign)) == $allowedSign) {
                                $subvalue = array('sign' => $allowedSign, 'value' => substr($subvalue, mb_strlen($allowedSign)));
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $subvalue = array('sign' => '=', 'value' => $subvalue);
                        }
                    }
                }
            }
        }

        if (!empty($values['min_size'])) {
            $values['min_size'] = array(
                'field' => 'size',
                'value' => $values['min_size']['value'],
                'sign' => '>=');
        }
        if (!empty($values['max_size'])) {
            $values['max_size'] = array (
                'field' => 'size',
                'value' => $values['max_size']['value'],
                'sign' => '<=');
        }
        if (!empty($values['min_budget'])) {
            $values['min_budget'] = array (
                'field' => 'amount_of_rent_excluding_charges',
                'value' => $values['min_budget']['value'],
                'sign' => '>=');
        }
        if (!empty($values['max_budget'])) {
            $values['max_budget'] = array (
                'field' => 'amount_of_rent_excluding_charges',
                'value' => $values['max_budget']['value'],
                'sign' => '<=');
        }

        if (isset($values['region_block_id']) && is_array($values['region_block_id'])) {
            foreach ($values['region_block_id'] as $indx => $val) {
                if (intval($val) <= 0) unset($values['region_block_id'][$indx]);
            }
            if (count($values['region_block_id']) == 0) unset($values['region_block_id']);
        }

        return $values;
    }

    public function standardAction(){
        // js function initSearchStandard will be called in form
        
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        $form = new Form_SearchStandard();
        
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();

            // alter availability value if user selected now or all
            if ($values['availability_select'] == '' && trim($values['availability']) == '') {
                unset($values['availability']); 
            } elseif ($values['availability_select'] == 'now') {
                $values['availability'] = Zend_Date::now()->toString('YYYY-MM-dd');
            }

            if (isset($values['availability']) && trim($values['availability']) != '') {
                $availabilityTime = strtotime($values['availability']);
                if ($availabilityTime === false) {
                    unset($values['availability']);
                } else {
                    $values['availability'] = date('Y-m-d', $availabilityTime);
                }
            } else {
                unset($values['availability']);
            }
            unset ($values['availability_select']);

            // convert date format of availability value and add comparison sign
            try {
                if (!empty($values['availability'])) {
                    //$zendDate = new Zend_Date($values['availability'], 'dd MMMM, yyyy');
                    $zendDate = new Zend_Date($values['availability'], 'YYYY-MM-dd');
                    $values['availability'] = array('value' => $zendDate->toString('YYYY-MM-dd'),
                                                    'sign' => '<=',);
                }
            } catch (Exception $e) {
                unset($values['availability']);
            }

            if (isset($values['rent_period']) && $values['rent_period'] != '') {
                if ('long' == $values['rent_period']) {
                    $values['lease_duration'] = '>2';
                } else {
                    $values['lease_duration'] = '<=2';
                }
            }
            unset($values['rent_period']);

            $values = $this->_processCommonData($form, $values);

            $search = new Model_Search;
            $search->search_type = 'standart';
            $search->conditions = serialize($values);
            $search->user_id = Zend_Auth::getInstance()->getIdentity();
            $search->save();
            
            $this->_helper->redirector->gotoSimple('results', 'search', 'default', array('search_id' => $search->id));
        }

        $regionBlockOptions = array();
        foreach (Model_RegionBlockTable::getInstance()->getAllWithDiscricts() as $region) {
            $regionBlockOptions[] = array('id' => $region->id, 'value' => $region->RegionDistrict->name . ' - ' . $region->name);
        }

        $this->view->regionBlocks = $regionBlockOptions;
        $this->view->form = $form;
    }

    public function updatesearchAction()
	{
        $searchId = $this->getRequest()->getParam('search_id');
        $from = $this->getRequest()->getParam('from');
        $search = Model_SearchTable::getInstance()->find($searchId);
        if (!$search or $search->user_id != Zend_Auth::getInstance()->getIdentity()) {
            $searchId = false;
        }

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        if ($from != 'results') {
            $form = new Form_SearchStandard();
            $form->setAction($this->view->url(array(
                'controller' => 'search',
                'action'     => 'updatesearch'
            ), null, true));

            $data = @unserialize($search->conditions);
            if ($data !== false) {
                $form_data = array();
                foreach ($data as $field => $value) {
                    if (is_array($value)) {
                        if (isset($value['value'])) {
                            $form_data[$field] = $value['value'];
                        } else {
                            $form_data[$field] = array();
                            foreach ($value as $indx => $subField) {
                                if (isset($subField['value'])) {
                                    if (isset($subField['sign']) && $subField['sign'] != '=') {
                                        $sign = trim($subField['sign']);
                                    } else {
                                        $sign = '';
                                    }
                                    $form_data[$field][] = $sign.$subField['value'];
                                }
                            }
                        }
                    } else {
                        $form_data[$field] = $value;
                    }
                }

                if ($searchId) {
                    $form_data['search_id'] = $searchId;
                }
                $form->populate($form_data);
            }
        } else {
            $form = new Form_SearchResults();
        }

        if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getPost())) {
				$values = $form->getValues();
                unset($values['search_id']);
				// alter availability value if user selected now or all
				if ($values['availability_select'] == '' && trim($values['availability']) == '') {
					unset($values['availability']);
				} elseif ($values['availability_select'] == 'now') {
					$values['availability'] = Zend_Date::now()->toString('YYYY-MM-dd');
				}

				if (isset($values['availability']) && trim($values['availability']) != '') {
					$availabilityTime = strtotime($values['availability']);
					if ($availabilityTime === false) {
						unset($values['availability']);
					} else {
						$values['availability'] = date('Y-m-d', $availabilityTime);
					}
				} else {
					unset($values['availability']);
				}
				unset ($values['availability_select']);

				// convert date format of availability value and add comparison sign
				try {
					if (!empty($values['availability'])) {
						//$zendDate = new Zend_Date($values['availability'], 'dd MMMM, yyyy');
						$zendDate = new Zend_Date($values['availability'], 'YYYY-MM-dd');
						$values['availability'] = array('value' => $zendDate->toString('YYYY-MM-dd'),
							'sign' => '<=',);
					}
				} catch (Exception $e) {
					unset($values['availability']);
				}

				if (isset($values['rent_period']) && $values['rent_period'] != '') {
					if ('long' == $values['rent_period']) {
						$values['lease_duration'] = '>2';
					} else {
						$values['lease_duration'] = '<=2';
					}
				}
				unset($values['rent_period']);

				if (!$values['metro_station_id'] and $values['metro_line_id']) {
					$values['metro_station_id'] = array();
					foreach (Model_MetroStationTable::getInstance()->findOrderedByMetroLineId($values['metro_line_id']) as $station) {
						$values['metro_station_id'][] = $station['id'];
					}
					if (count($values['metro_station_id']) == 0) {
						$values['metro_station_id'][] = -1;
					}
				}

				if (isset($values['metro_station_id']) && !is_array($values['metro_station_id'])) {
					$values['metro_station_id'] = array($values['metro_station_id']);
				}

				if (empty($values['distance'])) {
					$values['distance'] = 0.5;
				}

				$metro_stations = $values['metro_station_id'];

				$values = $this->_processCommonData($form, $values);

				$values['PropertyXMetroStation.distance'] = array(
					'value' => $values['distance'],
					'sign' => '<'
				);
				$values['PropertyXMetroStation.metro_station_id'] = array('value' => $metro_stations);
                if (empty($metro_stations)) {
                    unset($values['PropertyXMetroStation.metro_station_id']);
                    unset($values['PropertyXMetroStation.distance']);
                }
				unset($values['metro_station_id']);
				unset($values['metro_line_id']);
				unset($values['distance']);

				if ($searchId === false) {
					$search = new Model_Search;
					$search->search_type = 'standart';
					$search->user_id = Zend_Auth::getInstance()->getIdentity();
				}

                foreach($values as $field => $value) {
                    if (empty($value)) unset($values[$field]);
                }

                $search->conditions = serialize($values);

                $query = Model_PropertyTable::getInstance()->searchQuery(unserialize($search->conditions), array(), 'date', 'desc');
                $adapter = new ZFDoctrine_Paginator_Adapter_DoctrineQuery($query);
                $paginator = new Zend_Paginator($adapter);
                $search->found_items = $paginator->getTotalItemCount();

                $search->save();

				$this->_helper->redirector->gotoSimple('results', 'search', 'default', array('search_id' => $search->id));
			}
		}

        $regionBlockOptions = array();
        foreach (Model_RegionBlockTable::getInstance()->getAllWithDiscricts() as $region) {
            $regionBlockOptions[] = array('id' => $region->id, 'value' => $region->RegionDistrict->name . ' - ' . $region->name);
        }

        $this->view->regionBlocks = $regionBlockOptions;
        $this->view->form = $form;
        $this->view->showSaveBtn = true;
        if ($searchId) {
            $this->view->searchId = $searchId;
        }
		$this->renderScript('search/standard.phtml');
	}

    public function mapAction()
    {
        // js function initSearchMap will be called in a view helper googleMap
        
        $regions = Model_RegionTable::getInstance()->findAll();
        $regionsSelectedIds = array();
        
        $regionsArray = array();
        foreach ($regions->toArray() as $region) {
            $regionsArray[$region['id']] = $region;
        }
        $regionsJson = json_encode($regionsArray);
       
        $form = new Form_SearchMap();
        
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            if ($this->getRequest()->getParam('is-bottom-form') != 'yes') {
                $values = $form->getValues();

                $values['region_block_id'] = array('value' => explode(',', $values['regions_selected']));

                if (!isset($values['regions_selected']) || $values['regions_selected'] == '') {
                    $values['regions_selected'] = -1;
                }
                if ($values['regions_selected'] != -1) {
                    $regionsSelectedIds = explode(',', $values['regions_selected']);
                } else {
                    $regionsSelectedIds = array();
                }

                unset($values['regions_selected']);
                $values = $this->_processCommonData($form, $values);

                $RegionDistricts = array();
                $regions         = array();
                foreach ($_POST as $key => $value) {
                    $tmp = explode('-', $key);
                    if (isset($tmp[1]) && is_numeric($tmp[1]) && $tmp[0] == 'RegionDistrict') {
                        if ($value == 1 || $value == 'on') {
                            $RegionDistricts[] = intval($tmp[1]);
                        }
                    } else {
                        if (substr($key, 0, strlen('region')) == 'region' && $value == 1) {
                            $regId = intval(substr($key, strlen('region')));
                            if ($regId > 0) {
                                $regions[] = $regId;
                            }
                        }
                    }
                }

                $IDs = array();
                if (count($RegionDistricts) > 0) {
                    $regionsByDistricts = Model_RegionBlockTable::getInstance()->getByRegionDistrictIds($RegionDistricts);
                    foreach($regionsByDistricts as $key => $value) {
                        $IDs[] = $value->id;
                    }
                }
                $usedId = array();
                $values['region_block_id'] = array();
                foreach ($regionsSelectedIds as $indx => $id) {
                    if (!isset($usedId[$id])) {
                        $values['region_block_id'][] = $id;
                        $usedId[$id] = 1;
                    }
                }
                foreach ($IDs as $indx => $id) {
                    if (!isset($usedId[$id])) {
                        $values['region_block_id'][] = $id;
                        $usedId[$id] = 1;
                    }
                }
                foreach ($regions as $indx => $id) {
                    if (!isset($usedId[$id])) {
                        $values['region_block_id'][] = $id;
                        $usedId[$id] = 1;
                    }
                }

                $values['region_block_id'] = array('value' => $values['region_block_id']);
            }

            $search = new Model_Search;
            $search->search_type = 'map';
            $search->conditions = serialize($values);
            $search->user_id = Zend_Auth::getInstance()->getIdentity();
            $search->save();
            
            $this->_helper->redirector->gotoSimple('results', 'search', 'default', array('search_id' => $search->id));
        }

        $this->view->form = $form;
        $this->view->regionsArray = $regionsArray;
        $this->view->regionsJson = $regionsJson;
        $this->view->regionsSelectedIds = $regionsSelectedIds;
        $this->view->allBlockRegions = Model_RegionBlockTable::getInstance()->getAllWithDiscricts();
    }
    
    /*
    //update station coords by draggin the marker
    public function metroUpdateCoordsAction() {
        $params = $this->getRequest()->getParams();

        $station = Model_MetroStationTable::getInstance()->findById($params['statId']);
        
        $station->pixel_x=$params['dx'];
        $station->pixel_y=$params['dy'];
        
        $station->save();
            
    }

    //parse RATP csv files
    public function parseMetroAction() {
        $path = 'd://work/real_estate/csv/';
        $h = fopen($path . 'gps.csv', 'r');

        while (($data = fgetcsv($h, 0, "#")) !== FALSE && ( $data[5] == 'metro' || $data[5] == 'rer' )) {
            $stations[] = $data;
        }

        $h = fopen($path . 'map.csv', 'r');

        while (($data = fgetcsv($h, 0, ";")) !== FALSE) {

            foreach ($stations as &$station) {
                if ($station[0] == $data[0]) {
                    $station['map_x'] = empty($data[1]) ? $data[3] : $data[1];
                    $station['map_y'] = empty($data[2]) ? $data[4] : $data[2];
                    
                }
            }
        }

        $h = fopen($path . 'line.csv', 'r');

        while (($data = fgetcsv($h, 0, "#")) !== FALSE) {
            foreach ($stations as $key => &$val) {
                if ($val[0] == $data[0]) {
                    $line = substr($data[1], 0, strpos($data[1], ' ('));
                    switch ($line) {
                        case '3B':
                            $val['line_id'] = 4;
                            break;
                        case '4':
                        case '5':
                        case '6':
                        case '7':
                            $val['line_id'] = $line + 1;
                            break;
                        case '7B':
                            $val['line_id'] = 9;
                            break;
                        case '8':
                        case '9':
                        case '10':
                        case '11':
                        case '12':
                        case '13':
                        case '14':
                            $val['line_id'] = $line + 2;
                            break;
                        case 'A':
                            $val['line_id'] = 17;
                            break;
                        case 'B':
                            $val['line_id'] = 18;
                            break;
                        case 'C':
                            $val['line_id'] = 19;
                            break;
                        case 'D':
                            $val['line_id'] = 20;
                            break;
                        case 'E':
                            $val['line_id'] = 21;
                            break;
                        case 'FUN':
                            $val['line_id'] = 22;
                            break;
                        case 'ORV':
                            $val['line_id'] = 23;
                            break;
                        default:
                            $val['line_id'] = $line;
                            break;
                    }
                    
                }
            }
        }


        $stationsCollection = new Doctrine_Collection('Model_MetroStation');
        foreach ($stations as $station) {
            $stationModel = new Model_MetroStation();

            $stationModel->metro_line_id = $station['line_id'];
            $stationModel->name = $station[3];
            $stationModel->pixel_x = $station['map_x'];
            $stationModel->pixel_y = $station['map_y'];
            $stationModel->longitude = $station[1];
            $stationModel->latitude = $station[2];

            $stationsCollection->add($stationModel);
        }

        $stationsCollection->save();
    }
    */
    
    public function metroAction()
    {
        $stations = Model_MetroStationTable::getInstance()->findAll();
        $lines = Model_MetroLineTable::getInstance()->findAll();
        $form = new Form_SearchMetro();
        
        if ($this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getPost())
        ) {
            $values = $form->getValues();

            if (!$values['metro_station_id'] and $values['metro_line_id']) {
                $values['metro_station_id'] = array();
                foreach (Model_MetroStationTable::getInstance()->findOrderedByMetroLineId($values['metro_line_id']) as $station) {
                    $values['metro_station_id'][] = $station['id'];
                }
                if (count($values['metro_station_id']) == 0) {
                    $values['metro_station_id'][] = -1;
                }
            }

            if (isset($values['metro_station_id']) && !is_array($values['metro_station_id'])) {
                $values['metro_station_id'] = array($values['metro_station_id']);
            }

            if (empty($values['distance'])) {
                $values['distance'] = 0.5;
            }

            $metro_stations = $values['metro_station_id'];
            $values = $this->_processCommonData($form, $values);
            $values['PropertyXMetroStation.distance'] = array(
                'value' => $values['distance'],
                'sign' => '<'
            );
            $values['PropertyXMetroStation.metro_station_id'] = array('value' => $metro_stations);
            unset($values['metro_station_id']);
            unset($values['metro_line_id']);
            unset($values['distance']);

            $search = new Model_Search;
            $search->search_type = 'metro';

            $search->conditions = serialize($values);
            $search->user_id = Zend_Auth::getInstance()->getIdentity();
            $search->save();
            
            $this->_helper->redirector->gotoSimple('results', 'search', 'default', array('search_id' => $search->id));
        }

        $this->view->form = $form;
        $this->view->stations = $stations;
        $this->view->lines = $lines;
    }


    public function drawAction()
    {
        $form = new Form_SearchDraw();
        
        if ($this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getPost())
        ) {
            $geo = new Ext_Geo();
            $values = $form->getValues();

            $polygon = array();
            foreach (json_decode($values['drawn_polygon']) as $vertex) {
                $polygon[] = array('x' => $vertex->lat,
                                   'y' => $vertex->lng);
            }
            unset($values['drawn_polygon']);

            // Optimization ??? {{{
            // $districts = Model_RegionDistrictTable::getInstance()->findAll();
            // $foundDistricts = $geo->polygonRegionsIntersect($polygon, $districts);
            // $districtIds = array();
            // foreach ($foundDistricts as $foundDistrict) {
            //     $districtIds[] = $foundDistrict->id;
            // }
            // $blocks = Model_RegionBlockTable::getInstance()->getByRegionDistrictIds($districtIds);
            // }}} Optimization ??? 

            $values = $this->_processCommonData($form, $values);



            $blocks = Model_RegionBlockTable::getInstance()->findAll();
            $foundBlocks = $geo->polygonRegionsIntersect($polygon, $blocks);
            $blockIds = array();
            foreach ($foundBlocks as $foundBlock) {
                $blockIds[] = $foundBlock->id;
            }
            $propertiesByBlocks = Model_PropertyTable::getInstance()->search(array('region_block_id' => array('value' => $blockIds)));

            if ($propertiesByBlocks->count() < 1000) { // TODO: needs to decide how much properties is to much
                $foundPropertyIds = array();
                foreach ($propertiesByBlocks as $property) {
                    if ($geo->isPointInPolygon(array($property->latitude, $property->longitude), $polygon)) {
                        $foundPropertyIds[] = $property->id;
                    }
                }
                $values['id'] = array('value' => $foundPropertyIds);
            } else {
                $values['region_block_id'] = array('value' => $blockIds);
            }

            $search = new Model_Search;
            $search->search_type = 'draw';
            $search->conditions = serialize($values);
            $search->user_id = Zend_Auth::getInstance()->getIdentity();
            $search->save();
            
            $this->_helper->redirector->gotoSimple('results', 'search', 'default', array('search_id' => $search->id));
        }
        
        $this->view->exampleRegions = array_map(create_function('$region', '$region[\'path\'] = json_encode($region[\'path\']); return $region;'), $this->_exampleRegions);
        $this->view->form = $form;
    }

    public function mainAction() {
        $form = new Form_SearchMain();
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $this->_processCommonData($form, $this->getRequest()->getPost());

            unset($values['region_block_input']);

            $search = new Model_Search;
            $search->search_type = 'main';
            $search->conditions = serialize($values);
            $search->user_id = Zend_Auth::getInstance()->getIdentity();
            $search->save();

            $this->_helper->redirector->gotoSimple('results', 'search', 'default', array('search_id' => $search->id));
        }

        $this->view->form = $form;
    }

    protected function _getMetroStationsInfo(&$formValues) {
        $metroDistance  = '';
        $metroStations  = array();
        $otherLines     = array();
        $metroLine      = false;
        $curStationIndx = -1;

        if (isset($formValues['PropertyXMetroStation.metro_station_id']) && isset($formValues['PropertyXMetroStation.metro_station_id'][0])) {
            //$lines = Model_MetroLineTable::getInstance()->findAll();

            if (count($formValues['PropertyXMetroStation.metro_station_id']) > 1) {
                $curMetroStationId = $formValues['PropertyXMetroStation.metro_station_id'][intval(count($formValues['PropertyXMetroStation.metro_station_id'])/2)];
            } else {
                $curMetroStationId = $formValues['PropertyXMetroStation.metro_station_id'][0];
            }

            $metroLineId = -1;
            $station = Model_MetroStationTable::getInstance()->findById($curMetroStationId);
            if ($station !== false) {
                $metroLineId = $station->metro_line_id;
            }

            $lines = Model_MetroLineTable::getInstance()->findByStationName($station->name, $curMetroStationId);
            if ($lines !== false) {
                foreach ($lines as $indx => $rec) {
                    $otherLines[] = array(
                        'id'    => $rec['id'],
                        'name'  => $rec['name'],
                        'color' => $rec['color']
                    );
                }
            }

            if ($metroLineId == -1) {
                unset($formValues['PropertyXMetroStation.metro_station_id']);
            } else {
                $metroLine = Model_MetroLineTable::getInstance()->findById($metroLineId);

                $allStations = array();
                $curIndx     = false;

                foreach (Model_MetroStationTable::getInstance()->findOrderedByMetroLineId($metroLineId) as $station) {
                    $allStations[] = array('id' => $station['id'], 'name' => $station['name']);
                    if ($station['id'] == $curMetroStationId) {
                        $curIndx = count($allStations)-1;
                    }
                }

                if ($curIndx === false || count($allStations) == 0) {
                    unset($formValues['PropertyXMetroStation.metro_station_id']);
                    $allStations = array();
                    $curIndx     = false;
                } else {
                    if (($curIndx-2) < 0) {
                        $delta = -($curIndx-2);
                    } else {
                        $delta = 0;
                    }
                    $metroStations = array_slice($allStations, $curIndx-2+$delta, 5);

                    $curStationIndx = 2-$delta;
                    if ($curStationIndx < 0) $curStationIndx = 0;
                    if ($curStationIndx >= count($metroStations)) $curStationIndx = 0;
                }
            }

            if (isset($formValues['PropertyXMetroStation.metro_station_id'])) {
                if (!isset($formValues['PropertyXMetroStation.distance']) || !isset($formValues['PropertyXMetroStation.distance']['value'])) {
                    $metroDistance = '<=0.5';
                } else {
                    $metroDistance = '<='.$formValues['PropertyXMetroStation.distance']['value'];
                }
            } else {
                unset($formValues['PropertyXMetroStation.distance']);
            }
        }

        return array($metroDistance, $metroStations, $otherLines, $metroLine, $curStationIndx);
    }

    public function resultsAction()
    {
        $searchId = $this->getRequest()->getParam('search_id');
        $sortBy   = $this->getRequest()->getParam('sort', 'date');
        $sortDir  = $this->getRequest()->getParam('dir', 'desc');
        $search = Model_SearchTable::getInstance()->find($searchId);
        if (!$search or $search->user_id != Zend_Auth::getInstance()->getIdentity()) {
            $this->_helper->redirector->gotoSimple('index', 'search', 'default');
        }

        list($sortBy, $sortDir) = Model_PropertyTable::getInstance()->checkSortAttrs($sortBy, $sortDir);
        $query = Model_PropertyTable::getInstance()->searchQuery(unserialize($search->conditions), array(), $sortBy, $sortDir);
        
        $adapter = new ZFDoctrine_Paginator_Adapter_DoctrineQuery($query);
        $paginator = new Zend_Paginator($adapter);
        $currentPage = $this->getRequest()->getParam('page', 1);
        $paginator->setCurrentPageNumber($currentPage);
        $paginator->setItemCountPerPage(self::COUNT_PER_PAGE);
               
        $search->found_items = $paginator->getTotalItemCount();
        $search->save();
        
        $nearStations = Model_PropertyTable::getInstance()->getNearStations($paginator);
        $ownersStatus = Model_PropertyTable::getInstance()->getUsersStatus($paginator);

        $form = new Form_SearchResults();

        $values = @unserialize($search->conditions);
        $formValues = array();
        foreach ($values as $paramName => $paramValue) {
            if (empty($paramValue)) continue;

            if (is_array($paramValue)) {
                if (isset($paramValue[0]['value'])) {
                    $tmpList = array();
                    foreach ($paramValue as $indx => $rec) {
                        if (isset($rec['sign']) && $rec['sign'] != '=') $rec['value'] = $rec['sign'].$rec['value'];
                        $tmpList[] = $rec['value'];
                    }
                    $formValues[$paramName] = $tmpList;
                } else {
                    if (isset($paramValue['value'])) {
                        $formValues[$paramName] = $paramValue['value'];
                    } else {
                        $formValues[$paramName] = $paramValue;
                    }
                }
            } else {
                $formValues[$paramName] = $paramValue;
            }
        }

        $regions_id = isset($formValues['region_block_id']) ? (is_array($formValues['region_block_id']) ? $formValues['region_block_id'] : array($formValues['region_block_id'])) : array();
        $selected_regions_list = array();
        $full_regions_list = array();
        foreach (Model_RegionBlockTable::getInstance()->getAllWithDiscricts() as $region) {
            if (in_array($region->id, $regions_id)) {
                $parts = explode(':', $region->RegionDistrict->name);
                $selected_regions_list[] = array('id' => $region->id, 'value' => trim($parts[0]) . ': ' . $region->name, 'full' => $region->RegionDistrict->name . ' - ' . $region->name);
            }
            $full_regions_list[] = array('id' => $region->id, 'value' => $region->RegionDistrict->name . ' - ' . $region->name);
        }

        unset($formValues['region_block_id']);

        if (isset($formValues['number_of_rooms1']) && is_array($formValues['number_of_rooms1']) && count($formValues['number_of_rooms1']) > 0) {
            $was5 = false;
            $was4 = false;
            foreach ($formValues['number_of_rooms1'] as $indx => $val) {
                if ($val == '>=5') {
                    unset($formValues['number_of_rooms1'][$indx]);
                    $was5 = true;
                } elseif ($val == '4'){
                    $formValues['number_of_rooms1'][$indx] = '>=4';
                    $was4 = true;
                }
            }
            if ($was5 && !$was4) {
                $formValues['number_of_rooms1'][] = '>=4';
            }
        }

        if (isset($formValues['availability']) && $formValues['availability'] != '') {
            $formValues['availability'] = date('j F, Y', strtotime($formValues['availability']));
        }
        $form->setDefaults($formValues);

        list($metroDistance, $metroStations, $otherLines, $metroLine, $curStationIndx) = $this->_getMetroStationsInfo($formValues);

        $this->view->form            = $form;
        $this->view->regions_sel     = $selected_regions_list;
        $this->view->regions_list    = $full_regions_list;
        $this->view->paginator       = $paginator;
        $this->view->nearStations    = $nearStations;
        $this->view->ownersStatus    = $ownersStatus;
        $this->view->search          = $search;
        $this->view->sortBy          = $sortBy;
        $this->view->sortDir         = $sortDir;

        $this->view->metroDistance   = $metroDistance;
        $this->view->metroLine       = $metroLine;
        $this->view->curStationIndx  = $curStationIndx;
        $this->view->metroStations   = $metroStations;
        $this->view->otherLines      = $otherLines;

        $this->view->search_type      = (isset($formValues['PropertyXMetroStation.metro_station_id']) ? 'metro' : 'standard');
        if (isset($formValues['PropertyXMetroStation.metro_station_id'])) {
            if (count($formValues['PropertyXMetroStation.metro_station_id']) == 1) {
                $this->view->metro_station_id = $formValues['PropertyXMetroStation.metro_station_id'];
                $this->view->metro_line_id    = '';
            } else {
                $this->view->metro_station_id = '';
                $this->view->metro_line_id    = $metroLine->id;
            }
        }

        $this->view->MAX_SIZE        = self::MAX_SIZE;
        $this->view->MAX_BUDGET      = self::MAX_BUDGET;
    }
    
    public function ajaxSaveSearchAction()
    {
        $searchId = $this->getRequest()->getParam('search_id');
        $search = Model_SearchTable::getInstance()->find($searchId);
        if (!$search || $search->user_id != Zend_Auth::getInstance()->getIdentity()) {
            $this->_helper->redirector->gotoUrl('login/index');
        }
        
        $form = new Form_SaveSearch();
        $form->setDefault('search_id', $searchId);
        
        if (Zend_Auth::getInstance()->getIdentity() && $this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getPost())
        ) {
            $search->is_temp    = False;
            $search->name       = $form->getValue('name');
            $conditions         = @unserialize($search->conditions);
            if ($conditions !== false) {
                foreach($conditions as $field => $value) {
                    if (empty($value)) unset($conditions[$field]);
                }

                $search->conditions = serialize($conditions);

                $query = Model_PropertyTable::getInstance()->searchQuery(unserialize($search->conditions), array(), 'date', 'desc');
                $adapter = new ZFDoctrine_Paginator_Adapter_DoctrineQuery($query);
                $paginator = new Zend_Paginator($adapter);
                $search->found_items = $paginator->getTotalItemCount();

                $search->save();
            }
 
            $this->view->saved = true;
        }    
               
        $this->view->form = $form;
    }
}
