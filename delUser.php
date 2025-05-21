<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['userID'];

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

session_destroy();

require 'db.php';

$sql = "DELETE FROM konta WHERE id = $id";

mysqli_query($db, $sql);

header("Location: home.php");
exit();
?>