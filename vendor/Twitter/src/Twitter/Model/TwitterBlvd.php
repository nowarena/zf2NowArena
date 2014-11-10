<?php

namespace Twitter\Model;

use ZendService\Twitter\Twitter;
use Twitter\Model\TweetEntity;


/**
 * Class for extending twitter service and operating on response data from twitter
 * @author matt
 *
 */
class TwitterBlvd extends Twitter
{

    protected $id;
    protected $screen_name;
    
    public function __construct(array $configArr)
    {
        if (count($configArr) == 0) {
        // throw exception    
        }
        parent::__construct($configArr);
        $this->configArr = $configArr;
    }
    


    // the site's twitter id
    public function setUserId($val)
    {
        $this->id = $val; 
    }
    
    // the site's twitter id
    public function getUserId()
    {
        return $this->id;
    }
    
    public function getScreenName()
    {
    	return $this->screen_name;
    }
    
    public function setScreenName($val)
    {
    	$this->screen_name = $val;
    	return $this;
    }
    
    public function getFavorites($userId)
    {

        $options = $this->getOptions($userId);
        $response = $this->favoritesList();
        return $response;
        
    }
    
    private function getOptions($userId)
    {
        
    	if (is_numeric($userId)) {
    		$options = array('user_id' => $userId);
    	} else {
    		$options = array('screen_name' => $userId);
    	}
        return $options;
                
    }
   
    /**
     * Return list of twitter users that a user_id follows
     *
     * @param Integer $userId
     * return array
     */
    public function getTwitterFriendIds($userId)
    {
        
        $options = $this->getOptions($userId);

    	$response = $this->friendshipsList($options);
    	if (!$response->isSuccess()) {
    		$arr = ($response->getErrors());
    		echo $arr[0]->message."<br>";
    		die('A.) Something is wrong with my credentials!');
    	}
    
    	return $response->ids;
    
    }
    
    /**
     * Return data for each user_id
     * @param array $userIdArr
     * @return array
     */
    public function getTwitterFriends(array $userIdArr)
    {
    	$limit = 100;
    	$options = array();
    	$userArr = array();
    	for($i = 0; $i < 1000; $i+=$limit) {
    		$slicedArr = array_slice($userIdArr, $i, $limit);
    		if (count($slicedArr) == 0) {
    			break;
    		}
    		
    		$optionsArr['user_ids'] = $slicedArr;
    		$optionsArr['include_entities'] = 1;
    		$optionsArr['include_rts'] = 0;
    		$optionsArr['exclude_replies'] = 1;
    		$optionsArr['result_type'] = 'recent';
    		$response = $this->friendshipsLookup($optionsArr);
    		if (!$response->isSuccess()) {
    			$arr = ($response->getErrors());
    			echo $arr[0]->message."<br>";
    			die('B.) Something is wrong with my credentials!');
    		}
    		$userArr = array_merge($userArr, $response->toValue());

    	}
    printR($userArr);	
    	return $userArr;
    
    }
    
    public function getTwitterHomeTimeline()
    {

    	$options = array();
        //$optionsArr['user_ids'] = array($userId);
        $optionsArr['include_entities'] = 1;
        $optionsArr['include_rts'] = 0;
        $optionsArr['exclude_replies'] = 1;
        //$optionsArr['result_type'] = 'recent';
        $optionsArr['count'] = 100;
        // get recent 100 tweets appear on my home twitter page
        $response = $this->statusesHomeTimeline($optionsArr);
        if (!$response->isSuccess()) {
            $arr = ($response->getErrors());
            echo $arr[0]->message."<br>";
            die('B.) Something is wrong with my credentials!');
        }
        return $response->toValue();

    }
    
 

    public function getSmallMediaWidth(TweetEntity $ent)
    {
    
    	$targetWidth = 150;
    	if ($ent->getMediaWidth() == 0) {
    		return 0;
    	}
    	if ($ent->getMediaWidth() <= $targetWidth) {
    		return $ent->getMediaWidth();
    	}
    	$perc = $targetWidth/$ent->getMediaWidth();
    	if ($ent->getMediaHeight() * $perc <= $targetWidth) {
    		return $targetWidth;
    	} else {
    		$perc = $targetWidth/$ent->getMediaHeight();
    		return ceil($perc * $ent->getMediaWidth());
    	}
    }
    
    public function getSmallMediaHeight(TweetEntity $ent)
    {
 
    	$targetHeight = 150;
    	if ($ent->getMediaHeight() == 0) {
    		return 0;
    	}
    	if ($ent->getMediaHeight() <= $targetHeight) {
    		return $ent->getMediaHeight();
    	}
    	$perc = $targetHeight/$ent->getMediaHeight();
    	if ($ent->getMediaWidth() * $perc <= $targetHeight) {
    		return $targetHeight;
    	} else {
    		$perc = $targetHeight/$ent->getMediaWidth();
    		return ceil($perc * $ent->getMediaHeight());
    	}
    }
    
}