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

/**
 * View Helper
 *
 */
class Categories extends AbstractHelper
{
	 
	public function __invoke($categoryArr)
	{
	    
	    if (count($categoryArr) == 0) {
	        return;
	    }

	    $strlenArr = array();
	    foreach($categoryArr as $catId => $catArr) {
	        $cat = $catArr['category'];
	    	$urlCat = preg_replace("~/|\s~is", "-", strtolower($cat));
	    	$strlenArr[]= $cat;
	    }
	    
        $numRows = ceil(count($categoryArr)/4);
        $numCols = ceil(count($categoryArr)/$numRows);
        // style width
        $catWidth = 100/$numCols;
            
	    echo "<div class='moduleHeader'>Categories</div>";

	    echo "<div class='moduleBodyCategories'>";// id='categoriesBody'>";
	    
	    $count = 0;
	    $str = '';
	    foreach($categoryArr as $catId => $catArr) {
	        $cat = $catArr['category'];
	    	$urlCat = preg_replace("~/|\s~is", "-", strtolower($cat));
	    	//$displayCat = str_replace(" and " , " & ", $cat);
	    	$displayCat = $cat;
	    	
	    	$str.= "<div class='categoryBox' style='width:" . $catWidth . "%'>";
	    	$str.= "<a class='categoryLink' href='";
	    	$str.= $this->view->url("social", array(
	    			"action" => "index", 
	    			"category_id" => $catId, 
	    			"category" => $urlCat,
	    		)) . "'>$displayCat</a>";
	    	$str.= "</div>";
	    	$val = $count % $numCols;
	    	if ($val == $numCols - 1){ 
                $str.="<div style='clear:both;'></div>";
	    	}
	    	$count++;
	    }

	    echo "<div>";
	    echo $str;
	    echo "</div>";
        echo "<div style='clear:both;'></div>";
	    echo "</div>";//close categoriesBody
	}
	
}