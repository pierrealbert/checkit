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

        $this->view->property      = $property;
        $this->view->property_type = Model_Property::getTypes();

        $options = Zend_Controller_Action_HelperBroker::getStaticHelper('settings')
            ->get('services.googleMaps');

        $this->view->google_api_key = $options['apiKey'];
    }
}