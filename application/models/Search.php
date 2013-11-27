<?php

/**
 * Model_Search
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Model_Search extends Model_Base_Search
{
    public function getSearchConditions()
    {
        return unserialize($this->conditions);
    }
    
    public function getSearchFields()
    {
        $fields = array_keys($this->getSearchConditions());
        
        foreach ($fields as $key => $value) {
            if ($value == 'min_budget' || $value == 'max_budget') {
                unset($fields[$key]);
                if (!in_array('budget', $fields)) {
                    $fields[] = 'budget';
                }
            }
            if ($value == 'min_size' || $value == 'max_size') {
                unset($fields[$key]);
                if (!in_array('budget', $fields)) {
                    $fields[] = 'budget';
                }
            }

            if ($value == 'PropertyXMetroStation.distance' || $value == 'PropertyXMetroStation.metro_station_id') {
                unset($fields[$key]);
                if (!in_array('metro', $fields)) {
                    $fields[] = 'metro';
                }
            }

        }
        
        sort($fields);
        return $fields;
    }
    
    public function getCondition($name)
    {
        $conditions = $this->getSearchConditions();
        if ($this->issetCondition($name)) {
            $value = null;
            if (isset($conditions[$name]['value'])) {
                $value = $conditions[$name]['value'];
            } elseif (is_array($conditions[$name])) {
                $value = array();
                foreach ($conditions[$name] as $condition) {
                    if (is_array($condition) && isset($condition['value'])) {
                        $value[] = $condition['value'];
                    }
                }
            }
            return $value;
        }
        return null;
    }
    
    public function issetCondition($name)
    {
        $conditions = $this->getSearchConditions();
        return !empty($conditions[$name]);
    }
    
    
}

/*
Localisation
Budget
Surface
Mobilier
Mobilier
Disponibilité
Durée du bail
Aménagement
Dépendances
Immeuble
Chauffage
 * 
 */