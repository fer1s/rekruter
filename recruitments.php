<?php

session_start();

if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
    $mysqli = require __DIR__ . "/database.php";

    $query = "SELECT * FROM users
              WHERE id = {$user_id}";

    $result = mysqli_query($mysqli, $query);
    $user = $result->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekruter - Rekrutacje</title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/nav.css">
</head>

<body>
    <div class="content">
        <nav>
            <a href="index.php">
                <h1>rekruter</h1>
            </a>
            <div class="links">
                <a href="index.php">Strona główna</a>
                <a href="recruitments.php">Rekrutacje</a>
                <?php if (isset($user)): ?>
                    <a href="dashboard.php">Panel</a>
                    <?php if ($user["permission_level"] > 0): ?>
                        <a href="admin/index.php">Admin</a>
                    <?php endif; ?>
                    <a href="logout.php">Wyloguj</a>
                <?php else: ?>
                    <a href="login.php">Zaloguj</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</body>

</html>