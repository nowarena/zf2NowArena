<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/TumblrMy for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TumblrMy\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use TumblrMy\Model\TumblrBlvd;

class TumblrMyController extends AbstractActionController
{
    public function indexAction()
    {
        $this->tumblrcronAction();     
    }
    
    public function tumblrcronAction()
    {
        
    	$tumblrBlvd = $this->getServiceLocator()->get('TumblrMy\Model\TumblrBlvd');
    	$client = $tumblrBlvd->getTumblrClient();
        $r = $client->getBlogPosts($tumblrBlvd->blog_name, $options = null);
        if (!is_object($r)) {
            return false;
        }
        foreach($r->posts as $key => $obj) {
            $entArr = $tumblrBlvd->formatTumblrForGallery($obj);
            foreach($entArr as $ent) {
                printR($ent);
                $this->getGalleryMapper()->insertEnt($ent);
            }
        }
        return false;
    }
    

    private function getGalleryMapper() {
    	return $this->getServiceLocator()->get('Gallery\Model\GalleryMapper');
    }
    
}