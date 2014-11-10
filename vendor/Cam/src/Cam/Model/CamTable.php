<?php
namespace Cam\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Cam\Model\CamModel;

class CamTable extends AbstractTableGateway
{
    protected $table = 'camgirls_all';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Cam());

        $this->initialize();
    }

    public function getCamgirlsOnline()
    {
        
        $select = new Select('camgirls');
        $select->order('unixtime_last DESC, best DESC, boobs DESC');
        $select->where(array("status" => 0));
        $select->offset(0);
        $select->limit(40);
        //echo $select->getSqlString($this->adapter->getPlatform());
    	$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		$rows = new ResultSet();
    	$arr = $rows->initialize($result)->toArray();
		return $arr;
        
    }
 
    public function getTopCamgirls()
    {

        $q="SELECT * ";
        $q.="FROM camgirls WHERE ";
        $q.="status='app' ";
        $q.="ORDER BY unixtime_last DESC, best DESC, boobs DESC, ass DESC ";
        /*
        $r = rand(1,5);
        if ($r == 1) {
        	$q.="LIMIT 0,2";
        } elseif ($r == 2) {
        	$q.="LIMIT 2,2";
        } else if ($r == 3) {
        	$q.="LIMIT 4,2";
        } else if ($r == 4) {
        	$q.="LIMIT 6,2";
        } else {
        	$q.="LIMIT 8,2";
        }
        */
        $q.="LIMIT 0, 10";
        $s = $this->adapter->createStatement($q);
		$r = $s->execute();
		$arr = $this->arrWrapper($r);
		$newArr = array();
		foreach($arr as $key => $row) {
		  if ($row['best'] == 1) {
		      $newArr[] = $row;    
		  }
		}
	    $r = rand(0,8);
	    if (count($newArr) > 2 ){
	       shuffle($newArr);
	       $newArr = array_slice($newArr, 0, 2);
	    } elseif (count($newArr) == 0) {
		    $newArr[] = $arr[$r];
		    $newArr[] = $arr[$r + 1];
		} elseif (count($newArr) == 1) {
		    $newArr[] = $arr[$r]; 
		}
		return $newArr;
        
    }
 
    public function make300x300Ad()
    {

        $adArr = array();
        $camModel = new CamModel();
        $arr = $this->getTopCamgirls();
        foreach($arr as $key => $row) {
           $adArr[] = $camModel->get300x300Ad($row['performerid'], 89809, $row['thumb']); 
        }

        return $adArr;
        
    }

    public function updateLive($arr)
    {
        if (count($arr) == 0) {
            return false;
        }
	
		$idArr = array_keys($arr);
		$safeIdArr = array();
		foreach($idArr as $id) {
            $safeIdArr[] = $this->adapter->getPlatform()->quoteValue($id); 
        }
   		$q = "UPDATE camgirls ";
  		$q.= "SET unixtime_last = UNIX_TIMESTAMP() ";
   		$q.= "WHERE performerid  IN (" . implode(", ", $safeIdArr) . ") ";
   		$s = $this->adapter->createStatement($q);
   		$r = $s->execute();
		return $r; 
        
    }

	public function insertPerformer($dataArr)
	{

	    $offset = 0;
	    $limit = 200;
	    while($arr = array_slice($dataArr, $offset, $limit)) {
    		$q="INSERT INTO camgirls_all (performerid, unixtime, unixtime_last, thumb) VALUES ";
    		foreach($arr as $performerid => $thumb) {
    			$q.="(" . $this->adapter->getPlatform()->quoteValue($performerid) . ", UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), " . $this->adapter->getPlatform()->quoteValue($thumb) . "), ";
    		}
    		$q = substr($q,0,-2)." ";
    		$q.= "ON DUPLICATE KEY UPDATE unixtime_last=UNIX_TIMESTAMP()";
    		$s = $this->adapter->createStatement($q);
    		$r = $s->execute();
    		$offset+=$limit;
	    }

		return $q;

	}
    
    public function addNewGirls(){

        $idArr=array();
        $q="SELECT * FROM camgirls_all WHERE live_date=0 AND status='app'";
        $s=$this->adapter->createStatement($q);
        $r=$s->execute();

        if (count($r)>0 && $r!=false){
            $q="INSERT IGNORE INTO camgirls (performerid, unixtime_last, unixtime, ";
            $q.="status, boobs, ass, thumb, best ";
            $q.=") VALUES ";
            foreach($r as $row){
                $idArr[]=$row['performerid'];
                $q.="('".$row['performerid']."', '".$row['unixtime']."', ";
                $q.="'".$row['unixtime']."', 'app', ";
                $q.="'".$row['boobs']."', '".$row['ass']."', '" . $row['thumb'] . "', '" . $row['best'] ."' ";
                $q.="), ";
            }
            $q=substr($q,0,-2);
            $s=$this->adapter->createStatement($q);
            $r=$s->execute();
    
            $q="UPDATE camgirls_all SET live_date=".time()." WHERE performerid IN ('";
            $q.=implode("', '", $idArr)."')";
            $s=$this->adapter->createStatement($q);
            $r=$s->execute();

        }

    }
	
    public function checkClickGate($ip, $id) 
    {

        $dateYMD = date("Y-m-d");
        $select = new Select('click_gate');
        $select->where(array('ip' => $ip, 'dateYMD' => $dateYMD, 'id' => $id));
        $rowset = $this->selectWith($select);
        return $rowset->count();
        
    }
    
    public function insertClick($ip, $id)
    {

        $ipArr = explode(".", $ip);
        $ip = $ipArr[0] . '.' . $ipArr[1] . '.' . $ipArr[2];
        $dateYMD = date("Y-m-d");
        $data = array(
            'dateYMD' => $dateYMD,
            'ip'  => $ip,
            'id' => $id
        );
    
        $sql = new Sql($this->adapter);
        $insert = $sql->insert('click_gate');
        $insert->values($data);
        $sqlString = $sql->getSqlStringForSqlObject($insert);
        $results = $this->adapter->query($sqlString, Adapter::QUERY_MODE_EXECUTE);
        
        
    }
    

	public function updateBoobs($boobsArr)
	{

		if ($boobsArr){
			$q="UPDATE camgirls_all SET boobs=1, status='app', ";
			$q.="status_mod_date=".time()." ";
			$q.="WHERE performerid IN ('".implode("', '",$boobsArr)."')";
			$s=$this->adapter->createStatement($q);
			$r = $s->execute();
			$q="UPDATE camgirls SET boobs=1, status='app' ";
			$q.="WHERE performerid IN ('".implode("', '",$boobsArr)."')";
			$s=$this->adapter->createStatement($q);
			$r = $s->execute();
		}

	}
	
	public function updateAss($assArr)
	{

		if ($assArr){
			$q="UPDATE camgirls_all SET ass=1, status='app', ";
			$q.="status_mod_date=".time()." ";
			$q.=" WHERE performerid IN ('".implode("', '",$assArr)."')";
			$s=$this->adapter->createStatement($q);
			$s->execute();
			$q="UPDATE camgirls SET ass=1, status='app' ";
			$q.="WHERE performerid IN ('".implode("', '",$assArr)."')";
			$s=$this->adapter->createStatement($q);
			$s->execute();
		}

	}
	
	public function updateBest($bestArr)
	{

		if ($bestArr){
			$q="UPDATE camgirls_all SET best=1, status='app', ";
			$q.="status_mod_date=".time()." ";
			$q.=" WHERE performerid IN ('".implode("', '",$bestArr)."')";
			$s=$this->adapter->createStatement($q);
			$s->execute();
			$q="UPDATE camgirls SET best=1, status='app' ";
			$q.="WHERE performerid IN ('".implode("', '",$bestArr)."')";
			$s=$this->adapter->createStatement($q);
			$s->execute();
		}

	}

	
   public function getCam($id) 
   {
        //$id  = (int) $id;
        $resultSet = $this->select(array('performerid' => $id));
		$rows = new ResultSet();
    	$arr = $rows->initialize($resultSet)->toArray();
    	$camModel = new CamModel;
    	$arr = $camModel->buildCamgirlArr($arr);
        return $arr;


    }

	public function updateDisapproved($boobsArr, $assArr, $bestArr, $idArr)
	{

	    $arr = array();
	    $boobsArr = is_array($boobsArr) ? $boobsArr : array();
	    $assArr = is_array($assArr) ? $assArr : array();
	    $bestArr = is_array($bestArr) ? $bestArr : array();
	    
		$arr = array_unique(array_merge($boobsArr,$assArr));
		$arr = array_unique(array_merge($bestArr, $arr));
		
		foreach($arr as $id){
			foreach($idArr as $key=>$idVal){
				if ($id==$idVal){
					unset($idArr[$key]);
					break;
				}
			}
		}
		$this->runDisapprovedQuery($idArr);

	}
	
	public function setPerformersToZero($idArr)
	{
	    
		$q="UPDATE camgirls_all SET status='disapp', boobs = 0, ass = 0, best = 0, live_date = 0  ";
		$q.="WHERE performerid IN ('" . implode("', '", $idArr) . "')";
		$s=$this->adapter->createStatement($q);
		$s->execute();

		$q="UPDATE camgirls SET status='disapp', boobs = 0, ass = 0, best = 0 ";
		$q.="WHERE performerid IN ('" . implode("', '", $idArr) . "')";
		$s = $this->adapter->createStatement($q);
		$r = $s->execute();

	}

	public function runDisapprovedQuery($idArr)
	{

		if (count($idArr)>0){
    		$safeIdArr = array();
    		foreach($idArr as $id) {
                $safeIdArr[] = $this->adapter->getPlatform()->quoteValue($id); 
                $safeIdStr = implode(", ", $safeIdArr);
            }
            /*
			$q="UPDATE camgirls_all SET status='disapp', boobs = 0, ass = 0, best = 0, ";
			$q.="status_mod_date=".time()." ";
			$q.="WHERE performerid IN (" . $safeIdStr . ")";
			$s=$this->adapter->createStatement($q);
			$s->execute();
			*/
			$q = "DELETE FROM camgirls WHERE performerid IN (". $safeIdStr . ")";
			$s=$this->adapter->createStatement($q);
			$s->execute();
			
		}
	}

	public function fetchAppOnlineNow()
	{

	    $select = new Select('camgirls');
		$select->where(array('unixtime_last>UNIX_TIMESTAMP() - 300'));
		$select->where(array('status'=>'app'));
		$select->order("unixtime_last DESC, best DESC, boobs DESC");
		$select->offset(0);
		//$select->limit(40);
		
		$resultSet = $this->selectWith($select);
        return $this->arrWrapper($resultSet);

	}

	// Disapproved NOT unapproved
	public function fetchDisappOnlineNow()
	{

		$select = new Select("camgirls_all");
		$select->where(array('unixtime_last>unix_timestamp()-300'));
		$select->where(array('status="disapp"'));
		$select->order("unixtime_last DESC");
		$select->offset(0);
		$select->limit(20);
		$resultSet = $this->selectWith($select);
        return $this->arrWrapper($resultSet);

	}


	public function fetchOnlineNow()
	{

		$select=$this->getSql()->select();
		$select->where(array('unixtime_last>unix_timestamp() - 300'));
		$select->order("unixtime_last DESC, best DESC, boobs DESC");
		$resultSet = $this->selectWith($select);
        return $this->arrWrapper($resultSet);

	}

	public function fetchUnapp($numPerPage)
	{

		$select=$this->getSql()->select();
		$select->where(array('status'=>'unapp'));
		$select->order("unixtime_last DESC");
		$select->limit((int)$numPerPage);
        //echo $select->getSqlString($this->adapter->getPlatform());
        $resultSet = $this->selectWith($select);
        

        return $this->arrWrapper($resultSet);

	}
	
	private function arrWrapper($resultSet)
	{
	    
		$rows = new ResultSet();
    	$arr = $rows->initialize($resultSet)->toArray();
    	$camModel = new CamModel;
    	$arr = $camModel->buildCamgirlArr($arr);
    	return $arr;
    	
	}


    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

}