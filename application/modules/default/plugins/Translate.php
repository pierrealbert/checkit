<?php

class Plugin_Translate extends Zend_Controller_Plugin_Abstract
{
    const TRANSLATE_COOKIE_NAME = 'selectedTranslate';
  
    public function routeShutdown(Zend_Controller_Request_Abstract $request)
    {
        $lang = $request->getParam('lang');
        $translator = Zend_Registry::get('Zend_Translate');
        
        if ($request->getRequestUri() == '/'
                && $request->getCookie(self::TRANSLATE_COOKIE_NAME))
        {
            $lang = $request->getCookie(self::TRANSLATE_COOKIE_NAME);
            Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector')
                    ->gotoUrlAndExit($lang);
        }

        if ($translator instanceof Zend_Translate && $lang) {
            setcookie(self::TRANSLATE_COOKIE_NAME, $lang);

            $translator->setLocale($lang);

            App::getLocale()->setLocaleCode($lang);

            Model_Translatable_Field::setCurrentLanguage($lang);
        }
    }
}
