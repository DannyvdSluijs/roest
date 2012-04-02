<?php

class Application_Model_CustomerInsurance
    extends Application_Model_AbstractModel
{
    /* properties */
    protected $customerId;
    protected $insuranceAgencyId;
    protected $polisNumber;
    protected $fromDate;

    /* persistent storage of related models */
    protected $customer;
    protected $insuranceAgency;


    public function toArray()
    {
        return array(
            'customerId' => $this->customerId,
            'insuranceAgencyId' => $this->insuranceAgencyId,
            'polisNumber' => $this->polisNumber,
            'fromDate' => $this->fromDate
        );
    }

    public function __toString()
    {
        return 
            'Customer ' .$this->getCustomer()->__toString() . ' has an insurance at ' .
            $this->getInsuranceAgency() . ' since ' .
            $this->fromDate->toString('dd-MM-yyyy');
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

    public function setInsuranceAgencyId($id)
    {
        $this->insuranceAgencyId = (int) $id;
        $this->insuranceAgency = null;
        return $this;
    }


    public function getInsuranceAgencyId()
    {
        return $this->insuranceAgencyId;
    }

    public function setInsuranceAgency(Application_Model_InsuranceAgency $insuranceAgency)
    {
        $this->insuranceAgency = $insuranceAgency;
        $this->insuranceAgencyId = (int) $insuranceAgency->getId();
        return $this;
    }

    public function getInsuranceAgency()
    {
        if (is_null($this->insuranceAgency)) {
            $insuranceAgencyMapper = new Application_Model_InsuranceAgencyMapper();
            $this->insuranceAgency = $insuranceAgencyMapper->getById($this->insuranceAgencyId);
        }
        return $this->insuranceAgency;
    }

    public function setPolisNumber($polisNumber)
    {
        $this->polisNumber = $polisNumber;
        return $this;
    }

    public function getPolisNumber()
    {
        return $this->polisNumber;
    }

    public function setFromDate(Zend_Date $fromDate)
    {
        $this->fromDate = $fromDate;
        return $this;
    }

    public function getFromDate()
    {
        return $this->fromDate;
    }

}
