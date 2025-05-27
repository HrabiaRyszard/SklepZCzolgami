<?php
$db = mysqli_connect("localhost", "root", "", "sklep");

if (!$db) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}
?>