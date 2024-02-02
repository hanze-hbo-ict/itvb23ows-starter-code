<?php

namespace HiveGame;

class GameState
{
    private int $gameId;
    private Player $player1;
    private Player $player2;
    private Player $currentPlayer;
    private int $lastMove;
    private array $board;


    public function __construct()
    {
        $this->player1 = new Player(0);
        $this->player2 = new Player(1);
        $this->board = [];
        $this->lastMove = 0;

        $this->currentPlayer = $this->player1;

    }

    /**
     * @return Player
     */
    public function getPlayer1(): Player
    {
        return $this->player1;
    }

    /**
     * @param Player $player1
     */
    public function setPlayer1(Player $player1): void
    {
        $this->player1 = $player1;
    }

    /**
     * @return Player
     */
    public function getPlayer2(): Player
    {
        return $this->player2;
    }

    /**
     * @param Player $player2
     */
    public function setPlayer2(Player $player2): void
    {
        $this->player2 = $player2;
    }

    /**
     * @return int
     */
    public function getLastMove(): int
    {
        return $this->lastMove;
    }

    /**
     * @param int $lastMove
     */
    public function setLastMove(int $lastMove): void
    {
        $this->lastMove = $lastMove;
    }

    /**
     * @return int
     */
    public function getGameId(): int
    {
        return $this->gameId;
    }

    /**
     * @param int $gameId
     */
    public function setGameId(int $gameId): void
    {
        $this->gameId = $gameId;
    }

    /**
     * @return array
     */
    public function getBoard(): array
    {
        return $this->board;
    }

    /**
     * @param array $board
     */
    public function setBoard(array $board): void
    {
        $this->board = $board;
    }

    /**
     * @return Player
     */
    public function getCurrentPlayer(): Player
    {
        return $this->currentPlayer;
    }

    /**
     * @param Player $currentPlayer
     */
    public function setCurrentPlayer(Player $currentPlayer): void
    {
        $this->currentPlayer = $currentPlayer;
    }
}