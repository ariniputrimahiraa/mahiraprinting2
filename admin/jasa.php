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

// PROSES TAMBAH JASA
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'tambah_jasa') {
        $nama_jasa   = isset($_POST['nama_jasa']) ? trim($_POST['nama_jasa']) : "";
        $deskripsi   = isset($_POST['deskripsi']) ? trim($_POST['deskripsi']) : "";
        $status      = isset($_POST['status']) ? trim($_POST['status']) : "aktif";
        
        $gambar_nama = "";

        // Validasi input wajib
        if ($nama_jasa == "") {
            $pesan = "Nama jasa wajib diisi!";
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
                        $stmt = $pdo->prepare("INSERT INTO jasa (nama_jasa, gambar, deskripsi, status) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$nama_jasa, $gambar_nama, $deskripsi, $status]);
                    } elseif (isset($koneksi)) {
                        $stmt = $koneksi->prepare("INSERT INTO jasa (nama_jasa, gambar, deskripsi, status) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssss", $nama_jasa, $gambar_nama, $deskripsi, $status);
                        $stmt->execute();
                    }
                    $pesan = "Jasa berhasil ditambahkan!";
                    $jenis_pesan = "success";
                } catch (Exception $e) {
                    $pesan = "Gagal menambahkan jasa ke database: " . $e->getMessage();
                    $jenis_pesan = "error";
                }
            }
        }
    }
}

// LOGIKA SEARCH NAMA JASA (MINIMAL 3 HURUF) & PAGINATION (LIMIT 5)
$search_nama = isset($_GET['search_nama']) ? trim($_GET['search_nama']) : '';

// Ambil Halaman Sekarang (Pagination)
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Menyiapkan Query SQL
$where_clauses = array();
$params = array();
$types = "";

// Fitur Search minimal 3 karakter
if (strlen($search_nama) >= 3) {
    $where_clauses[] = "nama_jasa LIKE ?";
    $params[] = '%' . $search_nama . '%';
    $types .= "s";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = " WHERE " . implode(" AND ", $where_clauses);
}

// Hitung Total Data untuk Pagination
$total_data = 0;
$jasa_list = array();

try {
    if (isset($pdo)) {
        // Count total
        $stmt_count = $pdo->prepare("SELECT COUNT(*) FROM jasa" . $where_sql);
        $stmt_count->execute($params);
        $total_data = $stmt_count->fetchColumn();

        // Query Data dengan Limit dan Offset
        $sql = "SELECT * FROM jasa" . $where_sql . " ORDER BY id_jasa DESC LIMIT $limit OFFSET $offset";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $jasa_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } elseif (isset($koneksi)) {
        // Count total
        $sql_count = "SELECT COUNT(*) as total FROM jasa" . $where_sql;
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
        $sql = "SELECT * FROM jasa" . $where_sql . " ORDER BY id_jasa DESC LIMIT $limit OFFSET $offset";
        if (!empty($params)) {
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $res = $stmt->get_result();
            while ($row = $res->fetch_assoc()) {
                $jasa_list[] = $row;
            }
        } else {
            $res = $koneksi->query($sql);
            if ($res) {
                while ($row = $res->fetch_assoc()) {
                    $jasa_list[] = $row;
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
    <title>Kelola Jasa - Mahira Printing</title>

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

        /* --- KOTAK SEARCH BLACK CARD --- */
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

        .search-card-black input[type="text"] {
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

        .jasa-thumb {
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
            <li><a href="produk.php"><i class="fa-solid fa-box"></i><span class="text">Produk</span></a></li>
            <li class="active"><a href="jasa.php"><i class="fa-solid fa-user-tag"></i><span class="text">Jasa</span></a></li>
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
                    <span class="label">Layanan Mahira Printing</span>
                    <h2>Kelola Jasa</h2>
                    <p>Tambahkan dan kelola jenis layanan jasa yang ditawarkan.</p>
                </div>
                <button type="button" class="btn-add" id="openModalBtn">
                    + Tambah Jasa
                </button>
            </div>

            <!-- FITUR SEARCH (HITAM SESUAI PRODUK) -->
            <div class="search-card-black">
                <form method="GET" action="jasa.php" id="searchForm">
                    <div class="form-group-search">
                        <label for="search_nama">Cari Jasa</label>
                        <input type="text" id="search_nama" name="search_nama" placeholder="Masukkan nama jasa (min. 3 huruf)" value="<?php echo htmlspecialchars($search_nama); ?>">
                        <small id="searchWarn" style="color: #facc15; font-size: 0.72rem; display: none; margin-top: 4px;">Pencarian nama membutuhkan minimal 3 huruf/karakter.</small>
                    </div>

                    <div class="search-card-actions">
                        <button type="submit" class="btn-search-submit">Cari</button>
                        <a href="jasa.php" class="btn-search-reset">Reset</a>
                    </div>
                </form>
            </div>

            <!-- TABLE PANEL (YELLOW CONTAINER) -->
            <div class="panel-outer">
                <div class="panel-outer-title">Daftar Jasa</div>

                <div class="panel-inner">
                    <div class="table-wrapper">
                        <table id="dataTable">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>GAMBAR</th>
                                    <th>NAMA JASA</th>
                                    <th>DESKRIPSI</th>
                                    <th>STATUS</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($jasa_list) > 0): ?>
                                    <?php foreach ($jasa_list as $index => $item): ?>
                                        <tr>
                                            <td><?php echo $offset + $index + 1; ?></td>
                                            <td>
                                                <?php if (!empty($item['gambar']) && file_exists(__DIR__ . '/../uploads/' . $item['gambar'])): ?>
                                                    <img src="../uploads/<?php echo htmlspecialchars($item['gambar']); ?>" alt="Jasa" class="jasa-thumb">
                                                <?php else: ?>
                                                    <div class="jasa-thumb">
                                                        <i class="fa-solid fa-user-tag"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td style="font-weight: 700; color: #000;"><?php echo htmlspecialchars($item['nama_jasa']); ?></td>
                                            <td><?php echo htmlspecialchars(substr($item['deskripsi'], 0, 50)) . (strlen($item['deskripsi']) > 50 ? '...' : ''); ?></td>
                                            <td>
                                                <?php if ($item['status'] == 'aktif'): ?>
                                                    <span class="badge-status badge-aktif">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge-status badge-nonaktif">Nonaktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="action-btns">
                                                    <button type="button" class="btn-edit" onclick="alert('Fitur edit menyusul')">Edit</button>
                                                    <button type="button" class="btn-delete" onclick="alert('Fitur hapus menyusul')">Hapus</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">Belum ada data jasa yang tersedia.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <?php if ($total_pages > 1): ?>
                        <div class="pagination-container">
                            <div class="pagination-info">
                                Menampilkan halaman <?php echo $page; ?> dari <?php echo $total_pages; ?> (Total <?php echo $total_data; ?> data)
                            </div>
                            <ul class="pagination-list">
                                <!-- Previous -->
                                <li>
                                    <a href="?page=<?php echo max(1, $page - 1); ?>&search_nama=<?php echo urlencode($search_nama); ?>" class="<?php echo ($page <= 1) ? 'disabled' : ''; ?>">Prev</a>
                                </li>
                                
                                <!-- Numbered Pages -->
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li>
                                        <a href="?page=<?php echo $i; ?>&search_nama=<?php echo urlencode($search_nama); ?>" class="<?php echo ($page == $i) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Next -->
                                <li>
                                    <a href="?page=<?php echo min($total_pages, $page + 1); ?>&search_nama=<?php echo urlencode($search_nama); ?>" class="<?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">Next</a>
                                </li>
                            </ul>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </main>

    <!-- MODAL TAMBAH JASA -->
    <div class="modal" id="tambahModal">
        <div class="modal-content">
            <h3>Tambah Jasa Baru</h3>
            <p class="modal-description">Lengkapi formulir di bawah ini untuk menambahkan jasa baru.</p>

            <form action="jasa.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="tambah_jasa">

                <div class="form-group">
                    <label for="nama_jasa">Nama Jasa *</label>
                    <input type="text" id="nama_jasa" name="nama_jasa" required placeholder="Contoh: Jasa Desain Grafis Kilat">
                </div>

                <div class="form-group">
                    <label for="gambar">Upload Gambar</label>
                    <input type="file" id="gambar" name="gambar" accept=".jpg, .jpeg, .png, .webp">
                    <small style="color: var(--text-muted); font-size: 0.75rem;">Format: JPG, JPEG, PNG, WEBP</small>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi Jasa</label>
                    <textarea id="deskripsi" name="deskripsi" rows="3" placeholder="Tuliskan detail deskripsi layanan..."></textarea>
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-close" id="closeModalBtn">Batal</button>
                    <button type="submit" class="btn-add" style="border-radius: 8px;">Simpan Jasa</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script>
        // Toggle Sidebar
        const toggleSidebar = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        if (toggleSidebar) {
            toggleSidebar.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
            });
        }

        // Profile Dropdown
        const profileAvatar = document.getElementById('profileAvatar');
        const profileDropdown = document.getElementById('profileDropdown');
        if (profileAvatar) {
            profileAvatar.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
            });
        }
        window.addEventListener('click', () => {
            if (profileDropdown && profileDropdown.classList.contains('show')) {
                profileDropdown.classList.remove('show');
            }
        });

        // Modal Handler
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const tambahModal = document.getElementById('tambahModal');

        if (openModalBtn) {
            openModalBtn.addEventListener('click', () => {
                tambahModal.classList.add('show');
            });
        }
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', () => {
                tambahModal.classList.remove('show');
            });
        }
        window.addEventListener('click', (e) => {
            if (e.target === tambahModal) {
                tambahModal.classList.remove('show');
            }
        });

        // Validasi minimal 3 huruf untuk search
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('search_nama');
        const searchWarn = document.getElementById('searchWarn');

        if (searchForm) {
            searchForm.addEventListener('submit', (e) => {
                const val = searchInput.value.trim();
                if (val.length > 0 && val.length < 3) {
                    e.preventDefault();
                    searchWarn.style.display = 'block';
                    searchInput.focus();
                } else {
                    searchWarn.style.display = 'none';
                }
            });
        }

        // Auto hide alert after 4 seconds
        const notif = document.getElementById('notif');
        if (notif) {
            setTimeout(() => {
                notif.style.transition = 'opacity 0.5s ease';
                notif.style.opacity = '0';
                setTimeout(() => notif.remove(), 500);
            }, 4000);
        }
    </script>
</body>
</html>