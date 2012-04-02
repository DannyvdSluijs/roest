<?php

class Application_Model_Invoice
    extends Application_Model_AbstractModel
{
    protected $date;
    protected $paid = false;
    protected $reminder = 0;

    protected $appointments;
    protected $customer;

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'date' => $this->date,
            'paid' => $this->paid,
            'reminder' => $this->reminder
        );
    }

    public function __toString()
    {
        return 
            $this->id . ':' . 
            $this->date->toString('dd-MM-yyyy') . ' ' . 
            ($this->paid ? 'paid' : 'unpaid') . ' with ' . 
            $this->reminder . ' reminders';
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

    public function setPaid($paid)
    {
        $this->paid = (bool) $paid;
        return $this;
    }

    public function getPaid()
    {
        return $this->paid;
    }

    public function setReminder($reminder)
    {
        $this->reminder = (int) $reminder;
        return $this;
    }

    public function getReminder()
    {
        return $this->reminder;
    }

    public function getAppointments()
    {
        if (empty($this->appointments)) {
            $appointmentMapper = new Application_Model_AppointmentMapper();
            $this->appointments = $appointmentMapper->getByInvoice($this);
        }

        return $this->appointments;
    }

    public function getCustomer()
    {
        $appointments = $this->getAppointments();
        return $appointments[0]->getCustomer();
    }

    public function getTotalPrice()
    {
        $totalPrice = 0;
        foreach ($this->getAppointments() as $appointment) {
            $totalPrice += (float) $appointment->getPrice();
        }

        return number_format($totalPrice, 2);
    }

    public function getTotalPriceExcludingVat()
    {
        return number_format($this->getTotalPrice() / 100 * (100 - $this->getVatPercentage()), 2);
    }

    public function getVat()
    {
        return number_format($this->getTotalPrice() / 100 * $this->getVatPercentage(), 2);
    }

    public function getVatPercentage()
    {
        $businessRuleMapper = new Application_Model_BusinessRuleMapper(); 
        $vatPercentage =  $businessRuleMapper->getByKeyAndDate('btwhoog', $this->date);
        return $vatPercentage->getValue();
    }

    public function isDue() {
        $businessRuleMapper = new Application_Model_BusinessRuleMapper(); 
        $paymentDays = $businessRuleMapper->getByKeyAndDate('betaaltermijn', $this->date);
        $dueDate = clone $this->date;
        $dueDate->add($paymentDays->getValue(), Zend_Date::DAY);
        return $dueDate->isEarlier(new Zend_Date());
    }
}
