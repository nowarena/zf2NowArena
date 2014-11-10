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
class AdGlobal extends AbstractHelper
{

	public function __invoke($ad)
	{


	    if ($ad == '300x250') { ?>
	    
    	   <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    	   <!-- 300x250 -->
    	   <ins class="adsbygoogle" style="display:inline-block;width:300px;height:250px" data-ad-client="ca-pub-8986770718983798" data-ad-slot="3894343840"></ins>
           <script>
           (adsbygoogle = window.adsbygoogle || []).push({});
           </script>
	
	   <?php
	   
	    } elseif ($ad == '300x100') {

        ?>
        
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- 320x100 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:320px;height:100px"
                 data-ad-client="ca-pub-8986770718983798"
                 data-ad-slot="4952274642"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>

        <?php
        
        } elseif ($ad == '728x90') {

        ?>
        
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <!-- 728x90Leaderboard -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:728px;height:90px"
                 data-ad-client="ca-pub-8986770718983798"
                 data-ad-slot="7057156242"></ins>
            <script>
            (adsbygoogle = window.adsbygoogle || []).push({});
            </script>

        <?php 

        }

	    
	}
	
}
