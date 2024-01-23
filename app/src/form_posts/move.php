<?php namespace app\formPosts;

require_once(__DIR__ . "/../database/Database.php");
require_once(__DIR__ . "/../Game.php");
use app\database\Database;
use app\Game;

session_start();

$game = $_SESSION['game'];
$board = $game->getBoard();
$db = $_SESSION['db'];

$game->getPlayerAtTurn()->movePiece($board, $db);