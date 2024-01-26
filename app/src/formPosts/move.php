<?php namespace app\formPosts;

require_once '../../vendor/autoload.php';

use app\Game;
use app\Moves;

session_start();

/** @var Game $game **/
$game = $_SESSION['game'];

$fromPosition = $_POST['fromPosition'];
$toPosition = $_POST['toPosition'];

Moves::movePiece($fromPosition, $toPosition, $game);

header('Location: /../../index.php');
