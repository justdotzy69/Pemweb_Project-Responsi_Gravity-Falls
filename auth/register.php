<?php
session_start();
require_once '../config/db.php';
require_once '../functions/helpers.php';

// Kalau sudah login redirect
if (isLoggedIn()) {
    header("Location: ../index.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        // Cek username sudah ada atau belum
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $error = "Username sudah digunakan!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt   = $pdo->prepare("
                INSERT INTO users (username, password, role)
                VALUES (?, ?, 'viewer')
            ");
            $stmt->execute([$username, $hashed]);
            redirectWith('login.php', 'success', 'Registrasi berhasil! Silakan login.');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Gravity Falls</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .error-msg {
            background: rgba(200,50,50,0.3);
            border: 1px solid #c83232;
            color: #ffaaaa;
            padding: 0.6rem 1rem;
            border-radius: 4px;
            font-size: 0.85rem;
            text-align: center;
            width: 100%;
            max-width: 350px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="external-title">SIGN UP</div>
        <div class="left-side"></div>
        <div class="right-side">
            <img src="../assets/logogravity.png" alt="Gravity Falls Logo" class="logo">
            <div class="form-content">
                <h2 class="form-title">REGISTER</h2>

                <?php if ($error): ?>
                    <div class="error-msg"><?= $error ?></div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="input-group">
                        <input type="text"
                               name="username"
                               id="reg-username"
                               placeholder=" "
                               value="<?= sanitize($_POST['username'] ?? '') ?>"
                               required>
                        <label for="reg-username">USERNAME</label>
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="input-group">
                        <input type="password"
                               name="password"
                               id="reg-password"
                               placeholder=" "
                               required>
                        <label for="reg-password">PASSWORD</label>
                        <i class="fa-solid fa-lock"></i>
                    </div>

                    <button type="submit" class="submit-btn">SIGN UP</button>
                </form>

                <div class="switch-link">
                    Sudah punya akun? <a href="login.php">Masuk disini</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>