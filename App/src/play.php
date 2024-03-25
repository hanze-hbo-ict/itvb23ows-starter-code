<?php

session_start();

use functions\Game as Game;
use functions\Database as Database;

require_once './vendor/autoload.php';

$db = new Database();
$game = new Game($db);
$piece = $_POST['piece'];
$to = $_POST['to'];
$game->placeStone($piece, $to);

header('Location: index.php');
