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
        <div class="center">
            <form action="modifyCategories.php" method="POST" class="adminForm">
                <h1 class="noMargin">Modyfikuj kategorię</h1>
                <label for="name">Nazwa kategorii:</label>
                <input type="text" id="name" name="name" value="<?php echo $nazwa ?>" required>
                <input type="hidden" id="id" name="id" value="<?php echo $id ?>">

                <button type="submit">Modyfikuj kategorię</button>
            </form>
        </div>
    </main>
    <footer>
        Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
    </footer>
</body>

</html>
