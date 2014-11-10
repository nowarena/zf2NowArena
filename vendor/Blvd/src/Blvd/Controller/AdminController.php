<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Blvd for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Blvd\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Blvd\Model\BlvdMapper;
use Blvd\Model\BlvdCategoryMapper;
use Blvd\Model\BlvdEntity;
use Blvd\Model\BlvdCategoryEntity;
use Blvd\Model\Blvd;
use Zend\View\Model\ViewModel;
use Blvd\Form\BlvdForm;
use Blvd\Form\BlvdCategoryForm;
use Blvd\Form\BlvdJoinCategoryForm;
use Facebook\Model\FacebookBlvd;
use Blvd\Model\BlvdJoinCategory;
use Zend\View\Model\JsonModel;

class AdminController extends AbstractActionController
{
    
    protected $blvdTable;
    
    public function __construct(){
        
    }
    
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
    
    private function setCSS()
    {
        $headLink = $this->serviceLocator->get('viewhelpermanager')->get('headLink');
        $headLink->appendStylesheet('/jquery-ui/jquery-ui.min.css');
        $headLink->appendStylesheet('/css/main.css');
        $headLink->appendStylesheet('/css/edit.css');
    }

    public function adminCategoriesAction()
    {
        
        $this->checkPermission(); 
        
        $form = new BlvdCategoryForm();
        $blvd = new BlvdCategoryEntity();
        $form->bind($blvd);
        
        $this->setJqueryUI();
        $this->setCSS();
    	return new ViewModel(array(
    		'blvdCatTop' => $this->getBlvdCategoryMapper()->fetchAll(1,0),
    		'blvdCatBottom' => $this->getBlvdCategoryMapper()->fetchAll(0,1),
    		'blvdCatDisabled' => $this->getBlvdCategoryMapper()->fetchAll(0,0,1),
    		'categoryTopArr' => $this->getServiceLocator()->get('Blvd\Model\BlvdCategoryMapper')->fetchArr(1),
    		'categoryBottomArr' => $this->getServiceLocator()->get('Blvd\Model\BlvdCategoryMapper')->fetchArr(0,1),
    	    'form' => $form,
    	    'id' => 0,
    	    'action' => 'addcategory'
    	));
    }
    
    /**
     * Update categories with posted sort order
     * @return \Zend\Stdlib\ResponseInterface
     */
    public function categorySortAction()
    {
        $this->checkPermission(); 
        $catTopArr = $this->params()->fromPost('cattop');
        $catBottomArr = $this->params()->fromPost('catbottom');
        if (is_array($catTopArr) && count($catTopArr) >0 ) { 
            $this->getBlvdCategoryMapper()->updateCategoryTopOrder($catTopArr);  
        } elseif (is_array($catBottomArr) && count($catBottomArr) > 0) {
            $this->getBlvdCategoryMapper()->updateCategoryBottomOrder($catBottomArr);  
        }
        return $this->response;
    }
     
    public function editCategoryAction()
    {
        $this->checkPermission(); 
        $id = (int)$this->params('id');
        if (!$id) {
            return $this->redirect()->toRoute('blvd', array('action'=>'admincategories'));
        }
        $blvdCat = $this->getBlvdCategoryMapper()->fetchCategory($id);
        $formCat = new BlvdCategoryForm();
        $formCat->bind($blvdCat);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $formCat->setData($request->getPost());
            if ($formCat->isValid()) {
                $this->getBlvdCategoryMapper()->save($blvdCat);
        		$this->flashMessenger()->addMessage('Updated!');
        		return $this->redirect()->toRoute('blvd', array('action'=>'admincategories'));
            }
        }
        
        return array(
            'id' => $id,
        	'form' => $formCat,
            'action' => 'editcategory'
        );
        
    }
    
    public function deleteCategoryAction()
    {
        
        $this->checkPermission(); 
    	$id = (int)$this->params('id');
    	if (!$id) {
    		return $this->redirect()->toRoute('blvd', array('action'=>'admincategories'));
    	}
    	$this->getBlvdCategoryMapper()->deleteCategory($id);
		return $this->redirect()->toRoute('blvd', array('action'=>'admincategories'));
    	
    }
    
    public function deleteFromBlvdAction()
    {
        
        $this->checkPermission(); 
    	$id = (int)$this->params('id');
    	if (!$id) {
    		return $this->redirect()->toRoute('blvd', array('action'=>'list'));
    	}
    	$this->getBlvdMapper()->deleteFromBlvd($id);
		$this->flashMessenger()->addMessage('Removed from the blvd.');
		return $this->redirect()->toRoute('blvd', array('action'=>'list'));
    	
    }
    
    private function getBlvdJoinCategoryForm($blvdId) 
    {
        
    	$catArr = $this->getBlvdCategoryMapper()->fetchAllArr();
    	$arr = $this->getBlvdJoinCategoryMapper()->fetchBlvdCategories($blvdId);
    	$catForm = new BlvdJoinCategoryForm('catform', $catArr, array_keys($arr), $blvdId, array_keys($arr, 1));
    	return $catForm;
    	
    }

    public function joinCategoryAction()
    {
        $this->checkPermission(); 
    	$blvdId = (int)$this->params('id');
    	if (!$blvdId) {
    		return $this->redirect()->toRoute('blvd', array('action'=>'list'));
    	}
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    	    $blvdJoinCat = new BlvdJoinCategory();
    	    $form = $this->getBlvdJoinCategoryForm($blvdId);
    	    $form->setInputFilter($blvdJoinCat->getInputFilter());
    	    $form->setData($request->getPost());
    	    if ($form->isValid()) {
    	        $blvdJoinCat->exchangeArray($form->getData());
    	        $this->getBlvdJoinCategoryTable()->saveBlvdJoinCategories($blvdJoinCat, $blvdId);
    	        $msg = 'Updated!';
    	    } else {
    	        $arr = $form->getMessages();
    	        foreach($arr as $err) {
    	            $msg.=$err."<br>";
    	        }
    	    } 
    	}
		$this->flashMessenger()->addMessage($msg);
		header("location: /admin/list/" . "#$blvdId");
		exit;
		return $this->redirect()->toRoute('blvd', array('action'=>"list#$blvdId"));
		return $this->redirect()->toRoute('blvd', array('action'=>'edit', 'id' => $blvdId));
        
    }
    
    public function editAction()
    {
        
        $this->setCSS();
    
        $this->checkPermission(); 
    	$id = (int)$this->params('id');
    	if (!$id) {
    		return $this->redirect()->toRoute('blvd', array('action'=>'add'));
    	}
    	$blvd = $this->getBlvdMapper()->getBlvd($id);
    
    	$form = new BlvdForm();
    	$form->bind($blvd);
    
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setData($request->getPost());
    		if ($form->isValid()) {
    			$this->getBlvdMapper()->saveBlvd($blvd);
    			$this->flashMessenger()->addMessage('Updated!');
    			return $this->redirect()->toRoute('blvd', array('action'=>'list'));
    		}
    	}

    	$catForm = $this->getBlvdJoinCategoryForm($id);

    	$headScript = $this->serviceLocator->get('viewhelpermanager')->get('headScript');
    	$headScript->prependFile("/js/admin.js", $type = 'text/javascript', $attrs = array());
    	
    	$url="http://google.com?#q=".urlencode($form->get('name')->getValue()) . "' target=_blank>google " . $form->get('name')->getValue() . "</a><br><br>";

    	$addCatsFirst = false;
    	if (count($catForm->get('category_id_arr')->getValueOptions()) == 0) {
    	    $addCatsFirst = true;
    	    $form = array();
    	    $catForm = array();
    	}
    	return array(
    		'id' => $id,
    		'form' => $form,
    		'action' => 'edit',
    	    'catform' => $catForm,
    	    'addCatsFirst' => $addCatsFirst 
    	);
    
    }
    
    public function addCategoryAction()
    {
        
        $this->checkPermission(); 
        $form = new BlvdCategoryForm();
        $blvd = new BlvdCategoryEntity();
        $form->bind($blvd);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$form->setData($request->getPost());
        	if ($form->isValid()) {
        		if ($this->getBlvdCategoryMapper()->save($blvd)) {
        		  $msg = 'Added!'; 
        		} else {
        		  $msg = 'It may already be added';
        		}
    			$this->flashMessenger()->addMessage($msg);
    			return $this->redirect()->toRoute('blvd', array("action"=>"admincategories"));
        	} else {
    			$this->flashMessenger()->addMessage('Errors!');
        	}
        }
        
    	$result = new ViewModel();
    	$result->setTemplate('blvd/admin/editcategory');
    	return $result->setVariables(array('form' => $form, 'id' => 0, 'action' => 'addcategory'));
    
    }
    
    public function addAction()
    {
        
        $this->setCSS();
        $this->checkPermission(); 
        $form = new BlvdForm();
        $blvd = new BlvdEntity();
        $form->bind($blvd);
        
        $request = $this->getRequest();
        if ($request->isPost()) {
        	$form->setData($request->getPost());
        	if ($form->isValid()) {
        		$blvd = $this->getBlvdMapper()->saveBlvd($blvd);

        		$msg = 'Added! Updated categories.';
       			$this->flashMessenger()->addMessage($msg);
    			return $this->redirect()->toRoute('blvd', array("action"=>"edit", "id" => $blvd->getId()));
        
        		// Redirect to list of blvds
        		//return $this->redirect()->toRoute('blvd');
        	} else {
       			$this->flashMessenger()->addMessage('Some problems need fixing below.');
        	}
        	
        }
        
    	$result = new ViewModel();
    	$result->setTemplate('blvd/admin/edit');
    	return $result->setVariables(array('form' => $form, 'id' => 0, 'action' => 'add', 'addCatsFirst' => false));
    
    }
    
    public function unpublishSocialMediaAction()
    {
        if ($this->checkPermission()) {
            $socialId = $this->params('social_id');
            $username = $this->params('username');
            $r = $this->getSocialMediaMapper()->unpublish($socialId, $username); 
            $jsonModel = new JsonModel(array("result" => $r));
            return $jsonModel;
        }
        $jsonModel = new JsonModel(array("result" => 0));
        return $jsonModel;

    }

    public function getSocialMediaMapper()
    {
    	return $this->getServiceLocator()->get('Blvd\Model\SocialMediaMapper');
    }
    public function getBlvdJoinCategoryTable()
    {
    	return $this->getServiceLocator()->get('Blvd\Model\BlvdJoinCategoryTable');
    }
    public function getBlvdJoinCategoryMapper()
    {
    	return $this->getServiceLocator()->get('Blvd\Model\BlvdJoinCategoryMapper');
    }
    public function getBlvdCategoryMapper()
    {
    	return $this->getServiceLocator()->get('Blvd\Model\BlvdCategoryMapper');
    }
    public function getBlvdMapper()
    {
    	return $this->getServiceLocator()->get('Blvd\Model\BlvdMapper');
    }

    public function listAction()
    {
        
        $this->setCSS();
        $this->checkPermission(); 
    	$viewModel = new ViewModel(array(
    		'blvd' => $this->getBlvdMapper()->fetchAll('name'),
    	));

        return $viewModel;
        
    } 

}