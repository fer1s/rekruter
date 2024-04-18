<?php

$is_invalid = false;
$email = "";

if ($_SERVER["REQUEST_METHOD"] === 'POST') {

    $mysqli = require __DIR__ . "/database.php";

    $email = $_POST["email"];
    $password = $_POST["password"];

    $query = sprintf(
        "SELECT * FROM users WHERE email = '%s'",
        $mysqli->real_escape_string($email)
    );

    $result = $mysqli->query($query);
    $user = $result->fetch_assoc();

    if ($user) {
        $password_valid = password_verify($password, $user["password"]);
        if ($password_valid) {

            session_start();
            session_regenerate_id();

            $_SESSION["user_id"] = $user["id"];

            header("Location: index.php");
            exit;
        }
    }

    $is_invalid = true;
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekruter - Logowanie</title>
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/pages/auth.css">

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="content auth_page">
        <h1><a href="index.php"><i class='bx bx-left-arrow-alt'></i></a>Logowanie</h1>

        <form method="post">
            <div class="input_holder">
                <p>Email</p>
                <input type="email" placeholder="jan.kowalski@example.com" name="email" id="email" required value="<?= htmlspecialchars($email) ?? "" ?>">
            </div>
            <div class="input_holder">
                <p>Hasło</p>
                <input type="password" placeholder="Hasło" name="password" id="password" required>
            </div>

            <div class="button">
                <input type="submit" value="Zaloguj">
                <p>Nie masz konta? <a href="register.html">Zarejestruj się</a></p>
                <?php if ($is_invalid): ?>
                    <p class="error">Niepoprawne dane</p>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="badge">
        <h5>Stworzone przez <span>Jakub Molenda 3bTP</span></h5>
    </div>
</body>

</html>