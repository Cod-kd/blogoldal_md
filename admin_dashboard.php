<?php
include 'connection.php';

if(!isLoggedIn()){
    toHomePage();
}

$conn = new DBConnection();
$user_email = $_SESSION['user_email'];

// Csak admin jogosultsággal rendelkező felhasználók férhetnek hozzá
if (!$conn->isAdmin()) {
    toHomePage();
}

$posts_query = "SELECT p.id, p.title, p.description, p.date, u.name as author 
                FROM posts p 
                JOIN users u ON p.author_id = u.id 
                ORDER BY p.date DESC";
$posts_result = $conn->mysqli->query($posts_query);
$conn->close();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Adminisztrátori Felület</title>
</head>
<body>
    <?php renderNavbar(); ?>
    
    <div class="container">
        <h1>Adminisztrátori Felület</h1>
        <div class="row">
            <div class="col-md-12">
                <h2>Bejegyzések kezelése</h2>
                <a href="add_post.php" class="btn btn-success mb-3">Új bejegyzés</a>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Cím</th>
                            <th>Szerző</th>
                            <th>Dátum</th>
                            <th>Műveletek</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($posts_result->num_rows > 0) {
                            while($row = $posts_result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['title']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['author']) . '</td>';
                                echo '<td>' . $row['date'] . '</td>';
                                echo '<td>';
                                echo '<a href="edit_post.php?id=' . $row['id'] . '" class="btn btn-primary btn-sm me-2">Szerkesztés</a>';
                                echo '<a href="delete_post.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Biztosan törölni szeretné?\')">Törlés</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="4">Nincsenek bejegyzések</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>