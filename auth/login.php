<?php
session_start();
require_once '../config/db.php';
require_once '../functions/helpers.php';

// Kalau sudah login redirect
if (isLoggedIn()) {
    if (isAdmin()) {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../index.php");
    }
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = "Username dan password wajib diisi!";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gravity Falls</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        /* Tambahan style untuk pesan error */
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
        .success-msg {
            background: rgba(40,160,80,0.3);
            border: 1px solid #28a050;
            color: #a8e6b8;
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
        <div class="external-title">SIGN IN</div>
        <div class="left-side"></div>
        <div class="right-side">
            <img src="../assets/logogravity.png" alt="Gravity Falls Logo" class="logo">
            <div class="form-content">
                <h2 class="form-title">LOGIN</h2>

                <?php
                // Tampilkan flash message dari register
                $flash = getFlash();
                if ($flash): ?>
                    <div class="<?= $flash['type'] === 'success' ? 'success-msg' : 'error-msg' ?>">
                        <?= $flash['message'] ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="error-msg"><?= $error ?></div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="input-group">
                        <input type="text"
                               name="username"
                               id="username"
                               placeholder=" "
                               value="<?= sanitize($_POST['username'] ?? '') ?>"
                               required>
                        <label for="username">USERNAME</label>
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <div class="input-group">
                        <input type="password"
                               name="password"
                               id="password"
                               placeholder=" "
                               required>
                        <label for="password">PASSWORD</label>
                        <i class="fa-solid fa-lock"></i>
                    </div>

                    <button type="submit" class="submit-btn">SIGN IN</button>
                </form>

                <div class="switch-link">
                    Belum punya akun? <a href="register.php">Daftar disini</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>