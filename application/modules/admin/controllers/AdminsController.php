<?php

class Admin_AdminsController extends Ext_Controller_Action_Crud
{
    protected $_entityName  = 'Model_Admin';

    protected $_gridName    = 'Admin_Grid_Admins';

    protected $_formName    = 'Admin_Form_Admin';

    protected $_messages = array(
        'entityAdded'   => 'admin_added',
        'entityUpdated' => 'admin_updated',
        'entityDeleted' => 'admin_deleted'
    );

    public function blockAction()
    {
        $admin = $this->_findEntity();

        $admin->block();
        
        $this->_helper->messenger->success('admin_has_been_blocked');

        $this->_helper->redirector->gotoSimple('index');
    }

    public function activateAction()
    {
        $admin = $this->_findEntity();

        $admin->activate();

        $this->_helper->messenger->success('admin_has_been_activated');

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