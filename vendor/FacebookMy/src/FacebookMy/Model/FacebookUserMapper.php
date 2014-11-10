<?php
namespace FacebookMy\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;

class FacebookUserMapper
{

    // POINT TO `USERS` TABLE
    protected $tableName = 'users';
    protected $dbAdapter;
    protected $sql;
    
    public function __construct(Adapter $dbAdapter)
    {
    	$this->dbAdapter = $dbAdapter;
    	$this->sql = new Sql($dbAdapter);
    	$this->sql->setTable($this->tableName);
    }
    
    public function isWebmaster($userId)
    {
      
        $select = $this->sql->select();
        $select->where(array('user_id' => $userId));
        $select->where(array('is_webmaster' => 1));
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
      	//echo "<br><br>".$select->getSqlString($this->dbAdapter->getPlatform());exit;
      
        $results = $statement->execute();
        if ($results->count() >0 ) {
            return true;
        }
        return false;
        
    }
    
}