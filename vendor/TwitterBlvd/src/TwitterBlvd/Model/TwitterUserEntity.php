<?php
namespace TwitterBlvd\Model;

class TwitterUserEntity
{
    
    protected $id;
    protected $twitter_id;
    protected $name;
    protected $screen_name;
    protected $description;
    protected $created_at;
    protected $inserted_at;
    protected $url;
    protected $profile_image_url;
    protected $foodtruck = 0;
    protected $disable_at_tweets = 0;
    protected $disable_retweets = 0;
    
    public function __construct()
    {
        
    }
    public function setFoodtruck($val)
    {
    	$this->foodtruck = $val;
    }
    public function getFoodtruck()
    {
    	return $this->foodtruck;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setTwitterId($val)
    {
        $this->twitter_id = $val;
        return $this;
    }
    
    public function setName($val)
    {
        $this->name = $val;
        return $this;
    }
    
    public function setScreenName($val)
    {
        $this->screen_name = $val;
        return $this;
    }
    
    public function setDescription($val)
    {
        $this->description = $val;
        return $this;
    }
    
    public function setCreatedAt($val)
    {
        $this->created_at = $val;
        return $this;
    }

    public function setInsertedAt($val)
    {
        $this->inserted_at = $val;
        return $this;
    }

    public function setUrl($val)
    {
        $this->url = $val;
        return $this;
    }

    public function setProfileImageUrl($val)
    {
        $this->profile_image_url = $val;
        return $this;
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function getTwitterId()
    {
    	return $this->twitter_id ;

    }
    
    public function getName()
    {
    	return $this->name;
    	
    }
    
    public function getScreenName()
    {
    	return $this->screen_name;
    	
    }
    
    public function getDescription()
    {
    	return $this->description;
    	
    }
    
    public function getCreatedAt()
    {
    	return $this->created_at;
    	
    }
    
    public function getInsertedAt()
    {
    	return $this->inserted_at;
    	
    }
    
    public function getUrl()
    {
    	return $this->url;
    	
    }
    
    public function getProfileImageUrl()
    {
    	return $this->profile_image_url;
    }

    public function getDisableAtTweets()
    {
        return $this->disable_at_tweets;
    }

    public function setDisableAtTweets($disable_at_tweets)
    {
        $this->disable_at_tweets = $disable_at_tweets;
    }

    public function getDisableRetweets()
    {
        return $this->disable_retweets;
    }

    public function setDisableRetweets($disable_retweets)
    {
        $this->disable_retweets = $disable_retweets;
    }
}