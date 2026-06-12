<?php
session_start();
require_once "config/db.php";
require_once "functions/helpers.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Gravity Falls</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/home.css">
</head>

<body>

<nav class="navbar">
    <a href="index.php" class="nav-logo">
        <img src="assets/logogravity.png" alt="Logo">
    </a>

    <div class="nav-links">
        <a href="index.php" class="nav-item active-nav">
            <i class="fa-solid fa-house"></i> Home
        </a>

        <a href="characters/index.php" class="nav-item">
            <i class="fa-solid fa-users"></i> Character
        </a>

        <a href="journal/index.php" class="nav-item">
            <i class="fa-solid fa-book"></i> Journal
        </a>

        <a href="locations/index.php" class="nav-item">
            <i class="fa-solid fa-map-location-dot"></i> Location
        </a>
    </div>

    <div class="profile-dropdown">

    <button class="nav-profile" onclick="toggleDropdown(event)">
        <i class="fa-regular fa-circle-user"></i>
        <span>Account</span>
        <i class="fa-solid fa-caret-down"></i>
    </button>

    <div class="dropdown-menu" id="dropdownMenu">

        <a href="categories/index.php" class="dropdown-item">
            <i class="fa-solid fa-house"></i>
            Dashboard
        </a>

        <a href="auth/logout.php" class="dropdown-item">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>

    </div>

</div>
</nav>

<header class="hero-section">

    <div class="hero-text">
        Gravity Falls mengikuti petualangan musim panas liburan si kembar Dipper dan Mabel Pines,
        yang dikirim untuk tinggal bersama paman buyut mereka, Grunkle Stan, di sebuah kota terpencil
        bernama Gravity Falls, Oregon. Di balik toko wisata "Mystery Shack" yang penuh dengan barang
        palsu, mereka menemukan bahwa kota ini dipenuhi oleh makhluk gaib, anomali supernatural,
        dan konspirasi gelap.
    </div>

    <div class="hero-logo">
        <img src="assets/logogravity.png" alt="Gravity Falls">
    </div>

</header>

<main class="content-section">

    <div class="section-wrapper" id="character">

        <h2 class="section-title">CHARACTER</h2>

        <div class="character-grid">

            <div class="character-card">
                <div class="character-img">
                    <img src="assets/dipper.png" alt="Dipper">
                </div>
                <h3 class="character-name brand-font">DIPPER</h3>
            </div>

            <div class="character-card">
                <div class="character-img">
                    <img src="assets/stan.png" alt="Stan">
                </div>
                <h3 class="character-name brand-font">STAN</h3>
            </div>

            <div class="character-card">
                <div class="character-img">
                    <img src="assets/mabel.png" alt="Mabel">
                </div>
                <h3 class="character-name brand-font">MABEL</h3>
            </div>

        </div>

        <a href="characters/index.php" class="btn-more">
            See More <i class="fa-solid fa-arrow-right"></i>
        </a>

    </div>

    <div class="section-wrapper" id="journal">

        <h2 class="section-title">JOURNAL</h2>

        <div class="content-row">

            <img src="assets/bukujurnal.jpg" alt="Journal">

            <div class="text-box">
                Jurnal 1, 2, dan 3 ini merupakan buku harian misterius yang ditulis oleh Stanford Pines.
            </div>

        </div>

        <a href="journal/index.php" class="btn-more">
            See More <i class="fa-solid fa-arrow-right"></i>
        </a>

    </div>

    <div class="section-wrapper" id="location">

        <h2 class="section-title">LOCATION</h2>

        <div class="content-row">

            <div class="text-box">
                Gravity Falls ini bukanlah kota kecil yang biasa. Di setiap sudut kotanya menyimpan banyak sekali misteri.
            </div>

            <img src="assets/gravity falls forest.jpg" alt="Location">

        </div>

        <a href="locations/index.php" class="btn-more">
            See More <i class="fa-solid fa-arrow-right"></i>
        </a>

    </div>

</main>

<footer class="footer">

    <div class="footer-left">

        <div class="footer-logo">
            <img src="assets/logogravity.png" alt="Logo">
            <span class="brand-font">Gravity Falls</span>
        </div>

        <div class="footer-copyright">
            © 2026 Gravity Falls UI
        </div>

    </div>

</footer>

<script>
function toggleDropdown(event) {
    event.stopPropagation();
    document.getElementById('dropdownMenu').classList.toggle('show');
}

window.addEventListener('click', function() {
    document.getElementById('dropdownMenu').classList.remove('show');
});
</script>

</body>
</html>