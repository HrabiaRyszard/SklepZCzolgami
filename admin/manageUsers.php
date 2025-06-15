<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
require '../db.php';

$sql = "SELECT id, imie, nazwisko, email FROM uzytkownik";
$result = mysqli_query($db, $sql);
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzaj użytkownikami</title>
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
        <div>
            <h1 class="noMargin">Użytkownicy</h1>
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
                                <a href="editUser.php?id=<?= $user['id'] ?>"><button class='modifyBtn'>Modyfikuj</button></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <p><a href="addUser.php">+ Dodaj nowego użytkownika</a></p>
        </div>
    </main>
    <footer>
        Autorzy: <b>Ryszard Osiński</b>, <b>Mirosław Karpowicz</b>, <b>Szymon Linek</b>, <b>Krystian Kotowski</b>
    </footer>
</body>

</html>
