<?php

class User_Form_UserDocumentsSub extends Ext_Form_SubForm
{
    protected $_resident;

    protected function _addFileElements($elements)
    {
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        
        foreach ($elements as $documentType => $elementOptions) {
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
            
            $this->addElement('file', $documentType . $this->_resident->id, array_merge($defaultOptions, $elementOptions));
        }
    }
    
    public function init()
    {
		$this->setElementsBelongTo("resident_data[{$this->_resident->id}]");

        $elements = array();
        if ($this->_resident->resident_type == Model_UserResident::TYPE_STUDENT) {
            $elements['passport'] = array();
            $elements['student_id'] = array();
            if ($this->_resident->monthly_income) {
                $elements['payslip'] = array();
            }
            if ($this->_resident->monthly_income_guaranteed > 0) {
                $elements['tax_notice_guaranteed'] = array();
                $elements['payslip_guaranteed'] = array();
            }
        } elseif ($this->_resident->resident_type == Model_UserResident::TYPE_EMPLOYEE) {
            $elements['passport'] = array();
            $elements['paycheck1'] = array();
            $elements['paycheck2'] = array();
            $elements['paycheck3'] = array('required' => False);
            $elements['contract'] = array();
            $elements['tax_notice'] = array();
            if ($this->_resident->monthly_income_guaranteed > 0) {
                // TODO: only one of them should be required
                $elements['tax_notice_guaranteed'] = array();
                $elements['payslip_guaranteed'] = array();
            }
        } elseif ($this->_resident->resident_type == Model_UserResident::TYPE_INDEPENDENT) {
            $elements['passport'] = array();
            $elements['tax_notice'] = array();
            $elements['inc_certificate'] = array();
            if ($this->_resident->monthly_income_guaranteed > 0) {
                // TODO: only one of them should be required
                $elements['tax_notice_guaranteed'] = array();
                $elements['payslip_guaranteed'] = array();
            }
        } elseif ($this->_resident->resident_type == Model_UserResident::TYPE_OTHER) {
            $elements['passport'] = array();
            $elements['tax_notice'] = array();
            $elements['payslip'] = array();
            if ($this->_resident->monthly_income_guaranteed > 0) {
                // TODO: only one of them should be required
                $elements['tax_notice_guaranteed'] = array();
                $elements['payslip_guaranteed'] = array();
            }
        }
        $this->_addFileElements($elements);
	}
    
	public function setResident($resident)
	{
		$this->_resident = $resident;
		return $this;
	}

    public function getResident()
    {
        return $this->_resident;
    }
}

