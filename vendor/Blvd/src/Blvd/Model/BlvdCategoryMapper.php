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


class BlvdCategoryMapper
{
	protected $tableName = 'categories';
	protected $dbAdapter;
	protected $sql;

	public function __construct(Adapter $dbAdapter)
	{
	    $this->dbAdapter = $dbAdapter;
	    $this->sql = new Sql($dbAdapter);
	    $this->sql->setTable($this->tableName);	
	}
	
	public function updateCategoryOrder($catArr, $sort_order_column)
	{
		foreach($catArr as $position => $catId) {
			$position = (int)$position;
			$catId = (int)$catId;
    		$update = $this->sql->update();
			$update->set(array($sort_order_column => $position), $update::VALUES_SET)->where(array('id' => $catId));
			$statement = $this->sql->prepareStatementForSqlObject($update);
			$statement->execute();
		}
	
	}
	
	public function updateCategoryTopOrder($catArr)
	{
	    $this->updateCategoryOrder($catArr, 'sort_order_top');
	}
	
	
	public function updateCategoryBottomOrder($catArr)
	{
	    $this->updateCategoryOrder($catArr, 'sort_order_bottom');
	}

	public function fetchAll($topOnly = 1, $bottomOnly = 0, $disabledOnly = 0, $idNameOnly = false)
	{
	    
        $select = $this->sql->select();
        if ($topOnly) {
            $select->where(array('top' => 1, 'disabled' => 0));
            $select->order(array('sort_order_top ASC'));
        } elseif ($bottomOnly) {
            $select->where(array('bottom' => 1, 'disabled' => 0));
            $select->order(array('sort_order_bottom ASC'));
        } elseif ($disabledOnly) {
            $select->where(array('disabled' => 1));
        }
        
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
	 * Return a category_id=>category name array
	 * @return array
	 */
	public function fetchAllArr()
	{
	    
        $select = $this->sql->select();
        $select->order(array('category ASC'));
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result = $statement->execute();
        $rows = new ResultSet();
        $arr = $rows->initialize($result)->toArray();
        $catArr = array();
        foreach($arr as $key => $row) {
            $catArr[$row['id']] = $row['category'];
        }
        return $catArr; 
        
	}
	
	public function fetchArr($top = 1, $bottom = 0, $disabled = 0, $idNameOnly = false)
	{
	    
        $select = $this->sql->select();
        if ($top) {
            $select->where(array('top' => 1, 'disabled' =>0));
            $select->order(array('sort_order_top ASC'));
        }
        if ($bottom) {
            $select->where(array('bottom' => 1, 'disabled' =>0));
            $select->order(array('sort_order_bottom ASC'));
        }
        if ($disabled) {
            $select->where(array('disabled' => 1));
        }
        if (false) {
            $whereArr = array('bottom' => 1, 'top' => 1);
            $select->where($whereArr, \Zend\Db\Sql\Predicate\PredicateSet::OP_OR);
            $select->where(array('disabled' => 0), \Zend\Db\Sql\Predicate\PredicateSet::OP_AND);
        }
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        
        //echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
        
        $result = $statement->execute();
        $rows = new ResultSet();
        $arr = $rows->initialize($result)->toArray();
        $catArr = array();
        foreach($arr as $key => $row) {
            if ($idNameOnly) {
                $catArr[$row['id']] = $row['category'];
            } else {
                $catArr[$row['id']] = array("category" => $row['category'], "top" => $row['top'], "bottom" => $row['bottom']);
            }
        }
        return $catArr; 
	}
	
	public function deleteCategory($id)
	{
        $delete = $this->sql->delete(); 
        $delete->where(array("id"=>$id));
        $statement = $this->sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();
        
        $delete = $this->sql->delete();
        $delete->from('blvd_categories'); 
        $delete->where(array("category_id"=>$id));
        $statement = $this->sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();
        return $result;
	}

	public function fetchCategory($id = null)
	{
	  $select = $this->sql->select();
	    if (!is_null($id)) {
	        $select->where(array("id" => $id));
	    }
		$select->order(array('category ASC'));
	
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute()->current();
		$hydrator = new ClassMethods();
		$category = new BlvdCategoryEntity();
		$hydrator->hydrate($result, $category);
		
		return $category;
		
	}

	public function save(BlvdCategoryEntity $blvdCat)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($blvdCat);
		
		$select = $this->sql->select();
		$select->where(array('category' => $blvdCat->getCategory()));
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
	
		if ($blvdCat->getId()) {
			// update action
			$action = $this->sql->update();
			$action->set($data);
			$action->where(array('id' => $blvdCat->getId()));
		} elseif ($result->count() == 0) {
			// insert action
			$action = $this->sql->insert();
			unset($data['id']);
			//$data['disabled'] = 1;
			$action->values($data);
		} else {
		    return false;
		}
		$statement = $this->sql->prepareStatementForSqlObject($action);
		$result = $statement->execute();
	
		if (!$blvdCat->getId()) {
			$blvdCat->setId($result->getGeneratedValue());
		}
		return $result;
	
	}
	
	
}	