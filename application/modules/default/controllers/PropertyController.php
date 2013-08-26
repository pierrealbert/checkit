<?php

class PropertyController extends Zend_Controller_Action
{

    public function detailAction()
    {
	$currentPropertyId = $this->_getParam('item');

        $query = Doctrine::getTable('Model_Property')->createQuery('p')
            ->select('p.*, (p.amount_of_rent_excluding_charges + p.amount_of_charges) as price')
            ->where('p.id = ?', $this->_getParam('item', 0))
            ->andWhere('p.is_published = ?', 1);

        $property = $query->fetchOne();

        if (!$property) {

            $this->_helper->redirector('index', 'index');
        }
	
	//get all subjects from db
	$subjects = Doctrine::getTable('Model_Subject')->findAll();
	//form for sending issue to admin
	$fromIssue = new Form_Issue();
	$fromIssue->setAction('/property/set-issue')
		     ->setMethod('post')
		     ->setAttrib('id', "issue_pop_window_form");
	$this->view->formIssue        = $fromIssue;
	$this->view->currentPropertyId= $currentPropertyId;
        $this->view->property         = $property;
	$this->view->subjects	      = $subjects;
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

        $this->view->similarProperties  = $property->getSimilar(2);
        $this->view->google_api_key     = $options['apiKey'];
    }

    private static function getPreviousAndNext($currentId) 
    {
        $result = array(
            'previous' => 0,
            'next' => 0,
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
     * @return json string with all info (error and result) 
     */
    public function addToFavoriteAjaxAction() 
    {
        $returnData = new stdClass();
        //if it is ajax
        if ($this->getRequest()->isXmlHttpRequest()) {
            //if it is post request 
            if ($this->getRequest()->isPost()) {
                //here i need to know user id and property id
                $auth = Zend_Auth::getInstance();
                $userId = (int) $auth->getIdentity();

                //if we donot have userid it means we donot logined
                if (empty($userId)) {
                    $returnData->redirectUrl = $this->_helper->url('', 'login');
                    $jsonStr = $this->_helper->json($returnData);
                    echo $jsonStr;
                    die;
                }
                //get property id
                $propertyId = (int) $this->_request->getPost('id');
                if (empty($propertyId)) {
                    $returnData->redirectUrl = $this->_helper->url('', 'login');
                    $jsonStr = $this->_helper->json($returnData);
                    echo $jsonStr;
                    die;
                }
                // i need to know exist this data into db or doesnot 		
                $alreadyExist = Doctrine_Core::getTable('Model_Favorite')->find(array('user_id' => $userId, 'property_id' => $propertyId));

                if ($alreadyExist) {
                    //delete form db
                    $alreadyExist->delete();
                    $returnData->result = 'deleted from db'; //can be true or false
                } else {
                    //insert into db
                    $favoriteModel = new Model_Favorite();
                    $favoriteModel->user_id = $userId;
                    $favoriteModel->property_id = $propertyId;
                    $favoriteModel->save();
                    $returnData->result = 'inserted into db'; //can be true or false
                }

                $returnData->error = '';

                $jsonStr = $this->_helper->json($returnData);
                echo $jsonStr;
                die;
            } else {
                // it is not post request
                $this->_helper->redirector('index', 'index');
                die;
            }
        } else {
            // ... Do normal controller logic here (To catch non ajax calls to the script)
            $this->_helper->redirector('index', 'index');
        }
    }

    /**
     *  AJAX
     * this method for adding information to db table 
     * @return json string with all info (error and result) 
     */
    public function setIssueAction() 
    {
        $fromIssue = new Form_Issue();

        $returnData = new stdClass();
	
        if ($this->getRequest()->isPost()) {
            if ($fromIssue->isValid($this->getRequest()->getPost())) {

                //here i need to know user id and property id
                $auth = Zend_Auth::getInstance();
                $userId = $auth->getIdentity();
                //get property id
                $propertyIdPost = (int) $this->_request->getPost('property_id');
                $subjectId = (int) $this->_request->getPost('subject_id');
                $issueText = $this->_request->getPost('issueText');
		if (empty($subjectId) || empty($propertyIdPost) || empty($issueText)) {
		    $returnData->result = ''; 
		    $returnData->error = 'not valid parameters'; 
		    echo Zend_Json::encode($returnData);	 
		    exit();
		}
                //check exist property_id into db or doesnot 
		$properyData =  Doctrine::getTable('Model_Property')->find($propertyIdPost);
		$subjectData = Doctrine::getTable('Model_Subject')->find($subjectId);
	
		if( empty($properyData) || empty($subjectData) ) {
		    //not valid property id or something else
		    $returnData->result = ''; 
		    $returnData->error = 'not valid parameters'; 
		    echo Zend_Json::encode($returnData);	 
		    exit();
		}
	
		//property is exist into db and subject 
		//insert into db all data 
		$propertyIssueModel = new Model_PropertyIssueSubject();
		$data = new stdClass();
		if($userId) {
		    $data->user_id = $userId;
		}		
		$data->property_id = $propertyIdPost;
		$data->subject_id = $subjectId;
		$data->message = $issueText;
		$data->created_at = date( 'Y-m-d H:i:s' );
		$data->updated_at = date( 'Y-m-d H:i:s' );
		$propertyIssueModel->insert($data);
	
                //return result to ajax
                $returnData->result = 'inserted successfully';
                $returnData->error = '';
		echo Zend_Json::encode($returnData);	 
		exit();
            } else {
                //if we here it means the user sent not valid form 
                $returnData->result = false;
                $returnData->error = 'error not valid form';
                echo Zend_Json::encode($returnData);	 
		exit();
            }
        } else {
            // it is not post request
            $this->_helper->redirector('index', 'index');
        }
    }
  
}