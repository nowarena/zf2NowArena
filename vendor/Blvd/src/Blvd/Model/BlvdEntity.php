<?php
namespace Blvd\Model;

class BlvdEntity
{
    
    protected $id;
    protected $name;
    protected $display_name;
    protected $description;
    protected $address;
    protected $website;
    protected $phone;
    protected $facebook;
    protected $facebook_retrieve;
    protected $pinterest;
    protected $foursquare;
    protected $youtube;
    protected $googleplus;
    protected $yelp;
    protected $tumblr;
    protected $primary_social;
    protected $instagram_username;
    protected $twitter_username;
    protected $profile_picture_url;
    protected $reservation_url;
    protected $order_online;
    protected $last_social_media_datetime;
    protected $exclude_from_blvd;
    protected $instagram_disabled;
    
    public function getExcludeFromBlvd()
    {
        return $this->exclude_from_blvd;
    }
    
    public function setExcludeFromBlvd($val)
    {
        $this->exclude_from_blvd = $val;
        return $this;
    }
    
    public function getLastSocialMediaDatetime()
    {
        return $this->last_social_media_datetime;
    }
    
    public function setLastSocialMediaDatetime($val)
    {
        $this->last_social_media_datetime = $val;
        return $this;
    }

    public function getOrderOnline()
    {
        return $this->order_online;
    }
    public function setOrderOnline($val) 
    {
        $this->order_online = $val;
        return $this;
    } 
    
    public function setReservationUrl($val)
    {
        $this->reservation_url = $val;
        return $this;
    }
    
    public function getReservationUrl()
    {
        return $this->reservation_url;
    }
    
    public function setTwitterUsername($val)
    {
        $this->twitter_username = $val;
        return $this;
    }
    public function getTwitterUsername()
    {
        return $this->twitter_username;
    }
    
    public function setProfilePictureUrl($val)
    {
        $this->profile_picture_url = $val;
        return $this;
    }
    public function getProfilePictureUrl()
    {
        return $this->profile_picture_url;
    }
    
    // SETTERS
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setPrimarySocial($val)
    {
        $this->primary_social = $val;
        return $this;
    }

    public function setInstagramUsername($val)
    {
        $this->instagram_username = $val;
        return $this;
    }
 
    public function setName($val)
    {
        $this->name = $val;
        return $this;
    }
    
    public function setAddress($val) 
    {
        $this->address = $val;
        return $this;
    }
    
    public function setWebsite($val)
    {
        $this->website = $val;
        return $this;
    }
    
    public function setPhone($val)
    {
        $this->phone = $val;
        return $this;
    }
    
    public function setFacebookRetrieve($val)
    {
        $this->facebook_retrieve = $val;
        return $this;
    }
    public function getFacebookRetrieve()
    {
        return $this->facebook_retrieve;
    }
    
    public function setFacebook($val)
    {
        $this->facebook = $val;
        return $this;
    }

    public function setYoutube($val)
    {
        $this->youtube = $val;
        return $this;
    }
    
    public function setPinterest($val)
    {
    	$this->pinterest = $val;
    	return $this;
    }
    public function setYelp($val)
    {
    	$this->yelp = $val;
    	return $this;
    }
    public function setGoogleplus($val)
    {
    	$this->googleplus = $val;
    	return $this;
    }
    public function setDescription($val)
    {
    	$this->description = $val;
    	return $this;
    }
    public function setFoursquare($val)
    {
    	$this->foursquare = $val;
    	return $this;
    }
    public function setTumblr($val)
    {
    	$this->tumblr = $val;
    	return $this;
    }
    
    public function setDisplayName($val)
    {
    	$this->display_name = $val;
    	return $this;
    }
    
    // GETTERS 

    public function getInstagramUsername()
    {
        return $this->instagram_username;
    }
    public function getPrimarySocial() 
    {
        return $this->primary_social;
    }
    public function getAddress() 
    {
        return $this->address;
    }

    public function getId()
    {
    	return $this->id;
    }
    
    public function getName()
    {
    	return $this->name;
    	
    }
    
    public function getWebsite()
    {
    	return $this->website;
    	
    }
    
    public function getPhone()
    {
    	return $this->phone;
    	
    }
    
    public function getDisplayName()
    {
    	return $this->display_name;
    }

    // SOCIAL WEBSITE URLS
    public function getFacebook()
    {
    	return $this->facebook;
    }
    public function getYoutube()
    {
    	return $this->youtube;
    }
    public function getPinterest()
    {
    	return $this->pinterest;
    }
    public function getYelp()
    {
    	return $this->yelp;
    }
    public function getGoogleplus()
    {
    	return $this->googleplus;
    }
    public function getDescription()
    {
    	return $this->description;
    }
    public function getFoursquare()
    {
    	return $this->foursquare;
    }
    public function getTumblr()
    {
    	return $this->tumblr;
    }
 

    public function getInstagramDisabled()
    {
        return $this->instagram_disabled;
    }

    public function setInstagramDisabled($instagram_disabled)
    {
        $this->instagram_disabled = $instagram_disabled;
    }
}