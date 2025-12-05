<?php
session_start();
require '../config/db.php';

// Atur header JSON
header('Content-Type: application/json');
$response = ['success' => false, 'message' => 'Terjadi kesalahan.'];

// 1. Cek Keamanan & Metode Request
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'kadet') {
    $response['message'] = 'Akses ditolak. Sesi tidak valid.';
    echo json_encode($response);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Metode request tidak valid.';
    echo json_encode($response);
    exit;
}

// 2. Ambil data dari Session
$kadet_id = $_SESSION['user_id'];
$batalyon = $_SESSION['batalyon'];

// 3. Ambil data dari Form (POST)
$tanggal_kegiatan = $_POST['tanggal_kegiatan'] ?? null;
$isi_rencana = $_POST['isi_rencana'] ?? null;

// Validasi sederhana
if (empty($tanggal_kegiatan) || empty($isi_rencana)) {
    $response['message'] = 'Tanggal dan Isi Rencana wajib diisi.';
    echo json_encode($response);
    exit;
}

try {
    // 4. Siapkan Query SQL
    $sql = "INSERT INTO tbl_rencana (kadet_id, batalyon, tanggal_kegiatan, isi_rencana)
            VALUES (:kadet_id, :batalyon, :tanggal_kegiatan, :isi_rencana)";

    $stmt = $pdo->prepare($sql);

    // 5. Eksekusi Query
    $stmt->execute([
        ':kadet_id' => $kadet_id,
        ':batalyon' => $batalyon,
        ':tanggal_kegiatan' => $tanggal_kegiatan,
        ':isi_rencana' => $isi_rencana
    ]);

    // 6. Jika berhasil, update respons
    $response['success'] = true;
    $response['message'] = "Rencana kegiatan untuk tanggal $tanggal_kegiatan berhasil disimpan.";

} catch (PDOException $e) {
    // Tangani error database
    $response['message'] = "Gagal menyimpan data ke database: " . $e->getMessage();
}

// 7. Kirim respons JSON ke JavaScript
echo json_encode($response);
exit;
?>