<?php

/**
 * Model_Base_Transaction
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $paypal_transaction_id
 * @property string $paypal_correlation_id
 * @property string $paypal_ec_token
 * @property text $paypal_response_body
 * @property boolean $is_cancelled
 * @property integer $amount
 * @property string $currency_code
 * @property string $paypal_ack
 * @property boolean $is_success
 * @property integer $user_id
 * @property integer $error_code
 * @property string $error_message
 * @property datetime $sent_at
 * @property Model_User $User
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Model_Base_Transaction extends Ext_Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('transaction');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('paypal_transaction_id', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('paypal_correlation_id', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('paypal_ec_token', 'string', 50, array(
             'type' => 'string',
             'length' => '50',
             ));
        $this->hasColumn('paypal_response_body', 'text', null, array(
             'type' => 'text',
             ));
        $this->hasColumn('is_cancelled', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('amount', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => true,
             'length' => '4',
             ));
        $this->hasColumn('currency_code', 'string', 3, array(
             'type' => 'string',
             'length' => '3',
             ));
        $this->hasColumn('paypal_ack', 'string', 20, array(
             'type' => 'string',
             'length' => '20',
             ));
        $this->hasColumn('is_success', 'boolean', null, array(
             'type' => 'boolean',
             'default' => 0,
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'length' => '4',
             ));
        $this->hasColumn('error_code', 'integer', 3, array(
             'type' => 'integer',
             'length' => '3',
             ));
        $this->hasColumn('error_message', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('sent_at', 'datetime', null, array(
             'type' => 'datetime',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Model_User as User', array(
             'local' => 'user_id',
             'foreign' => 'id',
             'onDelete' => 'cascade'));

        $timestampable0 = new Doctrine_Template_Timestampable(array(
             'created' => 
             array(
              'name' => 'created_at',
             ),
             ));
        $this->actAs($timestampable0);
    }
}