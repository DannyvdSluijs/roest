<?php

class BusinessRuleController
    extends Zend_Controller_Action
{
    protected $businessRuleMapper = null;

    public function init()
    {
        $this->businessRuleMapper = new Application_Model_BusinessRuleMapper();

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('index', array('json', 'html'))
            ->addActionContext('create', array('json', 'html'))
            ->addActionContext('update', array('json', 'html'))
            ->initContext();
    }

    public function indexAction()
    {
        $this->view->businessRules = $this->businessRuleMapper->fetchAll();
    }

    public function listAction()
    {
        $this->view->businessRules = $this->businessRuleMapper->fetchAll();
    }

    public function viewAction()
    {
        $id = $this->_getParam('id');
        $this->view->businessRule = $this->businessRuleMapper->getById($id);
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_BusinessRule();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $businessRule = new Application_Model_BusinessRule();
                $values = $form->getValues();
                $mapper = new Application_Model_BusinessRuleMapper();

                $businessRule->setKey($values['key']);
                $businessRule->setValue($values['value']);
                $businessRule->setFromDate(new Zend_Date($values['fromDate'], 'dd-MM-yyyy'));

                $mapper->save($businessRule);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'BusinessRule ' . $businessRule . ' created!';
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
        $form = new Application_Form_BusinessRule();
        $id = $this->_getParam('id');
        $businessRule = $this->businessRuleMapper->getById($id);
        $form->populate($businessRule->toArray());
        $form->getElement('submit')->setLabel('Update');

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $businessRule->setOptions($form->getValues());
                $mapper = new Application_Model_BusinessRuleMapper();

                $mapper->save($businessRule);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'BusinessRule ' . $businessRule . ' updated!';
                } else {
                    $this->_helper->redirector('index');
                }
            }
        }

        $this->view->form = $form;
    }


    public function deleteAction()
    {
        if (!$this->_hasParam('key') && !$this->_hasParam('fromDate')) {
            throw new InvalidArgumentException();
        }

        $businessRule = new Application_Model_BusinessRule(
            array(
                'key' => $this->_getParam('key'),
                'fromDate' => new Zend_Date($this->_getParam('fromDate'), 'dd-MM-yyyy')
            )
        );
        $mapper = new Application_Model_BusinessRuleMapper();

        $mapper->delete($businessRule);

        return $this->_helper->redirector('index');
    }
        
}
