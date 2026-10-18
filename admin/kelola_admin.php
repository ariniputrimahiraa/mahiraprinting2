<?php
require_once __DIR__ . '/../config/database.php';

$pesan = "";
$jenis_pesan = "";

// Proses tambah admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'tambah') {
        $nama = isset($_POST['nama']) ? trim($_POST['nama']) : "";
        $username = isset($_POST['username']) ? trim($_POST['username']) : "";
        $password_raw = isset($_POST['password']) ? $_POST['password'] : "";

        if ($nama == "" || $username == "" || $password_raw == "") {
            $pesan = "Nama, username, dan password wajib diisi!";
            $jenis_pesan = "error";
        } else {
            // Hash password agar aman
            $password = password_hash($password_raw, PASSWORD_DEFAULT);

            if (isset($koneksi) && $koneksi) {
                $stmt = $koneksi->prepare("INSERT INTO admin (nama, username, password) VALUES (?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("sss", $nama, $username, $password);
                    if ($stmt->execute()) {
                        $pesan = "Admin berhasil ditambahkan!";
                        $jenis_pesan = "success";
                    } else {
                        $pesan = "Gagal menambahkan admin (Kemungkinan username sudah digunakan): " . $stmt->error;
                        $jenis_pesan = "error";
                    }
                    $stmt->close();
                } else {
                    $pesan = "Gagal memproses data: " . $koneksi->error;
                    $jenis_pesan = "error";
                }
            } else {
                // Fallback dengan PDO jika $koneksi tidak ada
                try {
                    $stmt = $pdo->prepare("INSERT INTO admin (nama, username, password) VALUES (?, ?, ?)");
                    $stmt->execute([$nama, $username, $password]);
                    $pesan = "Admin berhasil ditambahkan!";
                    $jenis_pesan = "success";
                } catch (Exception $e) {
                    $pesan = "Gagal menambahkan admin: " . $e->getMessage();
                    $jenis_pesan = "error";
                }
            }
        }
    }
}

$admins = array();
if (isset($pdo)) {
    $res = $pdo->query("SELECT * FROM admin ORDER BY id_admin DESC");
    if ($res) {
        while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
            $admins[] = $row;
        }
    }
} elseif (isset($koneksi)) {
    $res = $koneksi->query("SELECT * FROM admin ORDER BY id_admin DESC");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $admins[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Admin - Mahira Printing</title>

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

        .sidebar.collapsed {
            width: 75px;
        }

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

        .menu li {
            margin-bottom: 6px;
        }

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
        .sidebar.collapsed .menu-section {
            display: none;
        }

        .sidebar.collapsed .menu li a {
            justify-content: center;
            padding: 12px;
        }

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
            background: transparent;
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

        .toggle-btn:hover {
            background: #f3f4f6;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            background: #e5e5e0;
            border: none;
            padding: 10px 16px 10px 40px;
            border-radius: 20px;
            font-size: 0.88rem;
            outline: none;
            width: 220px;
            transition: all 0.2s;
        }

        .search-box input:focus {
            background: #fff;
            width: 260px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            font-size: 0.9rem;
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

        /* --- PROFILE & POPUP --- */
        .profile-container {
            position: relative;
        }

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
            transition: transform 0.2s;
            user-select: none;
        }

        .profile-avatar:hover {
            transform: scale(1.05);
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

        .profile-dropdown.show {
            display: flex;
        }

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

        .profile-info h4 {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .profile-info p {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .profile-menu {
            list-style: none;
            padding-top: 8px;
        }

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
            transition: background 0.15s;
        }

        .profile-menu li a:hover {
            background: #f9fafb;
        }

        .profile-menu li a.logout {
            color: #ef4444;
            background: #fef2f2;
            margin-top: 4px;
        }

        .profile-menu li a.logout:hover {
            background: #fee2e2;
        }

        /* --- DASHBOARD CONTAINER --- */
        .container {
            padding: 10px 32px 32px;
        }

        /* STAT CARDS */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .card {
            background: var(--card-bg);
            padding: 20px 24px;
            border-radius: 14px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            position: relative;
            overflow: hidden;
        }

        .card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--brand-yellow);
        }

        .card .title {
            font-size: 0.75rem;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card .value {
            font-size: 1.8rem;
            font-weight: 800;
            margin-top: 6px;
            color: var(--text-dark);
        }

        .card .subtext {
            font-size: 0.72rem;
            color: var(--brand-yellow-hover);
            margin-top: 4px;
            font-weight: 600;
        }

        /* HERO PANEL BANNER */
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

        /* MAIN PANEL CONTAINING TABLE */
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
        }

        .btn-add:hover {
            background: var(--brand-yellow-hover);
            transform: translateY(-1px);
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

        tbody tr:hover {
            background-color: #fafafa;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-block;
            background: #dcfce7;
            color: #15803d;
        }

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

        /* MODAL */
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
            max-width: 420px;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-content h3 {
            font-size: 1.2rem;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .modal-description {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: #374151;
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-size: 0.88rem;
        }

        .form-group input:focus {
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
            .topbar { padding: 16px; }
            .hero-banner { flex-direction: column; align-items: flex-start; gap: 14px; }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            
            
                <img style="width: 80%;"  src="../assets/images/download.png" alt="">
          
        </div>

        <div class="menu-section">Menu Utama</div>

        <ul class="menu">
            <li>
                <a href="dashboard.php">
                    <i class="fa-solid fa-grid-2"></i>
                    <span class="text">Beranda</span>
                </a>
            </li>
            <li class="active">
                <a href="admin.php">
                    <i class="fa-solid fa-user-gear"></i>
                    <span class="text">Kelola Admin</span>
                </a>
            </li>
            <li>
                <a href="produk.php">
                    <i class="fa-solid fa-box"></i>
                    <span class="text">Produk</span>
                </a>
            </li>
            <li>
                <a href="jasa.php">
                    <i class="fa-solid fa-user-tag"></i>
                    <span class="text">Jasa</span>
                </a>
            </li>
            <li>
                <a href="portofolio.php">
                    <i class="fa-solid fa-image"></i>
                    <span class="text">Portofolio</span>
                </a>
            </li>
            <li>
                <a href="review.php">
                    <i class="fa-solid fa-star"></i>
                    <span class="text">Review</span>
                </a>
            </li>
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
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Search...">
                </div>

                <div class="notif-btn">
                    <i class="fa-regular fa-bell"></i>
                    <span class="notif-badge"></span>
                </div>

                <!-- PROFILE AVATAR WITH GOOGLE POPUP -->
                <div class="profile-container">
                    <div class="profile-avatar" id="profileAvatar">A</div>

                    <!-- POPUP CARD -->
                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="profile-header">
                            <div class="profile-header-icon">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="profile-info">
                                <h4>Admin User</h4>
                                <p>Mahira Printing</p>
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

            <!-- STAT CARDS -->
            <div class="cards">
                <div class="card">
                    <div class="title">Total Admin</div>
                    <div class="value"><?php echo count($admins); ?></div>
                    <div class="subtext">Admin terdaftar di sistem</div>
                </div>

                <div class="card">
                    <div class="title">Tabel Database</div>
                    <div class="value" style="font-size: 1.2rem; margin-top: 10px;">admin</div>
                    <div class="subtext">Tabel aktif MySQL</div>
                </div>

                <div class="card">
                    <div class="title">Sistem Keamanan</div>
                    <div class="value" style="font-size: 1.2rem; margin-top: 10px;">Bcrypt</div>
                    <div class="subtext">Password Hashed</div>
                </div>
            </div>

            <!-- HERO BANNER -->
            <div class="hero-banner">
                <div>
                    <span class="label">Manajemen Sistem</span>
                    <h2>Kelola Admin</h2>
                    <p>Tambahkan, ubah, atau hapus data administrator Mahira Printing.</p>
                </div>
                <button type="button" class="btn-add" id="openModalBtn">
                    <i class="fa-solid fa-plus"></i> Tambah Admin
                </button>
            </div>

            <!-- TABLE PANEL -->
            <div class="panel-outer">
                <div class="panel-outer-title">Daftar Admin</div>

                <div class="panel-inner">
                    <div class="table-wrapper">
                        <table id="dataTable">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NAMA ADMIN</th>
                                    <th>USERNAME</th>
                                    <th>DIBUAT PADA</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($admins) > 0): ?>
                                    <?php foreach ($admins as $index => $admin): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td style="font-weight: 700; color: #000;">
                                                <?php echo isset($admin['nama']) ? htmlspecialchars($admin['nama']) : '-'; ?>
                                            </td>
                                            <td>
                                                <?php echo isset($admin['username']) ? htmlspecialchars($admin['username']) : '-'; ?>
                                            </td>
                                            <td>
                                                <span class="badge">
                                                    <?php echo isset($admin['created_at']) ? htmlspecialchars($admin['created_at']) : '-'; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-btns">
                                                    <button class="btn-edit" title="Edit Data">Edit</button>
                                                    <button class="btn-delete" onclick="deleteRow(this)" title="Hapus Data">Hapus</button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #9ca3af; padding: 24px;">
                                            Belum ada data admin.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- MODAL TAMBAH ADMIN -->
    <div class="modal" id="userModal">
        <div class="modal-content">
            <h3>Tambah Admin</h3>
            <p class="modal-description">Masukkan informasi admin baru di bawah ini.</p>

            <form method="POST" action="">
                <input type="hidden" name="action" value="tambah">

                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Masukkan username login" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-close" id="closeModalBtn">Batal</button>
                    <button type="submit" class="btn-add"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });

        // Profile Popup Interactive Toggle
        const profileAvatar = document.getElementById('profileAvatar');
        const profileDropdown = document.getElementById('profileDropdown');

        profileAvatar.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!profileDropdown.contains(e.target) && e.target !== profileAvatar) {
                profileDropdown.classList.remove('show');
            }
        });

        // Modal Functionality
        const modal = document.getElementById('userModal');
        document.getElementById('openModalBtn').addEventListener('click', function() {
            modal.classList.add('show');
            document.getElementById('nama').focus();
        });

        document.getElementById('closeModalBtn').addEventListener('click', function() {
            modal.classList.remove('show');
        });

        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.classList.remove('show');
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') modal.classList.remove('show');
        });

        // Live Table Filter/Search
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#dataTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(filter) !== -1 ? '' : 'none';
            });
        });

        function deleteRow(btn) {
            if(confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                alert('Fitur hapus ke database siap diintegrasikan.');
            }
        }

        // Auto Hide Alert
        setTimeout(function() {
            const notif = document.getElementById('notif');
            if (notif) notif.style.display = 'none';
        }, 3000);
    </script>
</body>
</html>