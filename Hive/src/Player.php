<?php

namespace HiveGame;

class Player
{
    public int $color;
    public array $hand;

    /**
     * @param int $color
     */
    public function __construct(int $color)
    {
        $this->color = $color;
        $this->setHand(["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]);
    }

    /**
     * @return int
     */
    public function getColor(): int
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor(int $color): void
    {
        $this->color = $color;
    }

    /**
     * @return array
     */
    public function getHand(): array
    {
        return $this->hand;
    }

    /**
     * @param mixed $hand
     */
    public function setHand(array $hand): void
    {
        $this->hand = $hand;
    }
}