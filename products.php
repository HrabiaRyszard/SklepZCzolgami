<?php
require 'db.php';

$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM produkt LIMIT $limit OFFSET $offset";
$result = mysqli_query($db, $sql);

$total_result = mysqli_query($db, "SELECT COUNT(*) as total FROM produkt");
$total_row = mysqli_fetch_assoc($total_result);
$total_pages = ceil($total_row['total'] / $limit);
?>

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
        <a href="index.php"><h1 class="noMargin">Sklep ogrodniczy</h1></a>
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
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">&laquo;</a>
            <?php endif; ?>
            <span>Strona <?php echo $page; ?> z <?php echo $total_pages; ?></span>
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">&raquo;</a>
            <?php endif; ?>
    </main>
    <footer>
        <div class="noMargin">
            Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
        </div>
    </footer>
</body>
</html>
