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
        ob_clean(); // Kiürítjük a kimeneti puffert

        // Ellenőrizzük, hogy az email már létezik-e
        $check_email = $this->mysqli->prepare("SELECT id FROM users WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_email->store_result();
        
        if ($check_email->num_rows > 0) {
            $_SESSION['registration_error'] = "Ez az email cím már regisztrálva van!";
            return false;
        }
        
        $stmt = $this->mysqli->prepare("CALL registration(?, ?, ?)");
        $hashed_password = hash('sha256', $password);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            // Átirányítás a login oldalra
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['registration_error'] = "Hiba a regisztráció során: " . $this->mysqli->error;
            return false;
        }
        $stmt->close();
    }

    function login($email, $password){
        ob_clean(); // Kiürítjük a kimeneti puffert

        $stmt = $this->mysqli->prepare("SELECT getHashedPassword(?) AS hashed_password");
        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            $_SESSION['login_error'] = "Hiba a bejelentkezés során: " . $this->mysqli->error;
            return false;
        }
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if (!$row) {
            $_SESSION['login_error'] = "Hibás email vagy jelszó!";
            return false;
        }
        
        if (hash('sha256', $password) == $row["hashed_password"]) {
            sessionLog($email);
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['login_error'] = "Hibás email vagy jelszó!";
            return false;
        }
    }

    function close(){
        $this->mysqli->close();
    }
}
?>