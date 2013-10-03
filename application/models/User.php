<?php

/**
 * Model_User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Model_User extends Model_Base_User
{
    const OWNER     = 'owner';
    const RESIDENT  = 'resident';
    
    const MR    = 'mr';    
    const MRS   = 'mrs';
    const MISS  = 'miss';
    
    /**
     *
     * @var Ext_Auth_Hash
     */
    protected $_authHash = null;

    public function block()
    {
        $this->is_active = false;
        return $this->save();
    }

    public function activate()
    {
        $this->is_active = true;
        return $this->save();
    }

    public function confirm()
    {
        $this->is_confirmed = 1;
        $this->confirm_registration_key = null;
        $this->save();

        return $this;
    }
    
    public function primaryResident()
    {
        $table = Model_UserResidentTable::getInstance();
        return $table->createQuery()
                ->andWhere('user_id = ?', $this->id)
                ->andWhere('is_primary = ?', 1)
                ->fetchOne();
    }    
    
    public function hasResidents()
    {
        $table = Model_UserResidentTable::getInstance();
        return (bool) $table->createQuery()
                ->andWhere('user_id = ?', $this->id)
                ->count();        
    }
    
    public function getRentType()
    {
        $resident = $this->primaryResident();
        if ($resident) {
            return $resident->rent_type;
        } 
        return Model_UserResident::RENT_TYPE_SINGLE;
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function isBlocked()
    {
        return !$this->active;
    }

    public function isConfirmed()
    {
        return $this->is_confirmed;
    }
    
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function generateConfirmRegistrationKey()
    {
        $this->confirm_registration_key = md5(time() . $this->id);

        return $this;
    }
    
    public function generateRestorePasswordKey()
    {
        $this->restore_password_key = md5(time() . $this->id);
        return $this;
    }
    
    public function isValidPassword($password)
    {
        return $this->getHash()->validateHash($password, $this->password);
    }

    public function preSave($event)
    {
        if (array_key_exists('password', $this->getModified())) {
            $this->password = $this->getHash()->getHash($this->password);
        }
    }

    /**
     *
     * @return Ext_Auth_Hash_Interface
     */
    public function getHash()
    {
        if (!$this->_authHash) {

            $this->_authHash = new Ext_Auth_Hash();

            $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

            if ($authSettings = $settings->get('security')) {
                $this->_authHash->setOptions($authSettings);
            }
        }
        return $this->_authHash;
    }

    /**
     *
     * @param Ext_Auth_Hash_Interface $hash
     * @return Doctrine_Record
     */
    public function setHash(Ext_Auth_Hash_Interface $hash)
    {
        $this->_authHash = $authHash;
        return $this;
    }
    
    public function isOwner()
    {
        return $this->type == self::OWNER;
    }
    
    public function isResident()
    {
        return $this->type == self::RESIDENT;
    }    
    
    static public function getTypes()
    {
        return array(
            self::OWNER     => 'user_owner',
            self::RESIDENT  => 'user_resident'
        );
    }
    
    static public function getTitles()
    {
        return array(
            self::MR   => 'title_mr',
            self::MRS  => 'title_mrs',
            self::MISS => 'title_miss'
        );
    }    
    
    static public function isTypeValid($type)
    {
        $types = self::getTypes();
        return isset($types[$type]);
    }
}