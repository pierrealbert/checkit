<?php

class User_SearchController extends Zend_Controller_Action
{
    public function mapAction()
    {
        $this->view->jQuery()->addJavascriptFile('/js/search-map.js');
        $regions = Model_RegionTable::getInstance()->findAll();
        $regionsArray = array();
        foreach ($regions->toArray() as $region) {
            $regionsArray[$region['id']] = $region;
        }
        $regionsJson = json_encode($regionsArray);

        $this->view->regionsArray = $regionsArray;
        $this->view->regionsJson = $regionsJson;
    }
}
