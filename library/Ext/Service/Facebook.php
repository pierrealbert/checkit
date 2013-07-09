<?php 

/**
 * Facebook service
 *
 * @category    Ext
 * @package     Facebook
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Service_Facebook extends Facebook_Facebook
{
    public function __construct()
    {
        $settingsHelper = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        
        $settings = $settingsHelper->getOptions();

        parent::__construct(array(
            'appId'  =>  $settings['services']['facebook']['appId'],
            'secret' =>  $settings['services']['facebook']['secret'],
            'cookie' =>  $settings['services']['facebook']['cookie']
        ));
    }

    public function getLoginUrl($params=array())
    {
        return parent::getLoginUrl(array_merge(
            array(
                'req_perms' => 'email'
            ),
            $params
        ));
    }
}
