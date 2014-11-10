<?php
namespace Blvd\Model;

class SocialMediaEntity
{
    
    public $username;
    public $social_id;
    public $title;
    public $header_text;
    public $text;
    public $media_url;
    public $media_height;
    public $media_width;
    public $link;
    public $date_created;
    public $source; 
    public $blvd_id;

    public function getBlvdId()
    {
        return $this->blvd_id;
    }
    public function setBlvdId($val)
    {
        $this->blvd_id = $val;
        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function getSocialId()
    {
        return $this->social_id;
    }

    public function setSocialId($social_id)
    {
        $this->social_id = $social_id;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getHeaderText()
    {
        return $this->header_text;
    }

    public function setHeaderText($header_text)
    {
        $this->header_text = $header_text;
        return $this;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    public function getMediaUrl()
    {
        return $this->media_url;
    }

    public function setMediaUrl($media_url)
    {
        $this->media_url = $media_url;
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

    public function getMediaWidth()
    {
        return $this->media_width;
    }

    public function setMediaWidth($media_width)
    {
        $this->media_width = $media_width;
        return $this;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    public function getDateCreated()
    {
        return $this->date_created;
    }

    public function setDateCreated($date_created)
    {
        $this->date_created = $date_created;
        return $this;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }
}