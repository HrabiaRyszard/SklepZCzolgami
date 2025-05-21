<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $sql = "INSERT INTO konta (username, password) VALUES ('$username', '$hashed_password')";

mysqli_query($db, $sql);

mysqli_close($db);
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="StylLogin.css">
    <title>Sklep z czołgami</title>
</head>
    <body>
        <div class="container">
            <h2>Zarejestruj Się</h2>
            <form action="#" method="POST">
                <input type="text" name="username" placeholder="Imię" required>
                <input type="password" name="password" placeholder="hasło" required>
                <button type="submit">Zarejestruj Się</button>
            </form>
            <div class="link">
                <p>Posiadasz konto? <a href="login.php">Zaloguj się tutaj</a></p>
            </div>
        </div>
    </body>
</html>