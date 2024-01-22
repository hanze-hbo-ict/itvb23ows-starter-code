<?php namespace app;

require_once(__DIR__ . "/database/Database.php");
use app\database\Database;

session_start();

$db = new Database();
$stmt = $db->getDatabase()->prepare('insert into moves
    (game_id, type, move_from, move_to, previous_id, state)
    values (?, "pass", null, null, ?, ?)');
$state = $db->getState();
$stmt->bind_param('iis', $_SESSION['game_id'], $_SESSION['last_move'], $state);
$stmt->execute();
$_SESSION['last_move'] = $db->getDatabase()->insert_id;
$_SESSION['player'] = 1 - $_SESSION['player'];

header('Location: index.php');
