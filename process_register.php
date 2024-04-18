<?php

// Sprawdz czy wszystkie dane są podane
if (empty($_POST["username"])) {
    die("Imie i nazwisko jest wymagane!");
}

if (empty($_POST["email"])) {
    die("E-mail jest wymagany");
}

if (empty($_POST["password"]) || empty($_POST["password_repeat"])) {
    die("Haslo jest wymagane");
}

// Zdefiniuj zmienne do łatwiejszego użycia
$username = trim($_POST["username"]);
$email = trim($_POST["email"]);
$password = trim($_POST["password"]);
$repeatPassword = trim($_POST["password_repeat"]);

// Sprawdź dane
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Niepoprawny e-mail");
}

if (strlen($password) < 8) {
    die("Haslo jest za krotkie (min. 8 znakow)");
}

if (!preg_match("/[a-z]/i", $password)) {
    die("Haslo musi zawierac min. 1 litere");
}

if (!preg_match("/[0-9]/i", $password)) {
    die("Haslo musi zawierac min. 1 cyfre");
}

if ($password !== $repeatPassword) {
    die("Hasla sie nie zgadzaja");
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/database.php";
$query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$statement = $mysqli->stmt_init();

if (!$statement->prepare($query)) {
    die("Blad SQL: " . $mysqli->error);
}

$statement->bind_param("sss", $username, $email, $password_hash);

if ($statement->execute()) {
    header("Location: login.php");
    exit;
} else {
    if ($mysqli->errno === 1062) {
        die("Ten e-mail jest zajety");
    } else {
        die($mysqli->error . " " . $mysqli->errno);
    }
}