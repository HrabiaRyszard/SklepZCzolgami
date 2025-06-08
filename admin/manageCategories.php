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
    <title>Zarządzaj kategoriami</title>
    <link rel="stylesheet" href="vie./style/adminstyl.css">
</head>

<body>
    <h1>Zarządzaj kategoriami</h1>
    <table>
        <tr>
            <th>Nazwa kategorii</th>
            <th>Akcje</th>
        </tr>
        <?php
        $sql = "SELECT * FROM kategoria order by nazwa";

        $result = mysqli_query($db, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['nazwa'] . "</td>";
            echo "<td><a href='modifyCategories.php?id=" . $row['id'] . "'><button class='modifyBtn'>Modyfikuj</button></a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>