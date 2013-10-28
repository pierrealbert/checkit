<?php

class User_FavoritesController extends Core_Controller_Action_UserDashboard
{    
    public function deleteAction()
    {
        $favorite = Model_FavoriteTable::getInstance()->find(array(
            'user_id'       => $this->_currUser->id,
            'property_id'   => (int) $this->getRequest()->getParam('id')
        ));
        
        if (!$favorite) {
            $this->_helper->error->notFound();
        }        
        
        $favorite->delete();
        
        $return = $this->_getParam('return') ? $this->_getParam('return') : 'index';
        
        $this->_helper->redirector->gotoSimple('index', $return, 'user');
    }    
}

