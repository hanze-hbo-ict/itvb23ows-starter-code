<?php namespace app\formPosts;

require_once '../../vendor/autoload.php';

use app\Game;
use app\Moves;

session_start();

/** @var Game $game **/
$game = $_SESSION['game'];

Moves::undoLastMove($game);

header('Location: /../../index.php');
