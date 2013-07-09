<?php

/**
 * @category    Ext
 * @package     Doctrine
 * @since       1.0
 * @version     $Revision: 1.0 $
 */
abstract class Ext_Doctrine_Record extends Doctrine_Record
{
    /**
     * Текущая локаль. поддерживается либо пустая строка (это русский), либо 'en' - английский
     * @var string
     */
    protected static $_currentLanguage = '';

    /**
     * Установка текущей локали
     * @param string $lang
     */
    public static function setCurrentLanguage($lang)
    {
        self::$_currentLanguage = $lang;
    }

    /**
     * Получение текущей локали
     * @return string
     */
    public static function getCurrentLanguage()
    {
        return self::$_currentLanguage;
    }

    /**
     * Получение поля с нужным переводом
     * @param string $fieldName
     * @param string $language
     * @return string
     */
    public function getTranslated($fieldName, $language = NULL)
    {
        $language = $language ? $language : self::$_currentLanguage;
        if (!$language) {
            return $this->__get($fieldName);
        }
        $translatedFieldName = $fieldName . '_' . $language;
        return $this->__get($translatedFieldName);
    }

    /**
     * Переопределение сохранения с целью автоматического дропа кэша
     *
     * @param Doctrine_Connection $conn
     * @param boolean $dropCache
     * @param string $cacheKey
     */
    public function save(Doctrine_Connection $conn = null, $dropCache = false, $cacheKey = NULL)
    {
        if (Ext_Doctrine_Query::isCacheEnabled() && $dropCache) {
            $this->dropCache($cacheKey = NULL);
        }
        parent::save($conn);
    }

    /**
     * Переопределение удаления с целью автоматического дропа кэша
     *
     * @param Doctrine_Connection $conn
     * @param boolean $dropCache
     * @param string $cacheKey
     */
    public function delete(Doctrine_Connection $conn = null, $dropCache = false, $cacheKey = NULL)
    {
        if (Ext_Doctrine_Query::isCacheEnabled() && $dropCache) {
            $this->dropCache($cacheKey);
        }
        parent::delete($conn);
    }

    /**
     * Очистка кэша в доктрине по ключу
     * @param string $cacheKey
     */
    public function dropCache($cacheKey = NULL)
    {
        $cacheKey = $cacheKey ? $cacheKey : $this->getTable()->getTableCacheKey() . $this->id . '_';
        Doctrine_Manager::getInstance()->getAttribute(Doctrine_Core::ATTR_RESULT_CACHE)->delete($cacheKey);
    }

    /**
     * Связывает объект с другими по массиву айдишников. остальные удаляет. используется при сохрании в формах
     * Если передан пустой массив - очищает все предыдущие связи
     * 
     * @param array of integers $objectsIds
     * @param string $relationAlias
     */
    public function saveRelatedObjects($objectsIds, $relationAlias)
    {
        $existedIds = $this->$relationAlias->getPrimaryKeys();
        if (!is_array($existedIds)) {
            $existedIds = array();
        }
        if (!is_array($objectsIds)) {
            $objectsIds = array();
        }

        $unlink = array_diff($existedIds, $objectsIds);
        $link = array_diff($objectsIds, $existedIds);

        if (count($unlink)) {
            $this->unlink($relationAlias, array_values($unlink));
        }
        if (count($link)) {
            $this->link($relationAlias, array_values($link));
        }
    }

    /**
     * Получение "соседа" для объекта
     * @param int $direction
     * @return Ext_Doctrine_Record
     */
    public function getSibling($direction = Core_App::DIRECTION_RIGHT)
    {
        return $this->getSiblingQuery($direction)->fetchOne();
    }

    /**
     * Получение следующего (правого) "соседа" для объекта
     * @return Ext_Doctrine_Record
     */
    public function getNext()
    {
        return $this->getSibling(Core_App::DIRECTION_RIGHT);
    }

    /**
     * Получение предыдущего (левого) "соседа" для объекта
     * @param int $direction
     * @return Ext_Doctrine_Record
     */
    public function getPrev()
    {
        return $this->getSibling(Core_App::DIRECTION_LEFT);
    }

    /**
     * Получение объекта запроса для получения "соседа" для объекта
     * @param int $direction
     * @return Ext_Doctrine_Query 
     */
    public function getSiblingQuery($direction = Core_App::DIRECTION_RIGHT)
    {
        $compareSign = $direction == Core_App::DIRECTION_RIGHT ? '>' : '<';
        return $this->getTable()
            ->getListQuery()
            ->andWhere("id $compareSign ?", $this->id)
            ->orderBy('id' . ($direction == Core_App::DIRECTION_RIGHT ? ' ASC' : ' DESC'))
        ;
    }

    /**
     * Возвращает имя папки для сохранения изображения
     * @return string
     */
    public function getFolderName()
    {
        return $this->exists() ? (string) floor(((int) $this->id) / 1000) : null;
    }
}