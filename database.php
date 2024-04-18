<?php

$db_host = "localhost";
$db_name = "rekruter";
$db_username = "root";
$db_password = "";

$mysqli = new mysqli(
    hostname: $db_host,
    username: $db_username,
    password: $db_password,
    database: $db_name
);

if ($mysqli->connect_error) {
    die("Blad polaczenia z baza danych! Blad: " . $mysqli->connect_error);
}

return $mysqli;