<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require '../db.php';
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $id = $_GET["id"];
    $get_sql = "SELECT * from kategoria where id = '$id'";
    $rows = mysqli_query($db, $get_sql);
    $row = mysqli_fetch_assoc($rows);
    $nazwa = $row['nazwa'];
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nazwa = ($_POST['name']);
    $id = ($_POST['id']);
    $checkCategory_sql = "SELECT count(*) as liczba from kategoria where nazwa = '$nazwa' and id <> '$id'";
    $rows = mysqli_query($db, $checkCategory_sql);
    $row = mysqli_fetch_assoc($rows);
    if ((isset($row)) && (isset($row['liczba'])) && ($row['liczba'] > 0)) {
        echo "<p> Kategoria już istnieje!</p>";
    } else {
        $update_sql = "UPDATE kategoria SET nazwa ='$nazwa' where id = '$id'";
        mysqli_query($db, $update_sql);
        echo "<p> Zmieniono kategorię!</p>";
    }
} else {
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj kategorię</title>
    <link rel="stylesheet" href="../style/adminstyl.css">
</head>

<body>
    <h1>Modyfikuj kategorię</h1>
    <form action="modifyCategories.php" method="POST">
        <label for="name">Nazwa kategorii:</label>
        <input type="text" id="name" name="name" value="<?php echo $nazwa ?>" required>
        <input type="hidden" id="id" name="id" value="<?php echo $id ?>">

        <button type="submit">Modyfikuj kategorię</button>
    </form>
</body>

</html>
