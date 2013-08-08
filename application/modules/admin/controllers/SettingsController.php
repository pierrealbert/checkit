<?php

class Admin_SettingsController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $options = Doctrine::getTable('Model_SettingsOption')->findAll();

        $form = new Admin_Form_Settings();
        $form->setSettings($options->toArray());

        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {

            $settings = $form->getSettingsValues();

            foreach ($settings as $name => $value) {
                $settingOption = Doctrine::getTable('Model_SettingsOption')->findOneByName($name);

                if (!$settingOption) {
                    $settingOption = new Model_SettingsOption();
                }

                $settingOption->name  = $name;
                $settingOption->value = $value;
                $settingOption->save();
            }

            $this->_helper->messenger->success('settings_updated');
        }

        $this->view->form = $form;
    }
}
