<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
// Header tambahan untuk kompatibilitas browser lama (terutama IE)
header("Cache-Control: post-check=0, pre-check=0", false);
// Header untuk HTTP/1.0
header("Pragma: no-cache");
// Memberitahu browser bahwa halaman ini sudah "kedaluwarsa" di masa lalu
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
// Selalu mulai session di baris paling atas
session_start();

$current_page = basename($_SERVER['PHP_SELF']);

// Halaman yang boleh diakses tanpa login (Hanya login dan verify)
$allowed_pages = ['login.php', 'admin_login.php', 'verify_token.php'];

if (!isset($_SESSION['user_id']) && !in_array($current_page, $allowed_pages)) {
    header('Location: ../pages/login.php');
    exit;
}


// 2. Cek Keamanan: Batas Waktu 24 Jam
if (isset($_SESSION['user_id']) && isset($_SESSION['login_time'])) {
    $sesi_berlaku = 24 * 60 * 60; // 24 jam dalam detik
    if (time() - $_SESSION['login_time'] > $sesi_berlaku) {
        // Jika sesi lebih dari 24 jam, hancurkan sesi
        session_unset();
        session_destroy();
        $_SESSION['error_message'] = "Sesi Anda telah berakhir. Silakan login kembali.";
        header('Location: ../pages/login.php'); // Path dari includes/ ke pages/
        exit;
    }
}


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' - Administrasi Piket' : 'Administrasi Piket'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link href="../assets/css/custom.css" rel="stylesheet"> 

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body>