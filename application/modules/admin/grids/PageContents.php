<?php

class Admin_Grid_PageContents extends Ext_Grid
{
    protected $_gridName = 'pageContents';

    protected $_columns = array(
        'id' => array(
            'header'    => 'id',
            'sortable'  => true
        ),
        'title' => array(
            'header'    => 'title',
            'sortable'  => true
        ),
        'slug' => array(
            'header'    => 'slug',
            'sortable'  => true
        ),
        'actions' => array(
            'header' => 'actions',
            'actions' => array(
                array(
                    'label' => 'edit',
                    'url'   => array(
                        'module'     => 'admin',
                        'controller' => 'page-content',
                        'action'     => 'edit',
                        'id'         => 'id'
                    )
                ),
                array(
                    'label' => 'delete',
                    'url'   => array(
                        'module'     => 'admin',
                        'controller' => 'page-content',
                        'action'     => 'delete',
                        'id'         => 'id'
                    )
                )
            )
        )
    );

    public function init()
    {
        $query = Doctrine_Query::create()->from('Model_PageContent');

        $this->setAdapter(new Ext_Grid_Adapter_DoctrineQuery($query));
        parent::init();
    }
}