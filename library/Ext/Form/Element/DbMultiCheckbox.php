<?php

/**
 * Create set of checkboxes, load entries from database
 *
 * @example
 * Example usage:
 * <code>
 * $categories = new Ext_Form_Element_DbMultiCheckbox('category_id',
 *       array(
 *           'label'=>'Category',
 *           'query' => \Doctrine\ORM\Query::create()->from('Ext_Entity_Category'),
 *           //'defaultValue' => false  // or
 *           'defaultValue' => '1',
 *           'valueColumn' => 'id'
 *           'nameColumn' => 'name'
 *           )
 *       );
 * </code>
 * @category    Ext
 * @package     Ext_From
 * @subpackage  Ext_From_Element
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Form_Element_DbMultiCheckbox extends Ext_Form_Element_DbMulti
{
    /**
     * Use formMultiCheckbox view helper by default
     * @var string
     */
    public $helper = 'formMultiCheckbox';

    /**
     * MultiCheckbox is an array of values by default
     * @var bool
     */
    protected $_isArray = true;

}
