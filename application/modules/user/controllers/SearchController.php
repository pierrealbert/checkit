<?php

class User_SearchController extends Zend_Controller_Action
{
    public function init()
    {
        $this->view->jQuery()->addJavascriptFile('/js/search.js');
        
    }

    public function indexAction()
    {
        // js function initSearchStandard will be called in form
        
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        $form = new User_Form_SearchStandard();
        
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

            // DEBUG {{{ 
            echo '<H2>Criteria</H2>';
            echo '<pre>';
            print_r($values);
            echo '</pre>';
            
            $foundProperties = Model_PropertyTable::getInstance()->search($values);
            echo "<H2>Results ({$foundProperties->count()})</H2>";
            foreach ($foundProperties as $property) {
                echo "<strong>address: " . $property->address . "</strong><br/>\n";
                echo "type: " . $property->property_type . "<br/>\n";
                echo "id: " . $property->id . "<br/>\n";
                echo "availability: " . $property->availability . "<br/>\n";
                echo "<pre>";
                print_r($property->toArray());
                echo "</pre>";
            }
            die();
            // }}} DEBUG 
        }

        $this->view->form = $form;
    }
    
    public function mapAction()
    {
        // js function initSearchMap will be called in a view helper googleMap
        
        $regions = Model_RegionTable::getInstance()->findAll();
        $foundProperties = array();
        $regionsSelectedIds = array();
        
        $regionsArray = array();
        foreach ($regions->toArray() as $region) {
            $regionsArray[$region['id']] = $region;
        }
        $regionsJson = json_encode($regionsArray);
       
        if ($this->getRequest()->isPost() and $this->getRequest()->getParam('regions_selected')) {
            $regionsSelectedIds = explode(',', $this->getRequest()->getParam('regions_selected'));
            // TODO: put here appropriate search when standart search tab will be implemented
            $dquery = Model_PropertyTable::getInstance()->createQuery();
            $foundProperties = $dquery->select()
                                      ->whereIn('region_block_id', $regionsSelectedIds)
                                      ->execute();
        }

        $this->view->foundProperties = $foundProperties;
        $this->view->regionsArray = $regionsArray;
        $this->view->regionsJson = $regionsJson;
        $this->view->regionsSelectedIds = $regionsSelectedIds;
    }
}
