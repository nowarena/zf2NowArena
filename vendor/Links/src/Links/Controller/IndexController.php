<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Links for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Links\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Links\Form\LinkAddForm;
use Links\Model\LinkEntity;

class IndexController extends AbstractActionController
{

    public function checkPermission()
    {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            return true;
        }
        header("location: /user");
        exit;
        
    }
    
    private function setJqueryUI()
    {
        $headScript = $this->serviceLocator->get('viewhelpermanager')->get('headScript');
        $headScript->prependFile("/jquery-ui/jquery-ui.min.js", $type = 'text/javascript', $attrs = array());
        $headScript->prependFile("/jquery-ui/external/jquery/jquery.js", $type = 'text/javascript', $attrs = array());
    }
    
    private function setJqueryCSS()
    {
        $headLink = $this->serviceLocator->get('viewhelpermanager')->get('headLink');
        $headLink->appendStylesheet('/jquery-ui/jquery-ui.min.css');
        $headLink->appendStylesheet('/css/main.css');
    }
    
    public function addlinksAction()
    {

        $form = new LinkAddForm();
        $linkEnt = new LinkEntity();
        $form->bind($linkEnt);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$form->setData($request->getPost());
        	if ($form->isValid()) {
        		$this->getServiceLocator()->get('Links\Model\LinkMapper')->save($linkEnt);
        		$msg = 'Added!';
        	} else {
        	    $msg = 'Errors!';
        	    printR($form->getMessages());exit;
        	    $msg.= implode("<br>", $form->getMessages());
        	}
       		$this->flashMessenger()->addMessage($msg);
        }
      
        $this->redirect()->toRoute('links', array("action"=>"index"));
        
    }
    
    public function indexAction()
    {
        
        $this->checkPermission();
        $this->setJqueryUI();
        $this->setJqueryCSS();
        $form = new \Links\Form\CreateLinksForm();
        $linkModel = new \Links\Model\Links();
        $linkEntities = $this->getServiceLocator()->get('Links\Model\LinkMapper')->getLinks();

        if ($this->request->isPost()) {

            $form->bind($linkModel);
        	$form->setData($this->request->getPost());
        
        	if ($form->isValid()) {
    	    	$this->getServiceLocator()->get('Links\Model\LinkMapper')->saveLinks($linkModel);
    	    	$this->flashMessenger()->addMessage('Updated!');
    	    	return $this->redirect()->toRoute('links', array('action'=>'index'));
        	} else {
        	   $msg = '';
        	   $arr = $form->getMessages();
        	   foreach($arr as $key => $err) {
        	       $msg.= "<br>" . $err;   
        	   }
        	   $this->flashMessenger()->addMessage("Errors! $msg");
        	}
        	
        } else {
            
            $linkModel->setLinks($linkEntities);
            $form->bind($linkModel);
            
        }
        
        return array(
        	'form' => $form,
            'formadd' => new \Links\Form\LinkAddForm()
        );
 
    }
    
    public function sortlinksAction()
    {
        
        $this->setJqueryUI();
        $this->setJqueryCSS();
        
        $linkEntities = $this->getServiceLocator()->get('Links\Model\LinkMapper')->getLinks();
                
        return array(
            'linkEnt' => $linkEntities
        );
        
        
    }
    
    public function linkSortAction()
    {
        
    	$this->checkPermission();
    	$linkArr = $this->params()->fromPost('link');
    	if (is_array($linkArr) && count($linkArr) >0 ) {
    		$this->getServiceLocator()->get('Links\Model\LinkMapper')->updateLinkOrder($linkArr);
    	}
    	return $this->response;
    }
    
    public function deleteLinkAction()
    {
    
    	$this->checkPermission();
    	$id = (int)$this->params('id');
    	if ($id) {
    	   $this->getServiceLocator()->get('Links\Model\LinkMapper')->deleteLink($id);
    	   $this->flashMessenger()->addMessage('Deleted!');
    	}
   
    	return $this->redirect()->toRoute('links', array('action'=>'index'));
    	 
    }
    
}
