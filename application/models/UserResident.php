<?php

/**
 * Model_UserResident
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Model_UserResident extends Model_Base_UserResident
{
    const TYPE_STUDENT      = 'student';
    const TYPE_EMPLOYEE     = 'employee';
    const TYPE_INDEPENDENT  = 'independent';
    const TYPE_OTHER        = 'other';
    
    const RENT_TYPE_SINGLE      = 'single';
    const RENT_TYPE_COUPLE      = 'couple';
    const RENT_TYPE_ROOMMATE    = 'roommate';

	const ROOMMATE_MAX_COUNT = 5;
    
    const EMPLOYEE_TYPE_CDI = 'cdi';
    const EMPLOYEE_TYPE_CSD = 'csd';
    const EMPLOYEE_TYPE_INTERIM = 'interim';
    
    public function getTitle($number = 1)
    {
        $title = 'Candidat';
        if ($this->is_primary) {
            $title = 'Candidat';
        } elseif ($this->rent_type == self::RENT_TYPE_ROOMMATE) {
            $title = 'Colocataire ' . $number;
        } elseif ($this->rent_type == self::RENT_TYPE_COUPLE) {
            $title = 'En couple';
        }      
        return $title;
    }
    
    public function getGarants()
    {
        $table = Model_UserResidentGarantTable::getInstance();
        return $table->createQuery()
                ->andWhere('user_resident_id = ?', $this->id)
                ->orderBy('id  DESC')
                ->execute();        
    }
    
    public function getDocuments()
    {
        $table = Model_UserResidentDocumentTable::getInstance();
        return $table->createQuery()
                ->andWhere('user_resident_id = ?', $this->id)
                ->andWhere('user_resident_garant_id IS NULL')
                ->orderBy('id  DESC')
                ->execute();        
    }    
    
    static public function getTypes()
    {
        return array(
            self::TYPE_STUDENT      => 'student',
            self::TYPE_EMPLOYEE     => 'employee',
            self::TYPE_INDEPENDENT  => 'independent',
            self::TYPE_OTHER        => 'other'
        );
    }
    
    static public function getRentTypes()
    {
        return array(
            self::RENT_TYPE_SINGLE     => 'single',
            self::RENT_TYPE_COUPLE     => 'couple',
            self::RENT_TYPE_ROOMMATE   => 'roommate'                
        );        
    }
    
    static public function getEmployeeTypes()
    {
        return array(
            self::EMPLOYEE_TYPE_CDI => 'employee_cdi',
            self::EMPLOYEE_TYPE_CSD => 'employee_csd',
            self::EMPLOYEE_TYPE_INTERIM => 'employee_interim',
        );        
    }
}