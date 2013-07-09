<?php

/**
 * Form with jQuery UI tabs support
 *
 * @category    Ext
 * @package     Ext_Form
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Form_UiTabForm extends Ext_Form
{
    protected $_defaultDisplayGroupClass = 'Ext_Form_DisplayGroup';

    /**
     * Decorators for rendering
     * @var array
     */
    protected $_decorators = array(            
        'FormElements',
        'UiTabs',
        'Form'
    );

    public function __construct($options = null)
    {
        $this->setDecorators($this->_decorators);
        
        $this->_disableLoadDefaultDecorators = true;

        parent::__construct($options);
    }

    public function setTabs($data)
    {
        $this->getDecorator('UiTabs')->setTabs($data);
    }

    public function addDisplayGroupButtons($buttons, $groupName = 'buttons', $decorators = array())
    {
        return parent::addDisplayGroupButtons($buttons, $groupName, array(
            'FormElements'
        ));
    }
}
