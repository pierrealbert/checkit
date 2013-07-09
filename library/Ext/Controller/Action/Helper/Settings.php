<?php

/**
 * Controller helper for retrieving custom settings
 *
 * @example
 * $options = Zend_Controller_Action_HelperBroker::getStaticHelper('settings')->get('some.option', 'defaultvalue');
 *
 * @category    Ext
 * @package     Ext_Controller
 * @subpackage  Action_Helper
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Controller_Action_Helper_Settings extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Options separator delimiter e.g.
     * option.subkey or
     * option/subkey
     */
    const DELIMITER = '.';

    /**
     * Options array
     *
     * @var array
     */
    protected $_options = null;
    /**
     * Retrieve application options from bootstrap
     *
     * @return array
     */
    public function getOptions()
    {
        if ($this->_options === null) {
            $bootstrap = $this->getFrontController()->getParam('bootstrap');
            if (null === $bootstrap) {
                throw new Ext_Exception('Unable to find bootstrap');
            }

            if (!$bootstrap->hasResource('settings')) {
                throw new Ext_Exception('Settings resource is not exists');
            }

            $options = $bootstrap->getResource('settings');

            if ($options instanceof Zend_Config) {
                $options = $options->toArray();
            }
            $this->_options = $options;
        }

        return $this->_options;
    }

    /**
     * Get array key if exists, otherwise returns null
     *
     * @param array $values
     * @param string $key
     * @return mixed
     */
    private static function _getValue($values, $key)
    {
        if (is_array($values) && isset($values[$key])) {

            return $values[$key];
        }

        return null;
    }

    /**
     * Get application option form bootstrap
     *
     * @param   string $section
     * @param   mixed $default
     * @return  Zend_Config
     */
    public function get($section = null, $default = null)
    {
        $value = $this->getOptions();

        if (null !== $section && is_string($section)) {
            if (false === strpos($section, self::DELIMITER)) {
                $value = $this->_getValue($value, $section);
            } else {
                $sections = explode(self::DELIMITER, $section);
                foreach ($sections as $section) {
                    $value = $this->_getValue($value, $section);
                    if (null === $value) {
                        break;
                    }
                }
            }
        }

        if (null === $value) {
            return $default;
        }

        return $value;
    }

    /**
     * @param   string $section
     * @param   mixed $default
     * @return  Zend_Config
     */
    public function direct($section = null, $default = null)
    {
        return $this->get($section, $default);
    }
}
