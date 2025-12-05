<?php
session_start();
require '../config/db.php';



// 1. Cek Keamanan: Hanya ADMIN yang boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error_message'] = "Akses ditolak.";
    header('Location: ../pages/login.php');
    exit;
}

// 2. Ambil data dari form
$user_input_id = $_SESSION['user_id'];
$tanggal_piket = $_POST['tanggal_piket'];
$batalyon = $_POST['batalyon']; // Batalyon yang dipilih
$nama_piket_array = $_POST['nama_piket']; // Ini sekarang ARRAY, cth: ["Budi|0812", "Dono|0813"]

// 3. Olah array menjadi string
$nama_list = [];
$kontak_list = [];

foreach ($nama_piket_array as $piket_data) {
    // Pisahkan string "Nama|Kontak"
    $parts = explode('|', $piket_data);
    $nama_list[] = $parts[0]; // Ambil nama
    $kontak_list[] = $parts[1]; // Ambil kontak
}

// Gabungkan menjadi string yang siap disimpan ke DB
$nama_piket = implode(', ', $nama_list);
$kontak_piket = implode(', ', $kontak_list);

try {
    // 3. Query Canggih: INSERT ... ON DUPLICATE KEY UPDATE
    // Ini akan:
    // - Mencoba INSERT data baru.
    // - JIKA data untuk (tanggal, batalyon) sudah ada (error UNIQUE KEY),
    // - Dia akan beralih ke mode UPDATE dan perbarui datanya.
    
    $sql = "INSERT INTO tbl_piket_jaga (tanggal_piket, batalyon, nama_piket, kontak_piket, user_input_id)
            VALUES (:tanggal, :batalyon, :nama, :kontak, :user_id)
            ON DUPLICATE KEY UPDATE
                nama_piket = VALUES(nama_piket),
                kontak_piket = VALUES(kontak_piket),
                user_input_id = VALUES(user_input_id)";
                
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':tanggal' => $tanggal_piket,
        ':batalyon' => $batalyon,
        ':nama' => $nama_piket,
        ':kontak' => $kontak_piket,
        ':user_id' => $user_input_id
    ]);

    // 4. Redirect dengan pesan sukses
    $_SESSION['success_message'] = "Jadwal piket Batalyon $batalyon untuk tanggal $tanggal_piket berhasil disimpan.";
    header('Location: ../pages/manage_roster.php');
    exit;

} catch (PDOException $e) {
    // Tangani error
    $_SESSION['error_message'] = "Gagal menyimpan jadwal: " . $e->getMessage();
    header('Location: ../pages/manage_roster.php');
    exit;
}
?>