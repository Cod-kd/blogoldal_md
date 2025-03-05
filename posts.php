<?php
include 'connection.php';

if(!isLoggedIn()){
    toHomePage();
}

$conn = new DBConnection();
$user_email = $_SESSION['user_email'];
$user_query = "SELECT id FROM users WHERE email = '$user_email'";
$user_result = $conn->mysqli->query($user_query);
$user = $user_result->fetch_assoc();
$is_admin = $conn->isAdmin();

$query = "SELECT p.id, p.title, p.description, p.date, p.author_id, u.name as author 
          FROM posts p 
          JOIN users u ON p.author_id = u.id 
          ORDER BY p.date DESC";
$result = $conn->mysqli->query($query);
$conn->close();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Blogoldal - Bejegyzések</title>
</head>
<body>
    <?php renderNavbar(); ?>
    
    <div class="container">
        <h1>Bejegyzések</h1>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-3">';
                    echo '<div class="card">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($row['title']) . '</h5>';
                    echo '<h6 class="card-subtitle mb-2 text-muted">' . htmlspecialchars($row['author']) . ' - ' . $row['date'] . '</h6>';
                    echo '<p class="card-text">' . substr(htmlspecialchars($row['description']), 0, 100) . '...</p>';
                    echo '<div class="d-flex justify-content-between">';
                    echo '<a href="post_detail.php?id=' . $row['id'] . '" class="btn btn-primary">Tovább olvasom</a>';
                    
                    // Szerkesztés link csak adminnak vagy a saját bejegyzésnél
                    if ($is_admin || $row['author_id'] == $user['id']) {
                        echo '<a href="edit_post.php?id=' . $row['id'] . '" class="btn btn-secondary">Szerkesztés</a>';
                    }
                    
                    echo '</div>';
                    echo '</div></div></div>';
                }
            } else {
                echo '<div class="col"><p>Jelenleg nincsenek bejegyzések.</p></div>';
            }
            ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>