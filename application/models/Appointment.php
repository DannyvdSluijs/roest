<?php

class Application_Model_Appointment
    extends Application_Model_AbstractModel
{
    const DURATION_HOURS = 0;
    const DURATION_MINUTES = 1;

    protected $therapyId;
    protected $therapistId;
    protected $customerId;
    protected $invoiceId;
    protected $date;
    protected $duration;

    protected $therapy;
    protected $therapist;
    protected $customer;
    protected $invoice;

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'therapyId' => $this->therapyId,
            'therapistId' => $this->therapistId,
            'customerId' => $this->customerId,
            'invoiceId' => $this->invoiceId,
            'duration' => $this->duration,
            'date' => $this->date
        );
    }

    public function __toString()
    {
        return 
            $this->getTherapy()->__toString() . ' by ' . 
            $this->getTherapist()->__toString() . ' for ' . 
            $this->getCustomer()->__toString() . ' on ' .
            $this->date->toString('dd-MM-yyyy');
    }

    public function setTherapyId($id)
    {
        $this->therapyId = (int) $id;
        $this->therapy = null;
        return $this;
    }

    public function getTherapyId()
    {
        return $this->therapyId;
    }

    public function setTherapy(Application_Model_Therapy $therapy)
    {
        $this->therapy = $therapy;
        $this->therapyId = (int) $therapy->getId();
        return $this;
    }

    public function getTherapy()
    {
        if (is_null($this->therapy)) {
            $therapyMapper = new Application_Model_TherapyMapper();
            $this->therapy = $therapyMapper->getById($this->therapyId);
        }
        return $this->therapy;
    }

    public function setTherapistId($id)
    {
        $this->therapistId = (int) $id;
        $this->therapist = null;
        return $this;
    }

    public function getTherapistId()
    {
        return $this->therapist;
    }

    public function setTherapist(Application_Model_Therapist $therapist)
    {
        $this->therapist = $therapist;
        $this->therapistId = (int) $therapist->getId();
        return $this;
    }

    public function getTherapist()
    {
        if (is_null($this->therapist)) {
            $therapistMapper = new Application_Model_TherapistMapper();
            $this->therapist = $therapistMapper->getById($this->therapistId);
        }
        return $this->therapist;
    }

    public function setCustomerId($id)
    {
        $this->customerId = (int) $id;
        $this->customer = null;
        return $this;
    }

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function setCustomer(Application_Model_Customer $customer)
    {
        $this->customer = $customer;
        $this->customerId = (int) $customer->getId();
        return $this;
    }

    public function getCustomer()
    {
        if (is_null($this->customer)) {
            $customerMapper = new Application_Model_CustomerMapper();
            $this->customer = $customerMapper->getById($this->customerId);
        }
        return $this->customer;
    }

    public function setInvoiceId($id)
    {
        $this->incoiceId = (int) $id;
        $this->invoice = null;
        return $this;
    }

    public function getInvoiceId()
    {
        return $this->invoiceId;
    }

    public function setInvoice(Application_Model_Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->invoiceId = (int) $invoice->getId();
        return $this;
    }

    public function getInvoice()
    {
        if (is_null($this->invoice)) {
            $invoiceMapper = new Application_Model_InvoiceMapper();
            $this->invoice = $invoiceMapper->getById($this->invoiceId);
        }
        return $this->invoice;
    }

    public function setDuration($duration, $measured_in = self::DURATION_HOURS)
    {
        switch ($measured_in) {
            case self::DURATION_MINUTES:
                /* Translate minutes to quarters of an hour */
                $this->duration = ceil($duration / 15) * 0.25;
                break;
            case self::DURATION_HOURS:
            default:
                $this->duration = $duration;
                break;
        }
        return $this;
    }

    public function getDuration($measured_in = self::DURATION_HOURS)
    {
        switch ($measured_in) {
            case self::DURATION_MINUTES:
                /* Translate hours to minutes */
                return $this->duration * 60;
                break;
            case self::DURATION_HOURS:
            default:
                return $this->duration;
                break;
        }
    }

    public function setDate(Zend_Date $date)
    {
        $this->date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getPrice()
    {
        return number_format($this->getTherapy()->getTherapyPriceByDate($this->date)->getPricePerHour() * $this->getDuration(self::DURATION_HOURS), 2);
    }

}
