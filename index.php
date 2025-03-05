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
    <?php renderNavbar(); ?>
    
    <div class="container">
        <div class="row">
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