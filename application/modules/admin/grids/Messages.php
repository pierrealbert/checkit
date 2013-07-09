<?php

class Admin_Grid_Messages extends Ext_Grid
{
    protected $_gridName            = 'messages';
    protected $_filtersEnabled      = true;
    protected $_sortColumn          = 'id';
    protected $_sortDirection       = 'desc';

    protected $_columns = array(
        'id' => array(
            'header'    => 'id',
            'sortable'  => true
        ),
        'Sender.username' => array(
            'header'            => 'sender',
            'filter'            => 'text',
            'relation_alias'    => 's',
            'callback'          => 'senderName',
            'filter_callback'   => 'senderName',
            'sortable'          => true,
        ),
        'Receiver.username' => array(
            'header'            => 'receiver',
            'filter'            => 'text',
            'relation_alias'    => 'r',
            'callback'          => 'receiverName',
            'filter_callback'   => 'receiverName',
            'sortable'          => true,
        ),
        'title' => array(
            'header'    => 'title',
            'filter'    => 'text',
            'sortable'  => true
        ),
        'created_at' => array(
            'header' => 'created_at',
            'helpers' => array(
                'dateTime' => array('%value%')
            )
        ),
        'actions' => array(
            'header' => 'Actions',
            'actions' => array(
                array(
                    'label' => 'view',
                    'url'   => array(
                        'module'     => 'admin',
                        'controller' => 'messages',
                        'action'     => 'view',
                        'id'         => 'id'
                    )
                )
            )
        )        
    );

    public function _senderNameCallback(Model_UserMessage $message)
    {        
        return $this->getView()->anchor(array(
            'urlOptions' => array(
                'module'        => 'admin',
                'controller'    => 'users',
                'action'        => 'edit',
                'id'            => $message->sender_id
        ), 'reset' => true), $message->Sender->getFullName());        
    }

    public function _senderNameFilterCallback(Doctrine_Query $query, $value)
    {        
        $query->andWhere('s.first_name LIKE ? OR s.last_name LIKE ?', array(
            '%' . $value . '%', 
            '%' . $value . '%'
        ));
    }

    public function _receiverNameCallback(Model_UserMessage $message)
    {
        return $this->getView()->anchor(array(
            'urlOptions' => array(
                'module'        => 'admin',
                'controller'    => 'users',
                'action'        => 'edit',
                'id'            => $message->receiver_id
        ), 'reset' => true), $message->Receiver->getFullName());  
    }

    public function _receiverNameFilterCallback(Doctrine_Query $query, $value)
    {
        $query->andWhere('r.first_name LIKE ? OR r.last_name LIKE ?', array(
            '%' . $value . '%', 
            '%' . $value . '%'
        ));
    }

    public function init()
    {
        $query = Doctrine_Query::create()->from('Model_UserMessage');
        
        $query->leftJoin('Model_UserMessage.Sender s')
            ->leftJoin('Model_UserMessage.Receiver r');       
        
        $this->setAdapter(new Ext_Grid_Adapter_DoctrineQuery($query));

        parent::init();
    }
}