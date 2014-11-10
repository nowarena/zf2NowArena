<?php
namespace TwitterBlvd\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use TwitterBlvd\Model\TweetEntity;
use Utility\Model\Utility;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Blvd\Model\SocialMediaMapper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TweetMapper implements ServiceLocatorAwareInterface
{
    
	protected $tableName = 'tweets';
	protected $dbAdapter;
	protected $sql;
    protected $serviceLocator;

	public function __construct(Adapter $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->tableName);
		$this->utilityObj = new Utility("twitter.com");
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

		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();

		$entityPrototype = new TweetEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
		
		return $resultset;
		
	}
	
	public function getTweetsByDate($ut, $twitter_id = 0, $intervalNum = 30, $unit ='hour')
	{

	    $date = date("Y-m-d H:i:s", $ut);
		$select = $this->sql->select();
		$select->where(array("created_at >= DATE_SUB('$date', INTERVAL $intervalNum $unit)"));
		//$select->where(array("created_at <= DATE_ADD('$date', INTERVAL $intervalNum $unit)"));
		if ($twitter_id) {
		    $select->where(array('twitter_id' => $twitter_id));
		}
	
		$statement = $this->sql->prepareStatementForSqlObject($select);
		//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
		$results = $statement->execute();
		
		if ($results->count() == 0) {
		    return false;
		}
		$entityPrototype = new TweetEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
	
		return $resultset;
	
	}
	
	public function getTweetsWithScreenName($screen_name, $limit = 3)
	{
	    
		$select = $this->sql->select();
		$select->where(array('screen_name' => $screen_name));
		$select->where(array('created_at >= DATE_SUB(NOW(), INTERVAL 7 day)'));
		if ($limit != 'all') {
		  $select->limit($limit);
		}
	
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
		$entityPrototype = new TweetEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
	
		return $resultset;
	
	}

	public function getTweetsWithTwitterId($twitter_id, $limit = 3)
	{
	    
	    if ($twitter_id == 0) {
	        return false;
	    }
		$select = $this->sql->select();
		$select->where(array('twitter_id' => $twitter_id));
		$select->where(array('created_at >= DATE_SUB(NOW(), INTERVAL 7 day)'));
		if ($limit != 'all') {
		  $select->limit($limit);
		}
	
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
		$entityPrototype = new TweetEntity();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($results);
	
		return $resultset;
	
	}

	// TODO type hint twitterUserObj
	public function formatTweet($obj, $twitterUserObj)
	{
		// Get most recent non-retweet status
		$text = '';
		$created_at = '';
		$tweet_id = 0;
	
		// extract expanded urls and replace shortened urls with expanded urls
		$media_url = '';
		$media_height = 0;
		$media_width = 0;
		$status_url = '';
		
		if (isset($obj->status)) {

		    $source = $obj->status->source;
		    if ($twitterUserObj->getFoodtruck() != 1 && (stristr($source, 'facebook') || stristr($source, 'instagram')/* || stristr($source, 'hootsuite')*/)) {
		        return false;
		    }
			$tweet_id = $obj->status->id_str;
			$text = $obj->status->text;

			// if retweets are disabled for twitter user, return false
			$disableRetweets = $twitterUserObj->getDisableRetweets();
            $disableAtTweets = $twitterUserObj->getDisableAtTweets();	
			if (isset($obj->status->retweeted_status) && is_object($obj->status->retweeted_status) && ($disableRetweets || $disableAtTweets)) {
			    return false;
			}
			
			// if it is a retweet, get original text of tweet being retweeted as it gets truncated when being retweeted
			if (substr($text, 0, 4) == 'RT @' && isset($obj->status->retweeted_status->text)) {
				$rt = preg_match("~RT @[^:]+:~is", $text, $arr);
				if (isset($arr[0])) {
					$text = $arr[0] . " " . $obj->status->retweeted_status->text;
				}
			}
			$created_at = $obj->status->created_at;
			if (isset($obj->status->entities)) {
				if (isset($obj->status->entities->urls)) {
					foreach($obj->status->entities->urls as $urlObj) {
					    // 'tweetdeck' source apparently posts to twitter and instagram at once
					    if ($twitterUserObj->getFoodtruck() !=1 && stristr($urlObj->expanded_url, "instagram")) {
					        return false;
					    }
						$text = str_replace($urlObj->url, $urlObj->expanded_url, $text);
					}
				}
				if(isset($obj->status->entities->media)) {
					foreach($obj->status->entities->media as $urlObj) {
						$text = str_replace($urlObj->url, $urlObj->expanded_url, $text);
						// if expanded url is a status link to twitter, save it
						if (stristr($urlObj->expanded_url, "/status/") && $status_url == '') {
							$status_url = $urlObj->expanded_url;
						}
						if ($urlObj->media_url != '' && $media_url == '') {
							$media_url = $urlObj->media_url . ":thumb";
							// all twitter thumbs are 150 x 150
							$media_height = 150;
							$media_width = 150;
							//$media_height = $urlObj->sizes->large->h;
							//$media_width = $urlObj->sizes->large->w;
							break;//just get the one pic
						}
	
					}
				}
			}

			// get the tweet that this tweet is in reply to
			$reply_id = 0;
			if (false && $obj->status->in_reply_to_status_id_str != '') {
			    $reply_id = $obj->status->in_reply_to_status_id_str;
			    $tmp = $this->serviceLocator->get('Twitter\Model\TwitterBlvd')->statuses->show($reply_id);
			    $tmp = $tmp->toValue();
			    echo "<hr>asdf<br>";
			    printR($tmp);
			   	$arr = array();
        		$arr['created_at'] = date("Y-m-d H:i:00", strtotime($tmp->created_at));
        		$arr['tweet_id'] = $tmp->id_str;
        		$arr['screen_name'] = $tmp->user->screen_name;
        		$arr['tweet'] = $tmp->text;
        		$arr['twitter_id'] = $tmp->id_str;
        		$arr['status_url'] = '';
        		$arr['tweet_parsed'] = '';
        		$arr['media_url'] = '';
        		$arr['media_width'] = 0;
        		$arr['media_height'] = 0;
        		echo "hey";
        		printR($arr);
        		$tweetEnt = $this->setTweetEntity($arr); 
        		$this->saveTweet($tweetEnt);
			}
		}
	
		// If missing tweet_id, skip
		if ($tweet_id == 0) {
			return false;
		}
	
		$twitter_id = preg_replace("~[^0-9]~", "", $obj->id_str);
	
		$date_ut = strtotime($created_at);
		$created_at = date("Y-m-d H:i:00", $date_ut);
	
		$tweet_parsed = $this->utilityObj->parseText($text);
		
		$arr = array();
		$arr['created_at'] = $created_at;
		$arr['tweet_id'] = $tweet_id;
		$arr['screen_name'] = $obj->screen_name;
		$arr['tweet'] = $text;
		$arr['twitter_id'] = $twitter_id;
		$arr['status_url'] = $status_url;
		$arr['tweet_parsed'] = $tweet_parsed;
		$arr['media_url'] = $media_url;
		$arr['media_width'] = $media_width;
		$arr['media_height'] = $media_height;
		$tweetEnt = $this->setTweetEntity($arr);

        return $tweetEnt;	
	
	}

	public function setTweetEntity(array $arr)
	{
	
		$tweet_parsed = isset($arr['tweet_parsed']) ? $arr['tweet_parsed'] : '';
		$media_url = isset($arr['media_url']) ? $arr['media_url'] : '';
		$media_height = isset($arr['media_height']) ? $arr['media_height'] : 0;
		$media_width = isset($arr['media_width']) ? $arr['media_width'] : 0;
		 
	
		$tweetEnt = new TweetEntity();
		$tweetEnt->setTwitterId($arr['twitter_id'])
    		->setScreenName($arr['screen_name'])
    		->setId($arr['tweet_id'])
    		->setTweetParsed($tweet_parsed)
    		->setTweet($arr['tweet'])
    		->setCreatedAt($arr['created_at'])
    		->setMediaUrl($media_url)
    		->setMediaHeight($media_height)
    		->setMediaWidth($media_width)
    		->setStatusUrl($arr['status_url']);
	
		return $tweetEnt;
	
	}
	
	public function saveTweet(TweetEntity $ent)
	{
		$hydrator = new ClassMethods();
		$data = $hydrator->extract($ent);
		//$data['tweet_parsed'] = $this->utilityObj->parseText($data['tweet']);
		
		$select = $this->sql->select();
		$whereArr = array('id' => $ent->getId());
		$select->where($whereArr);
		$statement = $this->sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		if ($results->count() > 0) {
/*	
			return true;//nothing to update. this is data from twitter and doesn't get updated - may update with instagram_id
	
			// update action
			$action = $this->sql->update();
			$action->set($data);
			$action->where(array('id' => $ent->getId()));
		    $statement = $this->sql->prepareStatementForSqlObject($action);
            $result = $statement->execute();
            */
		} elseif (false) {
			// insert action
			$action = $this->sql->insert();
			$action->values($data);
		    $statement = $this->sql->prepareStatementForSqlObject($action);
            $result = $statement->execute();
		}

		$blvdId = $this->getServiceLocator()->get("Blvd\Model\BlvdMapper")->getBlvdIdWithSocialUsername($ent->getScreenName(), 'twitter');
		if (true || $blvdId) {
    		$socEnt = $this->getServiceLocator()->get("Blvd\Model\SocialMediaMapper")->saveTweet($ent, $blvdId);
            $this->getServiceLocator()->get("Blvd\Model\BlvdMapper")->updateBlvdWithSocialMediaDatetime($socEnt);
		}
	
	}
	
	public function deleteTweets($screenname)
	{
		$delete = $this->sql->delete();
		$delete->where(array('screen_name' => $screenname));
		$statement = $this->sql->prepareStatementForSqlObject($delete);
		//echo "<br>".$delete->getSqlString($this->dbAdapter->getPlatform());
		return $statement->execute();
	}	
	
}