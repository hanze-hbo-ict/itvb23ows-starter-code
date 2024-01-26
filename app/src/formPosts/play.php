<?php namespace app\formPosts;

require_once '../../vendor/autoload.php';

use app\Game;
use app\Moves;

session_start();

/** @var Game $game **/
$game = $_SESSION['game'];

$piece = $_POST['piece'];
$toPosition = $_POST['toPosition'];

Moves::playPiece($piece, $toPosition, $game);

header('Location: /../../index.php');
