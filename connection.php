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
            return [
                'success' => false,
                'message' => "Ez az email cím már regisztrálva van!"
            ];
        }
        
        $stmt = $this->mysqli->prepare("CALL registration(?, ?, ?)");
        $hashed_password = hash('sha256', $password);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => "Sikeres regisztráció!"
            ];
        } else {
            return [
                'success' => false,
                'message' => "Hiba a regisztráció során: " . $this->mysqli->error
            ];
        }
        $stmt->close();
    }

    function login($email, $password){
        $stmt = $this->mysqli->prepare("SELECT getHashedPassword(?) AS hashed_password");
        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            return [
                'success' => false,
                'message' => "Hiba a bejelentkezés során: " . $this->mysqli->error
            ];
        }
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        if (!$row) {
            return [
                'success' => false,
                'message' => "Hibás email vagy jelszó!"
            ];
        }
        
        if (hash('sha256', $password) == $row["hashed_password"]) {
            return [
                'success' => true,
                'message' => "Sikeres belépés!"
            ];
        } else {
            return [
                'success' => false,
                'message' => "Hibás email vagy jelszó!"
            ];
        }
    }

    function close(){
        $this->mysqli->close();
    }

    function isAdmin($email = null) {
        // Ha nem adunk meg email-t, az aktuális sessionben lévő emailt használjuk
        if ($email === null && isset($_SESSION['user_email'])) {
            $email = $_SESSION['user_email'];
        }
    
        // Ha nincs email, akkor biztosan nem admin
        if ($email === null) {
            return false;
        }
    
        $stmt = $this->mysqli->prepare("SELECT is_admin FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['is_admin'] == 1;
        }
        
        return false;
    }
}
?>