<?php
namespace Game\Model;

use Game\Model\HandEntity;

class PlayerModel
{

    protected $position;
    protected $handEnt = NULL;
    protected $tiedWithArr = array();
    
    public function __construct()
    {
        $this->handEnt = new HandEntity();
        
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    public function getHandEntity()
    {
        return $this->handEnt;
    }
    
    public function setHandEntity(HandEntity $handEnt) 
    {
        $this->handEnt = $handEnt;
    }
    
    public function setTiedWith(array $arr)
    {
        $this->tiedWithArr = $arr;
        return $this;
    }  
    public function getTiedWith()
    {
        return $this->tiedWithArr;
    }
       

}