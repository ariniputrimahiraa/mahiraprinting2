<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit;
}

// Mengambil Statistik Ringkasan dari Database
$total_produk = 0;
$total_jasa = 0;
$total_portfolio = 0;
$total_review = 0;

try {
    if (isset($pdo)) {
        $total_produk = $pdo->query("SELECT COUNT(*) FROM produk")->fetchColumn();
        $total_jasa = $pdo->query("SELECT COUNT(*) FROM jasa")->fetchColumn();
        $total_portfolio = $pdo->query("SELECT COUNT(*) FROM portfolio")->fetchColumn();
        $total_review = $pdo->query("SELECT COUNT(*) FROM review")->fetchColumn();
    } elseif (isset($koneksi)) {
        $total_produk = $koneksi->query("SELECT COUNT(*) as total FROM produk")->fetch_assoc()['total'];
        $total_jasa = $koneksi->query("SELECT COUNT(*) as total FROM jasa")->fetch_assoc()['total'];
        $total_portfolio = $koneksi->query("SELECT COUNT(*) as total FROM portfolio")->fetch_assoc()['total'];
        $total_review = $koneksi->query("SELECT COUNT(*) as total FROM review")->fetch_assoc()['total'];
    }
} catch (Exception $e) {
    // Tangani jika tabel belum siap
}

// Inisial avatar admin
$nama_admin = isset($_SESSION['nama_admin']) ? $_SESSION['nama_admin'] : 'Admin User';
$initialAvatar = !empty($nama_admin) ? strtoupper(substr($nama_admin, 0, 1)) : 'A';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Dashboard - Mahira Printing</title>

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
            padding: 28px 32px;
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
            font-size: 1.6rem;
            font-weight: 700;
            margin-top: 4px;
        }

        .hero-banner p {
            font-size: 0.88rem;
            color: #a1a1aa;
            margin-top: 4px;
        }

        /* STATS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: #fff;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.2s;
        }

        .stat-card:hover { transform: translateY(-2px); }

        .stat-icon {
            width: 54px;
            height: 54px;
            background: #fef9c3;
            color: #854d0e;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .stat-info h3 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #111827;
        }

        .stat-info p {
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        /* QUICK ACCESS PANEL */
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
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
        }

        .quick-link-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 18px;
            border-radius: 12px;
            text-decoration: none;
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            gap: 8px;
            transition: all 0.2s;
        }

        .quick-link-card:hover {
            background: #0d0d0d;
            color: #fff;
            border-color: #0d0d0d;
            transform: translateY(-2px);
        }

        .quick-link-card:hover i { color: var(--brand-yellow); }

        .quick-link-card i {
            font-size: 1.2rem;
            color: #4b5563;
        }

        .quick-link-card span {
            font-weight: 700;
            font-size: 0.95rem;
        }

        .quick-link-card p {
            font-size: 0.78rem;
            color: var(--text-muted);
        }

        .quick-link-card:hover p { color: #a1a1aa; }

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
            <li class="active"><a href="dashboard.php"><i class="fa-solid fa-chart-pie"></i><span class="text">Beranda</span></a></li>
            <li><a href="kelola_admin.php"><i class="fa-solid fa-user-gear"></i><span class="text">Kelola Admin</span></a></li>
            <li><a href="produk.php"><i class="fa-solid fa-box"></i><span class="text">Produk</span></a></li>
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

            <!-- HERO BANNER -->
            <div class="hero-banner">
                <div>
                    <span class="label">Panel Kontrol Utama</span>
                    <h2>Selamat Datang, <?php echo htmlspecialchars($nama_admin); ?>!</h2>
                    <p>Kelola konten, produk, dan layanan Mahira Printing melalui dashboard ini.</p>
                </div>
            </div>

            <!-- STATS CARDS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-box"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $total_produk; ?></h3>
                        <p>Total Produk</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-user-tag"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $total_jasa; ?></h3>
                        <p>Total Jasa</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-image"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $total_portfolio; ?></h3>
                        <p>Portofolio</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fa-solid fa-star"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $total_review; ?></h3>
                        <p>Review Pelanggan</p>
                    </div>
                </div>
            </div>

            <!-- QUICK ACCESS PANEL -->
            <div class="panel-outer">
                <div class="panel-outer-title">Akses Cepat Menu</div>

                <div class="panel-inner">
                    <div class="quick-links">
                        <a href="produk.php" class="quick-link-card">
                            <i class="fa-solid fa-box"></i>
                            <span>Kelola Produk</span>
                            <p>Tambah, edit, atau hapus item produk katalog.</p>
                        </a>
                        <a href="jasa.php" class="quick-link-card">
                            <i class="fa-solid fa-user-tag"></i>
                            <span>Kelola Jasa</span>
                            <p>Atur daftar layanan jasa percetakan.</p>
                        </a>
                        <a href="portofolio.php" class="quick-link-card">
                            <i class="fa-solid fa-image"></i>
                            <span>Kelola Portofolio</span>
                            <p>Perbarui galeri dokumentasi hasil kerja.</p>
                        </a>
                        <a href="review.php" class="quick-link-card">
                            <i class="fa-solid fa-star"></i>
                            <span>Kelola Review</span>
                            <p>Tinjau dan kelola testimoni dari klien.</p>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </main>

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
    </script>
</body>
</html>