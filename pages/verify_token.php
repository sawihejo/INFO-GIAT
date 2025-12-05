<?php
session_start();

// Keamanan: Jika pengguna belum lolos tahap 1 (login_process),
// tendang mereka kembali ke halaman login.
if (!isset($_SESSION['pending_user_id'])) {
    $_SESSION['error_message'] = "Akses tidak sah.";
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Token - Administrasi Piket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 450px; border: none; border-radius: 0.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
        .btn-primary { background-color: #0d6efd; border: none; padding: 0.75rem; font-weight: 500; }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="card login-card p-4 p-md-5">
            <div class="card-body">
                <h4 class="card-title text-center mb-3">Verifikasi Dua Langkah</h4>
                <p class="text-center text-muted mb-4">
                    Kami telah mengirimkan token 6 digit ke akun Telegram Anda. Silakan periksa dan masukkan di bawah ini.
                </p>

                <?php
                // Tampilkan pesan error jika ada (misal: token salah)
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
                    unset($_SESSION['error_message']);
                }
                ?>

                <form action="../process/token_process.php" method="POST">
                    <div class="mb-3">
                        <label for="token" class="form-label">Token 6 Digit</label>
                        <input type="text" class="form-control text-center" id="token" name="token" 
                            maxlength="6" 
                            pattern="\d{6}" 
                            title="Token harus 6 digit angka" 
                            required 
                            autocomplete="off">
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Verifikasi & Masuk</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <a href="login.php" class="text-decoration-none">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>