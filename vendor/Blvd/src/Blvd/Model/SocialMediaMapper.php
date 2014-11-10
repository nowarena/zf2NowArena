<?php
namespace Blvd\Model;

use Application\Model\BaseModel;
use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\ArraySerializable;
use TwitterBlvd\Model\TweetEntity;
use Instagram\Model\InstagramEntity;
use FacebookMy\Model\FacebookEntity;
use YelpMy\Model\YelpEntity;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SocialMediaMapper extends BaseModel implements ServiceLocatorAwareInterface
{
	protected $tableName = 'social_media';
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

	public function fetchAllSocialMedia($blvdId, $offset = 0, $limit = 2)
	{
		$select = $this->sql->select();
		$select->where(array("blvd_id" => $blvdId, "unpublish"=>0));
		$select->order("date_created DESC");
		$select->offset($offset);
		$select->limit( $limit);
		$statement = $this->sql->prepareStatementForSqlObject($select);
		if (0) {
			echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());
		}
		$results = $statement->execute();
		$result = $this->hydrateResult(new SocialMediaModel(), $select);
		$result->buffer();
		
		//$result = $this->padMedia($results, $blvdId, $limit);
		return $result;
		 
	}
	
	public function getOpenTableSocial(BlvdEntity $blvdEnt)
	{
	
		$social = new SocialMediaModel();
		$social->setDateCreated(date("Y-m-d H:i:s"))
		    ->setBlvdId($blvdEnt->getId())
            ->setLink($blvdEnt->getReservationUrl())
            ->setMediaHeight(100)
            ->setMediaWidth(100)
            ->setText('Reserve table online')
            ->setSource('opentable')
            ->setSocialId(0)
            ->setMediaUrl('/img/opentable100x100.jpg');

		return ($social);
	
	}
	
	public function fetchWithBlvdId($blvdId, $offset = 0, $limit = 2)
	{
		$select = $this->sql->select();
        $select->where(array('blvd_id' => $blvdId))
            ->order('date_created DESC')
            ->limit($offset, $limit); 
	
		return $this->hydrateResult(new SocialMediaModel(), $select);

	}

	public function saveInstagram(InstagramEntity $ent, $blvdId)
	{
        $socMedEnt = new SocialMediaEntity();
        $socMedEnt->setUsername($ent->getUsername())
           ->setSocialId($ent->getId())
           ->setBlvdId($blvdId)
           ->setTitle('')
           ->setHeaderText('')
           ->setText($ent->getCaption())
           ->setMediaUrl($ent->getImage())
           ->setMediaHeight(150)
           ->setMediaWidth(150)
           ->setLink($ent->getLink())
           ->setSource('instagram')
           ->setDateCreated(date("Y-m-d H:i:s", $ent->getCreatedTime()));
        

        $this->insertSocialMedia($socMedEnt);
	   
        return $socMedEnt;
        
	}
	
	public function saveTweet(TweetEntity $ent, $blvdId)
	{
        $link = $ent->getStatusUrl();
        if ($link == '') {
            $link = "http://twitter.com/" . $ent->getScreenName() . "/status/" . $ent->getId();
        }
        $socMedEnt = new SocialMediaEntity();
        $socMedEnt->setUsername($ent->getScreenName())
           ->setSocialId($ent->getId())
           ->setBlvdId($blvdId)
           ->setTitle('')
           ->setHeaderText('')
           ->setText($ent->getTweet())
           ->setMediaUrl($ent->getMediaUrl())
           ->setMediaHeight($ent->getMediaHeight())
           ->setMediaWidth($ent->getMediaWidth())
           ->setLink($link)
           ->setSource('twitter')
           ->setDateCreated($ent->getCreatedAt());
        
        $this->insertSocialMedia($socMedEnt);
        
        return $socMedEnt;
        
	}
	
	public function saveYelp(YelpEntity $ent, $blvdId)
	{
	    
        $socMedEnt = new SocialMediaEntity();
        $socMedEnt->setUsername($ent->getBizname())
           ->setSocialId($ent->getId())
           ->setBlvdId($blvdId)
           ->setTitle('')
           ->setHeaderText('')
           ->setText($ent->getText())
           ->setMediaUrl($ent->getImage())
           ->setMediaHeight($ent->getMediaHeight())
           ->setMediaWidth($ent->getMediaWidth())
           ->setLink($ent->getLink())
           ->setSource('yelp')
           ->setDateCreated(date("Y-m-d H:i:s", $ent->getCreatedTime()));

        $this->insertSocialMedia($socMedEnt);
        
        return $socMedEnt;
        
	}
	
	public function saveFacebook(FacebookEntity $ent, $blvdId)
	{

	    $socMedEnt = new SocialMediaEntity();
	    $socMedEnt->setUsername($ent->getUsername())
    	    ->setSocialId($ent->getPostId())
    	    ->setTitle($ent->getMessageName())
    	    ->setHeaderText('')
    	    ->setText($ent->getMessage())
    	    ->setMediaUrl($ent->getPicture())
    	    ->setMediaHeight($ent->getMediaHeight())
    	    ->setMediaWidth($ent->getMediaWidth())
    	    ->setLink($ent->getLink())
    	    ->setSource('facebook')
    	    ->setBlvdId($blvdId)
    	    ->setDateCreated($ent->getCreatedTime());
	    
	    $this->insertSocialMedia($socMedEnt);
	    
        return $socMedEnt;
        
	}

	public function insertSocialMedia(SocialMediaEntity $socMedEnt)
	{
	    
        $select = $this->sql->select();
        $select->where(array("username" => $socMedEnt->getUsername(), "social_id" => $socMedEnt->getSocialId()));
        $result = $this->hydrateResult(new SocialMediaEntity(), $select);
        if ($result->count() == 0 ) {
            $hydrator = new ClassMethods();
            $data = $hydrator->extract($socMedEnt);
            $action = $this->sql->insert();
            $action->values($data);
            $statement = $this->sql->prepareStatementForSqlObject($action);
            // echo "<hr>"; echo $action->getSqlString($this->dbAdapter->getPlatform()); echo "<hr>asdf";
            $statement->execute();
            
       		$sql = new Sql($this->dbAdapter);
            $sql->setTable('blvd');
            $update = $sql->update();
            $update->set(array("last_social_media_datetime" => $data['date_created']))
                ->where(array("id" => $socMedEnt->getBlvdId()));
            $statement = $sql->prepareStatementForSqlObject($update);
            $r =  $statement->execute();
            echo $action->getSqlString($this->dbAdapter->getPlatform()); 
            
        } elseif (false) {
            $hydrator = new ClassMethods();
            $data = $hydrator->extract($socMedEnt);
            $update = $this->sql->update();
            $update->set(array("date_created" => $data['date_created']))
                ->where(array("username" => $socMedEnt->getUsername(), "social_id" => $socMedEnt->getSocialId()));
            $statement = $this->sql->prepareStatementForSqlObject($update);
            $r =  $statement->execute();
            //echo "<hr>"; echo $update->getSqlString($this->dbAdapter->getPlatform()); echo "<hr>asdf";
            return $r;
            
        }

        return false; 
        
	}
	
	public function unpublish($socialId, $username) 
	{

	   $update = $this->sql->update();
	   $update->set(array('unpublish'=>1));
	   $update->where(array("username" => $username, "social_id" => $socialId));
       $statement = $this->sql->prepareStatementForSqlObject($update);
       $r =  $statement->execute();
       return $r;
	
	}
	
	
	
}