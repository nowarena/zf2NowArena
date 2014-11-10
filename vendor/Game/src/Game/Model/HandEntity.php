<?php
namespace Game\Model;

class HandEntity
{
    
    protected $numCards;
    protected $pokerHandNameArr = array(
        1 => "Royal Straight Flush", 
        2 => "Straight Flush", 
        3 => "Four of a Kind", 
        4 => "Full House", 
        5 => "Flush", 
        6 => "Straight", 
        7 => "Three of a Kind", 
        8 => "Two Pair",
        9 => "Two of a Kind",
        10 => "High Card"
    );
    protected $pokerHandRank = 10;
    protected $cardEntityArr = array();
    //protected $cardArr;
    protected $sortedCardBySuitArr;
    protected $sortedCardByValueArr;
    protected $topCardsArr;//winning 5 cards only
    protected $topCardsValueArr;// winning 5 cards numeric values
    
    protected $isWinningHand = false; 
    /*
    protected $isRoyalStraightFlush = false;
    protected $isStraightFlush = false;
    protected $isFourOfAKind = false;
    protected $isFullHouse = false;
    protected $isFlush = false;
    protected $isStraight = false;
    protected $isThreeOfAKind = false;
    protected $isTwoOfAKind = false;
    protected $isTwoPair = false;
    protected $isAPair = false;
    protected $isHighCard = false;
  */ 
    protected $hasAce = false;
    protected $fourOf = false;
    protected $threeOf = false;
    protected $twoOf = false;
    protected $highTwoPairOf = false; 
    protected $lowTwoPairOf = false; 
    protected $fullHouseThreeOfAKindOf = false; 
    protected $fullHouseTwoOfAKindOf = false; 
    
    function __clone()
    {
        //$this->pokerHandRank = clone $this->pokerHandRank;
        //$this->cardEntityArr = clone $this->cardEntityArr;
    }
    

    public function getNumCards()
    {
        return $this->numCards;
    }

    public function setNumCards($numCards)
    {
        $this->numCards = $numCards;
        return $this;
    }

    public function setAllAcesToHigh()
    {
        $arr = $this->getCardEntityArr();
        
        foreach($arr as $key => $cardEnt) {
            if ($cardEnt->getValue() == 1) {
                $cardEnt->setValue(14);
                $arr[$key] = $cardEnt;
            }
        }
        
        $this->setCardEntityArr($arr);
        
    }
    
    public function setAllAcesToLow()
    {
        
        $arr = $this->getCardEntityArr();
        
        foreach($arr as $key => $cardEnt) {
            if ($cardEnt->getValue() == 14) {
                $cardEnt->setValue(1);
                $arr[$key] = $cardEnt;
            }
        }
        
        $this->setCardEntityArr($arr);
        
    }
    
    public function getHandName()
    {
        $rank = $this->getPokerHandRank();
        if ($rank == false) {
            return '';
        }
        return $this->pokerHandNameArr[$rank];
    }

    public function setHandName($handName)
    {
        $this->handName = $handName;
        return $this;
    }
    
    public function getPokerHandRank()
    {
        return $this->pokerHandRank;
    }

    public function setPokerHandRank($pokerHandRank)
    {
        $this->pokerHandRank = $pokerHandRank;
        return $this;
    }

    public function getCardEntityArr()
    {
        return $this->cardEntityArr;
    }

    public function setCardEntityArr($handArr)
    {
        $this->cardEntityArr = $handArr;
        return $this;
    }
/*
    public function getIsRoyalStraightFlush()
    {
        return $this->isRoyalStraightFlush;
    }

    public function setIsRoyalStraightFlush($isRoyalStraightFlush)
    {
        $this->isRoyalStraightFlush = $isRoyalStraightFlush;
        return $this;
    }

    public function getIsStraightFlush()
    {
        return $this->isStraightFlush;
    }

    public function setIsStraightFlush($isStraightFlush)
    {
        $this->isStraightFlush = $isStraightFlush;
        return $this;
    }

    public function getIsFourOfAKind()
    {
        return $this->isFourOfAKind;
    }

    public function setIsFourOfAKind($isFourOfAKind)
    {
        $this->isFourOfAKind = $isFourOfAKind;
        return $this;
    }

    public function getIsFullHouse()
    {
        return $this->isFullHouse;
    }

    public function setIsFullHouse($isFullHouse)
    {
        $this->isFullHouse = $isFullHouse;
        return $this;
    }

    public function getIsStraight()
    {
        return $this->isStraight;
    }

    public function setIsStraight($isStraight)
    {
        $this->isStraight = $isStraight;
        return $this;
    }

    public function getIsThreeOfAKind()
    {
        return $this->isThreeOfAKind;
    }

    public function setIsThreeOfAKind($isThreeOfAKind)
    {
        $this->isThreeOfAKind = $isThreeOfAKind;
        return $this;
    }

    public function getIsTwoPair()
    {
        return $this->isTwoPair;
    }

    public function setIsTwoPair($isTwoPair)
    {
        $this->isTwoPair = $isTwoPair;
        return $this;
    }

    public function getIsAPair()
    {
        return $this->isAPair;
    }

    public function setIsAPair($isAPair)
    {
        $this->isAPair = $isAPair;
        return $this;
    }

    public function getIsHighCard()
    {
        return $this->isHighCard;
    }

    public function setIsHighCard($isHighCard)
    {
        $this->isHighCard = $isHighCard;
        return $this;
    }
    
    public function getIsFlush()
    {
        return $this->isFlush;
    }

    public function setIsFlush($isFlush)
    {
        $this->isFlush = $isFlush;
        return $this;
    }

*/
    public function getIsWinningHand()
    {
        return $this->isWinningHand;
    }
    
    public function setIsWinningHand($isWinningHand)
    {
        $this->isWinningHand = $isWinningHand;
        return $this;
    }

    public function setStraightArr($arr)
    {   
       $this->straightArr = $arr;
    }
    
    public function getStraightArr()
    {
        return $this->straightArr;
    }

    public function getSortedCardBySuitArr()
    {
        return $this->sortedCardBySuitArr;
    }

    public function setSortedCardBySuitArr($sortedCardBySuitArr)
    {
        $this->sortedCardBySuitArr = $sortedCardBySuitArr;
        return $this;
    }
    
    public function getSortedCardByValueArr()
    {
        return $this->sortedCardByValueArr;
    }

    public function setSortedCardByValueArr($sortedCardByValueArr)
    {
        $this->sortedCardByValueArr = $sortedCardByValueArr;
        return $this;
    }
/*
    public function getCardArr()
    {
        return $this->cardArr;
    }

    public function setCardArr($cardArr)
    {
        $this->cardArr = $cardArr;
        return $this;
    }
 */   
    public function getTopCardsValueArr()
    {
        return $this->topCardsValueArr;
    }

    public function setTopCardsValueArr($topCardsValueArr)
    {
        if(count($topCardsValueArr) > 5){
            echo "More than five cards in topCardsValueArr:";
            printR($topCardsValueArr);
            printR($this);
        } 
        $this->topCardsValueArr = $topCardsValueArr;
        return $this;
    }

    public function getTopCardsArr()
    {
        return $this->topCardsArr;
    }

    public function setTopCardsArr($topCardsArr)
    {
        $this->topCardsArr = $topCardsArr;
        foreach($topCardsArr as $cardArr) {
            $topCardsValueArr[] = $cardArr[0];
        }
        $this->setTopCardsValueArr($topCardsValueArr);
        return $this;
    }

    public function getHasAce()
    {
        return $this->hasAce;
    }

    public function setHasAce($hasAce)
    {
        $this->hasAce = $hasAce;
        return $this;
    }

    public function getIsTwoOfAKind()
    {
        return $this->isTwoOfAKind;
    }

    public function setIsTwoOfAKind($isTwoOfAKind)
    {
        $this->isTwoOfAKind = $isTwoOfAKind;
        return $this;
    }



    public function getPokerHandNameArr()
    {
        return $this->pokerHandNameArr;
    }

    public function setPokerHandNameArr($pokerHandNameArr)
    {
        $this->pokerHandNameArr = $pokerHandNameArr;
        return $this;
    }

    public function getFourOf()
    {
        return $this->fourOf;
    }

    public function setFourOf($fourOf)
    {
        $this->fourOf = $fourOf;
        return $this;        
    }

    public function getThreeOf()
    {
        return $this->threeOf;
    }

    public function setThreeOf($threeOf)
    {
        $this->threeOf = $threeOf;
        return $this;
    }

    public function getTwoOf()
    {
        return $this->twoOf;
    }

    public function setTwoOf($twoOf)
    {
        $this->twoOf = $twoOf;
        return $this;
    }

    public function getHighTwoPairOf()
    {
        return $this->highTwoPairOf;
    }

    public function setHighTwoPairOf($highTwoPairOf)
    {
        $this->highTwoPairOf = $highTwoPairOf;
        return $this;
    }

    public function getLowTwoPairOf()
    {
        return $this->lowTwoPairOf;
    }

    public function setLowTwoPairOf($lowTwoPairOf)
    {
        $this->lowTwoPairOf = $lowTwoPairOf;
        return $this;
    }

    public function getFullHouseThreeOfAKindOf()
    {
        return $this->fullHouseThreeOfAKindOf;
    }

    public function setFullHouseThreeOfAKindOf($fullHouseThreeOfAKindOf)
    {
        $this->fullHouseThreeOfAKindOf = $fullHouseThreeOfAKindOf;
        return $this;
    }

    public function getFullHouseTwoOfAKindOf()
    {
        return $this->fullHouseTwoOfAKindOf;
    }

    public function setFullHouseTwoOfAKindOf($fullHouseTwoOfAKindOf)
    {
        $this->fullHouseTwoOfAKindOf = $fullHouseTwoOfAKindOf;
        return $this;
    }
}