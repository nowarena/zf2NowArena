<?php
namespace Base\Controller;

use Zend\Mvc\Controller\AbstractActionController;

abstract class AbstractBaseController extends AbstractActionController
{

    public $isMobile = false;

    public $numCols = 2;

    public function getIsMobile()
    {
        if (! isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->isMobile = false;
        } else {
            $this->isMobile = preg_match("~mobile~is", $_SERVER['HTTP_USER_AGENT']);
        }
        
        return $this->isMobile;
    }

    public function getNumCols()
    {
        if ($this->getIsMobile()) {
            $this->numCols = 1;
        } else {
            $this->numCols = 2;
        }
        
        return $this->numCols;
    }
}