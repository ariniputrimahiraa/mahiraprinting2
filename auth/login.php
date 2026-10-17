
<?php
session_start();

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
            $pdo = new PDO(
                "mysql:host=localhost;dbname=percetakan;charset=utf8",
                "root",
                ""
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            $stmt = $pdo->prepare(
                "SELECT id_admin, nama, username, password, role
                 FROM admin
                 WHERE username = :username
                 LIMIT 1"
            );
            $stmt->execute(array(':username' => $username));
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$admin) {
                $error = "Username tidak ditemukan. Periksa kembali username kamu.";
                $error_type = "username";
            } elseif (!password_verify($password, $admin['password'])) {
                $error = "Password yang kamu masukkan salah. Silakan coba lagi.";
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
        } catch (PDOException $e) {
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
            --black: #111111;
            --white: #ffffff;
            --gray: #777777;
            --light: #f7f7f5;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--light);
            color: var(--black);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
        }

        .login-wrapper {
            width: 100%;
            max-width: 1050px;
            min-height: 610px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: var(--white);
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 20px 65px rgba(0, 0, 0, 0.10);
            animation: appear .5s ease;
        }

        .brand-panel {
            background: var(--black);
            color: var(--white);
            padding: 55px 48px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .brand-panel:before {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            border: 65px solid rgba(255, 212, 0, 0.08);
            border-radius: 50%;
            right: -170px;
            bottom: -110px;
        }

        .brand-panel:after {
            content: "";
            position: absolute;
            width: 150px;
            height: 150px;
            background: var(--yellow);
            border-radius: 50%;
            right: -80px;
            top: 120px;
            opacity: .95;
        }

        .brand-content,
        .brand-footer,
        .brand-logo {
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .logo-symbol {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--yellow);
            color: var(--black);
            border-radius: 12px;
            font-size: 23px;
            font-weight: 900;
        }

        .brand-name {
            font-size: 19px;
            font-weight: 800;
            letter-spacing: -.5px;
        }

        .brand-caption {
            font-size: 11px;
            color: #bdbdbd;
            margin-top: 5px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .brand-content {
            margin-top: 65px;
            margin-bottom: 65px;
        }

        .eyebrow {
            display: inline-block;
            padding: 9px 13px;
            border: 1px solid #494949;
            border-radius: 30px;
            font-size: 11px;
            color: var(--yellow);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 25px;
        }

        .brand-content h1 {
            font-size: clamp(32px, 4vw, 43px);
            line-height: 1.2;
            letter-spacing: -1.8px;
            margin-bottom: 20px;
        }

        .brand-content h1 span {
            color: var(--yellow);
        }

        .brand-content p {
            font-size: 14px;
            color: #c4c4c4;
            line-height: 1.9;
            max-width: 330px;
        }

        .brand-line {
            width: 55px;
            height: 4px;
            background: var(--yellow);
            border-radius: 5px;
            margin-top: 30px;
        }

        .brand-footer {
            font-size: 12px;
            color: #999999;
            line-height: 1.8;
        }

        .form-panel {
            padding: 55px 52px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-top {
            margin-bottom: 37px;
        }

        .form-tag {
            font-size: 11px;
            font-weight: 800;
            color: #8b7300;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .form-top h2 {
            font-size: 31px;
            letter-spacing: -1px;
            margin-bottom: 12px;
        }

        .form-top p {
            color: var(--gray);
            font-size: 13px;
            line-height: 1.7;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .input-wrap {
            position: relative;
        }

        .form-control {
            width: 100%;
            height: 53px;
            border: 1px solid #dedede;
            border-radius: 10px;
            background: #fff;
            padding: 0 15px;
            font-size: 14px;
            color: var(--black);
            outline: none;
            transition: border .2s, box-shadow .2s;
        }

        .form-control:focus {
            border-color: #d8b500;
            box-shadow: 0 0 0 4px rgba(255, 212, 0, .16);
        }

        .password-input {
            padding-right: 70px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 13px;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            padding: 7px;
        }

        .toggle-password:hover {
            color: #000;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: -1px;
            margin-bottom: 25px;
            font-size: 12px;
            color: #666;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember input {
            accent-color: #d8b500;
            width: 15px;
            height: 15px;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            height: 54px;
            background: var(--yellow);
            color: var(--black);
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: background .2s, transform .2s, box-shadow .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login:hover {
            background: #edc500;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 212, 0, .25);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: .7;
            cursor: wait;
            transform: none;
        }

        .form-bottom {
            text-align: center;
            color: #999;
            font-size: 11px;
            margin-top: 30px;
            line-height: 1.8;
        }

        .form-bottom strong {
            color: #333;
        }

        .alert-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .48);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 1000;
            animation: appear .2s ease;
        }

        .alert-box {
            width: 100%;
            max-width: 380px;
            background: #fff;
            border-radius: 18px;
            padding: 32px 27px 27px;
            text-align: center;
            box-shadow: 0 20px 70px rgba(0, 0, 0, .2);
            animation: pop .25s ease;
        }

        .alert-icon {
            width: 62px;
            height: 62px;
            border-radius: 50%;
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 27px;
            font-weight: 800;
        }

        .alert-icon.error {
            background: #fff0ed;
            color: #d93025;
        }

        .alert-icon.warning {
            background: #fff7d6;
            color: #9a7600;
        }

        .alert-icon.info {
            background: #eeeeee;
            color: #444;
        }

        .alert-box h3 {
            font-size: 21px;
            margin-bottom: 10px;
        }

        .alert-box p {
            font-size: 13px;
            color: #666;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .alert-close {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 9px;
            background: var(--yellow);
            font-weight: 800;
            cursor: pointer;
            font-size: 13px;
        }

        .alert-close:hover {
            background: #edc500;
        }

        .shake {
            animation: shake .35s ease;
        }

        @keyframes appear {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes pop {
            from { opacity: 0; transform: scale(.94); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        @media (max-width: 760px) {
            body {
                padding: 16px;
            }

            .login-wrapper {
                max-width: 460px;
                min-height: auto;
                grid-template-columns: 1fr;
                border-radius: 17px;
            }

            .brand-panel {
                padding: 25px 27px;
                min-height: 190px;
            }

            .logo-symbol {
                width: 42px;
                height: 42px;
            }

            .brand-content {
                margin: 28px 0 12px;
            }

            .brand-content h1 {
                font-size: 27px;
                max-width: 300px;
            }

            .brand-content p,
            .brand-line,
            .brand-footer,
            .eyebrow {
                display: none;
            }

            .brand-panel:before {
                width: 180px;
                height: 180px;
                right: -100px;
                bottom: -95px;
                border-width: 35px;
            }

            .brand-panel:after {
                width: 90px;
                height: 90px;
                right: -50px;
                top: 70px;
            }

            .form-panel {
                padding: 35px 27px;
            }

            .form-top {
                margin-bottom: 27px;
            }

            .form-top h2 {
                font-size: 27px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *:before, *:after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <section class="brand-panel">
        <div class="brand-logo">
            <div class="logo-symbol">M.</div>
            <div>
                <div class="brand-name">MAHIRA PRINTING</div>
                <div class="brand-caption">Printing & Creative</div>
            </div>
        </div>

        <div class="brand-content">
            <div class="eyebrow">Administrator Access</div>
            <h1>Kelola bisnis.<br>Berikan hasil <span>terbaik.</span></h1>
            <p>
                Selamat datang di panel pengelolaan Mahira Printing.
                Kelola produk, layanan, dan portofolio dalam satu tempat.
            </p>
            <div class="brand-line"></div>
        </div>

        <div class="brand-footer">
            &copy; <?php echo date('Y'); ?> Mahira Printing.<br>
            Sistem pengelolaan website.
        </div>
    </section>

    <main class="form-panel">
        <div class="form-top">
            <div class="form-tag">Admin Panel</div>
            <h2>Selamat datang!</h2>
            <p>Masuk menggunakan akun admin untuk melanjutkan.</p>
        </div>

        <form method="POST" action="" id="loginForm" autocomplete="on">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <input
                        type="text"
                        id="username"
                        name="username"
                        class="form-control"
                        placeholder="Masukkan username"
                        value="<?php echo isset($_POST['username']) && $error_type != 'database' ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                        autocomplete="username"
                        maxlength="50"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control password-input"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                    <button
                        type="button"
                        class="toggle-password"
                        id="togglePassword"
                        aria-label="Tampilkan password"
                    >Lihat</button>
                </div>
            </div>

            <div class="form-options">
                <label class="remember">
                    <input type="checkbox" id="remember">
                    <span>Lihat username tersimpan</span>
                </label>
            </div>

            <button type="submit" class="btn-login" id="loginButton">
                <span id="buttonText">Masuk ke Dashboard</span>
                <span aria-hidden="true">&rarr;</span>
            </button>
        </form>

        <div class="form-bottom">
            Akses terbatas untuk pengelola website.<br>
            <strong>MAHIRA PRINTING</strong> &middot; Admin Area
        </div>
    </main>
</div>

<?php if ($error != "") { ?>
<div class="alert-overlay" id="alertOverlay" role="alertdialog" aria-modal="true" aria-labelledby="alertTitle">
    <div class="alert-box">
        <div class="alert-icon <?php echo $error_type == 'database' ? 'info' : ($error_type == 'empty' ? 'warning' : 'error'); ?>">
            <?php echo $error_type == 'database' ? '!' : ($error_type == 'empty' ? '!' : '×'); ?>
        </div>
        <h3 id="alertTitle">
            <?php
            if ($error_type == 'username') {
                echo 'Username Salah';
            } elseif ($error_type == 'password') {
                echo 'Password Salah';
            } elseif ($error_type == 'empty') {
                echo 'Form Belum Lengkap';
            } else {
                echo 'Terjadi Kesalahan';
            }
            ?>
        </h3>
        <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <button type="button" class="alert-close" id="alertClose">Mengerti, coba lagi</button>
    </div>
</div>
<?php } ?>

<script>
(function () {
    var passwordInput = document.getElementById('password');
    var toggleButton = document.getElementById('togglePassword');
    var loginForm = document.getElementById('loginForm');
    var loginButton = document.getElementById('loginButton');
    var buttonText = document.getElementById('buttonText');
    var usernameInput = document.getElementById('username');
    var rememberCheckbox = document.getElementById('remember');

    toggleButton.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.textContent = 'Sembunyi';
            toggleButton.setAttribute('aria-label', 'Sembunyikan password');
        } else {
            passwordInput.type = 'password';
            toggleButton.textContent = 'Lihat';
            toggleButton.setAttribute('aria-label', 'Tampilkan password');
        }
    });

    var overlay = document.getElementById('alertOverlay');
    var closeAlert = document.getElementById('alertClose');

    function dismissAlert() {
        if (overlay) {
            overlay.style.opacity = '0';
            overlay.style.transition = 'opacity .2s ease';
            window.setTimeout(function () {
                if (overlay.parentNode) {
                    overlay.parentNode.removeChild(overlay);
                }
                if (passwordInput.value === '') {
                    passwordInput.focus();
                } else {
                    passwordInput.focus();
                }
            }, 200);
        }
    }

    if (closeAlert) {
        closeAlert.addEventListener('click', dismissAlert);
    }

    if (overlay) {
        overlay.addEventListener('click', function (event) {
            if (event.target === overlay) {
                dismissAlert();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                dismissAlert();
            }
        });

        window.setTimeout(function () {
            if (overlay.parentNode) {
                closeAlert.focus();
            }
        }, 100);
    }

    rememberCheckbox.addEventListener('change', function () {
        if (this.checked) {
            usernameInput.setAttribute('autocomplete', 'on');
            usernameInput.focus();
        } else {
            usernameInput.setAttribute('autocomplete', 'off');
        }
    });

    loginForm.addEventListener('submit', function () {
        if (!loginForm.checkValidity()) {
            return;
        }

        loginButton.disabled = true;
        buttonText.textContent = 'Memeriksa akun...';
    });
})();
</script>

</body>
</html>
