<?php

require_once __DIR__ . '/vendor/autoload.php';

use HiveGame\Database;
use HiveGame\Game;

$db = new Database();

if (isset($_POST["game"])) {
    $game = new Game($db);
    $game->continueGame($_POST);
} else {
    $game = new Game($db);
    $game->startGame();
}


