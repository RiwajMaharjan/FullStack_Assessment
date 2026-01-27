<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$email = '';
$errors = [
    'email' => '',
    'password' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation
    if ($email === '') {
        $errors['email'] = 'Email is required';
    }

    if ($password === '') {
        $errors['password'] = 'Password is required';
    }

    if (!array_filter($errors)) {
        $sql = "SELECT id, password, role FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'trainer') {
                header('Location: trainers_dashboard.php');
            } else {
                header('Location: member_dashboard.php');
            }
            exit;
        } else {
            $errors['email'] = 'Invalid email or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/signup_login_style.css">
</head>
<body>
    <main>
        <div class="container-left">
            <figure>
                <img src="../assets/images/login_hero.jpg" alt="">
            </figure>
        </div>

        <div class="container-right">
            <div class="header">
                <h1>Log into your account</h1>
                <p>Takes less than a minute.</p>
            </div>

            <form action="" method="POST" class="signup-form">
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
                
                <button type="submit" class="signup-btn">Sign in</button>
            </form>

            <p>Don't have an account? <a href="register.php" style="color:#F16D34;">Sign up</a></p>
        </div>
    </main>
</body>
</html>
