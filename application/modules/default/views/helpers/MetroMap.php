<?php

/**
 * Display Google Map widget
 *
 * @category Ext
 * @package  View_Helper
 */
class View_Helper_MetroMap extends Zend_View_Helper_Abstract
{
    protected $_addedScripts = array();
    protected $_addedCss = array();
    
    protected function _addScriptOnce($filePath)
    {
        if (!in_array($filePath, $this->_addedScripts)) {
            $this->_addScript($filePath);
        }
    }
    protected function _addScript($filePath)
    {
        $this->view->jQuery()->addJavascriptFile($filePath);
        $this->_addedScripts[] = $filePath;
    }
    
    protected function _addCssOnce($filePath)
    {
        if (!in_array($filePath, $this->_addedCss)) {
            $this->_addCss($filePath);
        }
    }
    protected function _addCss($filePath)
    {
        $this->view->headLink()->appendStylesheet($filePath);
        $this->_addedCss[] = $filePath;
    }
    
    protected function _transformCoordinates($coordinates)
    {
        /*$mapZero = array(-569, 330);
        switch($metroLine){
            case '1':
                $modifier=array(0, -50);
                break;
        }
        $modifier = array(0, 0);
        $coordinates[0] = $coordinates[0] + $mapZero[0] + $modifier[0];
        $coordinates[1] = (7870 - $coordinates[1]) + $mapZero[1] + $modifier[1];*/
        $coordinates[0] = $coordinates[0];
        $coordinates[1] = (7870 - $coordinates[1]);
        return $coordinates;
    }
    
    public function metroMap($stations)
    {
        $this->_addScriptOnce('http://openlayers.org/api/OpenLayers.js');

        $stationsTransformed = array();
        foreach ($stations as $station) {
            $stationsTransformed[] = array('id' => $station->id,
                                           'name' => $station->name,
                                           'coordinates' => $this->_transformCoordinates(array($station->pixel_x, 
                                                                                               $station->pixel_y)),);
        }
        $stationsJSON = json_encode($stationsTransformed);
        // $stationsCoordinates = array($this->_transformCoordinates(array(5472, 6334)));

        $this->view->jQuery()->addOnload(
            "
            var map;
            initSearchMetro(map, $stationsJSON);
            ");

        $html = '<div id="map" class="smallmap" style="width:100%; height:500px;"></div>';

        return $html;
    }
}
