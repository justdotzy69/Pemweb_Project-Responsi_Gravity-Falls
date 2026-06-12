<?php
session_start();

require_once '../config/db.php';
require_once '../functions/helpers.php';

requireLogin('../auth/login.php');
requireAdmin('../index.php');

$flash = getFlash();

/* ===========================
   HAPUS LOCATION
=========================== */
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['action']) &&
    $_POST['action'] === 'delete'
) {

    requireAdmin('../index.php');

    $id = (int)$_POST['id'];

    $stmt = $pdo->prepare("DELETE FROM locations WHERE id = ?");
    $stmt->execute([$id]);

    setFlash('success','Location berhasil dihapus.');

    header("Location: locations.php");
    exit();
}

/* ===========================
   TAMBAH LOCATION
=========================== */
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['action']) &&
    $_POST['action'] === 'create'
) {

    $stmt = $pdo->prepare("
        INSERT INTO locations
        (
            name,
            location_type,
            is_dangerous,
            first_appearance,
            description
        )
        VALUES
        (
            ?,?,?,?,?
        )
    ");

    $stmt->execute([

        trim($_POST['name']),
        trim($_POST['type']),
        trim($_POST['dangerous']),
        trim($_POST['appearance']),
        trim($_POST['description'])

    ]);

    setFlash(
        'success',
        'Location berhasil ditambahkan.'
    );

    header("Location: locations.php");
    exit();
}

/* ===========================
   UPDATE LOCATION
=========================== */

if (

    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['action']) &&
    $_POST['action'] === 'update'

) {

    $id = (int)$_POST['id'];

    $stmt = $pdo->prepare("

        UPDATE locations

        SET

            name=?,
            location_type=?,
            is_dangerous=?,
            first_appearance=?,
            description=?

        WHERE id=?

    ");

    $stmt->execute([

        trim($_POST['name']),
        trim($_POST['type']),
        trim($_POST['dangerous']),
        trim($_POST['appearance']),
        trim($_POST['description']),
        $id

    ]);

    setFlash(
        'success',
        'Location berhasil diperbarui.'
    );

    header("Location: locations.php");
    exit();
}

/* ===========================
   AMBIL DATA
=========================== */

$locations = $pdo->query("
    SELECT *
    FROM locations
    ORDER BY id ASC
")->fetchAll(PDO::FETCH_ASSOC);

$editData = null;

if(isset($_GET['edit'])){

    $stmt = $pdo->prepare("
        SELECT *
        FROM locations
        WHERE id=?
    ");

    $stmt->execute([
        (int)$_GET['edit']
    ]);

    $editData = $stmt->fetch(PDO::FETCH_ASSOC);

}

?>

<!DOCTYPE html>

<html lang="id">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>

Dashboard Admin - Location

</title>

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

<span class="menu-text">

Dashboard

</span>

</a>

</li>

<li class="menu-item">

<a href="users.php">

<i class="fa-regular fa-user"></i>

<span class="menu-text">

Users

</span>

</a>

</li>

<li class="menu-item">

<a href="characters.php">

<i class="fa-solid fa-face-smile"></i>

<span class="menu-text">

Character

</span>

</a>

</li>

<li class="menu-item">

<a href="journal.php">

<i class="fa-solid fa-book"></i>

<span class="menu-text">

Journal

</span>

</a>

</li>

<li class="menu-item active">

<a href="locations.php">

<i class="fa-solid fa-location-dot"></i>

<span class="menu-text">

Location

</span>

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

<div id="table-view">

    <div class="title-pill brand-font">
        <span class="screw left"></span>
        LOCATION LIST
        <span class="screw right"></span>
    </div>

    <?php if($flash): ?>

        <div class="flash <?= $flash['type']; ?>">

            <?= htmlspecialchars($flash['message']); ?>

        </div>

    <?php endif; ?>

    <table class="data-table">

        <thead>

            <tr>

                <th>NO</th>
                <th>NAME</th>
                <th>TYPE</th>
                <th>DANGEROUS</th>
                <th>EDIT / DELETE</th>

            </tr>

        </thead>

        <tbody>

        <?php if(count($locations) > 0): ?>

            <?php foreach($locations as $i => $location): ?>

                <tr>

                    <td><?= $i+1 ?>.</td>

                    <td>

                        <?= htmlspecialchars($location['name']) ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($location['location_type'] ?? '') ?>

                    </td>

                    <td>

                        <?= htmlspecialchars($location['is_dangerous'] ?? '') ?>

                    </td>

                    <td>

                        <button
                            class="btn-edit"

                            onclick="showForm(

                            'edit',

                            '<?= htmlspecialchars(addslashes($location['name'])) ?>',

                            '<?= htmlspecialchars(addslashes($location['location_type'] ?? '')) ?>',

                            '<?= htmlspecialchars(addslashes($location['is_dangerous'] ?? '')) ?>',

                            '<?= htmlspecialchars(addslashes($location['first_appearance'] ?? '')) ?>',

                            '<?= htmlspecialchars(addslashes($location['description'] ?? '')) ?>',

                            <?= $location['id'] ?>

                            )">

                            <i class="fa-solid fa-pen-to-square"></i>

                            EDIT

                        </button>

                        <form
                            method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Yakin ingin menghapus location ini?');">

                            <input
                                type="hidden"
                                name="action"
                                value="delete">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= $location['id'] ?>">

                            <button class="btn-delete">

                                <i class="fa-solid fa-trash-can"></i>

                                DELETE

                            </button>

                        </form>

                    </td>

                </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>

                <td colspan="5">

                    Belum ada data location.

                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>

    <button
        class="btn-add"
        onclick="showForm('add')">

        ADD NEW LOCATION

    </button>

</div>

<div id="form-view" style="display:<?= $editData ? 'block' : 'none'; ?>;">

    <div class="title-pill brand-font">
        <span class="screw left"></span>

        <span id="form-title-text">
            <?= $editData ? 'EDIT LOCATION' : 'ADD NEW LOCATION'; ?>
        </span>

        <span class="screw right"></span>
    </div>

    <form class="admin-form" method="POST">

        <input
            type="hidden"
            name="action"
            value="<?= $editData ? 'update' : 'create'; ?>">

        <?php if ($editData): ?>
            <input
                type="hidden"
                name="id"
                value="<?= $editData['id']; ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>NAME</label>
            <input
                type="text"
                name="name"
                class="input-line"
                required
                value="<?= htmlspecialchars($editData['name'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>LOCATION TYPE</label>
            <input
                type="text"
                name="type"
                class="input-line"
                required
                value="<?= htmlspecialchars($editData['location_type'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>DANGEROUS?</label>
            <input
                type="text"
                name="dangerous"
                class="input-line"
                value="<?= htmlspecialchars($editData['is_dangerous'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>FIRST APPEARANCE</label>
            <input
                type="text"
                name="appearance"
                class="input-line"
                value="<?= htmlspecialchars($editData['first_appearance'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>DESCRIPTION</label>
            <textarea
                name="description"
                class="input-box"><?= htmlspecialchars($editData['description'] ?? '') ?></textarea>
        </div>

        <div class="form-buttons">

            <button
                type="submit"
                class="btn-submit">
                SUBMIT
            </button>

            <button
                type="button"
                class="btn-cancel"
                onclick="hideForm()">
                CANCEL
            </button>

        </div>

    </form>

</div>

</main>
</div>

<script>

function toggleSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    sidebar.classList.toggle('expanded');
}

function toggleAccountDropdown(event) {
    event.stopPropagation();
    document.getElementById('accountDropdown').classList.toggle('show');
}

window.addEventListener('click', function () {
    document.getElementById('accountDropdown').classList.remove('show');
});

function showForm(mode, name='', type='', dangerous='', appearance='', desc='', id='') {

    if (mode === 'add') {
        window.location = "locations.php";
    } else {
        window.location = "locations.php?edit=" + id;
    }

}

function hideForm() {
    window.location = "locations.php";
}

</script>

</body>
</html>