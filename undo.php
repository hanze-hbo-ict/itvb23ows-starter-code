<?php namespace undo;

use database;

session_start();

$db = database\getDatabase();
$stmt = $db->prepare('SELECT * FROM moves WHERE id = '.$_SESSION['last_move']);
$stmt->execute();
$result = $stmt->get_result()->fetch_array();
$_SESSION['last_move'] = $result[5];
database\setState($result[6]);
header('Location: index.php');
