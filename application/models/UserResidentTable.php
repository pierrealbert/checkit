<?php

/**
 * Model_UserResidentTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Model_UserResidentTable extends Ext_Doctrine_Table
{
    /**
     * Returns an instance of this class.
     *
     * @return object Model_UserResidentTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('Model_UserResident');
    }

    public function getResidentsByIDs($userIDs) {
        if (count($userIDs) == 0) {
            $userIDs = "NULL";
        } else {
            $userIDs = implode(',', $userIDs);
        }
        $statement = Doctrine_Manager::getInstance()->connection();
        $results = $statement->execute("SELECT t1.id, t1.resident_type, t1.user_id, t1.resident_name, t1.monthly_income  FROM user_resident AS t1 WHERE t1.is_primary = 1 AND t1.user_id IN (".$userIDs.")");
        $tmpList = $results->fetchAll();
        $result = array();
        $resIDs = array();
        if ($tmpList) {
            foreach($tmpList as $indx => $rec) {
                $result[$rec['user_id']] = array(
                    'id'             => $rec['id'],
                    'resident_name'  => $rec['resident_name'],
                    'resident_type'  => $rec['resident_type'],
                    'monthly_income' => $rec['monthly_income'],
                );
                $resIDs[] = $rec['id'];
            }
        }

        return array($result, $resIDs);
    }

}