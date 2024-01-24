<?php namespace app\formPosts;

//todo

require_once(__DIR__ . "/../Database.php");
require_once(__DIR__ . "/../Game.php");
require_once(__DIR__ . "/../Moves.php");
require_once(__DIR__ . "/../Player.php");

use app\Database;
use app\Game;
use app\Moves;

session_start();

/** @var Game $game **/
$game = $_SESSION['game'];

/** @var Database $db **/
$db = $_SESSION['db'];

$piece = $_POST['piece'];
$toPosition = $_POST['toPosition'];

Moves::playPiece($piece, $toPosition, $game, $db);
