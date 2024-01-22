<?php namespace app;

require_once(__DIR__ . "/database/database.php");
use app\database\Database;

session_start();

$db = new Database();
$stmt = $db->getDatabase()->prepare('SELECT * FROM moves WHERE id = '.$_SESSION['last_move']);
$stmt->execute();
$result = $stmt->get_result()->fetch_array();
$_SESSION['last_move'] = $result[5];
$db->setState($result[6]);
header('Location: index.php');
