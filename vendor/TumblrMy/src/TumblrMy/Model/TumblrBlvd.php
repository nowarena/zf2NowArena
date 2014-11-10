<?php
namespace TumblrMy\Model;

use TumblrMy\Model\TumblrEntity;
use Utility\Model\Utility;
use Gallery\Model\GalleryEntity;

class TumblrBlvd
{
	
	public $consumer_key;
	public $consumer_secret;
	public $token;
	public $token_secret;
	public $blog_name;

    public function __construct($configArr)
    {
    	$this->consumer_key = $configArr['consumer_key'];
    	$this->consumer_secret = $configArr['consumer_secret'];
    	$this->token = $configArr['token'];
    	$this->token_secret = $configArr['token_secret'];
    	$this->blog_name = $configArr['blog_name'];
    	
		$this->utilityObj = new Utility('tumblr.com');
		
    }
    
    public function getTumblrClient()
    {
        
         $client = new \Tumblr\API\Client(
            $this->consumer_key,
            $this->consumer_secret,
            $this->token,
            $this->token_secret
        );
         
        return $client;
    }
    
    public function formatTumblrForGallery($obj)
    {

        $socModelArr = array();
        if (isset($obj->photos)) {
        
            foreach($obj->photos as $key => $properties) {
                
                $tumblrEnt = $this->formatTumblrPost($obj, $properties);
                $socModel = $this->setGalleryEnt($tumblrEnt);
                $socModelArr[] = $socModel;
                
            }
        
        } else {
            
            $tumblrEnt = $this->formatTumblrPost($obj, null);
            $socModel = $this->setGalleryEnt($tumblrEnt);
            $socModelArr[] = $socModel;
            
        }

        return $socModelArr;
        
    }
    
    private function setGalleryEnt($tumblrEnt)
    {
        
        $socModel = new GalleryEntity();
        $socModel->setUsername($tumblrEnt->getBlogName())
            ->setSource('tumblr')
            ->setMediaUrl($tumblrEnt->getPhoto())
            ->setDateCreated($tumblrEnt->getDate())
            ->setLink($tumblrEnt->getPostUrl())
            ->setMediaHeight($tumblrEnt->getHeight())
            ->setMediaWidth($tumblrEnt->getWidth())
            ->setSocialId($tumblrEnt->getId());
        
       return $socModel;
        
    }
 
    public function formatTumblrPost($obj, $properties = null) 
    {

        $tumblrEnt = new TumblrEntity();
        $tumblrEnt->setId($obj->id)
            ->setPostUrl($obj->post_url)
            ->setDate($obj->date);
        
            
        //if (isset($obj->photos[0]->original_size->url)) {
        if (isset($properties->original_size->url)) {
            $tumblrEnt->setPhoto($properties->original_size->url);
            $tumblrEnt->setWidth($properties->original_size->width);
            $tumblrEnt->setHeight($properties->original_size->height);
        }
        if (isset($obj->title)) {
           $tumblrEnt->setTitle($this->cleanText($obj->title));
        }
        if (isset($obj->description)) {
           $tumblrEnt->setDescription($this->cleanText($obj->description));
        }
        if (isset($obj->caption)) {
            $tumblrEnt->setCaption($this->cleanText($obj->caption));
        }
        if (isset($obj->blog_name)) {
            $tumblrEnt->setBlogName($obj->blog_name);           
        }

        return $tumblrEnt;
 
    }
 

    private function cleanText($text)
    {
        
        $text = str_replace("<p>", "", $text);
        $text = str_replace("</p>", "", $text);
        $text = $this->utilityObj->cleanText($text);
        
        return $text;
        
    }
 

}