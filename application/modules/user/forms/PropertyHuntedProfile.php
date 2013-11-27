<?php


class User_Form_PropertyHuntedProfile extends Ext_Form
{
    public function init()
    {
        $multiCheckboxses = self::getMultiCheckboxses();

        $chbox = new Zend_Form_Element_MultiCheckbox('hunted_profile');
        $chbox->setSeparator('')
            ->setLabel('Profil recherchè')
            ->addMultiOptions($multiCheckboxses['hunted_profile'])
            ->setDecorators(array(
                array('MchBox', array('labelClass' => 'btn-input-lite', 'needAll' => false)),
                array('Label', array('tag'=>'label', 'separator'=>' ', 'class' => 'name-title-black')),
                array('HtmlTag', array('tag' => 'div', 'class'=>'box-universal')),
            ));
        $this->addElement($chbox);

        $this->addElement('radioButtons', 'is_roomate', array(
                'label'        => 'is_roomate',
                'required'     => false,
                'multiOptions' => array(
                    '0' => 'Non',
                    '1' => 'Oui'
                ))
        );

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