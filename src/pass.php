<?php

session_start();

//$db = include 'database/database.php';
//$stmt = $db->prepare('insert into moves (game_id, type, move_from, move_to, previous_id, state) values (?, "pass", null, null, ?, ?)');
//$stmt->bind_param('iis', $_SESSION['game_id'], $_SESSION['last_move'], get_state());
//$stmt->execute();
//$_SESSION['last_move'] = $db->insert_id;
include 'src/queries.php';
$lastMove = insertMove($_SESSION['game_id'], $_SESSION['last_move'], get_state());
$_SESSION['last_move'] = $lastMove;
$_SESSION['player'] = 1 - $_SESSION['player'];

header('Location: index.php');

?>