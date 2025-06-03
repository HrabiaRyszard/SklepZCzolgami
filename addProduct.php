<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj produkt</title>
    <link rel="stylesheet" href="../style/styl.css">
</head>

<body>
    <h1>Dodaj produkt</h1>
    <form action="addProduct.php" method="POST">
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
            $sql = "SELECT * FROM kategoria";
            $result = mysqli_query($db, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['id'] . "'>" . $row['nazwa'] . "</option>";
            }
            ?>
        </select>

        <label for="image">Zdjęcie:</label>
        <input type="file" id="image" name="image" accept="image/*" required>

        <button type="submit" value="Dodaj produkt">
    </form>
</body>

</html>
