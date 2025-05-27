<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['userID'];

$_SESSION = [];

session_destroy();

require 'db.php';

$sql = "DELETE FROM konta WHERE id = $id";

mysqli_query($db, $sql);

header("Location: home.php");
exit();
?>