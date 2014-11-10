<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Trainer for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Game\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Game\Model\GameModel;
use Game\Model\PlayerModel;
use Game\Model\DeckModel;
use Game\Model\EvalPokerHandModel;
use Zend\View\Model\ViewModel;


class IndexController extends AbstractActionController
{

    public function flushProgressionAction()
    {
        
        $this->setHead(); 
        
    }
    
    public function probabilitiesAction()
    {
        $this->setHead();    
    }
    
    private function setHead()
    {

        $headLink = $this->serviceLocator->get('viewhelpermanager')->get('headLink');
        $headLink->appendStylesheet('/jquery-ui/jquery-ui.theme.min.css');
        $headLink->appendStylesheet('/jquery-ui/jquery-ui.structure.min.css');       
        $headLink->appendStylesheet('/css/game.css');
         
        $headScript = $this->serviceLocator->get('viewhelpermanager')->get('headScript');
        $headScript->prependFile("/jquery-ui/jquery-ui.min.js", $type = 'text/javascript', $attrs = array());
        $headScript->prependFile("/jquery-ui/external/jquery/jquery.js", $type = 'text/javascript', $attrs = array());
        $headScript->prependFile("/js/probabilities.js", $type = 'text/javascript', $attrs = array());

    }
    
    public function indexAction()
    {
  
        $headLink = $this->serviceLocator->get('viewhelpermanager')->get('headLink');
        $headLink->appendStylesheet('/css/game.css');
        
        $gameModel = new GameModel();
        $forceHandArr = array();
       /* 
        $forceHandArr[] = "fourofakind1";
        $forceHandArr[] = "fourofakind2";
        $forceHandArr[] = "royalstraightflush";
        $forceHandArr[] = "royalstraightflush";
        $forceHandArr[] = "straightflush2";
        $forceHandArr[] = "straightflush1";
        $forceHandArr[] = "flush1";
        $forceHandArr[] = "flush2";
        $forceHandArr[] = "threeofakind1";
        $forceHandArr[] = "threeofakind2";
        
        $forceHandArr[] = "twoofakind2";
        $forceHandArr[] = "twoofakind2";
        
        $forceHandArr[] = "straight2";
        $forceHandArr[] = "straight2";
        $forceHandArr[] = "twoofakind1";
        $forceHandArr[] = "twoofakind2";
        $forceHandArr[] = "twoofakind3";
        $forceHandArr[] = "twopair1";
        $forceHandArr[] = "highcard1";
        $forceHandArr[] = "twoofakind4";
        $forceHandArr[] = "highcard2";
        $forceHandArr[] = "twoofakind3";
        $forceHandArr[] = "highcard3";
        //$forceHandArr[] = "highcard1";
        //$forceHandArr[] = "twoofakind4";
        //$forceHandArr[] = "highcard2";
        //$forceHandArr[] = "twoofakind3";
        $forceHandArr[] = "highcard3";
        $forceHandArr[] = "fourofakind1";
        $forceHandArr[] = "straight1";
        $forceHandArr[] = "straight2";
        $forceHandArr[] = "straight3";
        
        $forceHandArr[] = "highcard3";
        $forceHandArr[] = "flush1";
        $forceHandArr[] = "flush2";
        $forceHandArr[] = "fullhouse";
        $forceHandArr[] = "twopair1";
        $forceHandArr[] = "twopair2";
        $forceHandArr[] = "twoofakind4";
        */
 
        
        $numCards = 7;
        $numPlayers = 7;
        if (count($forceHandArr) > 0){
            $numPlayers = count($forceHandArr);
        }
        $playerArr = $gameModel->buildPlayerHands($numCards, $numPlayers, $forceHandArr); 
        $playerArr = $gameModel->evalPlayers($playerArr);   
       

        $view = new ViewModel();
        $view->setVariables(
            array(
                'playerArr' => $playerArr,
                'deckModel' => new DeckModel()
            )
        );
        return $view;
    }

}
