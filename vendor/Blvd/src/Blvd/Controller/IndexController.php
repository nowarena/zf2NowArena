<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Home for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blvd\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use TwitterBlvd\Model\TwitterBlvd;
use TwitterBlvd\Model\TwitterUsersMapper;
use Blvd\Model\BlvdMapper;
use Blvd\Model\Blvd;
use Blvd\Model\Social;
use Yelp\Model\YelpMapper;
use Yelp\Model\YelpBlvd;
use Utility\Model\Utility;
use Base\Controller\AbstractBaseController;
//use Facebook\Model\FacebookBlvd;

class IndexController  extends AbstractBaseController //extends AbstractActionController
{
    
    public function indexAction()
    {


        // confirm dialogue for unpub etc
        // 'dialog' method becomes undefined if i don't include jquery-ui themes
        $this->setJqueryUI();
        
        $categoryId = (int)$this->params('category_id');
        $blvdUsersEnt = array();
        $viewModel = new ViewModel();
        $viewModel->setTemplate('blvd/index/index.phtml');
        if ($categoryId) {
            $viewModel->setTemplate('blvd/index/category.phtml');
            $blvdUsersEnt = $this->getBlvdMapper()->getAllBlvd($categoryId);
        } else {
            // get the most recent media for each category
            $blvdUsersEnt = $this->getBlvdMapper()->getRecentBlvd($this->getNumCols());
        }

        $isWebmaster = $this->zfcUserAuthentication()->hasIdentity();
        
        $viewModel->setVariables(array(
            "blvdUsersEnt" => $blvdUsersEnt,
            'isWebmaster' => $isWebmaster, 
            'categoryTopArr' => $this->getServiceLocator()->get('Blvd\Model\BlvdCategoryMapper')->fetchArr(1),
            'categoryBottomArr' => $this->getServiceLocator()->get('Blvd\Model\BlvdCategoryMapper')->fetchArr(0,1),
            'categoryId' => $categoryId,
            'isMobile' => $this->getIsMobile(),
            'linkArr' => $this->getServiceLocator()->get('Links\Model\LinkMapper')->getLinkArr(),
        ));
        
        return $viewModel;
        
    }
    
    public function nextBizAction()
    {

        $catId = (int)$this->params('category_id');
        $offset = (int)$this->params('offset');
        $limit = $this->getNumCols();
        $blvdUsersEnt = array();
        $socialMediaEnt = array();
        $blvdId = 0;
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        if ($catId) {
            if ($blvdUsersEnt = $this->getBlvdMapper()->getRecentCategory($catId, $offset, $limit)) {
                // todo : another way to get the blvdId
                foreach($blvdUsersEnt as $key => $ent) {
                    $blvdId = $ent->getId();
                    break;
                }
            } else {
                // return 'refresh' icon that when clicked sets 'Next' Category offsetnext to 0 and then triggers Next link
                // if category  is empty and offset is greater than zero, end of feed reached
                $viewModel->setVariable("category_id", $catId);
                return $viewModel->setTemplate('blvd/index/reloadcategory.phtml');
            }
            // always starting at 0 for social media
            $socialMediaEnt = $this->getSocialMedia($blvdId, 0);
        }
        
        return $viewModel->setVariables(array(
				'blvdUsersEnt' => $blvdUsersEnt,
				'socialMediaEnt' => $socialMediaEnt,
				'isWebmaster' => $this->zfcUserAuthentication()->hasIdentity()
        	)
        );
        
    }
 
    public function socialMediaAction()
    {

        $blvdId = (int)$this->params('id');
        $offset = (int)$this->params('offset');
        $limit = $this->getIsMobile() ? 1 : 2;
        if ($blvdId) {
            $socialMediaEnt = $this->getSocialMedia($blvdId, $offset);
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $viewModel->setVariables(array(
            	'socialMediaEnt' => $socialMediaEnt,
				'isWebmaster' => $this->zfcUserAuthentication()->hasIdentity()
            ));
            return $viewModel;
            
        }

        return false;
        
    }
    
    private function getSocialMedia($blvdId, $offset)
    {
        return $socialMediaEnt = $this->getSocialMediaMapper()->fetchAllSocialMedia($blvdId, $offset, $this->getNumCols());
    }
    
    private function setJqueryUI()
    {
        $headScript = $this->serviceLocator->get('viewhelpermanager')->get('headScript');
//        $headScript->prependFile("//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js", $type = 'text/javascript', $attrs = array());
        $headScript->prependFile("/jquery-ui/jquery-ui.min.js", $type = 'text/javascript', $attrs = array());
        $headScript->prependFile("/jquery-ui/external/jquery/jquery.js", $type = 'text/javascript', $attrs = array());
        $headScript->prependFile("/js/socialblvd.js", $type = 'text/javascript', $attrs = array());
   
        
        $headLink = $this->serviceLocator->get('viewhelpermanager')->get('headLink');
        $headLink->appendStylesheet('/jquery-ui/jquery-ui.theme.min.css');
        $headLink->appendStylesheet('/jquery-ui/jquery-ui.structure.min.css');
        $headLink->appendStylesheet('/css/socialblvd.css');
        //$headLink->appendStylesheet('//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css');
        $headLink->appendStylesheet('/css/main.css');
        
    }

   /* 
    public function getFacebookBlvd()
    {
    	return $this->getServiceLocator()->get('Facebook\Model\FacebookBlvd');
    }
    */
    
    public function getSocialMediaMapper()
    {
    	return $this->getServiceLocator()->get('Blvd\Model\SocialMediaMapper');
    }
    
    public function getInstagramUsersMapper()
    {
        return $this->getServiceLocator()->get('Instagram\Model\InstagramUsersMapper');
    }
    public function getInstagramMapper()
    {
        return $this->getServiceLocator()->get('Instagram\Model\InstagramMapper');
    }

    public function getTumblrMapper()
    {
        return $this->getServiceLocator()->get('Tumblr\Model\TumblrMapper');
    }
    public function getTwitterUsersMapper()
    {
        return $this->getServiceLocator()->get('TwitterBlvd\Model\TwitterUserMapper');
    }
    
    public function getBlvdMapper()
    {
    	return $this->getServiceLocator()->get('Blvd\Model\BlvdMapper');
    }
    
    public function getTweetMapper()
    {
    	return $this->getServiceLocator()->get('TwitterBlvd\Model\TweetMapper');
    }
 
}
