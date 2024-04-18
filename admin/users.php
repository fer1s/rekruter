<?php

session_start();

// Pobierz dane użytkownika jeśli zalogowany
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    $mysqli = require __DIR__ . "/../database.php";

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

    $users_query = "SELECT id, username, email, permission_level FROM users";
    $users = mysqli_query($mysqli, $users_query);
    $users_result = $users->fetch_all(MYSQLI_ASSOC);
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
    <link rel="stylesheet" href="../styles/pages/admin/users.css">

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
            <a href="index.php">Główny panel</a>
            <a href="add.php">Dodaj projekt</a>
            <a href="users.php" class="active">Zarządzaj użytkownikami</a>
        </div>

        <hr>

        <h3>Zarządzaj</h3>
        <form action="process_user_action.php" method="POST">
            <div>
                <div class="input_group">
                    <p>ID</p>
                    <input required type="number" name="identificator">
                </div>

                <div class="input_group">
                    <p>Akcja</p>
                    <div class="radios">
                        <div>
                            <input required type="radio" name="action" value="permissions">
                            <label for="permissions">Zmień permisje</label>
                        </div>
                        <div>
                            <input required type="radio" name="action" value="delete">
                            <label for="permissions">Usuń</label>
                        </div>
                    </div>
                </div>
            </div>

            <input type="submit" value="Wykonaj">
        </form>

        <hr>

        <div class="users">
            <h3>Lista użytkowników</h3>

            <div class="users_table" role="region" tabindex="0">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Imię</th>
                            <th>E-mail</th>
                            <th>Poziom uprawnień</th>
                        </tr>
                        <?php foreach ($users_result as $row) { ?>
                            <tr>
                                <td>
                                    <?= htmlspecialchars($row['id']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['username']) ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($row['email']) ?>
                                </td>
                                <td>
                                    <?php if ($row['permission_level'] >= 1): ?>
                                        Administrator
                                    <?php else: ?>
                                        Użytkownik
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>