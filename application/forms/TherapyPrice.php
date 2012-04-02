<?php

class Application_Form_TherapyPrice
    extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('autocomplete', 'off');

        $this->addElement(
            'text',
            'pricePerHour',
            array(
                'label' => 'Price per hour',
                'required' => true,
                'filters' => array(),
                'validators' => array()
            )
        );

        /* date */
        $element = new ZendX_JQuery_Form_Element_DatePicker('fromDate');
        $element->setLabel('From date');
        $element->setRequired(true);
        $element->setJQueryParam('dateFormat', 'dd-mm-yy');
        $this->addElement($element);

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
