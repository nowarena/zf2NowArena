<?php
namespace Game\Model;
/*
use Game\Model\DeckModel;
use Game\Model\PlayerModel;
use Game\Model\CardEntity;
*/
class GameModel 
{
    
    protected $deckModel = null;
    
    public function __construct()
    {
        $this->deckModel = new DeckModel();
        
    }


    
    public function buildPlayerHands($numCards, $numPlayers, $forceHandArr = false) {

        for($i = 0; $i < $numPlayers; $i++) {
            if ($forceHandArr) {
                if (!isset($forceHandArr[$i])) {
                    throw new \Exception("$i is out of bounds in forceHandArr");
                }
                $forceHand = $forceHandArr[$i];
                if ($forceHand == "straightflush1") {
                    $cardEntityArr = $this->deckModel->dealStraightFlush1($numCards);
                }elseif ($forceHand == "straightflush2") {
                    $cardEntityArr = $this->deckModel->dealStraightFlush2($numCards);
                } else if ($forceHand == "royalstraightflush") {
                    $cardEntityArr = $this->deckModel->dealRoyalStraightFlush($numCards);
                } else if ($forceHand == "flush1") {
                    $cardEntityArr = $this->deckModel->dealFlush1($numCards);
                } else if ($forceHand == "flush2") {
                    $cardEntityArr = $this->deckModel->dealFlush2($numCards);
                } else if ($forceHand == "straight1") {
                    $cardEntityArr = $this->deckModel->dealStraight1($numCards);
                } else if ($forceHand == "straight2") {
                    $cardEntityArr = $this->deckModel->dealStraight2($numCards);
                } else if ($forceHand == "straight3") {
                    $cardEntityArr = $this->deckModel->dealStraight3($numCards);
                } else if ($forceHand == "fourofakind1") {
                    $cardEntityArr = $this->deckModel->dealFourOfAKind1($numCards);
                } else if ($forceHand == "fourofakind2") {
                    $cardEntityArr = $this->deckModel->dealFourOfAKind2($numCards);
                } else if ($forceHand == "threeofakind1") {
                    $cardEntityArr = $this->deckModel->dealThreeOfAKind1($numCards);
                } else if ($forceHand == "threeofakind2") {
                    $cardEntityArr = $this->deckModel->dealThreeOfAKind2($numCards);
                } else if ($forceHand == "twopair1") {
                    $cardEntityArr = $this->deckModel->dealTwoPair1($numCards);
                } else if ($forceHand == "twopair2") {
                    $cardEntityArr = $this->deckModel->dealTwoPair2($numCards);
                } else if ($forceHand == "twopair3") {
                    $cardEntityArr = $this->deckModel->dealTwoPair3($numCards);
                } else if ($forceHand == "fullhouse") {
                    $cardEntityArr = $this->deckModel->dealFullHouse($numCards);
                } else if ($forceHand == "twoofakind1") {
                    $cardEntityArr = $this->deckModel->dealTwoOfAKind1($numCards);
                } else if ($forceHand == "twoofakind2") {
                    $cardEntityArr = $this->deckModel->dealTwoOfAKind2($numCards);
                } else if ($forceHand == "twoofakind3") {
                    $cardEntityArr = $this->deckModel->dealTwoOfAKind3($numCards);
                } else if ($forceHand == "twoofakind4") {
                    $cardEntityArr = $this->deckModel->dealTwoOfAKind4($numCards);
                } else if ($forceHand == "highcard1") {
                    $cardEntityArr = $this->deckModel->dealHighCard1($numCards);
                } else if ($forceHand == "highcard2") {
                    $cardEntityArr = $this->deckModel->dealHighCard2($numCards);
                } else if ($forceHand == "highcard3") {
                    $cardEntityArr = $this->deckModel->dealHighCard3($numCards);
                } else if ($forceHand == "highcard4") {
                    $cardEntityArr = $this->deckModel->dealHighCard($numCards);
                } else {
                    throw new \Exception("not finding forceHand value: $forceHand");
                }
            } else {
                $cardEntityArr = $this->deckModel->dealCards($numCards);
            }
            
            $playerArr[$i] = new PlayerModel();
 
            $playerArr[$i]->getHandEntity()->setCardEntityArr($cardEntityArr);
        }
        
        return $playerArr; 
    }
    
    
   
    public function evalPlayers(array $playerArr)
    {
        
        $evalPokerHandModel = new EvalPokerHandModel();

        $topHandEnt = '';
        $topHandKey = false;//must be boolean false
        $tieArr = array();
        $loserKeyArr = array();
        $playerArr = $evalPokerHandModel->evalHands($playerArr);
        // first find the losers before looking for ties. Out of three players, two losers that tie isn't useful
        foreach($playerArr as $key => $playerModel) {
            $handEnt = $playerModel->getHandEntity();
            if ($key == 0) {
                $topHandEnt = $playerModel->getHandEntity();
                $topHandKey = $key;
                continue;
            }
            // best is 1, royal straight flush down to high card, 10
            // higher rank loses
            $currentHandRank = $handEnt->getPokerHandRank();
            $topHandRank = $topHandEnt->getPokerHandRank();
            if ($currentHandRank > $topHandRank) {
                $loserKeyArr[$key] = $key;
                $loserKeyArr = $tieArr + $loserKeyArr;
            } elseif ($currentHandRank < $topHandRank) {
                $loserKeyArr[$topHandKey] = $topHandKey;
                $loserKeyArr = $tieArr + $loserKeyArr;
                $tieArr = array();
                // set current hand to tops
                $topHandEnt = $playerModel->getHandEntity();
                $topHandKey = $key;
                
            } else {
                $tieArr[$key] = $handEnt;//$key;
                $tieArr[$topHandKey] = $topHandEnt;//$topHandKey;
                $tmpTieEnt = $handEnt;
            }
        }
/*
        $numLosers = count($loserKeyArr);
        $numPlayers = count($playerArr);
        // if there are more than two players left after finding losers, there is a tie
        if ($numPlayers - $numLosers> 1) {

            foreach($playerArr as $key => $playerModel) {
                if (in_array($key, $loserKeyArr)) {
                    continue;
                }
                $handEnt = $playerModel->getHandEntity();
                if ($key == 0) {
                    $topHandEnt = $playerModel->getHandEntity();
                    $topHandKey = $key;
                    continue;
                }
                $handEnt = $playerModel->getHandEntity();
                // best is 1, royal straight flush down to high card, 10
                // higher rank loses
                // TODO: do i need this conditional?
                if ($handEnt->getPokerHandRank() < $topHandEnt->getPokerHandRank()) {
                    $tieArr = array();
                    $topHandKey = $key;
                    continue;
                } elseif ($handEnt->getPokerHandRank() == $topHandEnt->getPokerHandRank()) {
                    $tieArr[$key] = $handEnt;
                    $tieArr[$topHandKey] = $topHandEnt;
                    $topHandKey = false;
                    // make it easy to check simple attributes the ties share
                    $tmpTieEnt = $handEnt;
                } 

            }

        } elseif (false) {

            // no tie
            // set winning key (player position)
            foreach($playerArr as $key => $playerModel) {
                if (!in_array($key, $loserKeyArr)) {
                    $topHandKey = $key;
                    break;
                }
            }
              
        }
*/
        // evaluate any ties
        if (count($tieArr) > 1) {

            // while there may be multiple hands with three of a kind or four of a kind, their can be no permanent ties amongst them
            // as there aren't enough cards. eg. two three of a kinds of Jacks requires six Jacks 
            if ($tmpTieEnt->getPokerHandRank() == 3) {
            //if ($tmpTieEnt->getIsFourOfAKind()) {

                $count = 0;
                foreach($tieArr as $key => $handEnt) {
                    if ($count == 0) {
                        $topValue = $handEnt->getFourOf();
                        $topHandKey = $key;
                        $count++;
                        continue;
                    }
                    if ($handEnt->getFourOf() > $topValue) {
                        $topHandKey = $key;
                        $topValue = $handEnt->getFourOf();
                    }
                }

            } elseif ($tmpTieEnt->getPokerHandRank() == 7) {
            //} elseif ($tmpTieEnt->getIsThreeOfAKind()) {
                
                $count = 0;
                foreach($tieArr as $key => $handEnt) {
                    if ($count == 0) {
                        $topValue = $handEnt->getThreeOf();
                        $topHandKey = $key;
                        $count++;
                        continue;
                    }
                    if ($handEnt->getThreeOf() > $topValue) {
                        $topHandKey = $key;
                        $topValue = $handEnt->getThreeOf();
                    }
                }
                
            //} elseif ($tmpTieEnt->getIsTwoOfAKind() || $tmpTieEnt->getIsTwoPair()) {
            } elseif ($tmpTieEnt->getPokerHandRank() == 9 || $tmpTieEnt->getPokerHandRank() == 8) {

                $count = 0;
                $stillTied = false;
                foreach($tieArr as $key => $handEnt) {
                    if ($count == 0) {
                        $topCardsValueArr[$key] = $handEnt->getTopCardsValueArr();
                        $topValue = $handEnt->getTwoOf();
                        $topHandKey = $key;
                        $count++;
                        continue;
                    }
                    if ($handEnt->getTwoOf() > $topValue) {
                        $topHandKey = $key;
                        $topValue = $handEnt->getTwoOf();
                    } elseif ($handEnt->getTwoOf() == $topValue) {
                        $stillTied = true;    
                        $topCardsValueArr[$key] = $handEnt->getTopCardsValueArr();
                    }
                }

                // if two pair of same value, pair of 2's versus pair of 2's
                if ($stillTied) {
                    
                    $topHandKey = false;
                    $playerPositionArr = array();
                    foreach($topCardsValueArr as $playerPositionOne => $cardOneArr) {

                        $playerPositionArr[] = $playerPositionOne;
                      
                        foreach($topCardsValueArr as $playerPositionTwo => $cardTwoArr) {
                            if ($playerPositionOne == $playerPositionTwo) {
                                continue;
                            }
                            foreach($cardOneArr as $indexOne => $cardOneValue) {
                                foreach($cardTwoArr as $indexTwo => $cardTwoValue) {
                                    if ($indexOne == $indexTwo) {
                                        if ($cardOneValue < $cardTwoValue) {
                                            $topHandKey = $playerPositionTwo;
                                            break 4;
                                        } else if ($cardOneValue > $cardTwoValue) {                            
                                            $topHandKey = $playerPositionOne;
                                            break 4;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                // unbreakable tie
                if ($topHandKey === false) {
                    $playerArr[$playerPositionArr[0]]->setTiedWith(array($playerPositionArr[1]));
                    $playerArr[$playerPositionArr[1]]->setTiedWith(array($playerPositionArr[0]));  
                }
                
            //} elseif ($tmpTieEnt->getIsHighCard() || $tmpTieEnt->getIsStraight() || $tmpTieEnt->getIsRoyalStraightFlush() || $tmpTieEnt->getIsFlush()) {
            } else if ($tmpTieEnt->getPokerHandRank() == 10 || $tmpTieEnt->getPokerHandRank() == 6 || $tmpTieEnt->getPokerHandRank() == 1 || $tmpTieEnt->getPokerHandRank() == 5) {
            // when these hands tie, high card determines winner

                // Put the values into simple arrays, but keep the key structure
                $topHandsArr = array();
                foreach($tieArr as $key => $handEnt) {
                    foreach($handEnt->getTopCardsArr() as $cardArr) {
                        $topHandsArr[$key][] = $cardArr[0];
                        $topHandsKeysArr[$key] = $key; 
                    }
                }

                $topCard = 0;
                $loserKeyArr = array();
                foreach($topHandsArr as $keyOne => $arrOne) {
                    // loop over each hand and compare to the other
                    foreach($topHandsArr as $keyTwo => $arrTwo) {
                        // don't compare a hand to itself
                        if ($keyOne == $keyTwo) {
                            continue;
                        }
                        // if already determined that a hand lost, skip it
                        if (in_array($keyOne, $loserKeyArr) || in_array($keyTwo, $loserKeyArr)) {
                            continue;
                        }
                        // compare each card in the same position as the card in the same position in the other hand
                        foreach($arrOne as $indexOne => $cardValueOne) {
                            foreach($arrTwo as $indexTwo => $cardValueTwo) {
                                if ($indexOne == $indexTwo) {
                                    if ($cardValueOne == $cardValueTwo) {
                                        continue;
                                    }elseif (in_array($keyOne, $loserKeyArr) || in_array($keyTwo, $loserKeyArr)) {
                                        continue;
                                    } elseif ($cardValueOne > $cardValueTwo && !isset($loserKeyArr[$keyTwo])) {
                                        // we have a winner
                                        // once designated as a loser, don't get inside this conditional again. eg. flush vs. flush, once high card is found, it is finished
                                        $loserKeyArr[$keyTwo] = $keyTwo;
                                    } elseif ($cardValueOne < $cardValueTwo && !isset($loserKeyArr[$keyOne])) {
                                        $loserKeyArr[$keyOne] = $keyOne;
                                    }
                                }
                            }
                        }
                    }
                }
                //remove losers
                foreach($loserKeyArr as $loserKey) {
                    unset($topHandsKeysArr[$loserKey]);
                }
                if (count($topHandsKeysArr) > 1) {
                    $topHandKey = false;
                    foreach($topHandsKeysArr as $key => $tieKey) {
                        // set who player is tied with
                        $tmp = $topHandsKeysArr;
                        unset($tmp[$tieKey]);
                        $playerArr[$tieKey]->setTiedWith($tmp);
                    }
                } else {
                    list($topHandKey, ) = each($topHandsKeysArr);
                }

            }
            
        } 

        if ($topHandKey !== false) {
            if (!isset($playerArr[$topHandKey])){
                var_dump($topHandKey);
                printR($this);
            }
            $playerArr[$topHandKey]->getHandEntity()->setIsWinningHand(true);
        }
            
        
        return $playerArr;
        
    }
    
         
        
       
}