<?php

class InsuranceagencyController
    extends Zend_Controller_Action
{
    protected $insuranceAgencyMapper = null;

    public function init()
    {
        $this->insuranceAgencyMapper = new Application_Model_InsuranceAgencyMapper();

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('index', array('json', 'html'))
            ->addActionContext('create', array('json', 'html'))
            ->addActionContext('update', array('json', 'html'))
            ->initContext();
    }

    public function indexAction()
    {
        $this->view->insuranceAgencies = $this->insuranceAgencyMapper->fetchAll();
    }

    public function listAction()
    {
        $this->view->insuranceAgencies = $this->insuranceAgencyMapper->fetchAll();
    }

    public function viewAction()
    {
        $id = $this->_getParam('id');
        $this->view->insuranceAgency = $this->insuranceAgencyMapper->getById($id);
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_InsuranceAgency();

        /* New insurance agency is always active */
        $form->removeElement('active');

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $insuranceAgency = new Application_Model_InsuranceAgency($form->getValues());
                $mapper = new Application_Model_InsuranceAgencyMapper();

                $mapper->save($insuranceAgency);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'InsuranceAgency ' . $insuranceAgency . ' created!';
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
        $form = new Application_Form_InsuranceAgency();
        $id = $this->_getParam('id');
        $insuranceAgency = $this->insuranceAgencyMapper->getById($id);
        $form->populate($insuranceAgency->toArray());
        $form->getElement('submit')->setLabel('Update');

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $insuranceAgency->setOptions($form->getValues());
                $mapper = new Application_Model_InsuranceAgencyMapper();

                $mapper->save($insuranceAgency);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'InsuranceAgency ' . $insuranceAgency . ' updated!';
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

        $insuranceAgency = new Application_Model_InsuranceAgency(array('id' => $this->_getParam('id')));
        $mapper = new Application_Model_InsuranceAgencyMapper();

        $mapper->delete($insuranceAgency);

        return $this->_helper->redirector('index');
    }
        
}
