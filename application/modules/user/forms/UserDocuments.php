<?php

class User_Form_UserDocuments extends Ext_Form
{
    protected $_residents;
    
    protected function _addGroupForResident($resident)
    {
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $documents = array(); // is an array of additional attributes with document type as a key
        if ($resident->resident_type == Model_UserResident::TYPE_STUDENT) {
            $documents['passport'] = array();
            $documents['student_id'] = array();
            if ($resident->monthly_income) {
                $documents['payslip'] = array();
            }
            if ($resident->monthly_income_guaranteed > 0) {
                $documents['tax_notice_guaranteed'] = array();
                $documents['payslip_guaranteed'] = array();
            }
        } elseif ($resident->resident_type == Model_UserResident::TYPE_EMPLOYEE) {
            $documents['passport'] = array();
            $documents['paycheck1'] = array();
            $documents['paycheck2'] = array();
            $documents['paycheck3'] = array('required' => False);
            $documents['contract'] = array();
            $documents['tax_notice'] = array();
            if ($resident->monthly_income_guaranteed > 0) {
                // TODO: only one of them should be required
                $documents['tax_notice_guaranteed'] = array();
                $documents['payslip_guaranteed'] = array();
            }
        } elseif ($resident->resident_type == Model_UserResident::TYPE_INDEPENDENT) {
            $documents['passport'] = array();
            $documents['tax_notice'] = array();
            $documents['inc_certificate'] = array();
            if ($resident->monthly_income_guaranteed > 0) {
                // TODO: only one of them should be required
                $documents['tax_notice_guaranteed'] = array();
                $documents['payslip_guaranteed'] = array();
            }
        } elseif ($resident->resident_type == Model_UserResident::TYPE_OTHER) {
            $documents['passport'] = array();
            $documents['tax_notice'] = array();
            $documents['payslip'] = array();
            if ($resident->monthly_income_guaranteed > 0) {
                // TODO: only one of them should be required
                $documents['tax_notice_guaranteed'] = array();
                $documents['payslip_guaranteed'] = array();
            }
        }

        $elements = array(); // is an array of form elements
        foreach ($documents as $documentType => $elementOptions) {
            if (!in_array($documentType, Model_UserResidentDocument::getTypes())) {
                throw new Zend_Form_Exception("Trying to create a form element for non-existent document type $documentType.");
            }
            $defaultOptions = array(
                'label' => "upload_{$documentType}_label",
                'documentType' => $documentType,
                'destination' => $settings->get('files.tmpPath'),
                'required' => True,
                'filters' => array(
                    new Ext_Filter_File_UniqueName(array('targetDir' => $settings->get('files.tmpPath'))),
                )
            );
            $name = $documentType . $resident->id;
            $this->addElement('file', $name, array_merge($defaultOptions, $elementOptions));
            $elements[$name] = $this->getElement($name);
        }
        $this->addDisplayGroup(array_keys($elements), 'resident_' . $resident->id, array('legend' => "Primary Resident ({$resident->resident_type})"));
    }
    
    public function init()
    {
        $this->setMethod('post');
        
		foreach($this->_residents as $resident) {
            $this->_addGroupForResident($resident);
			// $key = "resident_". $resident->id;
			// 
			// $subform = new User_Form_UserDocumentsSub(array("resident" => $resident));
            // if ($resident->is_primary) {
            //     $subform->setLegend("Primary Resident ({$resident->resident_type})");
            // } else {
            //     $subform->setLegend("Another Resident ({$resident->resident_type})");
            // }
			// $this->addSubForm($subform, $key);
		}

        $this->addElement('submit', 'save', array(
            'label'    => 'save',
        ));
	}
    
	public function setResidents($residents)
	{
		$this->_residents = $residents;
		return $this;
	}
}
