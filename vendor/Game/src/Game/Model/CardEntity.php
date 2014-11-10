<?php
namespace Game\Model;

class CardEntity
{
    
    protected $faceValue;
    protected $cardValue;
    protected $fileName;
    protected $suit;
    
    function __clone()
    {
        //$this->cardValue = clone $this->cardValue;
    }

    public function getFaceValue()
    {
        return $this->faceValue;
    }

    public function setFaceValue($faceValue)
    {
        $this->faceValue = $faceValue;
        return $this;
    }

    public function getValue()
    {
        return $this->cardValue;
    }

    public function setValue($cardValue)
    {
        $this->cardValue = $cardValue;
        return $this;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function setFileName($suit, $faceValue)
    {
        if (is_numeric($faceValue)) {
            $card = $faceValue;
        } else {
            $card = substr($faceValue, 0, 1);    
        }
        $this->fileName = $card . substr($suit, 0, 1) . '.jpg';
        return $this;
    }

    public function getSuit()
    {
        return $this->suit;
    }

    public function setSuit($suit)
    {
        $this->suit = $suit;
        return $this;
    }

}