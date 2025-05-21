<?php
session_start();
require 'db.php';

$error_message = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    $sql = "SELECT * FROM konta WHERE username = '$username'";
    $result = mysqli_query($db, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['userID'] = $user['ID']; 
            $_SESSION['username'] = $user['username'];
            header("Location: home.php"); 
            exit();
            
        } else {
            $error_message = "Złe hasło!";
        }
    } else {
        $error_message = "Nie znaleziono użytkownika!";
    }
}
mysqli_close($db);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="">
    <title>Sklep z czołgami</title>
<style>

</style>
</head>
    <body>
        <div class="container">
            <h2>Zaloguj się</h2>
            <form action="#" method="POST">
                <input type="text" name="username" placeholder="imię" required >
                <input type="password" name="password" placeholder="hasło" required>
                <button type="submit">Zaloguj się</button>
            </form>

            <div class="link">
                <p>Nie posiadasz konta? <a href="register.php">Zarejestruj się tutaj</a></p>
                <?php
                if($error_message){echo $error_message;}?>
            </div>
        </div>
    </body>
</html>