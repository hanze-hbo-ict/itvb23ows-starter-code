<?php

namespace app;

require_once(__DIR__ . "/Database.php");
require_once(__DIR__ . "/Board.php");
require_once(__DIR__ . "/Player.php");

class Player
{
    private array $hand;
    private int $playerNumber;

    public function __construct($playerNumber)
    {
        $this->hand = ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3];
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
        $this->hand[$piece]--;
    }

}