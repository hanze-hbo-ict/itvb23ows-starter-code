<?php

namespace functions;
use mysqli;
class Database {
    public mysqli $database;

    public function __construct() {
        $this->database = new mysqli(
            "mysql_game_container",
            $_ENV['MYSQL_ROOT_USER'],
            $_ENV['MYSQL_ROOT_PASSWORD'],
            $_ENV['MYSQL_DATABASE']
        );
    }

    public function getState(): string
    {
        return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
    }

    public function setState($state): void
    {
        list($a, $b, $c) = unserialize($state);
        $_SESSION['hand'] = $a;
        $_SESSION['board'] = $b;
        $_SESSION['player'] = $c;
    }
}
