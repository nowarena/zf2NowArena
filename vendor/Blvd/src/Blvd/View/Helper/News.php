<?php
/**
 * View\Helper
 * 
 * @author
 * @version 
 */
namespace Blvd\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Utility\Model\Utility;

/**
 * View Helper
 */
class News extends AbstractHelper
{

    public function __invoke($news)
    {
        $utilityObj = new Utility('tumblr.com');
	    if ($news->count() >0 ) {
            echo "<div class='moduleBody' id='newsBody' style='display:none';>";
	    	foreach($news as $key => $obj) {
	    		echo "<div class='moduleRow'>";
	    
	    		if ($obj->getPhoto() !='' ) {
	    			$img = $obj->getPhoto();
	    		} 
	    		echo "<a target='_blank' href='" . $obj->getPostUrl() . "'>";
	    		echo "<img src='" . $img . "' width='50' height='50' style='float:left;margin:0px 2px 0px 0px;'>";
	    		echo "</a>";
	    		echo "<div class='moduleText'>";

	    		$text = '';
	    		if ($obj->getCaption() !='' ) {
	    			$text = $obj->getCaption();
	    		}
	    		if ($obj->getTitle() !='' ) {
	    			$text = $obj->getTitle();
	    		}
	    		if ($obj->getDescription() !='' ) {
	    			$text = $obj->getDescription();
	    		}
	    		echo $utilityObj->parseText($text);
	    		echo " &nbsp; <a target='_blank' href='" . $obj->getPostUrl() . "'>More&raquo;</a>";
	    		echo "</div>";
	    		echo "<div style='clear:both;'></div>";
	    		echo "</div>";
	    	}
	    
	    } else {
            echo "<div class='moduleBodyEmpty' style='display:none;'>";  
	    	echo "<div class='moduleRow'>No news at the moment. Check back later.</div>";
	    }
	    echo "</div>";
    }
}
