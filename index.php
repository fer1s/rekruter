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
}

// Pobierz liste rekrutacji
// Najnowsze 3
$three_latest_query = "SELECT r.*, COUNT(e.id) AS entries_count 
                     FROM recruitments AS r
                     LEFT JOIN entries AS e ON e.recruitment = r.id
                     GROUP BY r.id
                     HAVING entries_count < r.entries_limit
                     ORDER BY r.creation_date DESC
                     LIMIT 9;";
$three_latest = mysqli_query($mysqli, $three_latest_query);
$three_latest_result = $three_latest->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekruter</title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/pages/home.css">
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

        <div class="recruitments">
            <h2>Najnowsze rekrutacje</h2>
            <div class="recruitments_list
                <?php if (!isset($three_latest_result)): ?> empty <?php endif; ?>
            ">
                <?php if (isset($three_latest_result)): ?>
                    <?php foreach ($three_latest_result as $row) {

                        ?>
                        <div class="recruitment">
                            <h2>
                                <?= htmlspecialchars($row['name']) ?>
                            </h2>
                            <p>
                                <?= htmlspecialchars(strlen($row['description']) > 30 ? substr($row['description'], 0, 30) . "..." : $row['description']) ?>
                            </p>

                            <div class="entries">
                                <p>
                                    <?= htmlspecialchars($row['entries_count']) ?> /
                                    <?= htmlspecialchars($row['entries_limit']) ?>
                                </p>
                            </div>

                            <a href="recruitment.php?id=<?= htmlspecialchars($row['id']) ?>">Więcej informacji</a>
                        </div>

                    <?php } ?>
                <?php else: ?>
                    <p>Nie znaleziono żadnych dostępnych rekrutacji.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>