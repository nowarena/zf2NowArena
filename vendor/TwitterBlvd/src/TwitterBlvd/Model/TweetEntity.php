<?php
namespace TwitterBlvd\Model;

class TweetEntity
{
    
    protected $id;
    protected $tweet_id;
    protected $tweet;
    protected $tweet_parsed;
    protected $created_at;
    protected $daysOld;
    protected $media_url;
    protected $media_width;
    protected $media_height;
    protected $status_url;
    protected $instagram_id;
    protected $twitter_id;
    protected $screen_name;
 
    public function setScreenName($val)
    {
        $this->screen_name = $val;
        return $this;
    }
    public function getScreenName()
    {
        return $this->screen_name;
    }
    
    public function setInstagramId($val)
    {
        $this->instagram_id = $val;
        return $this;
    }
    public function getInstagramId()
    {
        return $this->instagram_id;
    }
    
    public function setStatusUrl($val)
    {
        $this->status_url = $val;
        return $this;
    }
    
    public function setMediaUrl($val)
    {
        $this->media_url = $val;
        return $this;
    }
    public function setMediaHeight($val)
    {
        $this->media_height = $val;
        return $this;
    }
    public function setMediaWidth($val)
    {
        $this->media_width = $val;
        return $this;
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
    
    public function setTweetParsed($val)
    {
        $this->tweet_parsed = $val;
        return $this;
    }
    public function setTweet($val)
    {
        $this->tweet = $val;
        return $this;
    }
    
    public function setCreatedAt($val)
    {
        $this->created_at = $val;
        return $this;
    }

    public function getId()
    {
    	return $this->id;
    	
    }
    
    public function getTwitterId()
    {
    	return $this->twitter_id;
    }
    
    public function getTweetParsed()
    {
    	return $this->tweet_parsed;
    }
    public function getTweet()
    {
    	return $this->tweet;
    }
    
    public function getCreatedAt()
    {
    	return $this->created_at;
    }

    public function getStatusUrl()
    {
        return $this->status_url;
    }
 
    public function getMediaUrl()
    {
        return $this->media_url;
    }
    public function getMediaHeight()
    {
        return $this->media_height;
    }
    public function getMediaWidth()
    {
        return $this->media_width;
    }
 
}