<?php
session_start();
require_once "../config/database.php";

if (isset($_SESSION['id_admin'])) {
    header("Location: ../admin/index.php");
    exit;
}

$error = "";
$error_type = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : "";
    $password = isset($_POST['password']) ? $_POST['password'] : "";

    if ($username == "" || $password == "") {
        $error = "Username dan password wajib diisi.";
        $error_type = "empty";
    } else {
        try {
            if (!isset($pdo)) {
                throw new Exception("Variabel koneksi PDO tidak ditemukan.");
            }

            $stmt = $pdo->prepare(
                "SELECT id_admin, nama, username, password
                 FROM admin
                 WHERE username = :username
                 LIMIT 1"
            );

            $stmt->execute(array(':username' => $username));
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$admin) {
                $error = "Username yang Anda masukkan salah. Silakan coba lagi.";
                $error_type = "username";
            } elseif (!password_verify($password, $admin['password'])) {
                $error = "Password yang Anda masukkan salah. Silakan coba lagi.";
                $error_type = "password";
            } else {
                session_regenerate_id(true);

                $_SESSION['id_admin'] = $admin['id_admin'];
                $_SESSION['nama_admin'] = $admin['nama'];
                $_SESSION['username_admin'] = $admin['username'];

                header("Location: ../admin/dashboard.php");
                exit;
            }
        } catch (Exception $e) {
            error_log("Login Admin Error: " . $e->getMessage());
            $error = "Terjadi masalah saat mengakses database. Periksa konfigurasi sistem.";
            $error_type = "database";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Mahira Printing</title>

    <!-- FontAwesome Icons & Google Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-outer: #f5f3eb;
            --brand-yellow: #facc15;
            --brand-yellow-hover: #eab308;
            --btn-black: #0d0d0d;
            --btn-black-hover: #262626;
            --text-dark: #111827;
            --text-muted: #6b7280;
            --error-bg: #fef2f2;
            --error-border: #fca5a5;
            --error-text: #b91c1c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-color: var(--bg-outer);
            color: var(--text-dark);
        }

        /* --- LOGIN CARD CONTAINER (SPLIT LAYOUT) --- */
        .login-card {
            width: 100%;
            max-width: 880px;
            background: #ffffff;
            border-radius: 20px;
            display: flex;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
        }

        /* --- LEFT SIDE (YELLOW BANNER) --- */
        .login-left {
            flex: 1;
            background: var(--brand-yellow);
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .left-content {
            margin-top: auto;
            margin-bottom: auto;
        }

        .left-content h1 {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1.15;
            color: #000000;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .left-content p {
            font-size: 0.92rem;
            color: #27272a;
            font-weight: 500;
            line-height: 1.5;
            max-width: 320px;
        }

        .left-footer {
            font-size: 0.72rem;
            font-weight: 800;
            color: #000000;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        /* --- RIGHT SIDE (FORM PANEL) --- */
        .login-right {
            flex: 1.1;
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: #ffffff;
        }

        /* BRAND LOGO HEADER */
        .brand-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand-logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .logo-circle {
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
        }

        .logo-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--brand-yellow);
            letter-spacing: 1px;
            line-height: 1;
            text-align: left;
        }

        .logo-title span {
            display: block;
            font-size: 0.65rem;
            color: #a1a1aa;
            letter-spacing: 2px;
        }

        .brand-header p {
            font-size: 0.82rem;
            color: var(--text-muted);
            line-height: 1.4;
            max-width: 280px;
            margin: 0 auto;
        }

        /* FORM ELEMENTS */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 0.72rem;
            font-weight: 800;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.92rem;
            font-weight: 500;
            outline: none;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #9ca3af;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.03);
        }

        .password-wrapper .form-control {
            padding-right: 44px;
        }

        .toggle-password-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #6b7280;
            cursor: pointer;
            font-size: 1rem;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password-btn:hover {
            color: #111827;
        }

        /* ALERT INLINE STYLE */
        .alert-box {
            background: var(--error-bg);
            border: 1px solid var(--error-border);
            border-radius: 10px;
            padding: 10px 14px;
            margin-top: 10px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert-box i {
            color: var(--error-text);
            font-size: 1rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .alert-box span {
            font-size: 0.8rem;
            color: var(--error-text);
            font-weight: 500;
            line-height: 1.4;
        }

        /* SUBMIT BUTTON */
        .btn-submit {
            width: 100%;
            background: var(--btn-black);
            color: var(--brand-yellow);
            border: none;
            padding: 13px;
            border-radius: 10px;
            font-size: 0.92rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: var(--btn-black-hover);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* FOOTER BACK LINK */
        .back-link-wrapper {
            text-align: center;
            margin-top: 20px;
        }

        .back-link {
            font-size: 0.82rem;
            font-weight: 700;
            color: #111827;
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--brand-yellow-hover);
            text-decoration: underline;
        }

        /* RESPONSIVE DESIGN */
        @media (max-width: 768px) {
            .login-card {
                flex-direction: column;
                max-width: 440px;
            }

            .login-left {
                padding: 32px 28px;
            }

            .left-content h1 {
                font-size: 1.7rem;
            }

            .login-right {
                padding: 36px 28px;
            }
        }
    </style>
</head>
<body>

    <div class="login-card">
        <!-- BANNER KIRI (KUNING) -->
        <div class="login-left">
            <div class="left-content">
                <h1>Kelola<br>Percetakan<br>Lebih Mudah.</h1>
                <p>Kelola produk, katalog, portofolio, dan informasi Mahira Printing melalui dashboard admin.</p>
            </div>
            <div class="left-footer">
                ADMIN PANEL • MAHIRA PRINTING
            </div>
        </div>

        <!-- FORM KANAN (PUTIH) -->
        <div class="login-right">
            <!-- LOGO BRAND -->
            <div class="brand-header">
               <div class="brand">
            
            
                <img style="width: 50%;"  src="../assets/images/download.png" alt="">
          
        </div>
                <p>Silakan masuk untuk mengakses dashboard Mahira Printing.</p>
            </div>

            <!-- FORM LOGIN -->
            <form method="POST" action="" id="loginForm">
                <!-- USERNAME FIELD -->
                <div class="form-group">
                    <label for="username">USERNAME</label>
                    <div class="input-wrapper">
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control"
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                            autocomplete="username"
                            required
                            autofocus
                        >
                    </div>

                    <!-- ERROR MESSAGE UNTUK USERNAME -->
                    <?php if ($error != "" && ($error_type == 'username' || $error_type == 'empty' || $error_type == 'database')): ?>
                        <div class="alert-box">
                            <i class="fa-regular fa-circle-dot"></i>
                            <span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- PASSWORD FIELD -->
                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <div class="input-wrapper password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            autocomplete="current-password"
                            required
                        >
                        <button type="button" class="toggle-password-btn" id="togglePasswordBtn" title="Lihat Password">
                            <i class="fa-regular fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>

                    <!-- ERROR MESSAGE UNTUK PASSWORD -->
                    <?php if ($error != "" && $error_type == 'password'): ?>
                        <div class="alert-box">
                            <i class="fa-regular fa-circle-dot"></i>
                            <span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" class="btn-submit" id="submitBtn">
                    Login
                </button>
            </form>

            <!-- KEMBALI KE WEBSITE LINK -->
            <div class="back-link-wrapper">
                <a href="../index.php" class="back-link">Kembali ke Website</a>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT FOR INTERACTIVITY -->
    <script>
        // Toggle Show/Hide Password
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');

        togglePasswordBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

            // Swap icon
            if (isPassword) {
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });

        // Loading State On Form Submit
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');

        loginForm.addEventListener('submit', function () {
            if (loginForm.checkValidity()) {
                submitBtn.textContent = 'Memeriksa...';
                submitBtn.style.opacity = '0.8';
                submitBtn.style.cursor = 'wait';
            }
        });
    </script>
</body>
</html>