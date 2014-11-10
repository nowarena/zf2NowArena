<?php
namespace Gallery\Model;

use Utility\Model\Utility;
use Gallery\Model\GalleryEntity;

class GalleryModel extends GalleryEntity 
{
    
    protected $threeLetterMonthArr = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    
    public function __construct()
    {
    	$this->twitterUtilObj = new Utility('twitter.com');
    	$this->instagramUtilObj = new Utility('instagram.com');
    	$this->facebookUtilObj = new Utility('facebook.com');
    	$this->utilObj = new Utility();
    }
    
    /**
     * Used by ResultSet to pass each database row to the entity
     */
    public function exchangeArray($data)
    {
 
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->social_id = (isset($data['social_id'])) ? $data['social_id'] : null;
        $this->username = (isset($data['username'])) ? $data['username'] : null;
        $this->title  = (isset($data['title'])) ? $data['title'] : null;
        $this->header_text = (isset($data['header_text'])) ? $data['header_text'] : null;
        $this->text = (isset($data['text'])) ? $data['text'] : null;
        $this->media_url = (isset($data['media_url'])) ? $data['media_url'] : null;
        $this->media_height = (isset($data['media_height'])) ? $data['media_height'] : null;
        $this->media_width = (isset($data['media_width'])) ? $data['media_width'] : null;
        $this->link = (isset($data['link'])) ? $data['link'] : null;
        $this->date_created = (isset($data['date_created'])) ? $data['date_created'] : null;
        $this->date_inserted = (isset($data['date_inserted'])) ? $data['date_inserted'] : null;
        $this->source = (isset($data['source'])) ? $data['source'] : null;
        $this->unpublish = (isset($data['unpublish'])) ? $data['unpublish'] : null;
        $this->total = (isset($data['total'])) ? $data['total'] : null;
        
    }
    
    
    public function validateThreeLetterMonth($threeLetterMonth)
    {

        if (in_array($threeLetterMonth, $this->threeLetterMonthArr)) {
            return true;
        }
        return false; 
        
    }
    
    public function removeDirectoryContents($dir) 
    {
        
        foreach(glob($dir . '/*') as $file) {
            if(is_dir($file)) {
                $this->removeDirectoryContents($file);
            } else {
                unlink($file);
            }
        }
        
        rmdir($dir);
    
    }

    
    public function clearPictureCache($id, $position)
    {

        $cacheArr = $this->getPictureCache($id, $position);
        $key = $cacheArr['key'];
        $cache = $cacheArr['cache'];
        $r = $cache->removeItem($key);

    }

    public function getThumbGalleryCache($dateYMon)
    {

        $ttl = 86400;
        
        $cacheDir = $this->getThumbGalleryCacheDir($dateYMon);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir);     
        }
        
        $cache   = \Zend\Cache\StorageFactory::factory(array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'options' => array(
                'cache_dir' => $cacheDir,
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
    
    public function getThumbGalleryCacheDir($dateYMon)
    {
        
        $cacheDir = 'data/cache/gallerypages/' . $dateYMon;

        return $cacheDir;
        
    }
    
    public function getMonthlyNavCache()
    {

        $cache   = \Zend\Cache\StorageFactory::factory(array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'options' => array(
                'cache_dir' => 'data/cache/',
                'ttl' => 86000
            ),
            'plugins' => array(
                // Don't throw exceptions on cache errors
                'exception_handler' => array(
                    'throw_exceptions' => true
                ),
            )
        ));
        
        return $cache;

    }
    

    
    public function getLimit($width, $height)
    {
//exit($width."|");
        if ($width == 1080 && $height >= 1700) {
            return 111; 
        } elseif ($width == 864 && $height == 1067) {
            return 31; 
        } elseif ($width == 960 && $height == 1195) {
            return 45; 
        } elseif ($width <= 360) {
            return 9;
        } elseif ($width <= 640) {
            return 9;
        } elseif ($width <= 800) {
            return 9;
        } elseif ($width <= 1007) {
            return 18;
        } elseif ($width <= 1152) {
            return 18;
        } elseif ($width <= 1199) {
            return 24;
        } elseif ($width <= 1280) {// && $height == 896) {
            return 24;
        } elseif ($width <= 1920){ // && $height == 900) {
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
    
    public function getPictureCache($id, $idposition)
    {
        
        $subDir = $id % 5;
        $cache   = \Zend\Cache\StorageFactory::factory(array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'options' => array(
                'cache_dir' => 'data/cache/pictures/' . $subDir . '/',
                'ttl' => (86000 * 1)
            ),
            'plugins' => array(
                'exception_handler' => array(
                    'throw_exceptions' => true
                ),
            )
        ));

        $key = $id . "_" . $idposition;
        
        return array('key' => $key, 'cache' => $cache);
        
    }
     
    public function getPrevAndNextDate($arr, $dateYMon) 
    {

        $prevDateYMon = 0;
        $nextDateYMon = 0;
        $prevTotal = 0;
        $nextTotal = 0;
        $currentTotal = 0;
        foreach($arr as $key => $row) {
            if ($dateYMon == $row['dateYMon']) {
                $currentTotal = $row['total'];
                if (isset($arr[$key - 1]['dateYMon'])) {
                    $prevDateYMon = $arr[$key - 1]['dateYMon'];
                    $prevTotal = $arr[$key - 1]['total'];
                }
                if (isset($arr[$key + 1]['dateYMon'])) {
                    $nextDateYMon = $arr[$key + 1]['dateYMon'];
                    $nextTotal = $arr[$key + 1]['total'];
                }
                break;
            }
        }

        // if no date matching, get top most date
        if ($prevDateYMon == 0 && isset($arr[0])) {
            $prevDateYMon = $arr[0]['dateYMon']; 
        } 
 
        $arr = array(
            'prevDateYMon' => $prevDateYMon, 
            'nextDateYMon' => $nextDateYMon, 
            'prevTotal' => $prevTotal, 
            'nextTotal' => $nextTotal, 
            'currentTotal' => $currentTotal
        );

        return $arr;
            
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($val)
    {
        $this->total = $val;
        return $this;
    }

    public function getMediaHeight()
    {
        $val = $this->utilObj->getSmallMediaHeight(parent::getMediaWidth(), parent::getMediaHeight()); 
        return $val;
    }
    
    public function getMediaWidth()
    {
        return $this->utilObj->getSmallMediaWidth(parent::getMediaWidth(), parent::getMediaHeight()); 
    }
    
    public function getText()
    {
        $textLength = 1000;
        if (trim($this->getMediaUrl()) == '') {
            $textLength = 200;
        }
        $textLength = 1000;
        if ($this->getSource() == 'twitter') {
            // if there is no image and no link, build a link to status page and append it to text
            if ($this->getLink() != '' && trim($this->getMediaUrl()) == '') {
                $this->text.= " http://twitter.com/" . $this->getUsername() . "/status/" . $this->getSocialId();    
            }
            return $this->twitterUtilObj->parseText($this->text, $textLength);
        } elseif ($this->getSource() == 'instagram') {
            return ($this->instagramUtilObj->parseText($this->text, $textLength));
        } elseif ($this->getSource() == 'facebook') {
            return $this->facebookUtilObj->parseText($this->text, $textLength);
        } else if ($this->getSource() == 'yelp') {
            $text = $this->text . " " . str_replace("www.", "", $this->getLink());
            return $this->twitterUtilObj->parseText($text, 1000);
        } 
        return $this->text;
    }

    public function getHoursOld()
    {
        return $this->twitterUtilObj->getHoursOld($this->getDateCreated());
    }
    
    public function getDaysOld()
    {
        return $this->twitterUtilObj->getDaysOld($this->getDateCreated());
    }
    
    public function getMonthDay() 
    {
        return $this->twitterUtilObj->getMonthDay($this->getDateCreated());
    }

    public function getThumb()
    {

        if ($this->getSource() == 'tumblr') {
            return $this->formatImgSrc($this->media_url, 100);
        } elseif ($this->getSource() == 'twitter') {
            return $this->getMediaUrl() . ":thumb";
        } elseif ($this->getSource() == 'instagram' ) {
            $imgSrc = $this->getMediaUrl();
            if (stristr($imgSrc, "_n.")){
                $imgSrc = str_replace("_n.", "_s.", $imgSrc);
                return $imgSrc;
            } elseif (stristr($imgSrc, "_7.")) {
                return str_replace("_7.", "_5.", $imgSrc);
            } else {
                return str_replace("_6.", "_5.", $imgSrc);
            }
        }
        
        return "no thumb creation set up for ". $this->getSource() . " in GalleryModel"; 
        
    }
    
    public function getMediaUrl()
    {
        $size = false;
        if ($this->getSource() == 'tumblr') {
            $size = 500;
        }
        $this->media_url = $this->formatImgSrc($this->media_url, $size);
        return $this->media_url;
    }
    
    public function setMediaUrl($media_url)
    {
        $this->media_url = $media_url; 
        return $this;
    }

    private function formatImgSrc($imgSrc, $size)
    {

        if ($this->getSource() == 'tumblr') {
           $imgSrc = preg_replace("~http://[\d]+\.media\.tumblr\.com~is", "http://media.tumblr.com", $imgSrc);
           // get width of image as indicated by number before file extension. if under requested $size, leave alone, if over $size, set to $size 
           preg_match("~_([\d]+)\.(jpg|jpeg|png|gif)~is", $imgSrc, $arr);
           if (isset($arr[1]) && $arr[1] >= $size) {
               $imgSrc = preg_replace("~_[\d]+\.(jpg|jpeg|png|gif)~is", "_" . $size . ".$1", $imgSrc);
           }
           return $imgSrc;
        } 

        return $imgSrc;

    }
    
}