<?php
session_start();
// Pastikan path ini benar: KELUAR dari folder 'process', MASUK ke 'config'
require '../config/db.php';

// Keamanan: Pastikan pengguna ada di tahap "pending" (sudah lolos password)
// Jika tidak ada session pending, tendang ke login
if (!isset($_SESSION['pending_user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

// 1. Ambil data dari form dan session
$token_input = $_POST['token'];
$user_id = $_SESSION['pending_user_id'];

try {
    // 2. Cek ke database:
    // - Apakah ID user cocok?
    // - Apakah token-nya cocok?
    // - Apakah token-nya BELUM kedaluwarsa (token_expire > NOW())?
    $sql = "SELECT * FROM users WHERE id = :id AND token = :token AND token_expire > NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $user_id,
        ':token' => $token_input
    ]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 3. Logika Hasil Pengecekan
    if ($user) {
        // BERHASIL! Token benar dan masih valid
        
        // Buat sesi login yang SEBENARNYA (berlaku 24 jam)
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['batalyon'] = $user['batalyon']; // Ini penting untuk kadet
        $_SESSION['login_time'] = time(); // Penanda waktu login 24 jam

        session_regenerate_id(true);
        // Bersihkan data "pending"
        unset($_SESSION['pending_user_id']);
        
        // Bersihkan token dari database agar tidak bisa dipakai lagi
        $clear_stmt = $pdo->prepare("UPDATE users SET token = NULL, token_expire = NULL WHERE id = :id");
        $clear_stmt->execute([':id' => $user_id]);

        // Arahkan ke dashboard piket
        header('Location: ../pages/dashboard_piket.php');
        exit;

    } else {
        // GAGAL! Token salah atau kedaluwarsa
        
        // Hapus sesi pending agar mereka mengulang dari awal
        unset($_SESSION['pending_user_id']);

        $_SESSION['error_message'] = "Token salah atau telah kedaluwarsa. Silakan coba login kembali.";
        // Arahkan kembali ke login.php, BUKAN ke verify_token.php
        header('Location: ../pages/login.php');
        exit;
    }

} catch (PDOException $e) {
    // Tangani jika ada error database
    unset($_SESSION['pending_user_id']);
    $_SESSION['error_message'] = "Error database: " . $e->getMessage();
    header('Location: ../pages/login.php');
    exit;
}
?>