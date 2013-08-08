<?php

/**
 * Class for data hashing. Using for hashing passwords etc..
 *
 * @category    Ext
 * @package     Ext_Auth
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Auth_Hash implements Ext_Auth_Hash_Interface
{
    protected $_algorithm;

    protected $_eSalt;

    protected $_gSaltLen;

    /**
     * Constructor
     *
     * @param string $eSalt Extra salt (for higher secure)
     * @param string $gSaltLen Length for a generated salt
     */
    public function __construct($algorithm = 'sha1', $eSalt = '', $gSaltLen = 6)
    {
        $this->setAlgorithm($algorithm);
        $this->setExtraSalt($eSalt);
        $this->setGenSaltLength($gSaltLen);
    }

    /**
     * Set options
     *
     * @param array|Zend_Config $options
     */
    public function setOptions($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        foreach ($options as $name => $value) {
            $setterName = 'set' . ucfirst($name);
            if (method_exists($this, $setterName)) {
                $this->{$setterName}($value);
            }
        }

        return $this;
    }

    /**
     * Set an extra salt (for higher secure)
     *
     * @param string $salt
     */
    public function setExtraSalt($salt)
    {
        $this->_eSalt = $salt;
    }

    /**
     * Get an extra salt
     *
     * @return string
     */
    public function getExtraSalt()
    {
        return $this->_eSalt;
    }

    /**
     * Set the used algorithm
     *
     * @param string $algorithm
     */
    public function setAlgorithm($algorithm)
    {
        $this->_algorithm = $algorithm;
    }

    /**
     * Set an algorithm
     *
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->_algorithm;
    }

    /**
     * Set length for a generated salt
     *
     * @param string $salt
     */
    public function setGenSaltLength($len)
    {
        $this->_gSaltLen = $len;
    }

    /**
     * Get length for a generated salt
     *
     * @return string
     */
    public function getGenSaltLength()
    {
        return $this->_gSaltLen;
    }

    /**
     * Generate a salted hash
     *
     * @param string $password
     * @param string $salt
     * @return string
     */
    public function getHash($password, $salt = null)
    {
        if (null === $salt) {
            $salt = $this->_generateSalt();
        }
        return $this->_hash($password, $salt) . '-' . $salt;
    }

    /**
     * Validate hash
     *
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function validateHash($password, $hash)
    {
        $hashArr = explode('-', $hash);
        if (count($hashArr) == 2) {
            return self::_hash($password, $hashArr[1]) === $hashArr[0];
        }
        return false;
    }

    /**
     * Hash a string
     *
     * @param string $password
     * @return string
     * @throws Exception
     */
    protected function _hash($password, $salt)
    {
        $toHash = $password . $salt . $this->_eSalt;

        switch ($this->_algorithm) {
            case 'md5' :
                return md5($toHash);
            case 'sha1' :
                return sha1($toHash);
            default:
                throw new Exception('Algorithm \'' . $this->_algorithm . '\' is not supported in Ext_Hash');
        }
    }

    /**
     * Generates random string for the salt
     *
     * @return string
     */
    protected function _generateSalt()
    {
        $chars = "!@#$%^&*()_+=:abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $this->_gSaltLen; $i++) {
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }
}
