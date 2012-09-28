<?php

namespace Loiste\MinesweeperBundle\Model;

/**
 * This class represents a game model.
 */
class Game
{
    /**
     * A two dimensional array of game objects.
     *
     * E.x.: $gameArea[3][2] instance of GameObject
     *
     * @var array
     */
    public $gameArea;
    public $numRows;
    public $numColumns;
    public $percentageMines;

    public function __construct($percentageMines)
    {
        // Upon constructing a new game instance, setup an empty game area.
        $this->gameArea = array();
        $this->percentageMines = $percentageMines;
        $this->numRows = 8;
        $this->numColumns = 8;

        for ($row = 0; $row < $this->numRows; $row++) {

            $temp = array();
            for ($column = 0; $column < $this->numColumns; $column++) {
            	$mine = mt_rand(0, 99) < $this->percentageMines;
                $temp[] = new GameObject($mine);
            }

            $this->gameArea[] = $temp;
        }

        for ($row = 0; $row < $this->numRows; $row++) {
            for ($column = 0; $column < $this->numColumns; $column++) {
                $this->gameArea[$row][$column]->number = $this->getNumber($row, $column);
            }
        }
    }   
    
    private function isMine($row, $column)
    {
    	if ($row >= 0 && $column >= 0 && $row < $this->numRows && $column < $this->numColumns) {
    	    return $this->gameArea[$row][$column]->isMine();
    	} else {
    	    return false;
    	}
    }
    
    private function getNumber($row, $column)
    {
    	$count = 0;
    	
        for ($r = -1; $r <= 1; $r++) {
            for ($c = -1; $c <= 1; $c++) {
                if ($r != 0 || $c != 0) {
                    $count += $this->isMine($row + $r, $column + $c);
                }
            }
        }
        
        return $count;
    }

    public function discover($row, $column)
    {
    	if ($row >= 0 && $column >= 0 && $row < $this->numRows && $column < $this->numColumns &&
    	    !$this->gameArea[$row][$column]->isDiscovered()) {
    	    $this->gameArea[$row][$column]->discovered = true;
    	    if ($this->gameArea[$row][$column]->getNumber() == 0) {
                for ($r = -1; $r <= 1; $r++) {
                    for ($c = -1; $c <= 1; $c++) {
                        $this->discover($row + $r, $column + $c);
                    }
                }
            }     	    
    	}
    }
    
    public function isSolved() {
        for ($row = 0; $row < $this->numRows; $row++) {
            for ($column = 0; $column < $this->numColumns; $column++) {
            	if ($this->gameArea[$row][$column]->isMine() == 
            	    $this->gameArea[$row][$column]->isDiscovered()) {
            	    return false;
            	}
            }
        }
        return true;
    }
}
