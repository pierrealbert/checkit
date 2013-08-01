<?php


class User_Form_PropertyHuntedProfile extends Ext_Form
{
    public function init()
    {
        $multiCheckboxses = self::getMultiCheckboxses();

        $this->addElement('multiCheckbox', 'hunted_profile', array(
            'label'         => 'hunted_profile',
            'multiOptions'  => $multiCheckboxses['hunted_profile'],
            'separator'     => '',
        ));

        $this->addElement('checkbox', 'is_roomate', array(
            'label'          => 'is_roomate',
            'uncheckedValue' => '0',
            'checkedValue'   => '1'
        ));

        $this->addElement('submit', 'next', array(
            'class'     => 'ui-state-default ui-corner-all'
        ));
    }
    public static function getMultiCheckboxses()
    {
        return array(
            'hunted_profile' => array(
                'is_r_student'     => 'student',
                'is_r_employee'    => 'employee',
                'is_r_independent' => 'independent',
                'is_r_other'       => 'other',
            ),
        );
    }

    private function initMultiCheckboxses($data)
    {
        $multiCheckboxses = self::getMultiCheckboxses();

        foreach ($multiCheckboxses as $elementName => $itemsList) {
            $data[$elementName] = array();

            foreach ($itemsList as $key => $value) {
                if (1 == $data[$key]) {
                    $data[$elementName][] = $key;
                }
            }
        }

        return $data;
    }

    private function extractMultiCheckboxses($data)
    {
        $multiCheckboxses = self::getMultiCheckboxses();

        foreach ($multiCheckboxses as $elementName => $itemsList) {

            foreach ($itemsList as $key => $value) {
                $data[$key] = 0;

                if (in_array($key, $data[$elementName])) {
                    $data[$key] = 1;
                }
            }
        }
        return $data;
    }

    public function initData($data)
    {
        $this->populate($this->initMultiCheckboxses($data));
    }

    public function getData()
    {
        $data = $this->getValues();

        return $this->extractMultiCheckboxses($data);
    }
}