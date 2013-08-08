<?php

/**
 * Display Google Map widget
 *
 * @category Ext
 * @package  View_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_View_Helper_GoogleMap extends Zend_View_Helper_HtmlElement
{
    protected $_defaultAttribs = array(
        'id'        => 'map',
        'width'     => '300',
        'height'    => '300',
        'margin'    => '10'
    );

    protected $_googleMapJS = 'http://maps.google.com/maps/api/js?sensor=false';

    /**
     * @param $latitude
     * @param $longitude
     * @param array $attribs HTML tag attributes
     * @param array $options Google map options (for javascript)
     * @return string
     */
    public function googleMap($latitude, $longitude, $attribs = array(), $options = array())
    {
        $attribs = array_merge($this->_defaultAttribs, $attribs);
        $options = array_merge($this->getDefaultOptions(), $options);

        $optionsJson = Zend_Json::encode($options, false, array(
            'enableJsonExprFinder' => true
        ));

        $script = "
            var latlng      = new google.maps.LatLng({$latitude}, {$longitude});
            var mapOptions  = {$optionsJson};
            var map         = new google.maps.Map(document.getElementById('{$attribs['id']}'), mapOptions);
        ";

        $this->view->headScript()->appendFile($this->_googleMapJS);
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
