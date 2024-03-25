<?php

session_start();

use functions\Game as Game;
use functions\Database as Database;

require_once './vendor/autoload.php';

$db = new Database();
$game = new Game($db);
$from = $_POST['from'];
$to = $_POST['to'];
$game->moveStone($from, $to);

header('Location: index.php');
