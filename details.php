<?php
require './db.php';

$id = $_GET['id'];

$sql = "SELECT * FROM produkt WHERE id = $id";
$result = mysqli_query($db, $sql);

if (mysqli_num_rows($result) != 1) {
    echo "Nie znaleziono produktu.";
    exit;
}

$product = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title><?php echo $product['nazwa']; ?></title>
    <link rel="stylesheet" href="./style/styl.css">
</head>

<body>
    <header>
        <a href="index.php">
            <h1 class="noMargin">Sklep ogrodniczy</h1>
        </a>
        <div class="buttonContainer">
            <a href="products.php">
                <button class="iconButton">
                    <img src="./icons/products.svg" alt="Produkty" style="width:48px; height:48px; vertical-align:middle;">
                </button>
            </a>
            <a href="cart.php">
                <button class="iconButton">
                    <img src="./icons/cart.svg" alt="Koszyk" style="width:48px; height:48px; vertical-align:middle;">
                </button> 
            </a>
            <a href="login.php" >
                <button class="iconButton">
                    <img src="./icons/account.svg" alt="Konto" style="width:48px; height:48px; vertical-align:middle;">
                </button>
            </a>
        </div>
    </header>
    <main>
        <div class="details">
            <div class="imagesView"><img src="./images/<?php echo $product['url_zdjecia'] ? $product['url_zdjecia'] : 'placeholder.png'; ?>" alt="zdjęcie"></div>
            <div class="description">
                <p><strong>Opis:</strong> <?php echo $product['opis']; ?></p>
                <p><strong>Cena:</strong> <?php echo $product['cena']; ?> zł</p>
                <p><strong>Ilość:</strong> <?php echo $product['ilosc']; ?></p>
            </div>
                <div class="productName"><h2><?php echo $product['nazwa']; ?></h2></div>
                <div class="buyButton">
                    <button onclick="addToCart(<?php echo $product['id']; ?>); return false;">Dodaj do Koszyka</button>
                </div>
        </div>
    </main>
    <footer>
        <div class="noMargin">
            Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
        </div>
    </footer>
    <script src="script.js"></script>
</body>

</html>
