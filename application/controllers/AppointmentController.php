<?php

class AppointmentController
    extends Zend_Controller_Action
{
    protected $appointmentMapper = null;

    public function init()
    {
        $this->appointmentMapper = new Application_Model_AppointmentMapper();

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('index', array('json', 'html'))
            ->addActionContext('create', array('json', 'html'))
            ->addActionContext('update', array('json', 'html'))
            ->initContext();
    }

    public function indexAction()
    {
        $this->view->appointments = $this->appointmentMapper->fetchAll();
    }

    public function listAction()
    {
        $this->view->appointments = $this->appointmentMapper->fetchAll();
    }

    public function viewAction()
    {
        $id = $this->_getParam('id');
        $this->view->appointment = $this->appointmentMapper->getById($id);
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_Appointment();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();
                $appointment = new Application_Model_Appointment();
                $mapper = new Application_Model_AppointmentMapper();

                $therapyMapper = new Application_Model_TherapyMapper();
                $therapy = $therapyMapper->getById($values['therapy']);

                /* Lookup therapist */
                $therapistMapper = new Application_Model_TherapistMapper();
                $select = $therapistMapper->getSelect();
                $select->where('Name = ?', $values['therapist']);
                $therapist = array_shift($therapistMapper->fetchAll($select));

                /* Lookup customer */
                $customerMapper = new Application_Model_CustomerMapper();
                $select = $customerMapper->getSelect();
                $select->where('Name = ?', $values['customer']);
                $customer = array_shift($customerMapper->fetchAll($select));

                $appointment->setTherapy($therapy);
                $appointment->setTherapist($therapist);
                $appointment->setCustomer($customer);
                $appointment->setDate(new Zend_Date($values['date'], 'dd-MM-yyyy'));
                $appointment->setDuration($values['duration'], Application_Model_Appointment::DURATION_MINUTES);

                $mapper->save($appointment);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'Appointment ' . $appointment . ' created!';
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
        $form = new Application_Form_Appointment();
        $id = $this->_getParam('id');
        $appointment = $this->appointmentMapper->getById($id);
        /* This form doesn't work with appointment::toArray function*/
        $form->getElement('therapy')->setValue($appointment->getTherapyId());
        $form->getElement('therapist')->setValue($appointment->getTherapist()->getName());
        $form->getElement('customer')->setValue($appointment->getCustomer()->getName());
        $form->getElement('date')->setValue($appointment->getDate()->toString('dd-MM-yyyy'));
        $form->getElement('duration')->setValue($appointment->getDuration(Application_Model_Appointment::DURATION_MINUTES));
        $form->getElement('submit')->setLabel('Update');

        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $values = $form->getValues();
                $mapper = new Application_Model_AppointmentMapper();

                $therapyMapper = new Application_Model_TherapyMapper();
                $therapy = $therapyMapper->getById($values['therapy']);

                /* Lookup therapist */
                $therapistMapper = new Application_Model_TherapistMapper();
                $select = $therapistMapper->getSelect();
                $select->where('Name = ?', $values['therapist']);
                $therapist = array_shift($therapistMapper->fetchAll($select));

                /* Lookup customer */
                $customerMapper = new Application_Model_CustomerMapper();
                $select = $customerMapper->getSelect();
                $select->where('Name = ?', $values['customer']);
                $customer = array_shift($customerMapper->fetchAll($select));

                $appointment->setTherapy($therapy);
                $appointment->setTherapist($therapist);
                $appointment->setCustomer($customer);
                $appointment->setDate(new Zend_Date($values['date'], 'dd-MM-yyyy'));
                $appointment->setDuration($values['duration'], Application_Model_Appointment::DURATION_MINUTES);

                $mapper->save($appointment);

                if ($this->getRequest()->isXmlHttpRequest()) {
                    $this->view->result = true;
                    $this->view->message = 'Appointment ' . $appointment . ' updated!';
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

        $appointment = new Application_Model_Appointment(array('id' => $this->_getParam('id')));
        $mapper = new Application_Model_AppointmentMapper();

        $mapper->delete($appointment);

        return $this->_helper->redirector('index');
    }
        
}
