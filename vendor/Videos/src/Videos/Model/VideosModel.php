<?php
namespace Videos\Model;

use Videos\Model\VideosEntity;

class VideosModel 
{
    
    public $client_id;
    public $client_secret;
    public $api_key;
    public $playlist_id;
    
    public function __construct($configArr)
    {
    	$this->client_id = $configArr['client_id'];
    	$this->client_secret = $configArr['client_secret'];
    	$this->api_key = $configArr['api_key'];
    	$this->playlist_id = $configArr['playlist_id'];
		
    }
    
 
    public function getLimit($width, $height)
    {
//exit($width."|");
        if ($width == 1080 && $height >= 1700) {
            return 50;
            return 111; 
        } elseif ($width == 864 && $height == 1067) {
            return 16;
            return 31; 
        } elseif ($width == 960 && $height == 1195) {
            return 45; 
        } elseif ($width <= 360) {
            return 4;
            return 9;
        } elseif ($width <= 640) {
            return 4;
            return 12;
        } elseif ($width <= 800) {
            return 4;
            return 14;
        } elseif ($width <= 1007) {
            return 11;
            return 18;
        } elseif ($width <= 1152) {
            return 12;
            return 21;
        } elseif ($width <= 1199) {
            return 18;
            return 24;
        } elseif ($width <= 1280) {// && $height == 896) {
            return 12;
            return 27;
        } elseif ($width <= 1920){ // && $height == 900) {
            return 25;
            return 45;
        }

        $width = floor($width/100) * 100;
        $height = floor($height/100) * 100;

        // make best calculation if no match above
        $numCols = ($width/100) - 1;
        $numRows = ceil(($height/100)/2);
        $totalThumbs = $numCols *  $numRows;
        $remainder = $totalThumbs - (floor($totalThumbs/$numRows) * $numRows);
        $totalThumbs = $totalThumbs - $remainder;
        
        $adSize = 9; //(300 x 300 ad, so 9 100x100 thumbs)
        $totalThumbs-= $adSize; // less 9 100x100 thumbs)

        if ($totalThumbs > 70) {
            $totalThumbs = 70;
        }
        return $totalThumbs;

    }
    
 
    public function formatYoutubeResponse($arr)
    {

        if (!is_array($arr) || count($arr) == 0) {
            return false;
        }

        $vidEntArr = array();
        foreach($arr as $key => $obj) {

            $vidEnt = new VideosEntity();
            $vidEnt->setThumbnail($obj->snippet->thumbnails->default->url)
                ->setVideoId($obj->contentDetails->videoId)
                ->setWidth($obj->snippet->thumbnails->high->width)
                ->setHeight($obj->snippet->thumbnails->high->height)
                ->setSource('youtube')
                ->setTitle($obj->snippet->title);
            $vidEntArr[] = $vidEnt; 
            
        }
        
        return $vidEntArr;

    }
    
    public function getThumbVideosCache()
    {

        $ttl = 86400;
        
        $cache   = \Zend\Cache\StorageFactory::factory(array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'options' => array(
                'cache_dir' => 'data/cache/videothumbs',
                'ttl' => $ttl
            ),
            'plugins' => array(
                'exception_handler' => array(
                    'throw_exceptions' => true
                ),
            )
        ));

        return $cache;
           
    }
    
    
}