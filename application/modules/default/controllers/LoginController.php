<?php

class LoginController extends Zend_Controller_Action
{
    protected function _redirectAfterLogin()
    {
        $referralUrl = new Zend_Session_Namespace('ReferralUrl');

        if (isset($referralUrl->url)) {
            $url = $referralUrl->url;
            unset($referralUrl->url);
            $this->_helper->redirector->gotoUrl($url);
        }
        $currUser = $this->_helper->auth->getCurrUser();
        
        if ($currUser->isResident() && !$currUser->hasResidents()) {
            $this->_helper->redirector->gotoSimple('residents', 'my-account', 'user');
        }
        $this->_helper->redirector->gotoSimple('index', 'my-account', 'user');
    }
    
    public function indexAction()
    {
        if ($this->_helper->auth->isLoggedIn()) {
            $this->_helper->redirector->gotoSimple('index', 'my-account', 'user');
        }
        
        $form = new Form_Login();

        $auth = Zend_Auth::getInstance();
        
        if ($this->getRequest()->isPost()
            && $form->isValid($this->getRequest()->getPost())
        ) {
            $authAdapter = new Ext_Auth_Adapter_Doctrine('Model_User', 'email');
            $authAdapter->setIdentity($this->getRequest()->getParam('email'));
            $authAdapter->setCredential($this->getRequest()->getParam('password'));

            if ($auth->authenticate($authAdapter)->isValid()) {
                $user = $authAdapter->getResultObject();
                $this->_autorize($user);

                $this->_redirectAfterLogin();
            } else {
                $this->_helper->messenger->error('login_error_user');
                $this->_helper->redirector->gotoSimple('index', 'login', 'default');
            }
        }

        $this->view->form = $form;
    }
    
    public function facebookAction() {
        if ('cancel' == $this->getRequest()->getParam('submit')) {
            $this->_helper->messenger->success('facebook_auth_canceled');
            $this->_helper->redirector('index');
        }

        $currUser = Zend_Controller_Action_HelperBroker::getStaticHelper('Auth')->getCurrUser();
        $facebook = new Ext_Service_Facebook($currUser);
        $queryString = $this->getRequest()->getServer('QUERY_STRING');
        parse_str($queryString, $getVariables);
        $facebookProfile = null;
        $sess = new Zend_Session_Namespace('Registration');

        if (!empty($queryString)) {
            try {
                $facebookProfile = $facebook->api('/me');
            } catch (Facebook_FacebookApiException $e) {
                $this->_helper->messenger->success('facebook_unknown_error');
                $this->_helper->redirector('index');
            } catch (Exception $e) {
                $this->_helper->messenger->success('unknown_error');
                $this->_helper->redirector('index');
            }
        } else {
            $facebookLoginUrl = $facebook->getLoginUrl(array(
                'cancel_url' => $this->view->serverUrl() . $this->view->baseUrl() . $this->view->url(array(
                    'action' => 'facebook',
                    'controller' => 'login',
                    'module' => 'default',
                    'submit' => 'cancel'), Null, 1),
                'next' => $this->view->serverUrl() . $this->view->baseUrl() . $this->view->url( array(
                    'action' => 'facebook',
                    'controller' => 'login',
                    'module' => 'default',
                    'submit' => 'ok'), Null, 1),
                'req_perms' => 'email',
                'scope' => 'email,publish_stream',
            ));
            Header('Location: ' . $facebookLoginUrl);
        }

        if ($facebookProfile == null) {
            die(111); // TODO: need to solve this magic
        }

        if (!empty($currUser)) {
            $currUser->facebook_id = $facebookProfile['id'];
            $currUser->save();
            $this->_helper->messenger->success('facebook_connected_to_existed_user');
            $this->_helper->redirector->gotoSimple('index', 'login', 'default'); // if user is logged in he will be redirected to correct page according his role
        } else {
            $user = Model_UserTable::getInstance()->findOneBy('facebook_id', $facebookProfile['id']);
            if ($user) {
                $this->_autorize($user);
                $this->_redirectAfterLogin();
            } else {
                $sess->facebook = $facebook;
                $sess->facebookProfile = $facebookProfile;
                
                //$this->_helper->redirector->gotoSimple($action, 'signup', 'default');
                $this->_helper->redirector->gotoSimple('facebook-confirm', 'login', 'default');
            }
        }
    }

    public function facebookConfirmAction()
    {
        $sess = new Zend_Session_Namespace('Registration');
        if (!$sess->facebook or !$sess->facebookProfile or empty($sess->facebookProfile['id'])) {
            $this->_helper->redirector->gotoSimple('facebook', 'login', 'default');
        }

        if ($this->getRequest()->getParam('confirm')) {
            
            $user = Doctrine::getTable('Model_User')->create();
            $user->is_confirmed = 1;
            $user->is_active = 1;
            $user->first_name = $sess->facebookProfile['first_name'];
            $user->last_name = $sess->facebookProfile['last_name'];
            $user->email = $sess->facebookProfile['email'];
            $user->facebook_id = $sess->facebookProfile['id'];
            $user->save();

            $sess->facebook = Null;
            $sess->facebookProfile = Null;
            
            $this->_autorize($user);
            $this->_helper->redirector->gotoSimple('index', 'my-account', 'user');
        }

    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        
        Zend_Session::forgetMe();

        $this->_helper->messenger->success('logout_success');
        $this->_helper->redirector('index');
    }

    private function _autorize(Model_User $user)
    {
        $auth = Zend_Auth::getInstance();

        if ($user->isConfirmed() && $user->isActive()) {
            $auth->getStorage()->write($user->id);

            if ($this->getRequest()->getParam('remember') == 1) {

                $session = new Zend_Session_Namespace('Zend_User_RememberMe');
                $settings = $this->_helper->settings->getOptions();

                $session->setExpirationSeconds($settings['session']['expiration']);

                Zend_Session::rememberMe($settings['session']['remember']);
            }
        } else {
            $auth->clearIdentity();

            if (!$user->isConfirmed()) {
                $message = 'login_error_user_not_confirmed';
            }

            if (!$user->isActive()) {
                $message = 'login_error_user_account_blocked';
            }

            $this->_helper->messenger->error($message);
            $this->_helper->redirector->gotoSimple('index', 'login', 'default');
        }
    }
}
