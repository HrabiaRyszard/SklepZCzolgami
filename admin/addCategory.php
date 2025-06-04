<?php
session_start();
require '../db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = ($_POST['name']);
    $insert_sql = "INSERT INTO kategoria (nazwa) VALUES ('$name')";
    mysqli_query($db, $insert_sql);
}
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj kategorię</title>
    <link rel="stylesheet" href="../style/styl.css">
</head>

<body>
    <h1>Dodaj kategorię</h1>
    <form action="addCategory.php" method="POST">
        <label for="name">Nazwa kategorii:</label>
        <input type="text" id="name" name="name" required>

        <button type="submit">Dodaj kategorię</button>
    </form>
</body>

</html>
