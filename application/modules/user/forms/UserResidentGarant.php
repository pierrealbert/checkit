<?php
/**
 * User_Form_UserResidentGarant.php.
 * @author: Andrew Boyko <andrew.boyko@zelpex.com>
 * @copyright: Copyright (c) 2012-2013, Zelpex Media Group <http://zelpex.com>
 * @category: Module
 * @package: User_Form_UserResidentGarant.php
 */

class User_Form_UserResidentGarant extends Ext_Form
{
    protected $_count = 1;
    protected $_type = '';
    protected $_resident;

    public function __construct($count, $resident)
    {
        $this->setAttrib('id', 'form-user-resident-garant');
        $this->_resident = $resident;
        parent::__construct();

        $this->addGarantSubForm($count);
    }

    public function addGarantSubForm($count)
    {
        for ($i = 0; $i < $count; $i++) {
            $num = $i + 1;
            $key = "garant_" . $this->_resident->id . '_' . $num;

            $subform = new User_Form_UserResidentGarantItem(array("garant" => $num, 'residentId' => $this->_resident->id));
            $this->addSubForm($subform, $key)
                ->getSubForm($key);
        }
    }

    /* set garants */
    public function setGarants($garants)
    {
        if (!$garants) {
            return false;
        }

        if (is_object($garants)) {
            $garants = $garants->toArray();
        }

        foreach ($garants as $key => $garant) {
            $subform = $this->getSubForm('garant_' . $this->_resident->id . '_' . ($key + 1));
            if ($subform) {
                $subform->setDefaults($garant);
            }
        }
    }

    public function _preValidation($data)
    {

        foreach ($data['garant'] as $residentId => $r) {
            foreach($r as $garantId => $g) {
                $subForm = $this->getSubForm('garant_'.$residentId.'_'.$garantId);
                if (!$subForm) {
                    continue;
                }

                if (!isset($g['garant']) || ($g['garant'] == 'no')) {
                    $subForm->getElement('type')->setRequired(false);
                    $subForm->getElement('amount')->setRequired(false);
                    $subForm->getElement('company_name')->setRequired(false);
                }

                if (isset($g['type'])) {
                    if ($g['type'] == Model_UserResidentGarant::ORGANIZATION) {
                        $subForm->getElement('amount')->setRequired(false);
                        $subForm->getElement('company_name')->setRequired(true);
                    } else {
                        $subForm->getElement('company_name')->setRequired(false);
                        $subForm->getElement('amount')->setRequired(true);
                    }
                }
            }
        }
    }

    public function isValid($data)
    {
        $this->_preValidation($data);

        return parent::isValid($data);
    }
} 