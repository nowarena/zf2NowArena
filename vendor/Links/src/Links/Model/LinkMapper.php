<?php
namespace Links\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\ArraySerializable;

class LinkMapper 
{

	protected $tableName = 'links';
	protected $dbAdapter;
	protected $sql;

	public function __construct(Adapter $dbAdapter)
	{

		$this->dbAdapter = $dbAdapter;
		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->tableName);
		
	}
	
    public function getLinks()
    {
        
        $select = $this->sql->select();
        $select->order(array('sort_order ASC'));
        
        //echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        /*
        $rows = new ResultSet();
        $arr = $rows->initialize($results)->toArray();
        printR($arr);
        return $arr;
        */

        $entityPrototype = new LinkEntity();
        $hydrator = new ClassMethods();
        $resultset = new HydratingResultSet($hydrator, $entityPrototype);
        $resultset->initialize($results);
        $resultset->buffer();
        return $resultset;
        
    }
    
    public function getLinkArr()
    {
    
    	$select = $this->sql->select();
    	$select->order(array('sort_order ASC'));
    
    	//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
    	$statement = $this->sql->prepareStatementForSqlObject($select);
    	$results = $statement->execute();
    	
    	$rows = new ResultSet();
    	$arr = $rows->initialize($results)->toArray();
    	return $arr;
    	
    }
    
    public function deleteLink($id)
    {
    	$delete = $this->sql->delete();
    	$delete->where(array('id' => $id));
    	$statement = $this->sql->prepareStatementForSqlObject($delete);
    	return $statement->execute();
    }
    
    public function saveLinks(Links $linkModel) 
    {

        $arr = $linkModel->getLinks();
        foreach($arr as $ent) {
            $this->save($ent);
        }
        
    }
    
    public function save(LinkEntity $ent)
    {

    	$hydrator = new ClassMethods();
    	$data = $hydrator->extract($ent);

    	if ($ent->getId()) {
    		// update action
    		// sort order is managed via another form
    	    unset($data['sort_order']);
    		$action = $this->sql->update();
    		$action->set($data);
    		$action->where(array('id' => $ent->getId()));
    	} else {
    		// insert action
    		$action = $this->sql->insert();
    		unset($data['id']);
    		$data['sort_order'] = 0;
    		$data['disabled'] = 0;
    		$action->values($data);
    	}
    	$statement = $this->sql->prepareStatementForSqlObject($action);
    	//echo "<br><br>".$this->sql->update()->getSqlString($this->dbAdapter->getPlatform());
    	//echo "<br><br>".$this->sql->insert()->getSqlString($this->dbAdapter->getPlatform());
    	$statement->execute();
    
    }
    
    public function updateLinkOrder($linkArr) 
    {
        
        foreach($linkArr as $position => $linkId) {
        	$position = (int)$position;
        	$linkId = (int)$linkId;
        	$update = $this->sql->update();
        	$update->set(array('sort_order' => $position), $update::VALUES_SET)->where(array('id' => $linkId));
        	$statement = $this->sql->prepareStatementForSqlObject($update);
        	$statement->execute();
        }
    
    }
    
	
}