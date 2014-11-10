<?php
namespace TumblrMy\Model;

use Zend\Db\Adapter\Adapter;
use TumblrMy\Model\TumblrEntity;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;

class TumblrMapper 
{
	protected $tableName = 'tumblr';
	protected $dbAdapter;
	protected $sql;

	public function __construct(Adapter $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->tableName);
	}

	public function fetchAll($limit = 3)
	{
		$select = $this->sql->select();
		$select->order("date DESC");
		$select->limit($limit);
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();

		$entityPrototype = new TumblrEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);

		return $resultset;

	}

	public function saveTumblr(TumblrEntity $ent)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($ent);
	
		$select = $this->sql->select();
		$whereArr = array('id' => $ent->getId());
		$select->where($whereArr);
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
	
		if ($results->count() > 0) {
		    
			// update action
			$action = $this->sql->update();
			$action->set($data);
			$action->where(array('id' => $ent->getId()));
		} else {
			// insert action
			$action = $this->sql->insert();
			$action->values($data);
		}
		$statement = $this->sql->prepareStatementForSqlObject($action);
		$result = $statement->execute();
	
		return $result;
	
	}
	
	
}