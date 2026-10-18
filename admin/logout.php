<?php
// Memulai session
session_start();

// Menghapus semua variabel session
$_SESSION = array();

// Menghapus cookie session dari browser jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Menghancurkan session sepenuhnya
session_destroy();

// Mengarahkan kembali ke halaman login (sesuaikan nama file login Anda jika berbeda)
header("Location: ../auth/login.php");
exit;
?>