<?php namespace app\database;

use mysqli;

class Database {
    private mysqli $database;

    function __construct() {
        $this->database = $this->initDatabase();
    }

    public function getDatabase(): mysqli
    {
        return $this->database;
    }

    function getState(): string
    {
        return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
    }

    function setState($state): void
    {
        list($a, $b, $c) = unserialize($state);
        $_SESSION['hand'] = $a;
        $_SESSION['board'] = $b;
        $_SESSION['player'] = $c;
    }

    private function initDatabase(): mysqli
    {
        $host = $_ENV["MYSQL_HOST"];
        $user = $_ENV["MYSQL_USERNAME"];
        $pw = $_ENV["MYSQL_PASSWORD"];
        $db = $_ENV["MYSQL_DATABASE"];
        return new mysqli($host, $user, $pw, $db);
    }

}

