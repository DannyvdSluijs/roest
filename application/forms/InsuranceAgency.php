<?php

class Application_Form_InsuranceAgency
    extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('autocomplete', 'off');

        $this->addElement(
            'text', 
            'name', 
            array(
                'label' => 'Name',
                'required' => true,
                'filters' => array('StringTrim'),
                'validators' => array(),
            )
        );

        $this->addElement(
            'text', 
            'address', 
            array(
                'label' => 'Address',
                'required' => true,
                'filters' => array('StringTrim'),
                'validators' => array(),
            )
        );

        $this->addElement(
            'text', 
            'postalCode', 
            array(
                'label' => 'Postal code',
                'required' => true,
                'filters' => array('StringTrim'),
                'validators' => array(),
            )
        );

        $this->addElement(
            'text', 
            'city', 
            array(
                'label' => 'City',
                'required' => true,
                'filters' => array('StringTrim'),
                'validators' => array(),
            )
        );

        $this->addElement(
            'checkbox',
            'active',
            array(
                'label' => 'Active',
                'required' => false,
                'filters' => array(),
                'validators' => array()
            )
        );

        $this->addElement(
            'submit',
            'submit',
            array(
                'ignore' => true,
                'label' => 'Add'
            )
        );


        $this->addElement(
            'hash',
            'csfr',
            array(
                'ignore' => true,
            )
        );
    }
}
