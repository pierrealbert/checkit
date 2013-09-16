<?php

/**
 * Model_PropertyTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Model_PropertyTable extends Ext_Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object Model_PropertyTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Model_Property');
    }

    public static function getSearchAllowedSignes()
    {
        // pay attention, that longest signs goes first
        return array('<=', '>=', '=', '<', '>');
    }
    
    /**
     *TODO: write complete documentation for this method
     */
    public function searchQuery(array $params, $exceptIds = array())
    {
        if (is_numeric($exceptIds))
            $exceptIds = array($exceptIds);
        
        $propertyTN = $this->getTableName(); // used as alias
        
        $dqlQuery = $this->createQuery($propertyTN);
        $joins = array();
        
        foreach ($params as $key => $fieldValue) {
            $fieldName = $key;
            if (count($fieldParts = explode('.', $fieldName)) > 1) {
                if (!in_array($fieldParts[0], $joins)) {
                    $dqlQuery->leftJoin("{$propertyTN}.{$fieldParts[0]} {$fieldParts[0]}");
                    $joins[] = $fieldParts[0];
                }
            }
            $whereStr = '';
            $value = '';
            if (is_array($fieldValue)) {
                if (!empty($fieldValue['field'])) {
                    $fieldName = $fieldValue['field'];
                }
                if (empty($fieldValue['sign'])) {
                    $fieldValue['sign'] = '=';
                }
                if (!in_array($fieldValue['sign'], self::getSearchAllowedSignes())) {
                    throw Zend_Exception("Sign {$fieldValue['sign']} is not allowed in search.");
                }
                $whereStr = "$propertyTN.$fieldName {$fieldValue['sign']} ?";
                $value = $fieldValue['value'];
            } else {
                $whereStr = "$propertyTN.$fieldName = ?";
                $value = $fieldValue;
            }
            
            // add WHERE only if value is not empty string ('') and not Null,
            // but WHERE will be added if value is 0 or False
            if ($value !== '' and $value !== Null) {
                if (is_array($value))
                    $dqlQuery->andWhereIn($fieldName, $value);
                else
                    $dqlQuery->andWhere($whereStr, $value);
            }
        }
        return $dqlQuery;
    }

    public function search($params, $exceptIds = array())
    {
        return $this->searchQuery($params, $exceptIds)->execute();
    }
}
