<?php

class Application_Form_BusinessRule
    extends Zend_Form 
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('autocomplete', 'off');
        /* Key */
        $element = new Zend_Form_Element_Select('key');
        $element->setLabel('Key');
        $element->setRequired(true);
        $businessRuleMapper = new Application_Model_BusinessRuleMapper();
        $businessRules = $businessRuleMapper->fetchAll();

        foreach ($businessRules as $businessRule) {
            $element->addMultiOption($businessRule->getKey(), $businessRule->getKey());
        }
        $this->addElement($element);

        /* value */
        $this->addElement(
            'text', 
            'value', 
            array(
                'label' => 'Value',
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

        /* csfr token */
        $this->addElement(
            'hash',
            'csfr',
            array(
                'ignore' => true,
            )
        );
    }
}
