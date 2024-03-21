<?php

session_start();

use functions\Database as Database;

require_once './vendor/autoload.php';

$db = new Database();
$stmt = $db->database->prepare('SELECT * FROM moves WHERE id = '.$_SESSION['last_move']);
$stmt->execute();
$result = $stmt->get_result()->fetch_array();
$_SESSION['last_move'] = $result[5];
$db->setState($result[6]);
header('Location: index.php');
