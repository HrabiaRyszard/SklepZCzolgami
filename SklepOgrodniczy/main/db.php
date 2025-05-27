<?php
$db = mysqli_connect("localhost", "root", "", "02");

if (!$db) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}
?>