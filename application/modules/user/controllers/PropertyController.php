<?php

class User_PropertyController extends Zend_Controller_Action
{

    public function init()
    {
        parent::init();
        $this->view->contentTitle = 'add_property';    
    }   
            
    /*
    * Create clear property
    */
    public function newAddAction()
    {
        $form = new User_Form_PropertyNewAdd();

        if ($this->getRequest()->isPost()) {
            $this->_helper->redirector('location', 'property', 'user');
        }

        $this->view->form = $form;
    }

    /*
    * Set rental info
    */
    public function locationAction()
    {
        $user = $this->_helper->auth->getCurrUser();
        if ($this->getRequest()->getParam('item') > 0) {
            $property = $this->getProperty($user);
        } else {
            $property = Doctrine::getTable('Model_Property')->create();
            $property->owner_id = $user->id;
        }

        $form = new User_Form_PropertyRental($user);

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {
                $data = $form->getValues();

                if ($data['availability_select'] == '' && trim($data['availability']) == '') {
                    unset($data['availability']);
                } elseif ($data['availability_select'] == 'now') {
                    $data['availability'] = Zend_Date::now()->toString('YYYY-MM-dd');
                }

                if (isset($data['availability']) && trim($data['availability']) != '') {
                    $availabilityTime = new Zend_Date($data['availability'], 'dd/MM/yyyy');
                    if ($availabilityTime === false) {
                        unset($data['availability']);
                    } else {
                        $data['availability'] = $availabilityTime->toString('YYYY-MM-dd');
                    }
                } else {
                    unset($data['availability']);
                }
                unset ($data['availability_select']);

                try {
                    if (!empty($data['availability'])) {
                        $zendDate = new Zend_Date($data['availability'], 'dd/MM/yyyy');
                        $data['availability'] = $zendDate->toString('YYYY-MM-dd');
                    }
                } catch (Exception $e) {
                    unset($data['availability']);
                }

                $property->owner_id = $user->id;
                $property->state    = Model_Property::STATE_DESCRIPTION;                   
                $property->merge($data);
                $property->save();

                $this->_helper->redirector('description', 'property', 'user', array('item' => $property->id));
            } else {
                $property->state = Model_Property::STATE_RENTAL;
                $property->save();
                $forms = new Zend_Session_Namespace('Forms');
                $forms->rental_form = $form;

                $this->_helper->redirector('location', 'property', 'user', array('item' => $property->id));
            }
        } else {
            $forms = new Zend_Session_Namespace('Forms');

            if (isset($forms->rental_form)) {
                $form = $forms->rental_form;
                unset($forms->rental_form);

                $data = $form->getValues();
                if ($data['availability'] != '') {
                    $data['availability'] = date('d/m/Y', strtotime($data['availability']));
                    if ($data['availability'] === false || $data['availability'] == '') {
                        $data['availability'] = '';
                        $data['availability_select'] = 'now';
                    }
                } else {
                    $data['availability_select'] = 'now';
                }
                $form->populate($data);

            } elseif ($property) { // Fill form for edit
                $data = $property->toArray();
                if ($data['availability'] != '') {
                    $data['availability'] = date('d/m/Y', strtotime($data['availability']));
                    if ($data['availability'] === false || $data['availability'] == '') {
                        $data['availability'] = '';
                        $data['availability_select'] = 'now';
                    }
                } else {
                    $data['availability_select'] = 'now';
                }

                $form->populate($data);
            }
        }

        $this->view->user          = $user;
        $this->view->property      = $property;
        $this->view->current_state = Model_Property::STATE_RENTAL;
        $this->view->mode          = (($property->is_published == 1) ? 'edit' : 'add');

        $this->view->form = $form;
    }

    public function descriptionAction($mode = 'add')
    {
        $user = $this->_helper->auth->getCurrUser();
        $property = $this->getProperty($user);

        $form = new User_Form_PropertyDescription();

        if ($this->getRequest()->isPost()) {

            if ($this->getRequest()->getParam('cancel') == 1) {
                $this->_helper->redirector('location', 'property', 'user', array('item' => $property->id));
            }
            
            if ($form->isValid($this->getRequest()->getParams())) {
                $property->merge($form->getData());
                $property->state = Model_Property::STATE_PHOTOS;
                $property->save();
                $this->_helper->redirector('photos', 'property', 'user', array('item' => $property->id));
            } else {
                $property->state = Model_Property::STATE_DESCRIPTION;
                $property->save();
                $forms = new Zend_Session_Namespace('Forms');
                $forms->description_form = $form;
                $this->_helper->redirector('description', 'property', 'user', array('item' => $property->id));
            }
        } else {
            $forms = new Zend_Session_Namespace('Forms');
            if (isset($forms->description_form)) {
                $form = $forms->description_form;
                unset($forms->description_form);
            } elseif ($property) { // Fill form for edit
                $form->initData($property->toArray());
            }
        }

        $this->view->property = $property;
        $this->view->current_state = Model_Property::STATE_DESCRIPTION;

        $this->view->mode = $mode;
        $this->view->form = $form;
    }

    /*
    * Upload photos
    */
    public function photosAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $property = $this->getProperty($user);

        $form_upload_photo = new User_Form_PropertyUploadPhotos();
        $form_next_step = new User_Form_PropertyPhotos();

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getParams();

            if (isset($data['form'])) {
                switch($data['form']) {
                    case 'upload': // Upload photos
                        if ($form_upload_photo->isValid($data) && $form_upload_photo->image->receive()) {
                            $tmpPath = $form_upload_photo->image->getFileName();

                            $pathinfo = pathinfo($tmpPath);

                            $filePath =  md5($tmpPath . time()) . '.' . strtolower($pathinfo['extension']);

                            if (in_array(strtolower($pathinfo['extension']), array('gif', 'jpg', 'jpeg', 'png'))) {

                                $path = $settings->get('propertyImages.basePath') . "/{$property->id}";

                                // Create property dir on first photo upload
                                if (!is_dir($path) && !mkdir($path, 0777, true)) {
                                    throw new Zend_Controller_Action_Exception("Can't create dirictory \"{$path}\"");
                                }

                                $fullFilePath = $path . '/' . $filePath;

                                if (!is_file($tmpPath)) {
                                    throw new Zend_Controller_Action_Exception($tmpPath . ' is not exists');
                                }

                                // Move photo to propery dir
                                if (!@rename($tmpPath, $fullFilePath)) {
                                    throw new Zend_Controller_Action_Exception('Can not move file to ' . $fullFilePath);
                                }

                                // Select first photo as main
                                if (empty($property->main_photo)) {
                                    $property->main_photo = $settings->get('propertyImages.baseUrl') . "/{$property->id}/{$filePath}";
                                    $property->save();
                                }

                                $this->_helper->messenger->success('photo_was_uploaded');
                            } else {
                                $this->_helper->messenger->error('invalid_image_type');
                            }
                        } else {
                            
                            $this->_helper->messenger->error('error_photo_upload');

                            $forms = new Zend_Session_Namespace('Forms');
            
                            $forms->upload_photo_form = $form_upload_photo;
                        }

                        $this->_helper->redirector('photos', 'property', 'user', array('item' => $property->id));
                        break;
                    case 'next_step': // Go next step
                        // Get images list
                        $photos = $property->getPhotos();

                        if (2 <= count($photos)) {
            
                            $data = $form_next_step->getValues();
            
                            $property->merge($data);
            
                            $property->state = Model_Property::STATE_HUNTED_PROFILE;
            
                            $property->save();
            
                            $this->_helper->redirector('hunted-profile', 'property', 'user', array('item' => $property->id));
                        } else {
                            
                            $property->state = Model_Property::STATE_PHOTOS;
            
                            $property->save();
            
                            $this->_helper->messenger->error('error_photo_count');

                            $forms = new Zend_Session_Namespace('Forms');
            
                            $forms->next_step_form = $form_next_step;
            
                            $this->_helper->redirector('photos', 'property', 'user', array('item' => $property->id));
                        }
                        break;
                    default:
                }
            }
            
            $property->state = Model_Property::STATE_PHOTOS;

            $property->save();

            $this->_helper->redirector('photos', 'property', 'user', array('item' => $property->id));

        } else {
            $forms = new Zend_Session_Namespace('Forms');

            if (isset($forms->upload_photo_form)) {

                $form_upload_photo = $forms->upload_photo_form;

                unset($forms->upload_photo_form);
            } elseif ($property) { // Fill form for edit

                $form_upload_photo->populate($property->toArray());
            }

            if (isset($forms->next_step_form)) {

                $form_next_step = $forms->next_step_form;

                unset($forms->next_step_form);
            } elseif ($property) { // Fill form for edit

                $form_next_step->populate($property->toArray());
            }
        }

        $this->view->photos            = $property->getPhotos();
        $this->view->property          = $property;
        $this->view->current_state     = Model_Property::STATE_PHOTOS;
        $this->view->form_next_step    = $form_next_step;
        $this->view->form_upload_photo = $form_upload_photo;
    }
    
    public function huntedProfileAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $property = $this->getProperty($user);

        $form = new User_Form_PropertyHuntedProfile();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {

                $property->merge($form->getData());

                $property->state = Model_Property::STATE_VISIT_DATES;

                $property->save();

                $this->_helper->redirector('visit-dates', 'property', 'user', array('item' => $property->id));
            } else {
                
                $property->state = Model_Property::STATE_HUNTED_PROFILE;

                $property->save();

                $forms = new Zend_Session_Namespace('Forms');

                $forms->hunted_profile_form = $form;

                $this->_helper->redirector('hunted-profile', 'property', 'user', array('item' => $property->id));
            }
        } else {
            $forms = new Zend_Session_Namespace('Forms');

            if (isset($forms->hunted_profile_form)) {

                $form = $forms->hunted_profile_form;

                unset($forms->hunted_profile_form);
            } elseif ($property) { // Fill form for edit
                $form->initData($property->toArray());
            }
        }

        $this->view->property = $property;

        $this->view->current_state = Model_Property::STATE_HUNTED_PROFILE;

        $this->view->form = $form;
    }

    public function editVisitDatesAction()
    {
        $this->visitDates('edit');
        //$this->_helper->viewRenderer->renderScript('property/edit-visit-dates.phtml');
        $this->_helper->viewRenderer->renderScript('property/add-visit-dates.phtml');
    }

    /*
    * Set dates for visits
    */
    public function visitDatesAction()
    {
        $this->visitDates();
        $this->_helper->viewRenderer->renderScript('property/add-visit-dates.phtml');
    }

    protected function visitDates($mode = 'add')
    {
        $user = $this->_helper->auth->getCurrUser();

        $property = $this->getProperty($user);

        $form = new User_Form_PropertyVisitDates();

        if ($this->getRequest()->isPost()) {
            $q = Doctrine::getTable('Model_PropertyVisitDates')->createQuery('pvd')
                ->where('pvd.property_id = ?', $property->id);

            $visits = $q->execute();

            if (count($visits)) {

                $data = $form->getValues();
                $property->merge($data);
                $property->state = Model_Property::STATE_PUBLISH_AD;
                $property->save();

                $this->_helper->redirector('publish-ad', 'property', 'user', array('item' => $property->id));
            } else {
                
                $property->state = Model_Property::STATE_VISIT_DATES;
                $form->isValid($this->getRequest()->getParams());
                $data = $form->getValues();

                $property->phone = $data['phone'];
                $property->save();
                $forms = new Zend_Session_Namespace('Forms');
                $forms->visit_dates_form = $form;
                $this->_helper->messenger->error('list_dates_for_visit_empty');
                $this->_helper->redirector('visit-dates', 'property', 'user', array('item' => $property->id));
            }
        } else {
            $forms = new Zend_Session_Namespace('Forms');

            if (isset($forms->visit_dates_form)) {
                $form = $forms->visit_dates_form;
                unset($forms->visit_dates_form);
            } elseif ($property) {
                $form->populate($property->toArray());
            }
        }

        $this->view->defDate = date('d/m/Y');

        $this->view->selectedDates =  $this->getSelectedDates($property);
        $this->view->property = $property;
        $this->view->user = $user;
        $this->view->mode = $mode;
        $this->view->current_state = Model_Property::STATE_VISIT_DATES;
        $this->view->form = $form;
    }
    /*
    * Publish Ad
    */
    public function publishAdAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $property = $this->getProperty($user);
        $form = new User_Form_PropertyPublishAd();
        if ($this->getRequest()->isPost()) {
            $property->is_published = 1;
            $property->save();

            $this->_helper->messenger->success('property_published');
            $this->_helper->redirector('index', 'index', 'default');
        }

        $this->view->property = $property;
        $this->view->current_state = Model_Property::STATE_PUBLISH_AD;
        $this->view->form = $form;
    }

    public function unpublishAdAction()
    {
        $user = $this->_helper->auth->getCurrUser();
        $property = $this->getProperty($user);
        $form = new User_Form_PropertyUnPublishAd();

        if ($this->getRequest()->isPost()) {
            $property->is_published = 0;
            $property->save();
            $this->_helper->messenger->success('property_unpublished');
            $this->_helper->redirector('index', 'index', 'default');
        }

        $this->view->property = $property;
        $this->view->current_state = Model_Property::STATE_PUBLISH_AD;
        $this->view->form = $form;
    }

    private function getProperty($user)
    {
        $property = Doctrine::getTable('Model_Property')->findOneById($this->_getParam('item', ''));
        if (!$property || ($property && $property->owner_id != $user->id)) {
            $this->_helper->redirector('new-add', 'property', 'user');
        }

        return $property;
    }

    private function validateState($property, $requredState)
    {
        if ($requredState > $property->state) {
            $this->_helper->redirector($property->getStateAction(), 'property', 'user', array('item' => $property->id));
        }
    }

    public function removePhotoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $user = $this->_helper->auth->getCurrUser();
        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        $property = $this->getProperty($user);
        $result = array('error' => true);

        if ($property) {
            $data = $this->getRequest()->getPost();
            
            // TODO: Refactor. Move to model
            if (isset($data['photo']) && !empty($data['photo'])) {
                $data['photo'] = str_replace("_", '/', $data['photo']);

                @unlink($settings->get('propertyImages.basePath') . '/' . $data['photo']);

                // Remove thumb images
                $image_info = pathinfo($data['photo']);

                $path = $settings->get('propertyImages.basePath') . '/' . $image_info['dirname'] . '/thumb/';

                if (is_dir($path)) {
                    if ($dh = @opendir($path)) {
                        while (($file = readdir($dh)) !== false) {
                            if (false !== strpos($file, $image_info['filename'])) {
                                @unlink($path . $file);
                            }
                        }
                        closedir($dh);
                    }
                } 

                $result['main_photo'] = '';

                // If deleted photo is main
                if ($property->main_photo == $settings->get('propertyImages.baseUrl') . '/' . $data['photo']) {
                    $property->main_photo = '';

                    $photos = $property->getPhotos();

                    if ($photos) {
                        $property->main_photo = $photos[0]['link'];
                        $result['main_photo'] = $photos[0]['name'];
                    }

                    $property->save();
                }

                $result['error'] = false;
            }
        }

        $this->_helper->json->sendJson($result);
    }

    public function mainPhotoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $user = $this->_helper->auth->getCurrUser();

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');

        $property = $this->getProperty($user);

        $result = array('error' => true);

        if ($property) {
            $data = $this->getRequest()->getPost();

            if (isset($data['photo']) && !empty($data['photo'])) {
                $data['photo'] = str_replace("_", '/', $data['photo']);

                if (is_file($settings->get('propertyImages.basePath') . '/' . $data['photo'])) {
                    if ($property->main_photo != $settings->get('propertyImages.baseUrl') . '/' . $data['photo']) {
                        $property->main_photo = '';
                        $photos = $property->getPhotos();
                        foreach ($photos as $indx => $rec) {
                            if ($photos[$indx]['link'] == $settings->get('propertyImages.baseUrl') . '/' . $data['photo']) {
                                $property->main_photo = $photos[$indx]['link'];
                                $result['main_photo'] = $photos[$indx]['name'];
                                $property->save();
                                break;
                            }
                        }
                    }
                }


                $result['error'] = false;
            }
        }

        $this->_helper->json->sendJson($result);
    }

    public function selectPhotoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $user = $this->_helper->auth->getCurrUser();

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        
        $property = $this->getProperty($user);

        $result = array('error' => true);

        if ($property) {
            $data = $this->getRequest()->getPost();

            // TODO: Refactor. Move to model
            if (isset($data['photo']) && !empty($data['photo'])) {
                $data['photo'] = str_replace("_", '/', $data['photo']);

                $property->main_photo = $settings->get('propertyImages.baseUrl') . '/' . $data['photo'];
                $property->save();
                $result['error'] = false;
            }
        }

        $this->_helper->json->sendJson($result);
    }

    public function processVisitDateAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $user = $this->_helper->auth->getCurrUser();

        $property = $this->getProperty($user);

        $result = array('error' => true, 'list' => '');

        if ($property) {
            if ($this->getRequest()->isPost()) {
                $form = new User_Form_PropertyProcessVisitDate();

                $tmp = $this->getRequest()->getParams();

                if ($form->isValid($this->getRequest()->getParams())) {
                    $data = $form->getValues();

                    $old = $data['availability'];
                    $eff_date = new Zend_Date($data['availability'], 'dd/MM/yyyy');
                    $data['availability'] = $eff_date->get('YYYY-MM-dd');

                    $q = Doctrine::getTable('Model_PropertyVisitDates')->createQuery('pvd')
                        ->where('pvd.property_id = ?', $property->id)
                        ->andWhere('pvd.availability = ?', $data['availability']);

                    $visit = $q->fetchOne();

                    if (!$visit) {
                        if (strtotime($data['availability']) >= strtotime(date('Y-m-d'))) {
                            $visit = Doctrine::getTable('Model_PropertyVisitDates')->create();
                            $visit->property_id = $property->id;
                            $visit->merge($data);
                            $visit->save();
                        }
                    } else {
                        $visit->delete();
                    }

                    if (isset($tmp['phone'])) {
                        $user->phone = $tmp['phone'];
                        $user->save();
                    }

                    $result['error'] = false;
                }
            }

            $result['list'] =  $this->getSelectedDates($property);
        }

        $this->_helper->json->sendJson($result);
    }

    public function removeVisitDateAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $user = $this->_helper->auth->getCurrUser();

        $property = $this->getProperty($user);

        $result = array('error' => true, 'list' => '');

        if ($property) {
            if ($this->getRequest()->isPost()) {
                $data = $this->getRequest()->getParams();
                
                if (isset($data['id'])) {

                    $id = explode('_', $data['id']);

                    if (2 == count($id) && 0 < intval($id[1])) {
                        $q = Doctrine::getTable('Model_PropertyVisitDates')->createQuery('pvd')
                            ->delete()
                            ->where('pvd.property_id = ?', $property->id)
                            ->andWhere('pvd.id = ?', intval($id[1]))
                            ->execute();

                        $result['error'] = false;
                    }
                }
            }

            $result['list'] =  $this->getVisits($property, true);
        }

        $this->_helper->json->sendJson($result);
    }

    private function getVisits($property, $isAjax=false)
    {
        $q = Doctrine::getTable('Model_PropertyVisitDates')->createQuery('pvd')
            ->where('pvd.property_id = ?', $property->id);

        $visits = $q->execute();

        $this->view->visits = $visits;
 
        $this->view->property = $property;

        $this->view->is_ajax = $isAjax;

        return  $this->view->render('property/process-visit-date.phtml');
    }

    private function getSelectedDates($property)
    {
        $q = Doctrine::getTable('Model_PropertyVisitDates')->createQuery('pvd')
            ->where('pvd.property_id = ?', $property->id);

        $visits = $q->execute();
        $result = array();
        if ($visits !== false){
            foreach ($visits as $indx => $rec) {
                list($y,$m,$d) = explode('-', $rec->availability);

                $result[] = array(
                    'year' => intval($y),
                    'month' => intval($m),
                    'day' => intval($d)
                );
            }
        }

        return  $result;
    }

    public function removeAction()
    {
        $user = $this->_helper->auth->getCurrUser();
        $property = $this->getProperty($user);
    }

    public function editAction()
    {
        $this->descriptionAction('edit');
    }
}

