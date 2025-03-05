<?php
include 'connection.php';

if(isLoggedIn()){
    toHomePage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new DBConnection();
    $conn->login($_POST['email'],$_POST['password']);
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogoldal - Dani</title>
</head>
<body>
    <form method="POST">
        <label for="email">Email: </label>
        <input type="email" id="email" name="email" placeholder="Az emailed..." required>
        <label for="password">Jelszó:</label>
        <input type="password" id="password" name="password" placeholder="A jelszavad..." required>
        <button type="submit">Bejelentkezés</button>
    </form>
</body>
</html>