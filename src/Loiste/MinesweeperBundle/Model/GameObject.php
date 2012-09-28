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

    public function __construct($mine)
    {
        $this->mine = $mine;
        $this->discovered = false;
    }

    public function isMine()
    {
        return $this->mine;
    }

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
}