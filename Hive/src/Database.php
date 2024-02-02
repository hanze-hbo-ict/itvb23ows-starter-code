<?php

namespace HiveGame;

use mysqli;
use mysqli_result;

class Database
{
    private mysqli $db;

    public function __construct()
    {
        $env = include_once 'env.php';

        $mysqli = new mysqli($env["hostname"], $env["username"], $env["password"], $env["database"]);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        $mysqli->set_charset('utf8mb4');

        $this->db = $mysqli;
    }

    public function storeMove(int $gameId, string $type, string $from, string $to, int $previous, string $state) {
        $stmt = $this->getDb()->prepare('insert into moves (game_id, type, move_from, move_to, previous_id, state) values (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('isssis', $gameId, $type, $from, $to, $previous, $state);
        $stmt->execute();

        return $this->getDb()->insert_id;
    }

    public function getMoves(int $id): bool|mysqli_result
    {
        $stmt = $this->getDb()->prepare('SELECT * FROM moves WHERE id = ? ');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getMovesByGame(int $id): bool|mysqli_result
    {
        $stmt = $this->getDb()->prepare('SELECT * FROM moves WHERE game_id = ? ');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result();
    }


    public function createGame() {
        $this->getDb()->prepare('INSERT INTO games VALUES ()')->execute();
        return  $this->getDb()->insert_id;
    }

    public function getGame(int $id): GameState
    {
        $move = $this->getMovesByGame($id)->fetch_array();

        $gameState = new GameState();
        $gameState->setGameId($id);

        $gameAction = new GameActions($this, $gameState);
        $gameAction->setState($move["state"] ?? '');

        return $gameAction->getGame();
    }

    /**
     * @return mysqli
     */
    public function getDb(): mysqli
    {
        return $this->db;
    }

    /**
     * @param mysqli $db
     */
    private function setDb(mysqli $db): void
    {
        $this->db = $db;
    }
}