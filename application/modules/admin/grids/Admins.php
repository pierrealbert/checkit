<?php

class Admin_Grid_Admins extends Ext_Grid
{
    protected $_gridName = 'admins';

    protected $_columns = array(
        'id' => array(
            'header'    => 'id',
            'sortable'  => true
        ),
        'username' => array(
            'header'    => 'username',
            'field'     => 'username',
            'sortable'  => true
        ),
        'last_name' => array(
            'header'    => 'name',
            'callback'  => 'name',
            'sortable'  => true
        ),
        'email' => array(
            'header'    => 'email',
            'sortable'  => true,
        ),
        'is_active' => array(
            'header'    => 'is_active',
            'sortable'  => true,
            'helpers' => array(
                'replace' => array('%value%', array('no', 'yes'))
            )
        ),
        'actions' => array(
            'header' => 'Actions',
            'actions' => array(
                array(
                    'callback' => 'active'
                ),
                array(
                    'label' => 'Edit',
                    'url'   => array(
                        'module'     => 'admin',
                        'controller' => 'admins',
                        'action'     => 'edit',
                        'id'         => 'id'
                    )
                ),
                array(
                    'label'     => 'Delete',
                    'attribs'   => array(
                        'class' => 'delete'
                    ),
                    'url'   => array(
                        'module'     => 'admin',
                        'controller' => 'admins',
                        'action'     => 'delete',
                        'id'         => 'id'
                    )
                )
            )
        )
    );
  
    public function _nameCallback($entity)
    {
        return $this->getView()->escape($entity->getFullName());
    }

    public function _activeCallback($entity)
    {
        if ($entity->is_active) {
            $title  = $this->getView()->translate('block');
            $action = 'block';
        } else {
            $title  = $this->getView()->translate('activate');
            $action = 'activate';
        }

        return $this->getView()->anchor(array(
            'urlOptions' => array(
                'module'        => 'admin',
                'controller'    => 'admins',
                'action'        => $action,
                'id'            => $entity->id
        ), 'reset' => true), $title);
    }
    
    public function init()
    {
        $query = Doctrine_Query::create()->from('Model_Admin');
        
        $this->setAdapter(new Ext_Grid_Adapter_DoctrineQuery($query));

        parent::init();
    }
}