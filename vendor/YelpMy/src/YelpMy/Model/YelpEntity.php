<?php
namespace YelpMy\Model;

class YelpEntity
{
    
    protected $id;
    protected $bizname;
    protected $text;
    protected $created_time;
    protected $image;
    protected $link;
    protected $media_width;
    protected $media_height;
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setBizname($val)
    {
        $this->bizname = $val;
        return $this;
    }
    
    public function setText($val)
    {
        $this->text= $val;
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
    
    public function getBizname()
    {
    	return $this->bizname;
    }
    
    public function getText()
    {
    	return $this->text;
    }
    
    public function getCreatedTime()
    {
    	return $this->created_time;
    }
 
    public function getImage()
    {
        return $this->image;
    }
    
    public function getLink()
    {
        return $this->link;
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