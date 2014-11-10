<?php
namespace Videos\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Videos\Model\VideosEntity;

class VideosMapper 
{
	protected $tableName = 'videos';
	protected $dbAdapter;
	protected $sql;

	public function __construct(Adapter $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->tableName);
	}

	public function saveVideo(VideosEntity $ent)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($ent);
	
		$select = $this->sql->select();
		$whereArr = array('video_id' => $ent->getVideoId());
		$select->where($whereArr);
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();

		$result = false;
		if ($results->count() == 0) {
			// insert action
			$action = $this->sql->insert();
			$action->values($data);
    		$statement = $this->sql->prepareStatementForSqlObject($action);
	   	    $result = $statement->execute();
		} elseif (false && $results->count() > 0) {
		    
			// update action
			$action = $this->sql->update();
			unset($data['id']);
			$action->set($data);
			$action->where(array('video_id' => $ent->getVideoId()));
			
		}
	
		return $result;
	
	}
	
  
    public function fetchThumbArr( $offset, $limit )
    {

        $select = $this->sql->select(); 
        //$select->where(array('media_url IS NOT NULL'));
        $select->where(array('unpublish' => 0));
        $select->order('id DESC');
        $select->offset($offset);
        $select->limit($limit);
        $str = $select->getSqlString($this->dbAdapter->getPlatform());
    	$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		if ($result->count() == 0) {
		    return false;
		} 

        $hydrator = new ClassMethods(); 
        $resultSet = new HydratingResultSet($hydrator, new VideosEntity());
        $resultSet->initialize($result);
        $resultSet->buffer();
        $thumbArr = array();
        $showNextPageLink = 0;
        $key = 0;
        foreach($resultSet as $key => $obj) {
            $thumbArr[$key]['thumb'] = $obj->getThumbnail();
            $thumbArr[$key]['id'] = $obj->getId();
            $thumbArr[$key]['title'] = $obj->getTitle();
            $thumbArr[$key]['video_id'] = $obj->getVideoId();
            $dateYMon = date("Y-M", strtotime($obj->getDateCreated()));
            $thumbArr[$key]['dateYMon'] = $dateYMon;
        }
        if ($limit == $resultSet->count()) {
            $showNextPageLink = 1;    
        } 
        $key++;
        $thumbArr[$key]['nav']['showNextPageLink'] = $showNextPageLink;
        $thumbArr[$key]['nav']['page'] = $offset/$limit;
        $thumbArr[$key]['nav']['limit'] = $limit;
        return $thumbArr;
        
    }
	
	
}