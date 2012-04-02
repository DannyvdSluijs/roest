<?php

class Application_Model_CustomerInsuranceMapper
{
    protected $dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable;
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->dbTable = $dbTable;
        return $this;
    }

    public function getDbTable() 
    {
        if (null === $this->dbTable) {
            $this->setDbTable('Application_Model_DbTable_CustomerInsurance');
        }
        return $this->dbTable;
    }
     
    public function save(Application_Model_CustomerInsurance $customerInsurance)
    {
        $data = $customerInsurance->toArray();
        /* Change the Zend_Date to a string that suits the DB */
        $data['fromDate'] = $data['fromDate']->toString('yyyy-MM-dd');

        $select = $this->getSelect()
            ->from('CustomerInsurance', 'count(*) as number')
            ->where('customerId = ?', $customerInsurance->getCustomerId())
            ->where('InsuranceAgencyId = ?', $customerInsurance->getInsuranceAgencyId())
            ->where('fromDate = ?', $customerInsurance->getFromDate()->toString('yyyy-MM-dd'));
        $row = $this->getDbTable()->fetchRow($select);

        if ($row['number'] == 0) {
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update(
                $data, 
                array(
                    'customerId = ?' => $customerInsurance->getCustomerId(),
                    'InsuranceAgencyId = ?' => $customerInsurance->getInsuranceAgencyId(),
                    'fromDate = ?' => $customerInsurance->getFromDate()->toString('yyyy-MM-dd')
                )
            );
        }
    }

    public function delete(Application_Model_CustomerInsurance $customerInsurance)
    {
        $this->getDbTable()->delete(
            array(
                'customerId = ?' => $customerInsurance->getCustomerId(),
                'InsuranceAgencyId = ?' => $customerInsurance->getInsuranceAgencyId(),
                'fromDate = ?' => $customerInsurance->getFromDate()->toString('yyyy-MM-dd')
            )
        );
    }

    public function getByCustomer(Application_Model_Customer $customer)
    {
        return $this->fetchAll(
            $this
                ->getSelect()
                ->where('customerId = ?', $customer->getId())
                ->order('fromDate DESC')
        );
    }

    public function getCurrentForCustomer(Application_Model_Customer $customer)
    {
        $now = new Zend_Date();
        return array_pop(
            $this->fetchAll(
                $this
                    ->getSelect()
                    ->where('customerId = ?', $customer->getId())
                    ->where('fromdate < ?', $now->toString('yyyy-MM-dd'))
                    ->order('fromDate DESC')
                    ->limit(1)
            )
        );
    }

    public function getByCustomerAndDate(Application_Model_Customer $customer, Zend_Date $date)
    {
        return array_pop(
            $this->fetchAll(
                $this
                    ->getSelect()
                    ->where('customerId = ?', $customer->getId())
                    ->where('fromdate < ?', $date->toString('yyyy-MM-dd'))
                    ->order('fromDate DESC')
                    ->limit(1)
            )
        );
    }

    public function fetchAll($select = null)
    {
        if (is_null($select)) {
            $select = $this->getSelect();
        }

        $resultSet = $this->getDbTable()->fetchAll($select);
        $entries = array();
        foreach ($resultSet as $row) {
            $entries[] = $this->mapTableRowToModel($row);
        }
        return $entries;
    }

    protected function mapTableRowToModel(Zend_Db_Table_Row $row)
    {
        $entry = new Application_Model_CustomerInsurance;
        $entry->setCustomerId($row->customerId)
              ->setInsuranceAgencyId($row->insuranceAgencyId)
              ->setPolisNumber($row->polisNumber)
              ->setFromDate(new Zend_Date($row->fromDate, 'dd-MM-yyyy'));
        return $entry;
    }

    public function getSelect()
    {
        return $this->getDbTable()->select();
    }
}
