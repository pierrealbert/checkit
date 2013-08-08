<?php

/**
 * Grid abstract adapter
 *
 * @category    Ext
 * @package     Grid
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
abstract class Ext_Grid_Adapter_Abstract
{
    /**
     *
     * @param Ext_Grid $grid 
     */
    abstract function getPaginator(Ext_Grid $grid);
}