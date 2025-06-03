<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzaj produktami</title>
    <link rel="stylesheet" href="./style/styl.css">
</head>

<body>
    <h1>Zarządzaj produktami</h1>
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
            echo "<td><img class='productImage' src='images/" . $row['url_zdjecia'] . "' alt='zdjecie' class='imgProduct'></td>";
            echo "<td>" . $row['nazwa'] . "</td>";
            echo "<td>" . $row['opis'] . "</td>";
            echo "<td>" . $row['cena'] . "</td>";
            echo "<td>" . $row['kategoria_nazwa'] . "</td>";
            echo "<td><a href='modifyProduct.php?id=" . $row['id'] . "'><button class='modifyBtn'>Modyfikuj</button></a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>
