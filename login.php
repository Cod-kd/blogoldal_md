<?php
include 'connection.php';

if(isLoggedIn()){
    toHomePage();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Email validáció
    $email = trim($_POST['email']);
    if (empty($email)) {
        $errors[] = "Az email nem lehet üres.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Érvénytelen email formátum.";
    }

    // Jelszó validáció
    $password = $_POST['password'];
    if (empty($password)) {
        $errors[] = "A jelszó nem lehet üres.";
    }

    // Ha nincs hiba, bejelentkezés
    if (empty($errors)) {
        $conn = new DBConnection();
        $login_result = $conn->login($email, $password);
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
    <title>Blogoldal - Bejelentkezés</title>
</head>
<body>
    <?php renderNavbar(); ?>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-center">Bejelentkezés</h2>
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
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       placeholder="Adja meg az email címét" 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                       required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Jelszó</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       placeholder="Adja meg a jelszavát" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Bejelentkezés</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <p class="mb-0">Nincs még fiókja? <a href="register.php">Regisztráljon</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>