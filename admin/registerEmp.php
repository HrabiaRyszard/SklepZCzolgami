<?php
require '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imie = mysqli_real_escape_string($db, $_POST['imie']);
    $nazwisko = mysqli_real_escape_string($db, $_POST['nazwisko']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $login = mysqli_real_escape_string($db, $_POST['login']);
    $password = mysqli_real_escape_string($db, $_POST['haslo']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO pracownik (imie, nazwisko, email, login, haslo) VALUES ('$imie', '$nazwisko', '$email', '$login', '$hashed_password')";
    mysqli_query($db, $sql);

    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep Ogrodniczy</title>
    <link rel="stylesheet" href="../style/styl.css">
</head>

<body>
    <header>
        <a href="index.php">
            <h1 class="noMargin">Sklep ogrodniczy</h1>
        </a>
        <div class="hOptions">
            <a href="products.php">Sklep</a>
            <a href="cart.php">Koszyk</a>
        </div>
        <div class="buttonContainer">
            <a href="../login.php"><button>Logowanie</button></a>
            <a href="registerEmp.php"><button>Rejestracja pracownika</button></a>
        </div>
    </header>

    <main>
        <div class="center">
            <div class="userForm">
                <h2>Zarejestruj pracownika</h2>
                <form action="#" method="POST">
                    <input type="text" name="imie" placeholder="Imie" required>
                    <input type="text" name="nazwisko" placeholder="Nazwisko" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="text" name="login" placeholder="Login" required>
                    <input type="password" name="haslo" placeholder="Hasło" required>
                    <button type="submit">Zarejestruj pracownika</button>
                </form>
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
