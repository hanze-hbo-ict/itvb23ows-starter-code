<?php

namespace objects;

class Player {
    private $hand;
    private $playerNumber;

    public function __construct($playerNumber ,$hand)
    {
        $this->hand = $hand;
        $this->playerNumber = $playerNumber;

    }
    public function getHand()
    {
        return $this->hand;
    }

    public function setHand($hand): void
    {
        $this->hand = $hand;
    }

    public function switchPlayer()
    {
        $_SESSION['player'] = 1 - $_SESSION['player'];
    }

    public function getPlayerNumber()
    {
        return $this->playerNumber;
    }

    public function setPlayerNumber($playerNumber): void
    {
        $this->playerNumber = $playerNumber;
    }

    public function getAvailableHandPieces(): array
    {
        $hand = $this->hand[$this->playerNumber];

        foreach ($hand as $tile => $ct) {
            if ($ct > 0) {
                $pieces[] = $tile;
            }
        }

        return $pieces;
    }

}

?>