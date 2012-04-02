<?php

class CustomerController
    extends Zend_Controller_Action
{
    protected $customerMapper = null;

    public function init()
    {
        $this->customerMapper = new Application_Model_CustomerMapper();

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('index', array('json', 'html'))
            ->addActionContext('view', array('json', 'html'))
            ->addActionContext('create', array('json', 'html'))
            ->addActionContext('update', array('json', 'html'))
            ->addActionContext('addcustomerinsurance', array('json', 'html'))
            ->addActionContext('deletecustomerinsurance', array('json', 'html'))
            ->initContext();
    }

    public function indexAction()
    {
        $this->view->customers = $this->customerMapper->fetchAll();
    }

    public function listAction()
    {
        $this->view->customers = $this->customerMapper->fetchAll();
    }

    public function viewAction()
    {
        $id = $this->_getParam('id');
        $this->view->customer = $this->customerMapper->getById($id);
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_Customer();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $customer = new Application_Model_Customer($form->getValues());
                $mapper = new Application_Model_CustomerMapper();

                $mapper->save($customer);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'Customer ' . $customer . ' created!';
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
        $form = new Application_Form_Customer();
        $id = $this->_getParam('id');
        $customer = $this->customerMapper->getById($id);
        $form->populate($customer->toArray());
        $form->getElement('submit')->setLabel('Update');

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $customer->setOptions($form->getValues());
                $mapper = new Application_Model_CustomerMapper();

                $mapper->save($customer);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'Customer ' . $customer . ' updated!';
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

        $customer = new Application_Model_Customer(array('id' => $this->_getParam('id')));
        $mapper = new Application_Model_CustomerMapper();

        $mapper->delete($customer);

        return $this->_helper->redirector('index');
    }
        
    public function addcustomerinsuranceAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_CustomerInsurance();

        $id = $this->_getParam('id');
        $mapper = new Application_Model_CustomerMapper();
        $this->view->customer = $mapper->getById($id);

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();
                $customerInsurance = new Application_Model_CustomerInsurance();
                $mapper = new Application_Model_CustomerInsuranceMapper();
                $insuranceAgencyMapper = new Application_Model_InsuranceAgencyMapper();

                $customerInsurance->setCustomer($this->view->customer);

                $customerInsurance->setInsuranceAgency($insuranceAgencyMapper->getById($values['insuranceAgency']));
                $customerInsurance->setFromDate(new Zend_Date($values['fromDate'], 'dd-MM-yyyy'));
                $customerInsurance->setPolisNumber($values['polisNumber']);

                $mapper->save($customerInsurance);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'Customer insurance agency ' . $customerInsurance . ' was added!';
                } else {
                    $this->_helper->redirector('index');
                }
            }
        }

        $this->view->form = $form;
    }
        
    public function deletecustomerinsuranceAction()
    {
        $customerInsuranceMapper = new Application_Model_CustomerInsuranceMapper();

        $select = $customerInsuranceMapper->getSelect();

        $date = new Zend_Date($this->_getParam('fromDate'), 'dd-MM-yyyy');

        $select
            ->where('customerId = ?', $this->_getParam('id'))
            ->where('insuranceAgencyId = ?', $this->_getParam('insuranceAgencyId'))
            ->where('fromDate = ?', $date->toString('yyyy-MM-dd'));

        $customerInsurance = array_pop($customerInsuranceMapper->fetchAll($select));

        if (!is_null($customerInsurance)) {
            $customerInsuranceMapper->delete($customerInsurance);
        }
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            $this->view->result = true;
            $this->view->message = 'Customer insurance ' . $customerInsurance . ' was deleted!';
        } else {
            $this->_helper->redirector('index');
        }
    }
        
}
