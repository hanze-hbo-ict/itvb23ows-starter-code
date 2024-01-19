<?php namespace database;

function getState(): string
{
    return serialize([$_SESSION['hand'], $_SESSION['board'], $_SESSION['player']]);
}

function setState($state): void
{
    list($a, $b, $c) = unserialize($state);
    $_SESSION['hand'] = $a;
    $_SESSION['board'] = $b;
    $_SESSION['player'] = $c;
}

function getDatabase(): mysqli
{
    return new mysqli('mysql', 'root', '', 'hive');
}
