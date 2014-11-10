<?php
/**
 * helper
 * 
 * @author
 * @version 
 */
namespace Blvd\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * View Helper
 */
class SocialContent extends AbstractHelper
{

    public function __invoke($socialMediaEnt, $isWebmaster = false, $showNextFeedLink = false)
    {

        $username = '';
        $blvdId = 0;
        $lastId = 'end';
        $firstId = 0;
        $count = 0;
        $isMobile = stristr($_SERVER['HTTP_USER_AGENT'], 'mobile');
        $numCols = $isMobile ? 1 : 2;
        $str = '';
       	$widthStyle = ' style="width:50%"';
       	//TODO
       	if ($isMobile) {
       		$widthStyle = ' style="width:100%"';
       	}
       	
        foreach($socialMediaEnt as $key => $socEnt) {
            
            $blvdId = $socEnt->getBlvdId();
            $username = $socEnt->getUsername();
            
        	$count++;
        	$str.="<div class='box' $widthStyle>";
        	
        	$str.="<div class='postHeader'>";
            $age =  $socEnt->getAge();
            $str.= $age . " &#183; " . $socEnt->getMonthDay();

            $str.= $this->view->getAdminLinks($isWebmaster, $socEnt);
            
            $str.="<a href='javascript:void(0);' class='readMoreLink'>&darr;more</a>";
            $str.="<a href='javascript:void(0);' class='readLessLink'>&uarr;less</a>";
            
        	$str.="</div>";//close postHeader
        	
        	$str.="<div class='boxBorder'>";
        	if ($socEnt->getMediaUrl() != '') {
        		$str.="<div class='mediaBox'>";
        		if ($socEnt->getLink() != '') {
        			$str.="<a target='_blank' href='" . $socEnt->getLink() . "'>";
        		}
        		$str.="<img class='media'  ";
        		$str.="src='" . $socEnt->getMediaUrl();
                if ($socEnt->getSource() == 'twitter' && !stristr($socEnt->getMediaUrl(), ":thumb")) {
                    $str.=":thumb";
                }
        		$str.="' ";
        		$str.="width='" . $socEnt->getMediaWidth() . "'";
        		$str.="height='" . $socEnt->getMediaHeight() . "'";
        		$str.=">";
        		if ($socEnt->getLink() != '') {
        			$str.="</a>";
        		}
        		$str.="</div>";//close mediaBox 
        	}

        	$str.="<div class='textBox'>" . $socEnt->getText();
        	$str.="</div>";
        	
        	
        	$str.="</div>";//close boxBorder
        	
            
        	$str.="</div>";//close box

        	// get lastId to append to div
        	if ($socEnt->getSocialId() != '') {
        	    $lastId = $socEnt->getSocialId();
        	}
        	
            if ($firstId == 0 ) {
                $firstId = $socEnt->getSocialId();
            }
 
        }

        // end of feed reached
       	$emptyBox = "<div class='box' $widthStyle><div class='postHeader'> &nbsp; </div>";
       	$emptyBox.= "<div class='boxBorder'>";
       	$emptyBox.= "<div class='textBox'>End reached. <a href='javascript:void(0);' class='nextFeedLink'>Next feed&raquo;</a></div>";
       	$emptyBox.= "</div>";
       	$emptyBox.= "</div>";
        if ($str == '' ) {
        	$str.=$emptyBox;
        	if (!$isMobile) {
        	   $str.=$emptyBox;
        	}
        } elseif ($count%$numCols == 1 ) {
        	$str.=$emptyBox;
        }
 
        $str.="<lastid data-lastid='" . $lastId . "' style='display:none'></lastid>";
        $str.="<firstid data-firstid='" . $firstId . "' style='display:none'></firstid>";
		
        return $str; 

    }
    
    
}
