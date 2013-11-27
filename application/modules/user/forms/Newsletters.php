<?php

class User_Form_Newsletters extends Ext_Form
{
    protected $_currentUser = null;
    
    public function init()
    {       
        $options = array(
            1 => "Oui, je souhaite m'inscrire", 
            0 => 'Non merci'
        );
        
        $this->addElement('radioButtons', 'newsletters', array(
            'label'        => 'newsletters',
            'multiOptions' => $options
        )); 
        
        $this->addElement('radioButtons', 'offers', array(
            'label'        => 'offers',
            'multiOptions' => $options
        ));       
        
        $this->addElement('submit', 'save', array(
            'label'     => 'Enregistrer les modifications',
            'class'     => 'btn btn-red right'
        ));        
    }
}