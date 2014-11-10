<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Twitter for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Twitter\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Twitter\Model\TweetEntity;

class TwitterController extends AbstractActionController
{
    
    protected $twitterUsersTable;
    
    public function getFavoritesAction()
    {
        
        $this->twitterBlvd = $this->serviceLocator->get('Twitter\Model\TwitterBlvd');
        $response = $this->twitterBlvd->getFavorites($this->twitterBlvd->getUserId());
        $twitterModel = new \Twitter\Model\TwitterModel;
        $entArr = $twitterModel->formatFavorites($response);
        foreach($entArr as $ent) {
            $socEnt = $twitterModel->formatGalleryEnt($ent);
            $this->getGalleryMapper()->insertEnt($socEnt);
        }            

        return false;
        
    }

    private function getGalleryMapper() {
        return $this->getServiceLocator()->get('Gallery\Model\GalleryMapper');
    }
     
}
