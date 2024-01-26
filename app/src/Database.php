<?php namespace app;

use mysqli;

class Database {

    public static function addMoveToDatabase(
        Game $game, String $type, String $toPosition = '', $fromPosition = ''
    ): void
    {
        $db = Database::initDatabase();
        if ($db->connect_error) {
            die($db->connect_error);
        }
        $stmt = $db->prepare('insert into moves
            (game_id, type, move_from, move_to, previous_id, state)
            values (?, ?, ?, ?, ?, ?)');
        $state = $game->getState();
        $gameId = $game->getGameId();
        $lastMoveId = $game->getLastMoveId();
        $stmt->bind_param('isssis', $gameId,$type, $fromPosition, $toPosition, $lastMoveId, $state);
        $stmt->execute();
    }

    public static function addGameToDatabase(Game $game): void {
        $db = Database::initDatabase();
        if ($db->connect_error) {
            die($db->connect_error);
        }
        $db->prepare('INSERT INTO games VALUES ()')->execute();
        $game->setGameId(Database::initDatabase()->insert_id);
    }

    public static function selectAllMovesFromGame(int $gameId) {
        //todo op een of andere manier selecteert hij alle moves, ook van oude spellen?
        // misschien dat er wat misgaat met de gameId

        $db = Database::initDatabase();
        if ($db->connect_error) {
            die($db->connect_error);
        }
        $stmt = $db->prepare('SELECT * FROM moves WHERE game_id = '.$gameId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function selectLastMoveFromGame(Game $game) {
        $db = Database::initDatabase();
        if ($db->connect_error) {
            die($db->connect_error);
        }
        $lastMoveId = $game->getLastMoveId();
        $stmt = $db->prepare('SELECT * FROM moves WHERE id = '.$lastMoveId);
        $stmt->execute();
        return $stmt->get_result()->fetch_array();
    }

    public static function getLastMoveId() {
        $db = Database::initDatabase();
        if ($db->connect_error) {
            die($db->connect_error);
        }
        return $db->insert_id;
    }

    private static function initDatabase(): mysqli
    {
        $host = $_ENV["MYSQL_HOST"];
        $user = $_ENV["MYSQL_USERNAME"];
        $pw = $_ENV["MYSQL_PASSWORD"];
        $db = $_ENV["MYSQL_DATABASE"];
        return new mysqli($host, $user, $pw, $db);
    }

}

