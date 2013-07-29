<?php

class User_Form_UserDocuments extends Ext_Form
{
    protected $_residents;
    
    protected function _addSubs()
    {
		foreach($this->_residents as $resident) {
			$key = "resident_". $resident->id;
			
			$subform = new User_Form_UserDocumentsSub(array("resident" => $resident));
            if ($resident->is_primary) {
                $subform->setLegend("Primary Resident ({$resident->resident_type})");
            } else {
                $subform->setLegend("Another Resident ({$resident->resident_type})");
            }
			$this->addSubForm($subform, $key);
		}
    }
    
    public function init()
    {
        $this->setMethod('post');
        
        $this->_addSubs();

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
