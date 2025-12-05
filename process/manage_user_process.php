<?php
session_start();
require '../config/db.php';

// 1. Cek Keamanan: Hanya ADMIN yang boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error_message'] = "Akses ditolak.";
    header('Location: ../pages/login.php');
    exit;
}

// 2. Ambil data dari form dengan AMAN (cegah error undefined)
$nama = $_POST['nama'] ?? '';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$role = $_POST['role'] ?? '';
$telegram_id = !empty($_POST['telegram_id']) ? $_POST['telegram_id'] : null;
$phone = !empty($_POST['phone']) ? $_POST['phone'] : null;
$password = $_POST['password'] ?? '';

// 3. Tentukan Batalyon
// HANYA ambil batalyon jika role-nya 'kadet' DAN inputnya dikirim
$batalyon = ($role === 'kadet' && isset($_POST['batalyon'])) ? $_POST['batalyon'] : null;

// 4. Tentukan Mode: Tambah (Add) vs. Edit
$is_edit = isset($_POST['id']) && !empty($_POST['id']);
$user_id = $is_edit ? $_POST['id'] : null;

try {
    // Pastikan kolom `username` ada di tabel `users`. Jika belum, coba tambahkan.
    $has_username_col = false;
    try {
        $col = $pdo->query("SHOW COLUMNS FROM users LIKE 'username'")->fetch();
        if ($col) {
            $has_username_col = true;
        } else {
            // Coba tambahkan kolom username (safe default, tanpa UNIQUE)
            try {
                $pdo->exec("ALTER TABLE users ADD COLUMN username VARCHAR(100) DEFAULT NULL");
                $has_username_col = true;
            } catch (PDOException $alterEx) {
                // Tandai gagal menambah kolom untuk dijelaskan ke user
                $has_username_col = false;
                $username_alter_failed = true;
            }
        }
    } catch (PDOException $innerEx) {
        // Jika gagal memeriksa kolom sama sekali, anggap kolom tidak ada
        $has_username_col = false;
        $username_alter_failed = true;
    }

    // Jika upaya membuat kolom username gagal, beri pesan jelas dan hentikan proses.
    if (!empty($username_alter_failed) && !$has_username_col) {
        $_SESSION['error_message'] = "Kolom 'username' tidak ditemukan di tabel 'users' dan sistem tidak dapat menambahkannya secara otomatis.\n" .
            "Silakan jalankan SQL manual ini di phpMyAdmin atau MySQL client (pastikan backup terlebih dahulu):\n" .
            "ALTER TABLE users ADD COLUMN username VARCHAR(100) DEFAULT NULL;";
        // Redirect kembali ke form agar admin dapat mengeksekusi SQL manual terlebih dahulu.
        header('Location: ../pages/manage_user.php');
        exit;
    }

    if ($is_edit) {
        // --- LOGIKA UPDATE (EDIT) ---
        $sql_parts = [
            "nama = :nama",
            "role = :role",
            "batalyon = :batalyon",
            "telegram_id = :telegram_id",
            "phone = :phone"
        ];

        $params = [
            ':nama' => $nama,
            ':role' => $role,
            ':batalyon' => $batalyon,
            ':telegram_id' => $telegram_id,
            ':phone' => $phone,
            ':id' => $user_id
        ];

        if ($has_username_col) {
            // Sisipkan username jika kolom tersedia
            array_splice($sql_parts, 1, 0, "username = :username");
            $params[':username'] = $username;
        }

        // Cek apakah Password diisi?
        if (!empty($password)) {
            $sql_parts[] = "password = :password";
            $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql = "UPDATE users SET " . implode(', ', $sql_parts) . " WHERE id = :id";
        $message = 'Data pengguna berhasil diperbarui.';

    } else {
        // --- LOGIKA INSERT (BARU) ---
        
        // Validasi Password Wajib untuk User Baru
        if (empty($password)) {
            $_SESSION['error_message'] = "Password wajib diisi untuk pengguna baru.";
            header('Location: ../pages/manage_user.php');
            exit;
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        // Bangun INSERT dinamis: sertakan username hanya jika kolom tersedia
        $columns = ['nama', 'password', 'role', 'batalyon', 'telegram_id', 'phone'];
        $placeholders = [':nama', ':password', ':role', ':batalyon', ':telegram_id', ':phone'];
        $params = [
            ':nama' => $nama,
            ':password' => $password_hash,
            ':role' => $role,
            ':batalyon' => $batalyon,
            ':telegram_id' => $telegram_id,
            ':phone' => $phone
        ];

        if ($has_username_col) {
            array_splice($columns, 1, 0, 'username');
            array_splice($placeholders, 1, 0, ':username');
            $params[':username'] = $username;
        }

        $sql = "INSERT INTO users (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
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
    // Tangani error (misal: Username/Telegram ID duplikat)
    if ($e->getCode() == 23000) { 
        $_SESSION['error_message'] = "Gagal: Username atau ID Telegram sudah digunakan orang lain.";
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan database: " . $e->getMessage();
    }
    
    // Kembalikan ke halaman form (jika edit bawa ID-nya)
    if ($is_edit) {
        header("Location: ../pages/manage_user.php?id=$user_id");
    } else {
        header("Location: ../pages/manage_user.php");
    }
    exit;
}
?>