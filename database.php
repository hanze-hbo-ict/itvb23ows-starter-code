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

//return new mysqli('172.17.0.2', 'root', '', 'hive');
return new mysqli('mysql', 'root', '', 'hive');

?>