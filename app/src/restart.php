<?php namespace app;

require_once(__DIR__ . "/database/database.php");
use app\database\Database;

session_start();

$_SESSION['board'] = [];
$_SESSION['hand'] = [
    0 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3],
    1 => ["Q" => 1, "B" => 2, "S" => 2, "A" => 3, "G" => 3]
];
$_SESSION['player'] = 0;

$db = new Database();
$db->getDatabase()->prepare('INSERT INTO games VALUES ()')->execute();
$_SESSION['game_id'] = $db->getDatabase()->insert_id;

header('Location: index.php');
