
<?php
session_start();

// 1. Hubungkan ke file konfigurasi database Anda
require_once "../config/database.php";

if (isset($_SESSION['id_admin'])) {
    header("Location: dashboard.php");
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
            // Gunakan variabel koneksi dari config/database.php (misal $pdo atau $koneksi/$conn)
            // Jika file config/database.php menggunakan variabel $pdo:
            if (isset($pdo)) {
                $db = $pdo;
            } elseif (isset($conn)) {
                $db = $conn;
            } elseif (isset($koneksi)) {
                $db = $koneksi;
            } else {
                throw new Exception("Variabel koneksi tidak ditemukan di config/database.php");
            }

            $stmt = $db->prepare(
                "SELECT id_admin, nama, username, password, role
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
                $_SESSION['role_admin'] = $admin['role'];

                header("Location: dashboard.php");
                exit;
            }
        } catch (Exception $e) {
            $error = "Koneksi database bermasalah. Periksa konfigurasi database.";
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

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --yellow: #ffd400;
            --black: #050505;
            --white: #ffffff;
            --gray: #777777;
            --background: #f4f0e7;
            --error: #d96b6b;
            --error-bg: #fff1f1;
            --error-border: #f2cccc;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--background);
            color: var(--black);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 960px;
            min-height: 500px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: var(--white);
            border-radius: 17px;
            overflow: hidden;
            box-shadow: 0 8px 35px rgba(0, 0, 0, 0.06);
            animation: appear .45s ease;
        }

        /* PANEL KUNING */

        .brand-panel {
            background: var(--yellow);
            padding: 42px 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .brand-content {
            margin: auto 0;
            padding: 35px 0;
        }

        .brand-content h1 {
            font-size: clamp(30px, 3.3vw, 40px);
            line-height: 1.03;
            letter-spacing: -1.2px;
            font-weight: 800;
            margin-bottom: 22px;
        }

        .brand-content p {
            font-size: 13px;
            line-height: 1.6;
            max-width: 300px;
        }

        .brand-footer {
            font-size: 11px;
            font-weight: 700;
            position: relative;
        }

        /* FORM LOGIN */

        .form-panel {
            padding: 40px 43px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-top {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo {
            display: block;
            width: 150px;
            height: 55px;
            max-width: 80%;
            object-fit: contain;
            margin: 0 auto 10px;
        }

        .form-top p {
            font-size: 12px;
            color: #777;
            line-height: 1.5;
            max-width: 300px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 17px;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            color: #777;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .form-control {
            width: 100%;
            height: 45px;
            border: 1px solid #d5d5d5;
            border-radius: 10px;
            padding: 0 13px;
            background: #fff;
            color: #111;
            font-size: 14px;
            outline: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.12);
            transition: border .2s, box-shadow .2s;
        }

        .form-control:focus {
            border-color: #d8b500;
            box-shadow: 0 0 0 3px rgba(255, 212, 0, 0.18);
        }

        .password-input {
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #111;
        }

        .toggle-password svg {
            width: 19px;
            height: 19px;
        }

        /* ALERT MERAH MUDA */

        .field-alert {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 10px 11px;
            margin-top: 8px;
            border: 1px solid var(--error-border);
            border-radius: 10px;
            background: var(--error-bg);
            color: #9b4444;
            font-size: 11px;
            line-height: 1.4;
            animation: appear .25s ease;
        }

        .alert-symbol {
            flex: 0 0 15px;
            width: 15px;
            height: 15px;
            border: 2px solid var(--error);
            border-radius: 50%;
            color: var(--error);
            font-size: 10px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .general-alert {
            margin-bottom: 15px;
        }

        /* TOMBOL LOGIN */

        .btn-login {
            width: 100%;
            height: 43px;
            border: none;
            border-radius: 10px;
            background: var(--black);
            color: var(--yellow);
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .2s;
        }

        .btn-login:hover {
            background: #222;
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: .7;
            cursor: wait;
            transform: none;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 17px;
            color: #222;
            font-size: 12px;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        /* ANIMASI */

        @keyframes appear {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* RESPONSIVE */

        @media (max-width: 700px) {
            body {
                padding: 15px;
            }

            .login-wrapper {
                max-width: 420px;
                min-height: auto;
                grid-template-columns: 1fr;
                border-radius: 15px;
            }

            .brand-panel {
                padding: 25px 26px;
                min-height: 190px;
            }

            .brand-content {
                padding: 20px 0 5px;
            }

            .brand-content h1 {
                font-size: 29px;
                margin-bottom: 10px;
                max-width: 300px;
            }

            .brand-content p,
            .brand-footer {
                display: none;
            }

            .form-panel {
                padding: 30px 26px;
            }

            .form-top {
                margin-bottom: 24px;
            }

            .logo {
                height: 48px;
                width: 135px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>
</head>

<body>

<div class="login-wrapper">

    <section class="brand-panel">
        <div class="brand-content">
            <h1>Kelola<br>Percetakan<br>Lebih Mudah.</h1>
            <p>Kelola produk, katalog, portofolio, dan informasi Mahira Printing melalui dashboard admin.</p>
        </div>

        <div class="brand-footer">
            ADMIN PANEL &bull; MAHIRA PRINTING
        </div>
    </section>

    <main class="form-panel">

        <div class="form-top">
            <img
                class="logo"
                src="../../assets/img/logo1.png"
                alt="Mahira Printing"
            >
            <p>Silakan masuk untuk mengakses dashboard Mahira Printing.</p>
        </div>

        <?php if ($error_type == 'database' || $error_type == 'empty') { ?>
            <div class="field-alert general-alert" role="alert">
                <span class="alert-symbol">!</span>
                <span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        <?php } ?>

        <form method="POST" action="" id="loginForm" autocomplete="on">

            <div class="form-group">
                <label for="username">Username</label>

                <div class="input-wrap">
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        value="<?php echo isset($_POST['username']) && $error_type != 'database' ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                        autocomplete="username"
                        maxlength="50"
                        required
                        autofocus
                    >
                </div>

                <?php if ($error_type == 'username') { ?>
                    <div class="field-alert" role="alert">
                        <span class="alert-symbol">!</span>
                        <span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                <?php } ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <div class="input-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control password-input"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        id="togglePassword"
                        aria-label="Tampilkan password"
                    >
                        <svg viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>

                <?php if ($error_type == 'password') { ?>
                    <div class="field-alert" role="alert">
                        <span class="alert-symbol">!</span>
                        <span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                <?php } ?>
            </div>

            <button type="submit" class="btn-login" id="loginButton">
                Login
            </button>

        </form>

        <a href="../../index.php" class="back-link">
            Kembali ke Website
        </a>

    </main>

</div>

<script>
(function() {
    var passwordInput = document.getElementById('password');
    var toggleButton = document.getElementById('togglePassword');
    var loginForm = document.getElementById('loginForm');
    var loginButton = document.getElementById('loginButton');

    toggleButton.addEventListener('click', function() {
        var show = passwordInput.type === 'password';

        passwordInput.type = show ? 'text' : 'password';

        toggleButton.setAttribute(
            'aria-label',
            show ? 'Sembunyikan password' : 'Tampilkan password'
        );

        if (show) {
            toggleButton.innerHTML =
                '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3l18 18"></path><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"></path><path d="M9.9 5.2A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a15 15 0 0 1-3 3.8"></path><path d="M6.2 6.2C3.5 8 2 12 2 12s3.5 7 10 7a10.5 10.5 0 0 0 4-.8"></path></svg>';
        } else {
            toggleButton.innerHTML =
                '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
        }

        passwordInput.focus();
    });

    loginForm.addEventListener('submit', function() {
        if (loginForm.checkValidity()) {
            loginButton.disabled = true;
            loginButton.textContent = 'Memeriksa akun...';
        }
    });
})();
</script>

</body>
</html>
