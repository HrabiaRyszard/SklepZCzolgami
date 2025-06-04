<?php
session_start();
if (!isset($_SESSION['role'])  || $_SESSION['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}
require '../db.php';

$userID = $_SESSION['userID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] === 'update_info') {
        $imie = mysqli_real_escape_string($db, $_POST['imie']);
        $nazwisko = mysqli_real_escape_string($db, $_POST['nazwisko']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $login = mysqli_real_escape_string($db, $_POST['login']);
        $sql = "UPDATE uzytkownik SET imie='$imie', nazwisko='$nazwisko', email='$email', login='$login' WHERE id=$userID";
        mysqli_query($db, $sql);
    } elseif ($_POST['action'] === 'change_password') {

        $new_password = mysqli_real_escape_string($db, $_POST['new_password']);
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $sql = "UPDATE uzytkownik SET haslo='$hashed' WHERE id=$userID";
        mysqli_query($db, $sql);
        
    }
}
mysqli_close($db);
header("Location: account.php");
exit();
