<?php

namespace Cam\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Cam\Model\Cam;
use Cam\Form\CamForm;
use Zend\View\Model\JsonModel;


class CamController extends AbstractActionController
{
    protected $camTable;
    protected $camModel;
    
    public function checkPermission()
    {
        if ($this->zfcUserAuthentication()->hasIdentity()) {
            return true;
        }
        header("location: /user");
        exit;
    }
    
    public function disallowPerformerAction()
    {

        if ($this->checkPermission()) {
           
            $performerid = $this->params()->fromQuery('performerid', false);
            if ($performerid) {
                $this->getCamTable()->runDisapprovedQuery(array($performerid)); 
                $this->clearCache();
            }
        
        }
        
        return $this->response;
        
    }
    
    public function getCamGirlsOnlineAction()
    {

        $cache = $this->getCache();
        $key = $this->getCacheKey();
        $str = $cache->getItem($key, $success);
        if (!$success) {

            $res = $this->getCamTable()->getCamGirlsOnline();
            $res = $this->getCamModel()->buildCamgirlArr($res);
            if ($res) {
                $cache->setItem($key, serialize($res));
            }

        } else {
            
            $res = unserialize($str);

        }
        
        $jsonModel = new JsonModel(array("result" => $res));
        $jsonModel->setTerminal(true);
        return $jsonModel; 
        
    }
    
    public function getCache()
    {
    
        $cache   = \Zend\Cache\StorageFactory::factory(array(
            'adapter' => array(
                'name' => 'filesystem'
            ),
            'options' => array(
                'cache_dir' => 'data/cache/camgirls/',
                'ttl' => 50 
            ),
            'plugins' => array(
                'exception_handler' => array(
                    'throw_exceptions' => true
                ),
            )
        )); 

        return $cache;
    }
    
    private function getCacheKey()
    {
        return 'online_now';
    }
    
    private function clearCache()
    {

        $cache = $this->getCache();
        $cache->removeItem($this->getCacheKey());

    }

	// set last_ut
	// 
	public function readljAction()
	{
	    
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);

		// light
		$feed='http://live-cams-2.livejasmin.com/allonline/generate.php?site=jsm&psid=bustyshots&campaign_id=27116&pstour=t1&psprogram=REVS&landing_page=freechat&image_count=1&image_size=tmb&flags=1&willingness=1&allmodels=1';
        //echo $feed."<br>\n";exit;
        
        $feed = 'http://live-cams-2.livejasmin.com/custom/?site=jsm&psid=bustyshots&psprogram=REVS&fields=performerid&image_count=1&image_size=tmb&flags=1&landing_page=freechat&allmodels=0';

		//$feed='http://live-cams-2.livejasmin.com/custom/?site=jsm&psid=bustyshots&campaign_id=26640&';
		//$feed.='pstour=t1&psprogram=REVS&fields=subcategory&image_count=1&image_size=full&flags=1';
		//$feed.='&landing_page=freechat&allmodels=1';
        //echo $feed."<br>\n";
		$xml = simplexml_load_file($feed);
		/*
		   [performerinfo] => Array
        (
            [0] => SimpleXMLElement Object
                (
                    [subcategory] => SimpleXMLElement Object
                        (
                        )

                    [SB] => SimpleXMLElement Object
                        (
                        )

                    [performerid] => SimpleXMLElement Object
                        (
                        )

                    [watmb] => SimpleXMLElement Object
                        (
                        )

                    [status] => SimpleXMLElement Object
                        (
                        )

                    [picture] => SimpleXMLElement Object
                        (
                        )

                )
            */

		$i=0;
		$arr = array();
		while(isset($xml->performerinfo[$i]->performerid)) {
			$performerid=(string)($xml->performerinfo[$i]->performerid);
			$status=(string)($xml->performerinfo[$i]->status);
			$SB = (string)($xml->SB);
			$watmb = (string)($xml->watmb);
			$picture = (string)($xml->performerinfo[$i]->picture);
            //				echo $performerid." ".$status."<br>\n";
			//$language=($xml->performerinfo[$i]->language);
			$arr[$performerid]=$picture;
			//$trackArr[]=array("performerid"=>$performerid, "status"=>$status, /*"language"=>$language*/);
			$i++;
		}

		if (count($arr) > 0) {

			$this->getCamTable()->insertPerformer($arr);
			$this->getCamTable()->updateLive($arr);
            $this->makeCamgirlAds();			

		}
		
    	return $this->response;	

	}
	
	private function makeCamgirlAds()
	{
	    
		$adArr = $this->getCamTable()->make300x300Ad();
		foreach($adArr as $key => $ad) {
		    file_put_contents("public/ads/camgirl" . ($key + 1) .".html", $ad);
		    //echo $ad."<br>";
		}
			
	}
	
	private function getNumPerPage()
	{
	    
        // manage num per page
        $numPerPage = $this->params()->fromPost('numPerPage');
        if (is_null($numPerPage) && isset($_COOKIE['numPerPage'])) {
            $numPerPage = $_COOKIE['numPerPage'];
        }
        // default 
        if (!$numPerPage) {
            $numPerPage = 6;
        }
        setCookie('numPerPage', $numPerPage, time()+(86400*30));

        return $numPerPage;
	    
	}
	
	/*
	 * Default 'unapproved' listing
	 */
    public function indexAction()
    {

        $this->checkPermission();
        
        // update any posted data
        $this->updateCamgirls();

        $numPerPage = $this->getNumPerPage();
		$r = $this->getCamTable()->fetchUnapp($numPerPage);	
		return new ViewModel(array("cam"=> $r, "where" => "unapp", "numPerPage" => $numPerPage));

    }

    /*
     * Search for performer by id
     */
    public function getPerfAction()
    {
        
        $this->checkPermission();
		$data = preg_replace("~[^a-zA-Z0-9_]~is", '', $_GET['performerid']);
		$r=$this->getCamTable()->getCam($data);
		$vm = new ViewModel(array("cam"=> $r, "where" => "search"));
		$vm->setTemplate("cam/cam/index");
		return $vm;
        
    }
    
    public function getAppOnlineNowAction()
    {

        $this->checkPermission();
        $this->updateCamgirls();
		$r=$this->getCamTable()->fetchAppOnlineNow();	
		$vm = new ViewModel(array("cam"=> $r, "where" => "app"));
		$vm->setTemplate("cam/cam/index");
		return $vm;

    }
    
    public function getDisappOnlineNowAction()
    {

        $this->checkPermission();
        $this->updateCamgirls();
		$r=$this->getCamTable()->fetchDisappOnlineNow();	
		$vm = new ViewModel(array("cam"=> $r, "where" => "disapp"));
		$vm->setTemplate("cam/cam/index");
		return $vm;

    }
    
    private function updateCamgirls()
    {
        
    	if (isset($_POST['idArr'])){

			$idArr=$_POST['idArr'];
			$boobsArr=isset($_POST['boobsArr'])?$_POST['boobsArr']:false;
			$bestArr=isset($_POST['bestArr'])?$_POST['bestArr']:false;
			$assArr=isset($_POST['assArr'])?$_POST['assArr']:false;

			// set to zero boobs, ass, best and set status to disapp for posted performers
			$this->getCamTable()->setPerformersToZero($idArr);

			// set boobs, ass, best and status for posted performers
			$this->getCamTable()->updateAss($assArr);
			$this->getCamTable()->updateBoobs($boobsArr);
			$this->getCamTable()->updateBest($bestArr);
			
			$this->getCamTable()->addNewGirls();
			
			//$this->flashMessenger()->addMessage("Updated!");
			
		}
		
    }
 
    public function getCamTable()
    {
        if (!$this->camTable) {
            $sm = $this->getServiceLocator();
            $this->camTable = $sm->get('Cam\Model\CamTable');
        }
        return $this->camTable;
    }
    
    public function getCamModel()
    {
        if (!$this->camModel) {
            $sm = $this->getServiceLocator();
            $this->camModel = $sm->get('Cam\Model\CamModel');
        }
        return $this->camModel;
    }
    
    public function clicksoutAction()
    {
        // disabled as it will put a burden on the db
        return false;
        
        $id = $this->params()->fromQuery('id', false);
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($id && $this->getCamTable()->checkClickGate($ip, $id) == false) {
           $this->getCamTable()->insertClick($ip, $id); 
        }
     
        $campaign = $this->params('campaign', '');
        $url = $this->getCamModel()->genTargetUrl($id, $campaign);
        $this->redirect()->toUrl($url);
        return $this->response;
 
    }

}
