<?php
include 'connection.php';

if(!isLoggedIn()){
    toHomePage();
}

$conn = new DBConnection();
$user_email = $_SESSION['user_email'];
$query = "SELECT id FROM users WHERE email = '$user_email'";
$result = $conn->mysqli->query($query);
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $author_id = $user['id'];

    $stmt = $conn->mysqli->prepare("INSERT INTO posts (title, description, author_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $description, $author_id);
    $stmt->execute();
    $stmt->close();

    header('Location: posts.php');
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
    <title>Blogoldal - Új bejegyzés</title>
</head>
<body>
    <?php renderNavbar(); ?>
    
    <div class="container">
        <h1>Új bejegyzés</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Cím</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Tartalom</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Mentés</button>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>