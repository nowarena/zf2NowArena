<?php
namespace FacebookMy\Model;

class FacebookEntity
{
    
    protected $post_id;
    protected $username;
    protected $message;
    protected $created_time;
    protected $picture;
    protected $message_name;
    protected $description;
    protected $link;
    protected $media_height;
    protected $media_width;

    public function setMessageName($val)
    {
        $this->message_name = $val;
        return $this;
    }
    public function getMessageName()
    {
        return $this->message_name;
    }
    public function setDescription($val)
    {
        $this->description = $val;
        return $this;
    }
    public function getDescription()
    {
        return $this->description;
    }
    
    public function setPostId($val)
    {
        $this->post_id = $val;
        return $this;
    }

    public function setUsername($val)
    {
        $this->username = $val;
        return $this;
    }
    
    public function setMessage($val)
    {
        $this->message= $val;
        return $this;
    }
    
    public function setCreatedTime($val)
    {
        $this->created_time = $val;
        return $this;
    }

    public function getLink()
    {
        return $this->link;
    }
    public function setLink($val)
    {
        $this->link = $val;
        return $this;
    }

    public function setPicture($val)
    {
        $this->picture = $val;
        return $this;
    }
    
    public function getPostId()
    {
    	return $this->post_id;
    }
    
    public function getUsername()
    {
    	return $this->username;
    }
    
    public function getMessage()
    {
    	return $this->message;
    }
    
    public function getCreatedTime()
    {
    	return $this->created_time;
    }
 
    public function getPicture()
    {
        return $this->picture;
    }

    public function getMediaWidth()
    {
    	return $this->media_width;
    }
    
    public function setMediaWidth($media_width)
    {
    	$this->media_width = $media_width;
    	return $this;
    }
    
    public function getMediaHeight()
    {
    	return $this->media_height;
    }
    
    public function setMediaHeight($media_height)
    {
    	$this->media_height = $media_height;
    	return $this;
    } 

}