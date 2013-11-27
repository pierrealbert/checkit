<?php

class User_Form_UserResidentDocument extends Ext_Form
{
    protected $_count = 1;
    protected $_residents;

    public function __construct($count = null) {

        $this->setAttrib('id', 'form-user-resident-document');

        parent::__construct();


        $this->addElement('submit', 'submitBtn', array(
            'label' => 'submit'
        ));

    }

    public function addDocumentSubForms($count)
    {
        for ($i = 0; $i < $count; $i++) {
            $num = $i + 1;
            $key = "member_" . $num;

            $subform = new User_Form_UserResidentDocumentItem(array("doc" => $num));
            $this->addSubForm($subform, $key)
                ->getSubForm($key);
        }
    }

    public function setCount($count)
    {
        $this->_count = $count;
        return $this;
    }


    public function setDocuments($documents)
    {
        if (!$documents) {
            return false;
        }

        if (is_object($documents)) {
            $documents = $documents->toArray();
        }

        foreach ($documents as $key => $document) {
            $subform = $this->getSubForm('doc_' . ($key + 1));
            if ($subform) {
                $subform->setDefaults($document);
            }
        }
    }

    public function _preValidation($data)
    {

    }

    public function isValid($data)
    {
        $this->_preValidation($data);

        return parent::isValid($data);
    }
} 