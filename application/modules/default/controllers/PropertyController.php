<?php

class PropertyController extends Zend_Controller_Action
{

    protected $translator;

    public function init()
    {
        $this->translator = Zend_Registry::get('Zend_Translate');
    }

    public function showonmapAction() {
        $currentPropertyId = $this->_getParam('item');

        $query = Doctrine::getTable('Model_Property')->createQuery('p')
            ->select('p.*, (p.amount_of_rent_excluding_charges + p.amount_of_charges) as price')
            ->where('p.id = ?', $this->_getParam('item', 0))
            ->andWhere('p.is_published = ?', 1);

        $property = $query->fetchOne();

        $this->view->currentPropertyId = $currentPropertyId;
        $this->view->property          = $property;
    }

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
        $subjects = Doctrine::getTable('Model_PropertyIssueSubject')->findAll();
        //form for sending issue to admin
        $formIssue = new Form_Issue();
        $formIssue->setAction('/property/set-issue')
             ->setMethod('post')
             ->setAttrib('id', "issue_pop_window_form");
        //we need to know this property exist in favorte table or doesnot and if it is exist we need to create delete fprm bookmark button in order to add to bookmark
        $alreadyExist = (bool) Doctrine_Core::getTable('Model_Favorite')->find(
            array('user_id' => Zend_Auth::getInstance()->getIdentity(),
                  'property_id' => $currentPropertyId));

        $propertyVisitDates = Doctrine::getTable('Model_PropertyVisitDates')->createQuery('pvd')
            ->where('pvd.property_id = ?', $property->id)
            ->execute();
        
        $applyForm = new Form_Apply(array('propertyVisitDates' => $propertyVisitDates ,'propertyId' => $property->id));

        $this->view->formIssue        = $formIssue;
        $this->view->isInbookmark     = $alreadyExist;
        $this->view->currentPropertyId= $currentPropertyId;
        $this->view->property         = $property;
        $this->view->subjects         = $subjects;
        $this->view->property_type    = Model_Property::getTypes();
        $this->view->number_of_rooms1 = Model_Property::getNumberOfRooms1Info();
        $this->view->photos           = $property->getPhotos();
        $this->view->values_groups    = Model_Property::getValuesGroups();

        $this->view->previous_and_next = self::getPreviousAndNext($property->id);

        $this->view->visits = $propertyVisitDates;


        $options = Zend_Controller_Action_HelperBroker::getStaticHelper('settings')
            ->get('services.googleMaps');

        $this->view->applyForm          = $applyForm; 
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
        $returnData->success = 0;

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
                    $this->_helper->json($returnData);
                    return;
                }
                //get property id
                $propertyId = (int) $this->_request->getPost('id');
                if (empty($propertyId)) {
                    $returnData->redirectUrl = $this->_helper->url('', 'login');
                    $this->_helper->json($returnData);
                    return;
                }
                // i need to know exist this data into db or doesnot
                $alreadyExist = Doctrine_Core::getTable('Model_Favorite')->find(array('user_id' => $userId, 'property_id' => $propertyId));

                if ($alreadyExist) {
                    //delete form db
                    $alreadyExist->delete();
                    $returnData->success = 1;
                    $returnData->btnLabel = $this->translator->translate('btn_add_to_favorite');
                } else {
                    //insert into db
                    $favoriteModel = new Model_Favorite();
                    $favoriteModel->user_id = $userId;
                    $favoriteModel->property_id = $propertyId;
                    $favoriteModel->save();
                    $returnData->success = 1;
                    $returnData->btnLabel = $this->translator->translate('btn_rm_from_favorite');
                }
                $this->_helper->json($returnData);
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
                $propertyId = (int) $this->_request->getPost('property_id');
                $subjectId  = (int) $this->_request->getPost('subject_id');
                $issueText  = $this->_request->getPost('issueText');

                //check exist property_id into db or doesnot 
                $properyData = Doctrine::getTable('Model_Property')->find($propertyId);
                $subjectData = Doctrine::getTable('Model_PropertyIssueSubject')->find($subjectId);

                if (empty($properyData) || empty($subjectData) || !$issueText) {
                    //not valid property id or something else
                    $returnData->result = false;
                    $returnData->error = $this->translator->translate('not_valid_parameters');    
                } else {

                    //property is exist into db and subject 
                    //insert into db all data 
                    $propertyIssueModel = new Model_PropertyIssue();

                    if ($userId) {
                        $propertyIssueModel->user_id = $userId;
                    }
                    $propertyIssueModel->property_id = $propertyId;
                    $propertyIssueModel->subject_id = $subjectId;
                    $propertyIssueModel->message = $issueText;
                    $propertyIssueModel->save();


                    $returnData->result = $this->translator->translate('issue_sent_successfully');
                    $returnData->error = false;  
                }
            } else {
                //if we here it means the user sent not valid form 
                $returnData->result = false;
                $returnData->error = $this->translator->translate('error_not_valid_form');
            }                       
        } 
        
        echo Zend_Json::encode($returnData);
        $this->_helper->viewRenderer->setNoRender(true);         
    }
  
    public function ajaxApplyAction()
    {
        $currUserId = Zend_Auth::getInstance()->getIdentity();
        
        $errorMessage = ''; // if not null will be printed instead of form
        $successMessage = ''; // if not null will be printed instead of form
        $applyForm = '';
        $propertyId = $this->_getParam('item');
        $property = Model_PropertyTable::getInstance()->find($propertyId);

        if ($property and $property->is_published) {
            $propertyVisitDates = Model_PropertyVisitDatesTable::getInstance()->createQuery('pvd')
                ->where('pvd.property_id = ?', $propertyId)
                ->orderBy('pvd.availability ASC')
                ->execute();
            
            $applications = Model_PropertyApplicationTable::getInstance()->createQuery('app')
                ->where('app.visitor_id = ?', $currUserId)
                ->andWhereIn('app.property_visit_date_id', array_map(create_function('$visitDate', 'return $visitDate->id;'), $propertyVisitDates->getData()))
                ->execute();
            
            if ($applications->count() == 0) {
                $applyForm = new Form_Apply(array('propertyVisitDates' => $propertyVisitDates, 'propertyId' => $propertyId));
                
                if ($this->getRequest()->isPost() and $post = $this->getRequest()->getPost() and $applyForm->isValid($post)) {
                    $propertyApplication = new Model_PropertyApplication();
                    $propertyApplication->property_visit_date_id = $applyForm->getValue('property_visit_date_id');
                    $propertyApplication->visitor_id = $currUserId;
                    $propertyApplication->save();
                    $successMessage = 'application_sent';
                } else {
                    // Do nothing. If errorMessage and successMessage are empty,
                    // then will be print the form with marked wrong fields
                }
            } else {
                $errorMessage = 'already_applied';
            }
        } else {
            $errorMessage = $this->translator->translate('property_not_existed_or_inactive');
        }
        $this->view->applyForm = $applyForm;
        $this->view->errorMessage = $errorMessage;
        $this->view->successMessage = $successMessage;
    }
}
