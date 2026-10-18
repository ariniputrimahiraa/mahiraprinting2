<?php
session_start();
require_once __DIR__ . '/../config/database.php'; // Sesuaikan path database Anda

// Cek apakah admin sudah login
if (isset($_SESSION['id_admin'])) {
    // Jika sudah login, langsung tarik/lempar ke dashboard
    header("Location: dashboard.php");
    exit;
} else {
    // Jika belum login, arahkan ke halaman login
    header("Location: /../auth/login.php");
    exit;
}
?>