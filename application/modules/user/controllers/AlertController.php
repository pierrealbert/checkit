<?php

class User_AlertController extends Core_Controller_Action_UserDashboard
{
    private function getAlert($user)
    {
        $alert = Doctrine::getTable('Model_Alert')->findOneById($this->_getParam('item', ''));
        if (!$alert || ($alert && $alert->user_id != $user->id)) {
            return false;
        }

        return $alert;
    }

    public function closeAlertAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $alert = $this->getAlert($this->_currUser);
        $result = array('error' => true);

        if ($alert) {
            $alert->is_read = true;
            $alert->save();
            $result['error'] = false;
        }

        $this->_helper->json->sendJson($result);
    }
}

