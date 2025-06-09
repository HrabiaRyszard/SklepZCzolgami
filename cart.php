<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userID'])) {
    echo "Musisz być zalogowany, aby złożyć zamówienie.";
    exit;
}

$uzytkownik_id = $_SESSION['userID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produkt_id'], $_POST['ilosc'])) {
    $produkt_ids = $_POST['produkt_id'];
    $ilosci = $_POST['ilosc'];

    $panstwo = mysqli_real_escape_string($db, $_POST['panstwo']);
    $miasto = mysqli_real_escape_string($db, $_POST['miasto']);
    $ulica = mysqli_real_escape_string($db, $_POST['ulica']);
    $numer_domu = mysqli_real_escape_string($db, $_POST['numer_domu']);
    $numer_mieszkania = mysqli_real_escape_string($db, $_POST['numer_mieszkania']);
    $kod_pocztowy = (int)$_POST['kod_pocztowy'];

    mysqli_query($db, "INSERT INTO adres (panstwo, miasto, ulica, numer_domu, numer_mieszkania, kod_pocztowy, uzytkownik_id)
                       VALUES ('$panstwo', '$miasto', '$ulica', '$numer_domu', '$numer_mieszkania', $kod_pocztowy, $uzytkownik_id)");
    $adres_id = mysqli_insert_id($db);

    $suma = 0;
    $produkty = [];

    for ($i = 0; $i < count($produkt_ids); $i++) {
        $id = $produkt_ids[$i];
        $ilosc = $ilosci[$i];

        if ($ilosc > 0) {
            $res = mysqli_query($db, "SELECT * FROM produkt WHERE id = $id");
            $prod = mysqli_fetch_assoc($res);

            if ($prod) {
                $cena = $prod['cena'];
                $wartosc = $cena * $ilosc;
                $suma += $wartosc;

                $produkty[] = [
                    'id' => $id,
                    'ilosc' => $ilosc,
                    'suma' => $wartosc
                ];
            }
        }
    }

    if (empty($produkty)) {
        echo "Brak produktów w zamówieniu.";
        exit;
    }

    $data = date("Y-m-d H:i:s");
    $platnosc = 'gotówka';
    $status = 'przyjęte';
    $uwagi = 'Brak uwag';
    $kurier_id = 1;

    mysqli_query($db, "INSERT INTO zamowienie (uzytkownik_id, adres_id, platnosc, status, kurier_id, data_czas_zamowienia, suma, uwagi)
                       VALUES ($uzytkownik_id, $adres_id, '$platnosc', '$status', $kurier_id, '$data', $suma, '$uwagi')");
    $zamowienie_id = mysqli_insert_id($db);

    foreach ($produkty as $p) {
        mysqli_query($db, "INSERT INTO szczegoly_zamowienia (produkt_id, zamowienie_id, ilosc_produktu, suma)
                           VALUES ({$p['id']}, $zamowienie_id, {$p['ilosc']}, {$p['suma']})");
    }

    setcookie('koszyk', '', time() - 3600, '/');

    echo "<h2>Zamówienie złożone!</h2>";
    echo "<p>Kwota do zapłaty: <b>" . number_format($suma, 2, ',', ' ') . " zł</b></p>";
    echo "<p><a href='index.php'>Powrót do sklepu</a></p>";
} else {
    echo "Nieprawidłowe dane formularza.";
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
        <div class="hOptions">
            <a href="products.php">Sklep</a>
            <a href="cart.php" id="userCart">🛒 Koszyk</a>
        </div>
        <div class="buttonContainer">
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
                                <td><img src="./images/<?= $product['zdjecie'] ?>" alt="produkt"></td>
                                <td><?= htmlspecialchars($product['nazwa']) ?></td>
                                <td><?= number_format($price, 2, '.', '') ?> zł</td>
                                <td>
                                    <input type="hidden" name="produkt_id[]" value="<?= $id ?>">
                                    <input type="number" name="ilosc[]" value="<?= $qty ?>" min="0">
                                </td>
                                <td><?= number_format($value, 2, '.', '') ?> zł</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <br>
                <button type="submit">Zamów i zapłać</button>
                <h3>Dane do wysyłki</h3>
                <label>Państwo: <input type="text" name="panstwo" required></label><br>
                <label>Miasto: <input type="text" name="miasto" required></label><br>
                <label>Ulica: <input type="text" name="ulica" required></label><br>
                <label>Nr domu: <input type="text" name="numer_domu" required></label><br>
                <label>Nr mieszkania: <input type="text" name="numer_mieszkania" required></label><br>
                <label>Kod pocztowy: <input type="text" name="kod_pocztowy" pattern="\d{2}-?\d{3}" required></label><br><br>

            </form>
        <?php endif; ?>
    </main>

    <footer>
        <div class="noMargin">
            Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
        </div>
    </footer>
</body>

</html>
