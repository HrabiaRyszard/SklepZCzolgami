<?php
session_start();
require './db.php';

$products = [];
$quantities = [];
if (isset($_COOKIE['koszyk']) && $_COOKIE['koszyk'] !== '') {
    $ids = explode(',', $_COOKIE['koszyk']);
    foreach ($ids as $id) {
        if (!isset($quantities[$id])) {
            $quantities[$id] = 0;
        }
        $quantities[$id]++;
    }
    $unique_ids = array_keys($quantities);
    if (count($unique_ids) > 0) {
        $id_list = implode(',', array_map('intval', $unique_ids));
        $result = mysqli_query($db, "SELECT * FROM produkt WHERE id IN ($id_list)");
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
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
            <form>
                <table>
                    <thead>
                        <tr>
                            <th>Zdjęcie</th>
                            <th>Nazwa produktu</th>
                            <th>Cena</th>
                            <th>Ilość</th>
                            <th>Wartość</th>
                            <th>Akcja</th>
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
                                <td><img src="./images/<?= $product['url_zdjecia'] ?? 'placeholder.png' ?>" alt="produkt" width="100" height="100"></td>
                                <td><?= htmlspecialchars($product['nazwa']) ?></td>
                                <td><?= number_format($price, 2, '.', '') ?> zł</td>
                                <td><?= $qty ?></td>
                                <td><?= number_format($value, 2, '.', '') ?> zł</td>
                                <td>
                                    <button type="button" onclick="removeFromCart(<?= $id ?>)">Usuń</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <br>
                <a href="checkout.php" class="checkout-btn" style="display:inline-block;padding:12px 32px;background:#336633;color:#fff;border-radius:4px;text-decoration:none;font-size:18px;">PRZEJDŹ DO KASY</a>
            </form>
        <?php endif; ?>
    </main>

    <footer>
        <div class="noMargin">
            Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
        </div>
    </footer>

    <script>
    function removeFromCart(id) {
        var cookie = document.cookie.split('; ').find(row => row.startsWith('koszyk='));
        if (!cookie) return;
        var value = decodeURIComponent(cookie.split('=')[1]);
        var ids = value.split(',');
        ids = ids.filter(function(item) { return item != id; });
        var expires = new Date();
        expires.setFullYear(expires.getFullYear() + 1);
        document.cookie = 'koszyk=' + ids.join(',') + '; expires=' + expires.toUTCString() + '; path=/';
        location.reload();
    }
    </script>
</body>

</html>
