<?php

class Application_Form_Appointment
    extends ZendX_JQuery_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('autocomplete', 'off');

        /* Therapy */
        $element = new Zend_Form_Element_Select('therapy');
        $element->setLabel('Therapy');
        $element->setRequired(true);
        $therapyMapper = new Application_Model_TherapyMapper();
        $therapies = $therapyMapper->fetchAll();

        foreach ($therapies as $therapy) {
            $element->addMultiOption($therapy->getId(), $therapy->getName());
        }
        $this->addElement($element);

        /* Therapist */
        $element = new ZendX_JQuery_Form_Element_AutoComplete('therapist');
        $element->setLabel('Therapist');
        $element->setRequired(true);
        $therapistMapper = new Application_Model_TherapistMapper();
        $therapists = $therapistMapper->fetchAll();

        $data = array();
        foreach ($therapists as $therapist) {
            $data[] = $therapist->getName();
        }
        $element->setJQueryParams(array('data' => $data));
        $this->addElement($element);

        /* customer */
        $element = new ZendX_JQuery_Form_Element_AutoComplete('customer');
        $element->setLabel('Customer');
        $element->setRequired(true);
        $customerMapper = new Application_Model_CustomerMapper();
        $customers = $customerMapper->fetchAll();

        $data = array();
        foreach ($customers as $customer) {
            $data[] = $customer->getName();
        }
        $element->setJQueryParams(array('data' => $data));
        $this->addElement($element);

        /* date */
        $element = new ZendX_JQuery_Form_Element_DatePicker('date');
        $element->setLabel('Date');
        $element->setRequired(true);
        $element->setJQueryParam('dateFormat', 'dd-mm-yy');
        $this->addElement($element);

        /* duration */
        $element = new Zend_Form_Element_Select('duration');
        $element->setLabel('Duration');
        $element->setRequired(true);

        $element->addMultiOptions(array(30 => 'Half an hour', 60 => 'One hour', 90 => 'An hour and a half'));
        $this->addElement($element);

        /* submit */
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
