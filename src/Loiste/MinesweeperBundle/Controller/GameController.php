<?php

namespace Loiste\MinesweeperBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Loiste\MinesweeperBundle\Model\Game;

class GameController extends Controller
{
    public function startAction()
    {
        $percentageMines = $this->getRequest()->get('percentageMines'); // Retrieves the percentage of mines.
        if (!$percentageMines) {
            $percentageMines = 10;
        }
        // Setup an empty game. To keep things very simple for candidates, we just store info on the session.
        $game = new Game($percentageMines);

        $session = new Session();
        $session->start();
        $session->set('game', $game);

        return $this->render('LoisteMinesweeperBundle:Default:index.html.twig', array(
            'game' => $game
        ));
    }

    public function makeMoveAction()
    {
        $row = $this->getRequest()->get('row'); // Retrieves the row index.
        $column = $this->getRequest()->get('column'); // Retrieves the column index.

        $session = new Session();
        $session->start();
        $game = $session->get('game'); /** @var $game Game */
        $game->discover($row, $column);

        return $this->render('LoisteMinesweeperBundle:Default:index.html.twig', array(
            'game' => $game
        ));
    }
}
