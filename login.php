<?php
session_start();
require 'db.php';

$error_message = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = mysqli_real_escape_string($db, $_POST['login']);
    $password = mysqli_real_escape_string($db, $_POST['haslo']);

    $sql = "SELECT * FROM uzytkownik WHERE login = '$login'";
    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['haslo'])) {
            $_SESSION['userID'] = $user['uzytkownik_id']; 
            $_SESSION['username'] = $user['login'];
            header("Location: index.php"); 
            exit();
        } else {
            $error_message = "Złe hasło!";
        }
    } else {
        $error_message = "Nie znaleziono użytkownika!";
    }
}
mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep Ogrodniczy</title>
    <link rel="stylesheet" href="styl.css">
</head>
<body>
    <header>
        <a href="index.php"><h1 class="noMargin">Sklep ogrodniczy</h1></a>
        <div class="hOptions">
            <a href="products.php">Sklep</a>
            <a href="cart.php">Koszyk</a>
        </div>
        <div class="buttonContainer">
            <a href="login.php"><button>Logowanie</button></a>
            <a href="register.php"><button>Rejestracja</button></a>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Zaloguj się</h2>
            <form action="#" method="POST">
                <input type="text" name="login" placeholder="Login" required>
                <input type="password" name="haslo" placeholder="Hasło" required>
                <button type="submit">Zaloguj się</button>
            </form>

            <div class="link">
                <p>Nie posiadasz konta? <a href="register.php">Zarejestruj się tutaj</a></p>
                <?php
                if ($error_message) {
                    echo '<p class="error">' . $error_message . '</p>';
                    $error_message = 0; 
                }
                ?>
            </div>
        </div>
    </main>

    <footer>
        <div class="noMargin">
            Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
        </div>
    </footer>
</body>
</html>
