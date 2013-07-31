<?php

class Ext_Http_Client_Paypal extends Zend_Http_Client
{
    protected $_paypalParameters = array(
        'user' => 'USER',
        'password' => 'PWD',
        'signature' => 'SIGNATURE',
        'api_version' => 'VERSION',

        'method' => 'METHOD',

        'payment_action' => 'PAYMENTACTION',
        'amount' => 'AMT',
        'credit_card_type' => 'CREDITCARDTYPE',
        'credit_card_number' => 'ACCT',
        'expiration_date' => 'EXPDATE',
        'cvv2' => 'CVV2',
        'first_name' => 'FIRSTNAME',
        'last_name' => 'LASTNAME',
        'address1' => 'STREET',
        'address2' => 'STREET2',
        'city' => 'CITY',
        'state' => 'STATE',
        'zip' => 'ZIP',
        'country' => 'COUNTRYCODE',
        'currency_code' => 'CURRENCYCODE',
        'ip_address' => 'IPADDRESS',
    );

    protected function _setParameters(array $parameterValues, array $parametersRequired, array $parametersOptional = array())
    {
        foreach ($parametersRequired as $parName) {
            if (!array_key_exists($parName, $parameterValues)) {
                throw new Zend_Http_Client_Exception("Required parameter $parName is not set.");
                return False;
            }
            $this->setParameterGet($this->_paypalParameters[$parName], urlencode($parameterValues[$parName]));
        }
        foreach ($parametersOptional as $parName) {
            if (!array_key_exists($parName, $parameterValues)) {
                continue;
            }
            $this->setParameterGet($this->_paypalParameters[$parName], urlencode($parameterValues[$parName]));
        }
    }

    /**
    * Parse a Name-Value Pair response into an object.
    * @param string $responseBody
    * @return object Returns an object representation of the response.
    */
    public static function parseResponseBody($responseBody) {
        $parsedBody = array();
        parse_str($responseBody, $parsedBody);
        if (empty($parsedBody)) {
            return Null;
        } else {
            return (object) array_change_key_case($parsedBody, CASE_LOWER);
        }
    }

    function __construct($uri = Null, $options = Null)
    {
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        
        if ($uri === Null)
            $uri = $settings->get('payment.paypal.uri');
            
        parent::__construct($uri);
           
        // NOTE: Parameters must always be url encoded, as per PayPal documentation.
        $this->setParameterGet('USER', urlencode($settings->get('payment.paypal.user')));
        $this->setParameterGet('PWD', urlencode($settings->get('payment.paypal.password')));
        $this->setParameterGet('SIGNATURE', urlencode($settings->get('payment.paypal.signature')));
        $this->setParameterGet('VERSION', urlencode($settings->get('payment.paypal.api_version')));
    }

    
    /**
     * Calls the 'DoDirectPayment' API call. Note - Keep track of the
     * transaction ID on success! You'll need it to get transaction details
     * at a later date.
     *
     * @param array $parameters 
     *     required elements: 
     *         $amount
     *         $credit_card_number
     *         $expiration_date or ($expiration_month and $expiration_year)
     *         $cvv2
     *         $first_name
     *         $last_name
     *         $address1
     *         $city
     *         $state
     *         $zip
     *         $country
     *         $currency_code
     *         $ip_address
     *         $payment_action Can be 'Authorization' (default) or 'Sale'
     *     optional elements:
     *         $address2
     *         $credit_card_type
     *
     * @return Zend_Http_Response
     * @throws Zend_Http_Client_Exception
     */
    function doDirectPayment(array $parameters) {
        $parameters['method'] = 'DoDirectPayment';
        if (!array_key_exists('expiration_date', $parameters))
            $parameters['expiration_date'] = str_pad($parameters['expiration_month'], 2, 0, STR_PAD_LEFT) .  $parameters['expiration_year'];
     
        $requiredParameters = array('amount',
                                    'method',
                                    'credit_card_number',
                                    'expiration_date',
                                    'cvv2',
                                    'first_name',
                                    'last_name',
                                    'address1',
                                    'city',
                                    'state',
                                    'zip',
                                    'country',
                                    'currency_code',
                                    'ip_address');
        
        $optionalParameters = array('address2',
                                    'payment_action',
                                    'credit_card_type');
        
        $this->_setParameters( $parameters, $requiredParameters, $optionalParameters);
        
        return $this->request(Zend_Http_Client::GET);
     
    }
}
