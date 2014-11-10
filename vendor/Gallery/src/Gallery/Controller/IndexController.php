<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Gallery for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Gallery\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Utility\Model\Utility;
use Zend\View\Model\JsonModel;
use Gallery\Model\GalleryModel;
use Base\Controller\AbstractBaseController;

class IndexController extends AbstractBaseController // extends AbstractActionController
{
    protected $galleryMapper;
    protected $galleryModel;

    // getThumbs (for gallery page) makes use of getMonthlyNav's total pic count and prev and next month. To test
    // without caching, caching for both needs to be disabled, and that can be done here with $disableCache
    public $disableCache = CACHE_DISABLED;
    
    public function codeAction()
    {
        
    }
    
    private function setJqueryUI()
    {
        $headScript = $this->serviceLocator->get('viewhelpermanager')->get('headScript');
        $headScript->prependFile("/js/gallery.js?3", $type = 'text/javascript', $attrs = array());

        $headScript->prependFile("//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js", $type = 'text/javascript', $attrs = array());
        
        //$headScript->prependFile("/jquery-ui/jquery-ui.min.js", $type = 'text/javascript', $attrs = array());
        //$headScript->prependFile("/jquery-ui/external/jquery/jquery.js", $type = 'text/javascript', $attrs = array());
        
        $headLink = $this->serviceLocator->get('viewhelpermanager')->get('headLink');
        $headLink->appendStylesheet('//ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css');
        $headLink->appendStylesheet('/css/main.css');
        //$headLink->appendStylesheet('/jquery-ui/jquery-ui.theme.min.css');
        //$headLink->appendStylesheet('/jquery-ui/jquery-ui.structure.min.css');
        
    }
    
    public function forsaleAction()
    {
    }
    
    public function getThumbsAction()
    {
        
        $galleryModel = new GalleryModel();
        // width of html doc
        $screen_width = (int)$this->params()->fromQuery('screen_width');
        //width of browser viewport
        $width = (int)$this->params()->fromQuery('width');
        $height = (int)$this->params()->fromQuery('height');
        $direction = $this->params()->fromQuery('direction');
        $limit = $galleryModel->getLimit($width, $height);
        $page = (int)$this->params('page');

        $offset = 0; 
        if ($page > 0) {
            $offset = $page * $limit;
        }

        $dateYMon = $this->params('date', false);
        if (!$dateYMon) {
            $dateYMon = date("Y-M");
        } else {
            // validate date
            $partsArr = explode("-", $dateYMon);
            if (date("Y") < $partsArr[0] || $this->getGalleryModel()->validateThreeLetterMonth($partsArr[1]) == false) {
                $dateYMon = date("Y-M");
            } 
        }
         
        $cache = $this->getGalleryModel()->getThumbGalleryCache($dateYMon);
        $key = $offset . "_" . $limit;
        $jsonStr = $cache->getItem($key, $success);
        if ($this->disableCache || !$success) {
 
            // get previous and next month dates from monthly nav arr
            $navArr = $this->getMonthlyNav();
            $arr = $this->getGalleryModel()->getPrevAndNextDate($navArr, $dateYMon);
            $nextDateYMon = $arr['nextDateYMon'];
            $prevDateYMon = $arr['prevDateYMon'];
            $prevTotal = $arr['prevTotal'];

            $thumbArr = $this->getGalleryMapper()->fetchThumbArr($dateYMon, $offset, $limit, $prevDateYMon, $nextDateYMon, $prevTotal);
            printR($thumbArr);
            if (!$thumbArr) {
                $offset = 0;
                $dateYMon = 0;
                if ($direction == 'next' && $nextDateYMon) {
                    $dateYMon = $nextDateYMon;
                } elseif ($prevDateYMon) {
                    $dateYMon = $prevDateYMon;
                } 
                if ($dateYMon) {
                    $arr = $this->getGalleryModel()->getPrevAndNextDate($navArr, $dateYMon);
                    $nextDateYMon = $arr['nextDateYMon'];
                    $prevDateYMon = $arr['prevDateYMon'];
                    $prevTotal = $arr['prevTotal'];
                    $thumbArr = $this->getGalleryMapper()->fetchThumbArr($dateYMon, $offset, $limit, $prevDateYMon, $nextDateYMon, $prevTotal);
                }
            }
            
            if ($thumbArr) {
                $cache->setItem($key, serialize($thumbArr));
            }

        } else {
            
            $thumbArr = unserialize($jsonStr);
            
        }
        
        $jsonModel = new JsonModel(array("result" => $thumbArr));
        $jsonModel->setTerminal(true);
        return $jsonModel;
 
    }

    public function indexAction()
    {
 
        $this->setJqueryUI();

        return new ViewModel(array(
            'isWebmaster' => $this->zfcUserAuthentication()->hasIdentity(),
            'linkArr' => $this->getServiceLocator()->get('Links\Model\LinkMapper')->getLinkArr(),
            'isMobile' =>  $this->getIsMobile() 
        ));
    }
    

    
    public function disallowAction()
    {

        $isWebmaster = $this->zfcUserAuthentication()->hasIdentity();
        if ($isWebmaster) {
            $id = (int)$this->params('id');
            $picObj = $this->getGalleryMapper()->getPicture($id, 'current');
            
            // clear cache for 'current' postion
            if (is_object($picObj)) {
                $this->getGalleryModel()->clearPictureCache($id, 'current');
            }
            
            // get id of previous pic so that the 'next' cache key can be cleared
            $prevPicObj = $this->getGalleryMapper()->getPicture($id, 'prev');

            if (is_object($prevPicObj)) {
                $this->getGalleryModel()->clearPictureCache($prevPicObj->id, 'next');
            }
            
            // get id of next pic so that the 'prev' cache key can be cleared
            $nextPicObj = $this->getGalleryMapper()->getPicture($id, 'next');
            if (is_object($nextPicObj)) {
                $this->getGalleryModel()->clearPictureCache($nextPicObj->id, 'prev');
            }
            
            // remove nav for month pic occured in
            if (is_object($picObj) && !is_null($picObj->date_created)) {
                $dateYMon = date("Y-M", strtotime($picObj->date_created));
                if ($dateYMon) {
                    $this->getGalleryModel()->removeDirectoryContents('data/cache/gallerypages/' . $dateYMon);
                }
            }
            
            $r = $this->getGalleryMapper()->disallowPicture($id);
            
        }
        $jsonModel = new JsonModel(array("result" => 1));
        $jsonModel->setTerminal(true);
        return $jsonModel;

    }
    
    public function pictureAction()
    {
        //$headScript = $this->serviceLocator->get('viewhelpermanager')->get('headScript');
        //$headScript->prependFile("/js/gallery.js", $type = 'text/javascript', $attrs = array());
        $this->setJqueryUI();
        //$headScript->prependFile("/js/camgirls.js", $type = 'text/javascript', $attrs = array());
        
        $message = false;
        $id = (int)$this->params('id');
        $idposition = $this->params('idposition');
        // sandbox idposition
        if ($idposition != 'next' && $idposition != 'prev') {
            $idposition = 'current';
        } 
        $frompage = $this->params()->fromQuery('frompage', 0);
        $frommonth = $this->params()->fromQuery('frommonth', 0);
        
        $cacheArr = $this->getGalleryModel()->getPictureCache($id, $idposition);
        $key = $cacheArr['key'];
        $cache = $cacheArr['cache'];

        $key = $id . "_" . $idposition;
        $str = $cache->getItem($key, $success);
        if ($this->disableCache || !$success) {

            $res = $this->getGalleryMapper()->getPicture($id, $idposition);
            if ($res == 'top reached' && $idposition == 'prev') {
                $message = $res;
                $res = $this->getGalleryMapper()->getPicture($id, 'current');
            } elseif ($res == 'end reached' && $idposition == 'next') {
                $message = $res;
                $res = $this->getGalleryMapper()->getPicture($id, 'current');
            }

            if ($res) {
                $cache->setItem($key, serialize($res));
            }

        } else {
            
            $res = unserialize($str);

        }

        if ($res == false) {
           $message.= "Unable to find id: $id"; 
        }
 
        if (date("Y-M", strtotime($res->date_created)) != $frommonth) {
            $frommonth = date("Y-M", strtotime($res->date_created));
            $frompage = 0;
        } 

        return new ViewModel(array(
            'picture' => $res,
            'frompage' => $frompage,
            'frommonth' => $frommonth,
            'message' => $message,
            'linkArr' => $this->getServiceLocator()->get('Links\Model\LinkMapper')->getLinkArr(),
            'isWebmaster' => $this->zfcUserAuthentication()->hasIdentity(),
            'isMobile' => $this->getIsMobile()
        ));
        
    }
 
    public function getMonthlyNavAction()
    {

        $arr = $this->getMonthlyNav(); 
        $jsonModel = new JsonModel(array("result" => $arr));
        $jsonModel->setTerminal(true);
        return $jsonModel;
        
    }
    
    private function getMonthlyNav()
    {
        
        $cache = $this->getGalleryModel()->getMonthlyNavCache();
        $key = date("Y-M") . "_scrollable_nav";
        $str = $cache->getItem($key, $success);
        if ($this->disableCache == true || !$success) {
            $arr = $this->getGalleryMapper()->getMonthlyNav();
            $cache->setItem($key, serialize($arr));
        } else {
            $arr = unserialize($str);    
        }
        
        return $arr;
     
    }

    private function getGalleryMapper()
    {
        if (!$this->galleryMapper) {
            $sm = $this->getServiceLocator();
            $this->galleryMapper = $sm->get('Gallery\Model\GalleryMapper');
        }
        return $this->galleryMapper;
    }  
    
    private function getGalleryModel()
    {
        if (!$this->galleryModel) {
            $sm = $this->getServiceLocator();
            $this->galleryModel = $sm->get('Gallery\Model\GalleryModel');
        }
        return $this->galleryModel;
    }       
}
