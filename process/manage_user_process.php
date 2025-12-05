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
$nama = $_POST['nama'];
$username = isset($_POST['username']) ? trim($_POST['username']) : null;
$role = $_POST['role'];
$telegram_id = !empty($_POST['telegram_id']) ? $_POST['telegram_id'] : null;
$phone = !empty($_POST['phone']) ? $_POST['phone'] : null;
$password = $_POST['password'];

// 3. Tentukan Batalyon (hanya jika role 'kadet')
$batalyon = ($role === 'kadet') ? $_POST['batalyon'] : null;

// 4. Tentukan Mode: Tambah (Add) vs. Edit
$is_edit = isset($_POST['id']) && !empty($_POST['id']);
$user_id = $is_edit ? $_POST['id'] : null;

try {
    if ($is_edit) {
        // --- LOGIKA UPDATE (EDIT) ---
        
        $sql_parts = [
            "nama = :nama",
            "username = :username",
            "role = :role",
            "batalyon = :batalyon",
            "telegram_id = :telegram_id",
            "phone = :phone"
        ];

        $params = [
            ':nama' => $nama,
            ':username' => $username,
            ':role' => $role,
            ':batalyon' => $batalyon,
            ':telegram_id' => $telegram_id,
            ':phone' => $phone,
            ':id' => $user_id
        ];

        // Cek apakah password diisi (jika ya, update passwordnya)
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $sql_parts[] = "password = :password";
            $params[':password'] = $password_hash;
        }
        
        // Gabungkan query
        $sql = "UPDATE users SET " . implode(", ", $sql_parts) . " WHERE id = :id";
        $message = 'Data pengguna berhasil diperbarui.';

    } else {
        // --- LOGIKA INSERT (TAMBAH BARU) ---
        
        // Password wajib untuk pengguna baru
        if (empty($password)) {
             $_SESSION['error_message'] = "Password wajib diisi untuk pengguna baru.";
             header('Location: ../pages/manage_user.php');
             exit;
        }

        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (nama, username, password, role, batalyon, telegram_id, phone) 
            VALUES (:nama, :username, :password, :role, :batalyon, :telegram_id, :phone)";
        
        $params = [
            ':nama' => $nama,
            ':username' => $username,
            ':password' => $password_hash,
            ':role' => $role,
            ':batalyon' => $batalyon,
            ':telegram_id' => $telegram_id,
            ':phone' => $phone
        ];
        $message = 'Pengguna baru berhasil ditambahkan.';
    }

    // 5. Eksekusi Query
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // 6. Redirect dengan pesan sukses
    $_SESSION['success_message'] = $message;
    header('Location: ../pages/dashboard_admin.php');
    exit;

} catch (PDOException $e) {
    // Tangani error (misal: telegram_id duplikat)
    if ($e->getCode() == 23000) { // Kode SQL untuk 'Integrity constraint violation' (UNIQUE)
        $_SESSION['error_message'] = "Gagal: ID Telegram tersebut sudah terdaftar. Gunakan ID lain.";
    } else {
        $_SESSION['error_message'] = "Terjadi error database: " . $e->getMessage();
    }
    
    // Kembalikan ke form (jika edit, kembalikan ke form edit)
    $redirect_url = $is_edit ? '../pages/manage_user.php?id=' . $user_id : '../pages/manage_user.php';
    header("Location: $redirect_url");
    exit;
}
?>