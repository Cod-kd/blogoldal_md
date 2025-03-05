<?php
include 'connection.php';

if(!isLoggedIn()){
    toHomePage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new DBConnection();
    $user_email = $_SESSION['user_email'];
    
    // Felhasználó azonosítójának lekérése
    $user_query = $conn->mysqli->prepare("SELECT id FROM users WHERE email = ?");
    $user_query->bind_param("s", $user_email);
    $user_query->execute();
    $user_result = $user_query->get_result();
    $user = $user_result->fetch_assoc();

    // Új komment hozzáadása
    $post_id = $_POST['post_id'];
    $comment_text = trim($_POST['comment']);

    if (!empty($comment_text)) {
        $insert_comment_query = $conn->mysqli->prepare("INSERT INTO comments (post_id, user_id, text) VALUES (?, ?, ?)");
        $insert_comment_query->bind_param("iis", $post_id, $user['id'], $comment_text);
        $insert_comment_query->execute();
    }

    $conn->close();
    
    // Visszairányítás a bejegyzés részleteihez
    header("Location: post_detail.php?id=" . $post_id);
    exit();
} else {
    toHomePage();
}
?>