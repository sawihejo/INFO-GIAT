<?php
session_start();
require '../config/db.php';

// 1. Cek Keamanan: Hanya ADMIN yang boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error_message'] = "Akses ditolak.";
    header('Location: ../pages/login.php');
    exit;
}

// 2. Ambil ID dari URL
if (!isset($_GET['user_id']) || empty($_POST['user_id'])) {
    $_SESSION['error_message'] = "ID pengguna tidak valid.";
    header('Location: ../pages/dashboard_admin.php');
    exit;
}

$user_id = $_GET['user_id'];

// 3. PENTING: Cegah admin menghapus dirinya sendiri
if ($user_id == $_SESSION['user_id']) {
    $_SESSION['error_message'] = "Anda tidak dapat menghapus akun Anda sendiri!";
    header('Location: ../pages/dashboard_admin.php');
    exit;
}

try {
    // 4. Eksekusi Query Hapus
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);

    // 5. Redirect dengan pesan sukses
    $_SESSION['success_message'] = "Pengguna berhasil dihapus.";
    header('Location: ../pages/dashboard_admin.php');
    exit;

} catch (PDOException $e) {
    // Tangani error
    $_SESSION['error_message'] = "Gagal menghapus pengguna: " . $e->getMessage();
    header('Location: ../pages/dashboard_admin.php');
    exit;
}
?>