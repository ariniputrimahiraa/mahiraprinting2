<?php
session_start();
require_once __DIR__ . '/../config/database.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['id_admin'])) {
    header("Location: login.php");
    exit;
}

$id_admin = $_SESSION['id_admin'];
$pesan = "";
$jenis_pesan = "";

// Mengambil data admin dari database
try {
    if (isset($pdo)) {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE id_admin = ? LIMIT 1");
        $stmt->execute([$id_admin]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif (isset($koneksi)) {
        $stmt = $koneksi->prepare("SELECT * FROM admin WHERE id_admin = ? LIMIT 1");
        $stmt->bind_param("i", $id_admin);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();
    }
} catch (Exception $e) {
    $error_msg = "Gagal mengambil data: " . $e->getMessage();
}

// Proses Update Profil (Menyesuaikan tabel admin: hanya nama dan username)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'update_profile') {
        $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';

        if ($nama == '' || $username == '') {
            $pesan = "Nama dan username wajib diisi.";
            $jenis_pesan = "error";
        } else {
            try {
                if (isset($pdo)) {
                    $stmt_update = $pdo->prepare("UPDATE admin SET nama = ?, username = ? WHERE id_admin = ?");
                    $stmt_update->execute([$nama, $username, $id_admin]);
                } elseif (isset($koneksi)) {
                    $stmt_update = $koneksi->prepare("UPDATE admin SET nama = ?, username = ? WHERE id_admin = ?");
                    $stmt_update->bind_param("ssi", $nama, $username, $id_admin);
                    $stmt_update->execute();
                }

                // Update Session
                $_SESSION['nama_admin'] = $nama;
                $_SESSION['username_admin'] = $username;

                // Refresh Data
                $admin['nama'] = $nama;
                $admin['username'] = $username;

                $pesan = "Profil berhasil diperbarui!";
                $jenis_pesan = "success";
            } catch (Exception $e) {
                $pesan = "Gagal memperbarui profil (Kemungkinan username sudah digunakan): " . $e->getMessage();
                $jenis_pesan = "error";
            }
        }
    }
}

$initialAvatar = isset($admin['nama']) && !empty($admin['nama']) ? strtoupper(substr($admin['nama'], 0, 1)) : 'A';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Mahira Printing</title>

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

        /* SIDEBAR STYLE */
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
        }

        .sidebar.collapsed .text,
        .sidebar.collapsed .brand-details,
        .sidebar.collapsed .menu-section { display: none; }

        .sidebar.collapsed .menu li a { justify-content: center; padding: 12px; }

        /* MAIN CONTENT AREA */
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
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* GOOGLE STYLE POPUP */
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

        /* ACCOUNT CONTAINER */
        .container { padding: 10px 32px 32px; }

        .account-card {
            background: #fff;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            max-width: 800px;
            margin: 0 auto;
        }

        .account-header {
            display: flex;
            align-items: center;
            gap: 24px;
            padding-bottom: 24px;
            border-bottom: 1px solid #f3f4f6;
            margin-bottom: 24px;
        }

        .big-avatar {
            width: 80px;
            height: 80px;
            background: var(--brand-yellow);
            color: #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            font-weight: 800;
            box-shadow: 0 4px 15px rgba(250, 204, 21, 0.4);
        }

        .account-title h2 { font-size: 1.5rem; font-weight: 800; }
        .account-title p { color: var(--text-muted); font-size: 0.88rem; margin-top: 2px; }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group-full { grid-column: span 2; }

        .form-group label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            outline: none;
            font-size: 0.9rem;
            transition: border-color 0.2s;
        }

        .form-group input:focus { border-color: #000; }

        .btn-save {
            background: #0d0d0d;
            color: var(--brand-yellow);
            border: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
        }

        .btn-save:hover {
            background: #262626;
            transform: translateY(-1px);
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

        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-group-full { grid-column: span 1; }
            .account-header { flex-direction: column; text-align: center; }
            .container { padding: 16px; }
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
            <li class="active"><a href="admin.php"><i class="fa-solid fa-user-gear"></i><span class="text">Kelola Admin</span></a></li>
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
            <div class="toggle-btn" id="toggleSidebar">
                <i class="fa-solid fa-bars"></i>
            </div>

            <div class="topbar-right">
                <!-- PROFILE AVATAR WITH GOOGLE POPUP -->
                <div class="profile-container">
                    <div class="profile-avatar" id="profileAvatar"><?php echo $initialAvatar; ?></div>

                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="profile-header">
                            <div class="profile-header-icon">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <div class="profile-info">
                                <h4><?php echo htmlspecialchars(isset($admin['nama']) ? $admin['nama'] : 'Admin User'); ?></h4>
                                <p><?php echo htmlspecialchars(isset($admin['username']) ? $admin['username'] : 'admin'); ?></p>
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
            <div class="account-card">
                <?php if ($pesan != ""): ?>
                    <div class="alert <?php echo htmlspecialchars($jenis_pesan); ?>" id="notif">
                        <?php echo htmlspecialchars($pesan); ?>
                    </div>
                <?php endif; ?>

                <div class="account-header">
                    <div class="big-avatar"><?php echo $initialAvatar; ?></div>
                    <div class="account-title">
                       <h2><?php echo htmlspecialchars(isset($admin['nama']) ? $admin['nama'] : 'Admin User'); ?></h2>
                        <p>Kelola informasi akun dan identitas profil Anda</p>
                    </div>
                </div>

                <form method="POST" action="">
                    <input type="hidden" name="action" value="update_profile">

                    <div class="form-grid">
                        <div class="form-group form-group-full">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars(isset($admin['nama']) ? $admin['nama'] : ''); ?>" required>
                        </div>

                        <div class="form-group form-group-full">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars(isset($admin['username']) ? $admin['username'] : ''); ?>" required>
                        </div>
                    </div>

                    <div style="text-align: right; margin-top: 20px;">
                        <button type="submit" class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Toggle Sidebar
        const sidebar = document.getElementById('sidebar');
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
        });

        // Toggle Profile Dropdown
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

        // Hide Alert
        setTimeout(function() {
            const notif = document.getElementById('notif');
            if (notif) notif.style.display = 'none';
        }, 3000);
    </script>
</body>
</html>