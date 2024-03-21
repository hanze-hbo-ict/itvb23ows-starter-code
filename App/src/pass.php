<?php

session_start();

use functions\Database as Database;

require_once './vendor/autoload.php';

$db = new Database();

$stmt = $db->database->prepare('insert into moves (game_id, type, move_from, move_to, previous_id, state)
                        values (?, "pass", null, null, ?, ?)');
$stmt->bind_param('iis', $_SESSION['game_id'], $_SESSION['last_move'], $db->getState());
$stmt->execute();
$_SESSION['last_move'] = $db->database->insert_id;
$_SESSION['player'] = 1 - $_SESSION['player'];

header('Location: index.php');
