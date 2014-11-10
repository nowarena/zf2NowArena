<?php
namespace FacebookMy\Model;

use Zend\Db\Adapter\Adapter;
use FacebookMy\Model\FacebookEntity;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Utility\Model\Utility;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FacebookMapper implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
	protected $tableName = 'facebookpages';
	protected $dbAdapter;
	protected $sql;

	public function __construct(Adapter $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->tableName);
	}


	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}
	
	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}
	
	public function fetchAll()
	{
		$select = $this->sql->select();
		$select->order(array('username DESC'));

		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();

		$entityPrototype = new FacebookEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
		
		return $resultset;
		
	}

	public function getFacebookByUsername($username, $limit = 3)
	{
		$select = $this->sql->select();
		$select->where(array('username' => $username));
		$select->order(array('created_time DESC'));
		if ($limit != 'all') {
		  $select->limit($limit);
		}
	
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		if ($results->count() == 0) {
		    return false;
		}
		
		//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
		$entityPrototype = new FacebookEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
	
		return $resultset;
	
	}

	public function saveFacebookPagePost(FacebookEntity $facebook)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($facebook);
		$select = $this->sql->select();
		$whereArr = array('username' => $facebook->getUsername(), 'post_id' => $facebook->getPostId());
		$select->where($whereArr);
		$statement = $this->sql->prepareStatementForSqlObject($select);
        //echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
		$results = $statement->execute();
printR($data);	
		if ($results->count() == 0) {
			// insert action
			$action = $this->sql->insert();
			$action->values($data);
            $statement = $this->sql->prepareStatementForSqlObject($action);
            $result = $statement->execute();
		}

		$blvdId = $this->getServiceLocator()->get("Blvd\Model\BlvdMapper")->getBlvdIdWithSocialUsername($facebook->getUsername(), 'facebook');
		if ($blvdId) {
			$this->getServiceLocator()->get("Blvd\Model\SocialMediaMapper")->saveFacebook($facebook, $blvdId);
		}
	
	}	
	
}