<?php
session_start();

function logOutBtn(){
    echo '<form action="logout.php" method="POST" class="d-inline">';
    echo '<button type="submit" class="btn btn-danger">Kijelentkezés</button>';
    echo '</form>';
}

function destroySession(){
    // Töröljük a session összes változóját
    $_SESSION = array();

    // Töröljük a session cookie-t
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Végül megsemmisítjük a sessiont
    session_destroy();
}

function isLoggedIn(){
    return isset($_SESSION['user_email']);
}

function toHomePage(){
    header('Location: index.php');
    exit();
}

function sessionLog($email){
    $_SESSION['user_email']=$email;
}

function renderNavbar() {
    echo '<nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">';
    echo '<div class="container-fluid">';
    echo '<a class="navbar-brand" href="index.php">Blogoldal</a>';
    echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">';
    echo '<span class="navbar-toggler-icon"></span>';
    echo '</button>';
    echo '<div class="collapse navbar-collapse" id="navbarNav">';
    echo '<ul class="navbar-nav">';
    
    // Mindig látható linkek
    echo '<li class="nav-item"><a class="nav-link" href="index.php">Főoldal</a></li>';
    
    // Csak bejelentkezett felhasználóknak
    if(isLoggedIn()) {
        echo '<li class="nav-item"><a class="nav-link" href="posts.php">Bejegyzések</a></li>';
        echo '<li class="nav-item"><a class="nav-link" href="add_post.php">Új bejegyzés</a></li>';
    }
    
    echo '</ul>';
    
    // Bejelentkezés/Kijelentkezés gombok
    echo '<div class="ms-auto">';
    if(isLoggedIn()) {
        echo '<span class="navbar-text me-3">' . htmlspecialchars($_SESSION['user_email']) . '</span>';
        logOutBtn();
    } else {
        echo '<a href="login.php" class="btn btn-outline-primary me-2">Bejelentkezés</a>';
        echo '<a href="register.php" class="btn btn-primary">Regisztráció</a>';
    }
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
    echo '</nav>';
}
?>