<?php

/**
 * Model_UserProperty
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Model_UserProperty extends Model_Base_UserProperty
{
    const PENDING  = 1;
    const REJECTED = 2;
    const ACCEPTED = 3;
    
    public function getStatuses()
    {
        
    }
}