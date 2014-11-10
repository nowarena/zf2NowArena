<?php
namespace Game\Model;

class EvalPokerHandModel
{
     
    public function evalHands(array $playerArr) 
    {

        foreach($playerArr as $key => $playerModel) {
            
            $handEnt = $playerModel->getHandEntity();

            $handEnt = $this->setHasAce($handEnt);
            $handEnt = $this->evalHand($handEnt);
            
            if ($handEnt->getHasAce()) {
                $acesLowHandEnt = clone $handEnt;
                $acesLowHandEnt->setAllAcesToLow();
                $acesLowHandEnt->setPokerHandRank(10);
                $acesLowHandEnt = $this->evalHand($acesLowHandEnt);
                if ($acesLowHandEnt->getPokerHandRank() < $handEnt->getPokerHandRank()) {
                    $handEnt = $acesLowHandEnt;
                } else {
                    $handEnt->setAllAcesToHigh();    
                }
            }
 
            $playerArr[$key]->setHandEntity($handEnt);
 
        }

        return $playerArr;

    }
    
    private function evalHand(HandEntity $handEnt)
    {
        // set a presorted array by suit, card value, high to low
        $handEnt = $this->setSortedCardArr($handEnt, "suit");
        $handEnt = $this->setSortedCardArr($handEnt, "value");

        $handEnt = $this->setIsStraightFlush($handEnt );
        $handEnt = $this->setIsFlush($handEnt);
        $handEnt = $this->setIsStraight($handEnt);
        $handEnt = $this->setIsOfAKindOrFullHouse($handEnt);

        return $handEnt;
        
    }
    
    public function setIsStraight(HandEntity $handEnt)
    {

        if ($handEnt->getPokerHandRank() < 6) {
            return $handEnt;
        }
        /*
        if ($handEnt->getIsRoyalStraightFlush() || $handEnt->getIsStraightFlush() || $handEnt->getIsFourOfAKind() || $handEnt->getIsFullHouse() 
            || $handEnt->getIsFlush()) {
            return $handEnt;
        }
        */

        $sequenceArr = array();
        $sortedCardArr = $handEnt->getSortedCardByValueArr();
        foreach($sortedCardArr as $key => $cardArr) {

            if (count($sequenceArr) == 0) {
                $sequenceArr[] = $cardArr;
                continue;
            }    
            $currentCardValue = $cardArr[0];
            $previousCardValue = $sortedCardArr[$key - 1][0];
            if ($currentCardValue + 1 == $previousCardValue) {
                $sequenceArr[] = $cardArr;
                if (count($sequenceArr) > 4) {
                    $handEnt->setPokerHandRank(6)
                        ->setTopCardsArr($sequenceArr);  
                    break;
                }
            } else {
                $sequenceArr = array($cardArr);
            }

        }
        
        return $handEnt;
        
    }
    
    public function setIsFlush(HandEntity $handEnt)
    {
        
        if ($handEnt->getPokerHandRank() < 5) {
            return $handEnt;
        }
        /*
        if ($handEnt->getIsRoyalStraightFlush() || $handEnt->getIsStraightFlush() || $handEnt->getIsFourOfAKind() || $handEnt->getIsFullHouse()) {
            return $handEnt;
        }
        */
        
        $numArr['Hearts'] = 0;
        $numArr['Spades'] = 0;
        $numArr['Clubs'] = 0;
        $numArr['Diamonds'] = 0;
        $sortedCardArr = $handEnt->getSortedCardBySuitArr();
        foreach($sortedCardArr as $key => $cardArr) {
            $suit = $cardArr[1];
            $numArr[$suit]++; 
            $sequenceArr[$suit][] = $cardArr; 
        }
        foreach($numArr as $suit => $num) {
            if ($num >= 5) {
                $suitSetArr[$suit] = 1;
                $handEnt->setTopCardsArr(array_slice($sequenceArr[$suit], 0, 5))
                    ->setPokerHandRank(5);
                break;
            }
        }
        return $handEnt;
        
    }
    
    public function setIsOfAKindOrFullHouse(HandEntity $handEnt) 
    {
        
        if ($handEnt->getPokerHandRank() < 3) {
            return $handEnt;
        }
        /*
        if ($handEnt->getIsRoyalStraightFlush() || $handEnt->getIsStraightFlush()) {
            return $handEnt;
        }
        */

        $kindArr = array();
        $sortedCardArr = $handEnt->getSortedCardByValueArr();
        foreach($sortedCardArr as $key => $cardArr) {
            $cardValue = $cardArr[0];
            $kindArr[$cardValue] = isset($kindArr[$cardValue]) ? ++$kindArr[$cardValue] : 1;
        }
        arsort($kindArr);

        $isFourOfAKind = false;
        $isThreeOfAKind = false;
        $isTwoOfAKind = false;
        $isTwoPair = false;
        $fourOfAKindOf = false;
        $threeOfAKindOf = false;
        $highTwoPairOf = false;
        $lowTwoPairOf = false;
        $numPairs = 0;
        $numThreeOfAKind = 0;
        $sequenceArr = array();
        // cards left over in highest to lowest order after matches are extracted
        $unmatchedArr = $sortedCardArr; 
        foreach($kindArr as $cardValue => $num) {
            if ($num == 4) {
                $fourOfAKindOf = $cardValue;
                $isFourOfAKind = true;
                $arr = $this->buildSequenceArr($sortedCardArr, $unmatchedArr, $cardValue, 1);
                list(, $sequenceArr) = each($arr);
            } elseif ($num == 3) {
                $isThreeOfAKind = true;
                $numThreeOfAKind++;
                if ($numThreeOfAKind == 1) {
                    $threeOfAKindOf = $cardValue;
                    $arr = $this->buildSequenceArr($sortedCardArr, $unmatchedArr, $cardValue, 2);
                    list(, $sequenceArr) = each($arr);
                }
            } elseif ($num == 2) {
                if ($highTwoPairOf == false) {
                    $highTwoPairOf = $cardValue;
                } else {
                    if ($highTwoPairOf > $cardValue) {
                        $lowTwoPairOf = $cardValue;
                    } else {
                        $lowTwoPairOf = $highTwoPairOf;
                        $highTwoPairOf = $cardValue;
                    }
                }
                $numPairs++;
                $isTwoOfAKind = true;
            }
        }

        if ($isFourOfAKind) {
            $handEnt->setTopCardsArr($sequenceArr)
                ->setFourOf($fourOfAKindOf)
                ->setPokerHandRank(3);
        } else if ($numThreeOfAKind == 2) {
            //build 2 sets of three of a kind into a fullhouse
            $sequenceArr = array();
            asort($kindArr);
            foreach($kindArr as $cardValue => $num) {
                foreach($sortedCardArr as $key => $cardArr) {
                    if ($num == 3 && $cardArr[0] == $cardValue && count($sequenceArr) < 5) {
                        $sequenceArr[] = $cardArr;
                        if (count($sequenceArr) == 3){
                            $highTwoPairOf = $cardArr[0];
                        }
                    }
                }
            }
            $sequenceArr = array_splice($sequenceArr, 0, 5);
            $handEnt->setTopCardsArr($sequenceArr)
                ->setFullHouseThreeOfAKindOf($threeOfAKindOf)
                ->setFullHouseTwoOfAKindOf($highTwoPairOf)
                ->setPokerHandRank(4);
            
        } elseif ($isThreeOfAKind && $isTwoOfAKind) {
            $sequenceArr = array();
            foreach($kindArr as $cardValue => $num) {
                foreach($sortedCardArr as $key => $cardArr) {
                    if ($num >= 2 && $cardArr[0] == $cardValue && count($sequenceArr) < 5) {
                        $sequenceArr[] = $cardArr;
                    }
                }
            }
            $sequenceArr = array_slice($sequenceArr, 0, 5);
            $handEnt->setTopCardsArr($sequenceArr)
                ->setFullHouseThreeOfAKindOf($threeOfAKindOf)
                ->setFullHouseTwoOfAKindOf($highTwoPairOf)
                ->setPokerHandRank(4);
        } elseif ($handEnt->getPokerHandRank() > 6) {
        //}elseif ($handEnt->getIsStraight() == false && $handEnt->getIsFlush() == false) {
            if ($isThreeOfAKind) {
                $handEnt->setThreeOf($threeOfAKindOf)
                    ->setPokerHandRank(7)
                    ->setTopCardsArr($sequenceArr);
            } elseif ($isTwoOfAKind && $numPairs >1) {
                $count = 0;
                $sequenceArr = array();
                foreach($kindArr as $cardValue => $num) {
                    if ($num == 2 && $count < 2) {
                        $arr = $this->buildSequenceArr($sortedCardArr, $unmatchedArr, $cardValue, $count);
                        $unmatchedArr = $arr[1];
                        $sequenceArr = array_merge($sequenceArr, $arr[0]);
                        $count++;
                    }
                }
                $handEnt->setHighTwoPairOf($highTwoPairOf)
                    ->setLowTwoPairOf($lowTwoPairOf)
                    ->setPokerHandRank(8)
                    ->setTopCardsArr($sequenceArr);
            } elseif ($isTwoOfAKind) {
                foreach($kindArr as $cardValue => $num) {
                    if ($num == 2) {
                        $arr = $this->buildSequenceArr($sortedCardArr, $unmatchedArr, $cardValue, 3);
                        $sequenceArr = $arr[0];
                    }
                }
                $handEnt->setIsTwoOfAKind(true)
                    ->setTwoOf($highTwoPairOf)
                    ->setPokerHandRank(9)
                    ->setTopCardsArr($sequenceArr);
            } else {
                $handEnt->setTopCardsArr(array_slice($sortedCardArr, 0, 5))
                    ->setPokerHandRank(10);
            }
        }
 
        return $handEnt;
 
    }
 
    public function setIsStraightFlush(HandEntity $handEnt)
    {

        $sortedCardArr = $handEnt->getSortedCardBySuitArr();
        foreach($sortedCardArr as $key => $cardArr) {
            
            if ($key == 0) {
                $sequenceArr[] = $cardArr;
                continue;
            }

            $currentCardValue = $cardArr[0];
            $currentCardSuit = $cardArr[1];
            $previousCardValue = $sortedCardArr[$key - 1][0];
            $previousCardSuit = $sortedCardArr[$key - 1][1];
            // cards have been sorted by suit desc, card value desc
            if ($currentCardValue + 1 == $previousCardValue && $previousCardSuit == $currentCardSuit) {
                $sequenceArr[] = $cardArr;
                if (count($sequenceArr) > 4) {
                    // if we made it this far after starting the loop with an ace, it is a royal straight flush            
                    if ($key == 4 && $sortedCardArr[0][0] == 14) {
                        $handEnt->setPokerHandRank(1)
                            ->setIsRoyalStraightFlush(true)
                            ->setTopCardsArr($sequenceArr);
                        break;
                    }
                    $handEnt->setIsStraightFlush(true)
                        ->setTopCardsArr($sequenceArr)
                        ->setPokerHandRank(2); 
                    break;
                }
            } else {
                $sequenceArr = array();
            }
            
        }
 
        return $handEnt;
        
    }
    
   /*
     * Build array of top five cards
     * 
     * */
    private function buildSequenceArr(array $sortedCardArr, array $unmatchedArr, $cardValue, $padNum = 1) 
    {
     
        $sequenceArr = array();
        foreach($sortedCardArr as $key => $cardArr) {
            if ($cardArr[0] == $cardValue) {
                $sequenceArr[] = $cardArr;
                unset($unmatchedArr[$key]);
            } 
        }
        // remaining card is high card
        for($i = 0; $i < $padNum; $i++){
            $sequenceArr[] = array_shift($unmatchedArr);
        }

        return array($sequenceArr, $unmatchedArr);
        
    }
    /*
     * Array
(
    [0] => Array
        (
            [0] => 14
            [1] => Hearts
        )

    [1] => Array
        (
            [0] => 10
            [1] => Hearts
        )
     */
    private function setSortedCardArr(HandEntity $handEnt, $sortFirst = "suit")
    {
        
        foreach($handEnt->getCardEntityArr() as $cardEnt) {
            $data[] = array($cardEnt->getValue(),$cardEnt->getSuit());
            $cardValueArr[] = $cardEnt->getValue();
            $cardSuitArr[] = $cardEnt->getSuit();
        }

        if ($sortFirst == "suit") {
            array_multisort($cardSuitArr, SORT_DESC, $cardValueArr, SORT_DESC,  $data);
            $handEnt->setSortedCardBySuitArr($data);
        } else {
            array_multisort($cardValueArr, SORT_DESC, $cardSuitArr, SORT_DESC, $data);
            $handEnt->setSortedCardByValueArr($data);
        }
        return $handEnt;
 
    }
    
    private function setHasAce(HandEntity $handEnt)
    {
        
        foreach($handEnt->getCardEntityArr() as $cardEnt) {
            if ($cardEnt->getFaceValue() == 'Ace') {
                $handEnt->setHasAce(true);
                break;
            }
        }
        
        return $handEnt;
        
    }
    
    
    
}