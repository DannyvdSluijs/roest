<?php

class Application_Model_TherapyPriceMapper
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
            $this->setDbTable('Application_Model_DbTable_TherapyPrice');
        }
        return $this->dbTable;
    }
     
    public function save(Application_Model_TherapyPrice $therapyPrice)
    {
        $data = $therapyPrice->toArray();
        /* Change the Zend_date to a string that suits the DB */
        $data['fromDate'] = $data['fromDate']->toString('yyyy-MM-dd');

        $select = $this->getSelect()
            ->from('TherapyPrice', 'count(*) as number')
            ->where( 'therapyId = ?', $therapyPrice->getTherapyId())
            ->where( 'fromDate = ?', $therapyPrice->getFromDate()->toString('yyyy-MM-dd'));
        $row = $this->getDbTable()->fetchRow($select);

        if ($row['number'] == 0) {
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update(
                $data, 
                array(
                    'therapyId = ?' => $therapyPrice->getTherapyId(),
                    'fromDate = ?' => $therapyPrice->getFromDate()->toString('yyyy-MM-dd')
                )
            );
        }
    }

    public function delete(Application_Model_TherapyPrice $therapyPrice)
    {
        $this->getDbTable()->delete(
            array(
                'therapyId = ?' => $therapyPrice->getTherapyId(),
                'fromDate = ?' => $therapyPrice->getFromDate()->toString('yyyy-MM-dd')
            )
        );
    }

    public function getByTherapy(Application_Model_Therapy $therapy)
    {
        return $this->fetchAll(
            $this
                ->getSelect()
                ->where('therapyId = ?', $therapy->getId())
                ->order('fromDate DESC')
        );
    }

    public function getByTherapyAndDate(Application_Model_Therapy $therapy, Zend_Date $date)
    {
        return array_pop(
            $this->fetchAll(
                $this
                    ->getSelect()
                    ->where('therapyId = ?', $therapy->getId())
                    ->where('fromDate < ?', $date->toString('yyyy-MM-dd'))
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
        $entry = new Application_Model_TherapyPrice;
        $entry->setTherapyId($row->therapyId)
              ->setFromDate(new Zend_Date($row->fromDate, 'dd-MM-yyyy'))
              ->setPricePerHour($row->pricePerHour);
        return $entry;
    }

    public function getSelect()
    {
        return $this->getDbTable()->select();
    }
}
