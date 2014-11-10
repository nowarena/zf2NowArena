<?php
namespace Videos\Model;

class VideosEntity
{

    protected $id;
    protected $title;
    protected $video_id;
    protected $width;
    protected $height;
    protected $thumbnail;
    protected $source;
    protected $date_created;
    protected $date_inserted;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getVideoId()
    {
        return $this->video_id;
    }

    public function setVideoId($video_id)
    {
        $this->video_id = $video_id;
        return $this;
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

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
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

    public function getDateCreated()
    {
        return $this->date_created;
    }

    public function setDateCreated($date_created)
    {
        $this->date_created = $date_created;
        return $this;
    }

    public function getDateInserted()
    {
        return $this->date_inserted;
    }

    public function setDateInserted($date_inserted)
    {
        $this->date_inserted = $date_inserted;
        return $this;
    }
}