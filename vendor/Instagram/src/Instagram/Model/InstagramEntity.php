<?php
namespace Instagram\Model;

class InstagramEntity
{
    
    protected $id;
    protected $username;
    protected $caption;
    protected $caption_parsed;
    protected $created_time;
    protected $link;
    protected $image;
    protected $width;
    protected $height;
    protected $blvd_id;
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUsername($val)
    {
        $this->username = $val;
        return $this;
    }
    
    public function setCaption($val)
    {
        $this->caption= $val;
        return $this;
    }
    
    public function setCaptionParsed($val)
    {
        $this->caption_parsed = $val;
        return $this;
    }
    
    public function setCreatedTime($val)
    {
        $this->created_time = $val;
        return $this;
    }
    
    public function setLink($val)
    {
        $this->link = $val;
        return $this;
    }

    public function setImage($val)
    {
        $this->image = $val;
        return $this;
    }
    
    public function getId()
    {
    	return $this->id;
    }
    
    public function getUsername()
    {
    	return $this->username;
    }
    
    public function getCaptionParsed()
    {
    	return $this->caption_parsed;
    }
    public function getCaption()
    {
    	return $this->caption;
    }
    
    public function getCreatedTime()
    {
    	return $this->created_time;
    }
 
    public function getLink()
    {
        return $this->link;
    }
    public function getImage()
    {
        return $this->image;
    }
    


    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    public function getBlvdId()
    {
        return $this->blvd_id;
    }

    public function setBlvdId($blvd_id)
    {
        $this->blvd_id = $blvd_id;
    }
}