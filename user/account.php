<?php
session_start();
if (!isset($_SESSION['role'])  || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}
require '../db.php';

$userID = $_SESSION['userID'];
$sql = "SELECT * FROM uzytkownik WHERE id = $userID";
$result = mysqli_query($db, $sql);
if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    echo "Nie znaleziono użytkownika.";
    exit();
}

$trackError = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'track') {
    $order_number = mysqli_real_escape_string($db, $_POST['order_number']);
    $track_email = $user['email'];
    $sql = "SELECT status FROM zamowienie z LEFT JOIN uzytkownik u ON z.uzytkownik_id = u.id WHERE z.id = '$order_number' AND u.email = '$track_email'";
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $trackError = "Status zamówienia: " . htmlspecialchars($row['status']);
    } else {
        $trackError = "Nie znaleziono zamówienia.";
    }
}


$currentOrders = [];
$sqlCurrent = "SELECT * FROM zamowienie WHERE uzytkownik_id = $userID AND status != 'dostarczone' ORDER BY data_czas_zamowienia DESC";
$resCurrent = mysqli_query($db, $sqlCurrent);
while ($order = mysqli_fetch_assoc($resCurrent)) {
    $orderID = $order['id'];
    $sqlDetails = "SELECT sz.*, p.nazwa FROM szczegoly_zamowienia sz JOIN produkt p ON sz.produkt_id = p.id WHERE sz.zamowienie_id = $orderID";
    $resDetails = mysqli_query($db, $sqlDetails);
    $details = [];
    while ($det = mysqli_fetch_assoc($resDetails)) {
        $details[] = $det;
    }
    $order['details'] = $details;
    $currentOrders[] = $order;
}

$orderHistory = [];
$sqlOrders = "SELECT * FROM zamowienie WHERE uzytkownik_id = $userID AND status = 'dostarczone' ORDER BY data_czas_zamowienia DESC";
$resOrders = mysqli_query($db, $sqlOrders);
while ($order = mysqli_fetch_assoc($resOrders)) {

    $orderID = $order['id'];
    $sqlDetails = "SELECT sz.*, p.nazwa FROM szczegoly_zamowienia sz JOIN produkt p ON sz.produkt_id = p.id WHERE sz.zamowienie_id = $orderID";
    $resDetails = mysqli_query($db, $sqlDetails);
    $details = [];
    while ($det = mysqli_fetch_assoc($resDetails)) {
        $details[] = $det;
    }
    $order['details'] = $details;
    $orderHistory[] = $order;
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konto użytkownika - Sklep Ogrodniczy</title>
    <link rel="stylesheet" href="../style/styl.css">
    <link rel="stylesheet" href="../style/account.css">
</head>
<body>
    <header>
        <a href="../index.php"><h1 class="noMargin">Sklep ogrodniczy</h1></a>
        <div class="hOptions">
            <a href="../products.php">Sklep</a>
            <a href="../cart.php">Koszyk</a>
        </div>
        <div class="buttonContainer">
            <a href="../login.php">
                <button class="iconButton">
                    <img src="../icons/account.svg" alt="Konto" style="width:48px; height:48px; vertical-align:middle;">
                </button>
            </a>
        </div>
    </header>
    <main>
        <h1 class="accountTitle">Moje konto</h1>
        <div class="accountPanel">
            <div class="accountSection">
                <h2>Dane użytkownika</h2>
                <form method="post" action="updateUser.php" class="accountForm">
                    <input type="hidden" name="action" value="update_info">
                    <label>Imię</label>
                    <input type="text" name="imie" value="<?= htmlspecialchars($user['imie']) ?>" required>
                    <label>Nazwisko</label>
                    <input type="text" name="nazwisko" value="<?= htmlspecialchars($user['nazwisko']) ?>" required>
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    <label>Login</label>
                    <input type="text" name="login" value="<?= htmlspecialchars($user['login']) ?>" required>
                    <button type="submit" class="accountButton">Zapisz zmiany</button>
                </form>
                <form method="post" action="updateUser.php" class="accountForm">
                    <input type="hidden" name="action" value="change_password">
                    <label>Nowe hasło</label>
                    <input type="password" name="new_password" required>
                    <button type="submit" class="accountButton">Zmień hasło</button>
                </form>
                <div class="accountActions">
                    <a href="logout.php" class="logoutButton">Wyloguj się</a>
                    <a href="delUser.php" class="deleteButton" onclick="return confirm('Czy na pewno chcesz usunąć konto?');">Usuń konto</a>
                </div>
            </div>
            <div class="accountSection">
                <h2>Aktualne zamówienia</h2>
                <?php if (count($currentOrders) === 0): ?>
                    <div>Brak aktualnych zamówień.</div>
                <?php else: ?>
                    <div class="orderHistory">
                        <?php foreach ($currentOrders as $order): ?>
                            <div class="orderBox">
                                <div><b>Numer zamówienia:</b> <?= $order['id'] ?></div>
                                <div><b>Data zamówienia:</b> <?= $order['data_czas_zamowienia'] ?></div>
                                <div><b>Suma:</b> <?= $order['suma'] ?> zł</div>
                                <div><b>Status:</b> <?= htmlspecialchars($order['status']) ?></div>
                                <div><b>Produkty:</b>
                                    <ul>
                                        <?php foreach ($order['details'] as $det): ?>
                                            <li><?= htmlspecialchars($det['nazwa']) ?> (<?= $det['ilosc_produktu'] ?> szt.) - <?= $det['suma'] ?> zł</li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="accountSection">
                <h2>Historia zamówień</h2>
                <?php if (count($orderHistory) === 0): ?>
                    <div>Brak zrealizowanych zamówień.</div>
                <?php else: ?>
                    <div class="orderHistory">
                        <?php foreach ($orderHistory as $order): ?>
                            <div class="orderBox">
                                <div><b>Numer zamówienia:</b> <?= $order['id'] ?></div>
                                <div><b>Data zamówienia:</b> <?= $order['data_czas_zamowienia'] ?></div>
                                <div><b>Suma:</b> <?= $order['suma'] ?> zł</div>
                                <div><b>Status:</b> <?= htmlspecialchars($order['status']) ?></div>
                                <div><b>Produkty:</b>
                                    <ul>
                                        <?php foreach ($order['details'] as $det): ?>
                                            <li><?= htmlspecialchars($det['nazwa']) ?> (<?= $det['ilosc_produktu'] ?> szt.) - <?= $det['suma'] ?> zł</li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <footer>
        <div class="noMargin">
            Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
        </div>
    </footer>
</body>
</html>
