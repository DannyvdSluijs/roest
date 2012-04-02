<?php

class Application_Model_TherapyPrice
    extends Application_Model_AbstractModel
{
    protected $therapyId;
    protected $pricePerHour;
    protected $fromDate;

    public function toArray()
    {
        return array(
            'therapyId' => $this->therapyId,
            'pricePerHour' => $this->pricePerHour,
            'fromDate' => $this->fromDate
        );
    }

    public function __toString()
    {
        return 
            'Therapy ' .$this->getTherapy()->__toString() . ' for &euro;' . 
            number_format($this->pricePerHour, 2) . ' since ' .
            $this->fromDate->toString('dd-MM-yyyy');
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


    public function setPricePerHour($pricePerHour)
    {
        $pricePerHour = str_replace(',', '.', $pricePerHour);
        $this->pricePerHour = (float) $pricePerHour;
        return $this;
    }

    public function getPricePerHour()
    {
        return number_format($this->pricePerHour, 2);
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
