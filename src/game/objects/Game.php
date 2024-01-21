<?php

namespace objects;

class Game {
    private Player $player;
    private Board $board;
    private int $id;

    public function __construct(Player $player, Board $board, int $id)
    {
        $this->player = $player;
        $this->board = $board;
        $this->id = $id;

    }
    public function getBoard(): Board
    {
        return $this->board;
    }

    public function setBoard($board): void
    {
        $this->board = $board;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer($player): void
    {
        $this->player = $player;
    }

}

?>