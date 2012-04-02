<?php

class Application_Model_InsuranceAgency
    extends Application_Model_AbstractModel
{
    protected $name;
    protected $address;
    protected $postalCode;
    protected $city;
    protected $active = true;

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'postalCode' => $this->postalCode,
            'city' => $this->city,
            'active' => $this->active
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

    public function setActive($active)
    {
        $this->active = (bool) $active;
        return $this;
    }

    public function getActive()
    {
        return $this->active;
    }
}
