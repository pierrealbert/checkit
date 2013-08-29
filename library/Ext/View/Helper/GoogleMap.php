<?php

/**
 * Display Google Map widget
 *
 * @category Ext
 * @package  View_Helper
 */
class Ext_View_Helper_GoogleMap extends Zend_View_Helper_HtmlElement
{
    protected $_defaultAttribs = array(
        'id'        => 'map',
        'width'     => '300',
        'height'    => '300',
        'margin'    => '10'
    );

    /**
     *
     *
     * @return Ext_View_Helper_GoogleMap_Marker
     */
    protected function _createMarker($marker, $index = Null)
    {
        $lat = Null;
        $lng = Null;
        $options = array();
        if (is_object($marker)) {
            if ($marker instanceof Ext_View_Helper_GoogleMap_Marker) {
                return $marker;
            }
            if (method_exists ($marker, 'getGoogleMapsMarker')) {
                return $marker->getGoogleMapsMarker($index);
            }
            if (method_exists ($marker, 'getMarker')) {
                return $marker->getMarker($index);
            }

            if (method_exists ($marker, 'getLat')) {
                $lat = $marker->getLat();
            } else {
                $lat = $marker->lat;
            }
            
            if (method_exists ($marker, 'getLng')) {
                $lng = $marker->getLng();
            } else {
                $lng = $marker->lng;
            }
            
            if (method_exists ($marker, 'getTitle')) {
                $options['title'] = $marker->getTitle();
            } else {
                $options['title'] = $marker->title;
            }
        } elseif (is_array($marker)) {
            $lat = $marker['lat'];
            $lng = $marker['lng'];
            $options['title'] = $marker['title'];
        }
        return new Ext_View_Helper_GoogleMap_Marker($lat, $lng, $options);
    }

    protected function _getGoogleMapJs($apiKey = Null, $libraries = array())
    {
        // Fetch data from settings {{{
        if ($apiKey == Null)
            $apiKey = $this->view->settings('services.googleMaps.apiKey', Null);
        $libraries = array_merge ($libraries, $this->view->settings('services.googleMaps.libraries', array()));
        // }}} Fetch data from settings 
        
        
        $url = 'http://maps.google.com/maps/api/js?sensor=false';
        if ($apiKey)
            $url .= '&key=' . $apiKey;
        if ($libraries)
            $url .= '&libraries=' . implode(',', $libraries);
        return $url;
    }

    /**
     * @param $markers
     * @param array $attribs HTML tag attributes
     * @param array $options Google map options (for javascript)
     * @param string $appendScript js script which will be appended after adding markers to the map
     * @return string
     */
    public function googleMapWithMarkers($markers, $attribs = array(), $options = array())
    {
        $addMarkersScript = "\n/*  Add markers {{{  */\n\n";
        $addMarkersScript .= "bounds = new google.maps.LatLngBounds ();\n";
        
        foreach ($markers as $i => $marker) {
            $index = $i+1; // human readable index starts from 1 (instead of 0)
            if (!$marker instanceof Ext_View_Helper_GoogleMap_Marker) {
                $marker = $this->_createMarker($marker, $index);
            }
            $addMarkersScript .= "\n/*  Marker {$index} with title {$marker->optionVariables['title']} {{{  */\n\n" .
                                 "var marker{$index} = " . $marker .
                                 "marker{$index}.setMap(map);\n" .
                                 "bounds.extend(marker{$index}.getPosition());\n" .
                                 "\n/*  }}} Marker {$index} with title {$marker->optionVariables['title']}  */\n\n";
        }
        $addMarkersScript .= "map.fitBounds(bounds);\n";
        $addMarkersScript .= "\n/*  }}} Add markers  */\n\n";
        
        return $this->googleMap(0, 0, $attribs, $options, $addMarkersScript);
    }

    /**
     * @param $latitude
     * @param $longitude
     * @param array $attribs HTML tag attributes
     * @param array $options Google map options (for javascript)
     * @param string $appendScript js script which will be appended after initialization of the map object
     * @return string
     */
    public function googleMap($latitude, $longitude, $attribs = array(), $options = array(), $appendScript = '')
    {
        $attribs = array_merge($this->_defaultAttribs, $attribs);
        $options = array_merge($this->getDefaultOptions(), $options);

        $optionsJson = Zend_Json::encode($options, false, array(
            'enableJsonExprFinder' => true
        ));

        $script = "var latlng = new google.maps.LatLng({$latitude}, {$longitude});\n" .
                  "var mapOptions = {$optionsJson};\n" .
                  "var map = new google.maps.Map(document.getElementById('{$attribs['id']}'), mapOptions);\n";
        $script .= $appendScript;

        $this->view->headScript()->appendFile($this->_getGoogleMapJs());
        $this->view->JQuery()->addOnload($script);

        if (empty($attribs['style'])) {
            $attribs['style'] = "width: {$attribs['width']}px; height: {$attribs['height']}px; margin:{$attribs['margin']}px;";
        }

        unset($attribs['width'], $attribs['height']);

        $tag = 'div';
        $html = '<' . $tag . $this->_htmlAttribs($attribs) . '></' . $tag . '>';

        return $html;
    }

    public function getDefaultOptions()
    {
        return array(
            'zoom'      => 13,
            'center'    => new Zend_Json_Expr('latlng'),
            'mapTypeId' => new Zend_Json_Expr('google.maps.MapTypeId.ROADMAP')
        );
    }
}
