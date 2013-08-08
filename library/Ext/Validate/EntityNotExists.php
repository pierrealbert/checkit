<?php

/**
 * Validator to check if some entity is already exists
 *
 * @category    Ext
 * @package     Ext_Validate
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Validate_EntityNotExists extends Zend_Validate_Abstract
{
    const EXISTS = 'exists';

    protected $_messageTemplates = array(
        self::EXISTS => "%value% already exists"
    );

    protected $_entityName;
    
    protected $_entityField;

    protected $_exclude;

    public function __construct($entityName = null, $entityField = null, array $exclude = array())
    {
        if (!is_null($entityName)) {
           $this->setEntityName($entityName);
        }

        if (!is_null($entityField)) {
            $this->setEntityField($entityField);
        }

        if ($exclude) {
            $this->setExclude($exclude);
        }
    }

    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return boolean
     * @throws Zend_Validate_Exception If validation of $value is impossible
     */
    public function isValid($value)
    {
        $query = Doctrine_Query::create()
            ->select()
            ->from($this->getEntityName())
            ->addWhere($this->getEntityField() . ' = ?', $value);

        if (!empty ($this->_exclude)) {
            foreach ($this->_exclude as $fieldName => $excludeValue) {
                $query->andWhere($fieldName . ' <> ?', $excludeValue);                    
            }
        }
        
        if ((bool) $query->execute()->count()) {
            $this->_error(self::EXISTS, $value);
            return false;
        }
        
        return true;
    }

    /**
     *
     * @return string
     */
    public function getEntityName()
    {
        return $this->_entityName;
    }

    /**
     *
     * @param string $entityName
     * @return Ext_Validate_EntityNotExists
     */
    public function setEntityName($entityName)
    {
        $this->_entityName = $entityName;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getEntityField()
    {
        return $this->_entityField;
    }

    /**
     *
     * @param string $entityField
     * @return Ext_Validate_EntityNotExists
     */
    public function setEntityField($entityField)
    {
        $this->_entityField = $entityField;
        return $this;
    }

    /**
     * @param  $key
     * @param  $value
     * @return Ext_Validate_EntityNotExists
     */
    public function addExclusion($key, $value)
    {
        $this->_exclude[$key] = $value;
        return $this;
    }

    /**
     *
     * @return array
     */
    public function getExclude()
    {
        return $this->_exclude;
    }

    /**
     * @param  $exclude
     * @return Ext_Validate_EntityNotExists
     */
    public function setExclude($exclude)
    {
        $this->_exclude = $exclude;
        return $this;
    }
}
