<?php
require '../db.php';

$sql = "SELECT id, imie, nazwisko, email FROM uzytkownik";
$result = mysqli_query($db, $sql);
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Zarządzanie użytkownikami</title>
</head>

<body>
    <h1>Użytkownicy</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Email</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['imie'] ?></td>
                    <td><?= $user['nazwisko'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td>
                        <a href="editUser.php?id=<?= $user['id'] ?>">Edytuj</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <p><a href="addUser.php">+ Dodaj nowego użytkownika</a></p>
</body>

</html>