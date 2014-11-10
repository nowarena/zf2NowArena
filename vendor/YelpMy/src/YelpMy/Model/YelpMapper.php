<?php
namespace YelpMy\Model;

use Zend\Db\Adapter\Adapter;
use YelpMy\Model\YelpEntity;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Utility\Model\Utility;
use Application\Model\BaseModel; 
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
// test

class YelpMapper extends BaseModel implements ServiceLocatorAwareInterface
{
	protected $tableName = 'yelp';
	protected $dbAdapter;
	protected $sql;
	protected $serviceLocator;

	public function __construct(Adapter $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->tableName);
	}
	
	/*
	 * 	public function saveYelp(YelpEntity $ent)
	{
	    
        $socMedEnt = new SocialMediaEntity();
        $socMedEnt->setUsername($ent->getBizname())
           ->setSocialId($ent->getId())
           ->setTitle('')
           ->setHeaderText('')
           ->setText($ent->getText())
           ->setMediaUrl($ent->getImage())
           ->setMediaHeight($ent->getMediaHeight())
           ->setMediaWidth($ent->getMediaWidth())
           ->setLink($ent->getLink())
           ->setSource('yelp')
           ->setDateCreated(date("Y-m-d H:i:s", $ent->getCreatedTime()));
//point to correct table
        $this->insertSocialMedia($socMedEnt);
        
        return $socMedEnt;
        
	}
	*/
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}
	
	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	public function fetchAll()
	{
		$select = $this->sql->select();
		$select->order(array('bizname DESC'));

		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();

		$entityPrototype = new YelpEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
		
		return $resultset;
		
	}
	
	public function getYelpWithBlvdId($blvdId, $limit)
	{
    	$select = $this->sql->select();
    	$select->join('blvd', 'blvd.yelp = yelp.bizname')
    	   ->where(array('blvd.id' => $blvdId))
    	   ->order(array('created_time DESC'))
   		   ->limit($limit);
    	$statement = $this->sql->prepareStatementForSqlObject($select);
    	$result = $statement->execute()->current();
    	if (!$result) {
    		return false;
    	}
    	
    	$hydrator = new ClassMethods();
    	$yelpEnt = new YelpEntity();
    	$hydrator->hydrate($result, $yelpEnt);
    	return $yelpEnt;
   	    //return $this->hydrateResult(new YelpEntity, $select);	
	}

	public function getYelpByBizname($bizname, $limit = 3)
	{
		$select = $this->sql->select();
		$select->where(array('bizname' => $bizname));
		$select->order(array('created_time DESC'));
		if ($limit != 'all' && is_numeric($limit)) {
		  $select->limit($limit);
		}
	
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		if ($results->count() == 0) {
		    return false;
		}
		
		//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
		$entityPrototype = new YelpEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
	
		return $resultset;
	
	}

	public function saveYelp(YelpEntity $yelp)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($yelp);
		$select = $this->sql->select();
		$whereArr = array('bizname' => $yelp->getBizname(), 'id' => $yelp->getId(), 'created_time' => $yelp->getCreatedTime());
		$select->where($whereArr);
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();

		if ($results->count() > 0) {
		    //return false; 
			// update action
			$action = $this->sql->update();
			$action->set($data);
			$action->where(array('id' => $yelp->getId(), 'bizname' => $yelp->getBizname(), 'created_time' => $yelp->getCreatedTime()));
		} else {
			// insert action
			$action = $this->sql->insert();
			$action->values($data);
		}
		$statement = $this->sql->prepareStatementForSqlObject($action);
		$result = $statement->execute();
		
		$blvdId = $this->getServiceLocator()->get("Blvd\Model\BlvdMapper")->getBlvdIdWithSocialUsername($yelp->getBizname(), 'yelp');
		if ($blvdId) {
			$socEnt = $this->getServiceLocator()->get("Blvd\Model\SocialMediaMapper")->saveYelp($yelp, $blvdId);
			$this->getServiceLocator()->get("Blvd\Model\BlvdMapper")->updateBlvdWithSocialMediaDatetime($socEnt);
		}
	
		return $result;
	
	}	
	
}