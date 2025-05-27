<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep Ogrodniczy</title>
    <link rel="stylesheet" href="../Styles/styleRegisterAndLogin.css">
</head>

<body>
    <header>
        <a href="index.html">
            <h1 class="noMargin">Sklep ogrodniczy</h1>
        </a>
        <div class="hOptions">
            <a href="products.html">Sklep</a>
            <a href="cart.html">Koszyk</a>
        </div>
        <div class="buttonContainer">
            <a href="login.html"><button>Logowanie</button></a>
        </div>
    </header>

    <main>
        <div class="form-container">
            <h2>Rejestracja</h2>
            <form action="register.php" method="post">
                <input type="text" name="firstname" placeholder="Imię" required>
                <input type="text" name="lastname" placeholder="Nazwisko" required>
                <input type="text" name="username" placeholder="Nazwa użytkownika" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Hasło" required>
                <button type="submit">Zarejestruj się</button>
            </form>
            <div class="link">
                Masz już konto? <a href="login.html">Zaloguj się</a>
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