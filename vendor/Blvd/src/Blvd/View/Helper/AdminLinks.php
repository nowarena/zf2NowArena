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
use Blvd\Model\SocialMediaModel;

/**
 * View Helper
 *
 */
class AdminLinks extends AbstractHelper
{

	public function __invoke($isWebmaster, SocialMediaModel $socMod)
	{

	    $str = '';
        if ($isWebmaster) {
        	$str.=" &#183;<a href='javascript:void(0);' class='unpubLink' ";
        	$str.="data-social_id='" . $socMod->getSocialId() . "' ";
        	$str.="data-blvdid='" . $socMod->getBlvdId() . "' ";
        	$str.="data-username='" . $socMod->getUsername() . "'>";
        	$str.="unpub</a>"; 
        	if ($socMod->getSource() == 'twitter') {
        	   $arr = array('action' => 'edituser', 'screenname' => $socMod->getUsername());
        	   $str.="&#183;<a class='editTwitterLink' target='_blank' href='" . $this->view->url('twitter', $arr) ."'>edit twit</a>";
        	}
        }
        
        return $str;
	    
	    
	}
	
	
}