<?php
include 'connection.php';

if(isLoggedIn()){
    toHomePage();
}

$errors = [];

// Ellenőrizzük, van-e korábbi regisztrációs hiba
if (isset($_SESSION['registration_error'])) {
    $errors[] = $_SESSION['registration_error'];
    unset($_SESSION['registration_error']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Névhez tartozó validáció
    $name = trim($_POST['name']);
    if (empty($name)) {
        $errors[] = "A név nem lehet üres.";
    }

    // Email validáció
    $email = trim($_POST['email']);
    if (empty($email)) {
        $errors[] = "Az email nem lehet üres.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Érvénytelen email formátum.";
    }

    // Jelszó validáció
    $password = $_POST['password'];
    $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';
    if (empty($password)) {
        $errors[] = "A jelszó nem lehet üres.";
    } elseif (strlen($password) < 8) {
        $errors[] = "A jelszónak legalább 8 karakter hosszúnak kell lennie.";
    } elseif (!preg_match($password_pattern, $password)) {
        $errors[] = "A jelszónak tartalmaznia kell legalább egy kisbetűt, egy nagybetűt és egy számot.";
    }

    // Ha nincs hiba, regisztráció
    if (empty($errors)) {
        $conn = new DBConnection();
        $conn->registration($name, $email, $password);
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Blogoldal - Regisztráció</title>
</head>
<body>
    <?php renderNavbar(); ?>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Regisztráció</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        // Hibaüzenetek megjelenítése
                        if (!empty($errors)) {
                            echo '<div class="alert alert-danger">';
                            foreach ($errors as $error) {
                                echo "<p class='mb-0'>$error</p>";
                            }
                            echo '</div>';
                        }
                        ?>
                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Név</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       placeholder="Adja meg a nevét" 
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                       required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="Az email címe" 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                       required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Jelszó</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Adjon meg egy jelszót" required>
                                <small class="form-text text-muted">
                                    A jelszónak legalább 8 karakter hosszúnak kell lennie, 
                                    tartalmaznia kell legalább egy kisbetűt, egy nagybetűt és egy számot.
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Regisztráció</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-0">Már van fiókja? <a href="login.php">Jelentkezzen be</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>