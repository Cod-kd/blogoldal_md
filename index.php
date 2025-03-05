<?php
include "handler.php";
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Blogoldal - Dani</title>
</head>
<body>
    <div class="container mt-5">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Blogoldal</a>
                <div class="navbar-nav">
                    <?php
                    if(isLoggedIn()){
                        echo '<a class="nav-link" href="posts.php">Bejegyzések</a>';
                        logOutBtn();
                    } else {
                        echo '<a class="nav-link" href="register.php">Regisztráció</a>';
                        echo '<a class="nav-link" href="login.php">Bejelentkezés</a>';
                    }
                    ?>
                </div>
            </div>
        </nav>

        <div class="row mt-4">
            <div class="col">
                <h1>Üdvözöl a Blogoldal</h1>
                <?php
                if(!isLoggedIn()){
                    echo '<p>A bejegyzések megtekintéséhez jelentkezz be!</p>';
                }
                ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>