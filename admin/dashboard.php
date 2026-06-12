<?php
session_start();

require_once "../config/db.php";
require_once "../functions/helpers.php";

requireLogin("../auth/login.php");
requireAdmin("../index.php");

$flash = getFlash();

try {

    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    $totalCharacters = $pdo->query("SELECT COUNT(*) FROM characters")->fetchColumn();

    $totalJournal = $pdo->query("SELECT COUNT(*) FROM journal")->fetchColumn();

    $totalLocations = $pdo->query("SELECT COUNT(*) FROM locations")->fetchColumn();

} catch (PDOException $e) {

    die("Database Error : " . $e->getMessage());

}
?>

<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" href="../assets/css/dashboard.css">

</head>
<body>

<div class="admin-layout">

    <!-- SIDEBAR -->

    <aside class="admin-sidebar" id="adminSidebar" onclick="toggleSidebar()">

        <div class="sidebar-logo">

            <img src="../assets/logogravity.png" alt="Logo">

            <span class="brand-font logo-text">
                GRAVITY FALLS
            </span>

        </div>

        <ul class="sidebar-menu">

            <li class="menu-item active">
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

    <!-- MAIN -->

    <main class="admin-main">

        <img
            src="../assets/logogravity.png"
            alt="Logo Besar"
            class="bg-logo-top">

        <div class="title-pill brand-font">

            <span class="screw left"></span>

            DASHBOARD

            <span class="screw right"></span>

        </div>

        <?php if($flash): ?>

            <div style="
                background:#d4edda;
                color:#155724;
                padding:12px 18px;
                border-radius:8px;
                margin-bottom:25px;
                width:fit-content;
                font-weight:bold;">

                <?= htmlspecialchars($flash['message']) ?>

            </div>

        <?php endif; ?>

        <div class="stats-container">

            <div class="stat-row">

                <div class="stat-text">

                    Total registered users in the system.

                </div>

                <div class="stat-num brand-font">

                    <?= $totalUsers ?>

                </div>

                <a href="users.php" class="stat-btn">

                    VIEW USERS

                </a>

            </div>

            <div class="stat-row">

                <div class="stat-text">

                    Total Gravity Falls characters.

                </div>

                <div class="stat-num brand-font">

                    <?= $totalCharacters ?>

                </div>

                <a href="characters.php" class="stat-btn">

                    VIEW CHARACTERS

                </a>

            </div>

            <div class="stat-row">

                <div class="stat-text">

                    Total journal entries.

                </div>

                <div class="stat-num brand-font">

                    <?= $totalJournal ?>

                </div>

                <a href="journal.php" class="stat-btn">

                    VIEW JOURNAL

                </a>

            </div>

            <div class="stat-row">

                <div class="stat-text">

                    Total available locations.

                </div>

                <div class="stat-num brand-font">

                    <?= $totalLocations ?>

                </div>

                <a href="locations.php" class="stat-btn">

                    VIEW LOCATIONS

                </a>

            </div>

        </div>

    </main>

</div>

<script>

function toggleSidebar(){

    const sidebar = document.getElementById("adminSidebar");

    sidebar.classList.toggle("expanded");

}

function toggleAccountDropdown(event){

    event.stopPropagation();

    document
        .getElementById("accountDropdown")
        .classList
        .toggle("show");

}

window.addEventListener("click",function(){

    const dropdown = document.getElementById("accountDropdown");

    if(dropdown.classList.contains("show")){

        dropdown.classList.remove("show");

    }

});

</script>

</body>
</html>