<?php namespace app;

use mysqli;

class Database {

    public static function addMoveToDatabase(Game $game, String $type, String $toPosition = '', $fromPosition = ''): void
    {
        $db = Database::initDatabase();
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        $stmt = $db->prepare('insert into moves
            (game_id, type, move_from, move_to, previous_id, state)
            values (?, ?, ?, ?, ?, ?)');
        $state = Database::getState($game);
        $gameId = $game->getGameId();
        $lastMoveId = $game->getLastMoveId();
        $stmt->bind_param('isssis', $gameId,$type, $fromPosition, $toPosition, $lastMoveId, $state);
        $stmt->execute();
    }

    public static function addGameToDatabase(Game $game): void {
        $db = Database::initDatabase();
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        $db->prepare('INSERT INTO games VALUES ()')->execute();
        $game->setGameId(Database::initDatabase()->insert_id);
    }

    public static function selectAllMovesFromGame(int $gameId) {
        $db = Database::initDatabase();
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        $stmt = $db->prepare('SELECT * FROM moves WHERE game_id = '.$gameId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function getLastMoveId() {
        $db = Database::initDatabase();
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }
        return $db->insert_id;
    }

    public static function getState(Game $game): string
    {
        $hand = $game->getCurrentPlayer()->getHand();
        $board = $game->getBoard()->getBoardTiles();
        $player = $game->getCurrentPlayer()->getPlayerNumber();

        return serialize([$hand, $board, $player]);
    }

    public static function setState($state, Game $game): void
    {
        list($a, $b, $c) = unserialize($state);
        $hand = $a;
        $board = $b;
        $player = $c;

        if ($player == 0) {
            $game->getPlayerOne()->setHand($hand);
        } else {
            $game->getPlayerTwo()->setHand($hand);
        }
        $game->getBoard()->setBoardTiles($board);
        $game->switchTurn();
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

