<?php

namespace Loiste\MinesweeperBundle\Model;

/**
 * This class represents a game object.
 */
class GameObject
{
    public $mine;
    public $discovered;
    public $number;

    /**
     * Constructs a new game object with the given mine parameter
     */
    public function __construct($mine)
    {
        $this->mine = $mine;
        $this->discovered = false;
    }

    /**
     * Return true if the cell has a mine.
     */
    public function isMine()
    {
        return $this->mine;
    }

    /**
     * Return true if the cell has been discovered.
     */
    public function isDiscovered()
    {
        return $this->discovered;
    }

    /**
     * Returns the number of mines around this cell.
     */
    public function getNumber()
    {
        return $this->number;
    }
    
    /**
     * Return true if the cell has been solved. The cell is solved
     * if a mine-free cell is discovered or a mined cell is not discovered. 
     */
    public function isSolved()
    {
        return $this->discovered != $this->mine;
    }
    
    /**
     * Return true if a mine has been discovered.
     */
    public function hasExploded()
    {
        return $this->discovered && $this->mine;
    }
}