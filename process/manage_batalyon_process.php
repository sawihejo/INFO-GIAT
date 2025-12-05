<?php
session_start();
require '../config/db.php';
// Cek CSRF Token

// 1. Cek Keamanan: Hanya ADMIN yang boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error_message'] = "Akses ditolak.";
    header('Location: ../pages/login.php');
    exit;
}

// 2. Cek apakah data dikirim (method POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 3. Ambil data dari form modal
    $batalyon_id = $_POST['batalyon_id'];
    $jumlah = $_POST['jumlah_total_personil'];

    try {
        // 4. Siapkan Query UPDATE
        $sql = "UPDATE tbl_batalyon 
                SET jumlah_total_personil = :jumlah 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        
        // 5. Eksekusi Query
        $stmt->execute([
            ':jumlah' => $jumlah,
            ':id' => $batalyon_id
        ]);

        // 6. Redirect dengan pesan sukses
        $_SESSION['success_message'] = "Jumlah total personil berhasil diperbarui.";
        header('Location: ../pages/manage_batalyon.php');
        exit;

    } catch (PDOException $e) {
        // Tangani error
        $_SESSION['error_message'] = "Gagal memperbarui data: " . $e->getMessage();
        header('Location: ../pages/manage_batalyon.php');
        exit;
    }
} else {
    // Jika ada yang akses file ini langsung via URL
    $_SESSION['error_message'] = "Akses tidak sah.";
    header('Location: ../pages/manage_batalyon.php');
    exit;
}
?>