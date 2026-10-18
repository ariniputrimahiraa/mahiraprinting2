<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit;
}

$pesan = "";
$jenis_pesan = "";

// PROSES TAMBAH PRODUK
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'tambah_produk') {
        $nama_produk         = isset($_POST['nama_produk']) ? trim($_POST['nama_produk']) : "";
        $kategori            = isset($_POST['kategori']) ? trim($_POST['kategori']) : "";
        $deskripsi           = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : "";
        $spesifikasi         = isset($_POST['spesifikasi']) ? trim($_POST['spesifikasi']) : "";
        $estimasi_pengerjaan = isset($_POST['estimasi_pengerjaan']) ? trim($_POST['estimasi_pengerjaan']) : "";
        $minimal_order       = isset($_POST['minimal_order']) ? trim($_POST['minimal_order']) : "";
        $status              = isset($_POST['status']) ? trim($_POST['status']) : "aktif";
        
        $gambar_nama = "";

        // Validasi input wajib
        if ($nama_produk == "") {
            $pesan = "Nama produk wajib diisi!";
            $jenis_pesan = "error";
        } else {
            // Proses Upload Gambar
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath   = $_FILES['gambar']['tmp_name'];
                $fileName      = $_FILES['gambar']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($fileExtension, $allowedExtensions)) {
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $uploadFileDir = __DIR__ . '/../uploads/';
                    
                    if (!is_dir($uploadFileDir)) {
                        mkdir($uploadFileDir, 0755, true);
                    }
                    
                    $dest_path = $uploadFileDir . $newFileName;
                    if (move_uploaded_file($fileTmpPath, $dest_path)) {
                        $gambar_nama = $newFileName;
                    } else {
                        $pesan = "Terjadi kesalahan saat mengunggah file gambar.";
                        $jenis_pesan = "error";
                    }
                } else {
                    $pesan = "Format gambar tidak valid! Gunakan JPG, JPEG, PNG, atau WEBP.";
                    $jenis_pesan = "error";
                }
            }

            if ($jenis_pesan != "error") {
                try {
                    if (isset($pdo)) {
                        $stmt = $pdo->prepare("INSERT INTO produk (nama_produk, kategori, gambar, deskripsi, spesifikasi, estimasi_pengerjaan, minimal_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->execute([$nama_produk, $kategori, $gambar_nama, $deskripsi, $spesifikasi, $estimasi_pengerjaan, $minimal_order, $status]);
                    } elseif (isset($koneksi)) {
                        $stmt = $koneksi->prepare("INSERT INTO produk (nama_produk, kategori, gambar, deskripsi, spesifikasi, estimasi_pengerjaan, minimal_order, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssssssss", $nama_produk, $kategori, $gambar_nama, $deskripsi, $spesifikasi, $estimasi_pengerjaan, $minimal_order, $status);
                        $stmt->execute();
                    }
                    $pesan = "Produk berhasil ditambahkan dan terhubung ke katalog publik!";
                    $jenis_pesan = "success";
                } catch (Exception $e) {
                    $pesan = "Gagal menambahkan produk ke database: " . $e->getMessage();
                    $jenis_pesan = "error";
                }
            }
        }
    }
}

// LOGIKA SEARCH BY KATEGORI, NAMA PRODUK (MINIMAL 3 HURUF), & PAGINATION (LIMIT 5)
$search_nama = isset($_GET['search_nama']) ? trim($_GET['search_nama']) : '';
$search_kategori = isset($_GET['search_kategori']) ? trim($_GET['search_kategori']) : '';

// Ambil Halaman Sekarang (Pagination)
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Menyiapkan Query SQL
$where_clauses = array();
$params = array();
$types = "";

// Fitur Search 3 Huruf Awal / Minimal 3 Karakter
if (strlen($search_nama) >= 3) {
    $where_clauses[] = "nama_produk LIKE ?";
    $params[] = '%' . $search_nama . '%';
    $types .= "s";
}

if ($search_kategori != "" && $search_kategori != "Semua Kategori") {
    $where_clauses[] = "kategori = ?";
    $params[] = $search_kategori;
    $types .= "s";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = " WHERE " . implode(" AND ", $where_clauses);
}

// Hitung Total Data untuk Pagination
$total_data = 0;
$produk_list = array();

try {
    if (isset($pdo)) {
        // Count total
        $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM produk" . $where_sql);
        $stmt_count->execute($params);
        $total_data = $stmt_count->fetchColumn();

        // Query Data dengan Limit dan Offset
        $sql = "SELECT * FROM produk" . $where_sql . " ORDER BY id_produk DESC LIMIT $limit OFFSET $offset";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $produk_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } elseif (isset($koneksi)) {
        // Count total
        $sql_count = "SELECT COUNT(*) as total FROM produk" . $where_sql;
        if (!empty($params)) {
            $stmt_count = $koneksi->prepare($sql_count);
            $stmt_count->bind_param($types, ...$params);
            $stmt_count->execute();
            $res_count = $stmt_count->get_result()->fetch_assoc();
            $total_data = $res_count['total'];
        } else {
            $res_count = $koneksi->query($sql_count)->fetch_assoc();
            $total_data = $res_count['total'];
        }

        // Query Data dengan Limit dan Offset
        $sql = "SELECT * FROM produk" . $where_sql . " ORDER BY id_produk DESC LIMIT $limit OFFSET $offset";
        if (!empty($params)) {
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $produk_list[] = $row;
            }
        } else {
            $res = $koneksi->query($sql);
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $produk_list[] = $row;
                }
            }
        }
    }
} catch (Exception $e) {
    // Tangani jika tabel belum ada
}

$total_pages = ceil($total_data / $limit);

// Inisial avatar admin
$nama_admin = isset($_SESSION['nama_admin']) ? $_SESSION['nama_admin'] : 'Admin User';
$initialAvatar = !empty($nama_admin) ? strtoupper(substr($nama_admin, 0, 1)) : 'A';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - Mahira Printing</title>

    <!-- FontAwesome & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-main: #f5f3eb;
            --sidebar-bg: #0d0d0d;
            --brand-yellow: #facc15;
            --brand-yellow-hover: #eab308;
            --text-dark: #111827;
            --text-muted: #6b7280;
            --card-bg: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR STYLE --- */
        .sidebar {
            width: 250px;
            background: var(--sidebar-bg);
            color: #fff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            transition: all 0.3s ease;
            z-index: 100;
        }

        .sidebar.collapsed { width: 75px; }

        .brand {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 38px;
            height: 38px;
            background: var(--brand-yellow);
            color: #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .brand-text {
            font-weight: 800;
            font-size: 1.1rem;
            letter-spacing: 1px;
            color: var(--brand-yellow);
            line-height: 1.1;
        }

        .brand-sub {
            font-size: 0.65rem;
            color: #aaa;
            letter-spacing: 2px;
            display: block;
        }

        .menu-section {
            padding: 10px 20px 5px;
            font-size: 0.68rem;
            font-weight: 700;
            color: #52525b;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .menu {
            list-style: none;
            padding: 10px 14px;
            flex: 1;
        }

        .menu li { margin-bottom: 6px; }

        .menu li a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            color: #a1a1aa;
            text-decoration: none;
            font-size: 0.92rem;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .menu li a:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .menu li.active a {
            background: var(--brand-yellow);
            color: #000;
            font-weight: 700;
            box-shadow: 0 4px 15px rgba(250, 204, 21, 0.25);
        }

        .sidebar.collapsed .text,
        .sidebar.collapsed .brand-details,
        .sidebar.collapsed .menu-section { display: none; }

        .sidebar.collapsed .menu li a { justify-content: center; padding: 12px; }

        /* --- MAIN CONTENT AREA --- */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* TOPBAR */
        .topbar {
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .toggle-btn {
            cursor: pointer;
            font-size: 1.2rem;
            color: #4b5563;
            background: #fff;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
            transition: all 0.2s;
        }

        .toggle-btn:hover { background: #f3f4f6; }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notif-btn {
            position: relative;
            cursor: pointer;
            font-size: 1.2rem;
            color: #333;
        }

        .notif-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
        }

        /* PROFILE POPUP */
        .profile-container { position: relative; }

        .profile-avatar {
            width: 42px;
            height: 42px;
            background: var(--brand-yellow);
            color: #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            user-select: none;
        }

        .profile-dropdown {
            position: absolute;
            top: 52px;
            right: 0;
            width: 260px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
            border: 1px solid #f0f0f0;
            padding: 12px;
            display: none;
            flex-direction: column;
            z-index: 1000;
        }

        .profile-dropdown.show { display: flex; }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 8px 12px;
            border-bottom: 1px solid #f3f4f6;
        }

        .profile-header-icon {
            width: 36px;
            height: 36px;
            background: #f3f4f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4b5563;
        }

        .profile-info h4 { font-size: 0.9rem; font-weight: 700; }
        .profile-info p { font-size: 0.75rem; color: var(--text-muted); }

        .profile-menu { list-style: none; padding-top: 8px; }

        .profile-menu li a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            text-decoration: none;
            color: #374151;
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 8px;
        }

        .profile-menu li a:hover { background: #f9fafb; }
        .profile-menu li a.logout { color: #ef4444; background: #fef2f2; margin-top: 4px; }

        /* DASHBOARD CONTAINER */
        .container { padding: 10px 32px 32px; }

        /* HERO BANNER */
        .hero-banner {
            background: #0d0d0d;
            color: #fff;
            padding: 24px 28px;
            border-radius: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .hero-banner .label {
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--brand-yellow);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .hero-banner h2 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-top: 2px;
        }

        .hero-banner p {
            font-size: 0.82rem;
            color: #a1a1aa;
            margin-top: 4px;
        }

        .btn-add {
            background: var(--brand-yellow);
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-add:hover {
            background: var(--brand-yellow-hover);
            transform: translateY(-1px);
        }

        /* --- KOTAK SEARCH BLACK CARD (SESUAI GAMBAR) --- */
        .search-card-black {
            background: #000;
            color: #fff;
            padding: 24px;
            border-radius: 16px;
            margin-bottom: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .search-card-black .form-group-search {
            margin-bottom: 16px;
        }

        .search-card-black label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: #e5e7eb;
            margin-bottom: 8px;
        }

        .search-card-black input[type="text"],
        .search-card-black select {
            width: 100%;
            background: #e5e7eb;
            border: none;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.88rem;
            color: #111827;
            outline: none;
            font-weight: 500;
        }

        .search-card-black select {
            cursor: pointer;
            appearance: auto;
        }

        .search-card-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .btn-search-submit {
            background: var(--brand-yellow);
            color: #000;
            border: none;
            padding: 10px 32px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-search-submit:hover { background: var(--brand-yellow-hover); }

        .btn-search-reset {
            background: #fff;
            color: #000;
            border: none;
            padding: 10px 32px;
            border-radius: 8px;
            font-weight: 800;
            font-size: 0.88rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-search-reset:hover { background: #e5e7eb; }

        /* TABLE CONTAINER (YELLOW CARD) */
        .panel-outer {
            background: var(--brand-yellow);
            padding: 24px;
            border-radius: 20px;
        }

        .panel-outer-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: #000;
            margin-bottom: 16px;
        }

        .panel-inner {
            background: #fff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        /* TABLE DESIGN */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            font-size: 0.75rem;
            font-weight: 800;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 2px solid #f3f4f6;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.88rem;
            color: #374151;
            font-weight: 500;
            vertical-align: middle;
        }

        tbody tr:hover { background-color: #fafafa; }

        .product-thumb {
            width: 44px;
            height: 44px;
            object-fit: cover;
            background: #fef08a;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #854d0e;
        }

        .badge-status {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
        }
        .badge-aktif { background: #dcfce7; color: #15803d; }
        .badge-nonaktif { background: #fee2e2; color: #b91c1c; }

        /* ACTION BUTTONS */
        .action-btns {
            display: flex;
            gap: 6px;
        }

        .btn-edit {
            background: var(--brand-yellow);
            color: #000;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.78rem;
            cursor: pointer;
        }

        .btn-delete {
            background: #000;
            color: #fff;
            border: none;
            padding: 6px 14px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.78rem;
            cursor: pointer;
        }

        .btn-edit:hover { opacity: 0.9; }
        .btn-delete:hover { background: #262626; }

        /* PAGINATION STYLING */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 14px;
            border-top: 1px solid #f3f4f6;
        }

        .pagination-info {
            font-size: 0.82rem;
            color: #6b7280;
            font-weight: 600;
        }

        .pagination-list {
            display: flex;
            gap: 6px;
            list-style: none;
        }

        .pagination-list a, .pagination-list span {
            padding: 8px 14px;
            font-size: 0.82rem;
            font-weight: 700;
            border-radius: 8px;
            text-decoration: none;
            color: #111827;
            background: #f3f4f6;
            transition: all 0.2s;
        }

        .pagination-list a:hover {
            background: var(--brand-yellow);
            color: #000;
        }

        .pagination-list .active {
            background: #000;
            color: #fff;
        }

        .pagination-list .disabled {
            opacity: 0.4;
            cursor: not-allowed;
            pointer-events: none;
        }

        /* MODAL STYLE */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
            padding: 20px;
            z-index: 1000;
            backdrop-filter: blur(2px);
        }

        .modal.show { display: flex; }

        .modal-content {
            background: #fff;
            width: 100%;
            max-width: 500px;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-content h3 { font-size: 1.2rem; font-weight: 800; margin-bottom: 4px; }
        .modal-description { font-size: 0.82rem; color: var(--text-muted); margin-bottom: 20px; }

        .form-group { margin-bottom: 16px; }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: #374151;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-size: 0.88rem;
        }

        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #000;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
        }

        .btn-close {
            background: #f3f4f6;
            color: #374151;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.88rem;
            font-weight: 600;
        }
        .alert.success { background: #dcfce7; color: #15803d; }
        .alert.error { background: #fee2e2; color: #b91c1c; }

        @media (max-width: 768px) {
            .sidebar { width: 75px; }
            .sidebar .text, .sidebar .brand-details, .sidebar .menu-section { display: none; }
            .sidebar .menu li a { justify-content: center; padding: 12px; }
            .container { padding: 16px; }
            .hero-banner { flex-direction: column; align-items: flex-start; gap: 14px; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-logo">M</div>
            <div class="brand-details">
                <span class="brand-text">MAHIRA</span>
                <span class="brand-sub">PRINTING</span>
            </div>
        </div>

        <div class="menu-section">Menu Utama</div>

        <ul class="menu">
            <li><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i><span class="text">Beranda</span></a></li>
            <li><a href="kelola_admin.php"><i class="fa-solid fa-user-gear"></i><span class="text">Kelola Admin</span></a></li>
            <li class="active"><a href="produk.php"><i class="fa-solid fa-box"></i><span class="text">Produk</span></a></li>
            <li><a href="jasa.php"><i class="fa-solid fa-user-tag"></i><span class="text">Jasa</span></a></li>
            <li><a href="portofolio.php"><i class="fa-solid fa-image"></i><span class="text">Portofolio</span></a></li>
            <li><a href="review.php"><i class="fa-solid fa-star"></i><span class="text">Review</span></a></li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- TOPBAR -->
        <header class="topbar">
            <div class="topbar-left">
                <div class="toggle-btn" id="toggleSidebar">
                    <i class="fa-solid fa-bars"></i>
                </div>
            </div>

            <div class="topbar-right">
                <div class="notif-btn">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notif-badge"></span>
                </div>

                <!-- PROFILE AVATAR -->
                <div class="profile-container">
                    <div class="profile-avatar" id="profileAvatar"><?php echo $initialAvatar; ?></div>

                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="profile-header">
                            <div class="profile-header-icon">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="profile-info">
                                <h4><?php echo htmlspecialchars($nama_admin); ?></h4>
                                <p>Mahira Printing Admin</p>
                            </div>
                        </div>
                        <ul class="profile-menu">
                            <li><a href="account.php"><i class="fa-regular fa-id-badge"></i> My Account</a></li>
                            <li><a href="settings.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
                            <li><a href="logout.php" class="logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- CONTAINER -->
        <div class="container">

            <?php if ($pesan != ""): ?>
                <div class="alert <?php echo htmlspecialchars($jenis_pesan); ?>" id="notif">
                    <?php echo htmlspecialchars($pesan); ?>
                </div>
            <?php endif; ?>

            <!-- HERO BANNER -->
            <div class="hero-banner">
                <div>
                    <span class="label">Katalog Mahira Printing</span>
                    <h2>Kelola Produk</h2>
                    <p>Tambahkan dan kelola produk yang ditampilkan di website.</p>
                </div>
                <button type="button" class="btn-add" id="openModalBtn">
                    + Tambah Produk
                </button>
            </div>

            <!-- FITUR SEARCH & KATEGORI (HITAM SESUAI CONTOH GAMBAR) -->
            <div class="search-card-black">
                <form method="GET" action="produk.php" id="searchForm">
                    <div class="form-group-search">
                        <label for="search_nama">Cari Produk</label>
                        <input type="text" id="search_nama" name="search_nama" placeholder="Masukkan nama produk (min. 3 huruf)" value="<?php echo htmlspecialchars($search_nama); ?>">
                        <small id="searchWarn" style="color: #facc15; font-size: 0.72rem; display: none; margin-top: 4px;">Pencarian nama membutuhkan minimal 3 huruf/karakter.</small>
                    </div>

                    <div class="form-group-search">
                        <label for="search_kategori">Kategori</label>
                        <select id="search_kategori" name="search_kategori">
                            <option value="Semua Kategori" <?php echo ($search_kategori == '' || $search_kategori == 'Semua Kategori') ? 'selected' : ''; ?>>Semua Kategori</option>
                            <option value="Textile & Sablon" <?php echo ($search_kategori == 'Textile & Sablon') ? 'selected' : ''; ?>>Textile & Sablon</option>
                            <option value="Custom & Souvenir" <?php echo ($search_kategori == 'Custom & Souvenir') ? 'selected' : ''; ?>>Custom & Souvenir</option>
                            <option value="Kertas & Desain" <?php echo ($search_kategori == 'Kertas & Desain') ? 'selected' : ''; ?>>Kertas & Desain</option>
                        </select>
                    </div>

                    <div class="search-card-actions">
                        <button type="submit" class="btn-search-submit">Cari</button>
                        <a href="produk.php" class="btn-search-reset">Reset</a>
                    </div>
                </form>
            </div>

            <!-- TABLE PANEL (YELLOW CONTAINER) -->
            <div class="panel-outer">
                <div class="panel-outer-title">Daftar Produk</div>

                <div class="panel-inner">
                    <div class="table-wrapper">
                        <table id="dataTable">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>GAMBAR</th>
                                    <th>NAMA PRODUK</th>
                                    <th>KATEGORI</th>
                                    <th>ESTIMASI</th>
                                    <th>MIN. ORDER</th>
                                    <th>STATUS</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($produk_list) > 0): ?>
                                    <?php foreach ($produk_list as $index => $item): ?>
                                        <tr>
                                            <td><?php echo $offset + $index + 1; ?></td>
                                            <td>
                                                <?php if (!empty($item['gambar']) && file_exists(__DIR__ . '/../uploads/' . $item['gambar'])): ?>
                                                    <img src="../uploads/<?php echo htmlspecialchars($item['gambar']); ?>" alt="Produk" class="product-thumb">
                                                <?php else: ?>
                                                    <div class="product-thumb">
                                                        <i class="fa-solid fa-box-archive"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td style="font-weight: 700; color: #000;">
                                                <?php echo htmlspecialchars(isset($item['nama_produk']) ? $item['nama_produk'] : 'Produk'); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars(isset($item['kategori']) && !empty($item['kategori']) ? $item['kategori'] : '-'); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars(isset($item['estimasi_pengerjaan']) ? $item['estimasi_pengerjaan'] : '-'); ?></td>
                                            <td><?php echo htmlspecialchars(isset($item['minimal_order']) ? $item['minimal_order'] : '-'); ?></td>
                                            <td>
                                                <?php $statusVal = isset($item['status']) ? $item['status'] : 'aktif'; ?>
                                                <span class="badge-status <?php echo ($statusVal == 'aktif') ? 'badge-aktif' : 'badge-nonaktif'; ?>">
                                                    <?php echo ucfirst($statusVal); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-btns">
                                                    <button class="btn-edit">Edit</button>
                                                    <button class="btn-delete" onclick="deleteRow(this)">Hapus</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" style="text-align: center; color: #9ca3af; padding: 24px;">
                                            Belum ada data produk atau tidak ada hasil pencarian yang cocok.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- FITUR PAGINATION (PREVIOUS & NEXT PAGE - MAX 5 PRODUK PER HALAMAN) -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination-container">
                            <div class="pagination-info">
                                Menampilkan <?php echo count($produk_list); ?> dari total <?php echo $total_data; ?> produk
                            </div>
                            <ul class="pagination-list">
                                <!-- Tombol Previous Page -->
                                <?php if ($page > 1): ?>
                                    <li><a href="?page=<?php echo $page - 1; ?>&search_nama=<?php echo urlencode($search_nama); ?>&search_kategori=<?php echo urlencode($search_kategori); ?>"><i class="fa-solid fa-angle-left"></i> Previous</a></li>
                                <?php else: ?>
                                    <li><span class="disabled"><i class="fa-solid fa-angle-left"></i> Previous</span></li>
                                <?php endif; ?>

                                <!-- Angka Halaman -->
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <?php if ($i == $page): ?>
                                        <li><span class="active"><?php echo $i; ?></span></li>
                                    <?php else: ?>
                                        <li><a href="?page=<?php echo $i; ?>&search_nama=<?php echo urlencode($search_nama); ?>&search_kategori=<?php echo urlencode($search_kategori); ?>"><?php echo $i; ?></a></li>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <!-- Tombol Next Page -->
                                <?php if ($page < $total_pages): ?>
                                    <li><a href="?page=<?php echo $page + 1; ?>&search_nama=<?php echo urlencode($search_nama); ?>&search_kategori=<?php echo urlencode($search_kategori); ?>">Next <i class="fa-solid fa-angle-right"></i></a></li>
                                <?php else: ?>
                                    <li><span class="disabled">Next <i class="fa-solid fa-angle-right"></i></span></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </main>

    <!-- MODAL TAMBAH PRODUK -->
    <div class="modal" id="productModal">
        <div class="modal-content">
            <h3>Tambah Produk Baru</h3>
            <p class="modal-description">Isi rincian produk yang ingin ditampilkan ke halaman publik.</p>

            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" value="tambah_produk">

                <div class="form-group">
                    <label for="nama_produk">Nama Produk</label>
                    <input type="text" id="nama_produk" name="nama_produk" placeholder="Contoh: Mug Custom Premium" required>
                </div>

                <div class="form-group">
                    <label for="kategori_tambah">Kategori Produk</label>
                    <select id="kategori_tambah" name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Textile & Sablon">Textile & Sablon</option>
                        <option value="Custom & Souvenir">Custom & Souvenir</option>
                        <option value="Kertas & Desain">Kertas & Desain</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="gambar">Upload Gambar Produk</label>
                    <input type="file" id="gambar" name="gambar" accept="image/*" required>
                    <small style="color: var(--text-muted); font-size: 0.75rem;">Format yang didukung: JPG, JPEG, PNG, WEBP</small>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label for="estimasi_pengerjaan">Estimasi Pengerjaan</label>
                        <input type="text" id="estimasi_pengerjaan" name="estimasi_pengerjaan" placeholder="Contoh: 1-2 Hari">
                    </div>
                    <div class="form-group">
                        <label for="minimal_order">Minimal Order</label>
                        <input type="text" id="minimal_order" name="minimal_order" placeholder="Contoh: 1 Pcs">
                    </div>
                </div>

                <div class="form-group">
                    <label for="spesifikasi">Spesifikasi</label>
                    <textarea id="spesifikasi" name="spesifikasi" rows="2" placeholder="Spesifikasi bahan, ukuran, dll..."></textarea>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi Singkat</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Jelaskan detail produk..."></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-close" id="closeModalBtn">Batal</button>
                    <button type="submit" class="btn-add">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT UTAMA -->
    <script>
        // Sidebar Toggle
        const sidebar = id('sidebar');
        const toggleBtn = id('toggleSidebar');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
            });
        }

        // Profile Dropdown Toggle
        const profileAvatar = id('profileAvatar');
        const profileDropdown = id('profileDropdown');
        if (profileAvatar) {
            profileAvatar.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
            });
        }
        document.addEventListener('click', () => {
            if (profileDropdown && profileDropdown.classList.contains('show')) {
                profileDropdown.classList.remove('show');
            }
        });

        // Modal Handler
        const productModal = id('productModal');
        const openModalBtn = id('openModalBtn');
        const closeModalBtn = id('closeModalBtn');

        if (openModalBtn) {
            openModalBtn.addEventListener('click', () => {
                productModal.classList.add('show');
            });
        }

        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', () => {
                productModal.classList.remove('show');
            });
        }

        // Validasi JavaScript: Minimal 3 Huruf sebelum Submit Search
        const searchForm = id('searchForm');
        const searchInput = id('search_nama');
        const searchWarn = id('searchWarn');

        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const val = searchInput.value.trim();
                if (val.length > 0 && val.length < 3) {
                    e.preventDefault();
                    searchWarn.style.display = 'block';
                } else {
                    searchWarn.style.display = 'none';
                }
            });
        }

        function id(elementId) {
            return document.getElementById(elementId);
        }

        function deleteRow(btn) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                const row = btn.closest('tr');
                row.remove();
            }
        }
    </script>
</body>
</html>