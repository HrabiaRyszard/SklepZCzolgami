<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require '../db.php';
if (!isset($_GET['id'])) {
    echo "Nieprawidłowe ID produktu.";
    exit();
}

$produkt_id = $_GET['id'];

$select_sql = "SELECT * FROM produkt WHERE id = '$produkt_id'";
$result = mysqli_query($db, $select_sql);
if (!$result || mysqli_num_rows($result) === 0) {
    echo "Produkt nie znaleziony.";
    exit();
}
$produkt = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazwa = $_POST['nazwa'];
    $opis = $_POST['opis'];
    $cena = $_POST['cena'];
    $kategoria_id = $_POST['kategoria_id'];
    $zdjecie = $_POST['image'];

    $update_sql = "UPDATE produkt SET nazwa='$nazwa', cena='$cena', opis='$opis', kategoria_id='$kategoria_id', url_zdjecia='$zdjecie' WHERE id='$produkt_id'";
    if (!mysqli_query($db, $update_sql)) {
        echo "<p class='error'>Błąd zapisywania</p>";
    } else {
        header("Location: adminPanel.php");
        exit();
    }
}
$kategorie_sql = "SELECT * FROM kategoria";
$kategorie = mysqli_query($db, $kategorie_sql);
$images = glob('../images/*.jpg');
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sklep Ogrodniczy</title>
    <link rel="stylesheet" href="./style/adminstyl.css">
</head>

<body>
    <header>
        <a href="index.php">
            <h1 class="noMargin">Sklep ogrodniczy</h1>
        </a>
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
        <div>
            <h1>Modyfikuj produkt</h1>

            <form method="post">
                <label>Nazwa:<br>
                    <input type="text" name="nazwa" value="<?php echo $produkt['nazwa']; ?>" required>
                </label><br><br>

                <label>Opis:<br>
                    <textarea name="opis" required><?php echo $produkt['opis']; ?></textarea>
                </label><br><br>

                <label>Cena:<br>
                    <input type="number" name="cena" step="0.01" value="<?php echo $produkt['cena']; ?>" required>
                </label><br><br>

                <label>Kategoria:<br>
                    <select name="kategoria_id">
                        <?php while ($kategoria = mysqli_fetch_assoc($kategorie)) { ?>
                            <option value="<?php echo $kategoria['id']; ?>"
                                <?php if ($kategoria['id'] == $produkt['kategoria_id']) echo 'selected'; ?>>
                                <?php echo $kategoria['nazwa']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </label><br><br>
                <label>Zdjęcie:<br />
                    <select id="image" name="image" required>
                        <option value="">Brak zdjęcia</option>
                        <?php
                        foreach ($images as $img) {
                            $filename = basename($img);
                            $selected = ($filename == $produkt['url_zdjecia ']) ? 'selected' : '';
                            echo "<option value='" . $filename . "' $selected>$filename</option>";
                        }
                        ?>
                    </select>
                </label><br /><br />
                <button type="submit">Zapisz zmiany</button>
                <a href="adminPanel.php"><button type="button">Anuluj</button></a>
            </form>

        </div>
    </main>
    <footer>
        <div class="noMargin">
            Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
        </div>
    </footer>
</body>

</html>
