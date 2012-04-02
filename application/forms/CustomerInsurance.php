<?php

class Application_Form_CustomerInsurance
    extends ZendX_JQuery_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('autocomplete', 'off');

        /* Insurance agency */
        $element = new Zend_Form_Element_Select('insuranceAgency');
        $element->setLabel('Insurance agency');
        $element->setRequired(true);
        $insuranceAgencyMapper = new Application_Model_InsuranceAgencyMapper();
        $select = $insuranceAgencyMapper->getSelect();
        $select->where('active = true');
        $insuranceAgencies = $insuranceAgencyMapper->fetchAll($select);

        foreach ($insuranceAgencies as $insuranceAgency) {
            $element->addMultiOption($insuranceAgency->getId(), $insuranceAgency->getName());
        }
        $this->addElement($element);


        /* polis number */
        $this->addElement(
            'text', 
            'polisNumber', 
            array(
                'label' => 'Polis number',
                'required' => true,
                'filters' => array('StringTrim'),
                'validators' => array(),
            )
        );

        /* date */
        $element = new ZendX_JQuery_Form_Element_DatePicker('fromDate');
        $element->setLabel('Date');
        $element->setRequired(true);
        $element->setJQueryParam('dateFormat', 'dd-mm-yy');
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
