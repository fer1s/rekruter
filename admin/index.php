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

    // Pobierz projekty użytkownika
    $projects_query = "SELECT * FROM recruitments WHERE created_by = {$user['id']}";
    $projects = mysqli_query($mysqli, $projects_query);
    $projects_result = $projects->fetch_all(MYSQLI_ASSOC);
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
    <link rel="stylesheet" href="../styles/pages/admin/home.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="content">

        <div class="title">
            <h1><a href="../index.php"><i class='bx bx-left-arrow-alt'></i></a>Panel administratora</h1>
            <p>Witaj
                <span>
                    <?= htmlspecialchars(
                        $userName = explode(" ", $user["username"])[0]
                    ) ?>
                </span>!
            </p>
        </div>

        <hr>

        <div class="buttons">
            <a href="index.php" class="active">Główny panel</a>
            <a href="add.php">Dodaj projekt</a>
            <a href="users.php">Zarządzaj użytkownikami</a>
        </div>

        <hr>

        <div class="projects">
            <h3>Twoje projekty</h3>
            <div class="projects_list <?php if (!isset($projects_result)): ?> empty <?php endif; ?>">

                <?php if (isset($projects_result)): ?>
                    <?php foreach ($projects_result as $row) { ?>
                        <div class="project">
                            <h4>
                                <?= htmlspecialchars($row['name']) ?>
                            </h4>
                            <p>
                                <?= htmlspecialchars(strlen($row['description']) > 30 ? substr($row['description'], 0, 30) . "..." : $row['description']) ?>
                            </p>

                            <div class="project_buttons">
                                <a href="edit.php?id=<?= htmlspecialchars($row['id']) ?>">Edytuj</a>
                                <a href="delete.php?id=<?= htmlspecialchars($row['id']) ?>">Usuń</a>
                                <a href="../recruitment.php?id=<?= htmlspecialchars($row['id']) ?>">Informacje</a>
                            </div>
                        </div>
                    <?php } ?>
                <?php else: ?>
                    <p>Nie znaleziono żadnych rekrutacji utworzonych przez Ciebie.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>