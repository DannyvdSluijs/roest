<?php

class Application_Model_Customer
    extends Application_Model_AbstractModel
{
    protected $name;
    protected $address;
    protected $postalCode;
    protected $city;
    protected $telephone;

    protected $customerInsurances;
    protected $currentCustomerInsurance;

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'postalCode' => $this->postalCode,
            'city' => $this->city,
            'telephone' => $this->telephone,
        );
    }

    public function __toString()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function hasCustomerInsurance()
    {
        return count($this->getCustomerInsurances()) > 0;
    }

    public function getCustomerInsurances()
    {
        if (empty($this->customerInsurances)) {
            $customerInsuranceMapper = new Application_Model_CustomerInsuranceMapper();
            $this->customerInsurances = $customerInsuranceMapper->getByCustomer($this);
        }

        return $this->customerInsurances;
    }

    public function getCurrentCustomerInsurance()
    {
        if (empty($this->currentCustomerInsurance)) {
            $customerInsuranceMapper = new Application_Model_CustomerInsuranceMapper();
            $this->currentCustomerInsurance = $customerInsuranceMapper->getCurrentForCustomer($this);
        }

        return $this->currentCustomerInsurance;
    }

    public function getCustomerInsuranceByDate(Zend_Date $date)
    {
        $customerInsuranceMapper = new Application_Model_CustomerInsuranceMapper();
        return $customerInsuranceMapper->getByCustomerAndDate($this, $date);
    }

}
