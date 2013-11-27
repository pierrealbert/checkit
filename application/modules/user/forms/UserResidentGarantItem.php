<?php
/**
 * UserResidentGarantItem.php.
 * @author: Andrew Boyko <andrew.boyko@zelpex.com>
 * @copyright: Copyright (c) 2012-2013, Zelpex Media Group <http://zelpex.com>
 * @category: Module
 * @package: UserResidentGarantItem.php
 */

class User_Form_UserResidentGarantItem extends Ext_Form_SubForm
{
    protected $key = 1;
    protected $residentId;

    public function init()
    {
        $this->key = $this->getAttrib('garant');
        $this->residentId = $this->getAttrib('residentId');

        $this->setElementsBelongTo("garant[{$this->residentId}][{$this->key}]");

        $this->addElement('hidden', 'id', array(
            'value' => $this->key
        ));

        $this->addElement('radio', 'garant', array(
            'label' => 'Garant',
            'required' => true,
            'separator' => '',
            'multiOptions' => array('yes' => 'Yes', 'no' => 'No'),
            'value' => '',
        ));
        $this->getElement('garant')->setDecorators(array(
            array('MrButtons', array('needAll' => false, 'labelClass' => 'btn-input-lite', 'inputClass' => 'garant input-pretty')),
            array('Label', array('tag'=>'label', 'separator'=>' ')),
            array('HtmlTag', array('tag' => 'div', 'class'=>'')),
            array('Errors'),
        ));

        $this->addElement('radio', 'type', array(
            'label' => 'garant_type',
            'required' => true,
            'separator' => '',
            'multiOptions' => Model_UserResidentGarant::getTypes(),
            'attribs' => array('class' => 'type'),
            'value' => '',
        ));
        $this->getElement('type')->setDecorators(array(
            array('MrButtons', array('needAll' => false, 'labelClass' => 'btn-input-lite', 'inputClass' => 'garant_type input-pretty')),
            array('Label', array('tag'=>'label', 'separator'=>' ')),
            array('HtmlTag', array('tag' => 'div', 'class'=>'')),
            array('Errors'),
        ));

        $this->addElement('text', 'amount', array(
            'label' => 'garant_amount',
            'required' => true,
            'attribs' => array('class' => ' garant_amount input-money', 'style' => ''),
            'filters'    => array('StringTrim', 'StripTags'),
        ));
        $this->getElement('amount')->setDecorators(array('ViewHelper', 'Label', 'Errors'));

        $this->addElement('text', 'company_name', array(
            'label'      => 'garant_company_name',
            'required'   => true,
            'filters'    => array('StringTrim', 'StripTags'),
            'attribs'	 => array('class' => 'garant_company_name', 'style' => 'width: 400px')
        ));
        $this->getElement('company_name')->setDecorators(array('ViewHelper', 'Label', 'Errors'));
    }

    /*public function setKey($num)
    {
        $this->key = (int) $num;
        return $this;
    }*/

} 