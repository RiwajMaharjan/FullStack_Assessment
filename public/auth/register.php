<?php
session_start();
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/db.php';

$name = $email = '';
$errors = [
    'name' => '',
    'email' => '',
    'password' => '',
    'tos' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        die('Invalid CSRF token');
    }
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $tos      = $_POST['TOS'] ?? '';

    // Validation
    if ($name === '') {
        $errors['name'] = 'Full name is required';
    }

    if ($email === '') {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email address';
    }

    if ($password === '') {
        $errors['password'] = 'Password is required';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters';
    }

    if (!$tos) {
        $errors['tos'] = 'You must agree to the terms';
    }

    if (!array_filter($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        try {
            $stmt->execute([$name, $email, $hashedPassword, 'member']); // role = member
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            $errors['email'] = 'Email already exists';
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../assets/css/signup_login_style.css">
    <script src="https://kit.fontawesome.com/78444c0a5e.js" crossorigin="anonymous"></script>
</head>
<body>
    <main>
        <div class="container-left">
            <figure>
                <img src="../../assets/images/fitness_signup_hero.jpg" alt="">
            </figure>
        </div>

        <div class="container-right">
            <div class="header">
                <h1>Create your account.</h1>
                <p>Takes less than a minute.</p>
            </div>

            <form action="" method="POST" class="signup-form">
                <div class="form-group">
                    <label>Full Name*</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" placeholder="Enter your name">
                    <small class="error"><?= $errors['name'] ?></small>
                </div>

                <div class="form-group">
                    <label>Email Address*</label>
                    <input type="text" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="Enter your email">
                    <small class="error"><?= $errors['email'] ?></small>
                </div>

                <div class="form-group">
                    <label>Password*</label>
                    <input type="password" name="password" placeholder="Enter your password">
                    <small class="error"><?= $errors['password'] ?></small>
                </div>

                <div class="form-group-checkbox">
                    <input type="checkbox" class="checkbox" name="TOS" id="tos">
                    <label for="tos" class="agree-text">
                        I agree to the Terms of service & Privacy Policy.
                    </label>
                </div>
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken(); ?>">
                <small class="error"><?= $errors['tos'] ?></small>

                <button type="submit" class="signup-btn">Create Account</button>
            </form>

            <p>Already have an account?
                <a href="login.php" class="hyperlink" style="color:#F16D34;">Sign in</a>
            </p>
        </div>
    </main>
</body>
</html>
