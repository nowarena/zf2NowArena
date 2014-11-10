<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Yelp for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace YelpMy\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use YelpMy\Model\YelpBlvd;
use YelpMy\Model\YelpEntity;
use Blvd\Model\BlvdMapper;
use Utility\Model\Utility;

class YelpController extends AbstractActionController
{
    public function indexAction()
    {
    }
    
    public function yelpcronAction()
    {
        $utilObj = new Utility('yelp.com');
        $yelpBlvd = $this->getServiceLocator()->get('YelpMy\Model\YelpBlvd'); 
        $blvdUsers = $this->getBlvdMapper()->fetchAll('address', 'asc', true);
        foreach($blvdUsers as $blvd) {
            $blvdId = $blvd->getId();
            $data = $yelpBlvd->fetchBiz($blvd->getYelp());
            if (isset($data->error) || $data == '') {
                continue;
            }
            
            printR($data);
            //printR($data);return false;
            $image ='';
            $imageWidth = 100;
            $imageHeight = 100;
            if (isset($data->image_url)) {
                $image = $data->image_url;
                //$imageDimArr = $utilObj->getImageDims($image);
            }
            $review = '';

            $snippet_text = '';
            if(isset($data->snippet_text)) {
                $snippet_text = $data->snippet_text;
            }
            $created_time = time();
            if (isset($data->reviews[0]->time_created)) {
                $created_time = $data->reviews[0]->time_created;
            }
            $excerpt = '';
            if (isset($data->reviews[0]->excerpt)) {
                $excerpt = $data->reviews[0]->excerpt;
            }
            if ($excerpt) {
                
                $yelpEnt = new YelpEntity();
                $yelpEnt->setId($data->reviews[0]->id)
                    ->setMediaHeight($imageHeight)
                    ->setMediaWidth($imageWidth)
                    ->setText($excerpt)
                    ->setLink($data->url)
                    ->setCreatedTime($created_time)
                    ->setBizname($blvd->getYelp())
                    ->setImage($image);
                
                $this->getServiceLocator()->get('YelpMy\Model\YelpMapper')->saveYelp($yelpEnt);
                printR($yelpEnt);
                
            } elseif(false && $snippet_text) {
                
                $yelpEnt = new YelpEntity();
                $yelpEnt->setId('snippet')
                    ->setMediaHeight($imageHeight)
                    ->setMediaWidth($imageWidth)
                    ->setLink($data->url)
                    ->setText($snippet_text)
                    ->setCreatedTime($created_time)
                    ->setBizname($blvd->getYelp())
                    ->setImage($image);
                
                $this->getServiceLocator()->get('YelpMy\Model\YelpMapper')->saveYelp($yelpEnt);
                printR($yelpEnt);
            }
        }

        return false;
        
    }
    
    public function searchAction()
    {
             
        $bizArr = array();
        $yelpBlvd = new YelpBlvd();
        $arr = $yelpBlvd->search();
        printR($arr);
        foreach($arr as $key => $obj) {
           foreach($obj->businesses as $index => $obj2)
           $bizArr[$obj2->name] = $obj2->id;
        }
        printR($bizArr);
        foreach($bizArr as $name => $id) {
           $blvdEnt = $this->getBlvdMapper()->getBlvd(0, $name);
           if ($blvdEnt) {
               if ($blvdEnt->getYelp() != '')continue;
               $blvdEnt->setYelp($id);
               $this->getBlvdMapper()->saveBlvd($blvdEnt);
           } else {
               echo "<br>nothing found for: " .$name. "|".$id."<br>";
           }
           
        }  
        
        return false; 
        
    }
    
    private function getBlvdMapper()
    {
    	$sm = $this->getServiceLocator();
    	$this->blvdTable = $sm->get('Blvd\Model\BlvdMapper');
    	return $this->blvdTable;
    }
    

}