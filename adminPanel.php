<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep Ogrodniczy</title>
    <link rel="stylesheet" href="./style/styl.css">
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
            <a href="login.php"><button>Logowanie</button></a>
            <a href="register.php"><button>Rejestracja</button></a>
        </div>
    </header>
    <main>
        <div>
            <h2>Panel Admina</h2>
            <p>Witaj w panelu administracyjnym sklepu ogrodniczego. Tutaj możesz zarządzać produktami, kategoriami i innymi ustawieniami sklepu.</p>
            <h3>Opcje administracyjne:</h3>
            <a href="addProduct.php">Dodaj produkt</a>
            <a href="manageProducts.php">Zarządzaj produktami</a>
            <a href="addCategory.php">Dodaj kategorię</a>
            <a href="manageCategories.php">Zarządzaj kategoriami</a>
            <a href="viewOrders.php">Przeglądaj zamówienia</a>
            <a href="manageUsers.php">Zarządzaj użytkownikami</a>
            <a href="settings.php">Ustawienia sklepu</a>

        </div>
    </main>
    <footer>
        Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
    </footer>
</body>

</html>
