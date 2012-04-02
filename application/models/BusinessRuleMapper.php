<?php

class Application_Model_BusinessRuleMapper
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
            $this->setDbTable('Application_Model_DbTable_BusinessRule');
        }
        return $this->dbTable;
    }
     
    public function save(Application_Model_BusinessRule $businessRule)
    {
        $data = $businessRule->toArray();
        /* Change the Zend_date to a string that suits the DB */
        $data['fromDate'] = $data['fromDate']->toString('yyyy-MM-dd');
        if (null === ($id = $businessRule->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function delete(Application_Model_BusinessRule $businessRule)
    {
        $this->getDbTable()->delete(
            array(
                '`key` = ?' => $businessRule->getKey(),
                'fromDate = ?' => $businessRule->getFromDate()->toString('yyyy-MM-dd')
            )
        );
    }

    public function getById($id)
    {
        $resultSet = $this->getDbTable()->find($id);
        if (0 == count($resultSet)) {
            return;
        }
        $row = $resultSet->current();

        return $this->mapTableRowToModel($row);
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
        $entry = new Application_Model_BusinessRule;
        $entry->setKey($row->key)
              ->setValue($row->value)
              ->setFromDate(new Zend_Date($row->fromDate, 'yyyy-MM-dd'));

        return $entry;
    }

    public function getSelect()
    {
        return $this->getDbTable()->select();
    }

    public function getDistinctKeyValues()
    {
        $select = $this->getSelect();
    }

    public function getByKeyAndDate($key, Zend_Date $date)
    {
        return array_pop(
            $this->fetchAll(
                $this
                    ->getSelect()
                    ->where('`key` = ?', $key)
                    ->where('fromDate < ?', $date->toString('yyyy-MM-dd'))
                    ->order('fromDate DESC')
                    ->limit(1)
            )
        );
    }
}
