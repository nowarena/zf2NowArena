<?php
namespace FacebookMy\Model;

use FacebookMy\Model\FacebookEntity;
use FacebookMy\Model\FacebookMapper;
use Utility\Model\Utility;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FacebookBlvd implements ServiceLocatorAwareInterface
{
    
    protected $appId;
    protected $appSecret;
    protected $isWebmasterArr = array();
    
    public function __construct()
    {
        $this->utilityObj = new Utility();
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
    	$this->serviceLocator = $serviceLocator;
    }
    
    public function getServiceLocator()
    {
    	return $this->serviceLocator;
    }
    
    public function setAppId($appId)
    {
        $this->appId = $appId;
    }
    
    public function getAppId()
    {
        return $this->appId;
    }
    
    public function setAppSecret($appSecret)
    {
    	$this->appSecret = $appSecret;
    }
    
    public function getAppSecret()
    {
    	return $this->appSecret;
    }  
    
    
    public function formatFacebookPagePost($o, $username = '')
    {
        if (!($username)) {
           //throw exception
           return false; 
        }

        // sends empty data at end
        if (!isset($o->id)) {
            return false;
        }
        
        if (!isset($o->message) || $o->message == '') {
            return false;
        }

        $link = isset($o->link) ? $o->link : '';
        $name = isset($o->name) ? $o->name : '';
        $name = $this->utilityObj->cleanText($name);
        $description = isset($o->description) ? $this->utilityObj->cleanText($o->description) : '';
        $message = isset($o->message) ? $this->utilityObj->cleanText($o->message) : '';
        $picture = isset($o->picture) ? $o->picture : '';
        $mediaHeight = '';
        $mediaWidth = '';
        
        // get pic dimensions
        if ($picture) {
            $arr = $this->utilityObj->getImageDims($picture);
            if (isset($arr[1])) {
                $mediaWidth = $arr[0];
                $mediaHeight = $arr[1];
            }
        }

        // usually a sign of customer's posting
        if ($link == '' && $picture =='') {
            return false;
        }
        
        $ut = strtotime($o->created_time);
        $created_time = date("Y-m-d H:i:s", $ut);
        
        $fbEnt = new FacebookEntity();
        $fbEnt->setPostId($o->id)
            ->setUsername($username)
            ->setMessage($message)
            ->setPicture($picture)
            ->setLink($link)
            ->setMediaHeight($mediaHeight)
            ->setMediaWidth($mediaWidth)
            ->setMessageName($name)
            ->setDescription($description)
            ->setCreatedTime($created_time);
        return $fbEnt; 
    }
    
    /////
    // Taken from facebook sdk
    public function parseSignedRequest($signedRequest, $state)
    {
    
    	if (strpos($signedRequest, '.') !== false) {
    		list($encodedSig, $encodedData) = explode('.', $signedRequest, 2);
    		$sig = self::_base64UrlDecode($encodedSig);
    		$data = json_decode(self::_base64UrlDecode($encodedData), true);
    		if (isset($data['algorithm']) && $data['algorithm'] === 'HMAC-SHA256') {
    			$expectedSig = hash_hmac( 'sha256', $encodedData, $this->getAppSecret(), true);
    			if (strlen($sig) !== strlen($expectedSig)) {
    				throw new \Exception( 'Invalid signature on signed request.', 602);
    			}
    			$validate = 0;
    			for ($i = 0; $i < strlen($sig); $i++) {
    				$validate |= ord($expectedSig[$i]) ^ ord($sig[$i]);
    			}
    			if ($validate !== 0) {
    				throw new \Exception( 'Invalid signature on signed request.', 602);
    			}
    			if (!isset($data['oauth_token']) && !isset($data['code'])) {
    				throw new \Exception( 'Invalid signed request, missing OAuth data.', 603);
    			}
    			if ($state && (!isset($data['state']) || $data['state'] != $state)) {
    				throw new \Exception( 'Signed request did not pass CSRF validation.', 604);
    			}
    			return $data;
    		} else {
    			throw new \Exception( 'Invalid signed request, using wrong algorithm.', 605);
    		}
    	} else {
    		throw new \Exception( 'Malformed signed request.', 606);
    	}
    
    }
    
    public static function _base64UrlDecode($input) {
    	return base64_decode(strtr($input, '-_', '+/'));
    }
    
    public function isWebmaster()
    {
    	$str = 'fbsr_' . $this->getAppId();
        if (!isset($_COOKIE[$str])) {
            return false;
        }
        
        if (isset($this->isWebmasterArr[$this->getAppId()]) && $this->isWebmasterArr[$this->getAppId()] == 1) {
        	return true;
        }
        
        try{
        	
            $r = $this->parseSignedRequest($_COOKIE['fbsr_' . $this->getAppId()], false);
            $userId = $r['user_id'];
            $bool = $this->getServiceLocator()->get('Facebook\Model\FacebookUserMapper')->isWebmaster($userId);
            $this->isWebmasterArr[$this->getAppId()] = $bool;
            return $bool;
            
        } catch (\Exception $e) {
        	
        	echo $e->getMEssage()."<Br>";
            return false;
            
        }
    }
    
    //
    //////////////////

}

