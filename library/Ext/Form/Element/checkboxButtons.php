<?php

class Ext_Form_Element_CheckboxButtons extends Zend_Form_Element_Radio
{
    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $this->setDecorators(array(
                array('MchBox', array('needAll' => false, 'labelClass' => 'btn-input-lite')),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-white')),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            )); 
        }
        return $this;
    }        
}