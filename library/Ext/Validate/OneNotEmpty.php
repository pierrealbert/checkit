<?php

/** @see Zend_Validate_Abstract */

class Ext_Validate_OneNotEmpty extends Zend_Validate_Abstract
{
    /**
     * Error codes
     * @const string
     */
    const BOTH_EMPTY    = 'bothEmpty';
    const MISSING_TOKEN = 'missingToken';

    /**
     * Error messages
     * @var array
     */
    protected $_messageTemplates = array(
        self::BOTH_EMPTY    => "At least one of this elements should be not empty",
        self::MISSING_TOKEN => 'No token was provided to match against',
    );

    /**
     * @var array
     */
    protected $_messageVariables = array(
        'token' => '_tokenString'
    );

    /**
     * Original token against which to validate
     * @var string
     */
    protected $_tokenString;
    protected $_token;

    /**
     * Sets validator options
     *
     * @param  mixed $token
     * @return void
     */
    public function __construct($token = null)
    {
        if ($token instanceof Zend_Config) {
            $token = $token->toArray();
        }

        if (is_array($token) && array_key_exists('token', $token)) {
            $this->setToken($token['token']);
        } else if (null !== $token) {
            $this->setToken($token);
        }
    }

    /**
     * Retrieve token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * Set token against which to compare
     *
     * @param  mixed $token
     * @return Zend_Validate_Identical
     */
    public function setToken($token)
    {
        $this->_tokenString = (string) $token;
        $this->_token       = $token;
        return $this;
    }

    /**
     * Defined by Zend_Validate_Interface
     *
     * Returns true if provided value or token is not empty
     *
     * @param  mixed $value
     * @param  array $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->_setValue((string) $value);
        
        $token = $this->getToken();
        if ($token === null) {
            $this->_error(self::MISSING_TOKEN);
            return False;
        }

        if (isset($context[$token]) && !empty($context[$token])) {
            return True;
        }
        $this->_error(self::BOTH_EMPTY);
        return False;
    }
}
