<?php
session_start();

if (!isset($_SESSION['userID'])) {
    echo "Musisz być zalogowany, aby zobaczyć koszyk.";
    exit;
}
require './db.php';

$uzytkownik_id = $_SESSION['userID'];

$adres_result = mysqli_query($db, "SELECT * FROM adres WHERE uzytkownik_id = $uzytkownik_id LIMIT 1");
$adres_row = mysqli_fetch_assoc($adres_result);
$adres_panstwo = $adres_row['panstwo'] ?? '';
$adres_miasto = $adres_row['miasto'] ?? '';
$adres_ulica = $adres_row['ulica'] ?? '';
$adres_numer_domu = $adres_row['numer_domu'] ?? '';
$adres_numer_mieszkania = $adres_row['numer_mieszkania'] ?? '';
$adres_kod_pocztowy = $adres_row['kod_pocztowy'] ?? '';

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
            <form action="order.php" method="post" onsubmit="return confirm('Czy na pewno chcesz złożyć zamówienie?');">
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
                                <td><img src="./images/<?= $product['url_zdjecia'] ?? 'placeholder.png' ?>" alt="produkt" width="300" high="300"></td>
                                <td><?= htmlspecialchars($product['nazwa']) ?></td>
                                <td><?= number_format($price, 2, '.', '') ?> zł</td>
                                <td>
                                    <input type="hidden" name="produkt_id[]" value="<?= $id ?>">
                                    <input type="number" name="ilosc[]" value="<?= $qty ?>" min="0">
                                </td>
                                <td><?= number_format($value, 2, '.', '') ?> zł</td>
                                <td>
                                    <button type="button" onclick="removeFromCart(<?= $id ?>)">Usuń</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <h3>Dane do wysyłki</h3>
                <label>Państwo: <input type="text" name="panstwo" required value="<?= htmlspecialchars($adres_panstwo) ?>"></label><br>
                <label>Miasto: <input type="text" name="miasto" required value="<?= htmlspecialchars($adres_miasto) ?>"></label><br>
                <label>Ulica: <input type="text" name="ulica" required value="<?= htmlspecialchars($adres_ulica) ?>"></label><br>
                <label>Nr domu: <input type="text" name="numer_domu" required value="<?= htmlspecialchars($adres_numer_domu) ?>"></label><br>
                <label>Nr mieszkania: <input type="text" name="numer_mieszkania" required value="<?= htmlspecialchars($adres_numer_mieszkania) ?>"></label><br>
                <label>Kod pocztowy: <input type="text" name="kod_pocztowy" pattern="\d{2}-?\d{3}" required value="<?= htmlspecialchars($adres_kod_pocztowy) ?>"></label><br><br>
                <button type="submit">Zamów i zapłać</button>
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
