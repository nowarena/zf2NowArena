<?php
namespace Blvd\Model;

use Zend\Db\TableGateway\TableGateway;

class BlvdJoinCategoryTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
/*
    public function fetchAll($blvdId)
    {
        $resultSet = $this->tableGateway->select()->where(array("blvd_id" => $blvdId));
        return $resultSet;
    }

    public function getBlvdCategories($blvdId)
    {
        $blvdId  = (int) $blvdId;
        $rowset = $this->tableGateway->select(array('bvld_id' => $blvdId));
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }
*/

    public function saveBlvdJoinCategories(BlvdJoinCategory $obj, $blvdId)
    {

        $this->deleteBlvdJoinCategories($blvdId);

        foreach($obj->category_id_arr as $catId) {
            $primary = 0;
            if ($obj->primary == $catId) {
                $primary = 1;
            }
            $data[] = array(
                'blvd_id' => $blvdId,
                'category_id'  => $catId,
                'primary' => $primary
            );
        }

        foreach($data as $arr) {
            $this->tableGateway->insert($arr);
        }

    }

    public function deleteBlvdJoinCategories($blvdId)
    {
        $this->tableGateway->delete(array('blvd_id' => $blvdId));
    }

}