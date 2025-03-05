<?php
include 'connection.php';

if(!isLoggedIn()){
    toHomePage();
}

$conn = new DBConnection();
$post_id = $_GET['id'];

// Bejegyzés lekérdezése
$query = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->mysqli->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $update_query = "UPDATE posts SET title = ?, description = ? WHERE id = ?";
    $stmt = $conn->mysqli->prepare($update_query);
    $stmt->bind_param("ssi", $title, $description, $post_id);
    $stmt->execute();

    $conn->close();
    header('Location: admin_dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Bejegyzés szerkesztése</title>
</head>
<body>
    <?php renderNavbar(); ?>
    
    <div class="container">
        <h1>Bejegyzés szerkesztése</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Cím</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Tartalom</label>
                <textarea class="form-control" id="description" name="description" rows="5" required><?php echo htmlspecialchars($post['description']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Mentés</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Vissza</a>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>