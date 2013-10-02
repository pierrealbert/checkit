<?php

/**
 * @category    Ext
 * @package     Doctrine
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
class Ext_Doctrine_Table extends Doctrine_Table
{
    /**
     * Нужен ли автокэш для данной таблицы (кэширование всех запросов от данной таблицы по умолчанию)
     * @var bool
     */
    protected $_autoCache = false;

    /**
     * Время жизни кэша для записей
     * @var int
     */
    protected $_cacheLifeTime = 7200;

    /**
     * Ключ для кэша
     * @var string 
     */
    protected $_cacheKey;

    /**
     * Короткий алиас для таблицы, для запросов DQL
     * @var string 
     */
    protected $_tableAlias;

    /**
     * Фронтенд либо админка. влияет на выборку контента, соответствующего локали
     * @var bool //true - фронтенд, false (по умолчанию) - админка
     */
    protected static $_isFrontend = false;

    /**
     *  Устанавливает - фронтенд это или админка
     *
     * @param bool $value
     */
    public static function setIsFrontend($value = true)
    {
        self::$_isFrontend = $value;
    }

    /**
     * Переопределение конструктора для параметров кэша
     *
     * @param string $name
     * @param Doctrine_Connection $conn
     * @param bool $initDefinition
     */
    public function  __construct($name, Doctrine_Connection $conn, $initDefinition = false)
    {
        parent::__construct($name, $conn, $initDefinition);
        $this->_cacheKey = $this->_cacheKey ? $this->_cacheKey : $this->getComponentName();
    }

    /**
     * Проверка на существование записи с определенным значением определенного поля
     * Можно внести исключение по ID, тогда эта запись проверяться не будет (применяется для редактирования элементов с уникальными полями)
     * 
     * @param string $fieldName
     * @param string $value
     * @param int $exceptId
     * @return bool
     */
    public function isRecordWithFieldExist($fieldName, $value, $exceptId = null)
    {
        if (!$this->hasColumn($fieldName)) {
            throw new Exception("The field '$fieldName' doesn't exist in the table '" . $this->getTableName() . "'");
        }
        $query = $this->getListQuery()
            ->select('COUNT(*) as cnt')
            ->where($this->_getTableAlias() . '.' . $fieldName . ' = ?', $value)
            ->limit(1)
        ;
        if ($exceptId) {
            $query->andWhere($this->_getTableAlias() . '.id <> ?', $exceptId);
        }
        return $query->fetchOne()->cnt ? true : false;
    }

    /**
     * Возвращает ключ для кэширования
     * @return string
     */
    public function getTableCacheKey()
    {
        return $this->_cacheKey;
    }
    
    /**
     * Получение записи по ID с возможностью автоматического кэширования
     *
     * @param int $id
     * @return Ext_Doctrine_Record
     */
    public function findById($id)
    {
        return $this->createQuery($this->_getTableAlias(), $this->_autoCache, $this->_cacheLifeTime, $this->_cacheKey . $id . '_')
            ->where($this->_getTableAlias() . '.id = ?', $id)
            ->fetchOne()
        ;
    }
    /**
     * Получение записи по ID с возможностью автоматического кэширования
     *
     * @param int $id
     * @return Ext_Doctrine_Record
     */
    public function findByIds(array $ids)
    {
        return $this->createQuery($this->_getTableAlias(), $this->_autoCache, $this->_cacheLifeTime, $this->_cacheKey . print_r($ids, 1) . '_')
            ->whereIn($this->_getTableAlias() . '.id', $ids)
            ->execute()
        ;
    }
    /**
     * Удаление записей по айдишнику или по массиву айдишников
     *
     * @param int|array $ids
     * @param bool $dropCache
     * @param string $cacheKeyPrefix
     */
    public function deleteByIds($ids, $dropCache = false, $cacheKeyPrefix = NULL)
    {
        $ids = (array) $ids;
        Ext_Doctrine_Table::createQuery($cachable, $cacheLifeTime, $cacheKey, $cacheKeyHashNeeded)
            ->delete($this->getComponentName())
            ->whereIn('id', $ids)
            ->execute()
        ;
        if (Ext_Doctrine_Query::isCacheEnabled() && $dropCache) {
            foreach($ids as $id) {
                $this->dropRecordCacheById($id, $cacheKeyPrefix);
            }
        }
    }

    /**
     * Удалить кэш записи по ИД
     *
     * @param int $id
     * @param string $cacheKeyPrefix
     */
    public function dropRecordCacheById($id, $cacheKeyPrefix = NULL)
    {
        $cacheKeyPrefix = $cacheKeyPrefix ? $cacheKeyPrefix : $this->getTableCacheKey();
        Doctrine_Manager::getInstance()->getAttribute(Doctrine_Core::ATTR_RESULT_CACHE)->delete($cacheKeyPrefix . $id . '_');
    }

    /**
     * Метод получения коллекции объектов по фильтру.
     *
     * @param array $filters
     * @param string $alias
     * @param bool $hydrationMode
     * @return Doctrine_Collection
     */
    public function getList($filters = array(), $alias = NULL, $hydrationMode = null)
    {
        return $this->getListQuery($filters, $alias)->execute(null, $hydrationMode);
    }

    /**
     * Получение метода запроса для метода getList() по фильтру
     *
     * @param array $filters
     * @param string $alias
     * @return Ext_Doctrine_Query
     */
    public function getListQuery($filters = array(), $alias = NULL)
    {
        $this->_tableAlias = $alias ? $alias : $this->_getTableAlias();
        $query = $this->createQuery($this->_tableAlias);

        if (!$filters) {
            return $query;
        }

        $filter = new Zend_Filter_Word_UnderscoreToCamelCase();

        foreach ($filters as $filterName => $value) {

            $filterCallbackName = '_' . $filter->filter($filterName);
            if (method_exists($this, $filterCallbackName . 'Callback')) {
                call_user_func_array(array($this, $filterCallbackName . 'Callback'), array($query, $value));
            } elseif (method_exists($this, $filterCallbackName . 'FilterCallback')) {
                call_user_func_array(array($this, $filterCallbackName . 'FilterCallback'), array($query, $value));
            } elseif ($this->hasColumn($filterName) && $value) {
                $query->andWhere($this->_tableAlias . '.' . $filterName . ' = ?', $value);
//            } else {
//                throw new Ext_Exception(sprintf(
//                    'For filtering by field %1$s to define the function _%1$sFilterCallback',
//                    $filterName
//                ));
            }
        }

        return $query;
    }

    /**
     * Создает запрос на селект из текущей таблицы
     *
     * @param string $tableAlias
     * @param bool $cachable
     * @param int $cacheLifeTime
     * @param string $cacheKey
     * @param bool $cacheKeyHashNeeded
     * @return Ext_Doctrine_Query
     */
    public function createQuery($tableAlias = NULL, $cachable = NULL, $cacheLifeTime = NULL, $cacheKey = NULL, $cacheKeyHashNeeded = false)
    {
        if ($cachable === NULL)
            $cachable = $this->_autoCache;
        if (!$cacheLifeTime)
            $cacheLifeTime = $this->_cacheLifeTime;
        if (!$cacheKey)
            $cacheKey = $this->_cacheLifeTime;
        if (!$tableAlias)
            $tableAlias = $this->_getTableAlias();
        $query = new Ext_Doctrine_Query(NULL, NULL, $cachable, $cacheLifeTime, $cacheKey, $cacheKeyHashNeeded);
        return $query->from($this->getComponentName() . ' ' . $tableAlias);
    }

    /**
     * @param array $filters
     * @param string $alias
     * @param string $keyFieldName
     * @param string $valueFieldName
     * @return array 
     */
    public function getListForSelect($filters = array(), $alias = null, $keyFieldName = 'id', $valueFieldName = 'name')
    {
        return $this->getList($filters, $alias)->toKeyValueArray($keyFieldName, $valueFieldName);
    }

    /**
     * Возвращает алиас таблицы. Генерирует его, если он еще не задан
     * @return string
     */
    protected function _getTableAlias()
    {
        if (!$this->_tableAlias) {
            $this->_tableAlias = $this->_generateTableAlias();
        }
        return $this->_tableAlias;
    }

    /**
     * Генеририрует сокращенный алиас таблицы, соединяя первые буквы слов названия таблицы
     * @return string
     */
    protected function _generateTableAlias()
    {
        $alias = '';
        foreach (explode('_', $this->getTableName()) as $namePart) {
            $alias .= $namePart[0];
        }
        return $alias;
    }

    /**
     * @param Ext_Doctrine_Query $query
     * @param int|array $value 
     * @return void
     */
    protected function _idFilterCallback(Ext_Doctrine_Query $query, $value)
    {
        if (empty($value)) {
            $query->andWhere($this->_tableAlias . '.id IS NULL');
        } else if (!is_array($value)) {
            $query->andWhere($this->_tableAlias . '.id = ?', $value);
        } else {
            $query->whereIn($this->_tableAlias . '.id', $value);
        }
    }

    /**
     * @param Ext_Doctrine_Query $query
     * @param int|array $value 
     * @return void
     */
    protected function _notIdFilterCallback(Ext_Doctrine_Query $query, $value)
    {
        if (!is_array($value)) {
            $query->andWhere($this->_tableAlias . '.id <> ?', $value);
        } else {
            $query->whereNotIn($this->_tableAlias . '.id', $value);
        }
    }

    /**
     * @param Ext_Doctrine_Query $query
     * @param string $value - format: 'yyyy-mm-dd'
     * @return void
     */
    protected function _dateFromFilterCallback(Ext_Doctrine_Query $query, $value)
    {
        if ($value) {
            $query->andWhere($this->_tableAlias . '.created_at >= ?', $value . ' 00:00:00');
        }
    }

    /**
     * @param Ext_Doctrine_Query $query
     * @param string $value - format: 'yyyy-mm-dd'
     * @return void
     */
    protected function _dateToFilterCallback(Ext_Doctrine_Query $query, $value)
    {
        if ($value) {
            $query->andWhere($this->_tableAlias . '.created_at <= ?', $value . ' 23:59:59');
        }
    }

    /**
     * @param Ext_Doctrine_Query $query
     * @param array $value
     * @throws Ext_Exception
     * @return void 
     */
    protected function _gridSortCallback(Ext_Doctrine_Query $query, $value)
    {
        if (!empty($value['field'])) {
            $order = isset($value['order']) && in_array($value['order'], array('asc', 'desc'))
                ? $value['order'] : 'asc';
            if (!$this->hasColumn($value['field'])) {
                throw new Ext_Exception(sprintf(
                    'For sorting by field %1$s you must define the function _%1$sSortCallback',
                    $value['field']
                ));
            }
            $query->orderBy($this->_tableAlias . '.' . $value['field'] . ' ' . $order);
        }
    }

    /**
     * @param Ext_Doctrine_Query $query
     * @param int $value 
     * @return void
     */
    protected function _limitCallback(Ext_Doctrine_Query $query, $value)
    {
        if ($value) {
            $query->limit($value);
        }
    }

    /**
     * @param Ext_Doctrine_Query $query
     * @param int $value 
     * @return void
     */
    protected function _offsetCallback(Ext_Doctrine_Query $query, $value)
    {
        if ($value) {
            $query->offset($value);
        }
    }

    /**
     * пример1:
     *   $filters['order_by'] = 'name'; //sql - ORDER BY name
     * пример2:
     *   $filters['order_by'] = array(
     *     'field' => 'name',
     *     'alias' => 't',
     *     'order' => 'DESC'
     *   ); // sql - ORDER BY t.name DESC
     * @param Ext_Doctrine_Query $query
     * @param string|array $value 
     * @return void
     */
    protected function _orderByCallback(Ext_Doctrine_Query $query, $value)
    {
        if (!empty($value)) {
            if (!is_array($value)) {
                $query->orderBy($value);
            } else {
                $query->orderBy(
                    (isset($value['alias']) ? $value['alias'] : $this->_tableAlias) . //table alias
                    '.' . $value['field'] .  ' ' .                                    //order field name
                    (isset($value['order']) ? $value['order'] : 'ASC')                //ASC ot DESC
                );
            }
        }
    }
}
