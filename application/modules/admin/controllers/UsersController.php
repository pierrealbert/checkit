<?php

class Admin_UsersController extends Ext_Controller_Action_Crud
{
    protected $_entityName  = 'Model_User';

    protected $_formName    = 'Admin_Form_User';

    protected $_gridName    = 'Admin_Grid_Users';

    public function blockAction()
    {
        $user = $this->_findEntity();

        $user->block();

        $this->_helper->messenger->success('user_has_been_blocked');

        $this->_helper->redirector->gotoSimple('index');
    }

    public function activateAction()
    {
        $user = $this->_findEntity();

        $user->activate();

        $this->_helper->messenger->success('user_has_been_activated');

        $this->_helper->redirector->gotoSimple('index');
    }
    
    protected function _massDelete()
    {
        $deletedUsers = array();
        
        foreach ($this->_getParam('ids') as $id) {
            $user = $this->_findEntity($id);
            $user->delete();

            $deletedUsers[] = $user->getFullName();
        }
        
        $this->_helper->messenger->success('%users% has been deleted', array(
            'users' => implode(', ', $deletedUsers)
        ));
        
        $this->_helper->redirector->gotoSimple('index');
    }

    protected function _saveEntity(Doctrine_Record $entity, Zend_Form $form)
    {
        $values = $form->getValues();

        if (empty($values['password'])) {
            unset($values['password']);
        }
 
        $entity->merge($values);

        $entity->save();
    }
}
