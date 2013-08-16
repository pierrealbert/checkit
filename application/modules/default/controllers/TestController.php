<?php

class TestController extends Zend_Controller_Action
{
    public function adListAction()
    {
        $ads = Doctrine::getTable('Model_Property')->createQuery('p')
            ->select('p.*, (p.amount_of_rent_excluding_charges + p.amount_of_charges) as price')
            ->where('p.is_published = ?', 1)
            ->execute();

        $this->view->ads = $ads;
    }
}
