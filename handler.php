<?
session_start();

function logOutBtn(){
    echo '<form action="index.php" method="POST">
    <button onclick="destroySession()">Log out</button>
    </form>';
}

function destroySession(){
    session_unset();
    toHomePage();
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
    
?>