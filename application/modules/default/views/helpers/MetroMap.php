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
    
    
    public function metroMap($imagePath = null)
    {
        $this->_addScriptOnce('/js/ammap/ammap.js');
        $this->_addScriptOnce('/js/ammap/maps/js/franceLow.js');
        $this->_addCssOnce('/js/ammap/ammap.css');

        $this->view->jQuery()->addOnload(
            '
            var map;
            var descriptionBurgundy = \'Burgundy\';
            var descriptionBrittany = \'Brittany\';
            AmCharts.ready(function() {
                map = new AmCharts.AmMap();
                map.pathToImages = "http://www.ammap.com/lib/images/";
                //map.panEventsEnabled = true; // this line enables pinch-zooming and dragging on touch devices
                map.colorSteps = 10;
                map.balloon.color = "#000000";
                var dataProvider = {
                    mapVar: AmCharts.maps.franceLow,
                    areas: [
                        {
                        id: "FR-D",
                        description: descriptionBurgundy},
                        {
                        id: "FR-E",
                        description: descriptionBrittany}
                    ]
                };

                map.areasSettings = {
                    autoZoom: true,
                    color: "#1A5FFF",
                    rollOverColor: "#00298B",
                    descriptionWindowY: 20,
                    descriptionWindowX: 550,
                    descriptionWindowWidth: 280,
                    descriptionWindowHeight: 330
                };
                map.dataProvider = dataProvider;

                map.write("mapdiv");
            });'
        );

        $html = '<div id="mapdiv" style="width: 100%; height: 370px;"></div>';

        return $html;
    }
}
