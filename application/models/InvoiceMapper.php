<?php

class Application_Model_InvoiceMapper
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
            $this->setDbTable('Application_Model_DbTable_Invoice');
        }
        return $this->dbTable;
    }
     
    public function save(Application_Model_Invoice $invoice)
    {
        $data = $invoice->toArray();
        /* Change the Zend_date to a string that suits the DB */
        $data['date'] = $data['date']->toString('yyyy-MM-dd');
        if (null === ($id = $invoice->getId())) {
            unset($data['id']);
            $invoice->setId($this->getDbTable()->insert($data));
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function delete(Application_Model_Invoice $invoice)
    {
        if (null !== ($id = $invoice->getId())) {
            $this->getDbTable()->delete(array('id = ?' => $id));
        }
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
        $entry = new Application_Model_Invoice;
        $entry->setId($row->id)
              ->setDate(new Zend_Date($row->date, 'yyyy-MM-dd'))
              ->setPaid($row->paid)
              ->setReminder($row->reminder);

        return $entry;
    }

    public function getSelect()
    {
        return $this->getDbTable()->select();
    }
}
