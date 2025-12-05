<?php

// ---- SESUAIKAN PENGATURAN DI BAWAH INI ----
date_default_timezone_set('Asia/Jakarta');
$host = 'localhost';
$dbname = 'piket_batalyon'; // Ganti ini dengan nama database Anda
$username = 'root';
$password = '';

// ---- AKHIR PENGATURAN ----
$telegram_bot_token = '8056176445:AAEZ2t4kBKRYRY_rLMGRAgggjKrOYV63edg';

// Kode untuk koneksi ke database
try {
    // Membuat koneksi PDO baru
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);

    // Mengatur mode error PDO ke exception
    // Ini akan menampilkan error SQL jika terjadi (membantu debugging)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Mengatur mode pengambilan data default ke array asosiatif
    // Ini membuat hasil query lebih mudah dibaca (e.g., $row['nama'] bukan $row[1])
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Jika koneksi gagal, hentikan script dan tampilkan pesan error
    die("Koneksi ke database gagal: " . $e->getMessage());
}

// Jika berhasil, file ini akan menyediakan variabel $pdo
// yang siap digunakan oleh file lain yang memanggilnya.
?>