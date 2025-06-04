<?php
session_start();
require 'db.php';

if (isset($_SESSION['role'])) {
    if($_SESSION['role'] === 'user') {
        header("Location: ./user/account.php");
        exit();
    }
    elseif ($_SESSION['role'] === 'admin') {
        header("Location: ./admin/adminPanel.php");
        exit();
    }
}

$loginError = $registerError = $trackError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    if ($action === 'login') {
        $login = mysqli_real_escape_string($db, $_POST['login']);
        $password = mysqli_real_escape_string($db, $_POST['haslo']);

        $sql_user = "SELECT * FROM uzytkownik WHERE login = '$login'";
        $result_user = mysqli_query($db, $sql_user);
        if (mysqli_num_rows($result_user) > 0) {
            $user = mysqli_fetch_assoc($result_user);
            if (password_verify($password, $user['haslo'])) {
                $_SESSION['userID'] = $user['id'];
                $_SESSION['username'] = $user['login'];
                $_SESSION['role'] = 'user';
                header("Location: index.php");
                exit();
            } else {
                $loginError = "Złe hasło!";
            }
        } else {
            $sql_admin = "SELECT * FROM pracownik WHERE login = '$login'";
            $result_admin = mysqli_query($db, $sql_admin);

            if (mysqli_num_rows($result_admin) > 0) {
                $admin = mysqli_fetch_assoc($result_admin);
                if ($password === $admin['haslo']) {
                    $_SESSION['userID'] = $admin['id'];
                    $_SESSION['username'] = $admin['login'];
                    $_SESSION['role'] = 'admin';
                    header("Location: ./admin/adminPanel.php");
                    exit();
            } else {
                $loginError = "Złe hasło!";
            }
            } else {
                $loginError = "Nie znaleziono użytkownika!";
            }
        }
    } elseif ($action === 'register') {
        $imie = mysqli_real_escape_string($db, $_POST['imie']);
        $nazwisko = mysqli_real_escape_string($db, $_POST['nazwisko']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $login = mysqli_real_escape_string($db, $_POST['login']);
        $password = mysqli_real_escape_string($db, $_POST['haslo']);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $check = mysqli_query($db, "SELECT * FROM uzytkownik WHERE login = '$login'");
        if (mysqli_num_rows($check) > 0) {
            $registerError = "Login już istnieje!";
        } else {
            $sql = "INSERT INTO uzytkownik (imie, nazwisko, email, login, haslo) VALUES ('$imie', '$nazwisko', '$email', '$login', '$hashed_password')";
            if (mysqli_query($db, $sql)) {
                $registerError = "Rejestracja udana! Możesz się zalogować.";
            } else {
                $registerError = "Błąd rejestracji.";
            }
        }
    } elseif ($action === 'track') {
        $order_number = mysqli_real_escape_string($db, $_POST['order_number']);
        $track_email = mysqli_real_escape_string($db, $_POST['track_email']);

        $sql = "SELECT status FROM zamowienie z left join uzytkownik u ON z.uzytkownik_id = u.id WHERE z.id = '$order_number' AND u.email = '$track_email'";
        $result = mysqli_query($db, $sql);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $trackError = "Status zamówienia: " . htmlspecialchars($row['status']);
        } else {
            $trackError = "Nie znaleziono zamówienia.";
        }
    }
}
mysqli_close($db);
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moje Konto - Sklep Ogrodniczy</title>
    <link rel="stylesheet" href="./style/loginStyl.css">
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
            <a href="login.php" >
                <button class="iconButton">
                    <img src="./icons/account.svg" alt="Konto" style="width:48px; height:48px; vertical-align:middle;">
                </button>
            </a>
        </div>
    </header>
    <main>
        <h1 style="margin-top: 30px;">MOJE KONTO</h1>
        <div class="authPanel">
            <div style="flex:1; max-width: 400px;">
                <div class="authTabs">
                    <div class="authTab active" id="loginTab">ZALOGUJ SIĘ</div>
                    <div class="authTab" id="registerTab">STWÓRZ KONTO</div>
                </div>
                <div class="authForm" id="loginContent">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="login">
                        <label class="authLabel">Login *</label>
                        <input class="authInput" type="text" name="login" required>
                        <label class="authLabel">Hasło *</label>
                        <input class="authInput" type="password" name="haslo" required>
                        <button class="authButton" type="submit">ZALOGUJ SIĘ I KONTYNUUJ</button>
                        <div class="authError"> <?= $loginError ?> </div>
                    </form>
                </div>
                <div class="authForm" id="registerContent" style="display:none;">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="register">
                        <label class="authLabel">Imię *</label>
                        <input class="authInput" type="text" name="imie" required>
                        <label class="authLabel">Nazwisko *</label>
                        <input class="authInput" type="text" name="nazwisko" required>
                        <label class="authLabel">E-mail *</label>
                        <input class="authInput" type="email" name="email" required>
                        <label class="authLabel">Login *</label>
                        <input class="authInput" type="text" name="login" required>
                        <label class="authLabel">Hasło *</label>
                        <input class="authInput" type="password" name="haslo" required>
                        <button class="authButton" type="submit">STWÓRZ KONTO</button>
                        <div class="authError"> <?= $registerError ?> </div>
                    </form>
                </div>
            </div>
            <div class="orderStatusBox">
                <h2>SPRAWDŹ STATUS ZAMÓWIENIA</h2>
                <form method="post" action="">
                    <input type="hidden" name="action" value="track">
                    <label class="authLabel">Numer zamówienia</label>
                    <input class="authInput" type="text" name="order_number" required>
                    <label class="authLabel">E-mail</label>
                    <input class="authInput" type="email" name="track_email" required>
                    <button class="authButton" type="submit">SPRAWDŹ STATUS</button>
                    <div class="authError"> <?= $trackError ?> </div>
                </form>
            </div>
        </div>
    </main>
    <footer>
        <div class="noMargin">
            Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
        </div>
    </footer>
    <script>
        document.getElementById('loginTab').onclick = function () {
            this.classList.add('active');
            document.getElementById('registerTab').classList.remove('active');
            document.getElementById('loginContent').style.display = '';
            document.getElementById('registerContent').style.display = 'none';
        };
        document.getElementById('registerTab').onclick = function () {
            this.classList.add('active');
            document.getElementById('loginTab').classList.remove('active');
            document.getElementById('registerContent').style.display = '';
            document.getElementById('loginContent').style.display = 'none';
        };
    </script>
</body>

</html>
