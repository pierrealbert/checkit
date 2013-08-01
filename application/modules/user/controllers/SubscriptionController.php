<?php

class User_SubscriptionController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $user = $this->_helper->auth->getCurrUser();
        
        $this->view->user = $user;
    }
        
    public function paypalPaymentAction()
    {
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        $paypalClient = new Ext_Http_Client_Paypal();
        $user = $this->_helper->auth->getCurrUser();
        
        if ($user->is_premium) {
            $this->_helper->messenger->warning('you_already_purchased_premium_account');
            $this->_helper->redirector('index', 'subscription', 'user');
        }

        $parameters = array(
            'amount' => $settings->get('payment.premiumPrice'),
            'return_url' => $this->view->serverUrl() . 
                            $this->view->baseUrl() . 
                            $this->view->url(array('module' => 'user', 
                                                   'controller' => 'subscription', 
                                                   'action' => 'paypal-return'), null, true),
            'cancel_url' => $this->view->serverUrl() . 
                            $this->view->baseUrl() . 
                            $this->view->url(array('module' => 'user', 
                                                   'controller' => 'subscription', 
                                                   'action' => 'paypal-return',
                                                   'cancel' => 'cancel'), null, true),
            'currency_code' => $settings->get('payment.currencyCode'),
        );
        
        $response = $paypalClient->setExpressCheckout($parameters);


        if ($response->isSuccessful()) {
            // get a token and redirect to paypal
            $response = Ext_Http_Client_Paypal::parseResponseBody($response->getBody());
                 
            if (strtolower(substr($response->ack, 0, 7)) == 'success') {
                $transaction = new Model_Transaction();
                $transaction->amount = $parameters['amount'];
                $transaction->currency_code = $parameters['currency_code'];
                $transaction->user_id = $user->id;
                $transaction->paypal_ec_token = $response->token;
                $transaction->save();
                
                $paypalClient->redirectToExpressCheckoutPage($response->token);
            // } elseif  {
            //     $translated_error = $this->view->translate('transaction_error_' . $transaction->error_code);
            //     if ($translated_error == 'transaction_error_' . $transaction->error_code) {
            //         $translated_error = $this->view->translate($transaction->error_message);
            //     }
            //     $this->_helper->messenger->error($translated_error);
            //     $this->_helper->redirector('index', 'subscription', 'user');
            // }
            } elseif ($response->l_errorcode0) {
                throw new Exception("SetExpressCheckout error {$response->l_errorcode0}: {$response->l_longmessage0}. Response body: {$response->getBody()}");
            } else {
                throw new Exception("SetExpressCheckout unknown error. Response body: {$response->getBody()}");
            }
        } else {
            throw new Exception('SetExpressCheckout: Failed getting response from PayPal.');
        }
    }
    
    public function paypalReturnAction()
    {
        $user = $this->_helper->auth->getCurrUser();
        $tokenParam = $this->getRequest()->getQuery('token');
        $payerIdParam = $this->getRequest()->getQuery('PayerID');
            
        if ($this->getRequest()->getParam('cancel') == 'cancel') {
            if ($tokenParam) {
                if ($transaction = Model_TransactionTable::getInstance()->findOneByPaypalEcToken($tokenParam)) {
                    $transaction->is_cancelled = 1;
                    $transaction->save();
                }
            }
            $this->_helper->messenger->error('transaction_cancelled');
            $this->_helper->redirector('index', 'subscription', 'user');
        }
        
        if ($tokenParam and $payerIdParam) {
            $transaction = Model_TransactionTable::getInstance()->findOneByPaypalEcToken($tokenParam);
            if (!$transaction) {
                $this->_helper->messenger->error('wrong_paypal_token');
                $this->_helper->redirector('index', 'subscription', 'user');
            }
            if ($transaction->is_cancelled) {
                // this may happen only if user manually visited this page with proper token and parameter cancel
                $this->_helper->messenger->error('transaction_was_cancelled_before');
                $this->_helper->redirector('index', 'subscription', 'user');
            }
            $paypalClient = new Ext_Http_Client_Paypal();
            $response = $paypalClient->doExpressCheckout(array('token' => $tokenParam,
                                                               'payment_action' => 'sale',
                                                               'payer_id' => $payerIdParam,
                                                               'amount' => $transaction->amount,
                                                               'currency_code' => $transaction->currency_code));
            if ($response->isSuccessful()) {
                $transaction->paypal_response_body = $response->getBody();
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
                    $this->_helper->redirector('index', 'subscription', 'user');
                } elseif ($transaction->error_message) {
                    $translated_error = $this->view->translate('transaction_error_' . $transaction->error_code);
                    if ($translated_error == 'transaction_error_' . $transaction->error_code) {
                        $translated_error = $this->view->translate($transaction->error_message);
                    }
                    $this->_helper->messenger->error($translated_error);
                    $this->_helper->redirector('index', 'subscription', 'user');
                } else {
                    $this->_helper->messenger->error('unknown_error');
                    $this->_helper->redirector('index', 'subscription', 'user');
                }
            } else {
                throw new Exception('DoExpressCheckout: Failed getting response from PayPal.');
            }
        } else {
            $this->_helper->redirector('index', 'subscription', 'user');
        }
    }
    
    public function creditCardPaymentAction()
    {
        $user = $this->_helper->auth->getCurrUser();
        $form = new User_Form_PaymentDirect();
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        if ($user->is_premium) {
            $this->_helper->messenger->warning('you_already_purchased_premium_account');
            $this->_helper->redirector('index', 'subscription', 'user');
        }
        
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
                $this->_helper->redirector('index', 'subscription', 'user');
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
