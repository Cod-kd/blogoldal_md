<?php
include 'connection.php';

if(!isLoggedIn()){
    toHomePage();
}

$conn = new DBConnection();
$post_id = $_GET['id'];

// Bejegyzés lekérdezése
$post_query = "SELECT p.id, p.title, p.description, p.date, u.name as author 
               FROM posts p 
               JOIN users u ON p.author_id = u.id 
               WHERE p.id = ?";
$stmt = $conn->mysqli->prepare($post_query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$post_result = $stmt->get_result();
$post = $post_result->fetch_assoc();

// Kommentek lekérdezése
$comment_query = "SELECT c.text, c.date, u.name 
                  FROM comments c 
                  JOIN users u ON c.user_id = u.id 
                  WHERE c.post_id = ? 
                  ORDER BY c.date";
$stmt = $conn->mysqli->prepare($comment_query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$comment_result = $stmt->get_result();

// Új komment hozzáadása
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_email = $_SESSION['user_email'];
    $user_query = "SELECT id FROM users WHERE email = '$user_email'";
    $user_result = $conn->mysqli->query($user_query);
    $user = $user_result->fetch_assoc();

    $comment_text = $_POST['comment'];
    $insert_comment_query = "INSERT INTO comments (post_id, user_id, text) VALUES (?, ?, ?)";
    $stmt = $conn->mysqli->prepare($insert_comment_query);
    $stmt->bind_param("iis", $post_id, $user['id'], $comment_text);
    $stmt->execute();
    
    header("Location: post_detail.php?id=$post_id");
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Blogoldal - <?php echo htmlspecialchars($post['title']); ?></title>
</head>
<body>
    <?php renderNavbar(); ?>
    
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                <p class="text-muted">
                    Szerző: <?php echo htmlspecialchars($post['author']); ?> 
                    | Dátum: <?php echo $post['date']; ?>
                </p>
                <div class="card mb-4">
                    <div class="card-body">
                        <?php echo htmlspecialchars($post['description']); ?>
                    </div>
                </div>

                <h3>Hozzászólások</h3>
                <?php
                if ($comment_result->num_rows > 0) {
                    while($comment = $comment_result->fetch_assoc()) {
                        echo '<div class="card mb-2">';
                        echo '<div class="card-body">';
                        echo '<h6 class="card-subtitle mb-2 text-muted">' . htmlspecialchars($comment['name']) . ' - ' . $comment['date'] . '</h6>';
                        echo '<p class="card-text">' . htmlspecialchars($comment['text']) . '</p>';
                        echo '</div></div>';
                    }
                } else {
                    echo '<p>Még nincsenek hozzászólások.</p>';
                }
                ?>

                <form method="POST" class="mt-3">
                    <div class="mb-3">
                        <label for="comment" class="form-label">Új hozzászólás</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Küldés</button>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>