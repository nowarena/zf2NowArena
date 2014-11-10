<?php

namespace Cam\Model;

class CamModel 
{

    public function buildCamgirlArr($arr, $campaign = null) 
    {

        $newArr = array();
        foreach($arr as $key => $row) {
            $thumb = empty($row['thumb']) ? $this->genTmSrc($row['performerid']) : $row['thumb'];
            $newArr[$key]['thumb'] = $thumb;
            $newArr[$key]['image'] = $this->genImgSrc($row['performerid'], $campaign, $thumb);
            $newArr[$key]['performerid'] = $row['performerid'];
            $newArr[$key]['unixtime_last'] = $row['unixtime_last'];
            $newArr[$key]['lastSeen'] = $this->getLastSeen($row['unixtime_last']);
            $newArr[$key]['ass'] = $row['ass'];
            $newArr[$key]['boobs'] = $row['boobs'];
            $newArr[$key]['status'] = $row['status'];
            $newArr[$key]['url'] = $this->genTargetUrl($row['performerid'], $campaign);
            $newArr[$key]['campaign'] = $campaign;
            $newArr[$key]['best'] = $row['best'];
        }

        return $newArr;
         
    }
    
    public function get300x300Ad($p_id, $campaign_id=35916, $thumb)
    {

    	$out="<div style='overflow:hidden;width:100%;text-align:center;'>";
    	//$href= $this->genRedirectUrl($p_id,$campaign_id);
    	$href = $this->genTargetUrl($p_id,$campaign_id);
    	$out.= $this->getCamPic($p_id,$campaign_id, $thumb);
    	//$out.="<a target='blank' href='/cams'><b>View more camgirls&raquo;</b></a>";
    	$out.="</div>";
    	return $out;
        
    }
    
    public function genRedirectUrl($id, $campaign = null) 
    {

    	$ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
    	preg_match("~http://([^/]+)~is",$ref,$arr);
    	if (isset($arr[1])){
    		$ref=$arr[1];
    		$ref=str_replace("www.","",$ref);
    	}
 
    	return "/cam/cam/clicksout?id=$id&campaign=$campaign&ref=$ref";
	
    }
    
    
    public function getCamPic($p_id,$campaign_id, $thumb) 
    {
        
        $width = 146;
        $height = 110;

    	//$href = $this->genRedirectUrl($p_id,$campaign_id);
    	$href = $this->genTargetUrl($p_id,$campaign_id);
    	$target='_blank';
    	$pic = $this->genImgSrc($p_id);
    	$bgimg = is_null($thumb) ? $this->genTmSrc($p_id) : $thumb;
    	$out="<div style=\"position:relative;text-align:center;background:url('$bgimg')no-repeat center center;width:" . $width . "px;overflow:hidden;";
    	$out.="\">";
    	$out.="<div style='filter: alpha(opacity=70);opacity:0.7;background-color:#ffffff;position:absolute;z-index:2;top:94px;width:100%;text-align:center;height:16px;'>";
    	$out.="<a href='$href' target='$target' style='color:#000000;font-size:10px;'>$p_id Online!</a>";
    	$out.="</div>";
    	$out.="<a href='$href' target='$target'>";
    	$out.="<img id='campic' src='".$pic."' border='0' width='". $width . "' height='" . $height . "'>";
    	$out.="</a>";
    	$out.="</div>";
    	
    	return $out;

    }
    
    public function genImgSrc($p_id){
    
        if ($p_id=="sensualshanax")return "http://31.media.tumblr.com/e57be81df098e9d4efdac224b28266d6/tumblr_n585vwsU3x1rzbdf8o2_r1_400.jpg";
    	return "http://static.awempire.com/jsm/_profile/".(substr($p_id,0,1))."/".(substr($p_id,0,2))."/$p_id/pimage1.jpg";
    
    }
    
    public function genTargetUrl($p_id, $campaign=27116){
    
    	return "http://www.livejasmin.com/freechat.php?psid=bustyshots&pstour=t1&psprogram=REVS&performerid=$p_id&pstool=7_47261&campaign_id=$campaign&gopage=bio";
    
    }

    public function genTmSrc($p_id){

	   return "http://img2.livejasmin.com/wa/".(substr($p_id,0,1))."/".(substr($p_id,0,2))."/$p_id/live/tmbImage.jpg";

    }
    
    public function getLastSeen($ut) 
    {

        $lastSeen = $this->getDaysOld($ut);
        if ($lastSeen == 0) {
            $lastSeen = $this->getHoursOld($ut);
            if ($lastSeen <= 1) {
                $lastSeen = $this->getMinutesOld($ut);
                $lastSeen.=" mins";    
            } else {
                $lastSeen.=" hrs";    
            }
        } else {
            if ($lastSeen > 7) {
                $lastSeen = "Over 7 days";
            } else {
                $lastSeen.= " days";
            }    
        }
        
        return $lastSeen;
        
    }
            
        
    private function getDaysOld($date)
    {
        $ut = $this->setUnixtime($date);
    	$ut = strtotime(date("Y-m-d", $ut));
    	$secs = time() - $ut;
    	return floor($secs/86400);
    }
    
    private function getHoursOld($date)
    {
        $ut = $this->setUnixtime($date);
    	$ut = strtotime(date("Y-m-d H:i:s", $ut));
    	$secs = time() - $ut;
    	return ceil($secs/60/60);
    } 
    
    private function getMinutesOld($ut)
    {
        //$ut = $this->setUnixtime($date);
    	//$ut = strtotime(date("Y-m-d H:i:s", $ut));
    	$secs = time() - $ut;
    	return ceil($secs/60);
    } 

    private function setUnixtime($date)
    {
        if (strstr($date, "-") || strstr($date, "/") || strstr($date, '\\'))  {
            $ut = strtotime($date);
        } else {
            $ut = $date;
        }
        return $ut;
    }
}