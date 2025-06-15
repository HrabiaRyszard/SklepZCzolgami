<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require '../db.php';
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzaj produktami</title>
    <link rel="stylesheet" href="../style/adminstyl.css">
</head>

<body>
    <header>
        <a href="../index.php">
            <h1 class="noMargin">Sklep ogrodniczy</h1>
        </a>
        <div class="buttonContainer">
            <a href="../admin/adminPanel.php">
                <button class="iconButton">
                    <img src="../icons/close.svg" alt="Index" style="width:48px; height:48px; vertical-align:middle;">
                </button>
            </a>
        </div>
    </header>
    <main>
        <h1 class="noMargin">Zarządzaj produktami</h1>
        <table>
            <tr>
                <th>Zdjęcie</th>
                <th>Nazwa produktu</th>
                <th>Opis</th>
                <th>Cena</th>
                <th>Kategoria</th>
                <th>Akcje</th>
            </tr>
            <?php
            $sql = "SELECT produkt.*, kategoria.nazwa AS kategoria_nazwa 
            FROM produkt 
            LEFT JOIN kategoria ON produkt.kategoria_id = kategoria.id";

            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td><img class='productImage' src='../images/" . $row['url_zdjecia'] . "' alt='zdjecie' class='imgProduct'></td>";
                echo "<td>" . $row['nazwa'] . "</td>";
                echo "<td>" . $row['opis'] . "</td>";
                echo "<td>" . $row['cena'] . "</td>";
                echo "<td>" . $row['kategoria_nazwa'] . "</td>";
                echo "<td><a href='modifyProduct.php?id=" . $row['id'] . "'><button class='modifyBtn'>Modyfikuj</button></a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </main>
    <footer>
        Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
    </footer>
</body>

</html>
