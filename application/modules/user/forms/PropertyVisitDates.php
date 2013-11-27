<?php


class User_Form_PropertyVisitDates extends Ext_Form
{
    public function init()
    {
        $this->addElement('text', 'phone', array(
            'required'   => false,
            'class'      => 'w300 phone-sign',
            'filters'    => array('StringTrim')
        ));

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $this->addElement('datePicker', 'availability', array(
            'JQueryParams' => array (
                'dateFormat' => 'dd/mm/yy',

                'monthNames' => array('Janvier','Février','Mars','Avril','Mai','Juin', 'Juillet','Août','Septembre','Octobre','Novembre','Décembre'),
                'monthNamesShort' => array('Jan','Fév','Mar','Avr','Mai','Jun', 'Jul','Aoû','Sep','Oct','Nov','Déc'),
                'dayNames' => array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'),
                'dayNamesShort' => array('Dim','Lun','Mar','Mer','Jeu','Ven','Sam'),
                'dayNamesMin' => array('Di','Lu','Ma','Me','Je','Ve','Sa'),

                'firstDay' => 1,
                'prevText' => '<',
                'prevStatus' => 'Voir le mois précédent',
                'prevJumpText' => '&#x3c;&#x3c;',
                'prevJumpStatus' => 'Voir l\'année précédent',
                'nextText' => '>',
                'nextStatus' => 'Voir le mois suivant',
                'nextJumpText' => '&#x3e;&#x3e;',
                'nextJumpStatus' => 'Voir l\'année suivant',
                'currentText' => 'Courant',
                'currentStatus' => 'Voir le mois courant',
                'todayText' => 'Aujourd\'hui',
                'todayStatus' => 'Voir aujourd\'hui',
                'clearText' => 'Effacer',
                'clearStatus' => 'Effacer la date sélectionnée',
                'closeText' => 'Fermer',
                'closeStatus' => 'Fermer sans modifier',
                'yearStatus' => 'Voir une autre année',
                'monthStatus' => 'Voir un autre mois',
                'weekText' => 'Sm',
                'weekStatus' => 'Semaine de l\'année',
                'dayStatus' => '\'Choisir\' le DD d MM',
                'defaultStatus' => 'Choisir la date',

            )
        ));

        $this->addElement('submit', 'next', array(
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
}