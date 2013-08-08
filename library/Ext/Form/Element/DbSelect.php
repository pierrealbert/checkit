<?php

/**
 * Create select dropdown, load entries from database
 *
 * @example
 * Example usage:
 * <code>
 * $categories = new Ext_Form_Element_DbSelect('category_id',
 *       array(
 *           'label'        => 'Category',
 *           'query'        => Doctrine_Query::create()->from('Category'),
 *           //'defaultValue' => false  // or
 *           'defaultValue' => '1',
 *           'defaultName'  => '-- select category'
 *           'valueColumn'  => 'id'
 *           'nameColumn'   => 'name'
 *           )
 *       );
 * </code>
 * @category    Ext
 * @package     Ext_From
 * @subpackage  Ext_From_Element
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Form_Element_DbSelect extends Ext_Form_Element_DbMulti
{
    /**
     * Use formSelect view helper by default
     * @var string
     */
    public $helper = 'formSelect';

}
