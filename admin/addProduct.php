<?php
session_start();


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require '../db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $imagePath = $_POST['image'];
    $insert_sql = "INSERT INTO produkt (nazwa, opis, cena, kategoria_id, url_zdjecia) VALUES ('$name', '$description', '$price', '$category', '$imagePath')";
    if (mysqli_query($db, $insert_sql)) {
        echo "Produkt dodany pomyślnie.";
    } else {
        echo "Błąd: " . mysqli_error($db);
    }
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj produkt</title>
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
            <form action="addProduct.php" method="POST" class="adminForm">
                <h1 class="noMargin">Dodaj produkt</h1>
                <label for="name">Nazwa produktu:</label>
                <input type="text" id="name" name="name" required>

                <label for="description">Opis:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="price">Cena:</label>
                <input type="number" id="price" name="price" step="0.01" required>

                <label for="category">Kategoria:</label>
                <select id="category" name="category" required>
                    <option value="">Wybierz kategorię</option>
                    <?php
                    $sql = "SELECT * FROM kategoria ORDER BY nazwa";
                    $result = mysqli_query($db, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nazwa'] . "</option>";
                    }
                    ?>
                </select>

                <label for="image">Zdjęcie:</label>
                <select id="image" name="image" required>
                    <option value="">Brak zdjęcia</option>
                    <?php
                    $images = glob('./images/*.jpg');
                    foreach ($images as $image) {
                        $filename = basename($image);
                        echo "<option value='$filename'>$filename</option>";
                    }
                    ?>
                </select>

                </list>

                <button type="submit">Dodaj produkt</button>
            </form>
        </div>
    </main>
    <footer>
        Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
    </footer>
    
</body>

</html>
