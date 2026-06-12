<?php
session_start();
require_once '../../config/db.php';
require_once '../../functions/helpers.php';

requireLogin('../../auth/login.php');
requireAdmin('../../index.php');

// Ambil semua kategori beserta jumlah karakternya
$categories = $pdo->query("
    SELECT cat.*, COUNT(c.id) AS total_chars
    FROM categories cat
    LEFT JOIN characters c ON c.category_id = cat.id
    GROUP BY cat.id
    ORDER BY cat.name ASC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kategori - Gravity Falls</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-brand">
        <a href="../../index.php">📖 Gravity Falls Wiki</a>
    </div>
    <div class="nav-links">
        <a href="../dashboard.php" class="btn-nav">Dashboard</a>
        <a href="../../auth/logout.php" class="btn-nav btn-logout">Logout</a>
    </div>
</nav>

<div class="container">
    <a href="../dashboard.php" class="back-link">← Kembali ke Dashboard</a>
    <h1 class="page-title">🏷️ Daftar Kategori</h1>
    <p style="color:#a0a0b0; margin-bottom:1.5rem;">
        Kategori digunakan sebagai pengelompokan karakter.
        Data kategori bersifat tetap dan hanya untuk referensi.
    </p>

    <!-- TAMPILAN KATEGORI SEBAGAI CARD INFO -->
    <div class="categories-info">
        <?php foreach ($categories as $cat): ?>
            <div class="category-info-card">
                <div class="category-info-header">
                    <h3><?= sanitize($cat['name']) ?></h3>
                    <span class="badge"><?= $cat['total_chars'] ?> Karakter</span>
                </div>
                <p><?= sanitize($cat['description'] ?? '-') ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer class="footer">
    <p>📖 Gravity Falls Journal Wiki &copy; <?= date('Y') ?></p>
</footer>

</body>
</html>