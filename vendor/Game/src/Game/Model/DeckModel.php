<?php
namespace Game\Model;

use Game\Model\CardEntity;

class DeckModel
{
    
    protected $suitsArr;
    protected $facecardArr;
    protected $cardValueArr;
    protected $deckArr;
    protected $cardArr;
    
    public function __construct() 
    {

        $this->setSuitsArr(array("Hearts", "Spades", "Clubs", "Diamonds"));
        //$this->setFacecardArr(array(2, 3, 4, 5, 6, 7, 8, 9, 10, "Jack", "Queen", "King", "Ace"));
        // default value for ace is 14 as it works with checking for straight royal flush first best
        //$this->setCardValueArr(array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14));
        $this->setCardArr(
            array(2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => "Jack", 12 => "Queen", 13 => "King", 14 => "Ace")
        );
        $this->createDeck();
        
    }

    public function createDeck()
    {

        $deckArr = array();
        
        foreach($this->getSuitsArr() as $suit) {
            foreach($this->getCardArr() as $cardValue => $cardFace) {
                $cardEnt = new CardEntity;
                $cardEnt->setFaceValue($cardFace)
                    ->setSuit($suit)
                    ->setValue($cardValue)
                    ->setFilename($suit, $cardFace);
                $deckArr[] = $cardEnt; 
            }
            
        }

        $deckArr = $this->shuffleDeck($deckArr);
        $this->setDeckArr($deckArr);
 
    }
    
    public function buildCardEnt($cardArr)
    {

        $cardEntityArr = array();
        foreach($cardArr as $key => $arr) {
            
            $cardEnt = new CardEntity();
            $cardEnt->setFaceValue($arr['faceValue'])
                ->setValue($arr['value'])
                ->setFileName($arr['suit'], $arr['faceValue'])
                ->setSuit($arr['suit']);
            $cardEntityArr[] = $cardEnt;
            
        }
        
        return $cardEntityArr;
        
    }
    
    public function setCardArr($cardArr) 
    {
        $this->cardArr = $cardArr;
    }
    
    public function getCardArr()
    {
        return $this->cardArr;
    }

    public function getCardName($cardValue)
    {

        $cardArr = $this->getCardArr();
        if ($cardValue == 1) return "Ace";
        return $cardArr[$cardValue];
        
    }
    
    public function dealCards($numCards) {
        
        $deckArr = $this->getDeckArr();
        $arr = array_splice($deckArr, 0, $numCards);
        $this->setDeckArr($deckArr);
        
        return $arr; 
        
    }
    
    function shuffleDeck($arr) {

        shuffle($arr);
        return $arr;
        
    }
    
    public function getDeckArr()
    {
        return $this->deckArr;
    }
    
    public function setDeckArr($deckArr) 
    {
        
        $this->deckArr = $deckArr;
        return $this;    
    }
    

    public function getSuitsArr()
    {
        return $this->suitsArr;
    }

    public function setSuitsArr($suitsArr)
    {
        $this->suitsArr = $suitsArr;
        return $this;
    }

    public function getFacecardArr()
    {
        return $this->facecardArr;
    }

    public function setFacecardArr($facecardArr)
    {
        $this->facecardArr = $facecardArr;
        return $this;
    }

    public function getDefaultValuecardArr()
    {
        return $this->cardValueArr;
    }

    public function setCardValueArr($arr)
    {
        $this->cardValueArr = $arr;
        return $this;
    }
    
    public function dealHighCard1($numCards, $handNum = 1) 
    {

        $arr = array(
            $this->getNumberCard('Diamonds', 8), 
            $this->getNumberCard('Spades', 2),
            $this->getKing("Clubs"),
            $this->getJack("Hearts"),
            //$this->getNumberCard('Hearts', 4),
            $this->getNumberCard('Clubs', 3), 
            //$this->getJack('Spades')
        );
 
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 5);
            //$arr[] = $this->getNumberCard('Clubs', 9);
            $arr[] = $this->getQueen('Diamonds');
        }

        return $this->buildCardEnt($arr);
 
    }

    public function dealHighCard2($numCards, $handNum = 1) 
    {

        $arr = array(
            $this->getNumberCard('Spades', 2),
            $this->getNumberCard('Hearts', 4),
            $this->getNumberCard('Clubs', 6), 
            $this->getNumberCard('Diamonds', 8), 
            $this->getKing('Diamonds')
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Diamonds', 7);
            $arr[] = $this->getNumberCard('Clubs', 9);
        }

        return $this->buildCardEnt($arr);
 
    }
    
    public function dealHighCard3($numCards, $handNum = 1) 
    {

        $arr = array(
            $this->getNumberCard('Spades', 10),
            $this->getKing('Diamonds'),
            $this->getNumberCard('Diamonds', 9), 
            $this->getNumberCard('Diamonds', 4),
            $this->getNumberCard('Hearts', 6), 
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Diamonds', 7);
            //$arr[] = $this->getNumberCard('Clubs', 9);
            $arr[] = $this->getJack("Clubs");
        }

        return $this->buildCardEnt($arr);
 
    }
    
    
    public function dealTwoOfAKind1($numCards) 
    {

        $arr = array(
            $this->getNumberCard('Spades', 3),
            $this->getNumberCard('Hearts', 3),
            $this->getAce("Clubs"),
            $this->getKing("Diamonds"),
            //$this->getNumberCard('Clubs', 7), 
            //$this->getNumberCard('Diamonds', 4), 
            $this->getNumberCard('Clubs', 6),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 4);    
            $arr[] = $this->getNumberCard('Diamonds', 9);
        }

        return $this->buildCardEnt($arr);
 
    }
    
    public function dealTwoOfAKind2($numCards) 
    {

        $arr = array(
            $this->getNumberCard('Spades', 8),
            $this->getNumberCard('Hearts', 8),
            $this->getNumberCard('Clubs', 9), 
            $this->getNumberCard('Diamonds', 7), 
            $this->getNumberCard('Clubs', 5),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 6);     
            //$arr[] = $this->getNumberCard('Diamonds', 7);
            $arr[] = $this->getJack('Diamonds');
        }

        return $this->buildCardEnt($arr);
 
    }
    
    public function dealTwoOfAKind3($numCards) 
    {

        $arr = array(
            $this->getNumberCard('Diamonds', 10),
            $this->getNumberCard('Clubs', 10),
            $this->getAce("Diamonds"),
            $this->getQueen("Spades"),
            $this->getKing("Spades"),
            //$this->getNumberCard('Clubs', 9), 
            //$this->getNumberCard('Diamonds', 7), 
            //$this->getNumberCard('Clubs', 5),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 6);     
            $arr[] = $this->getNumberCard('Diamonds', 2);
            //$arr[] = $this->getJack('Diamonds');
        }

        return $this->buildCardEnt($arr);
 
    }
    
    public function dealTwoOfAKind4($numCards) 
    {

        $arr = array(
            $this->getAce("Hearts"),
            $this->getNumberCard('Clubs', 4),
            $this->getNumberCard('Diamonds', 5),
            $this->getNumberCard('Hearts', 10), 
            $this->getNumberCard('Diamonds', 2),
            //$this->getQueen("Spades"),
            //$this->getKing("Spades"),
            //$this->getNumberCard('Clubs', 9), 
            //$this->getNumberCard('Diamonds', 7), 
            //$this->getNumberCard('Clubs', 5),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getJack('Spades');
            $arr[] = $this->getAce('Spades');
            //$arr[] = $this->getNumberCard('Spades', 6);     
            //$arr[] = $this->getNumberCard('Diamonds', 2);
        }

        return $this->buildCardEnt($arr);
 
    }
    
    public function dealFullHouse($numCards) 
    {

        $arr = array(
            $this->getNumberCard('Spades', 5),
            $this->getKing("Hearts"),
            $this->getNumberCard('Spades', 6),
            $this->getNumberCard('Hearts', 6), 
            $this->getNumberCard('Clubs', 5), 
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 6);
            $arr[] = $this->getNumberCard('Spades', 5);
        }

        return $this->buildCardEnt($arr);
 
    }
    
    public function dealTwoPair1($numCards) 
    {

        $arr = array(
            $this->getNumbercard('Diamonds', 7),
            $this->getTen('Clubs'), 
            $this->getNumbercard('Clubs', 5),
            $this->getNumbercard('Diamonds', 6),
            $this->getNumbercard('Spades', 7),
            //$this->getAce("Diamonds"),
            //$this->getNumbercard('Hearts', 8),
            //$this->getQueen('Clubs'),
            //$this->getQueen('Hearts'), 
            //$this->getNumbercard('Clubs', 8), 
            //$this->getAce("Hearts"),
            //$this->getNine('Spades'),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getJack('Spades');
            $arr[] = $this->getNumberCard('Hearts', 6);
            //$arr[] = $this->getKing('Clubs');
            //$arr[] = $this->getTen('Hearts');
        }

        return $this->buildCardEnt($arr);
 
    }
    
    
    public function dealTwoPair3($numCards) 
    {

        $arr = array(
            $this->getNumberCard('Diamonds', 5),
            $this->getNumberCard('Hearts', 5), 
            $this->getNumberCard('Clubs', 2),
            $this->getNumberCard('Spades', 2),
            $this->getJack('Spades')
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Diamonds', 4);
            $arr[] = $this->getJack('Diamonds');
        }

        return $this->buildCardEnt($arr);
 
    }
    
    public function dealTwoPair2($numCards) 
    {

        $arr = array(
            $this->getKing("Spades"),
            $this->getAce("Hearts"),
            $this->getNumberCard('Clubs', 9),
            $this->getNumberCard('Diamonds', 9),
            //$this->getNumberCard('Hearts', 7), 
            $this->getNumberCard('Diamonds', 5),
            //$this->getJack('Diamonds'), 
            //$this->getJack('Spades'),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getAce("Diamonds");
            $arr[] = $this->getNumberCard("Hearts", 4);
            //$arr[] = $this->getNumberCard('Spades', 6);     
            //$arr[] = $this->getNumberCard('Diamonds', 4);
        }

        return $this->buildCardEnt($arr);
 
    }
    
    public function dealThreeOfAKind1($numCards) 
    {

        $arr = array(
            $this->getNumberCard('Spades', 8),
            $this->getNumberCard('Hearts', 8),
            $this->getNumberCard('Diamonds', 2), 
            $this->getNumberCard('Clubs', 8), 
            $this->getNine('Spades'),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 5);     
            $arr[] = $this->getNumberCard('Diamonds', 7);
        }

        return $this->buildCardEnt($arr);
 
    }
    
    public function dealThreeOfAKind2($numCards) 
    {

        $arr = array(
            $this->getNumberCard('Spades', 6),
            $this->getNumberCard('Hearts', 6),
            $this->getNumberCard('Diamonds', 3), 
            $this->getNumberCard('Clubs', 6), 
            $this->getNine('Spades'),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Clubs', 5);     
            $arr[] = $this->getNumberCard('Hearts', 7);
        }

        return $this->buildCardEnt($arr);
 
    }
    
    public function dealFourOfAKind1($numCards) 
    {

        $arr = array(
            $this->getNumberCard('Spades', 8),
            $this->getNumberCard('Hearts', 8),
            $this->getNumberCard('Diamonds', 8), 
            $this->getNumberCard('Clubs', 8), 
            $this->getNine('Spades'),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 5);     
            $arr[] = $this->getNumberCard('Diamonds', 7);
        }

        return $this->buildCardEnt($arr);
 
    }
    
    public function dealFourOfAKind2($numCards) 
    {

        $arr = array(
            $this->getNumberCard('Spades', 7),
            $this->getNumberCard('Hearts', 7),
            $this->getNumberCard('Diamonds', 7), 
            $this->getNumberCard('Clubs', 7), 
            $this->getNine('Spades'),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 9);     
            $arr[] = $this->getNumberCard('Diamonds', 6);
        }

        return $this->buildCardEnt($arr);
 
    }
    public function getAce($suit)
    {
        
        return array(
            "faceValue" => "Ace",
            "value" => 14,
            "activeValue" => 14,
            "cardName" => "Ace of " . $suit,
            "suit" => $suit 
        );
       
    }
    
    public function getKing($suit)
    {
        
        return array(
            "faceValue" => "King",
            "value" => 13,
            "activeValue" => 13,
            "cardName" => "King of " . $suit,
            "suit" => $suit 
        );
        
    } 
    
    public function getQueen($suit)
    {

        return array(
            "faceValue" => "Queen",
            "value" => 12,
            "activeValue" => 12,
            "cardName" => "Queen of " . $suit,
            "suit" => $suit 
        );

    } 
    
    public function getJack($suit)
    {

        return array(
            "faceValue" => "Jack",
            "value" => 11,
            "activeValue" => 11,
            "cardName" => "Jack of " . $suit,
            "suit" => $suit 
        );

    } 
    
    public function getTen($suit)
    {

        return array(
            "faceValue" => "10",
            "value" => 10,
            "activeValue" => 10,
            "cardName" => "Ten of " . $suit,
            "suit" => $suit 
        );

    } 
    
    public function getNine($suit)
    {

        return array(
            "faceValue" => 9,
            "value" => 9,
            "activeValue" => 9,
            "cardName" => "Nine of " . $suit,
            "suit" => $suit 
        );

    } 
    
    public function getNumberCard($suit, $num)
    {
        
        return array(
            "faceValue" => $num,
            "value" => $num,
            "activeValue" => $num,
            "cardName" => "$num of " . $suit,
            "suit" => $suit 
        );
        
    }

    public function dealRoyalStraightFlush($numCards) 
    {

        $arr = array(); 
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 5);     
            $arr[] = $this->getNumberCard('Diamonds', 7);
        }
        $arr = array_merge($arr, array(
            $this->getAce('Spades'),
            $this->getKing('Spades'),
            $this->getQueen('Spades'), 
            $this->getJack('Spades'), 
            $this->getTen('Spades'), 
        ));

        return $this->buildCardEnt($arr);
        
    }
    
    public function dealStraightFlush1($numCards) 
    {

        $arr = array(); 
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 5);     
            $arr[] = $this->getNumberCard('Diamonds', 7);
        }
        $arr = array_merge($arr, array(
            $this->getKing('Spades'),
            $this->getQueen('Spades'), 
            $this->getJack('Spades'), 
            $this->getTen('Spades'), 
            $this->getNine('Spades'),
        ));
/*        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 5);     
            $arr[] = $this->getNumberCard('Diamonds', 7);
        }
*/
        return $this->buildCardEnt($arr);
        
    }
    
    public function dealStraightFlush2($numCards) 
    {

        $arr = array(); 
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 5);     
            $arr[] = $this->getNumberCard('Diamonds', 7);
        }
        $arr = array_merge($arr, array(
            $this->getQueen('Hearts'), 
            $this->getJack('Hearts'), 
            $this->getTen('Hearts'), 
            $this->getNine('Hearts'),
            $this->getNumberCard('Hearts', 8),
        ));
/*        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 5);     
            $arr[] = $this->getNumberCard('Diamonds', 7);
        }
*/
        return $this->buildCardEnt($arr);
        
    }
    
    public function dealFlush1($numCards) 
    {

        $arr = array(
            $this->getNumberCard('Diamonds', 4),
            $this->getNumberCard('Diamonds', 8),
            $this->getNumberCard('Diamonds', 2),
            $this->getNumberCard('Diamonds', 7),
            $this->getNumberCard('Diamonds', 6),
            //$this->getJack('Spades'), 
            //$this->getTen('Spades'), 
            //$this->getNine('Spades'),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Clubs', 4);     
            $arr[] = $this->getNumberCard('Clubs', 10);
        }

        return $this->buildCardEnt($arr);
 
    }
 
    public function dealFlush2($numCards) 
    {

        $arr = array(
            $this->getNumberCard('Spades', 4),
            $this->getNumberCard('Spades', 5),
            $this->getNumberCard('Hearts', 6),  
            $this->getJack('Spades'), 
            $this->getQueen('Spades'), 
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Hearts', 8);  
            $arr[] = $this->getNumberCard('Spades', 3);
        }

        return $this->buildCardEnt($arr);
 
    }
 
    public function dealStraight1($numCards) 
    {

        $arr = array(
            $this->getNumberCard('Spades', 2),
            $this->getNumberCard('Hearts', 3),
            $this->getNumberCard('Spades', 4),
            $this->getNumberCard('Diamonds', 5),
            $this->getAce('Clubs'),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getTen('Diamonds');
            $arr[] = $this->getJack('Spades');
        }

        return $this->buildCardEnt($arr);
 
    }
    
    public function dealStraight2($numCards) 
    {

        $arr = array(
            $this->getNumberCard('Clubs', 8),
            $this->getJack('Spades'), 
            $this->getQueen('Diamonds'), 
            $this->getTen('Spades'), 
            $this->getNine('Spades'),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 5);
            $arr[] = $this->getNumberCard('Diamonds', 7);
        }

        return $this->buildCardEnt($arr);
 
    }
    
    public function dealStraight3($numCards) 
    {

        $arr = array(
            $this->getKing('Clubs'),
            $this->getJack('Spades'), 
            $this->getQueen('Diamonds'), 
            $this->getTen('Spades'), 
            $this->getAce('Spades'),
        );
        
        if ($numCards == 7) {
            $arr[] = $this->getNumberCard('Spades', 5);
            $arr[] = $this->getNumberCard('Diamonds', 7);
        }

        return $this->buildCardEnt($arr);
 
    }
    
    /*
     * Array
    (
    [0] => Game\Model\CardEntity Object
        (
            [faceValue:protected] => 7
            [value:protected] => 7
            [alternateValue:protected] => 
            [activeValue:protected] => 7
            [fileName:protected] => 7C.jpg
            [cardName:protected] => 7 of Clubs
            [suit:protected] => Clubs
        )
    */
    

    
}