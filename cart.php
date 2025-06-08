<?php
require 'db.php';

$products = [];
$quantities = [];

if (isset($_COOKIE['koszyk']) && !empty($_COOKIE['koszyk'])) {
    $ids = explode(',', $_COOKIE['koszyk']);

    foreach ($ids as $id) {
        $id = (int)$id;
        if ($id > 0) {
            if (!isset($quantities[$id])) {
                $quantities[$id] = 1;
            } else {
                $quantities[$id]++;
            }
        }
    }

    if (!empty($quantities)) {
        $uniqueIds = implode(',', array_keys($quantities));
        $sql = "SELECT * FROM produkt WHERE id IN ($uniqueIds)";
        $result = mysqli_query($db, $sql);
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Koszyk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/styl.css">
    <script src="script.js"></script>
    <style>
        input[type="number"] {
            width: 50px;
            text-align: center;
        }
    </style>
</head>

<body>
    <header>
        <a href="index.php">
            <h1 class="noMargin">Sklep ogrodniczy</h1>
        </a>
        </div>
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
            <a href="login.php">
                <button class="iconButton">
                    <img src="./icons/account.svg" alt="Konto" style="width:48px; height:48px; vertical-align:middle;">
                </button>
            </a>
        </div>
    </header>

    <main>
        <h2>Twój koszyk</h2>

        <?php if (empty($products)): ?>
            <p>Koszyk jest pusty.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Zdjęcie</th>
                        <th>Nazwa produktu</th>
                        <th>Cena</th>
                        <th>Ilość</th>
                        <th>Wartość</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <?php
                        $id = $product['id'];
                        $qty = $quantities[$id] ?? 1;
                        $price = $product['cena'];
                        $value = $qty * $price;
                        ?>
                        <tr>
                            <td> <img src="./images/<?php echo $product['zdjecie']; ?>" alt="produkt" max-width="200px" max-height="200px">
                            </td>
                            <td><?= htmlspecialchars($product['nazwa']) ?></td>
                            <td id="price<?= $id ?>"><?= number_format($price, 2, '.', '') ?></td>
                            <td>
                                <input type="number" id="count<?= $id ?>" value="<?= $qty ?>" min="0" onchange="changeValue(<?= $id ?>)">
                            </td>
                            <td id="value<?= $id ?>"><?= number_format($value, 2, '.', '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p>Suma: <span id="cartSum">0.00</span> PLN</p>
        <?php endif; ?>
    </main>

    <footer>
        <div class="noMargin">
            Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
        </div>
    </footer>

    <script>
        window.onload = function() {
            updateCart();
            setCartSum();
        };
    </script>
</body>

</html>
