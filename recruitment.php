<?php

session_start();

$mysqli = require __DIR__ . "/database.php";

if (!isset($_GET["id"]) || $_GET["id"] === "") {
    header("Location: recruitments.php");
}

$recruitment_id = $_GET["id"];
$logged_in = false;
$is_admin = false;

// Pobierz dane użytkownika jeśli zalogowany
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    $query = "SELECT * FROM users
              WHERE id = {$user_id}";

    $result = mysqli_query($mysqli, $query);
    $user = $result->fetch_assoc();

    if (isset($user["id"])) {
        $logged_in = true;

        if ($user["permission_level"] > 0) {
            $is_admin = true;
        }
    }
}

// Pobierz szczegóły rekrutacji
$project_query = "SELECT r.id, r.name, r.description, r.entries_limit, r.creation_date, u.username AS created_by, (SELECT COUNT(*) FROM entries WHERE recruitment = r.id) AS entries_count
                 FROM recruitments AS r
                 LEFT JOIN users AS u ON r.created_by = u.id
                 WHERE r.id = {$recruitment_id};";

$project_details = mysqli_query($mysqli, $project_query);
$project_details_result = mysqli_fetch_assoc($project_details);

if (!isset($project_details_result)) {
    header("Location: recruitments.php");
    exit;
}

// TODO: Sprawdź czy użytkownik jest już zgłoszony

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekruter</title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/pages/recruitment.css">
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

        <div class="recruitment_details">
            <div class="title">
                <h2>
                    <?= htmlspecialchars($project_details_result["name"]) ?>
                    <br>
                    <span>Utworzone przez
                        <b>
                            <?= htmlspecialchars($project_details_result["created_by"]) ?>
                        </b>
                        /
                        <b>
                            <?= htmlspecialchars($project_details_result["creation_date"]) ?>
                        </b>
                    </span>
                </h2>
                <div class="entries">
                    <h5>Liczba kandydatów</h5>
                    <p>
                        <?= htmlspecialchars($project_details_result["entries_count"]) ?> /
                        <?= htmlspecialchars($project_details_result["entries_limit"]) ?>
                    </p>
                </div>
            </div>

            <div class="description">
                <p>
                    <?php echo nl2br($project_details_result["description"]) ?>
                </p>
            </div>

            <div class="actions 
                    <?php if (!$logged_in): ?>
                        logged_out
                    <?php endif; ?>">

                <?php if ($logged_in): ?>

                    <?php if ($is_admin): ?>
                        <a href="admin/edit.php?id=<?= $project_details_result["id"] ?>" class="button">Edytuj</a>
                    <?php else: ?>
                        <?php if ($project_details_result["entries_count"] < $project_details_result["entries_limit"]): ?>
                            <a href="recruit.php?id=<?= $recruitment_id ?>&type=recruitment" class="button">Rekrutuj</a>
                            <a href="recruit.php?id=<?= $recruitment_id ?>&type=practice" class="button">Zgłoś się na staż</a>
                        <?php endif; ?>
                    <?php endif; ?>



                <?php else: ?>
                    <a href="login.php" class="button">Zaloguj się, aby zgłosić</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>