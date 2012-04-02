<?php

class InvoiceController
    extends Zend_Controller_Action
{
    protected $invoiceMapper = null;

    public function init()
    {
        $this->invoiceMapper = new Application_Model_InvoiceMapper();

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext
            ->addActionContext('index', array('json', 'html'))
            ->addActionContext('view', array('json', 'html'))
            ->addActionContext('create', array('json', 'html'))
            ->addActionContext('update', array('json', 'html'))
            ->initContext();
    }

    public function indexAction()
    {
        $this->view->invoices = $this->invoiceMapper->fetchAll();
    }

    public function precreateAction()
    {
        /* Show all customers which have appointments that are not invoiced */
        $appointmentMapper = new Application_Model_AppointmentMapper();

        $now = new Zend_Date();
        $select = $appointmentMapper->getSelect();
        $select->where('invoiceId is null');
        $select->where('date < ?', $now->toString('yyyy-MM-dd'));

        $appointments = $appointmentMapper->fetchAll($select);
        $customerIds = '';
        foreach ($appointments as $appointment) {
            $customerIds .= $appointment->getCustomerId() . ', ';
        }
        if (count($appointments)) {
            $customerIds = substr($customerIds, 0, -2);
        } else {
            $customerIds = '-1';
        }

        $customerMapper = new Application_Model_CustomerMapper();
        $select = $customerMapper->getSelect();

        $select->where('id in ('. $customerIds . ')');
        $this->view->customers = $customerMapper->fetchAll($select);

        
    }

    public function createAction()
    {
        $this->_helper->viewRenderer->setNoRender();

        $id = $this->_getParam('id');
    
        $customerMapper = new Application_Model_CustomerMapper();
        $customer = $customerMapper->getById($id);

        $invoice = new Application_Model_Invoice();
        $invoice->setDate(new Zend_Date());
        $this->invoiceMapper->save($invoice);

        $appointmentMapper = new Application_Model_AppointmentMapper();
        $now = new Zend_Date();
        $select = $appointmentMapper->getSelect();
        $select->where('customerId = ?', $customer->getId());
        $select->where('date < ?', $now->toString('yyyy-MM-dd'));

        $appointments = $appointmentMapper->fetchAll($select);
        foreach ($appointments as $appointment) {
            $appointment->setInvoice($invoice);
            $appointmentMapper->save($appointment);
        }
            
    }

    public function viewAction()
    {
        $id = $this->_getParam('id');

        $this->view->invoice = $this->invoiceMapper->getById($id);
        $this->view->appointments = $this->view->invoice->getAppointments();
    }

    public function processAction() 
    {
    }

}
