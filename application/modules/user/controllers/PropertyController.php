<?php

class User_PropertyController extends Zend_Controller_Action
{

    /*
    * Create clear property
    */
    public function newAddAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $form = new User_Form_PropertyNewAdd();

        if ($this->getRequest()->isPost()) {
            
            $property = Doctrine::getTable('Model_Property')->create();

            $property->owner_id = $user->id;
            $property->state    = Model_Property::STATE_RENTAL;

            $property->save();

            $this->_helper->redirector('rental', 'property', 'user', array('item' => $property->id));
        }

        $this->view->form = $form;
    }

    /*
    * Set rental info
    */
    public function rentalAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $property = $this->getProperty($user);

        $form = new User_Form_PropertyRental();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {

                $data = $form->getValues();

                $property->merge($data);

                $property->state = Model_Property::STATE_DESCRIPTION;

                $property->save();

                $this->_helper->redirector('description', 'property', 'user', array('item' => $property->id));
            } else {
                
                $property->state = Model_Property::STATE_RENTAL;

                $property->save();

                $forms = new Zend_Session_Namespace('Forms');

                $forms->rental_form = $form;

                $this->_helper->redirector('rental', 'property', 'user', array('item' => $property->id));
            }
        } else {
            $forms = new Zend_Session_Namespace('Forms');

            if (isset($forms->rental_form)) {

                $form = $forms->rental_form;

                unset($forms->rental_form);
            } elseif ($property) { // Fill form for edit

                $form->populate($property->toArray());
            }
        }

        $this->view->property = $property;

        $this->view->current_state = Model_Property::STATE_RENTAL;

        $this->view->form = $form;
    }

    public function descriptionAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $property = $this->getProperty($user);

        $form = new User_Form_PropertyDescription();

        if ($this->getRequest()->isPost()) {
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

        $this->view->form = $form;
    }

    public function photosAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $property = $this->getProperty($user);

        $form = new User_Form_PropertyPhotos();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {

                $data = $form->getValues();

                $property->merge($data);

                $property->state = Model_Property::STATE_HUNTED_PROFILE;

                $property->save();

                $this->_helper->redirector('hunted-profile', 'property', 'user', array('item' => $property->id));
            } else {
                
                $property->state = Model_Property::STATE_PHOTOS;

                $property->save();

                $forms = new Zend_Session_Namespace('Forms');

                $forms->photos_form = $form;

                $this->_helper->redirector('photos', 'property', 'user', array('item' => $property->id));
            }
        } else {
            $forms = new Zend_Session_Namespace('Forms');

            if (isset($forms->photos_form)) {

                $form = $forms->photos_form;

                unset($forms->photos_form);
            } elseif ($property) { // Fill form for edit

                $form->populate($property->toArray());
            }
        }

        $this->view->property = $property;

        $this->view->current_state = Model_Property::STATE_PHOTOS;

        $this->view->form = $form;
    }
    
    public function huntedProfileAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $property = $this->getProperty($user);

        $form = new User_Form_PropertyHuntedProfile();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {

                $data = $form->getValues();

                $property->merge($data);

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

                $form->populate($property->toArray());
            }
        }

        $this->view->property = $property;

        $this->view->current_state = Model_Property::STATE_HUNTED_PROFILE;

        $this->view->form = $form;
    }

    /*
    * Set dates for visits
    */
    public function visitDatesAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $property = $this->getProperty($user);

        $form = new User_Form_PropertyVisitDates();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getParams())) {

                $data = $form->getValues();

                $property->merge($data);

                $property->state = Model_Property::STATE_PUBLISH_AD;

                $property->save();

                $this->_helper->redirector('publish-ad', 'property', 'user', array('item' => $property->id));
            } else {
                
                $property->state = Model_Property::STATE_VISIT_DATES;

                $property->save();

                $forms = new Zend_Session_Namespace('Forms');

                $forms->visit_dates_form = $form;

                $this->_helper->redirector('visit-dates', 'property', 'user', array('item' => $property->id));
            }
        } else {
            $forms = new Zend_Session_Namespace('Forms');

            if (isset($forms->visit_dates_form)) {

                $form = $forms->visit_dates_form;

                unset($forms->visit_dates_form);
            } elseif ($property) { // Fill form for edit

                $form->populate($property->toArray());
            }
        }

        $this->view->property = $property;

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

    // Old -------------------------------------------------------

    public function wellRentalAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $property = $this->getProperty($user);

        $form = new User_Form_WellRental();        

        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getParams())) {

                $data = $form->getValues();

                if (!$property) { // On create
                    $property = Doctrine::getTable('Model_Property')->create();
                
                    $data = $property->setOnCreateDefaults($data, $user);
                }
            
                $property->merge($data);

                $property->save();

                $this->_helper->redirector('well-description-of-property', 'property', 'user', array('item' => $property->id));
            } else {
                
                $forms = new Zend_Session_Namespace('Forms');
                $forms->well_rental_form = $form;

                if ($property) {

                    $this->_helper->redirector('well-rental', 'property', 'user', array('item' => $property->id));
                } else {

                    $this->_helper->redirector('well-rental', 'property', 'user');
                }
            }
        } elseif ($this->getRequest()->isGet()) {

            $forms = new Zend_Session_Namespace('Forms');

            if (isset($forms->well_rental_form)) {

                $form = $forms->well_rental_form;
                unset($forms->well_rental_form);
            } elseif ($property) { // Fill form for edit

                $form->populate($property->toArray());
            }
        }

        $this->view->jQuery()->addStylesheet('/css/ui/jquery-ui-1.8.14.css');

        $this->view->form = $form;
    }

    public function wellDescriptionOfPropertyAction()
    {
        $user = $this->_helper->auth->getCurrUser();
        
        $property = $this->getProperty($user);

        if (!$property) {

            $this->_helper->redirector('well-rental', 'property', 'user');
        }

        $form = new User_Form_WellDescriptionOfProperty();        

        if ($this->getRequest()->isPost()) {

            if ($form->isValid($this->getRequest()->getParams())) {

                $property->merge($form->getData());

                $property->save();

                $this->_helper->redirector('well-upload-photos', 'property', 'user', array('item' => $property->id));
            } else {

                $forms = new Zend_Session_Namespace('Forms');
                $forms->well_description_of_property = $form;

                $this->_helper->redirector('well-description-of-property', 'property', 'user', array('item' => $property->id));
            }
        } elseif ($this->getRequest()->isGet()) {

            $forms = new Zend_Session_Namespace('Forms');

            if (isset($forms->well_description_of_property)) {

                $form = $forms->well_description_of_property;
                unset($forms->well_description_of_property);
            } elseif ($property) { // Fill form for edit

                $form->initData($property->toArray());
            }
        }

        $this->view->property = $property;

        $this->view->form = $form;
    }

    public function wellUploadPhotosAction()
    {
        $user = $this->_helper->auth->getCurrUser();

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        
        $property = $this->getProperty($user);

        if (!$property) {

            $this->_helper->redirector('well-description-of-property', 'property', 'user', array('item' => $property->id));
        }

        $form = new User_Form_WellUploadPhotos();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost()) && $form->image->receive()) {
                $tmpPath = $form->image->getFileName();

                $pathinfo = pathinfo($tmpPath);

                $filePath =  md5($tmpPath . time()) . '.' . strtolower($pathinfo['extension']);
                
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
    		    $this->_helper->messenger->error('error_photo_upload');

                $forms = new Zend_Session_Namespace('Forms');
                $forms->well_upload_photos = $form;
            }
            $this->_helper->redirector('well-upload-photos', 'property', 'user', array('item' => $property->id));
        }  elseif ($this->getRequest()->isGet()) {
            $forms = new Zend_Session_Namespace('Forms');

            if (isset($forms->well_upload_photos)) {

                $form = $forms->well_upload_photos;
                unset($forms->well_upload_photos);
            }
        }

        $this->view->photos             = $property->getPhotos();
        $this->view->property           = $property;
        $this->view->property_type      = Model_Property::getTypes();
        $this->view->number_of_rooms1   = Model_Property::getNumberOfRooms1Info();
        $this->view->number_of_rooms2   = Model_Property::getNumberOfRooms2Info();
        $this->view->number_of_bathrooms = Model_Property::getNumberOfBathroomsInfo();
        $this->view->multi_checkboxses  = User_Form_WellDescriptionOfProperty::getMultiCheckboxses();
        
        $this->view->form = $form;
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

                // Remove thub images
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

}

