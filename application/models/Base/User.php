<?php

/**
 * Model_Base_User
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $country
 * @property string $city
 * @property string $facebook_id
 * @property enum $type
 * @property string $confirm_registration_key
 * @property string $restore_password_key
 * @property boolean $is_active
 * @property boolean $is_confirmed
 * @property string $company_name
 * @property string $company_address
 * @property string $company_siret
 * @property integer $company_zip
 * @property string $company_city
 * @property Doctrine_Collection $UserResident
 * @property Doctrine_Collection $UserMessage
 * @property Doctrine_Collection $Property
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_User extends Ext_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('username', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('first_name', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('last_name', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('email', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('password', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('role', 'string', 10, array(
             'type' => 'string',
             'length' => '10',
             ));
        $this->hasColumn('country', 'string', 2, array(
             'type' => 'string',
             'length' => '2',
             ));
        $this->hasColumn('city', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('facebook_id', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('type', 'enum', 15, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'owner',
              1 => 'resident',
             ),
             'size' => 15,
             'length' => 15,
             ));
        $this->hasColumn('confirm_registration_key', 'string', 32, array(
             'type' => 'string',
             'length' => 32,
             'notnull' => false,
             ));
        $this->hasColumn('restore_password_key', 'string', 32, array(
             'type' => 'string',
             'length' => 32,
             'notnull' => false,
             ));
        $this->hasColumn('is_active', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => true,
             ));
        $this->hasColumn('is_confirmed', 'boolean', null, array(
             'type' => 'boolean',
             'notnull' => true,
             'default' => false,
             ));
        $this->hasColumn('company_name', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('company_address', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('company_siret', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('company_zip', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('company_city', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Model_UserResident as UserResident', array(
             'local' => 'id',
             'foreign' => 'user_id'));

        $this->hasMany('Model_UserMessage as UserMessage', array(
             'local' => 'id',
             'foreign' => 'sender_id'));

        $this->hasMany('Model_Property as Property', array(
             'local' => 'id',
             'foreign' => 'owner_id'));

        $timestampable0 = new Doctrine_Template_Timestampable(array(
             'created' => 
             array(
              'name' => 'created_at',
             ),
             'updated' => 
             array(
              'name' => 'updated_at',
             ),
             ));
        $this->actAs($timestampable0);
    }
}