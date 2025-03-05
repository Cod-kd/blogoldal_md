<?php
include 'handler.php';
class DBConnection{
    private $host = "localhost";
    private $user = "root";
    private $password = "root";
    private $database = "blogoldal";

    public $mysqli;

    function __construct()
    {
        $this->mysqli = new mysqli(
            $this->host, $this->user, $this->password, $this->database
        );
        if ($this->mysqli->connect_error) {
            die("Sikertelen kapcsolat az adatbázissal: " . $this->mysqli->connect_error);
        }
    }
    
    function registration($name, $email, $password){
        // Ellenőrizzük, hogy az email már létezik-e
        $check_email = $this->mysqli->prepare("SELECT id FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();
        
        if ($check_email->num_rows > 0) {
            echo '<div class="alert alert-danger">Ez az email cím már regisztrálva van!</div>';
            return false;
        }
        
        $stmt = $this->mysqli->prepare("CALL registration(?, ?, ?)");
        $hashed_password = hash('sha256', $password);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Sikeres regisztráció!</div>';
            // Átirányítás a login oldalra
            header('Location: login.php');
            exit();
        } else {
            echo '<div class="alert alert-danger">Hiba a regisztráció során: ' . $this->mysqli->error . '</div>';
            return false;
        }
        $stmt->close();
    }

    function login($email, $password){
        $stmt = $this->mysqli->prepare("SELECT getHashedPassword(?) AS hashed_password");
        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            echo '<div class="alert alert-danger">Hiba a bejelentkezés során: ' . $this->mysqli->error . '</div>';
            return false;
        }
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if (!$row) {
            echo '<div class="alert alert-danger">Hibás email vagy jelszó!</div>';
            return false;
        }
        
        if (hash('sha256', $password) == $row["hashed_password"]) {
            sessionLog($email);
            echo '<div class="alert alert-success">Sikeres belépés!</div>';
            // Átirányítás a főoldalra
            header('Location: index.php');
            exit();
        } else {
            echo '<div class="alert alert-danger">Hibás email vagy jelszó!</div>';
            return false;
        }
    }

    function close(){
        $this->mysqli->close();
    }
}
?>