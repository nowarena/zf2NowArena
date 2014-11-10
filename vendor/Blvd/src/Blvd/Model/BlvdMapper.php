<?php
namespace Blvd\Model;

use Zend\Db\Adapter\Adapter;
//use Zend\Db\ResultSet\ResultSet;
use Blvd\Model\BlvdEntity;
use Blvd\Model\SocialMediaEntity;
use Blvd\Model\SocialMediaMapper;
use Blvd\Model\BlvdModel;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Instagram\Model\InstagramEntity;
use TwitterBlvd\Model\TwitterBlvd;
use TwitterBlvd\Model\TwitterUserEntity;
use Blvd\Model\Social;
use Utility\Model\Utility;
use Facebook\Model\FacebookEntity;
use Application\Model\BaseModel;
use Zend\Db\Sql\Expression;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BlvdMapper extends BaseModel implements ServiceLocatorAwareInterface
{
    
	protected $tableName = 'blvd';
	protected $dbAdapter;
	protected $sql;

	public function __construct(Adapter $dbAdapter)
	{

		$this->dbAdapter = $dbAdapter;
		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->tableName);
		
		$this->media_width = 100;
		$this->media_height = 100;
	/*	
		$this->adapter = $dbAdapter;
		$this->resultSetPrototype = new ResultSet();
		$this->resultSetPrototype->setArrayObjectPrototype(new BlvdEntity());
		$this->initialize();
		*/
	}
	
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->serviceLocator = $serviceLocator;
	}
	
	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}
	
	public function getInstagramDisabledArr()
	{
		$select = $this->sql->select();
		$select->where(array("instagram_disabled" => 1));
		//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());exit;
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		$rows = new ResultSet();
		$arr = $rows->initialize($result)->toArray();
		$disabledArr = array();
		foreach($arr as $key => $row) {
			$disabledArr[] = strtolower($row['instagram_username']);
		}
		
		return $disabledArr;
	    
	}
	
	public function deleteFromBlvd($blvdId) 
	{
	    $delete = $this->sql->delete();
	    $delete->where(array('id' => $blvdId));
		$statement = $this->sql->prepareStatementForSqlObject($delete);
	    //echo "<br>".$delete->getSqlString($this->dbAdapter->getPlatform());
		return $statement->execute();
	}
	
	public function fetchBlvdWithFacebookPages() 
	{
		$select = $this->sql->select();
		$select->where(array("facebook_retrieve" => 1));
		//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());exit;
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		
		$entityPrototype = new BlvdEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
		$resultset->buffer();
		return $resultset;
	    
	}

	public function fetchAll($sort = 'address', $direction = 'ASC', $yelpOnly = false)
	{
		$select = $this->sql->select();
		$select->join('blvd_categories', new Expression('(blvd_categories.blvd_id = blvd.id AND blvd_categories.primary = 1)'), array('*'), $select::JOIN_LEFT);
		$select->join('categories', 'categories.id = blvd_categories.category_id', array('category'), $select::JOIN_LEFT);
		$select->order(array($sort . ' ' . $direction));

		//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		
		$entityPrototype = new BlvdModel();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
		$resultset->buffer();
		
		$resultset = $this->addSecondaryCategory($resultset);
		        
		return $resultset;
		
	}

	public function getBlvdWithInstagramUsername($username)
	{
		 
		$select = $this->sql->select();
        $select->where(array('instagram_username' => $username));
        return $this->getBlvdWithSocialUsername($select, 'instagram');  
	}
	
	public function getBlvdWithTwitterUsername($username)
	{
		 
		$select = $this->sql->select();
        $select->where(array('twitter_username' => $username));
        return $this->getBlvdWithSocialUsername($select, 'twitter');        
        
	}
	
	public function getBlvdWithFacebookUsername($username)
	{
		 
		$select = $this->sql->select();
        $select->where(array('facebook' => $username));
        return $this->getBlvdWithSocialUsername($select, 'facebook');   
        
	}
	
	public function getBlvdIdWithSocialUsername($username, $socialsite) 
	{

		$select = $this->sql->select();
		if ($socialsite == 'twitter') {
            $select->where(array('twitter_username' => $username));
		} elseif ($socialsite == 'instagram') {
            $select->where(array('instagram_username' => $username));
		} elseif ($socialsite == 'facebook') {
            $select->where(array('facebook' => $username));
		} elseif ($socialsite == 'yelp') {
		    $select->where(array('yelp' => $username));
		} else {
		    // todo throw exception
		    return 0; 
		    return false;
		}
        $arr = $this->arrayResult($select);
        if (isset($arr[0]['id'])) {
            return $arr[0]['id'];
        }
        return false;
		//$statement = $this->sql->prepareStatementForSqlObject($select);
		//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
		//$results = $statement->execute();
		//if ($results->count() == 0) {
		//    return false;
		//}
		return $this->arrayResult($select);
	}
	
	public function getBlvdWithSocialUsername($select) {
	    
		$statement = $this->sql->prepareStatementForSqlObject($select);
		//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
		$results = $statement->execute();
		if ($results->count() == 0) {
		    return false;
		}
		//return $this->arrayResult($select);
		//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
		$entityPrototype = new BlvdEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
		$resultset->buffer();
		$resultset->current();

		return $resultset;

	}
	
	public function getTwitterUsernameWithInstagramUsername($username)
	{
	    if ($blvdEnt = $this->getBlvdWithInstagramUsername($username)) {
	       foreach($blvdEnt as $row) {
	           return $row->getTwitterUsername();
	        }
	    }
	    return false;
	        
	}
	
	
	public function getBlvd($id = '', $name = '')
	{
	    
        $select = $this->sql->select();
        if ($name) {
            $select->where(array("name like '%".mysql_real_escape_string($name)."%'"));
        } elseif ($id) {
            $select->where(array('id' => $id));
        } else {
            return false;
        }
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        
		//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
        $result = $statement->execute()->current();
        if (!$result) {
            return false;
        }
        
        $hydrator = new ClassMethods();
        $blvd = new BlvdEntity();
        $blvd = $hydrator->hydrate($result, $blvd);
        return $blvd;
                
    }
    
    public function getRecentCategory($catId, $offset, $numCols) 
    {
        
        $q= "SELECT `blvd`.*, `blvd_categories`.`category_id` AS `category_id` FROM `blvd` ";
        $q.= "INNER JOIN `blvd_categories` ON (`blvd_categories`.`blvd_id` = `blvd`.`id` AND blvd_categories.category_id = ?) ";
        $q.= "INNER JOIN categories ON (blvd_categories.category_id = categories.id) ";
        $q.= "WHERE blvd_categories.primary = 1 ";
        $q.= "AND categories.top = 1 ";
        $q.= "AND `categories`.`disabled` = '0' ";
        $q.= "ORDER BY `category_id` DESC, `last_social_media_datetime` DESC, blvd_id DESC ";
        $q.= "LIMIT ?, 1";
        $statement = $this->dbAdapter->createStatement($q, array($catId, $offset));
        $result = $statement->execute(); 
        $hydrator = new ClassMethods(); 
        $resultSet = new HydratingResultSet($hydrator, new BlvdModel());
        $resultSet->initialize($result);
        if ($resultSet->count() == 0) {
            return false;
        }
        $resultSet->buffer(); 
        $resultSet = $this->addSocialMedia($resultSet, $numCols);
        $resultSet = $this->addSecondaryCategory($resultSet);
        return $resultSet;
        
        
    }
    
    public function getRecentBlvd($numCols)
    {

        // can't get the join to work using zf2 methods, so using raw query
        $q = "SELECT * from ";
        $q.= "(SELECT `blvd`.*, `blvd_categories`.`category_id` AS `category_id` FROM `blvd` ";
        $q.= "INNER JOIN `blvd_categories` ON `blvd_categories`.`blvd_id` = `blvd`.`id` ";
        $q.= "INNER JOIN `categories` ON `blvd_categories`.`category_id` = `categories`.`id` ";
        $q.= "WHERE `categories`.`disabled` = '0' ";
        $q.= "AND categories.top = 1 ";
        $q.= "AND blvd_categories.primary = '1' ";
        $q.= "ORDER BY `category_id` DESC, `last_social_media_datetime` DESC, blvd_id DESC) ";
        $q.= "AS `blvdrows` ";
        $q.= "GROUP BY `category_id`";
        $statement = $this->dbAdapter->createStatement($q);
        $result = $statement->execute(); 
        $hydrator = new ClassMethods(); 
        $resultSet = new HydratingResultSet($hydrator, new BlvdModel());
        $resultSet->initialize($result);
        $resultSet->buffer();

        $resultSet = $this->addSocialMedia($resultSet, $numCols);
        $resultSet = $this->addSecondaryCategory($resultSet);
        return $resultSet;
        
       
       /* 
    	$select = $this->sql->select();
   		$select->join("blvd_categories", "blvd_categories.blvd_id = blvd.id", 'category_id', $select::JOIN_INNER);
   		$select->join("categories", "blvd_categories.category_id = categories.id", array(), $select::JOIN_INNER);
   		$select->where(array("categories.main" => 1));
    	$select->order('category_id DESC, last_social_media_datetime DESC');

        $mainSelect = new Select();
        $mainSelect->from(new \Zend\Db\Sql\Expression( '?', array( $select )));
        //$mainSelect->columns(array('*'));
        //$mainSelect->from($select, array('*'))->group('category_id');
        //$mainSelect->columns(array('name','category', 'last_social_media_datetime', '*' => new \Zend\Db\Sql\Expression( '?', array( $select ))))->group('category_id'); 
       */ 

    }
    
    public function addSocialMedia($resultSet, $numCols)
    {
        
        $resultArr = array();
        foreach($resultSet as $key => $model) {
            $model->setSocialMedia($this->getServiceLocator()->get('Blvd\Model\SocialMediaMapper')->fetchAllSocialMedia($model->getId(), 0, $numCols));
            $resultArr[] = $model;
        }
        
        return $resultArr;
        
        
    }
    
    public function addSecondaryCategory($resultSet) 
    {
        
        $resultArr = array();
        foreach($resultSet as $key => $model) {
            $arr = $this->getServiceLocator()->get('Blvd\Model\BlvdJoinCategoryMapper')->getNonPrimaryCategory($model->getId());
            if ($arr) {
                $model->setSecondaryCategory($arr['secondary_category']);
                $model->setSecondaryCategoryId($arr['secondary_category_id']); 
            }
            $resultArr[] = $model;
        }
        
        return $resultArr;
        
    }

    // updates blvd table with most recent datetime of last social media posting
    public function updateBlvdWithSocialMediaDatetime(SocialMediaEntity $socEnt)
    {
        $update = $this->sql->update();
        $update->set(array('last_social_media_datetime' => $socEnt->getDateCreated()))
            ->where(array("id" => $socEnt->getBlvdId()))
            ->where->lessThan("last_social_media_datetime", $socEnt->getDateCreated());
            
    	$statement = $this->sql->prepareStatementForSqlObject($update);
    	$result = $statement->execute();
		//echo "<br><br>".$update->getSqlString($this->dbAdapter->getPlatform());
    	if (!$result) {
    		return false;
    	}
        
    }
    
    public function getAllBlvd($categoryId = 0, $browseBlvd = 0)
    {
       
        $select = $this->sql->select();
        if ($categoryId) {
        	$select->join("blvd_categories", "blvd_categories.blvd_id = blvd.id", "*", $select::JOIN_INNER);
        	$select->where(array("blvd_categories.category_id" => $categoryId));
            $select->order('last_social_media_datetime DESC');
        } else {
            $select->order('address ASC');
        }
        if ($browseBlvd) {
            $select->where(array('exclude_from_blvd' => 0));
        }
        
        $resultset = $this->hydrateResult(new BlvdModel(), $select);
        
		$resultset = $this->addSecondaryCategory($resultset);
		
		return $resultset;
        
    }
    
    public function saveTwitterUserToBlvd(TwitterUserEntity $twitterUser) 
    {

        $blvdEnt = $this->convertTwitterUserToBlvd($twitterUser);
        return $this->saveBlvd($blvdEnt); 
        
    }
    
    public function convertTwitterUserToBlvd(TwitterUserEntity $twitterUser) 
    {

        $blvdEnt = new BlvdEntity();
        $blvdEnt->setName($twitterUser->getName())
            ->setWebsite($twitterUser->getUrl())
            ->setDescription($twitterUser->getDescription())
            ->setProfilePictureUrl($twitterUser->getProfileImageUrl())
            ->setExcludeFromBlvd(1)
            ->setTwitterUsername($twitterUser->getScreenName())
            ->setDisplayName($twitterUser->getName());
        
        return $blvdEnt;    
        
    }
	
	public function saveBlvd(BlvdEntity $blvd)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($blvd);

		if ($blvd->getId()) {
			// update action
			$action = $this->sql->update();
			$action->set($data);
			$action->where(array('id' => $blvd->getId()));
		} else {
			// insert action
			$action = $this->sql->insert();
			unset($data['id']);
			$data['last_social_media_datetime'] = '';
			$action->values($data);
		}
		$statement = $this->sql->prepareStatementForSqlObject($action);
		// can't get query to display
		//echo "<br><br>".$this->sql->update()->getSqlString($this->dbAdapter->getPlatform());
		//echo "<br><br>".$this->sql->insert()->getSqlString($this->dbAdapter->getPlatform());
		$result = $statement->execute();
		if (!$blvd->getId()) {
			$blvd->setId($result->getGeneratedValue());
		}
		return $blvd;
	
	}

	
	/*
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 * old
	 */
	public function fetchAllSocial($categoryId = 0, $blvdId = 0)
	{
		/*
			$select->join('instagrams', 'blvd.instagram_username = instagrams.username' , array('*'), 'left');
		$select->join('tweets', 'blvd.twitter_username = tweets.screen_name', array('*'), 'left');
		$select->where("FROM_UNIXTIME(instagrams.created_time) >= DATE_SUB(NOW(), INTERVAL 7 day)");
		$select->where("tweets.created_at >= DATE_SUB(NOW(), INTERVAL 7 day)");
		*/
		//echo $select->getSqlString(); // see the sql query
		//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());exit;
		$select = $this->sql->select();
		if ($categoryId) {
			$select->join("blvd_categories", "blvd_categories.blvd_id = blvd.id", "*", $select::JOIN_INNER);
			$select->where(array("blvd_categories.category_id" => $categoryId));
		}
	
		if ($blvdId) {
			$select->where(array("id" => $blvdId));
		}
		$select->order("address ASC");
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$blvdResultObj = $statement->execute();
	
		$utilityObj = new Utility();
		$this->sql = new Sql($this->dbAdapter);
		$this->sql->setTable('tweets');
		$blvdArr = array();
		foreach($blvdResultObj as $arr) {
	
			$blvdArr = $arr;
			$instagramIdArr = array();
	
			$blvdId = $arr['id'];
	
			// instagram
			if ($arr['instagram_username']) {
				$this->sql->setTable('instagrams');
				$select = $this->sql->select();
				$select->where(array('username' => $arr['instagram_username']))->order('created_time DESC')->limit(3);
				$statement = $this->sql->prepareStatementForSqlObject($select);
				$instagramResultObj = $statement->execute();
				if ($instagramResultObj->count() >0 ) {
					foreach($instagramResultObj as $row) {
						// set instagram id's to have twitter use them in to avoid same content
						$instagramIdArr[] = $row['id'];
						$socialObj = new Social();
						$socialObj->setDateCreated($row['created_time'])
						->setLink($row['link'])
						->setMediaHeight($this->media_height)
						->setMediaWidth($this->media_width)
						->setMediaUrl($row['image'])
						->setSocial('instagram')
						->setUsername($row['username'])
						->setText($row['caption']);
						$blvdArr['social_arr'][] = $socialObj;
					}
				}
			}
	
			// twitter
			if ($arr['twitter_username']) {
				$this->sql->setTable('tweets');
				$select = $this->sql->select();
				$select->where(array('screen_name' => $arr['twitter_username']));
				if (false && count($instagramIdArr) >0 ) {
					$select->where("instagram_id NOT IN ('" . implode("','", $instagramIdArr) . "')");
				}
				$select->order('created_at DESC')->limit(3);
				$statement = $this->sql->prepareStatementForSqlObject($select);
				//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());//;exit;
				$tweetResultObj = $statement->execute();
				if ($tweetResultObj->count() >0 ) {
					foreach($tweetResultObj as $row) {
						$thumb = '';
						if ($row['media_url'] != '') {
							$thumb = $row['media_url'] . ":thumb";
						}
						$socialObj = new Social();
						$socialObj->setDateCreated($row['created_at'])
						->setLink($row['status_url'])
						->setMediaHeight($this->media_height)
						->setMediaWidth($this->media_width)
						->setMediaUrl($thumb)
						->setSocial('twitter')
						->setUsername($row['screen_name'])
						->setText($row['tweet']);
						$blvdArr['social_arr'][] = $socialObj;
					}
	
				}
	
			}
	
			// facebook
			if ($arr['facebook'] != '' && $arr['facebook_retrieve'] == 1) {
				$this->sql->setTable('facebookpages');
				$select = $this->sql->select();
				$select->where(array('username' => $arr['facebook']));
				$select->order('created_time DESC')->limit(3);
				$statement = $this->sql->prepareStatementForSqlObject($select);
				//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());exit;
				$resultObj = $statement->execute();
				if ($resultObj->count() >0 ) {
					foreach($resultObj as $row) {
						$message = $row['message'];
						$message_name = $row['message_name'];
						$description = $row['description'];
						if ($message_name != '') {
							$text = $message_name;
						}elseif ($message != '') {
							$text = $message;
						} else {
							$text = $description;
						}
						$username = $row['username'];
						$thumb = $row['picture'];
						$link = $row['link'];
						if ($thumb =='' ) {
							$text.=" &nbsp; " . $link;
						}
						$socialObj = new Social();
						$socialObj->setDateCreated($row['created_time'])
    						->setLink($link)
    						->setMediaHeight($this->media_height)
    						->setMediaWidth($this->media_width)
    						->setMediaUrl($thumb)
    						->setSocial('facebook')
    						->setUsername($username)
    						->setText($text);
						//if ($username=='go2yas')printR($socialObj);
						$blvdArr['social_arr'][] = $socialObj;
					}
	
				}
	
			}
	
			$blvdEntitiesArr[] = new BlvdModel($blvdArr);
		}
	
		return $blvdEntitiesArr;
	
	}

}