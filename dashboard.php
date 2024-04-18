<?php

session_start();

$mysqli = require __DIR__ . "/database.php";

// Pobierz dane użytkownika jeśli zalogowany
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    $query = "SELECT * FROM users
              WHERE id = {$user_id}";

    $result = mysqli_query($mysqli, $query);
    $user = $result->fetch_assoc();

    // Sprawdź czy istnieje taki użytkownik
    if (!isset($user)) {
        header("Location: logout.php");
    }
} else {
    $user_id = null;

    // Przekieruj użytkownika na strone główną
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekruter - Panel</title>

    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/pages/dashboard.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="content">

        <div class="title">
            <h1><a href="index.php"><i class='bx bx-left-arrow-alt'></i></a>Panel użytkownika</h1>
            <p>Witaj
                <span>
                    <?= htmlspecialchars(
                        $userName = explode(" ", $user["username"])[0]
                    ) ?>
                </span>!
            </p>
        </div>

        <hr>

        <div class="recuritments">
            <h2>Podania o rekrutację</h2>
        </div>

        <div class="recuritments">
            <h2>Podania o staż</h2>
        </div>

    </div>
</body>

</html>