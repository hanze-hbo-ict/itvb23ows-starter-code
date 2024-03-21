<?php

session_start();

use functions\Database as Database;

require_once './vendor/autoload.php';

$db = new Database();

$_SESSION['board'] = [];
$_SESSION['hand'] =
    [0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3],
        1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]];
$_SESSION['player'] = 0;

$db->database->prepare('INSERT INTO games VALUES ()')->execute();
$_SESSION['game_id'] = $db->database->insert_id;

header('Location: index.php');
