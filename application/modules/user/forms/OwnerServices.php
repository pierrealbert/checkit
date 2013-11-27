<?php


class User_Form_OwnerServices extends Ext_Form
{
    public function init()
    {
        $this->addElement('hidden', 'request_type', array('decorators' => array('ViewHelper')));

        $this->addElement('text', 'phone', array(
            'required'   => false,
            'class'      => 'w300 phone-sign',
            'filters'    => array('StringTrim')
        ));

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $this->addElement('datePicker', 'availability', array(
            'JQueryParams' => array (
                'dateFormat' => 'dd/mm/yy',

                'class' => 'datepickerSmall',

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

            ),
        ));

        $applicationStartTime = new Zend_Date($settings->get('applicationStartTime'), Zend_Date::TIMES);
        $applicationEndTime = new Zend_Date($settings->get('applicationEndTime'), Zend_Date::TIMES);
        $iterTime = $applicationStartTime;
        $startTimeOptions = array();
        $startTimeOptions[''] = '--:--';
        while ($applicationEndTime->compare($iterTime) == 1) {
            $startTimeOptions[$iterTime->toString('HH:mm:ss', 'iso')] = $iterTime->toString(Zend_Date::TIME_SHORT);
            $iterTime->add('00:15:00', Zend_Date::TIMES);
        }

        $this->addElement('select', 'visit_time_begin', array(
            'multiOptions' => $startTimeOptions,
            'decorators' => array(array('ViewHelper')),
            'class' => 'pretty',
        ));

        $this->addElement('select', 'visit_time_end', array(
            'multiOptions' => $startTimeOptions,
            'decorators' => array(array('ViewHelper')),
            'class' => 'pretty',
        ));

        $specOptions = Model_UserRequest::getSpecialisationsList();

        $this->addElement('select', 'visit_spec', array(
            'multiOptions' => $specOptions,
            'decorators' => array(array('ViewHelper'), array('Errors')),
            'class' => 'pretty',
        ));

        $this->setDefaults(array(
            'request_type' => '1',
        ));

    }
}