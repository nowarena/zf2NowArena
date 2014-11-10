<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Twitter for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TwitterBlvd\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use TwitterBlvd\Model\TwitterBlvd;
use TwitterBlvd\Form\TwitterForm;
use TwitterBlvd\Model\TwitterUserEntity;
use TwitterBlvd\Model\TweetEntity;
use Utility\Model\Utility;
use Zend\View\Model\ViewModel;
use Facebook\Model\FacebookBlvd;

class TwitterController extends AbstractActionController
{
    
    protected $twitterUsersTable;
    
    public function scrapeAction()
    {


        
    }
    
    public function checkPermission()
    {

        if ($this->zfcUserAuthentication()->hasIdentity()) {
            return true;
        }
        header("location: /user");
        exit;        
    
    }
    
    public function addToBlvdAction()
    {
        
        $this->checkPermission();
        $screenName = $this->params('screenname');
        if (!$screenName) {
        	return $this->redirect()->toRoute('blvd', array('action'=>'admin'));
        }
        if ($this->getBlvdMapper()->getBlvdWithTwitterUsername($screenName)) {
      		$this->flashMessenger()->addMessage("$screenName is already added to the blvd.");
        	return $this->redirect()->toRoute('twitter', array('action'=>'list'));
        }
        $twitterUser = $this->getTwitterUserMapper()->fetchTwitterUser($screenName);
        $blvdEnt = $this->getBlvdMapper()->saveTwitterUserToBlvd($twitterUser); 
   		$this->flashMessenger()->addMessage("$screenName now added to the blvd.");
       	return $this->redirect()->toRoute('blvd', array('action'=>'edit', 'id' => $blvdEnt->getId()));
        
    }
    
    public function indexAction()
    {

        $this->twittercronAction(); 
        return false;   
    }
    
    public function twittercronAction()
    {
    	//http://www.idfromuser.com/ get twitter id from user name
        
        $utilObj = new Utility('twitter');
        $this->twitterBlvd = $this->serviceLocator->get('TwitterBlvd\Model\TwitterBlvd');
        
        $userIdArr = $this->twitterBlvd->getTwitterFriendIds($this->twitterBlvd->getUserId());
        // add site's twitter account to id array
        $userIdArr[] = $this->twitterBlvd->getUserId();
        
        // to update/test single users, use their twitter_id here
        //$userIdArr=array(11039532);
        
        $twitterUsersArr = $this->twitterBlvd->getTwitterFriends($userIdArr);
        foreach($twitterUsersArr as $key => $obj) {
            $userEnt = $this->getServiceLocator()->get('TwitterBlvd\Model\TwitterUserMapper')->formatTwitterUser($obj);
            $userEnt = $this->getServiceLocator()->get('TwitterBlvd\Model\TwitterUserMapper')->saveTwitterUser($userEnt);
            if ($tweetEnt = $this->getServiceLocator()->get('TwitterBlvd\Model\TweetMapper')->formatTweet($obj, $userEnt)) {
                $tweetEnt->setTweet($utilObj->cleanText($tweetEnt->getTweet()));
                $this->getServiceLocator()->get('TwitterBlvd\Model\TweetMapper')->saveTweet($tweetEnt);
            }
        }

        return false;
        
    }

    
    public function editUserAction()
    {
        
        $this->setCSS();
        $this->checkPermission();   
        $screenName = $this->params('screenname');
        if (!$screenName) {
        	return $this->redirect()->toRoute('blvd', array('action'=>'admin'));
        }
        $twitterUser = $this->getTwitterUserMapper()->fetchTwitterUser($screenName);
        
        if (!$twitterUser) {
      		$this->flashMessenger()->addMessage("Not finding $screenName");
       		return $this->redirect()->toRoute('blvd', array('action'=>'admin'));
        }

        $form = new TwitterForm();
        $form->bind($twitterUser);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$form->setData($request->getPost());
        	if ($form->isValid()) {
        		$this->getTwitterUserMapper()->saveTwitterUser($twitterUser, true);
        		$this->flashMessenger()->addMessage('Updated!');
        		return $this->redirect()->toRoute('twitter', array('action'=>'edituser', 'screenname' => $screenName));
        	}
        }
        
        $headLink = $this->serviceLocator->get('viewhelpermanager')->get('headLink');
        $headLink->appendStylesheet('/css/edit.css');

        $blvdArr = \Zend\Stdlib\ArrayUtils::iteratorToArray($this->serviceLocator->get('Blvd\Model\BlvdMapper')->getBlvdWithTwitterUsername($screenName));
        
        return array(
        	'screenname' => $screenName,
        	'form' => $form,
        	'action' => 'edituser',
            'blvdArr' => $blvdArr[0]
        );
    }

    public function deleteAction()
    {
    
    	$this->checkPermission();
    	$screenname = $this->params('screenname');
    	if ($screenname) {
    		$this->getServiceLocator()->get('TwitterBlvd\Model\TwitterUserMapper')->deleteUser($screenname);
    		$this->getServiceLocator()->get('TwitterBlvd\Model\TweetMapper')->deleteTweets($screenname);
    		
    		$this->flashMessenger()->addMessage('Deleted!');
    	}
    	return $this->redirect()->toRoute('twitter', array('action'=>'list'));
    
    }
    
    public function listAction()
    {
        
        $this->setCSS();
        $this->checkPermission();   
    	return new ViewModel(array(
   			'list' => $this->getTwitterUserMapper()->fetchAll(),
    	));
    }
    
    
    private function setCSS()
    {
        $headLink = $this->serviceLocator->get('viewhelpermanager')->get('headLink');
        $headLink->appendStylesheet('/jquery-ui/jquery-ui.min.css');
        $headLink->appendStylesheet('/css/main.css');
    }
    
    private function getBlvdMapper() {
        return $this->getServiceLocator()->get('Blvd\Model\BlvdMapper');
    }
    
    private function getTwitterUserMapper() {
        return $this->getServiceLocator()->get('TwitterBlvd\Model\TwitterUserMapper');
    }
     
}
