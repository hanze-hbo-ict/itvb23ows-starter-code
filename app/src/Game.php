<?php
namespace app;

require_once(__DIR__ . "/database/Database.php");
require_once(__DIR__ . "/Board.php");
require_once(__DIR__ . "/Player.php");
use app\database\Database;

class Game
{
    private Board $board;
    private int $playersTurn;
    private Player $playerOne;
    private Player $playerTwo;

    public function __construct(Database $database)
    {
        $this->restart();
        $this->addToDatabase($database);
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

    public function getPlayersTurn(): int
    {
        return $this->playersTurn;
    }

    public function setPlayersTurn($playersTurn): void
    {
        $this->playersTurn = $playersTurn;
    }

    public function getPlayerOne(): Player
    {
        return $this->playerOne;
    }

    public function getPlayerTwo(): Player
    {
        return $this->playerTwo;
    }

    public function restart(): void {
        $this->board = new Board();
        $this->playerOne = new Player();
        $this->playerTwo = new Player();
        $this->playersTurn = 0;
    }

    public function addToDatabase(Database $database): void {
        //todo deze functie anders, of anders noemen (naar functie)
        $database->getDatabase()->prepare('INSERT INTO games VALUES ()')->execute();
        //todo game id anders doen?
        $_SESSION['game_id'] = $database->getDatabase()->insert_id;
    }


}