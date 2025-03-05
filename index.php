<?php
include "handler.php";
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogoldal - Dani</title>
</head>
<body>
    <?php
    if(isLoggedIn()){
        logOutBtn();
    } else {
        echo 
        `<a href="./register.php">Regisztráció</a>
        <a href="./login.php">Bejelentkezés</a>`;
    }
    ?>
</body>
</html>