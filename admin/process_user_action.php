<?php

// identificator
// action

session_start();

if (empty($_POST["identificator"])) {
    die("Identyfikator użytkownika jest wymagany");
}

if (empty($_POST["action"])) {
    die("Akcja jest wymagana");
}

$identificator = $_POST["identificator"];
$action = $_POST["action"]; // permissions/delete

if (!($action === "permissions" || $action === "delete")) {
    die("Zły typ akcji");
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

    $get_user_query = "SELECT * FROM users WHERE id={$identificator}";
    $get_user = mysqli_query($mysqli, $get_user_query);
    $get_user_result = mysqli_fetch_assoc($get_user);

    if (!isset($get_user_result)) {
        die("Nie znaleziono takiego uzytkownika!");
    }

    $action_query = "";

    if ($action === "permissions") {
        // Zmien permisje uzytkownika (jesli 0 zmien na 1, jesli 1 zmien na 0)
        if ($get_user_result["permission_level"] === 0) {
            $action_query = "UPDATE users SET permission_level = 1 WHERE users.id = {$get_user_result['id']};";
        } else {
            $action_query = "UPDATE users SET permission_level = 0 WHERE users.id = {$get_user_result['id']};";
        }

    } elseif ($action === "delete") {
        // Usun uzytkownika
        $action_query = "DELETE FROM users WHERE users.id = {$get_user_result['id']};";
    }

    $action_statement = $mysqli->stmt_init();

    if (!$action_statement->prepare($action_query)) {
        die("Blad SQL: " . $mysqli->error);
    }

    if ($action_statement->execute()) {
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