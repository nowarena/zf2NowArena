<?php
namespace Gallery\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\ArraySerializable;
use Twitter\Model\TweetEntity;
use Instagram\Model\InstagramEntity;
use Facebook\Model\FacebookEntity;
use Yelp\Model\YelpEntity;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Tumblr\Model\TumblrEntity;
use Gallery\Model\GalleryModel;
use Base\Model\BaseMapper;

class GalleryMapper extends BaseMapper //implements ServiceLocatorAwareInterface
{
	protected $tableName = 'gallery';
	protected $dbAdapter;
	protected $sql;

	public function __construct(Adapter $dbAdapter)
	{
		$this->dbAdapter = $dbAdapter;
		$this->sql = new Sql($dbAdapter);
		$this->sql->setTable($this->tableName);
	}

	public function insertEnt(GalleryEntity $socMedEnt)
	{
	    
        $select = $this->sql->select();
        $select->where(array("media_url" => $socMedEnt->getMediaUrl()));
        $result = $this->hydrateResult(new GalleryEntity(), $select, false);
        if ($result->count() == 0 ) {
            $hydrator = new ClassMethods();
            $data = $hydrator->extract($socMedEnt);
            $data['date_inserted'] = date("Y-m-d H:i:s");
            $action = $this->sql->insert();
            $action->values($data);
            $statement = $this->sql->prepareStatementForSqlObject($action);
            //echo "<hr>"; echo $action->getSqlString($this->dbAdapter->getPlatform()); echo "<hr>asdf";
            
            // clear cache
            $dateYMon = date("Y-M");
            $galleryModel = new GalleryModel;
            $cacheDir = $galleryModel->getThumbGalleryCacheDir($dateYMon);
            if (is_dir($cacheDir)) {
                $galleryModel->removeDirectoryContents($cacheDir);
            }
            
            return $statement->execute();
        } else {
            // don't update date_inserted, it just messes up the gallery thumb page when, for example, a pic from sept 30 gets set to oct 1
            // alternatively, that effect could be a good thing as it would keep the day one page of each month populated with thumbs
            /*
            $hydrator = new ClassMethods();
            $data = $hydrator->extract($socMedEnt);
            $update = $this->sql->update();
            $update->set(array("date_inserted" => $data['date_inserted']))
                ->where(array("username" => $socMedEnt->getUsername(), "social_id" => $socMedEnt->getSocialId()));
            $statement = $this->sql->prepareStatementForSqlObject($update);
            $r =  $statement->execute();
            //echo "<hr>"; echo $update->getSqlString($this->dbAdapter->getPlatform()); echo "<hr>asdf";
            return $r;
            */
            return true;
            
        }

        return false; 
        
	}

    public function disallowPicture($id)
    {

       $update = $this->sql->update();
	   $update->set(array('unpublish'=>1));
	   $update->where(array("id" => $id));
       $statement = $this->sql->prepareStatementForSqlObject($update);
       $r =  $statement->execute();
       return $r;
        
    }

    public function getPicture($id, $position)
    {

        $select = $this->sql->select();
        $id  = (int) $id;
        if ($position == 'current') {
            $select->where(array('id' => $id, 'unpublish' => 0, 'media_url IS NOT NULL'));
        } elseif ($position == 'next') {
            $select->where(array("id < $id", 'unpublish' => 0, 'media_url IS NOT NULL'));
            $select->order('id DESC')->limit(1);
        } elseif ($position == 'prev') {
            $select->where(array("id > $id", 'unpublish' => 0, 'media_url IS NOT NULL'));
            $select->order('id ASC')->limit(1);
        }
        //echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());exit;
   		$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
        if ($result->count() == 0) {
            if ($position == 'prev') {
                return 'top reached';
            } elseif ($position == 'next') {
                return 'end reached';    
            }
        }
		
		$socMediaModel = new GalleryModel();
		$socMediaModel->exchangeArray($result->current());
		return $socMediaModel;

    }
   
    public function getMonthlyNav()
    {

        // can't get the join to work using zf2 methods, so using raw query
        $q = "SELECT *, count(*) as total FROM ";
        $q.= "(SELECT id, date_inserted as date_inserted_full, source, media_url, ";
        $q.= "CONCAT(YEAR(date_inserted), '-', DATE_FORMAT(date_inserted, '%b')) AS `date_inserted` ";
        $q.= "FROM `gallery` ";
        $q.= "WHERE unpublish=0 AND media_url IS NOT NULL ";
        $q.= "ORDER BY `date_inserted_full` DESC, id DESC) as nav ";
        $q.= "GROUP BY `date_inserted` ";
        $q.= "ORDER BY date_inserted_full DESC, id DESC ";
        //echo $q;
        
        /* needs to be a subselect to get order/group working
        $select = $this->sql->select();
        $select->columns(array('source', 'media_url', 'date_inserted' => new \Zend\Db\Sql\Expression("CONCAT(YEAR(date_inserted), '-', MONTH(date_inserted))")));
        $select->where(array("unpublish=0"));
        $select->group('date_inserted');
        $select->order("date_inserted DESC");
        $select->limit($offset, $limit);
        echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());exit;
        $statement = $this->sql->prepareStatementForSqlObject($select);
        */
        
        $statement = $this->dbAdapter->createStatement($q);
        $result = $statement->execute();
        
        $entityPrototype = new GalleryModel();
		$hydrator = new ClassMethods();
		$resultset = new HydratingResultSet($hydrator, $entityPrototype);
		$resultset->initialize($result);
		$resultset->buffer();
		
        $newArr = array();
        foreach($resultset as $key => $socMediaModel) {
            $newArr[$key]['media_url'] = $socMediaModel->getThumb();
            $newArr[$key]['total'] = $socMediaModel->getTotal();
            $newArr[$key]['dateYMon'] = date("Y-M", strtotime($socMediaModel->getDateInserted()));
        }
        return $newArr;
        
    }
    
    public function fetchThumbArr( $dateYMon, $offset, $limit, $prevDateYMon, $nextDateYMon, $prevTotal )
    {

        $select = $this->sql->select(); 
        $dateUt = mktime(0, 0, 0, date("m", strtotime($dateYMon)), 1, date("Y", strtotime($dateYMon)));
        $select->where(array("unpublish=0", "date_inserted >= '" . date("Y-m-d 00:00:00", $dateUt) . "'"));
        $year = date("Y", strtotime($dateYMon));
        $month = date("m", strtotime($dateYMon));
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dateUt = mktime(0, 0, 0, $month, $daysInMonth, $year);
        $select->where(array("date_inserted <= '" . date("Y-m-d 23:59:59", $dateUt) . "'"));
        $select->where(array('media_url IS NOT NULL'));
        $select->order('id DESC');
        $select->offset($offset);
        $select->limit($limit);
        $str = $select->getSqlString($this->dbAdapter->getPlatform());
    	$statement = $this->sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		if ($result->count() == 0) {
		    // if the date being requested is today's date and their are no results, return false so as to retry with previous month
		    return false;
		} 

        $hydrator = new ClassMethods(); 
        $resultSet = new HydratingResultSet($hydrator, new GalleryModel());
        $resultSet->initialize($result);
        $resultSet->buffer();
        $thumbArr = array();
        $dateNowYM = date("Y-M");
        $dateNextYM = 0;
        $showNextPageLink = 0;
        $showNextMonthLink = 0;
        $key = 0;
        foreach($resultSet as $key => $obj) {
            $thumbArr[$key]['thumb'] = $obj->getThumb();
            $thumbArr[$key]['id'] = $obj->getId();
            $dateYMon = date("Y-M", strtotime($obj->getDateInserted()));
            $thumbArr[$key]['dateYMon'] = $dateYMon;
        }
        if ($limit == $resultSet->count()) {
            // TODO readjust query to get from first of next month in where clause, and if first of next month is returned, showNextMonthLink instead
            $showNextPageLink = 1;    
        } elseif ($nextDateYMon != 0) {
            $showNextMonthLink = 1;
        }
        $key++;
        $thumbArr[$key]['nav']['dateYMon'] = $dateYMon;
        $thumbArr[$key]['nav']['prevDateYMon'] = $prevDateYMon;
        $thumbArr[$key]['nav']['nextDateYMon'] = $nextDateYMon;
        $thumbArr[$key]['nav']['showNextPageLink'] = $showNextPageLink;
        $thumbArr[$key]['nav']['showNextMonthLink'] = $showNextMonthLink;
        $thumbArr[$key]['nav']['page'] = $offset/$limit;
        $maxPage = floor($prevTotal/$limit);
        $thumbArr[$key]['nav']['prevMaxPage'] = $maxPage;
        $thumbArr[$key]['nav']['limit'] = $limit;
        return $thumbArr;
        
    }

    // for use with the google style numbered results navigation
    public function fetchAllPaginated( $itemCountPerPage, $dateYM = false)
    {

        
        // create a new Select object for the table album
        $select = new Select($this->table);
        $select->where(array("unpublish=0"));
        //$select->join("blvd", "blvd.id = gallery.blvd_id", SELECT::SQL_STAR, SELECT::JOIN_INNER);
        $select->order('date_inserted DESC');
        //echo $select->getSqlString($this->dbAdapter->getPlatform());

        $resultSetPrototype = new ResultSet();
        $resultSetPrototype->setArrayObjectPrototype(new GalleryModel());
        // create a new pagination adapter object
        $paginatorAdapter = new DbSelect(
            // our configured select object
            $select,
            // the adapter to run it against
            $this->dbAdapter,
            // the result set to hydrate
            $resultSetPrototype
        );
        $paginator = new Paginator($paginatorAdapter);
        
        if ($dateYM) {
            $select = $this->sql->select();
            $select->columns(array('num' => new \Zend\Db\Sql\Expression('COUNT(*)'))); 
            $select->where(array("unpublish=0"));
            $dateUt = mktime(0, 0, 0, date("m", strtotime($dateYM)) + 1, 1, date("Y", strtotime($dateYM)));
            $select->where(array("date_inserted <= '" . date("Y-m-d 00:00:00", $dateUt) . "'"));
            //echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());exit;
            $statement = $this->sql->prepareStatementForSqlObject($select);
            $result = $statement->execute();
            $rows = new ResultSet();
            $arr = $rows->initialize($result)->toArray();
            $num = $arr[0]['num'];
            $totalItemCount = $paginator->getTotalItemCount(); 
            $page = ceil(($totalItemCount - $num)/36);
            $paginator->setCurrentPageNumber($page);	
        }
        
        $paginator->setItemCountPerPage($itemCountPerPage);
        return $paginator;
        
    }	

}