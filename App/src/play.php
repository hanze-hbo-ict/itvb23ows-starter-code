<?php

session_start();

use functions\Game as Game;

require_once './vendor/autoload.php';

$game = new Game();
$piece = $_POST['piece'];
$to = $_POST['to'];
$game->placeStone($piece, $to);

header('Location: index.php');
