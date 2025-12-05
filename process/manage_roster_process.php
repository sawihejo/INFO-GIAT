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
$ketyon_array = isset($_POST['nama_ketyon']) ? $_POST['nama_ketyon'] : [];
$korsis_array = isset($_POST['nama_korsis']) ? $_POST['nama_korsis'] : [];

// Parser helper: from array of "Name|Phone" to arrays
function split_names_and_contacts($arr) {
    $names = [];
    $contacts = [];
    foreach ($arr as $item) {
        $parts = explode('|', $item);
        $names[] = $parts[0];
        $contacts[] = isset($parts[1]) ? $parts[1] : '';
    }
    return [$names, $contacts];
}

list($names_ketyon, $contacts_ketyon) = split_names_and_contacts($ketyon_array);
list($names_korsis, $contacts_korsis) = split_names_and_contacts($korsis_array);

// Build grouped string with labels so it can be displayed as sections later
$nama_groups = [];
$kontak_groups = [];
if (!empty($names_ketyon)) {
    $nama_groups[] = 'Ketyon: ' . implode(', ', $names_ketyon);
    $kontak_groups[] = 'Ketyon: ' . implode(', ', $contacts_ketyon);
}
if (!empty($names_korsis)) {
    $nama_groups[] = 'Korsis: ' . implode(', ', $names_korsis);
    $kontak_groups[] = 'Korsis: ' . implode(', ', $contacts_korsis);
}

$nama_piket = implode("\n", $nama_groups); // store groups separated by newline
$kontak_piket = implode("\n", $kontak_groups);

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