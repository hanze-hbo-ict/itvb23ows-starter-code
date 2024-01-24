<?php
namespace app;

require_once(__DIR__ . "/Database.php");
require_once(__DIR__ . "/Board.php");
require_once(__DIR__ . "/Player.php");

class Game
{
    private Board $board;
    private Player $currentPlayer;
    private Player $playerOne;
    private Player $playerTwo;
    private int $gameId;
    private int $lastMoveId;

    public function __construct(Database $database)
    {
        $this->restart($database);
        $_SESSION['game'] = $this;
        $_SESSION['db'] = $database;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function setBoard($board): void
    {
        $this->board = $board;
    }

    public function getCurrentPlayer(): Player
    {
        return $this->currentPlayer;
    }

    public function setCurrentPlayer($currentPlayer): void
    {
        $this->currentPlayer = $currentPlayer;
    }

    public function getPlayerOne(): Player
    {
        return $this->playerOne;
    }

    public function getPlayerTwo(): Player
    {
        return $this->playerTwo;
    }

    public function setPlayerOne(Player $playerOne): void
    {
        $this->playerOne = $playerOne;
    }

    public function setPlayerTwo(Player $playerTwo): void
    {
        $this->playerTwo = $playerTwo;
    }

    public function getGameId(): int
    {
        return $this->gameId;
    }

    public function setGameId(int $gameId): void
    {
        $this->gameId = $gameId;
    }

    public function getLastMoveId(): int
    {
        return $this->lastMoveId;
    }

    public function setLastMoveId(int $lastMoveId): void
    {
        $this->lastMoveId = $lastMoveId;
    }

    public function switchTurn(): void
    {
        if ($this->getCurrentPlayer() === $this->getPlayerOne() ) {
            $this->setCurrentPlayer($this->getPlayerTwo());
        } else {
            $this->setCurrentPlayer($this->getPlayerOne());
        }
    }

    public function restart(Database $database): void {
        $this->setBoard(new Board());
        $this->setPlayerOne(new Player(0));
        $this->setPlayerTwo(New Player(1));
        $this->setCurrentPlayer($this->getPlayerOne());
        $database->addGameToDatabase($this);
        $this->setLastMoveId($database->getLastMoveId());
    }

    public function undoLastMove() {
        //todo
    }


}