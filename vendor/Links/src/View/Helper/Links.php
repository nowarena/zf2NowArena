<?php
/**
 * View\Helper
*
* @author
* @version
*/
namespace Links\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Url;

/**
 * View Helper
 *
 */
class Links extends AbstractHelper
{

	public function __invoke($linkArr)
	{

	    if (count($linkArr) == 0) {
	        return;
	    }

	    echo "<div class='links'>";
		echo "<div class='moduleHeader'>Links</div>";

		echo "<div class='moduleBodyCategories'>";// id='categoriesBody'>";
	  
        $width = 33;
        
		$count = 0;
		$str = '';
		foreach($linkArr as $linkId => $arr) {
			$linkname = $arr['linkname'];
			$str.= "<div class='categoryBox' style='width:" . $width . "%'>";
			$str.= "<a class='categoryLink' href='" . $arr['link'] . "' target='_blank'>" . $linkname . "</a>";
			$str.= "</div>";
		}

		echo "<div>";
		echo $str;
		echo "</div>";
		echo "<div style='clear:both;'></div>";
		echo "</div>";//close categoriesBody
		echo "</div>";//close links
	}

}