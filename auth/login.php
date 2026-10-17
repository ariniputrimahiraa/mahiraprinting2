```php
<?php
session_start();
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
                $error = "Username yang Anda masukkan salah.";
                $error_type = "username";
            } elseif (!password_verify($password, $admin['password'])) {
                $error = "Password yang Anda masukkan salah.";
                $error_type = "password";
            } else {
                session_regenerate_id(true);

                $_SESSION['id_admin'] = $admin['id_admin'];
                $_SESSION['nama_admin'] = $admin['nama'];
                $_SESSION['username_admin'] = $admin['username'];

                header("Location: dashboard.php");
                exit;
            }
        } catch (Exception $e) {
            error_log("Login Admin Error: " . $e->getMessage());
            $error = "Terjadi masalah saat mengakses database. Periksa konfigurasi dan struktur tabel admin.";
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
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #111;
            color: #fff;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 38px 32px;
            background: #fff;
            color: #222;
            border-radius: 14px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo h1 {
            font-size: 27px;
            font-weight: 800;
            color: #111;
            letter-spacing: -0.8px;
        }

        .logo h1 span {
            color: #e5b900;
        }

        .logo p {
            margin-top: 8px;
            color: #777;
            font-size: 13px;
        }

        .admin-label {
            display: inline-block;
            margin-top: 15px;
            padding: 7px 13px;
            border-radius: 5px;
            background: #fff4bd;
            color: #6b5500;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 19px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #ddd;
            border-radius: 7px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group input:focus {
            border-color: #e5b900;
            box-shadow: 0 0 0 3px rgba(255, 212, 0, 0.18);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 75px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #555;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
        }

        .login-button {
            width: 100%;
            padding: 14px;
            margin-top: 5px;
            border: none;
            border-radius: 7px;
            background: #ffd400;
            color: #111;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
        }

        .login-button:hover {
            background: #edc400;
        }

        .login-button:active {
            transform: scale(0.99);
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-bottom: 20px;
            padding: 12px 13px;
            border-radius: 7px;
            font-size: 12px;
            line-height: 1.5;
        }

        .alert-error {
            background: #fff0f0;
            border: 1px solid #f1c4c4;
            color: #a32121;
        }

        .alert-success {
            background: #edf9f0;
            border: 1px solid #b9e4c4;
            color: #216b35;
        }

        .footer-text {
            margin-top: 24px;
            text-align: center;
            color: #888;
            font-size: 11px;
            line-height: 1.6;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 22px;
            }

            .logo h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="logo">
        <h1>MAHIRA <span>PRINTING</span></h1>
        <p>Kelola bisnis percetakan dengan mudah</p>
        <div class="admin-label">ADMIN PANEL</div>
    </div>

    <?php if ($error != "") { ?>
        <div class="alert alert-error" id="loginAlert" role="alert">
            <span><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
    <?php } ?>

    <form method="POST" action="" id="loginForm">
        <div class="form-group">
            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                placeholder="Masukkan username"
                value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                autocomplete="username"
                required
                autofocus
            >
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="password-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >
                <button
                    type="button"
                    class="toggle-password"
                    id="togglePassword"
                >Lihat</button>
            </div>
        </div>

        <button type="submit" class="login-button" id="loginButton">
            Masuk ke Dashboard
        </button>
    </form>

    <div class="footer-text">
        &copy; <?php echo date('Y'); ?> Mahira Printing.<br>
        Halaman khusus administrator.
    </div>
</div>

<script>
var togglePassword = document.getElementById('togglePassword');
var passwordInput = document.getElementById('password');

togglePassword.addEventListener('click', function () {
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        togglePassword.textContent = 'Sembunyikan';
    } else {
        passwordInput.type = 'password';
        togglePassword.textContent = 'Lihat';
    }
});

var loginForm = document.getElementById('loginForm');
var loginButton = document.getElementById('loginButton');

loginForm.addEventListener('submit', function () {
    if (loginForm.checkValidity()) {
        loginButton.textContent = 'Memeriksa...';
        loginButton.disabled = true;
    }
});
</script>

</body>
</html>
```