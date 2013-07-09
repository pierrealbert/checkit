<?php

class Admin_Grid_Users extends Ext_Grid
{
    protected $_gridName = 'users';

    protected $_filtersEnabled = true;

    protected $_massActionsEnabled = true;

    protected $_massActions = array(
        'delete' => 'delete_users',
    );
    
    protected $_columns = array(
        'id' => array(
            'header'    => 'id',
            'sortable'  => true
        ),
        'email' => array(
            'header'    => 'email',
            'filter'    => 'text',
            'sortable'  => true
        ),
        'username' => array(
            'header'    => 'username',
            'filter'    => 'text',
            'sortable'  => true
        ),
        'first_name' => array(
            'header'    => 'full_name',
            'filter'    => 'text',
            'callback'  => 'fullname',
            'sortable'  => true
        ),
        'country' => array(
            'header'    => 'country',
            'callback'  => 'country',
            'sortable'  => true
        ),
        'is_active' => array(
            'header'    => 'is_active',
            'sortable'  => true,
            'helpers' => array(
                'replace' => array('%value%', array('no', 'yes'))
            ),
            'filter'         => 'select',
            'filter_options' => array()
        ),
        'is_confirmed' => array(
            'header'    => 'is_confirmed',
            'sortable'  => true,
            'helpers' => array(
                'replace' => array('%value%', array('no', 'yes'))
            ),
            'filter'        => 'select',
            'filter_options' => array()
        ),
        'created_at' => array(
            'header' => 'created_at',
            'helpers' => array(
                'date' => array('%value%')
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
                        'controller' => 'users',
                        'action'     => 'edit',
                        'id'         => 'id'
                    )
                ),
                array(
                    'label' => 'Delete',
                    'url'   => array(
                        'module'     => 'admin',
                        'controller' => 'users',
                        'action'     => 'delete',
                        'id'         => 'id'
                    )
                )
            )
        )
    );

    public function _fullnameCallback($entity)
    {
        return $this->getView()->escape($entity->last_name . ', ' . $entity->first_name);
    }

    public function _countryCallback($entity)
    {
        if ($entity->country) {
            return Zend_Locale::getTranslation($entity->country, 'territory');
        }
        return '-';
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
                'controller'    => 'users',
                'action'        => $action,
                'id'            => $entity->id
        ), 'reset' => true), $title);
    }
    
    public function init()
    {
        $query = Doctrine_Query::create()->from('Model_User');

        

        $this->setAdapter(new Ext_Grid_Adapter_DoctrineQuery($query));

        $this->_setFilterOptions();

        parent::init();
    }

    protected function _setFilterOptions()
    {
        $filterOptions = array(
            ''  => '-',
            '0' => $this->getView()->translate('no'),
            '1' => $this->getView()->translate('yes')
        );

        $this->_columns['is_active']['filter_options']    = $filterOptions;
        $this->_columns['is_confirmed']['filter_options'] = $filterOptions;
    }
}