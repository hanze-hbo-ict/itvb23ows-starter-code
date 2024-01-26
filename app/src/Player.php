<?php

namespace app;

class Player
{
    private array $hand;
    private int $playerNumber;

    public function __construct($playerNumber, $startingHand)
    {
        $this->hand = $startingHand;
        $this->playerNumber = $playerNumber;
    }

    public function getHand(): array
    {
        return $this->hand;
    }

    public function setHand(array $hand): void
    {
        $this->hand = $hand;
    }

    public function getPlayerNumber(): int
    {
        return $this->playerNumber;
    }

    public function removePieceFromHand($piece): void
    {
        if ($this->getHand()[$piece] == 1) {
            unset($this->hand[$piece]);
        } else {
            $this->hand[$piece]--;
        }
    }

}
