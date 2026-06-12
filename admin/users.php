<?php
session_start();

require_once '../config/db.php';
require_once '../functions/helpers.php';

requireLogin('../auth/login.php');
requireAdmin('../index.php');


$flash = getFlash();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['action']) && $_POST['action'] == 'update_role') {

        $user_id = (int)$_POST['user_id'];
        $role    = $_POST['role'];

        if ($user_id == $_SESSION['user_id']) {

            setFlash('error','Tidak dapat mengubah role akun sendiri.');

        } else {

            $stmt = $pdo->prepare("
                UPDATE users
                SET role=?
                WHERE id=?
            ");

            $stmt->execute([
                $role,
                $user_id
            ]);

            setFlash('success','Role berhasil diperbarui.');

        }

        header("Location: users.php");
        exit;
    }


    if(isset($_POST['action']) && $_POST['action']=='delete_user'){

        $user_id=(int)$_POST['user_id'];

        if($user_id==$_SESSION['user_id']){

            setFlash('error','Tidak dapat menghapus akun sendiri.');

        }else{

            $stmt=$pdo->prepare("
                DELETE FROM users
                WHERE id=?
            ");

            $stmt->execute([$user_id]);

            setFlash('success','User berhasil dihapus.');

        }

        header("Location: users.php");
        exit;

    }

}

$stmt=$pdo->query("
SELECT
id,
username,
email,
role,
created_at
FROM users
ORDER BY id ASC
");

$users=$stmt->fetchAll(PDO::FETCH_ASSOC);


$roleLabel=[
    'admin'=>'Admin',
    'contributor'=>'Contributor',
    'viewer'=>'Viewer'
];

?>
<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Dashboard Admin - Users</title>

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<link rel="stylesheet"
href="../assets/css/dashboard.css">

</head>

<body>

<div class="admin-layout">

    <aside class="admin-sidebar" id="adminSidebar" onclick="toggleSidebar()">

        <div class="sidebar-logo">
            <img src="../assets/logogravity.png" alt="Logo">
            <span class="brand-font logo-text">GRAVITY FALLS</span>
        </div>

        <ul class="sidebar-menu">

            <li class="menu-item">
                <a href="dashboard.php">
                    <i class="fa-solid fa-house"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            <li class="menu-item active">
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
            alt="Logo"
            class="bg-logo-top">

        <div class="title-pill brand-font">
            <span class="screw left"></span>
            USERS
            <span class="screw right"></span>
        </div>

        <?php if($flash): ?>

            <div class="alert <?= $flash['type']; ?>">

                <?= htmlspecialchars($flash['message']); ?>

            </div>

        <?php endif; ?>

        <table class="data-table">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>USERNAME</th>
                    <th>ROLE</th>
                    <th>CREATED</th>
                    <th>ACTION</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach($users as $user): ?>

                <tr>

                    <td><?= $user['id']; ?></td>

                    <td>
                        <?= htmlspecialchars($user['username']); ?>
                    </td>

                    <td>

                        <form method="POST">

                            <input
                                type="hidden"
                                name="action"
                                value="update_role">

                            <input
                                type="hidden"
                                name="user_id"
                                value="<?= $user['id']; ?>">

                            <select
                                name="role"
                                onchange="this.form.submit()">

                                <option
                                    value="admin"
                                    <?= $user['role']=='admin' ? 'selected' : ''; ?>>
                                    Admin
                                </option>

                                <option
                                    value="contributor"
                                    <?= $user['role']=='contributor' ? 'selected' : ''; ?>>
                                    Contributor
                                </option>

                                <option
                                    value="viewer"
                                    <?= $user['role']=='viewer' ? 'selected' : ''; ?>>
                                    Viewer
                                </option>

                            </select>

                        </form>

                    </td>

                    <td>
                        <?= $user['created_at'] ?? '-'; ?>
                    </td>

                    <td>

                        <?php if($user['id'] != $_SESSION['user_id']): ?>

                        <form
                            method="POST"
                            style="display:inline;"
                            onsubmit="return confirm('Hapus user ini?');">

                            <input
                                type="hidden"
                                name="action"
                                value="delete_user">

                            <input
                                type="hidden"
                                name="user_id"
                                value="<?= $user['id']; ?>">

                            <button
                                type="submit"
                                class="btn-delete">

                                <i class="fa-solid fa-trash"></i>
                                DELETE

                            </button>

                        </form>

                        <?php else: ?>

                            <span style="color:#ccc;">
                                Current User
                            </span>

                        <?php endif; ?>

                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </main>

</div>

<script>

function toggleSidebar() {
    document
        .getElementById('adminSidebar')
        .classList
        .toggle('expanded');
}

function toggleAccountDropdown(event) {

    event.stopPropagation();

    document
        .getElementById('accountDropdown')
        .classList
        .toggle('show');
}

window.addEventListener('click', function() {

    const dropdown =
        document.getElementById('accountDropdown');

    if(dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
    }

});

</script>

</body>
</html>