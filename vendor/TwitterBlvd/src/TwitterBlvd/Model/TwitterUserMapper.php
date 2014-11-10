<?php
namespace TwitterBlvd\Model;

use Zend\Db\Adapter\Adapter;
use TwitterBlvd\Model\TwitterUserEntity;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Blvd\Model\BlvdMapper;
use Blvd\Model\BlvdEntity;
use Application\Model\BaseModel;

class TwitterUserMapper extends BaseModel
{
	protected $tableName = 'twitter_users';
	protected $dbAdapter;
	protected $sql;

	public function __construct(Adapter $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->tableName);
	}

	public function fetchAll()
	{
		$select = $this->sql->select();
		$select->order(array('screen_name ASC'));
		
		return $this->hydrateResult(new TwitterUserEntity(), $select);

		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();

		$entityPrototype = new TwitterUserEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
		
		return $resultset;
		
	}

	public function getFoodtruckScreenName()
	{
	    
	   $select = $this->sql->select()
	       ->join('tweets', '(twitter_users.screen_name = tweets.screen_name)', 'screen_name', SELECT::JOIN_LEFT)
	       ->where(array('foodtruck' => 1))
	       ->group('tweets.screen_name')
	       ->order('tweets.id DESC');
	   $arr = $this->arrayResult($select);	
	   $screenNameArr = array();
	   foreach($arr as $key => $row) {
	       $name = ($row['screen_name'] != '') ? $row['screen_name'] : $row['name'];
	       $screenNameArr[] = strtolower($name);
	   }
	   return $screenNameArr;
	    
	}
	public function fetchTwitterUser($screenName)
	{
        $whereArr = array('screen_name' => $screenName);
		$select = $this->sql->select();
		$select->where($whereArr);

		$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute()->current();
        //echo "<br><br>\n".$select->getSqlString($this->dbAdapter->getPlatform())."\n";
		
		if (!$result) {
		    return false;
		}

		$hydrator = new ClassMethods();
		$twitterUser = new TwitterUserEntity();
		$hydrator->hydrate($result, $twitterUser);
		return $twitterUser;
		
	// this triggers an exception 'getArrayCopy() needed'	
	// Zend\Stdlib\Hydrator\ArraySerializable::extract expects the provided object to implement getArrayCopy()
		$entityPrototype = new TwitterUserEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
		$resultset->buffer();
		return $resultset;

	}
	
	public function saveTwitterUser(TwitterUserEntity $twitterUser, $setFoodtruck = false)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($twitterUser);
		$select = $this->sql->select();
		//$whereArr = array('screen_name' => $twitterUser->getScreenName());
		$whereArr = array('twitter_id' => $twitterUser->getTwitterId());
		$select->where($whereArr);
		$statement = $this->sql->prepareStatementForSqlObject($select);
		echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
		$results = $statement->execute();
		//echo "\n".$results->count()."\n";
		//$results->current();
		if ($results->count() > 0) {
			// update action
			$action = $this->sql->update();
			$rows = new ResultSet();
			$arr = $rows->initialize($results)->toArray();
		    $isFoodtruck = (int)$arr[0]['foodtruck'];
			if ($setFoodtruck == false) {
                unset($data['foodtruck']);
			}
			$action->set($data);
			$action->where(array('screen_name' => $twitterUser->getScreenName()));
			$data['foodtruck'] = $isFoodtruck;
		} else {
			// insert action
			$action = $this->sql->insert();
			$action->values($data);
		}

        $statement = $this->sql->prepareStatementForSqlObject($action);
        echo "<br><br>\n".$action->getSqlString($this->dbAdapter->getPlatform())."\n";
		$result = $statement->execute();
	
		// save profile pic to blvd users
		// TODO use $results to see if profile image url is already set in db 
		// TODO dont' iterate over blvdEntity
		// TODO use service locator to get BlvdMapper
	    $blvdMapper = new BlvdMapper($this->dbAdapter);
	    //this needs to be done manually as instagram may have added them already 
		if ($blvdMapper->getBlvdWithTwitterUsername($twitterUser->getScreenName()) == false) {
			$blvdMapper->saveTwitterUserToBlvd($twitterUser);
		}
		if ( $twitterUser->getProfileImageUrl() != '' ) {
		    if ($blvdEntity = $blvdMapper->getBlvdWithTwitterUsername($twitterUser->getScreenName())) {
		        foreach($blvdEntity as $key => $ent ) {
		          $ent->setProfilePictureUrl($twitterUser->getProfileImageUrl());
		          $blvdMapper->saveBlvd($ent);
		          break;
		        }
		    }
		}

		$hydrator = new ClassMethods();
		$twitterUser = new TwitterUserEntity();
		$hydrator->hydrate($data, $twitterUser);
		return $twitterUser;	
		return $result;
	
	}	

	// data from twitter.com feed
	public function formatTwitterUser($obj)
	{
	    
	    $url = '';
	    if (isset($obj->entities->url)) {
	    	$url = $obj->entities->url->urls[0]->expanded_url;
	    }
	    $date_ut = strtotime($obj->created_at);
	    $dateYMD = date("Y-m-d H:i:00", $date_ut);
	    
	    $userEnt = new TwitterUserEntity();
	    $userEnt->setId(NULL)
	       ->setName($obj->name)
            ->setTwitterId($obj->id_str)
            ->setScreenName($obj->screen_name)
            ->setDescription($obj->description)
            ->setCreatedAt($dateYMD)
            ->setUrl($url)
            ->setProfileImageUrl($obj->profile_image_url);
	    
	    return $userEnt;
	}
	
	public function deleteUser($screenname)
	{
		$delete = $this->sql->delete();
		$delete->where(array('screen_name' => $screenname));
		$statement = $this->sql->prepareStatementForSqlObject($delete);
		
		//echo "<br>".$delete->getSqlString($this->dbAdapter->getPlatform());
		return $statement->execute();
	}
	
	
}