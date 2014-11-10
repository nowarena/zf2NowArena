<?php
/**
 * View\Helper
*
* @author
* @version
*/
namespace Blvd\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Url;
use Blvd\Model\BlvdModel;
use Home\View\Helper\SocialContent;

/**
 * View Helper
 *
 */
class Container extends AbstractHelper
{
	 
	public function __invoke(BlvdModel $ent, $socialMediaEnt = '')
	{
	    
	    $isMobile = stristr($_SERVER['HTTP_USER_AGENT'], 'mobile');
	    $numCols = $isMobile ? 1 : 2;
	    
	    // header
	    echo "<div class='header'>";
	    
	    echo "<div class='addressAndOffsiteLinks'>";
        echo "<br>";
        
	    if (!$isMobile && $ent->getWebsite() != '' ){
	    	echo "<div class='headerLink'>";
	    	echo " <a target='_blank' href='" . $ent->getWebsite() . "'><span class='headerLinkText'>";
	    	if (substr($ent->getWebsite(), 0, 7) == 'http://') {
	    		//echo substr($ent->getWebsite(),7);
	    	}else{
	    		//echo $ent->getWebsite();
	    	}
	    	echo "site&raquo;</span>";
	    	echo "</a>";
	    	echo "</div>";
	    }
	    
	    if (!$isMobile && $ent->getFacebook() != '') {
	    	echo "<a href='http://www.facebook.com/" . $ent->getFacebook() . "' target='_blank'><div class='socialMediaIcon facebookIcon'>&nbsp;</div></a>";
	    }
	    if (!$isMobile && $ent->getTwitterUsername() != '') {
	    	echo "<a href='https://twitter.com/" . $ent->getTwitterUsername() . "' target='_blank'><div class='socialMediaIcon twitterIcon'>&nbsp;</div></a>";
	    }
	    if (!$isMobile && $ent->getInstagramUsername() != '') {
	    	echo "<a href='http://instagram.com/" . $ent->getInstagramUsername() . "' target='_blank'><div class='socialMediaIcon instagramIcon'>&nbsp;</div></a>";
	    }
	    if (false && $ent->getYoutube() != '') {
	    	echo "<span class='headerLink'><a href='" . $ent->getYoutube() . "' target='_blank'>youtube</a></span>";
	    }
	    if (false && $ent->getPinterest() != '') {
	    	echo "<span class='headerLink'><a href='" . $ent->getPinterest() . "' target='_blank'>pinterest</a></span>";
	    }
	    if (false && $ent->getGoogleplus() != '') {
	    	echo "<span class='headerLink'><a href='" . $ent->getGoogleplus() . "' target='_blank'>googleplus</a></span>";
	    }
	    if (false && $ent->getTumblr() != '') {
	    	echo "<span class='headerLink'><a href='" . $ent->getTumblr() . "' target='_blank'>tumblr</a></span>";
	    }
	    if (false && $ent->getFoursquare() != '') {
	    	echo "<span class='headerLink'><a href='" . $ent->getFoursquare() . "' target='_blank'>foursquare</a></span>";
	    }
	    if (false && $ent->getYelp() != '') {
	    	echo "<span class='headerLink'><a href='http://yelp.ca/biz/" . $ent->getYelp() . "' target='_blank'>yelp</a></span>";
	    }
	    
	    echo "</div>";//close offset links

	    // profile thumbnail/
	    if ($ent->getProfilePictureUrl() !='' ) {
	        echo "<div class='profilePicContainer'>";
	    	echo "<img class='profilePic' id='profilePic_" . $ent->getId() . "' data-src='" . $ent->getProfilePictureUrl() . "' width='50' height='50'>";
	    	echo "</div>";
	    }

	    // display name
	    echo "<div class='bizname'>" . $ent->getDisplayName() . "</div>";
	    
	    // reservation url
	    if ($ent->getReservationUrl() !='' ) {
	       echo "<div class='reserveTable'";
	       if ($ent->getProfilePictureUrl() == '' ){
	           echo 'style="margin-left:4px;"';
	       }	
	       echo ">";
	       echo " <a href='" . $ent->getReservationUrl() . "' target='_blank'><span class='reserveTableLink'>Reserve Table&raquo;</span></a> ";
	       echo "</div>";
	    }
        if ($ent->getOrderOnline() != '' ) {
	       echo "<div class='reserveTable'";
	       if ($ent->getProfilePictureUrl() == '' ){
	           echo 'style="margin-left:4px;"';
	       }	
	       echo ">";
	       echo " <a href='" . $ent->getOrderOnline() . "' target='_blank'><span class='reserveTableLink'>Order Online&raquo;</span></a> ";
	       echo "</div>";
	    }
	    // only display secondary category if no res url or order online
	    if ($ent->getReservationUrl() == '' && $ent->getOrderOnline() == '') {
	       // don't show the link to the category if it is hte category already being viewed
	       if ($ent->getSecondaryCategory() != '' && $ent->getSecondaryCategoryId() != $ent->getCategoryId()) {
    	       echo "<div class='secondaryCategoryLink'>";
    	       $cat = $ent->getSecondaryCategory();
    	       $urlcat = preg_replace("~[^A-Z0-9_-]+~is", "_", $cat);
    	       $arr = array("action" => "index", "category_id" => $ent->getSecondaryCategoryId(), "category" =>$urlcat);
    	       echo "<a href='" . $this->view->url("home", $arr) . "'>" . $cat . "&raquo;</a>";
    	       echo "</div>";
	       }
	    }

	    echo "<div style='clear:both;'></div>";
	    echo "</div>";// close header row

	    // feed navs, left, right and reload
	    $blvdId = $ent->getId();
	    $str = "<div class='socialNavs'>";

	    $offset = 0;
	    
	    $str.= "<div class='nextFeed'>";
	    $str.= "<a ";
	    $str.= "data-blvdid='" . $blvdId . "' ";
	    $str.= "data-category_id='" . $ent->getCategoryId() . "' ";
	    $str.= "data-offset='" . $offset . "' ";
	    $str.= "data-numcols='" . $numCols . "' ";
	    $str.= "id='nextAjaxLink_" . $blvdId . "' class='ajaxLink' href='javascript:void(0);'>";
	    $str.= "<span id='arrow_" . $blvdId . "'>&raquo;</span>";
	    $str.= "</a>";
	    $str.= "</div>";
	    
	    $str.= "<div class='prevFeed'>";
	    $str.= "<a ";
	    $str.= "data-blvdid='" . $blvdId . "' ";
	    $str.= "data-category_id='" . $ent->getCategoryId() . "' ";
	    $str.= "data-offset='" . $offset . "' ";
	    $str.= "data-numcols='" . $numCols . "' ";
	    $str.= "id='prevAjaxLink_" . $blvdId . "' class='ajaxLink' href='javascript:void(0);'>";
	    $str.= "<span id='arrow_" . $blvdId . "'>&laquo;</span>";
	    $str.= "</a>";
	    $str.= "</div>";

	    $str.= "<div class='reloadIcon'>";
	    $str.= "<a ";
	    $str.= "data-blvdid='" . $blvdId . "' ";
	    $str.= " data-offset='0' id='reloadAjaxLink_" . $blvdId . "' class='ajaxLink' href='javascript:void(0);'>";
	    $str.= "<span id='reload_" . $blvdId . "'>&#x21bb;</span>";
	    $str.= "</a>";
	    $str.= "</div>";

	    $str.=  "</div>";
	    echo $str;
	    
	    echo "<div class='socialContent' id='_" . $blvdId . "'>";
	    // social content gets populated with an ajax call, however, we may already have social content
	    // when clicking 'next' or 'prev' feed in the category (/nextbiz) or on first page load, socialMediaEnt is returned at the same time as container data
	    if ($socialMediaEnt != '') {
	        echo $this->view->displaySocialContent($socialMediaEnt, $this->view->isWebmaster);
	    } 
	    echo "</div>";
	    
	    echo "<div id='loadingani_" . $ent->getCategoryId() . "' class='loadingAni'><img src='/img/loadingani.gif' border='0'></div>";
	}
	
}