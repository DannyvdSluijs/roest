<?php

class Application_Model_BusinessRule
    extends Application_Model_AbstractModel
{
    protected $key;
    protected $value;
    protected $fromDate;

    public function toArray()
    {
        return array(
            'key' => $this->key,
            'value' => $this->value,
            'fromDate' => $this->fromDate
        );
    }

    public function __toString()
    {
        return $this->key . ': ' . $this->value . ' since ' . $this->fromDate->toString('dd-MM-yyyy');
    }

    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;
        return $this;
    }

    public function getFromDate()
    {
        return $this->fromDate;
    }

}
