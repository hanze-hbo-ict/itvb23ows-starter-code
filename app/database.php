<?php

function get_state() {
    return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
}

function set_state($state) {
    list($a, $b, $c) = unserialize($state);
    $_SESSION['hand'] = $a;
    $_SESSION['board'] = $b;
    $_SESSION['player'] = $c;
}

$host = 'db';
$user = 'root';
$password = 'Incognito153!';
$db = 'hive';

$conn = new mysqli('db', $user, $password, $db);
if($conn->connect_error) {
    echo 'connection failed' . $conn->connect_error;
} else {
    return $conn;
}
