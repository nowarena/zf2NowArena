<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Videos for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Videos\Controller;

session_start();

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Utility\Model\Utility;
//use Google\Client;
use Madcoda\Youtube;
use Videos\Model\VideosModel;
use Videos\Model\VideosMapper;
use Base\Model\MobileModel;
use Base\Controller\AbstractBaseController;
use Gallery\Model\GalleryModel;

class IndexController extends AbstractBaseController //AbstractActionController
{

    protected $disableCache = CACHE_DISABLED;
    
    public function setCSS()
    {

        $headLink = $this->serviceLocator->get('viewhelpermanager')->get('headLink');
        $headLink->appendStylesheet('/css/main.css?1'); 
        
    }
    
    public function setJS()
    {
        $headScript = $this->serviceLocator->get('viewhelpermanager')->get('headScript');
        $headScript->prependFile("/js/videos.js?4", $type = 'text/javascript', $attrs = array());
    } 
    
    public function indexAction()
    {

        $this->setCSS();
        $this->setJS();
        
        return new ViewModel(array(
            'isWebmaster' => $this->zfcUserAuthentication()->hasIdentity(),
            'linkArr' => $this->getServiceLocator()->get('Links\Model\LinkMapper')->getLinkArr(),
            'isMobile' => $this->getIsMobile()
        ));
        
    }
        
    public function getThumbsAction()
    {
        
        // width of html doc
        $screen_width = (int)$this->params()->fromQuery('screen_width');
        //width of browser viewport
        $width = (int)$this->params()->fromQuery('width');
        $height = (int)$this->params()->fromQuery('height');
        $direction = $this->params()->fromQuery('direction');
        
        $galleryModel = $this->getVideosModel();
        $limit = $galleryModel->getLimit($width, $height);
        //$page = (int)$this->params('page');
        $page = (int)$this->params()->fromQuery('page');
        $offset = 0; 
        if ($page > 0) {
            $offset = $page * $limit;
        }
         
        $cache = $this->getVideosModel()->getThumbVideosCache();
        $key = $offset . "_" . $limit;
        $jsonStr = $cache->getItem($key, $success);
        if ($this->disableCache || !$success) {

            $thumbArr = $this->getVideosMapper()->fetchThumbArr($offset, $limit);
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
    
    
    public function youtubecronAction()
    {
        
        include_once('vendor/autoload.php');

        $vidModel = $this->getVideosModel();
        $youtube = new Youtube(array('key' => $vidModel->api_key));
        $playListArr = $youtube->getPlaylistItemsByPlaylistId($vidModel->playlist_id);

        $vidMapper = $this->getVideosMapper();
        $vidEntArr = $vidModel->formatYoutubeResponse($playListArr);
        foreach($vidEntArr as $key => $ent) {
            $vidMapper->saveVideo($ent);
        }
        
    }
    
    private function getVideosMapper()
    {
        return $this->serviceLocator->get('Videos\Model\VideosMapper');
    }
    private function getVideosModel()
    {
        return $this->serviceLocator->get('Videos\Model\VideosModel');
    }
}
