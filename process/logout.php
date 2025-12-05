<?php
session_start(); // Ambil sesi yang ada
session_unset(); // Hapus semua variabel sesi
session_destroy(); // Hancurkan sesi

// Arahkan kembali ke halaman login
header('Location: ../pages/login.php');
exit;
?>