<?php

/**
 * Create radio element, load entries from database
 *
 * @example
 * Example usage:
 * <code>
 * $categories = new Ext_Form_Element_DbRadio('category_id',
 *       array(
 *           'label'    => 'Category',
 *           'query'    => Doctrine_Query::create()->from('Category'),
 *           //'defaultValue' => false  // or
 *           'defaultValue' => '1',
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
class Ext_Form_Element_DbMultiRadio extends Ext_Form_Element_DbMulti
{
    /**
     * Use formRadio view helper by default
     * @var string
     */
    public $helper = 'formRadio';

    /**
     * Load default decorators
     *
     * Disables "for" attribute of label if label decorator enabled.
     *
     * @return Zend_Form_Element_Radio
     */
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }
        parent::loadDefaultDecorators();
        $this->addDecorator('Label', array('tag' => 'dt',
                                           'disableFor' => true));
        return $this;
    }
}
