<?php
session_start();
require 'db.php';

if (!isset($_SESSION['userID'])) {
    echo "Musisz być zalogowany, aby złożyć zamówienie.";
    exit;
}

$uzytkownik_id = $_SESSION['userID'];

$adres_result = mysqli_query($db, "SELECT id FROM adres WHERE uzytkownik_id = $uzytkownik_id LIMIT 1");
$adres_row = mysqli_fetch_assoc($adres_result);
if (!$adres_row) {
    echo "Nie masz przypisanego adresu. Uzupełnij dane w swoim profilu.";
    echo "<p><a href='user/account.php'>Przejdź do profilu</a></p>";
    exit;
}

$adres_id = $adres_row['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['produkt_id'], $_POST['ilosc'])) {
    $produkt_ids = $_POST['produkt_id'];
    $ilosci = $_POST['ilosc'];

    $suma = 0;
    $produkty = [];

    for ($i = 0; $i < count($produkt_ids); $i++) {
        $id = $produkt_ids[$i];
        $ilosc = $ilosci[$i];

        if ($ilosc > 0) {
            $result = mysqli_query($db, "SELECT * FROM produkt WHERE id = $id");
            $product = mysqli_fetch_assoc($result);

            if ($product) {
                $cena = $product['cena'];
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
    $status = 'przyjęnte';
    $uwagi = 'Brak uwag';
    $kurier_id = 1;

    $sql = "INSERT INTO zamowienie (uzytkownik_id, adres_id, platnosc, status, kurier_id, data_czas_zamowienia, suma, uwagi)
            VALUES ($uzytkownik_id, $adres_id, '$platnosc', '$status', $kurier_id, '$data', $suma, '$uwagi')";
    mysqli_query($db, $sql);
    $zamowienie_id = mysqli_insert_id($db);

    foreach ($produkty as $p) {
        mysqli_query($db, "INSERT INTO szczegoly_zamowienia (produkt_id, zamowienie_id, ilosc_produktu, suma)
                           VALUES ({$p['id']}, $zamowienie_id, {$p['ilosc']}, {$p['suma']})");
    }

    setcookie('koszyk', '', time() - 3600, '/');

    echo "<h2>Zamówienie złożone!</h2>";
    echo "<p>Łączna kwota: <b>" . number_format($suma, 2, ',', ' ') . " zł</b></p>";
    echo "<p><a href='index.php'>Powrót do sklepu</a></p>";
} else {
    echo "Nieprawidłowe dane formularza.";
}
