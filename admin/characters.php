<?php
session_start();

require_once '../config/db.php';
require_once '../functions/helpers.php';

requireLogin('../auth/login.php');
requireAdmin('../index.php');

$flash = getFlash();

/* ==========================================
   DELETE CHARACTER
========================================== */
if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    $stmt = $pdo->prepare("DELETE FROM characters WHERE id=?");
    $stmt->execute([$id]);

    setFlash('success','Character berhasil dihapus.');
    header("Location: characters.php");
    exit();
}

/* ==========================================
   ADD / EDIT CHARACTER
========================================== */
if ($_SERVER['REQUEST_METHOD']=="POST") {

    $id = $_POST['id'] ?? '';

    $name = sanitize($_POST['name']);
    $nickname = sanitize($_POST['nickname']);
    $category = sanitize($_POST['category']);
    $appearance = sanitize($_POST['appearance']);
    $status = sanitize($_POST['status']);
    $description = sanitize($_POST['description']);

    if(empty($id)){

        $stmt=$pdo->prepare("
            INSERT INTO characters
            (name,nickname,category,appearance,status,description)
            VALUES (?,?,?,?,?,?)
        ");

        $stmt->execute([
            $name,
            $nickname,
            $category,
            $appearance,
            $status,
            $description
        ]);

        setFlash('success','Character berhasil ditambahkan.');

    }else{

        $stmt=$pdo->prepare("
            UPDATE characters SET
            name=?,
            nickname=?,
            category=?,
            appearance=?,
            status=?,
            description=?
            WHERE id=?
        ");

        $stmt->execute([
            $name,
            $nickname,
            $category,
            $appearance,
            $status,
            $description,
            $id
        ]);

        setFlash('success','Character berhasil diperbarui.');
    }

    header("Location: characters.php");
    exit();
}

/* ==========================================
   EDIT DATA
========================================== */

$edit = null;

if(isset($_GET['edit'])){

    $stmt=$pdo->prepare("SELECT * FROM characters WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $edit=$stmt->fetch();
}

/* ==========================================
   AMBIL DATA CHARACTER
========================================== */

$characters=$pdo
    ->query("SELECT * FROM characters ORDER BY id ASC")
    ->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin - Character</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- CSS milik frontend teman -->
<link rel="stylesheet" href="../assets/css/dashboard.css">

<!-- jika nanti ada css khusus character -->
<link rel="stylesheet" href="../assets/css/character.css">

</head>

<body>

<div class="admin-layout">

<aside class="admin-sidebar" id="adminSidebar" onclick="toggleSidebar()">

<div class="sidebar-logo">

<img src="../assets/logogravity.png" alt="Logo">

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

<li class="menu-item active">
<a href="characters.php">
<i class="fa-solid fa-face-smile"></i>
<span class="menu-text">Character</span>
</a>
</li>

<li class="menu-item">
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
<?= htmlspecialchars($_SESSION['username']) ?>
</span>

<i class="fa-solid fa-caret-up account-caret"></i>

</button>

<div class="dropdown-menu" id="accountDropdown">

<a href="../index.php" class="dropdown-item">
<i class="fa-solid fa-house"></i>
Home Page
</a>

<a href="../auth/logout.php" class="dropdown-item">
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

<?php if ($flash): ?>

<div class="<?= $flash['type']=='success' ? 'success-msg' : 'error-msg'; ?>">
    <?= $flash['message']; ?>
</div>

<?php endif; ?>

<?php $showForm = isset($_GET['add']) || isset($_GET['edit']); ?>

<?php if(!$showForm): ?>

<div id="table-view">

    <div class="title-pill brand-font">
        <span class="screw left"></span>
        CHARACTER
        <span class="screw right"></span>
    </div>

    <table class="data-table">

        <thead>

            <tr>

                <th>NO</th>

                <th>NAME</th>

                <th>EDIT / DELETE</th>

            </tr>

        </thead>

        <tbody>

        <?php if(count($characters)>0): ?>

            <?php
            $no=1;
            foreach($characters as $character):
            ?>

            <tr>

                <td><?= $no++; ?></td>

                <td><?= htmlspecialchars($character['name']); ?></td>

                <td>

                    <a
                    href="characters.php?edit=<?= $character['id']; ?>"
                    class="btn-edit">

                        <i class="fa-solid fa-pen-to-square"></i>

                        EDIT

                    </a>

                    <a
                    href="characters.php?delete=<?= $character['id']; ?>"
                    class="btn-delete"
                    onclick="return confirm('Yakin ingin menghapus character ini?')">

                        <i class="fa-solid fa-trash-can"></i>

                        DELETE

                    </a>

                </td>

            </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>

                <td colspan="3">

                    Belum ada data character.

                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

    <button
    class="btn-add"
    onclick="location.href='characters.php?add=1'">

        ADD NEW CHARACTER

    </button>

</div>

<?php else: ?>

<div id="form-view">

    <div class="title-pill brand-font">

        <span class="screw left"></span>

        <span id="form-title-text">

            <?= isset($editData) ? 'EDIT CHARACTER' : 'ADD NEW CHARACTER'; ?>

        </span>

        <span class="screw right"></span>

    </div>

    <form
    class="admin-form"
    method="POST"
    enctype="multipart/form-data">

        <input
        type="hidden"
        name="id"
        value="<?= $editData['id'] ?? ''; ?>">

    <div class="form-group">
    <label>NAME</label>
    <input
        type="text"
        name="name"
        class="input-line"
        value="<?= $editData['name'] ?? ''; ?>"
        required>
</div>

<div class="form-group">
    <label>NICKNAME</label>
    <input
        type="text"
        name="nickname"
        class="input-line"
        value="<?= $editData['nickname'] ?? ''; ?>">
</div>

<div class="form-group">
    <label>CATEGORY</label>
    <input
        type="text"
        name="category"
        class="input-line"
        value="<?= $editData['category'] ?? ''; ?>">
</div>

<div class="form-group">
    <label>FIRST APPEARANCE</label>
    <input
        type="text"
        name="first_appearance"
        class="input-line"
        value="<?= $editData['first_appearance'] ?? ''; ?>">
</div>

<div class="form-group">
    <label>STATUS</label>
    <input
        type="text"
        name="status"
        class="input-line"
        value="<?= $editData['status'] ?? ''; ?>">
</div>

<div class="form-group">
    <label>DESCRIPTION</label>
    <textarea
        name="description"
        class="input-box"><?= $editData['description'] ?? ''; ?></textarea>
</div>

<div class="form-group">
    <label>IMAGE</label>

    <?php if(isset($editData) && !empty($editData['image'])): ?>

        <img
            src="../assets/characters/<?= $editData['image']; ?>"
            width="120"
            style="margin-bottom:10px; display:block;">

    <?php endif; ?>

    <input
        type="file"
        name="image"
        accept=".jpg,.jpeg,.png,.webp,.svg">
</div>

<div class="form-buttons">

    <button
        type="submit"
        class="btn-submit">

        SUBMIT

    </button>

    <a
        href="characters.php"
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