<?php

/**
 * Grid array adapter
 *
 * @category    Ext
 * @package     Grid
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Grid_Adapter_Array extends Ext_Grid_Adapter_Abstract
{
    /**
     * @var array
     */
    protected $_array;

    /**
     * @param $array
     */
    public function __construct($array)
    {
        $this->_array = $array;
    }

    /**
     * Return paginator
     *
     * @return Zend_Paginator
     */
    public function getPaginator(Ext_Grid $grid)
    {
        $paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Array($this->_array));
        $paginator->setItemCountPerPage($grid->getPerPage());
        $paginator->setCurrentPageNumber($grid->getCurrentPageNumber());
        return $paginator;
    }
}