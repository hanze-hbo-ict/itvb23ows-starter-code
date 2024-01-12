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

function undo($lastMove)
{
    $db = getDatabaseConnection();
    $stmt = $db->prepare('SELECT * FROM moves WHERE id = '.$lastMove);
    $stmt->execute();
    return $stmt->get_result()->fetch_array();;
}

function move($gameId, $from, $to, $lastMove)
{
    $db = getDatabaseConnection();
    $stmt = $db->prepare('insert into moves (game_id, type, move_from, move_to, previous_id, state) values (?, "move", ?, ?, ?, ?)');
    $stmt->bind_param('issis', $gameId, $from, $to, $lastMove, get_state());
    $stmt->execute();
    return $db->insert_id;
}

function play($gameId, $piece, $to, $lastMove)
{
    $db = getDatabaseConnection();
    $stmt = $db->prepare('insert into moves (game_id, type, move_from, move_to, previous_id, state) values (?, "play", ?, ?, ?, ?)');
    $stmt->bind_param('issis', $gameId, $piece, $to, $lastMove, get_state());
    $stmt->execute();
    return $db->insert_id;
}
