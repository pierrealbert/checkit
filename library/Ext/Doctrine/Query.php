<?php

/**
 * @category    Ext
 * @package     Doctrine
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Doctrine_Query extends Doctrine_Query
{
    /**
     * Необходимость кэширования запроса
     * @var boolean
     */
    protected $_cachable;

    /**
     * Ключ для кэширования
     * @var string
     */
    protected $_cacheKey = 'cache';

    /**
     * Время жизни кэша в миллисекундах
     * @var int
     */
    protected $_cacheLifeTime = 7200; //2 hours

    /**
     * Необходимость в генерации дополнительного хэша для ключа кэша
     * @var boolean
     */
    protected $_cacheKeyHashNeeded = false;

    /**
     * Глобальная переменная, отвечающая за то, включен ли кэш доктрины на сайте или нет
     * @var boolean
     */
    protected static $_isCacheEnabled = false;

    /**
     * Устанавливает значения для переключателя работы кэша, т.е. либо включает его (true), либо выключает (false)
     *
     * @param boolean $enabled
     */
    public static function setCacheEnabled($enabled)
    {
        self::$_isCacheEnabled = $enabled;
    }

    /**
     * Возвращает включен ли кэш глообально или нет
     * @return boolean
     */
    public static function isCacheEnabled()
    {
        return self::$_isCacheEnabled;
    }

    /**
     * Статическая функция создания объекта - применяется при fluent интерфейсе
     *
     * @param boolean $cachable
     * @param int $cacheLifeTime
     * @param string $cacheKey
     * @param boolean $cacheKeyHashNeeded
     * @return Ext_Doctrine_Query
     */
    public static function create($cachable = false, $cacheLifeTime = NULL, $cacheKey = NULL, $cacheKeyHashNeeded = false)
    {
        return new self(NULL, NULL, $cachable, $cacheLifeTime, $cacheKey, $cacheKeyHashNeeded);
    }

    /**
     * Переопределение конструктора родителя для передачи параметров кэша
     *
     * @param Doctrine_Connection $connection
     * @param Doctrine_Hydrator_Abstract $hydrator
     * @param boolean $cachable
     * @param int $cacheLifeTime
     * @param string $cacheKey
     * @param boolean $cacheKeyHashNeeded
     */
    public function  __construct(Doctrine_Connection $connection = null,
            Doctrine_Hydrator_Abstract $hydrator = null, $cachable = false, $cacheLifeTime = NULL, $cacheKey = NULL, $cacheKeyHashNeeded = false)
    {
        $this->_cachable = $cachable;
        $this->_cacheKeyHashNeeded = $cacheKeyHashNeeded;
        $this->_cacheKey = $cacheKey ? $cacheKey : $this->_cacheKey;
        $this->_cacheLifeTime = $cacheLifeTime ? $cacheLifeTime : $this->_cacheLifeTime;
        parent::__construct($connection, $hydrator);
    }

    /**
     * Adds IN condition to the query WHERE part
     *
     * The difference from the standard whereIn method is that this method make the SQL part false if the params are empty
     * <code>
     * $q->whereIn('u.id', array(10, 23, 44));
     * </code>
     *
     * @param string $expr           The operand of the IN
     * @param mixed $params          An array of parameters or a simple scalar
     * @return Ext_Doctrine_Query   this object.
     */
    public function andWhereInExplicit($expr, $params = array())
    {
        if (empty($params)) {
            $this->andWhere('1=0');
        } else {
            $this->andWhereIn($expr, $params);
        }

    }

    /**
     * Переопределение метода выполнения запроса с целью управление кэшированием
     *
     * @param array $params
     * @param int $hydrationMode
     * @return Ext_Doctrine_Record
     */
    public function execute($params = array(), $hydrationMode = null)
    {
        if (self::$_isCacheEnabled && $this->_cachable) {
            if ($this->_cacheKeyHashNeeded) {
                $this->_cacheKey .= $this->_getQueryHash();
            }
            $this
                ->useQueryCache(true)
                ->useResultCache(true, $this->_cacheLifeTime, $this->_cacheKey)
            ;
        }
        return parent::execute($params, $hydrationMode);
    }

    /**
     * Составляет хэш для запроса. это используется для составления ключа для кэширования запросов
     *
     * @return string
     */
    protected function _getQueryHash()
    {
        $queryStr = str_replace(array(
            ' ', "\n", 'SELECT', 'FROM', 'WHERE', 'AS', 'INNER', 'JOIN', 'LEFT', 'GROUP', 'ORDER', 'BY', 'IN', ',', '__', '.'
        ), '', $this->getSqlQuery());
        $paramsStr = str_replace(array(' ', "\n", 'Array', '(', ')', '[', ']', '=>'), '', print_r($this->getParams(), 1));
        $hash = md5($queryStr . $paramsStr);
        return $hash;
    }
}