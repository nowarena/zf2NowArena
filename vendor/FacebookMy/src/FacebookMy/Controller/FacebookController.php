<?php
namespace FacebookMy\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use FacebookMy\Model\FacebookBlvd;
use Blvd\Model\BlvdMapper;
use Utility\Model\Utility;

class FacebookController extends AbstractActionController
{
    
    public function __construct()
    {
        $this->utility = new Utility();
    }
    
    public function indexAction()
    {
        $this->facebookpagescronAction();
        return false;
    }

    public function gentokenAction()
    {
//    	exit();
        //Retrieve access token
        $util = new Utility();
        $app_id ='695092280570823';
        $app_secret = '92a7b185ac724e3da04c7cb9cfce5805';
        $access_token = $util->fetchUrl("https://graph.facebook.com/oauth/access_token?grant_type=client_credentials&client_id={$app_id}&client_secret={$app_secret}");
        exit($access_token);
    }
        
    public function facebookpagescronAction()
    {

         
        
        $facebookBlvd = $this->serviceLocator->get('Facebook\Model\FacebookBlvd');
        $configArr = $this->getServiceLocator()->get('config');
        $accessToken = $configArr['facebook']['accessToken'];
        

        $arr = $this->getBlvdMapper()->fetchBlvdWithFacebookPages();
        foreach($arr as $key => $blvdObj) {
            //if ($blvdObj->getFaceBook() != 'williejanerestaurant')continue;
            //$url = 'https://graph.facebook.com/v2.0/' . $blvdObj->getFacebook() . "/feed?access_token=" . $accessToken;
            $since = time() - (86400 * 3);
            $url = 'https://graph.facebook.com/v2.0/' . $blvdObj->getFacebook() . "/posts?limit=3&since=$since&access_token=" . $accessToken;
            $json_object = $this->utility->fetchUrlAndJsonDecode($url);
            foreach($json_object as $key => $arr) {
                foreach($arr as $obj) {
                    if (!is_object($obj)){
                        continue;
                    }
                    unset($obj->likes);
                    unset($obj->comments);
                    printR($obj);
                    if ($fbEnt = $facebookBlvd->formatFacebookPagePost($obj, $blvdObj->getFacebook())) {
                        printR($fbEnt);
                        //$this->getServiceLocator()->get('Facebook\Model\FacebookMapper')->saveFacebookPagePost($fbEnt);
                        $socEnt = $this->getServiceLocator()->get("Blvd\Model\SocialMediaMapper")->saveFacebook($fbEnt, $blvdObj->getId());
                        $this->getBlvdMapper()->updateBlvdWithSocialMediaDatetime($socEnt);
                    }
                }
            }
        }     
         
        return false; 
        
    }

        public function getBlvdMapper()
        {
        	$sm = $this->getServiceLocator();
        	$this->blvdTable = $sm->get('Blvd\Model\BlvdMapper');
        	return $this->blvdTable;
        }        
}
