<?php namespace app\formPosts;

//todo

require_once(__DIR__ . "/../Game.php");
require_once(__DIR__ . "/../Moves.php");

use app\Game;
use app\Moves;

session_start();

/** @var Game $game **/
$game = $_SESSION['game'];

Moves::undoLastMove($game);

header('Location: /../../index.php');
