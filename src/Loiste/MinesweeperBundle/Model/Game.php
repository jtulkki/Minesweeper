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
    /**
     * Number of rows in the game area.
     */
    public $numRows;
    /**
     * Number of columns in the game area.
     */
    public $numColumns;
    /**
     * Percentage on mines in the field.
     */
    public $percentageMines;

    public function __construct($percentageMines, $numRows = 8, $numColumns = 8)
    {
        // Upon constructing a new game instance, setup an empty game area.
        $this->gameArea = array();
        $this->percentageMines = $percentageMines;
        $this->numRows = $numRows;
        $this->numColumns = $numColumns;

        // Initialized the game area and the mines.
        for ($row = 0; $row < $this->numRows; $row++) {

            $temp = array();
            for ($column = 0; $column < $this->numColumns; $column++) {
            	$mine = mt_rand(0, 99) < $this->percentageMines;
                $temp[] = new GameObject($mine);
            }

            $this->gameArea[] = $temp;
        }

        // Calculate the number of mines around each position.
        for ($row = 0; $row < $this->numRows; $row++) {
            for ($column = 0; $column < $this->numColumns; $column++) {
                $this->gameArea[$row][$column]->number = $this->getNumber($row, $column);
            }
        }
    }   
    
    /** 
     * Check if the given poisition has a mine. If the position is outside
     * the playing field, return false.
     */
    private function isMine($row, $column)
    {
    	if ($row >= 0 && $column >= 0 && $row < $this->numRows && $column < $this->numColumns) {
    	    return $this->gameArea[$row][$column]->isMine();
    	} else {
    	    return false;
    	}
    }

    /**
     * Returns the number of mines around the given position.
     */   
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

    /**
     * Discovers a new position. If the position has no neighboring mines, 
     * neighboring positions are also discovered recursively.
     * If the position is already discovered or is outside the game area then 
     * do nothing.
     */
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
    
    /**
     * Checks if the game is solved. The game is solved when all positions
     * have been solved.
     */
    public function isSolved() {
        for ($row = 0; $row < $this->numRows; $row++) {
            for ($column = 0; $column < $this->numColumns; $column++) {
            	if (!$this->gameArea[$row][$column]->isSolved()) {
            	    return false;
            	}
            }
        }
        return true;
    }

    /**
     * Checks if the game is over. The game is over when it has either been 
     * solved or a mine has exploded.
     */
    public function isGameOver() {
        // Check if the game is solved.
        if ($this->isSolved()) {
            return true;
        }
        // Check if a mine has exploded.
        for ($row = 0; $row < $this->numRows; $row++) {
            for ($column = 0; $column < $this->numColumns; $column++) {
            	if ($this->gameArea[$row][$column]->hasExploded()) {
            	    return true;
            	}
            }
        }
        return false;
    }
}
