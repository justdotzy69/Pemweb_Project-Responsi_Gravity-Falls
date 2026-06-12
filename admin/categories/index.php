<?php
session_start();

require_once '../../config/db.php';
require_once '../../functions/helpers.php';

requireLogin('../../auth/login.php');
requireAdmin('../../index.php');

$categories = [
    [
        "title" => "Character",
        "description" => "Kategori yang digunakan untuk mengelompokkan karakter di Gravity Falls."
    ],
    [
        "title" => "Journal",
        "description" => "Kategori informasi mengenai seluruh jurnal Gravity Falls."
    ],
    [
        "title" => "Location",
        "description" => "Kategori tempat-tempat penting yang ada di Gravity Falls."
    ]
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Categories</title>

    <link rel="stylesheet" href="../../assets/css/categories.css">
</head>

<body>

<h1>Categories</h1>

<div class="categories-info">

<?php foreach($categories as $category): ?>

<div class="category-info-card">

    <div class="category-info-header">

        <h3><?= $category['title']; ?></h3>

    </div>

    <p><?= $category['description']; ?></p>

</div>

<?php endforeach; ?>

</div>

</body>
</html>