<?php

class ReportController
    extends Zend_Controller_Action
{
    public function dueAction()
    {
        $invoiceMapper = new Application_Model_InvoiceMapper();

        $select = $invoiceMapper->getSelect();
        $select
            ->where('paid = 0')
            ->order('date DESC');

        $this->view->invoices = $invoiceMapper->fetchAll($select);
    }

    public function citiesAction()
    {
        $customerDbTable = new Application_Model_DbTable_Customer();

        $select = $customerDbTable->select();
        $select
            ->from(
                $customerDbTable, 
                array(
                    'MAX(city) AS city',
                    'COUNT(*) AS number'
                )
            )
            ->group('city')
            ->order('number DESC');

        $this->view->data = $customerDbTable->fetchAll($select);
    }

    public function therapistAction()
    {
        $therapistDbTable = new Application_Model_DbTable_Therapist();

        $select = $therapistDbTable->select();
        $select
            ->setIntegrityCheck(false)
            ->from(
                $therapistDbTable, 
                array(
                    'MAX(therapist.name) AS therapist',
                    'MAX(Therapy.name) AS therapy',
                    'COUNT(*) AS number',
                    'SUM(duration) AS total'
                )
            )
            ->joinInner('Appointment', 'Appointment.therapistId = therapist.id')
            ->joinInner('Therapy', 'Appointment.therapyId = Therapy.id')
            ->group('therapistId')
            ->group('therapyId')
            ->order('MAX(Therapist.name), MAX(Therapy.name)');

        $this->view->data = $therapistDbTable->fetchAll($select);
    }

}
