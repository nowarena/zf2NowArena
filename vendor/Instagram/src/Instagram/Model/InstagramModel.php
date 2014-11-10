<?php
namespace Instagram\Model;

use Utility\Model\Utility;
use Instagram\Model\InstagramEntity;
use Blvd\Model\SocialMediaEntity;
use Gallery\Model\GalleryEntity;
//use Blvd\Model\SocialMediaModel;

/**
 * Class for extending twitter service and operating on response data from twitter
 * @author matt
 *
 */
class InstagramModel 
{
    
    private $accessToken;

	public function __construct($configArr)
	{
        $this->accessToken = $configArr['access_token'];
	}

	public function fetchFeed() 
	{
	    $utility = new Utility();
        $url = 'https://api.instagram.com/v1/users/self/feed?access_token=' . $this->accessToken;
	    return $utility->fetchUrlAndJsonDecode($url);
	}
	
	public function getLikes()
	{
	    
		$utility = new Utility();
		$url = 'https://api.instagram.com/v1/users/self/media/liked?access_token=' . $this->accessToken;
	    $response = $utility->fetchUrlAndJsonDecode($url);
	    printR($response);
	    return $response;

	}
	
	public function formatResponse($response)
	{

	   if (!is_object($response) || !isset($response->data)) {
	       return false;
	   }

	   $entArr = array();
	   foreach($response->data as $obj) {
	       printR($obj);
	       $instagramEnt = new InstagramEntity();
	       $instagramEnt->setLink($obj->link)
	           ->setCaption($obj->caption->text)
	           ->setCreatedTime($obj->caption->created_time)
	           ->setId($obj->id)
	           ->setImage($obj->images->standard_resolution->url)
	           ->setWidth($obj->images->standard_resolution->width)
	           ->setHeight($obj->images->standard_resolution->height)
	           ->setUsername($obj->user->username);
	       $entArr[] = $instagramEnt;
	   }
	   return $entArr;
	   
	    
	}
	
	public function formatGalleryEnt(InstagramEntity $ent)
	{
        $socMedEnt = new GalleryEntity();
        $socMedEnt->setUsername($ent->getUsername())
           ->setSocialId($ent->getId())
           ->setTitle('')
           ->setHeaderText('')
           ->setText($ent->getCaption())
           ->setMediaUrl($ent->getImage())
           ->setMediaHeight(150)
           ->setMediaWidth(150)
           ->setLink($ent->getLink())
           ->setSource('instagram')
           ->setDateCreated(date("Y-m-d H:i:s", $ent->getCreatedTime()));
        	   
        return $socMedEnt;
        
	}
	
	public function formatSocialMediaEnt(InstagramEntity $ent)
	{

   		$socMedEnt = new SocialMediaEntity();
        $socMedEnt->setUsername($ent->getUsername())
		    ->setDateCreated(date("Y-m-d H:i:s"))
		    ->setBlvdId($ent->getBlvdId())
            ->setLink($ent->getLink())
            ->setMediaHeight(150)
            ->setMediaWidth(150)
            ->setText($ent->getCaption())
            ->setSource('instagram')
            ->setSocialId($ent->getId())
            ->setMediaUrl($ent->getImage());
		
		return $socMedEnt;
	    
	}
	
    public function getThumbnail($imageUrl)
    {
    	return str_replace("_n.jpg", "_s.jpg", $imageUrl);
    }
    
    public function getMediumImage($imageUrl)
    {
    	return str_replace("_n.jpg", "_a.jpg", $imageUrl);
    }

     
    
}