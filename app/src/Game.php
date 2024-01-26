<?php
namespace app;

class Game
{
    private Board $board;
    private Player $currentPlayer;
    private Player $playerOne;
    private Player $playerTwo;
    private int $gameId;
    private int $lastMoveId;

    public function __construct()
    {
        $this->restart();
        $_SESSION['game'] = $this;
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

    public function restart(): void {
        $startingHand = ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3];
        $this->setBoard(new Board());
        $this->setPlayerOne(new Player(0, $startingHand));
        $this->setPlayerTwo(new Player(1, $startingHand));
        $this->setCurrentPlayer($this->getPlayerOne());
        Database::addGameToDatabase($this);
        $this->setLastMoveId(Database::getLastMoveId());
    }

    public function getState(): string
    {
        $hand = $this->getCurrentPlayer()->getHand();
        $board = $this->getBoard()->getBoardTiles();
        $player = $this->getCurrentPlayer()->getPlayerNumber();

        return serialize([$hand, $board, $player]);
    }

    public function setState($state): void
    {
        list($a, $b, $c) = unserialize($state);
        $hand = $a;
        $board = $b;
        $player = $c;

        if ($player == 0) {
            $this->getPlayerOne()->setHand($hand);
        } else {
            $this->getPlayerTwo()->setHand($hand);
        }
        $this->getBoard()->setBoardTiles($board);
        $this->switchTurn();
    }

}
