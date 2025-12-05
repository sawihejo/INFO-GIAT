<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administrasi Piket</title>
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
                <h3 class="card-title text-center mb-4">Administrasi Piket</h3>
                <p class="text-center text-muted mb-4">Silakan login untuk melanjutkan</p>
                <div class="text-center mb-3">
                    <a href="admin_login.php" class="text-decoration-none small">Login sebagai Admin</a>
                </div>

                <?php
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error_message'] . '</div>';
                    unset($_SESSION['error_message']);
                }
                ?>

                <form action="../process/login_process.php" method="POST">
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">Username / NIP</label>
                        <input type="text" class="form-control" id="username" name="nama" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Login Sebagai</label>
                        <select class="form-select" id="role" name="role" required onchange="toggleBatalyon()">
                            <option value="" disabled selected>Pilih peran...</option>
                            <option value="organik">Organik / Atasan</option>
                            <option value="kadet">Kadet / Piket</option>
                        </select>
                    </div>

                    <div class="mb-3" id="batalyon-group" style="display: none;">
                        <label for="batalyon" class="form-label">Batalyon</label>
                        <select class="form-select" id="batalyon" name="batalyon">
                            <option value="I">I</option>
                            <option value="II">II</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                        </select>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleBatalyon() {
            var roleSelect = document.getElementById('role');
            var batalyonGroup = document.getElementById('batalyon-group');
            if (roleSelect.value === 'kadet') {
                batalyonGroup.style.display = 'block';
            } else {
                batalyonGroup.style.display = 'none';
            }
        }
    </script>
</body>
</html>