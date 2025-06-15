<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require '../db.php';

$sql = "SELECT 
  z.id AS zamowienie_id,
  z.data_czas_zamowienia,
  z.data_czas_realizacji,
  z.status,
  z.suma,
  z.platnosc,
  z.uwagi,
  z.uzytkownik_id,
  z.adres_id,
  z.kurier_id,
  u.imie AS uzytkownik_imie,
  u.nazwisko AS uzytkownik_nazwisko,
  a.miasto,
  a.ulica,
  a.numer_domu,
  p.imie AS kurier_imie,
  p.nazwisko AS kurier_nazwisko
FROM zamowienie z
LEFT JOIN uzytkownik u ON z.uzytkownik_id = u.id
LEFT JOIN adres a ON z.adres_id = a.id
LEFT JOIN pracownik p ON z.kurier_id = p.id";

$result = mysqli_query($db, $sql);
if (!$result) {
    echo "Błąd zapytania: " . mysqli_error($db);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep Ogrodniczy</title>
    <link rel="stylesheet" href="../style/adminstyl.css">
</head>

<body>
    <header>
        <a href="../index.php">
            <h1 class="noMargin">Sklep ogrodniczy</h1>
        </a>
        <div class="buttonContainer">
            <a href="../index.php">
                <button class="iconButton">
                    <img src="../icons/close.svg" alt="Index" style="width:48px; height:48px; vertical-align:middle;">
                </button>
            </a>
            <a href="../user/logout.php" >
                <button class="iconButton">
                    <img src="../icons/logout.svg" alt="Konto" style="width:48px; height:48px; vertical-align:middle;">
                </button>
            </a>
        </div>
    </header>
    <main>
        <div>
            <h1>Lista zamówień</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID zamówienia</th>
                        <th>Użytkownik</th>
                        <th>Adres</th>
                        <th>Płatność</th>
                        <th>Status</th>
                        <th>Kurier</th>
                        <th>Data zamówienia</th>
                        <th>Data realizacji</th>
                        <th>Suma</th>
                        <th>Uwagi</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?= $row['zamowienie_id'] ?></td>
                            <td><?= $row['uzytkownik_imie'] . ' ' . $row['uzytkownik_nazwisko'] ?></td>
                            <td><?= $row['miasto'] . ', ' . $row['ulica'] . ' ' . $row['numer_domu'] ?></td>
                            <td><?= $row['platnosc'] ?></td>
                            <td><?= $row['status'] ?: '-' ?></td>
                            <td><?= $row['kurier_imie'] . ' ' . $row['kurier_nazwisko'] ?></td>
                            <td><?= $row['data_czas_zamowienia'] ?></td>
                            <td><?= $row['data_czas_realizacji'] ?: '-' ?></td>
                            <td><?= $row['suma'] ?> zł</td>
                            <td><?= $row['uwagi'] ?: '-' ?></td>
                            <td>
                                <form method="get" action="orderDetails.php">
                                    <input type="hidden" name="id" value="<?= $row['zamowienie_id'] ?>" />
                                    <button type="submit" class="modifyBtn">Podgląd zamówienia</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer>
        Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
    </footer>
    
</body>

</html>
