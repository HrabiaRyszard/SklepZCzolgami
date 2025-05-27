<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep Ogrodniczy</title>
    <link rel="stylesheet" href="../Styles/styleRegisterAndLogin.txt">
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
            <a href="register.html"><button>Rejestracja</button></a>
        </div>
    </header>

    <main>
        <div class="form-container">
            <h2>Logowanie</h2>
            <form action="login.php" method="post">
                <input type="text" name="username" placeholder="Nazwa użytkownika" required>
                <input type="password" name="password" placeholder="Hasło" required>
                <button type="submit">Zaloguj się</button>
            </form>
            <div class="link">
                Nie masz konta? <a href="register.html">Zarejestruj się</a>
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