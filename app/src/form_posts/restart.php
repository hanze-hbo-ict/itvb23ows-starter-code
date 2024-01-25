<?php namespace app\formPosts;

require_once(__DIR__ . "/../Game.php");

use app\Game;

session_start();

/** @var Game $game **/
$game = $_SESSION['game'];

$game->restart();

header('Location: /../../index.php');
