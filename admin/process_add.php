<?php

// project_name
// project_description
// project_entry_limit

session_start();

if (empty($_POST["project_name"])) {
    die("Nazwa projektu jest wymagana");
}

if (empty($_POST["project_description"])) {
    die("Opis jest wymagany");
}

if (empty($_POST["project_entry_limit"])) {
    die("Maksymalna liczba uczestnikow jest wymagana");
}

$project_name = $_POST["project_name"];
$project_description = $_POST["project_description"];
$project_entry_limit = $_POST["project_entry_limit"];

// Walidacja danych
if (!is_numeric($project_entry_limit)) {
    die("Bledna wartosc wejsciowa max. liczby uczestnikow");
}

$mysqli = require __DIR__ . "/../database.php";
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    $query = "SELECT * FROM users
              WHERE id = {$user_id}";

    $result = mysqli_query($mysqli, $query);
    $user = $result->fetch_assoc();

    // Sprawdź czy istnieje taki użytkownik
    if (!isset($user)) {
        header("Location: ../logout.php");
    }

    // Sprawdź czy użytkownik ma uprawnienia administratora
    if ($user["permission_level"] < 1) {
        header("Location: ../index.php");
        exit;
    }

    $user_id = $user["id"];
    $add_query = "INSERT INTO recruitments (name, description, entries_limit, created_by)
                 VALUES (?, ?, ?, ?)";
    $add_statement = $mysqli->stmt_init();

    if (!$add_statement->prepare($add_query)) {
        die("Blad SQL: " . $mysqli->error);
    }

    $add_statement->bind_param("ssss", $project_name, $project_description, $project_entry_limit, $user_id);

    if ($add_statement->execute()) {
        header("Location: index.php");
        exit;
    } else {
        die("Wystapil blad! " . $mysqli->error);
    }
} else {
    $user_id = null;

    // Przekieruj użytkownika na strone główną
    header("Location: ../index.php");
    exit;
}