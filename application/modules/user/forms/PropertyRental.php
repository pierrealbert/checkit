<?php


class User_Form_PropertyRental extends Ext_Form
{
    protected $_user = null;

    public function __construct($curUser) {
        $this->_user = $curUser;

        parent::__construct();
    }

    public function init()
    {
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        
        $this->addElement('text', 'title', array(
            'label'      => 'location_title',
            'required'   => true,
            'class'      => 'w300',
            'filters'    => array('StringTrim')
        ));
        
        $this->addElement('text', 'amount_of_rent_excluding_charges', array(
            'label'      => 'amount_of_rent_excluding_charges',
            'allowEmpty' => false,
            'placeholder' => 'Montant en euros',
            'required'   => true,
            'validators' => array(new Zend_Validate_GreaterThan(array('min' => 0))),            
        ));        
        $this->getElement('amount_of_rent_excluding_charges')->removeDecorator('HtmlTag');

        $this->addElement('text', 'amount_of_charges', array(
            'label'      => 'amount_of_charges',
            'allowEmpty' => false,
            'required'   => true,
            'placeholder' => 'Montant en euros',
            'validators' => array(new Zend_Validate_GreaterThan(array('min' => 0))),
        ));

        if ($this->_user->is_premium == 1) {
            $this->addElement('text', 'honoraire', array(
                'label'      => 'Montant des honoraires',
                'allowEmpty' => false,
                'required'   => true,
                'placeholder' => 'Montant en euros',
                'validators' => array(new Zend_Validate_GreaterThan(array('min' => 0))),
            ));
        }

        $radio = new Zend_Form_Element_Radio('is_furnished');
        $radio->setSeparator('')
            ->setRequired(true)
            ->setLabel('Mobilier')
            ->addMultiOptions(array(
                1 => 'Meublé',
                0 => 'Vide'
            ))
            ->setDecorators(array(
                array('MrButtons', array('needAll' => false, 'labelClass' => 'btn-input-lite')),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-white')),
                array('Errors'),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            ));
        $this->addElement($radio);
        
        // -------------------------------------------------------------------

        $leaseDuration = new Zend_Form_Element_Radio('lease_duration');                      
        $leaseDuration->setSeparator('')
            ->setRequired(true)
            ->setLabel('lease_duration') 
            ->addMultiOptions(array(
                1 => "1 mois",
                2 => "2 mois",
                3 => "3 mois",
                4 => "4 mois",
                5 => "5 mois",
                6 => "6 mois",    
        ));
        $leaseDuration->setDecorators(array(
            array('MrButtons', array('needAll' => false, 'labelClass' => 'btn-input-lite')),
            array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-white')),
            array('Errors'),
            array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
        ));    
        $this->addElement($leaseDuration);        
        // -------------------------------------------------------------------
        
        $deposit = new Zend_Form_Element_Radio('deposit');
        $deposit->setSeparator('')
            ->setRequired(true)
            ->setLabel('deposit')
            ->addMultiOptions(array(
                1 => '1 mois',
                2 => '2 mois'
            ))               
            ->setDecorators(array(
                array('MrButtons', array('needAll' => false, 'labelClass' => 'btn-input-lite')),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-white')),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            ));
        $this->addElement($deposit);
        
        // -------------------------------------------------------------------
        
        $availability = new Zend_Form_Element_Radio('availability_select');
        $availability->setSeparator('')
            ->setLabel('Dépôt de garantie')
            ->setRequired(true)
            ->setAttrib('allowEmpty', false)
            ->addMultiOptions(array(
                'now' => 'Immédiatement', 
                'date' => 'select_date'
            ))->setDecorators(array(
                array('MrButtons', array(
                    'needAll' => false, 
                    'labelClass' => 'btn-input-lite',
                    'appendPart' => array(
                        1 => array(
                            'html' => '<label for="availability_select_date" class="btn-input-gray-lite label-date icon-calendar-gray"></label>',
                            'pre' => '<span class="separator">ou à partir de</span><div class="labels-group standard-search-date-pick">',
                            'post' => '</div>'
                        )
                    )
                )),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-white')),
                array('Errors'),                
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal box-short')),
            ));;

        $this->addElement($availability);

        $this->addElement('datePicker', 'availability', array(
            'JQueryParams' => array (
                //'dateFormat' => $settings->get('dateFormat.picker.jquery'),
                'dateFormat' => 'dd/mm/yy',
                'nextText' => '',
                'prevText' => '',
            )
        ));


        $this->addElement('submit', 'next', array(
            'class' => 'ui-state-default ui-corner-all'
        ));
    }
}