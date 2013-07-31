<?php

class User_SubscriptionController extends Zend_Controller_Action
{
    public function paymentAction()
    {
        $user = $this->_helper->auth->getCurrUser();
        $form = new User_Form_PaymentDirect();
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        
        if ($this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getParams())
        ) {
            $paypalClient = new Ext_Http_Client_Paypal();
            $parameters = $form->getValues();

            // Addational parameteres
            $parameters['currency_code'] = $settings->get('payment.currencyCode');
            $parameters['amount'] = $settings->get('payment.premiumPrice');
            $parameters['ip_address'] = $_SERVER['REMOTE_ADDR'];

            // Create transaction and save to the DB in case if PayPal will not response
            $transaction = new Model_Transaction();
            $transaction->amount = $parameters['amount'];
            $transaction->currency_code = $parameters['currency_code'];
            $transaction->user_id = $user->id;
            $transaction->save();
            
            $response = $paypalClient->doDirectPayment($parameters);
            $response = Ext_Http_Client_Paypal::parseResponseBody($response->getBody());

            $transaction->sent_at = $response->timestamp;
            $transaction->paypal_ack = $response->ack;
            if (!empty($response->transactionid)) {
                $transaction->paypal_transaction_id = $response->transactionid;
            }
            if (!empty($response->correlationid)) {
                $transaction->paypal_correlation_id = $response->correlationid;
            }
            if (!empty($response->l_errorcode0)) {
                $transaction->error_code = $response->l_errorcode0;
            }
            if (!empty($response->l_longmessage0)) {
                $transaction->error_message = $response->l_longmessage0;
            }
            if (strtolower(substr($response->ack, 0, 7)) == 'success') {
                $transaction->is_success = 1;
            }
            $transaction->save();
           
           
            if ($transaction->is_success) {
                $user->is_premium = 1;
                $user->save();
                
    		    $this->_helper->messenger->success('transaction_succeed');
                $this->_helper->redirector('index', 'my-account', 'user');
            } elseif ($transaction->error_message) {
                $translated_error = $this->view->translate('transaction_error_' . $transaction->error_code);
                if ($translated_error == 'transaction_error_' . $transaction->error_code) {
                    $translated_error = $this->view->translate($transaction->error_message);
                }
    		    $this->_helper->messenger->error($translated_error);
            } else {
    		    $this->_helper->messenger->error('unknown_error');
            }
            
            $transaction->save();
        }

        // Erase secure information
        $form->credit_card_number->setValue(Null);
        $form->cvv2->setValue(Null);
        $form->expiration_year->setValue(Null);
        $form->expiration_month->setValue(Null);

        $this->view->form = $form;
    }
}
