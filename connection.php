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
        $stmt = $this->mysqli->prepare("CALL registration(?, ?, ?)");
        $hashed_password = hash('sha256', $password);
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Sikeres regisztráció!";
        } else {
            echo "Error: " . $this->mysqli->error;
        }
        $stmt->close();
    }

    function login($email, $password){
        $stmt = $this->mysqli->prepare("SELECT getHashedPassword(?) AS hashed_password");
        $stmt->bind_param("s", $email);
        if (!$stmt->execute()) {
            echo "Error: " . $this->mysqli->error;
        }
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if (hash('sha256', $password) == $row["hashed_password"]) {
            sessionLog($email);
            echo "Sikeres belépés!";
        }else{
            echo "Hibás email vagy jelszó!";
        }
    }

    function close(){
        $this->mysqli->close();
    }
}
?>