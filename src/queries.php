<?php

$db = include 'database/database.php';

function insertMove($gameId, $lastMove, $state)
{
    global $db;

    $stmt = $db->prepare('INSERT INTO moves (game_id, type, move_from, move_to, previous_id, state) VALUES (?, "pass", null, null, ?, ?)');
    $stmt->bind_param('iis', $gameId, $lastMove, $state);
    $stmt->execute();
    return $db->insert_id;
}

function restart()
{
    global $db;

    $db->prepare('INSERT INTO games VALUES ()')->execute();
    return $db->insert_id;
}

