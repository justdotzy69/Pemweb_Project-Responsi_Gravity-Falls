<?php
// FUNCTION 1: Cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// FUNCTION 2: Cek apakah user adalah admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// FUNCTION 3: Cek apakah user adalah contributor
function isContributor() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'contributor';
}

// FUNCTION 4: Cek apakah user adalah viewer
function isViewer() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'viewer';
}

// FUNCTION 5: Cek apakah user bisa edit
function canEdit() {
    return isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'contributor']);
}

// FUNCTION 6: Proteksi halaman wajib login
function requireLogin($redirect = '../auth/login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirect");
        exit();
    }
}

// FUNCTION 7: Proteksi halaman wajib admin
function requireAdmin($redirect = '../index.php') {
    if (!isAdmin()) {
        header("Location: $redirect");
        exit();
    }
}

// FUNCTION 8: Sanitasi input
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

// FUNCTION 9: Format tanggal Indonesia
function formatDate($date) {
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April',
        'Mei', 'Juni', 'Juli', 'Agustus',
        'September', 'Oktober', 'November', 'Desember'
    ];
    $timestamp = strtotime($date);
    return date('d', $timestamp) . ' ' .
           $bulan[(int)date('m', $timestamp)] . ' ' .
           date('Y', $timestamp);
}

// FUNCTION 10: Simpan flash message
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

// FUNCTION 11: Ambil flash message
function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// FUNCTION 12: Redirect dengan pesan
function redirectWith($url, $type, $message) {
    setFlash($type, $message);
    header("Location: $url");
    exit();
}
?>