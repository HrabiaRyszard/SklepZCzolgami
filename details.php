<?php
require 'db.php';

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
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script src="script.js"></script>
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
                    <img src="./icons/cart.svg" alt="Produkty" style="width:48px; height:48px; vertical-align:middle;">
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
            <div class="imagesView">
                <div class="productName">
                    <h2><?php echo $product['nazwa']; ?></h2>
                </div>
                <div class="imagesView">
                    <model-viewer
                        src="./models/<?php echo $product['model']; ?>"
                        alt="Model 3D produktu"
                        auto-rotate
                        camera-controls
                        background-color="#ffffff"
                        style="width: 400px; height: 400px;">
                    </model-viewer>
                </div>
                <div class="description">
                    <p><strong>Opis:</strong> <?php echo $product['opis']; ?></p>
                    <p><strong>Cena:</strong> <?php echo $product['cena']; ?> zł</p>
                    <p><strong>Ilość dostępnych w magazynie:</strong> <?php echo $product['ilosc']; ?></p>
                </div>
                <button onclick="addToCart(<?php echo $product['id']; ?>)">Dodaj do Koszyka</button>
            </div>
    </main>
    <footer>
        <div class="noMargin">
            Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
        </div>
    </footer>
</body>

</html>
