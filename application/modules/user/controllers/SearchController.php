<?php

class User_SearchController extends Zend_Controller_Action
{
    public function mapAction()
    {
        $this->view->jQuery()->addJavascriptFile('/js/search-map.js');
        
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
