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
$kategori = $_POST['kategori'] ?? null;
$keterangan_lainnya = ($kategori === 'lainnya') ? ($_POST['keterangan_lainnya'] ?? null) : null;

// Validasi sederhana
if (empty($kategori)) {
    $response['message'] = 'Kategori wajib dipilih.';
    echo json_encode($response);
    exit;
}
if ($kategori === 'lainnya' && empty($keterangan_lainnya)) {
    $response['message'] = 'Keterangan wajib diisi untuk kategori Lainnya.';
    echo json_encode($response);
    exit;
}

// 4. Logika Upload File
if (isset($_FILES['file_dokumentasi']) && $_FILES['file_dokumentasi']['error'] == 0) {
    
    $file = $_FILES['file_dokumentasi'];
    $max_size = 5 * 1024 * 1024; // 5 MB
    $allowed_types = ['image/jpeg', 'image/png'];
    $upload_dir = '../uploads/'; // Target folder

    // Cek ukuran & tipe
    if ($file['size'] > $max_size) {
        $response['message'] = "File terlalu besar. Ukuran maksimal 5MB.";
        echo json_encode($response);
        exit;
    }
    if (!in_array($file['type'], $allowed_types)) {
        $response['message'] = "Tipe file tidak valid. Hanya .jpg, .jpeg, atau .png.";
        echo json_encode($response);
        exit;
    }

    // Buat nama file baru (format tanggal_waktu_batalyon...)
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $tanggal = date('Y-m-d');
    $waktu = date('H-i-s');
    $unik = substr(md5(time()), 0, 6);
    $new_filename = $tanggal . '_' . $waktu . '_Batalyon-' . $batalyon . '_' . $unik . '.' . $file_extension;
    $target_path = $upload_dir . $new_filename;
    $db_path = 'uploads/' . $new_filename;

    // 5. Pindahkan file
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        
        // 6. JIKA BERHASIL PINDAH, simpan ke Database
        try {
            $sql = "INSERT INTO tbl_dokumentasi (kadet_id, batalyon, kategori, keterangan_lainnya, file_path)
                    VALUES (:kadet_id, :batalyon, :kategori, :keterangan_lainnya, :file_path)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':kadet_id' => $kadet_id,
                ':batalyon' => $batalyon,
                ':kategori' => $kategori,
                ':keterangan_lainnya' => $keterangan_lainnya,
                ':file_path' => $db_path
            ]);

            // Update respons sukses
            $response['success'] = true;
            $response['message'] = "Dokumentasi kategori '$kategori' berhasil diunggah.";

        } catch (PDOException $e) {
            // Jika DB gagal, hapus file & update respons error
            unlink($target_path); 
            $response['message'] = "Gagal menyimpan data ke database: " . $e->getMessage();
        }
    } else {
        // Gagal memindahkan file
        $response['message'] = "Terjadi error saat memindahkan file yang diupload.";
    }
} else {
    // Tidak ada file atau ada error saat upload awal
    $error_code = $_FILES['file_dokumentasi']['error'] ?? 'Tidak ada file';
    $response['message'] = "Tidak ada file yang diupload atau terjadi error (Kode: $error_code).";
}

// 7. Kirim respons JSON ke JavaScript
echo json_encode($response);
exit;
?>