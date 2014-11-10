<?php
namespace Base\Model;

abstract class BaseMapper
{

    protected function hydrateResult($entityPrototype, $select, $displayQuery = false)
    {
        $statement = $this->sql->prepareStatementForSqlObject($select);
        if ($displayQuery) {
            echo "<br><br>" . $select->getSqlString($this->dbAdapter->getPlatform());
        }
        $results = $statement->execute();
        // 'ClassMethods' et al was not being found, so I used absolut path
        $hydrator = new \Zend\Stdlib\Hydrator\ClassMethods();
        $resultset = new \Zend\Db\ResultSet\HydratingResultSet($hydrator, $entityPrototype);
        $resultset->initialize($results);
        
        return $resultset;
    }

    protected function arrayResult($select)
    {
        $statement = $this->sql->prepareStatementForSqlObject($select);
        // echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
        $results = $statement->execute();
        // $results->current();
        if ($results->count() > 0) {
            $rows = new \Zend\Db\ResultSet\ResultSet();
            return $rows->initialize($results)->toArray();
        }
        return false;
    }
}