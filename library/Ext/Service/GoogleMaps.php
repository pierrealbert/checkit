<?php

/**
 * GoogleMaps service
 *
 * @category    Ext
 * @package     GoogleMaps
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Service_GoogleMaps
{
    /**
     * Return address coordinates by given PostalAddress instance
     *
     * array (
     *    'lat' => 104.8454635,
     *    'lon' => -154.2342433
     * )
     *
     * @param array $address
     *
     * example: array(
     *  'street'        => '201 Varick Street',
     *  'city'          => 'New York',
     *  'state'         => 'NY',
     *  'postal_code'   => 10014)
     *
     * @return array|null
     */
    public function addressToCoordinates($address)
    {
        $address = $address['street']   . ' '
                . $address['city']      . ' '
                . $address['state']     . ' '
                . $address['postal_code'];
        
        $response = $this->_getGeocodedCoordinates($address);

        if (isset($response->Placemark[0]->Point->coordinates[1])) {
            return array(
                'lat' => $response->Placemark[0]->Point->coordinates[1],
                'lon' => $response->Placemark[0]->Point->coordinates[0]
            );
        }
        return null;
    }

    /**
     *
     * @return array
     */
    public function getOptions()
    {
        if (empty($this->_options)) {
            $this->_options = Zend_Controller_Action_HelperBroker::getStaticHelper('settings')
                    ->get('services.googleMaps');
        }

        return $this->_options;
    }

    /**
     *
     * @param array $options
     * @return Ext_Service_GoogleMaps
     */
    public function setOptions(array $options = array())
    {
        $this->_options = $options;
        return $this;
    }

    /**
     *
     * @param string $address
     * @return mixed
     */
    protected function _getGeocodedCoordinates($address)
    {
        $options = $this->getOptions();

        $client = new Zend_Http_Client();
        $client->setUri($options['geocodingUri']);
        $client->setParameterGet('q', urlencode($address))
                ->setParameterGet('output', 'json')
                ->setParameterGet('sensor', 'false')
                ->setParameterGet('key', $options['apiKey']);

        $result = $client->request('GET');

        return Zend_Json_Decoder::decode(
                $result->getBody(),
                Zend_Json::TYPE_OBJECT);
    }
}
