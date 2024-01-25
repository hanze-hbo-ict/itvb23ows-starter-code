<?php namespace app\formPosts;

require_once(__DIR__ . "/../Game.php");
require_once(__DIR__ . "/../Moves.php");

use app\Game;
use app\Moves;

session_start();

/** @var Game $game **/
$game = $_SESSION['game'];

$piece = $_POST['piece'];
$toPosition = $_POST['toPosition'];

Moves::playPiece($piece, $toPosition, $game);

header('Location: /../../index.php');
