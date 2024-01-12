<?php

function getDatabaseConnection() {
    return include 'database.php';
}

function insertMove($gameId, $lastMove)
{
    $db = getDatabaseConnection();

    $stmt = $db->prepare('INSERT INTO moves (game_id, type, move_from, move_to, previous_id, state) VALUES (?, "pass", null, null, ?, ?)');
    $stmt->bind_param('iis', $gameId, $lastMove, get_state());
    $stmt->execute();
    return $db->insert_id;
}

function restart()
{
    $db = getDatabaseConnection();

    $db->prepare('INSERT INTO games VALUES ()')->execute();
    return $db->insert_id;
}

