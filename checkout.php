<?php
session_start();
require './db.php';

$step = 1;
if (isset($_SESSION['userID'])) {
    $step = isset($_GET['step']) ? intval($_GET['step']) : 2;
} else {
    $step = isset($_GET['step']) ? intval($_GET['step']) : 1;
}

function redirectToStep($step) {
    header("Location: checkout.php?step=$step");
    exit();
}

function getCart() {
    $products = [];
    $quantities = [];
    if (isset($_COOKIE['koszyk']) && $_COOKIE['koszyk'] !== '') {
        $ids = explode(',', $_COOKIE['koszyk']);
        foreach ($ids as $id) {
            if (!isset($quantities[$id])) $quantities[$id] = 0;
            $quantities[$id]++;
        }
        $unique_ids = array_keys($quantities);
        if (count($unique_ids) > 0) {
            global $db;
            $id_list = implode(',', array_map('intval', $unique_ids));
            $result = mysqli_query($db, "SELECT * FROM produkt WHERE id IN ($id_list)");
            while ($row = mysqli_fetch_assoc($result)) {
                $products[] = $row;
            }
        }
    }
    return [$products, $quantities];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $login = mysqli_real_escape_string($db, $_POST['login_field']);
        $password = mysqli_real_escape_string($db, $_POST['haslo']);
        $sql_user = "SELECT * FROM uzytkownik WHERE login = '$login'";
        $result_user = mysqli_query($db, $sql_user);
        if (mysqli_num_rows($result_user) > 0) {
            $user = mysqli_fetch_assoc($result_user);
            if (password_verify($password, $user['haslo'])) {
                $_SESSION['userID'] = $user['id'];
                $_SESSION['username'] = $user['login'];
                $_SESSION['role'] = 'user';
                redirectToStep(2);
            } 
            else {
                $loginError = "Złe hasło!";
            }
        } else {
            $loginError = "Nie znaleziono użytkownika!";
        }
    } elseif (isset($_POST['guest'])) {
        $_SESSION['guest'] = true;
        redirectToStep(2);
    } elseif (isset($_POST['address'])) {

        $_SESSION['checkout_address'] = $_POST;
        redirectToStep(3);
    } elseif (isset($_POST['confirm_order'])) {

        list($products, $quantities) = getCart();
        $address = $_SESSION['checkout_address'];
        $user_id = $_SESSION['userID'] ?? null;
        $is_guest = isset($_SESSION['guest']);

        if ($is_guest) {
            $imie = mysqli_real_escape_string($db, $address['imie']);
            $nazwisko = mysqli_real_escape_string($db, $address['nazwisko']);
            $email = mysqli_real_escape_string($db, $address['email']);
            $ulica = mysqli_real_escape_string($db, $address['ulica']);
            $numer_domu = mysqli_real_escape_string($db, $address['numer_domu']);
            $numer_mieszkania = mysqli_real_escape_string($db, $address['numer_mieszkania'] ?? '');
            $miasto = mysqli_real_escape_string($db, $address['miasto']);
            $kod_pocztowy = mysqli_real_escape_string($db, $address['kod_pocztowy']);

            $sql = "INSERT INTO uzytkownik (imie, nazwisko, email, login, haslo) VALUES ('$imie', '$nazwisko', '$email', '', '')";
            mysqli_query($db, $sql);
            $user_id = mysqli_insert_id($db);

            $sql = "INSERT INTO adres (panstwo, miasto, ulica, numer_domu, numer_mieszkania, kod_pocztowy, uzytkownik_id) VALUES ('Polska', '$miasto', '$ulica', '$numer_domu', '$numer_mieszkania', '$kod_pocztowy', $user_id)";
            mysqli_query($db, $sql);
            $adres_id = mysqli_insert_id($db);
        } else {

            $adres_result = mysqli_query($db, "SELECT * FROM adres WHERE uzytkownik_id = $user_id LIMIT 1");
            $adres_row = mysqli_fetch_assoc($adres_result);
            $adres_id = $adres_row ? $adres_row['id'] : null;
            $ulica = mysqli_real_escape_string($db, $address['ulica']);
            $numer_domu = mysqli_real_escape_string($db, $address['numer_domu']);
            $numer_mieszkania = mysqli_real_escape_string($db, $address['numer_mieszkania'] ?? '');
            $miasto = mysqli_real_escape_string($db, $address['miasto']);
            $kod_pocztowy = mysqli_real_escape_string($db, $address['kod_pocztowy']);
            if (!$adres_row) {

                $sql = "INSERT INTO adres (panstwo, miasto, ulica, numer_domu, numer_mieszkania, kod_pocztowy, uzytkownik_id) VALUES ('Polska', '$miasto', '$ulica', '$numer_domu', '$numer_mieszkania', '$kod_pocztowy', $user_id)";
                mysqli_query($db, $sql);
                $adres_id = mysqli_insert_id($db);
            } else {

                if (
                    $adres_row['ulica'] !== $ulica ||
                    $adres_row['numer_domu'] !== $numer_domu ||
                    $adres_row['numer_mieszkania'] !== $numer_mieszkania ||
                    $adres_row['miasto'] !== $miasto ||
                    $adres_row['kod_pocztowy'] !== $kod_pocztowy
                ) {
                    $sql = "UPDATE adres SET ulica='$ulica', numer_domu='$numer_domu', numer_mieszkania='$numer_mieszkania', miasto='$miasto', kod_pocztowy='$kod_pocztowy' WHERE id=$adres_id";
                    mysqli_query($db, $sql);
                }
            }
        }
        $suma = 0;
        foreach ($products as $product) {
            $id = $product['id'];
            $qty = $quantities[$id] ?? 1;
            $suma += $qty * $product['cena'];
        }
        $platnosc = mysqli_real_escape_string($db, $address['platnosc'] ?? 'gotowka');
        $status = 'przyjęte';
        $data = date('Y-m-d H:i:s');
        $sql = "INSERT INTO zamowienie (uzytkownik_id, adres_id, platnosc, status, kurier_id, data_czas_zamowienia, suma, uwagi) VALUES ($user_id, $adres_id, '$platnosc', '$status', NULL, '$data', $suma, '')";
        mysqli_query($db, $sql);
        $zamowienie_id = mysqli_insert_id($db);
        foreach ($products as $product) {
            $id = $product['id'];
            $qty = $quantities[$id] ?? 1;
            $wartosc = $qty * $product['cena'];
            mysqli_query($db, "INSERT INTO szczegoly_zamowienia (produkt_id, zamowienie_id, ilosc_produktu, suma) VALUES ($id, $zamowienie_id, $qty, $wartosc)");
        }
        setcookie('koszyk', '', time() - 3600, '/');
        $_SESSION['order_confirmed'] = true;
        $_SESSION['order_id'] = $zamowienie_id;
        $_SESSION['checkout_cart'] = [ 'products' => $products, 'quantities' => $quantities ];
        redirectToStep(3);
    }
}

$addressPrefill = [
    'email' => '', 'imie' => '', 'nazwisko' => '', 'ulica' => '', 'numer_domu' => '', 'miasto' => '', 'kod_pocztowy' => '', 'telefon' => ''
];
if (isset($_SESSION['userID'])) {
    $user_id = $_SESSION['userID'];
    $user_result = mysqli_query($db, "SELECT * FROM uzytkownik WHERE id = $user_id");
    $user = mysqli_fetch_assoc($user_result);
    $adres_result = mysqli_query($db, "SELECT * FROM adres WHERE uzytkownik_id = $user_id LIMIT 1");
    $adres = mysqli_fetch_assoc($adres_result);
    $addressPrefill['email'] = $user['email'] ?? '';
    $addressPrefill['imie'] = $user['imie'] ?? '';
    $addressPrefill['nazwisko'] = $user['nazwisko'] ?? '';
    $addressPrefill['ulica'] = $adres['ulica'] ?? '';
    $addressPrefill['numer_domu'] = $adres['numer_domu'] ?? '';
    $addressPrefill['miasto'] = $adres['miasto'] ?? '';
    $addressPrefill['kod_pocztowy'] = $adres['kod_pocztowy'] ?? '';
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Sklep Ogrodniczy</title>
    <link rel="stylesheet" href="./style/styl.css">
</head>
<body>
    <header>
        <a href="index.php"><h1 class="noMargin">Sklep ogrodniczy</h1></a>
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
        <div class="checkout-step">
            <div class="checkout-header">
                <div class="step<?= $step == 1 ? ' active' : '' ?>">1<br>Logowanie</div>
                <div class="step<?= $step == 2 ? ' active' : '' ?>">2<br>Dostawa i płatność</div>
                <div class="step<?= $step == 3 ? ' active' : '' ?>">3<br>Potwierdzenie</div>
            </div>
            <?php if ($step == 1): ?>
                <h2>ZALOGUJ SIĘ LUB KONTYNUUJ JAKO GOŚĆ</h2>
                <div class="checkout-box">
                    <div>
                        <form method="post">
                            <h3>LOGOWANIE</h3>
                            <label>Login</label><br>
                            <input type="text" name="login_field" required><br>
                            <label>Hasło</label><br>
                            <input type="password" name="haslo" required><br><br>
                            <button class="checkout-btn" type="submit" name="login">ZALOGUJ SIĘ</button>
                            <?php if (!empty($loginError)) echo '<div class="authError">'.$loginError.'</div>'; ?>
                        </form>
                    </div>
                    <div>
                        <form method="post">
                            <h3>KONTYNUUJ JAKO GOŚĆ</h3>
                            <p>Możesz kontynuować zakupy jako gość.<br>W trakcie możesz również zarejestrować konto</p>
                            <button class="checkout-btn" type="submit" name="guest">KONTYNUUJ</button>
                        </form>
                    </div>
                </div>
            <?php elseif ($step == 2): ?>
                <h2>KROK 1. ADRES WYSYŁKI</h2>
                <form method="post">
                    <input type="hidden" name="address" value="1">
                    <label>Email</label><br><input type="email" name="email" required value="<?= htmlspecialchars($_SESSION['checkout_address']['email'] ?? $addressPrefill['email']) ?>"><br>
                    <label>Imię</label><br><input type="text" name="imie" required value="<?= htmlspecialchars($_SESSION['checkout_address']['imie'] ?? $addressPrefill['imie']) ?>"><br>
                    <label>Nazwisko</label><br><input type="text" name="nazwisko" required value="<?= htmlspecialchars($_SESSION['checkout_address']['nazwisko'] ?? $addressPrefill['nazwisko']) ?>"><br>
                    <label>Ulica</label><br><input type="text" name="ulica" required value="<?= htmlspecialchars($_SESSION['checkout_address']['ulica'] ?? $addressPrefill['ulica']) ?>"><br>
                    <label>Numer domu</label><br><input type="text" name="numer_domu" required value="<?= htmlspecialchars($_SESSION['checkout_address']['numer_domu'] ?? $addressPrefill['numer_domu']) ?>"><br>
                    <label>Numer mieszkania</label><br><input type="text" name="numer_mieszkania" value="<?= htmlspecialchars($_SESSION['checkout_address']['numer_mieszkania'] ?? $addressPrefill['numer_mieszkania'] ?? '') ?>"><br>
                    <label>Miasto</label><br><input type="text" name="miasto" required value="<?= htmlspecialchars($_SESSION['checkout_address']['miasto'] ?? $addressPrefill['miasto']) ?>"><br>
                    <label>Kod pocztowy</label><br><input type="text" name="kod_pocztowy" required value="<?= htmlspecialchars($_SESSION['checkout_address']['kod_pocztowy'] ?? $addressPrefill['kod_pocztowy']) ?>"><br>
                    <h2>KROK 2. METODA DOSTAWY</h2>
                    <input type="radio" name="dostawa" value="paleta" checked> 0,00 zł Paleta<br>
                    <input type="radio" name="dostawa" value="paleta_pobranie"> 0,00 zł Paleta Pobranie<br>
                    <h2>KROK 3. METODA PŁATNOŚCI</h2>
                    <input type="radio" name="platnosc" value="gotowka" checked> Płatność przy odbiorze<br>
                    <h2>KROK 4. FAKTURA</h2>
                    <input type="radio" name="faktura" value="imienna" checked> Faktura imienna
                    <input type="radio" name="faktura" value="firma"> Faktura na firmę (NIP)<br>
                    <h2>KROK 5. PODSUMOWANIE ZAMÓWIENIA</h2>
                    <?php list($products, $quantities) = getCart(); if (empty($products)): ?>
                        <p>Koszyk jest pusty.</p>
                    <?php else: ?>
                        <table class="summary-table">
                            <thead><tr><th>Produkt</th><th>Ilość</th><th>Cena</th><th>Wartość</th></tr></thead>
                            <tbody>
                            <?php $sum = 0; foreach ($products as $product): $id = $product['id']; $qty = $quantities[$id] ?? 1; $value = $qty * $product['cena']; $sum += $value; ?>
                                <tr>
                                    <td><?= htmlspecialchars($product['nazwa']) ?></td>
                                    <td><?= $qty ?></td>
                                    <td><?= number_format($product['cena'], 2, ',', ' ') ?> zł</td>
                                    <td><?= number_format($value, 2, ',', ' ') ?> zł</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                            <tfoot><tr><td colspan="3" class="summary-total">Łącznie</td><td class="summary-total"><?= number_format($sum, 2, ',', ' ') ?> zł</td></tr></tfoot>
                        </table>
                    <?php endif; ?>
                    <button class="checkout-btn" type="submit">PRZEJDŹ DO POTWIERDZENIA</button>
                </form>
            <?php elseif ($step == 3): ?>
                <h2>POTWIERDZENIE ZAMÓWIENIA</h2>
                <?php if (!empty($_SESSION['order_confirmed'])): ?>
                    <p>Dziękujemy za złożenie zamówienia!</p>
                    <h3>Podsumowanie zamówienia</h3>
                    <?php 
                    $products = $_SESSION['checkout_cart']['products'] ?? [];
                    $quantities = $_SESSION['checkout_cart']['quantities'] ?? [];
                    ?>
                    <?php if (empty($products)): ?>
                        <p>Koszyk był pusty.</p>
                    <?php else: ?>
                    <table class="summary-table">
                        <thead><tr><th>Produkt</th><th>Ilość</th><th>Cena</th><th>Wartość</th></tr></thead>
                        <tbody>
                        <?php $sum = 0; foreach ($products as $product): $id = $product['id']; $qty = $quantities[$id] ?? 1; $value = $qty * $product['cena']; $sum += $value; ?>
                            <tr>
                                <td><?= htmlspecialchars($product['nazwa']) ?></td>
                                <td><?= $qty ?></td>
                                <td><?= number_format($product['cena'], 2, ',', ' ') ?> zł</td>
                                <td><?= number_format($value, 2, ',', ' ') ?> zł</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot><tr><td colspan="3" class="summary-total">Łącznie</td><td class="summary-total"><?= number_format($sum, 2, ',', ' ') ?> zł</td></tr></tfoot>
                    </table>
                    <?php endif; ?>
                    <br><a href="index.php" class="checkout-btn">Powrót do sklepu</a>
                    <?php unset($_SESSION['order_confirmed'], $_SESSION['order_id'], $_SESSION['checkout_address'], $_SESSION['guest'], $_SESSION['checkout_cart']); ?>
                <?php else: ?>
                    <form method="post">
                        <input type="hidden" name="confirm_order" value="1">
                        <button class="checkout-btn" type="submit">ZŁÓŻ ZAMÓWIENIE</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
