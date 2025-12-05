<?php
session_start();
require '../config/db.php';

// Atur header agar browser tahu ini adalah JSON
header('Content-Type: application/json');

// Siapkan array untuk respons
$response = ['success' => false, 'message' => 'Terjadi kesalahan.'];

// 1. Cek Keamanan: Hanya KADET yang boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kadet') {
    $response['message'] = 'Akses ditolak. Sesi tidak valid.';
    echo json_encode($response);
    exit;
}

// Cek jika metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Metode request tidak valid.';
    echo json_encode($response);
    exit;
}

// 2. Ambil data dari Session
$kadet_id = $_SESSION['user_id'];
$batalyon = $_SESSION['batalyon'];

// 3. Ambil data dari Form (POST)
$jenis_apel = $_POST['jenis_apel'] ?? null;
$tanggal_apel = $_POST['tanggal_apel'] ?? null;
$keterangan = $_POST['keterangan'] ?? null;
$jumlah = $_POST['jumlah'] ?? null;
$nama_nama = $_POST['nama_nama'] ?? null;

// Validasi sederhana (pastikan field wajib diisi)
if (empty($jenis_apel) || empty($tanggal_apel) || empty($keterangan) || empty($jumlah) || empty($nama_nama)) {
    $response['message'] = 'Semua field wajib diisi.';
    echo json_encode($response);
    exit;
}


try {
    // 4. Siapkan Query SQL
    $sql = "INSERT INTO tbl_apel (kadet_id, batalyon, jenis_apel, tanggal_apel, keterangan, jumlah, nama_nama)
            VALUES (:kadet_id, :batalyon, :jenis_apel, :tanggal_apel, :keterangan, :jumlah, :nama_nama)";
    
    $stmt = $pdo->prepare($sql);
    
    // 5. Eksekusi Query
    $stmt->execute([
        ':kadet_id' => $kadet_id,
        ':batalyon' => $batalyon,
        ':jenis_apel' => $jenis_apel,
        ':tanggal_apel' => $tanggal_apel,
        ':keterangan' => $keterangan,
        ':jumlah' => $jumlah,
        ':nama_nama' => $nama_nama
    ]);

    // 6. Jika berhasil, update respons
    $response['success'] = true;
    $response['message'] = "Data \"$keterangan\" berhasil disimpan.";

} catch (PDOException $e) {
    // Tangani error database
    $response['message'] = "Gagal menyimpan data ke database: " . $e->getMessage();
    // (Opsional) Log error $e->getMessage() ke file log server untuk debugging
}

// 7. Kirim respons JSON ke JavaScript
echo json_encode($response);
exit;
?>