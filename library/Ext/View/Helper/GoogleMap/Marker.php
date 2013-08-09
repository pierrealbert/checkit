<?php 


class Ext_View_Helper_GoogleMap_Marker
{
    public $lat = 0;
    public $lng = 0;
    public $optionVariables = array();
    
    public function __construct($latitude, $longitude, $optionVariables = array())
    {
        $this->lat = $latitude;
        $this->lng = $longitude;
        $this->optionVariables = $optionVariables;
    }

    public function __toString()
    {
        return $this->getJsCreate();
    }

    public function getJsCreatePoint()
    {
        return "new google.maps.LatLng({$this->lat}, {$this->lng})";
    }

    public function getJsCreate()
    {
        $code = "new google.maps.Marker({\n" .
                "    position: {$this->getJsCreatePoint()},\n";
        
        foreach ($this->optionVariables as $optionName => $optionValue) {
            $code .= "    $optionName: '$optionValue',\n";
        }
        $code .= "});\n";
        
        return $code;
    }
}
