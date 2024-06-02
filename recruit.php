<?php

session_start();

$mysqli = require __DIR__ . "/database.php";

// Sprawdź czy parametr id został przekazany
if (!isset($_GET["id"]) || $_GET["id"] === "") {
    header("Location: recruitments.php");
}

// Sprawdź czy parametr 'type' został przekazany
if (!isset($_GET["type"]) || $_GET["type"] === "") {
    header("Location: recruitments.php");
}

// Sprawdź czy parametr 'type' jest prawidłowy (recruitment/practice)
if ($_GET["type"] !== "recruitment" && $_GET["type"] !== "practice") {
    header("Location: recruitments.php");
}

$recruitment_id = $_GET["id"];
$type = $_GET["type"];

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
        exit;
    }

    // Sprawdź czy użytkownik ma uprawnienia administratora, jeśli tak to przekieruj go na stronę główną
    if ($user["permission_level"] > 0) {
        header("Location: index.php");
        exit;
    }

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

    // Sprawdź czy rekrutacja jest otwarta
    if ($project_details_result["entries_count"] >= $project_details_result["entries_limit"]) {
        header("Location: recruitments.php");
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
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekruter</title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/nav.css">
    <link rel="stylesheet" href="styles/pages/recruit.css">
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

        <?php if ($type === "practice"): ?>
            <div class="recruitment_details">
                <div class="title">
                    <h2><?= htmlspecialchars($project_details_result["name"]) ?></h2>
                </div>
                <div class="details">
                    <p>Limit miejsc: <?= $project_details_result["entries_limit"] ?></p>
                    <p>Data utworzenia: <?= $project_details_result["creation_date"] ?></p>
                    <p>Utworzone przez: <?= htmlspecialchars($project_details_result["created_by"]) ?></p>
                </div>
                <form action="submit_entry.php" method="POST">
                    <p>Ja, <input type="text" placeholder="Imię i nazwisko" name="personname">, telefon kontaktowy: <input type="tel" name="phone" placeholder="+48123123123">, e–mail: <input type="email" name="email" placeholder="E-mail">, uczeń/uczennica* Zespołu Szkół Elektronicznych im. Stanisława Staszica w Zduńskiej Woli, klasy <input type="text" placeholder="3bTP">. (podaj klasę wraz z
                        kierunkiem np. 3aTI) deklaruję udział w stażach zawodowych realizowanych w roku szkolnym 2022/2023 (w okresie wakacji) w liczbie 150 godzin w instytucji</p>

                    <div>
                        <input type="radio" name="institution" id="director" value="director">
                        <label for="director">Wskazanej przez Kierującego na staż</label>
                    </div>

                    <div>
                        <input type="radio" name="institution" id="selfly" value="selfly">
                        <label for="selfly">Wybranej samodzielnie (podaj dokładną nazwę instytucji i jej adres oraz NIP)</label>
                    </div>
                    <input type="text" style="width: 100%;" placeholder="Nazwa instytucji">

                    <p>Planowany termin odbycia stażu <input type="text" placeholder="Od - Do">.</p>

                    <p>Jednocześnie oświadczam, że jestem uczestnikiem/uczestniczką* projektu „Informatyku! Pracodawcy czekają na Ciebie” realizowanego w ramach w ramach Regionalnego Programu Operacyjnego Województwa Łódzkiego na lata 2014-2020 w ramach Działania XI.3 Kształcenie zawodowe. </p>

                    <p>W związku ze złożeniem deklaracji udziału w stażach oświadczam, że zapoznałem/am się z Regulaminem staży i akceptuję go w całości (w przypadku uczestnika niepełnoletniego dokument podpisuje rodzic/opiekun prawny).</p>
                </form>
            </div>
        <?php else: ?>
            <div class="recruitment_details">
                <div class="title">
                    <h2><?= htmlspecialchars($project_details_result["name"]) ?></h2>
                </div>
                <div class="details">
                    <p>Limit miejsc: <?= $project_details_result["entries_limit"] ?></p>
                    <p>Data utworzenia: <?= $project_details_result["creation_date"] ?></p>
                    <p>Utworzone przez: <?= htmlspecialchars($project_details_result["created_by"]) ?></p>
                </div>
                <form action="submit_entry.php" method="POST">
                    <p>Ja, <input type="text" placeholder="Imię i nazwisko" name="personname">, telefon kontaktowy: <input type="tel" name="phone" placeholder="+48123123123">, e–mail: <input type="email" name="email" placeholder="E-mail">, uczeń/uczennica* Zespołu Szkół Elektronicznych im. Stanisława Staszica w Zduńskiej Woli, klasy <input type="text" placeholder="3bTP">. (podaj klasę wraz z
                        kierunkiem np. 3aTI) deklaruję udział w stażach zawodowych realizowanych w roku szkolnym 2022/2023 (w okresie wakacji) w liczbie 150 godzin w instytucji</p>

                    <div>
                        <input type="radio" name="institution" id="director" value="director">
                        <label for="director">Wskazanej przez Kierującego na staż</label>
                    </div>

                    <div>
                        <input type="radio" name="institution" id="selfly" value="selfly">
                        <label for="selfly">Wybranej samodzielnie (podaj dokładną nazwę instytucji i jej adres oraz NIP)</label>
                    </div>
                    <input type="text" style="width: 100%;" placeholder="Nazwa instytucji">

                    <p>Planowany termin odbycia stażu <input type="text" placeholder="Od - Do">.</p>

                    <p>Jednocześnie oświadczam, że jestem uczestnikiem/uczestniczką* projektu „Informatyku! Pracodawcy czekają na Ciebie” realizowanego w ramach w ramach Regionalnego Programu Operacyjnego Województwa Łódzkiego na lata 2014-2020 w ramach Działania XI.3 Kształcenie zawodowe. </p>

                    <p>W związku ze złożeniem deklaracji udziału w stażach oświadczam, że zapoznałem/am się z Regulaminem staży i akceptuję go w całości (w przypadku uczestnika niepełnoletniego dokument podpisuje rodzic/opiekun prawny).</p>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>