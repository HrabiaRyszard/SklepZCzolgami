<?php
require 'db.php';

$sql = "SELECT * FROM produkt";
$result = mysqli_query($db, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep Ogrodniczy</title>
    <link rel="stylesheet" href="./style/styl.css">
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
        <div class="productPanel">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <a href="details.php?id=<?php echo $row['id']; ?>" class="productLink">
                <div class="productContainer">
                    <img src="./images/<?php echo $row['url_zdjecia'] ? $row['url_zdjecia'] : 'placeholder.png'; ?>" alt="produkt" max-width="200px" max-height="200px">
                    <h2><?php echo $row['nazwa']; ?></h2>
                    <h3>Cena: <b><?php echo $row['cena']; ?> zł</b></h3>
                </div>
            </a>
        <?php endwhile; ?>
            
        </div>
    </main>
    <footer>
        <div class="noMargin">
            Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
        </div>
    </footer>
</body>
</html>
