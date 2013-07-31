<?php

class User_SubscriptionController extends Zend_Controller_Action
{
    public function paymentAction()
    {
        $form = new User_Form_PaymentDirect();
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        
        if ($this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getParams())
        ) {
            $paypalClient = new Ext_Http_Client_Paypal();
            $parameters = $form->getValues();

            // addational parameteres
            $parameters['currency_code'] = $settings->get('payment.currencyCode');
            $parameters['amount'] = $settings->get('payment.primaryPrice');
            $parameters['ip_address'] = $_SERVER['REMOTE_ADDR'];
            
            $result = $paypalClient->doDirectPayment($parameters);
            
            // DEBUG {{{
            parse_str($result->getBody(), $parsedBody);
            echo '<pre>';
            print_r($parsedBody);
            echo '</pre>';
            // }}}
        }

        $this->view->form = $form;
    }
}
