<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Instagram for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Instagram\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Instagram\Model\InstagramBlvd;
use Instagram\Model\InstagramModel;
use Instagram\Model\InstagramEntity;
use Instagram\Model\InstagramUsersEntity;
use Twitter\Model\TwitterMapper;
use Blvd\Model\BlvdMapper;
use Utility\Model\Utility;

/*
 * The easiest way to get a valid access_token is to navigate to:

https://instagram.com/oauth/authorize/?client_id=ec5e025553c04e068abacb941c95e7f7&redirect_uri=http://slimandbusty.com/oauth_redirect_url&response_type=token

http://symmetricinfinity.com/2013/04/06/download-your-likes-from-instagram.html
*/


class InstagramController extends AbstractActionController
{
    
    public function getLikesAction()
    {

        $response = $this->getInstagramModel()->getLikes();
        $entArr = $this->getInstagramModel()->formatResponse($response);
        foreach($entArr as $ent) {
            printR($ent);
            $ent = $this->getInstagramModel()->formatGalleryEnt($ent);
            $this->getGalleryMapper()->insertEnt($ent);
        }
        
        return false; 
        
        
    }

    public function instagramcronAction()
    {
           
        $disabledArr = $this->getBlvdMapper()->getInstagramDisabledArr();
        $response = $this->getInstagramModel()->fetchFeed();
        $entArr = $this->getInstagramModel()->formatResponse($response);

        foreach($entArr as $ent) {
            if (in_array(strtolower($ent->getUsername()), $disabledArr)) {
                continue;
            }
            printR($ent);
            $blvdId = $this->getBlvdMapper()->getBlvdIdWithSocialUsername($ent->getUsername(), 'instagram');
            if (!$blvdId) {
                echo $ent->getUsername() . " did not return a blvdId \n";
                continue;
            }
            $ent->setBlvdId($blvdId);
            $ent = $this->getInstagramModel()->formatSocialMediaEnt($ent);
            $this->getSocialMediaMapper()->insertSocialMedia($ent);
        }
        
        return false; 
    }
    

    private function getBlvdMapper()
    {
        return $this->getServiceLocator()->get('Blvd\Model\BlvdMapper');
    }
    private function getSocialMediaMapper() {
        return $this->getServiceLocator()->get('Blvd\Model\SocialMediaMapper');
    }
    
    private function getGalleryMapper() {
        return $this->getServiceLocator()->get('Gallery\Model\GalleryMapper');
    }

    private function getInstagramModel()
    {
        return $this->getServiceLocator()->get('Instagram\Model\InstagramModel');
    }

}
