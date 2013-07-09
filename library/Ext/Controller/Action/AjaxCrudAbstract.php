<?php

/**
 * CRUD controller for ajax grid. Contains base CRUD logic (Create/Read/Update/Delete)
 *
 * @category    Ext
 * @package     Controllers
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
abstract class Ext_Controller_Action_AjaxCrudAbstract extends Zend_Controller_Action
{
    protected $_formName;
    protected $_entityName;
    protected $_gridName;
    protected $_messages = array(
        'entityAdded'    => 'entity_has_been_added',
        'entityUpdated'  => 'entity_has_been_updated',
        'entityDeleted'  => 'entity_has_been_deleted',
        'entityNotFound' => ''
    );
    protected $_isAjax = false;

    public function init()
    {
        $this->_isAjax = (bool) $this->getRequest()->isXmlHttpRequest();
        $this->_helper->getHelper('AjaxContext')
            ->addActionContext('list', 'json')
            ->addActionContext('add', 'json')
            ->addActionContext('edit', 'json')
            ->addActionContext('delete', 'json')
            ->initContext('json')
        ;
    }

    public function indexAction()
    {
        $this->view->grid = $this->_getGrid();
    }

    public function listAction()
    {
        if ($this->_isAjax) {
            $this->_getGrid()->sendResponse();
        } else {
            $this->view->grid = $this->_getGrid();
        }
    }

    public function addAction()
    {
        $this->_form();
    }

    public function editAction()
    {
        if (null == ($entity = $this->_findEntity())
            || !$this->_isAllowed($entity)
        ) {
            $this->_helper->error->notFound(
                !empty($this->_messages['entityNotFound']) 
                    ? $this->_messages['entityNotFound'] : ''
            );
        }
        $this->_form($entity);
    }

    public function deleteAction()
    {
        if (null == ($entity = $this->_findEntity())
            || !$this->_isAllowed($entity)
        ) {
            $this->_helper->error->notFound(
                !empty($this->_messages['entityNotFound'])
                    ? $this->_messages['entityNotFound'] : ''
            );
        }
        $entity->delete();
        $this->_messenger(
            $this->_messages['entityDeleted'],
            Ext_Controller_Action_Helper_Messenger::SUCCESS
        );
        $this->_redirectToIndex();
    }

    /**
     * @param Doctrine_Record $entity
     * @return void
     */
    protected function _form(Doctrine_Record $entity = null)
    {
        $form = $this->_getForm();

        if ($this->getRequest()->getParam('cancel')) {
            $this->_redirectToIndex();
        }

        if ($entity) {
            $form = $this->_fillForm($entity, $form);
        } else {
            $entity = $this->_createEntity();
        }

        $success = true;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                $this->_messenger(
                    $this->_messages[($entity->exists() ? 'entityUpdated' : 'entityAdded')],
                    Ext_Controller_Action_Helper_Messenger::SUCCESS
                );
                $this->_saveEntity($entity, $form);
                $this->_redirectToIndex();
            } else {
                $success = false;
            }
        }
        if (!($this->getRequest()->isPost() && $success)) {
            $this->view->form = ($this->_isAjax ? $form->render() : $form);
        }
        $this->view->success = $success;
    }

    /**
     * @param Doctrine_Record $entity
     * @param Zend_Form $form
     * @return void
     */
    protected function _saveEntity(Doctrine_Record $entity, Zend_Form $form)
    {
        $entity->merge($form->getValues());
        $entity->save();
    }

    /**
     * @param string $paramName
     * @return Doctrine_Record
     */
    protected function _findEntity($paramName = 'id')
    {
        return Doctrine::getTable($this->_entityName)->find($this->_getParam($paramName));
    }

    /**
     * @return Doctrine_Record
     */
    protected function _createEntity()
    {
        return new $this->_entityName;
    }

    /**
     * @param Doctrine_Record $entity
     * @param Zend_Form $form
     * @return Zend_Form
     */
    protected function _fillForm(Doctrine_Record $entity, Zend_Form $form)
    {
        $form->setDefaults($entity->toArray());
        return $form;
    }

    /**
     * @param Doctrine_Record $entity
     * @return bool
     */
    protected function _isAllowed(Doctrine_Record $entity)
    {
        return true;
    }

    /**
     * @return Zend_Form
     */
    protected function _getForm()
    {
        return new $this->_formName;
    }

    /**
     * @return array
     */
    protected function _getCustomFilters()
    {
        return array();
    }

    /**
     * @params array $options
     * @return array
     */
    protected function _getGrid($options = array())
    {
        $grid = new $this->_gridName($options);
        $grid->setEntityName($this->_entityName)->setCustomFilters($this->_getCustomFilters());
        return $grid;
    }

    /**
     * @param string $message
     * @param string $namespase
     * @param array $variables
     * @return void
     */
    protected function _messenger($message, $namespase, $variables = array())
    {
        if ($this->_isAjax) {
            $this->view->success = true;
            $this->view->message = $this->view->t($message);
        } else {
            $this->_helper->messenger($message, $namespase, $variables);
        }
    }

    /**
     * @param string $returnUrl
     * @return void
     */
    protected function _redirectToIndex($returnUrl = null)
    {
        if ($this->_isAjax) {
            return;
        }
        if ($returnUrl) {
            $this->_helper->redirector->gotoUrl($returnUrl);
        } else {
            $this->_helper->redirector->gotoSimple(
                'index',
                $this->getRequest()->getControllerName(),
                $this->getRequest()->getModuleName()
            );
        }
    }
}