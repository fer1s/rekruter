<?php

session_start();

$mysqli = require __DIR__ . "/../database.php";

// Pobierz dane użytkownika jeśli zalogowany
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    $query = "SELECT * FROM users
              WHERE id = {$user_id}";

    $result = mysqli_query($mysqli, $query);
    $user = $result->fetch_assoc();

    // Sprawdź czy istnieje taki użytkownik
    if (!isset($user)) {
        header("Location: ../logout.php");
        exit;
    }

    // Sprawdź czy użytkownik ma uprawnienia administratora
    if ($user["permission_level"] < 1) {
        header("Location: ../index.php");
        exit;
    }
} else {
    $user_id = null;

    // Przekieruj użytkownika na strone główną
    header("Location: ../index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekruter - Panel administratora</title>

    <link rel="stylesheet" href="../styles/index.css">
    <link rel="stylesheet" href="../styles/pages/admin/main.css">
    <link rel="stylesheet" href="../styles/pages/admin/add.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="content">

        <div class="title">
            <h1><a href="../index.php"><i class='bx bx-left-arrow-alt'></i></a>Panel administratora</h1>
            <p>Witaj
                <span>
                    <?= htmlspecialchars(
                        explode(" ", $user["username"])[0]
                    ) ?>
                </span>!
            </p>
        </div>

        <hr>

        <div class="buttons">
            <a href="index.php">Główny panel</a>
            <a href="add.php" class="active">Dodaj projekt</a>
            <a href="users.php">Zarządzaj użytkownikami</a>
        </div>

        <hr>

        <h3>Dodaj nowy projekt</h3>
        <form action="process_add.php" method="POST">
            <div class="input_group">
                <p>Nazwa</p>
                <input type="text" name="project_name">
            </div>

            <div class="input_group">
                <p>Opis</p>
                <textarea name="project_description"></textarea>
            </div>

            <div class="input_group">
                <p>Maksymalna liczba uczestników</p>
                <input type="number" name="project_entry_limit">
            </div>

            <input type="submit" value="Dodaj">
        </form>
    </div>
</body>

</html>