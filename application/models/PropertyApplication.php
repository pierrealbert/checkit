<?php

/**
 * Model_PropertyApplication
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Model_PropertyApplication extends Model_Base_PropertyApplication
{
    private $_statusBuff = false;

    public function isAccepted()
    {
        return (bool) $this->is_accepted;
    }
    
    public function isDeclined()
    {
        return (bool) $this->is_declined;
    }
    
    public function isRead()
    {
        return (bool) $this->is_read;
    }

    public function postDelete($event)
    {
        $property = Model_PropertyTable::getInstance()->findById($this->property_id);
        if ($property) {
            Model_AlertTable::getInstance()->addAlertWarning($property->owner_id, $this->_translate('alert_propertyApplication_delete_title'), $this->_translate('alert_propertyApplication_delete_body'));
        }
    }

    public function postInsert($event)
    {
        $property = Model_PropertyTable::getInstance()->findById($this->property_id);
        if ($property) {
            Model_AlertTable::getInstance()->addAlertInfo($property->owner_id, $this->_translate('alert_propertyApplication_insert_owner_title'), $this->_translate('alert_propertyApplication_insert_owner_body'), $this->property_id);
            Model_AlertTable::getInstance()->addAlertSuccess($this->visitor_id, $this->_translate('alert_propertyApplication_insert_visitor_title'), $this->_translate('alert_propertyApplication_insert_visitor_body'), $this->property_id);
        }
    }

    public function save(Doctrine_Connection $conn = null, $dropCache = false, $cacheKey = NULL)
    {
        $modified = $this->getModified();
        parent::save($conn, $dropCache, $cacheKey);
        if (isset($modified['is_read']) || isset($modified['is_accepted']) || isset($modified['is_declined'])) {
            $property = Model_PropertyTable::getInstance()->findById($this->property_id);
            if ($property) {
                Model_AlertTable::getInstance()->addAlertInfo($this->visitor_id, $this->_translate('alert_propertyApplication_statusChange_title'), $this->_translate('alert_propertyApplication_statusChange_body'), $this->property_id);
            }
        }
    }

    protected function _translate($string)
    {
        $translator = $this->getTranslator();
        if ($translator) {
            return $translator->translate($string);
        }
        return $string;
    }
    
}