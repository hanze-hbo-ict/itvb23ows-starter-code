<?php

function getState() {
    return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
}

function setState($state) {
    list($a, $b, $c) = unserialize($state);
    $_SESSION['hand'] = $a;
    $_SESSION['board'] = $b;
    $_SESSION['player'] = $c;
}

$host = getenv('HOST') ?: 'database';
$user = getenv('USER') ?: 'hiveuser';
$password = getenv('PASSWORD') ?: 'hivepassword';
$name = getenv('NAME') ?: 'hive';

return new mysqli($host, $user, $password, $name);
