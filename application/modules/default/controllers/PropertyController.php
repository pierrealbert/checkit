<?php

class PropertyController extends Zend_Controller_Action
{
    public function detailAction()
    {
        $query = Doctrine::getTable('Model_Property')->createQuery('p')
            ->select('p.*, (p.amount_of_rent_excluding_charges + p.amount_of_charges) as price')
            ->where('p.id = ?', $this->_getParam('item', 0))
            ->andWhere('p.is_published = ?', 1);

        $property = $query->fetchOne();

        if (!$property) {

            $this->_helper->redirector('index', 'index');
        }

        $this->view->property         = $property;
        $this->view->property_type    = Model_Property::getTypes();
        $this->view->number_of_rooms1 = Model_Property::getNumberOfRooms1Info();
        $this->view->photos           = $property->getPhotos();
        $this->view->values_groups    = Model_Property::getValuesGroups();

        $this->view->previous_and_next = self::getPreviousAndNext($property->id);

        $this->view->visits = Doctrine::getTable('Model_PropertyVisitDates')->createQuery('pvd')
            ->where('pvd.property_id = ?', $property->id)
            ->execute();


        $options = Zend_Controller_Action_HelperBroker::getStaticHelper('settings')
            ->get('services.googleMaps');

        $this->view->google_api_key = $options['apiKey'];
    }

    private static function getPreviousAndNext($currentId)
    {
        $result = array(
            'previous' => 0,
            'next'     => 0,
        );

        $list_items_ids = Doctrine::getTable('Model_Property')->createQuery('p')
            ->select('p.id')
            ->where('p.is_published = ?', 1)
            ->execute();

        $is_detect_next = false;

        foreach ($list_items_ids as $item) {
            if ($is_detect_next) {
                $result['next'] = $item->id;
                break;
            } else if ($currentId == $item->id) {
                $is_detect_next = true;
            } else {
                $result['previous'] = $item->id;
            }
        }

        return $result;
    }
    

    /**
     * AJAX
     * this method for adding information to db table favorite
     * when we ckick button add to bookmark we call this method using ajax
     * @return 
     */
    public function addtobookmarkajaxAction()
    {
	$returnData = new stdClass();
	//if it is ajax
	if ($this->getRequest()->isXmlHttpRequest())
	{	   
	    //if it is post request 
	    if ($this->getRequest()->isPost()) 
	    {
		//here i need to know user id and property id
		$auth = Zend_Auth::getInstance();
		$userId = $auth->getIdentity();
		//if we donot have userid it means we donot logined
		if(empty($userId))
		{
		    $returnData->redirectUrl = $this->_helper->url('' , 'login');
		    $jsonStr = $this->_helper->json($returnData);
		    echo $jsonStr;
		    die;
		}//endif
		//get property id

		$propertyId = '';
		echo "<pre>";
		print_r($propertyId);
		echo "</pre>";
		die;
	    }
	    else
	    {
		// it is not post request
		$this->_helper->redirector('index', 'index');
		die;
	    }//endif
	}
	else 
	{
	    // ... Do normal controller logic here (To catch non ajax calls to the script)
	    $this->_helper->redirector('index', 'index');
	}//endif

    }//endfunc
     /**
     * AJAX
     * this method for adding information to db table favorite
     * 
     */
    public function addtoshareajaxAction()
    {
	echo "<pre>";
	print_r(2);
	echo "</pre>";
	die;
    }
       /**
     * AJAX
     * this method for adding information to db table favorite
     * 
     */
    public function addtoapplyajaxAction()
    {
	echo "<pre>";
	print_r(3);
	echo "</pre>";
	die;
    }
  
}