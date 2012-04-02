<?php

class Application_Model_TherapistMapper
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
            $this->setDbTable('Application_Model_DbTable_Therapist');
        }
        return $this->dbTable;
    }
     
    public function save(Application_Model_Therapist $therapist)
    {
        $data = array(
            'name' => $therapist->getName()
        );
        $data = $therapist->toArray();

        if (null === ($id = $therapist->getId())) {
            unset($data['id']);
            $data['active'] = true;
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function delete(Application_Model_Therapist $therapist)
    {
        if (null !== ($id = $therapist->getId())) {
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
        $entry = new Application_Model_Therapist;
        $entry->setId($row->Id)
              ->setName($row->Name)
              ->setActive($row->active);

        return $entry;
    }

    public function getSelect()
    {
        return $this->getDbTable()->select();
    }
}
