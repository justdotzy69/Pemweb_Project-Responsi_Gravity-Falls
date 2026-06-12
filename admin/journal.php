<?php
session_start();

require_once '../config/db.php';
require_once '../functions/helpers.php';

requireLogin('../auth/login.php');
requireAdmin('../index.php');

$flash = getFlash();

/* =========================
   DELETE
========================= */

if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM journal WHERE id=?");
    $stmt->execute([$id]);

    setFlash('success', 'Journal berhasil dihapus.');

    header("Location: journal.php");
    exit();
}

/* =========================
   ADD & UPDATE
========================= */

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $id = $_POST['id'] ?? '';

    $title = trim($_POST['title']);
    $journal_number = trim($_POST['journal_number']);
    $description = trim($_POST['description']);

    if (empty($id)) {

        $stmt = $pdo->prepare("
            INSERT INTO journal
            (title, journal_number, description)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $title,
            $journal_number,
            $description
        ]);

        setFlash('success', 'Journal berhasil ditambahkan.');

    } else {

        $stmt = $pdo->prepare("
            UPDATE journal
            SET
                title=?,
                journal_number=?,
                description=?
            WHERE id=?
        ");

        $stmt->execute([
            $title,
            $journal_number,
            $description,
            $id
        ]);

        setFlash('success', 'Journal berhasil diperbarui.');

    }

    header("Location: journal.php");
    exit();
}

/* =========================
   EDIT
========================= */

$editData = null;

if (isset($_GET['edit'])) {

    $stmt = $pdo->prepare("
        SELECT *
        FROM journal
        WHERE id=?
    ");

    $stmt->execute([
        $_GET['edit']
    ]);

    $editData = $stmt->fetch();
}

/* =========================
   AMBIL DATA
========================= */

$stmt = $pdo->query("
    SELECT *
    FROM journal
    ORDER BY id ASC
");

$journals = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - Journal</title>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link
        rel="stylesheet"
        href="../assets/css/dashboard.css">

</head>

<body>

<div class="admin-layout">

<aside
class="admin-sidebar"
id="adminSidebar"
onclick="toggleSidebar()">

<div class="sidebar-logo">

<img
src="../assets/logogravity.png"
alt="Logo">

<span class="brand-font logo-text">
GRAVITY FALLS
</span>

</div>

<ul class="sidebar-menu">

<li class="menu-item">
<a href="dashboard.php">
<i class="fa-solid fa-house"></i>
<span class="menu-text">Dashboard</span>
</a>
</li>

<li class="menu-item">
<a href="users.php">
<i class="fa-regular fa-user"></i>
<span class="menu-text">Users</span>
</a>
</li>

<li class="menu-item">
<a href="characters.php">
<i class="fa-solid fa-face-smile"></i>
<span class="menu-text">Character</span>
</a>
</li>

<li class="menu-item active">
<a href="journal.php">
<i class="fa-solid fa-book"></i>
<span class="menu-text">Journal</span>
</a>
</li>

<li class="menu-item">
<a href="locations.php">
<i class="fa-solid fa-location-dot"></i>
<span class="menu-text">Location</span>
</a>
</li>

</ul>

<div class="sidebar-bottom">

<div class="profile-dropdown">

<button
type="button"
class="account-btn"
onclick="toggleAccountDropdown(event)">

<i class="fa-regular fa-circle-user"></i>

<span class="account-text">
Account
</span>

<i class="fa-solid fa-caret-up account-caret"></i>

</button>

<div
class="dropdown-menu"
id="accountDropdown">

<a
href="../index.php"
class="dropdown-item">

<i class="fa-solid fa-house"></i>

Home Page

</a>

<a
href="../auth/logout.php"
class="dropdown-item">

<i class="fa-solid fa-arrow-right-from-bracket"></i>

Logout

</a>

</div>

</div>

</div>

</aside>

<main class="admin-main">

<img
src="../assets/logogravity.png"
alt="Logo Besar"
class="bg-logo-top">

<?php if($flash): ?>

<div class="<?= $flash['type']=='success' ? 'success-msg' : 'error-msg'; ?>">

    <?= $flash['message']; ?>

</div>

<?php endif; ?>

<?php $showForm = isset($_GET['add']) || isset($_GET['edit']); ?>

<?php if(!$showForm): ?>

<div id="table-view">

    <div class="title-pill brand-font">

        <span class="screw left"></span>

        JOURNAL LIST

        <span class="screw right"></span>

    </div>

    <table class="data-table">

        <thead>

            <tr>

                <th>NO</th>

                <th>TITLE</th>

                <th>JOURNAL NUMBER</th>

                <th>EDIT / DELETE</th>

            </tr>

        </thead>

        <tbody>

        <?php if(count($journals)>0): ?>

            <?php
            $no = 1;
            foreach($journals as $journal):
            ?>

            <tr>

                <td><?= $no++; ?></td>

                <td><?= htmlspecialchars($journal['title']); ?></td>

                <td><?= htmlspecialchars($journal['journal_number']); ?></td>

                <td>

                    <a
                        href="journal.php?edit=<?= $journal['id']; ?>"
                        class="btn-edit">

                        <i class="fa-solid fa-pen-to-square"></i>

                        EDIT

                    </a>

                    <a
                        href="journal.php?delete=<?= $journal['id']; ?>"
                        class="btn-delete"
                        onclick="return confirm('Yakin ingin menghapus journal ini?')">

                        <i class="fa-solid fa-trash-can"></i>

                        DELETE

                    </a>

                </td>

            </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>

                <td colspan="4">

                    Belum ada data journal.

                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

    <button
        class="btn-add"
        onclick="location.href='journal.php?add=1'">

        ADD NEW JOURNAL

    </button>

</div>

<?php else: ?>

<div id="form-view">

    <div class="title-pill brand-font">

        <span class="screw left"></span>

        <span id="form-title-text">

            <?= isset($editData) ? 'EDIT JOURNAL' : 'ADD NEW JOURNAL'; ?>

        </span>

        <span class="screw right"></span>

    </div>

    <form
        class="admin-form"
        method="POST">

        <input
            type="hidden"
            name="id"
            value="<?= $editData['id'] ?? ''; ?>">
    
    <div class="form-group">

    <label>TITLE</label>

    <input
        type="text"
        name="title"
        class="input-line"
        value="<?= $editData['title'] ?? ''; ?>"
        required>

</div>

<div class="form-group">

    <label>JOURNAL NUMBER</label>

    <input
        type="text"
        name="journal_number"
        class="input-line"
        value="<?= $editData['journal_number'] ?? ''; ?>"
        required>

</div>

<div class="form-group">

    <label>DESCRIPTION</label>

    <textarea
        name="description"
        class="input-box"
        required><?= $editData['description'] ?? ''; ?></textarea>

</div>

<div class="form-buttons">

    <button
        type="submit"
        class="btn-submit">

        SUBMIT

    </button>

    <a
        href="journal.php"
        class="btn-cancel">

        CANCEL

    </a>

</div>

</form>

</div>

<?php endif; ?>

</main>

</div>

<script>

function toggleSidebar(){

    document
        .getElementById("adminSidebar")
        .classList
        .toggle("expanded");

}

function toggleAccountDropdown(event){

    event.stopPropagation();

    document
        .getElementById("accountDropdown")
        .classList
        .toggle("show");

}

window.addEventListener("click",function(){

    document
        .getElementById("accountDropdown")
        .classList
        .remove("show");

});

</script>

</body>
</html>