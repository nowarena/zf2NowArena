<?php
namespace Blvd\Model;

use Zend\Db\Adapter\Adapter;
use Blvd\Model\BlvdCategoryEntity;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Utility\Model\Utility;


class BlvdJoinCategoryMapper
{
	protected $tableName = 'blvd_categories';
	protected $dbAdapter;
	protected $sql;

	public function __construct(Adapter $dbAdapter)
	{
	    $this->dbAdapter = $dbAdapter;
	    $this->sql = new Sql($dbAdapter);
	    $this->sql->setTable($this->tableName);	
	}

    public function getNonPrimaryCategory($blvdId)
    {
        
    	$select = $this->sql->select();
   		$select->join("categories", "category_id = id", array("secondary_category_id" => "id", "secondary_category" => "category"), $select::JOIN_INNER);
   		$select->where(array("primary" => 0, "blvd_id" => $blvdId));
   		$select->order("top DESC");
    	$statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute(); 
		$rows = new ResultSet();
		$arr = $rows->initialize($result)->toArray();
		if (is_array($arr) && count($arr) > 0 ){
            return array_pop($arr);
		} else {
            return false;    
		}
        
    }
    
    public function getBottomCatForBlvd($blvdId) 
    {
        
    	$select = $this->sql->select();
   		$select->join("categories", "category_id = id", array("secondary_category_id" => "id", "secondary_category" => "category"), $select::JOIN_INNER);
   		$select->where(array("primary" => 0, "bottom" => 1, "blvd_id" => $blvdId));
    	$statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute(); 
		$rows = new ResultSet();
		$arr = $rows->initialize($result)->toArray();
		if (is_array($arr) && count($arr) > 0 ){
            return array_pop($arr);
		} else {
            return false;    
		}
                
    }
    
	public function fetchAll($blvdId)
	{
		 
		$select = $this->sql->select();
		$select->where(array("blvd_id" => $blvdId));
	
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
	
		$entityPrototype = new BlvdCategoryEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
		$resultset->buffer();
		return $resultset;
	
	}

	/**
	 * Return an array of category_id=>name 
	 * @param string $blvdId
	 * @param number $mainOnly
	 * @return array 
	 */
	public function fetchBlvdCategories($blvdId = null, $mainOnly = 1)
	{
		$select = $this->sql->select();
		$select->where(array("blvd_id" => $blvdId));
	
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();//->current();
		$rows = new ResultSet();
		$arr = $rows->initialize($result)->toArray();
		$catArr = array();
		foreach($arr as $key => $row) {
			$catArr[$row['category_id']] = $row['primary'];
		}
		
		return $catArr;
	
	}
	
	public function deleteBlvdJoinCategory($id)
	{
		$delete = $this->sql->delete();
		$delete->where(array("blvd_id"=>$id));
		$statement = $this->sql->prepareStatementForSqlObject($delete);
		$result = $statement->execute();
		return $result;
	}
		
    public function saveRows($blvdCatArr, $blvdId) 
    {
        // delete existing relations
        $this->deleteBlvdJoinCategory($blvdId);
        $r = true;
        foreach($blvdCatArr as $key => $ent) {
            $r = $r & $this->saveRow($ent);
        }
        return $r;
    }	

    /*
     * This must be called by saveRows() so as to ensure deletions
     */
	private function saveRow(BlvdJoinCategoryEntity $blvdCat)
	{
	    
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($blvdCat);
		// insert action
		$action = $this->sql->insert();
		$action->values($data);
		$statement = $this->sql->prepareStatementForSqlObject($action);
		$result = $statement->execute();
	
		if (!$blvdCat->getId()) {
			$blvdCat->setId($result->getGeneratedValue());
		}
		return $result;
	
	}
	
	
}