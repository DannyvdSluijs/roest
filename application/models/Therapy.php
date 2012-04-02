<?php

class Application_Model_Therapy
    extends Application_Model_AbstractModel
{
    /* properties */
    protected $name = '';
    protected $active = true;

    /* persistent relations */
    protected $therapyPrices;

    public function toArray()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
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

    public function setActive($active)
    {
        $this->active = (bool) $active;
        return $this;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function getTherapyPrices()
    {
        if (empty($this->therapyPrices)) {
            $therapyPriceMapper = new Application_Model_TherapyPriceMapper();
            $this->therapyPrices = $therapyPriceMapper->getByTherapy($this);
        }

        return $this->therapyPrices;
    }

    public function getTherapyPriceByDate(Zend_Date $date) {
        $therapyPriceMapper = new Application_Model_TherapyPriceMapper();
        return $therapyPriceMapper->getByTherapyAndDate($this, $date);
    }

}
