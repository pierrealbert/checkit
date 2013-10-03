<?php

class SearchController extends Zend_Controller_Action
{
    /**
     *
     * @var type Zend_Session_Namespace
     */
    protected $_searchConditions = array();
    
    public function init()
    {
        $this->view->jQuery()->addJavascriptFile('/js/search.js');
        
        $this->_searchConditions = new Zend_Session_Namespace('search');
    }

    public function indexAction()
    {
        $this->view->buttons=array(
            0=>array(
                'type'=>'link',
                'title'=>'Standard Search',
                'url'=>'search/standard',
                'class'=>'btnSearch',
            ),
            1=>array(
                'type'=>'link',
                'title'=>'Search by Metro',
                'url'=>'search/metro',
                'class'=>'btnSearch',
            ),
            2=>array(
                'type'=>'link',
                'title'=>'Search by Draw',
                'url'=>'search/draw',
                'class'=>'btnSearch',
            ),
            3=>array(
                'type'=>'link',
                'title'=>'Search by Map',
                'url'=>'search/map',
                'class'=>'btnSearch',
            )
        );
    }
    
    public function standardAction(){
        // js function initSearchStandard will be called in form
        
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        $form = new Form_SearchStandard();
        
        if ($this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getPost())
        ) {
            $values = $form->getValues();
            
            // alter availability value if user selected now or all
            if ($values['availability_select'] == '') {
                unset($values['availability']); 
            } elseif ($values['availability_select'] == 'now') {
                $values['availability'] = Zend_Date::now()->toString('YYYY-MM-dd');
            }
            unset ($values['availability_select']);

            // convert date format of availability value and add comparison sign
            if (!empty($values['availability'])) {
                $zendDate = new Zend_Date($values['availability'], $settings->get('dateFormat.picker.zend'));
                $values['availability'] = array('value' => $zendDate->toString('YYYY-MM-dd'),
                                                'sign' => '<=',);
            }

            // unset all unchecked checkboxes from the values array
            foreach ($form->getElements() as $element) {
                if ($element instanceof Zend_Form_Element_Checkbox and $values[$element->getName()] == 0) {
                    unset ($values[$element->getName()]);
                }
            }

            // convert values with signs to appropriate format
            foreach ($values as &$value) {
                if (is_string($value)) {
                    foreach (Model_PropertyTable::getSearchAllowedSignes() as $allowedSign) {
                        if (substr($value, 0, strlen($allowedSign)) == $allowedSign) {
                            $value = array('sign' => $allowedSign,
                                           'value' => substr($value, strlen($allowedSign)));
                            break;
                        }
                    }
                }
            }
            
            // convert min_size, max_size, min_budget and max_budget to appropriate format
            // ex: 
            //     min_size => 3 
            //     to 
            //     min_size => array('field' => 'size',
            //                       'value' => '3',
            //                       'sign' => '>=')
            if (!empty($values['min_size'])) {
                $values['min_size'] = array ('field' => 'size',
                                             'value' => $values['min_size'],
                                             'sign' => '>=');
            }
            if (!empty($values['max_size'])) {
                $values['max_size'] = array ('field' => 'size',
                                             'value' => $values['max_size'],
                                             'sign' => '<=');
            }
            if (!empty($values['min_budget'])) {
                $values['min_budget'] = array ('field' => 'amount_of_rent_excluding_charges',
                                               'value' => $values['min_budget'],
                                               'sign' => '>=');
            }
            if (!empty($values['max_budget'])) {
                $values['max_budget'] = array ('field' => 'amount_of_rent_excluding_charges',
                                               'value' => $values['max_budget'],
                                               'sign' => '<=');
            }

            $this->_searchConditions->data = $values;
            
            $this->_helper->redirector->gotoSimple('results', 'search', 'default');  
        }

        $this->view->form = $form;
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
        
        if ($this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getPost())
        ) {
            $values = $form->getValues();
            $values['region_block_id'] = array('value' => explode(',', $values['regions_selected']));
            
            $regionsSelectedIds = explode(',', $this->getRequest()->getParam('regions_selected'));
            
            $this->_searchConditions->data = array(
                'region_block_id' => array('value' => $regionsSelectedIds)
            );
            
            $this->_helper->redirector->gotoSimple('results', 'search', 'default');            
        }

        $this->view->form = $form;
        $this->view->regionsArray = $regionsArray;
        $this->view->regionsJson = $regionsJson;
        $this->view->regionsSelectedIds = $regionsSelectedIds;
        $this->view->allBlockRegions = Model_RegionBlockTable::getInstance()->getAllWithDiscricts();
    }
    
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
                foreach (Model_MetroStationTable::getInstance()->findByMetroLineId($values['metro_line_id']) as $station) {
                    $values['metro_station_id'][] = $station->id;
                }
            }
            $this->_searchConditions->data = array(
                'PropertyXMetroStation.distance' => array('value' => $values['distance'],
                                                          'sign' => '<',),
                'PropertyXMetroStation.metro_station_id' => array('value' => $values['metro_station_id']),
            );
            
            $this->_helper->redirector->gotoSimple('results', 'search', 'default');            
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
                $this->_searchConditions->data = array(
                    'id' => array('value' => $foundPropertyIds),
                );
            } else {
                $this->_searchConditions->data = array(
                    'region_block_id' => array('value' => $blockIds),
                );
            }

            $this->_helper->redirector->gotoSimple('results', 'search', 'default');            
        }
        
        $this->view->form = $form;
    }
    
    public function resultsAction()
    {           
        $foundProperties = Model_PropertyTable::getInstance()
                ->search($this->_searchConditions->data);

        $this->view->foundProperties = $foundProperties;
    }
}
