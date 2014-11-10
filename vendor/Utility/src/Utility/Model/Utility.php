<?php
namespace Utility\Model;

class Utility
{

    public function __construct($domain = null)
    {
        $this->domain = $domain; 
    	$this->targetWidth = 100;
    	$this->targetHeight = 100;
    	$this->targetDimension = 100;
    }
    
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }
    
    public function fetchUrl($url)
    {
    
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    	$data = curl_exec($ch);
    	curl_close($ch);
    
    	return $data;
    
    }
    
    public function fetchUrlAndJsonDecode($url)
    {
    
        $data = $this->fetchUrl($url);
    	$result = json_decode($data);
    	return $result;
    }
    
    

    /*
     * http://www.php.net/manual/en/function.array-multisort.php#109943
     */
    public function sortArrOfObj($array, $sortby, $direction='desc') {
    	
    	$sortedArr = array();
    	$tmp_Array = array();
    	 
    	foreach($array as $k => $v) {
    		$tmp_Array[] = strtolower($v->{$sortby});
    	}
    	 
    	if($direction=='asc'){
    		asort($tmp_Array);
    	}else{
    		arsort($tmp_Array);
    	}
    	 
    	foreach($tmp_Array as $k=>$tmp){
    		$sortedArr[] = $array[$k];
    	}
    	 
    	return $sortedArr;
    
    }
    
    public function getImageDims($file)
    {
        return getImageSize($file);
    }
    
    public function getSmallMediaWidth($width, $height)
    {
    
    	if ($width == 0) {
    		return 0;
    	} 
    	
    	return ceil($this->getPercentReduceBy($width, $height) * $width);
    	
    }
    
    public function getSmallMediaHeight($width, $height)
    {
    
    	if ($height == 0) {
    		return 0;
    	}
    	return ceil($this->getPercentReduceBy($width, $height) * $height);

    }

    /**
     * Calc what percentage a value must reduce itself by 
     * @param int $dimension
     */
    private function calcPercentReduceBy($dimension) 
    {
       return $this->targetDimension / $dimension;
    }

    /**
     * Use the percentage of reduction that is the greatest
     * @param unknown $width
     * @param unknown $height
     */
    private function getPercentReduceBy($width, $height)
    {

        $reduceWidthBy = $this->calcPercentReduceBy($width);
        $reduceHeightBy = $this->calcPercentReduceBy($height);
        if ($width > $this->targetDimension && $reduceWidthBy < $reduceHeightBy) {
        	$val = $this->calcPercentReduceBy($width);
        	return $val;
        } elseif ($height > $this->targetDimension) {
        	$val = $this->calcPercentReduceBy($height);
        	return $val;
        } else{
            return 1;
        }
 
    }
    
    public function compareStrings($textA, $textB) 
    {

        $matchNum = 0;
        $textAWordCount = 0;
        $textAArr = explode(" ", strtolower(str_replace("#", "", $textA)));
        $textBArr = explode(" ", strtolower(str_replace("#", $textB)));
        foreach($textAArr as $key => $wordA) {
            if (strlen($wordA) <3 || $wordA == 'the') {
                continue;
            }
            $textAWordCount++;// get the count without short non-words
            if (in_array($wordA, $textBArr)) {
                $matchNum++;
            }
        }
        $perc = ($textAWordCount == 0 ) ? 0 : $matchNum/$textAWordCount;
        return array(
            "wordcount" => $textAWordCount, 
            "matchNum" => $matchNum,
            "percent" => $perc,
            "textA" => $textA,
            "textB" => $textB
        );
    }

    public function setUnixtime($date)
    {
        if (strstr($date, "-") || strstr($date, "/") || strstr($date, '\\'))  {
            $ut = strtotime($date);
        } else {
            $ut = $date;
        }
        return $ut;
    }

    public function setDatetime($date) 
    {
        
        if (strstr($date, "-") || strstr($date, "/") || strstr($date, '\\') || strlen($date) == 8)  {
            return new \DateTime($date);
        } else {
            return new \DateTime(date("Y-M-d H:i:s", $date));
        }
        
    }
    
    public function getMonthDay($date)
    {
    	return date("M, d", $this->setUnixtime($date));
    }
    
    public function pluralize( $count, $text ) 
    { 
        return $count . ( ( $count == 1 ) ? ( " $text" ) : ( " ${text}s" ) );
    }
    
    public function getAge( $datetime )
    {
        
        $datetime = $this->setDatetime($datetime);
        
        $interval = date_create('now')->diff( $datetime );
        $suffix = ( $interval->invert ? ' ago' : '' );
        if ( $v = $interval->y >= 1 ) return $this->pluralize( $interval->y, 'yr' ) . $suffix;
        if ( $v = $interval->m >= 1 ) return $this->pluralize( $interval->m, 'mnth' ) . $suffix;
        if ( $v = $interval->d >= 1 ) return $this->pluralize( $interval->d, 'dy' ) . $suffix;
        if ( $v = $interval->h >= 1 ) return $this->pluralize( $interval->h, 'hr' ) . $suffix;
        if ( $v = $interval->i >= 1 ) return $this->pluralize( $interval->i, 'min' ) . $suffix;
        return $this->pluralize( $interval->s, 'second' ) . $suffix;
    }
    
    public function getDaysOld($date)
    {
        $ut = $this->setUnixtime($date);
    	$ut = strtotime(date("Y-m-d", $ut));
    	$secs = time() - $ut;
    	return floor($secs/86400);
    }
    
    public function getHoursOld($date)
    {
        $ut = $this->setUnixtime($date);
    	$ut = strtotime(date("Y-m-d H:i:s", $ut));
    	$secs = time() - $ut;
    	return ceil($secs/60/60);
    }
    
    /**
     * Parse @username, #hastags and urls
     * @param unknown $text
     */
    public function parseText($text, $strlen = 150, $action = 'shorten')
    {
    	$text = preg_replace("~\\n|\\r~", " ", $text);
    	//$text = htmlspecialchars_decode($text);
    	$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    	$text = rawurldecode($text);
    	
        //$text = str_replace("é","e", $text);
        // doesn't work. work around:
        $text = mb_convert_encoding($text, "HTML-ENTITIES", "UTF-8");
        $text = str_replace("&eacute;", "e", $text);
        
    	$text = $this->convertQuotes($text);
    	$text = $this->convertMS($text);
    	
    	$text = $this->removeNonAscii($text);
    	if ($action == "shorten" ) {
        	$text = $this->shortenText($text, $strlen);
    	} elseif ($action == "readmore") {
    	   $text = $this->formatReadMore($text, $strlen);
    	}
    	// add spaces between merged hashtags. eg. #love#peace
    	//https://www.google.com/webhp?sourceid=chrome-instant&rlz=1C1CHFX_enUS516US516&ion=1&espv=2&ie=UTF-8#q=regex+php+make+hashtag+hyperlink+except+when+it+is+anchor+tag+part+of+a+url&safe=off
    	//$text = preg_replace("~(^|\s)(#[a-z\d][\w-]*)~is", "$1 $2", $text);
    	//echo "<hr>$text<hr>";
    	/*
    	preg_match_all("~#[^a-z0-9_-]+~is", $text, $arr);
    	if (isset($arr[0]) && count($arr[0]) > 0){ 
        	foreach($arr[0] as $key => $val) {
        	   // if hashtag is part of a url, skip. eg. http://test.com#anchortag
        	   if (preg_match("~://[^\s]+$val~is", $text)) {
        	       continue;
        	   }
        	   $text = preg_replace("~[^\s]$val~is", " $val", $text);    
        	}
    	}
    	*/
    	//echo "<hr>$text<hr>";
    	$text = $this->makeUrlsHyperlinks($text);
    	$text = $this->parseAt($text);
    	$text = $this->parseHashtags($text);
    	$text = str_replace("><", ">&nbsp;<", $text);
    	
    	// get rid of multiple consecutive ""
    	$text = preg_replace('~""~', '"', $text);
    
    	// Get rid of 'Photo:' that preceds texts when posted from tumblr
    	$text = preg_replace("~^Photo: ~is", "", $text);
    	
    	return $text;
    }

    // todo make into a view helper - reconsider alot of these methods as view helpers
    public function formatReadMore($text, $length) 
    {

        if (strlen($text) <= $length) {
            return $text;
        }
// temp
$r = rand(1,9999999999);
$fullTextId = "_" . $r;
$shortTextId = "__" . $r;
        $shortenedText = $this->shortenText($text, $length);
        $position = strlen($shortenedText) - 3;
        $hiddenText = substr($text, $position);
        $readMore = "<div style='display:inline;' id='$shortTextId'>";
        $readMore.= $shortenedText;
        $readMore.= " <a class='showMore' onclick='showMore(\"$shortTextId\", \"$fullTextId\")'; href='javascript:void(0);'>show more&raquo;</a>";
        $readMore.= "</div>";
        $readMore.= "<div id='$fullTextId' class='fullText' style='display:none;'>";
        $readMore.= $text;
        $readMore.= " <a class='showLess' href='javascript:void(0);'>&laquo;hide</a>";
        $readMore.= "</div>";
        return $readMore;
                
    }
    
    public function shortenText($text, $length = 150)
    {
        if (strlen($text) > $length) {
            $arr = explode(" ", $text);
            $str = '';
            foreach($arr as $key => $val) {
                if (strlen($str) <$length) {
                    $str.=" " . $val;
                } else {
                    $str.="...";
                    break;
                }
            }
            $text = $str;
        }
        return $text;
            
    }

    // remove junk characters, but don't format or hyperlink
    public function cleanText($text) 
    {
       $text = preg_replace("~\\n|\\r~", " ", $text);
       $text = htmlspecialchars_decode($text);
       $text = $this->removeNonAscii($text);
       $text = $this->convertMS($text);
       $text = $this->convertQuotes($text);
       return $text; 
    }
    
    public function removeNonAscii($text)
    {
    	return preg_replace('/[^(\x20-\x7F)]*/','', $text);
    }
    
    public function parseAt($text)
    {
    
    	// determine if user is twitter user or fb or insta?
    	// replace all @username with hyperlink to username on twitter
    	// (?<!\S) = Assert that it's impossible to match a non-space character before the match
    	if (substr($text, 0, 1) == '.') {
    		$text = substr($text, 1);
    	}
    	$text = preg_replace('~(?<!\S\/)@([a-z0-9_]+)~i', '<a target="_blank" href="http://' . $this->domain . '/$1">@$1</a>', $text);
    
    	return $text;
    
    }
    
    public function parseHashtags($text)
    {
        // set urls with hashtags to hold a placeholder and reinsert url back into text after done parsing for hashtags
        $placeHolderArr = array();
        if (preg_match("~http://[^\s'\"]+#~is", $text)) {
            preg_match_all("~http://[^\s'\">]+#[^\s'\">]+~is", $text, $arr);
            if (isset($arr[0]) && count($arr[0]) >0) {
                $placeHolderArr = $arr[0];
                foreach($placeHolderArr as $key => $url) {
                    $text = str_replace($url, $key . '!-placeholder-!', $text);        
                }
            }
        }
//        echo htmlspecialchars($text);
        // add a space between hashtags and the preceding letter
        if (strstr($text, "#")) {
            $arr = explode("#", $text);
            foreach($arr as $val) {
                $text =  str_replace("#" . $val, " #" . $val, $text);  
            }
        }
        if ($this->domain=='instagram.com') {
           // hashtags aren't clickable in browser, so point to facebook
    	   return preg_replace('~(^|\s)#([a-z0-9_]+)~i', '$1<a target="_blank" href="http://www.facebook.com/hashtag/$2">#$2</a>', $text);
        } 
        $domain = $this->domain;
        // add spaces to hashtags
    	$text = preg_replace('~(^|\s)#([a-z0-9_]+)~i', '$1<a target="_blank" href="http://' . $domain . '/search?q=%23$2">#$2</a>', $text);

    	//re-insert urls that have hashes in them
    	if (count($placeHolderArr) > 0) {
            foreach($placeHolderArr as $key => $url) {
                $text = str_replace($key . '!-placeholder-!', $url, $text);        
            }
    	}
    	return $text;
    }
    
    public function makeUrlsHyperlinks($text)
    {
    	// put a space after any character that is not a ', " or = and that precedes http
    	preg_match_all("~([^'\"=\s]+)(https?://[^'\"\s>]+)~is", $text, $arr);
    	if (isset($arr[0]) && count($arr[0]) > 0) {
    	    foreach($arr[2] as $needsASpace) {
    	        $text = str_replace($needsASpace, ' ' . $needsASpace, $text);
    	    } 
    	}
    	 
    	//ugh some sites use @ in their urls eg. http://medium.com/@munchery/fuckstick
    	$text = str_replace("/@", "/somedipstick", $text);
 
        //$text = str_replace("www.", "", $text);
        // (?<!\S) = Assert that it's impossible to match a non-space character before the match
        //$text = preg_replace('@((?<!\S)https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$2&raquo;</a>', $text);
        // added (#[^/\s]+)? to above to make it match links with anchor tags eg http://test.com#anchortag
        $text = preg_replace('@((?<!\S)https?://([-\w\.]+[-\w])+(:\d+)?(#[^/\s]+)?(/([\w/_\.#+-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$2&raquo;</a>', $text);
    	$text = str_replace("/somedipstick", "/@", $text);
        //echo htmlspecialchars($text)."<br>";
    	return $text;
    	
    }
    
    public function convertMS($text)
    {
    	$search = array(chr(145), chr(146), chr(147), chr(148), chr(151), chr(133));
    
    	$replace = array("'", "'", '"', '"', '-', '...');
    
    	return str_replace($search, $replace, $text);
    
    }
    
    public function convertQuotes($text)
    {
    	$chr_map = array(
    			// Windows codepage 1252
    			"\xC2\x82" => "'", // U+0082->U+201A single low-9 quotation mark
    			"\xC2\x84" => '"', // U+0084->U+201E double low-9 quotation mark
    			"\xC2\x8B" => "'", // U+008B->U+2039 single left-pointing angle quotation mark
    			"\xC2\x91" => "'", // U+0091->U+2018 left single quotation mark
    			"\xC2\x92" => "'", // U+0092->U+2019 right single quotation mark
    			"\xC2\x93" => '"', // U+0093->U+201C left double quotation mark
    			"\xC2\x94" => '"', // U+0094->U+201D right double quotation mark
    			"\xC2\x9B" => "'", // U+009B->U+203A single right-pointing angle quotation mark
    
    			// Regular Unicode     // U+0022 quotation mark (")
    			// U+0027 apostrophe     (')
    			"\xC2\xAB"     => '"', // U+00AB left-pointing double angle quotation mark
    			"\xC2\xBB"     => '"', // U+00BB right-pointing double angle quotation mark
    			"\xE2\x80\x98" => "'", // U+2018 left single quotation mark
    			"\xE2\x80\x99" => "'", // U+2019 right single quotation mark
    			"\xE2\x80\x9A" => "'", // U+201A single low-9 quotation mark
    			"\xE2\x80\x9B" => "'", // U+201B single high-reversed-9 quotation mark
    			"\xE2\x80\x9C" => '"', // U+201C left double quotation mark
    			"\xE2\x80\x9D" => '"', // U+201D right double quotation mark
    			"\xE2\x80\x9E" => '"', // U+201E double low-9 quotation mark
    			"\xE2\x80\x9F" => '"', // U+201F double high-reversed-9 quotation mark
    			"\xE2\x80\xB9" => "'", // U+2039 single left-pointing angle quotation mark
    			"\xE2\x80\xBA" => "'", // U+203A single right-pointing angle quotation mark
    	);
    	$chr = array_keys  ($chr_map); // but: for efficiency you should
    	$rpl = array_values($chr_map); // pre-calculate these two arrays
    	$text = str_replace($chr, $rpl, html_entity_decode($text, ENT_QUOTES, "UTF-8"));
    	return $text;
    } 
    
}