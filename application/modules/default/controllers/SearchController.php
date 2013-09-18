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
            echo '<pre>';
            print_r($values);
            echo '</pre>';
            die();
        }
        
        $this->view->form = $form;
        $this->view->stations = $stations;
        $this->view->lines = $lines;
    }


    public function drawAction()
    {
        $form = new Form_SearchDraw();
        
        $this->view->form = $form;
    }
    
    public function resultsAction()
    {           
        $foundProperties = Model_PropertyTable::getInstance()
                ->search($this->_searchConditions->data);

        $this->view->foundProperties = $foundProperties;
    }
}
