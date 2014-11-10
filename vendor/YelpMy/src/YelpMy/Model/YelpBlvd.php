<?php
namespace YelpMy\Model;

use Utility\Model\Utility;

class YelpBlvd
{

    private $consumerKey;
    private $consumerSecret;
    private $token;
    private $tokenSecret;
    
    public function __construct(array $configArr)
    {

        $this->consumer_key = $configArr['consumer_key'];
        $this->consumer_secret = $configArr['consumer_secret'];
        $this->token = $configArr['token'];
        $this->token_secret = $configArr['token_secret'];
        
    }
	
	public function fetchBiz($biz)
	{
	    $unsigned_url = "http://api.yelp.com/v2/business/" . $biz;
	    return $this->fetchData($unsigned_url);
	    
	}
	
	public function search() 
	{
	    //http://www.yelp.ca/search?find_desc=abbot+kinney+blvd&find_loc=90291&ns=1#find_desc=abbot+kinney+
        for($i=0; $i<=120; $i+=20) {
	       $url = "http://api.yelp.com/v2/search?location=90291&term=abbot+kinney&limit=20&offset=$i";
	       $arr[] = $this->fetchData($url); 
        }
        return $arr;
	}
	
	public function fetchData($url) 
	{

	    require_once ('vendor/yelp/OAuth.php');
        
        // Token object built using the OAuth library
        $token = new \Yelp\OAuthToken($this->token, $this->token_secret);
        
        // Consumer object built using the OAuth library
        $consumer = new \Yelp\OAuthConsumer($this->consumer_key, $this->consumer_secret);
        
        // Yelp uses HMAC SHA1 encoding
        $signature_method = new \Yelp\OAuthSignatureMethod_HMAC_SHA1();
        
        // Build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
        $oauthrequest = \Yelp\OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $url);
        
        // Sign the request
        $oauthrequest->sign_request($signature_method, $consumer, $token);
        
        // Get the signed URL
        $signed_url = $oauthrequest->to_url();
        
        $utility = new Utility();
        return $utility->fetchUrlAndJsonDecode($signed_url);
        
	}
}