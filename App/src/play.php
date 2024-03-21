<?php

session_start();

use functions\Util as Util;
use functions\Database as Database;

require_once './vendor/autoload.php';

$db = new Database();
$util = new Util();

$piece = $_POST['piece'];
$to = $_POST['to'];

$player = $_SESSION['player'];
$board = $_SESSION['board'];
$hand = $_SESSION['hand'][$player];

if (!$hand[$piece]) {
    $_SESSION['error'] = "Player does not have tile";
}
elseif (isset($board[$to])) {
    $_SESSION['error'] = 'Board position is not empty';
}
elseif (count($board) && !$util->hasNeighBour($to, $board)) {
    $_SESSION['error'] = "board position has no neighbour";
}
elseif (array_sum($hand) < 11 && !$util->neighboursAreSameColor($player, $to, $board)) {
    $_SESSION['error'] = "Board position has opposing neighbour";
}
elseif (array_sum($hand) <= 8 && $hand['Q']) {
    $_SESSION['error'] = 'Must play queen bee';
} else {
    $_SESSION['board'][$to] = [[$_SESSION['player'], $piece]];
    $_SESSION['hand'][$player][$piece]--;
    $_SESSION['player'] = 1 - $_SESSION['player'];
    $stmt = $db->database->prepare('insert into moves (game_id, type, move_from, move_to, previous_id, state)
                            values (?, "play", ?, ?, ?, ?)');
    $stmt->bind_param('issis', $_SESSION['game_id'], $piece, $to, $_SESSION['last_move'], $db->getState());
    $stmt->execute();
    $_SESSION['last_move'] = $db->database->insert_id;
}

header('Location: index.php');
