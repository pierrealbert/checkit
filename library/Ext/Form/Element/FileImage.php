<?php

/**
 * Create file form element with image preview
 *
 * @category    Ext
 * @package     Ext_From
 * @subpackage  Ext_From_Element
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Form_Element_FileImage extends Zend_Form_Element_File
{
    public function __construct($spec, $options = null)
    {
        $this->setAttrib('class', 'input-text');
        parent::__construct($spec, $options);
    }

    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->addDecorator('FileImage')
                 ->addDecorator('Errors')
                 ->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
                 ->addDecorator('HtmlTag', array('tag' => 'dd'))
                 ->addDecorator('Label', array('tag' => 'dt'));
        }
        return $this;
    }
}
