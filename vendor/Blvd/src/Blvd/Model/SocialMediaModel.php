<?php
namespace Blvd\Model;

use Utility\Model\Utility;

class SocialMediaModel extends SocialMediaEntity 
{
    public function __construct()
    {
    	$this->twitterUtilObj = new Utility('twitter.com');
    	$this->instagramUtilObj = new Utility('instagram.com');
    	$this->facebookUtilObj = new Utility('facebook.com');
    	$this->utilObj = new Utility();
    }
    
    /**
     * Used by ResultSet to pass each database row to the entity
     */
    public function exchangeArray($data)
    {
 
        $this->social_id     = (isset($data['social_id'])) ? $data['social_id'] : null;
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->title  = (isset($data['title'])) ? $data['title'] : null;
        $this->header_text = (isset($data['header_text'])) ? $data['header_text'] : null;
        $this->text = (isset($data['text'])) ? $data['text'] : null;
        $this->media_url = (isset($data['media_url'])) ? $data['media_url'] : null;
        $this->media_height = (isset($data['media_height'])) ? $data['media_height'] : null;
        $this->media_width = (isset($data['media_width'])) ? $data['media_width'] : null;
        $this->link = (isset($data['link'])) ? $data['link'] : null;
        $this->date_created = (isset($data['date_created'])) ? $data['date_created'] : null;
        $this->blvd_id = (isset($data['blvd_id'])) ? $data['blvd_id'] : null;
        $this->source = (isset($data['source'])) ? $data['source'] : null;
        $this->unpublish = (isset($data['unpublish'])) ? $data['unpublish'] : null;
        
    }
    public function getMediaHeight()
    {
        $val = $this->utilObj->getSmallMediaHeight(parent::getMediaWidth(), parent::getMediaHeight()); 
        return $val;
    }
    
    public function getMediaWidth()
    {
        return $this->utilObj->getSmallMediaWidth(parent::getMediaWidth(), parent::getMediaHeight()); 
    }
    
    public function getText()
    {
        $textLength = 1000;
        if (trim($this->getMediaUrl()) == '') {
            $textLength = 200;
        }
        $textLength = 1000;
        if ($this->getSource() == 'twitter') {
            // if there is no image and no link, build a link to status page and append it to text
            if ($this->getLink() != '' && trim($this->getMediaUrl()) == '') {
                $this->text.= " http://twitter.com/" . $this->getUsername() . "/status/" . $this->getSocialId();    
            }
            return $this->twitterUtilObj->parseText($this->text, $textLength);
        } elseif ($this->getSource() == 'instagram') {
            return ($this->instagramUtilObj->parseText($this->text, $textLength));
        } elseif ($this->getSource() == 'facebook') {
            return $this->facebookUtilObj->parseText($this->text, $textLength);
        } else if ($this->getSource() == 'yelp') {
            $text = $this->text . " " . str_replace("www.", "", $this->getLink());
            return $this->twitterUtilObj->parseText($text, 1000);
        } 
        return $this->text;
    }

    public function getAge()
    {
        return $this->twitterUtilObj->getAge($this->getDateCreated());
    }
    
    
    public function getHoursOld()
    {
        return $this->twitterUtilObj->getHoursOld($this->getDateCreated());
    }
    
    public function getDaysOld()
    {
        return $this->twitterUtilObj->getDaysOld($this->getDateCreated());
    }
    
    public function getMonthDay() 
    {
        return $this->twitterUtilObj->getMonthDay($this->getDateCreated());
    }
    
    
}