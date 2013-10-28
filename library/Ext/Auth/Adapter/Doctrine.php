<?php

/**
 * Auth adapter for doctrine entity
 *
 * @category    Ext
 * @package     Ext_Auth
 * @subpackage  Adapter
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Auth_Adapter_Doctrine implements Zend_Auth_Adapter_Interface
{
    /**
     * $_tableName - the table name to check
     *
     * @var string
     */
    protected $_table = null;

    /**
     * $_identityColumn - the column to use as the identity
     *
     * @var string
     */
    protected $_identityColumn = 'username';

    /**
     * $_credentialColumns - columns to be used as the credentials
     *
     * @var string
     */
    protected $_credentialColumn = 'password';

    /**
     * $_identity - Identity value
     *
     * @var string
     */
    protected $_identity = null;

    /**
     * $_credential - Credential values
     *
     * @var string
     */
    protected $_credential = null;

    /**
     * $_authenticateResultInfo
     *
     * @var array
     */
    protected $_authenticateResultInfo = null;

    /**
     * $_resultRow - Results of database authentication query
     *
     * @var array
     */
    protected $_resultObject = null;

    /**
     * __construct() - Sets configuration options
     *
     * @param  string                       $table
     * @param  string                       $identityColumn
     * @param  string                       $credentialColumn
     * @return void
     */
    public function __construct($table = null, $identityColumn = 'username', $credentialColumn = 'password')
    {
        if (null !== $table) {
            $this->setTableName($table);
        }

        if (null !== $identityColumn) {
            $this->setIdentityColumn($identityColumn);
        }

        if (null !== $credentialColumn) {
            $this->setCredentialColumn($credentialColumn);
        }
    }

    /**
     * setEntityName() - set doctrine entity name to be used in the query
     *
     * @param  string $table
     * @return Ext_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setTableName($table)
    {
        $this->_table = $table;
        return $this;
    }

    /**
     * setIdentityColumn() - set the column name to be used as the identity column
     *
     * @param  string $identityColumn
     * @return Ext_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setIdentityColumn($identityColumn)
    {
        $this->_identityColumn = $identityColumn;
        return $this;
    }

    /**
     * setCredentialColumn() - set the column name to be used as the credential column
     *
     * @param  string $credentialColumn
     * @return Ext_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setCredentialColumn($credentialColumn)
    {
        $this->_credentialColumn = $credentialColumn;
        return $this;
    }
   
    /**
     * setIdentity() - set the value to be used as the identity
     *
     * @param  string $value
     * @return Ext_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setIdentity($value)
    {
        $this->_identity = $value;
        return $this;
    }

    /**
     * setCredential() - set the credential value to be used, optionally can specify a treatment
     * to be used, should be supplied in parameterized form, such as 'MD5(?)' or 'PASSWORD(?)'
     *
     * @param  string $credential
     * @return Ext_Auth_Adapter_Doctrine Provides a fluent interface
     */
    public function setCredential($credential)
    {
        $this->_credential = $credential;
        return $this;
    }

    /**
     * getResultObject()
     *
     * @return stdClass|boolean
     */
    public function getResultObject()
    {
        if (!$this->_resultObject) {
            return false;
        }

        return $this->_resultObject;
    }

    /**
     * authenticate() - defined by Zend_Auth_Adapter_Interface.  This method is called to
     * attempt an authentication.  Previous to this call, this adapter would have already
     * been configured with all necessary information to successfully connect to a database
     * table and attempt to find a record matching the provided identity.
     *
     * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        $this->_authenticateSetup();

        $resultIdentity = $this->_authenticate();

        return $this->_authenticateValidateResult($resultIdentity);
    }

    protected function _authenticate()
    {
        $result = Doctrine::getTable($this->_table)->findOneBy($this->_identityColumn, $this->_identity);
        
        if (!$result) {
            return false;
        }

        /** @var Model_Admin $result */
        if (!$result->getHash()->validateHash($this->_credential, $result->{$this->_credentialColumn})) {
            return false;
        }

        return $result;
    }

    /**
     * _authenticateSetup() - This method abstracts the steps involved with
     * making sure that this adapter was indeed setup properly with all
     * required pieces of information.
     *
     * @throws Zend_Auth_Adapter_Exception - in the event that setup was not done properly
     * @return true
     */
    protected function _authenticateSetup()
    {
        $exception = null;

        if ($this->_table == '') {
            $exception = 'A entity name must be supplied for the Ext_Auth_Adapter_Doctrine authentication adapter.';
        } elseif ($this->_identityColumn == '') {
            $exception = 'An identity column must be supplied for the Ext_Auth_Adapter_Doctrine authentication adapter.';
        } elseif ($this->_credentialColumn == '') {
            $exception = 'A credential column must be supplied for the Ext_Auth_Adapter_Doctrine authentication adapter.';
        } elseif ($this->_identity == '') {
            $exception = 'A value for the identity was not provided prior to authentication with Ext_Auth_Adapter_Doctrine.';
        } elseif ($this->_credential === null) {
            $exception = 'A credential value was not provided prior to authentication with Ext_Auth_Adapter_Doctrine.';
        }

        if (null !== $exception) {
            throw new Zend_Auth_Adapter_Exception($exception);
        }

        $this->_authenticateResultInfo = array(
            'code'     => Zend_Auth_Result::FAILURE,
            'identity' => $this->_identity,
            'messages' => array()
        );

        return true;
    }

    /**
     * _authenticateValidateResult() - This method attempts to validate that
     * the record in the resultset is indeed a record that matched the
     * identity provided to this adapter.
     *
     * @param array $resultIdentity
     * @return Zend_Auth_Result
     */
    protected function _authenticateValidateResult($resultIdentity)
    {
        if (!$resultIdentity) {
            $this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE;
            $this->_authenticateResultInfo['messages'][] = 'Authentication failure.';
            return $this->_authenticateCreateAuthResult();
        }

        $this->_resultObject = $resultIdentity;

        $this->_authenticateResultInfo['code'] = Zend_Auth_Result::SUCCESS;
        $this->_authenticateResultInfo['messages'][] = 'Authentication successful.';
        return $this->_authenticateCreateAuthResult();
    }

    /**
     * _authenticateCreateAuthResult() - Creates a Zend_Auth_Result object from
     * the information that has been collected during the authenticate() attempt.
     *
     * @return Zend_Auth_Result
     */
    protected function _authenticateCreateAuthResult()
    {
        return new Zend_Auth_Result(
            $this->_authenticateResultInfo['code'],
            $this->_authenticateResultInfo['identity'],
            $this->_authenticateResultInfo['messages']
        );
    }
}