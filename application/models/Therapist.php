<?php

class Application_Model_Therapist
    extends Application_Model_AbstractModel
{
    protected $name;
    protected $active;

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
}
