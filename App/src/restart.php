<?php

session_start();

use functions\Game as Game;

require_once './vendor/autoload.php';

$game = new Game();
$game->restart();

header('Location: index.php');
