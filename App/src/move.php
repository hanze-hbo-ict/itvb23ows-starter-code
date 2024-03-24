<?php

session_start();

use functions\Game as Game;

require_once './vendor/autoload.php';

$game = new Game();
$from = $_POST['from'];
$to = $_POST['to'];
$game->moveStone($from, $to);

header('Location: index.php');
