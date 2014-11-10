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
class Streetsign extends AbstractHelper
{

    public function __invoke($ent, $view, $rowCount, $displayedArr)
    {
        
        $foundStreetsign = 0;
        $streetsignCount = 0;
        $firstStreetsign = '';
        $lastStreetsign = '';
        $streetsignArr = $this->getArr();
        foreach($streetsignArr as $num => $arr ) {
        	if ((int)$ent->getAddress() > $num && !isset($displayedArr[$num])) {
        		$streetsignCount++;
        		$str = $view->partial('partial/streetsign.phtml', array('streetsign' => $arr[0]));
        		if ($streetsignCount == 1 && $rowCount >0 ) {
        			$firstStreetsign = $str;
        		} else {
        			$lastStreetsign = $str;
        		}
        		$displayedArr[$num] = 1;
        		$foundStreetsign = 1;
        	}
        }
        if ($foundStreetsign) {
        	echo $firstStreetsign . $lastStreetsign;
        }
        if ($foundStreetsign == 0 && $rowCount >0 ) {
        	echo "<hr>";
        }
        
        return $displayedArr;
         
    }

    public function alreadyDisplayed($num) 
    {
        
    }
    
    public function getArr()
    {
        return array(
        
    		999 => array('Brooks Av', 'left'),
    		1010 => array('Broadway St', 'left'),
    		1099 => array('Westminster Av', 'left'),
    		1131 => array('San Juan Av', 'left'),
    		1240 => array('Santa Clara Av', 'left'),
    		1201 => array('Aragon Ct', 'right'),
    		1326 => array('Andalusia Av', 'right'),
    		1302 => array('Cadiz Av', 'right'),
    		1400 => array('California Av', 'left'),
    		1429 => array('Milwood Av', 'left'),
    		1415 => array('Navarre Ct', 'right'),
    		1516 => array('Palms Av', 'left'),
    		1611 => array('Rialto Av', 'right'),
    		1662 => array('Venice Bl', 'left')
            
        );
        
    }
}