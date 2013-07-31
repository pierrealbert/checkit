<?php

class User_PropertyController extends Zend_Controller_Action
{
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
                
                if (!is_dir($path) && !mkdir($path, 0777, true)) {
                    throw new Zend_Controller_Action_Exception("Can't create dirictory \"{$path}\"");
                }

                $fullFilePath = $path . '/' . $filePath;

                if (!is_file($tmpPath)) {
                    throw new Zend_Controller_Action_Exception($tmpPath . ' is not exists');
                }

                if (!@rename($tmpPath, $fullFilePath)) {
                    throw new Zend_Controller_Action_Exception('Can not move file to ' . $fullFilePath);
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

    private function getProperty($user)
    {
        $property = Doctrine::getTable('Model_Property')->findOneById($this->_getParam('item', ''));

        if ($property && $property->owner_id != $user->id) {

            $this->_helper->redirector('well-rental', 'property', 'user');
        }

        return $property;
    }

    public function removePhotoAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();

        $user = $this->_helper->auth->getCurrUser();

        $settings = Zend_Controller_Action_HelperBroker::getStaticHelper('settings');
        
        $property = $this->getProperty($user);

        if ($property) {
            $data = $this->getRequest()->getPost();

            if (isset($data['photo']) && !empty($data['photo'])) {
                $data['photo'] = str_replace("_", '/', $data['photo']);

                @unlink($settings->get('propertyImages.basePath') . '/' . $data['photo']);
            }
        }

        $this->_helper->json->sendJson(array());
    }
}

