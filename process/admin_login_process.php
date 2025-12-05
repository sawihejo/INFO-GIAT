<?php
session_start();
require '../config/db.php';

// 1. Ambil data dari form
if (!isset($_POST['nama']) || !isset($_POST['password'])) {
    $_SESSION['error_message'] = "Username dan Password harus diisi.";
    header('Location: ../pages/admin_login.php');
    exit;
}

$nama = $_POST['nama'];
$password = $_POST['password'];

try {
    // 2. Query khusus untuk admin - hanya role 'admin' yang bisa login
    $sql = "SELECT * FROM users WHERE nama = :nama AND role = 'admin'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nama' => $nama]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 3. Verifikasi User dan Password
    if ($user && password_verify($password, $user['password'])) {
        // Login berhasil - set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_time'] = time(); // Penanda waktu login

        session_regenerate_id(true);

        // Redirect ke dashboard admin
        header("Location: ../pages/dashboard_admin.php");
        exit;
        
    } else {
        // Jika username atau password salah
        $_SESSION['error_message'] = "Username atau Password salah, atau akun ini bukan administrator.";
        header('Location: ../pages/admin_login.php');
        exit;
    }

} catch (PDOException $e) {
    // Tangani error database
    $_SESSION['error_message'] = "Terjadi masalah koneksi: " . $e->getMessage();
    header('Location: ../pages/admin_login.php');
    exit;
}
?>

