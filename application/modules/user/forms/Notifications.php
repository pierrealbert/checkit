<?php

class User_Form_Notifications extends Ext_Form
{
    protected $_currentUser = null;
    
    public function init()
    {
        $options = Model_UserSettings::getNotificationOptions();
        
        $this->addElement('radioButtons', 'visit_alerts', array(
            'label'        => 'visit_alerts',
            'multiOptions' => $options['visit_alerts']
        )); 
        
        $this->addElement('radioButtons', 'visit_alerts_period', array(
            'label'        => 'visit_alerts_period',
            'multiOptions' => $options['visit_alerts_period']
        ));         
        
        $this->addElement('radioButtons', 'profile_alerts', array(
            'label'        => 'profile_alerts',
            'multiOptions' => $options['profile_alerts']
        )); 
               
        $chbox = new Zend_Form_Element_MultiCheckbox('secondary_visit_alerts');
        $chbox->setSeparator('')
            ->setLabel('secondary_visit_alerts')
            ->addMultiOptions($options['secondary_visit_alerts'])
            ->setDecorators(array(
                array('MchBox', array('needAll' => false)),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-white')),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            ));
        $this->addElement($chbox);        
        
        $this->addElement('submit', 'save', array(
            'label'     => 'Enregistrer les modifications',
            'class'     => 'btn btn-red right'
        ));        
    }
}