<?php

class TherapyController
    extends Zend_Controller_Action
{
    protected $therapyMapper = null;

    public function init()
    {
        $this->therapyMapper = new Application_Model_TherapyMapper();

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('index', array('json', 'html'))
            ->addActionContext('view', array('json', 'html'))
            ->addActionContext('create', array('json', 'html'))
            ->addActionContext('update', array('json', 'html'))
            ->addActionContext('addprice', array('json', 'html'))
            ->addActionContext('deleteprice', array('json', 'html'))
            ->initContext();
    }

    public function indexAction()
    {
        $this->view->therapies = $this->therapyMapper->fetchAll();
    }

    public function listAction()
    {
        $this->view->therapies = $this->therapyMapper->fetchAll();
    }

    public function viewAction()
    {
        $id = $this->_getParam('id');
        $this->view->therapy = $this->therapyMapper->getById($id);
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_Therapy();

        /* New therapist is always active */
        $form->removeElement('active');

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();
                $therapy = new Application_Model_Therapy($values);
                $mapper = new Application_Model_TherapyMapper();

                $therapy->setId($mapper->save($therapy));

                /* On create we ask for the first price as well */
                $therapyPrice = new Application_Model_TherapyPrice();
                $mapper = new Application_Model_TherapyPriceMapper();
                $therapyPrice->setTherapy($therapy)
                    ->setFromDate(Zend_date::now())
                    ->setPricePerHour($values['price']);

                $mapper->save($therapyPrice);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'Therapy ' . $therapy . ' created!';
                } else {
                    $this->_helper->redirector('index');
                }
            }
        }

        $this->view->form = $form;
    }

    public function updateAction()
    {
        if (!$this->_hasParam('id')) {
            throw new InvalidArgumentException();
        }

        $request = $this->getRequest();
        $form = new Application_Form_Therapy();
        $form->removeElement('price');
        $id = $this->_getParam('id');
        $therapy = $this->therapyMapper->getById($id);
        $form->populate($therapy->toArray());
        $form->getElement('submit')->setLabel('Update');

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $therapy->setOptions($form->getValues());
                $mapper = new Application_Model_TherapyMapper();

                $mapper->save($therapy);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'Therapy ' . $therapy . ' updated!';
                } else {
                    $this->_helper->redirector('index');
                }
            }
        }

        $this->view->form = $form;
    }


    public function deleteAction()
    {
        if (!$this->_hasParam('id')) {
            throw new InvalidArgumentException();
        }

        $therapy = new Application_Model_Therapy(array('id' => $this->_getParam('id')));
        $mapper = new Application_Model_TherapyMapper();

        $mapper->delete($therapy);

        return $this->_helper->redirector('index');
    }

    public function addpriceAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_TherapyPrice();

        $id = $this->_getParam('id');
        $this->view->therapy = $this->therapyMapper->getById($id);

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();
                $therapyPrice = new Application_Model_TherapyPrice();
                $mapper = new Application_Model_TherapyPriceMapper();

                $therapyPrice->setTherapy($this->view->therapy);
                $therapyPrice->setFromDate(new Zend_Date($values['fromDate'], 'dd-MM-yyyy'));
                $therapyPrice->setPricePerHour($values['pricePerHour']);

                $mapper->save($therapyPrice);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'Therapy price ' . $therapyPrice . ' was added!';
                } else {
                    $this->_helper->redirector('index');
                }
            }
        }

        $this->view->form = $form;
    }
        
    public function deletepriceAction()
    {
        $therapyPriceMapper = new Application_Model_TherapyPriceMapper();

        $select = $therapyPriceMapper->getSelect();

        $date = new Zend_Date($this->_getParam('fromDate'), 'dd-MM-yyyy');

        $select
            ->where('therapyId = ?', $this->_getParam('therapyId'))
            ->where('fromDate = ?', $date->toString('yyyy-MM-dd'));

        $therapyPrice = array_pop($therapyPriceMapper->fetchAll($select));

        if (!is_null($therapyPrice)) {
            $therapyPriceMapper->delete($therapyPrice);
        }
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->view->result = true;
            $this->view->message = 'Therapy price ' . $therapyPrice . ' was deleted!';
        } else {
            $this->_helper->redirector('index');
        }
    }
        
}
