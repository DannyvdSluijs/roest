<?php

class TherapistController
    extends Zend_Controller_Action
{
    protected $therapistMapper = null;

    public function init()
    {
        $this->therapistMapper = new Application_Model_TherapistMapper();

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('index', array('json', 'html'))
            ->addActionContext('create', array('json', 'html'))
            ->addActionContext('update', array('json', 'html'))
            ->initContext();
    }

    public function indexAction()
    {
        $this->view->therapists = $this->therapistMapper->fetchAll();
    }

    public function listAction()
    {
        $this->view->therapists = $this->therapistMapper->fetchAll();
    }

    public function viewAction()
    {
        $id = $this->_getParam('id');
        $this->view->therapist = $this->therapistMapper->getById($id);
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_Therapist();

        /* New therapist is always active */
        $form->removeElement('active');

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $therapist = new Application_Model_Therapist($form->getValues());
                $mapper = new Application_Model_TherapistMapper();

                $mapper->save($therapist);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'Therapist ' . $therapist . ' created!';
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
        $form = new Application_Form_Therapist();
        $id = $this->_getParam('id');
        $therapist = $this->therapistMapper->getById($id);
        $form->populate($therapist->toArray());
        $form->getElement('submit')->setLabel('Update');

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $therapist->setOptions($form->getValues());
                $mapper = new Application_Model_TherapistMapper();

                $mapper->save($therapist);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'Therapist ' . $therapist . ' updated!';
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

        $therapist = new Application_Model_Therapist(array('id' => $this->_getParam('id')));
        $mapper = new Application_Model_TherapistMapper();

        $mapper->delete($therapist);

        return $this->_helper->redirector('index');
    }
        
}
