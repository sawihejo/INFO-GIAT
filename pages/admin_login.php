<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Administrasi Piket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 100%; max-width: 450px; border: none; border-radius: 0.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); }
        .btn-primary { background-color: #dc3545; border: none; padding: 0.75rem; font-weight: 500; }
        .btn-primary:hover { background-color: #bb2d3b; }
        .admin-badge { background-color: #dc3545; color: white; padding: 0.25rem 0.75rem; border-radius: 0.5rem; font-size: 0.875rem; }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="card login-card p-4 p-md-5">
            <div class="card-body">
                <div class="text-center mb-4">
                    <span class="admin-badge">ADMIN ONLY</span>
                </div>
                <h3 class="card-title text-center mb-4">Login Administrator</h3>
                <p class="text-center text-muted mb-4">Akses khusus untuk administrator sistem</p>

                <?php
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
                    unset($_SESSION['error_message']);
                }
                ?>

                <form action="../process/admin_login_process.php" method="POST">
                    
                    <div class="mb-3">
                        <label for="nama" class="form-label">Username / NIP</label>
                        <input type="text" class="form-control" id="nama" name="nama" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Login sebagai Admin</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="login.php" class="text-muted text-decoration-none">Kembali ke Login Umum</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

