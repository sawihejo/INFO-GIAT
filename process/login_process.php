<?php
session_start();
require '../config/db.php'; // Ini memuat $pdo dan $telegram_bot_token
require '../includes/telegram_sender.php'; // Ini memuat fungsi kirim_token_telegram

// 1. Ambil data dari form
if (!isset($_POST['nama']) || !isset($_POST['password']) || !isset($_POST['role'])) {
    $_SESSION['error_message'] = "Semua field harus diisi.";
    header('Location: ../pages/login.php');
    exit;
}

$nama = $_POST['nama'];
$password = $_POST['password'];
$role = $_POST['role'];
$batalyon = isset($_POST['batalyon']) ? $_POST['batalyon'] : null;

try {
    // 2. Buat query berdasarkan data
    $sql = "SELECT * FROM users WHERE nama = :nama AND role = :role";
    $params = [':nama' => $nama, ':role' => $role];

    // Jika kadet, tambahkan pengecekan batalyon
    if ($role === 'kadet') {
        $sql .= " AND batalyon = :batalyon";
        $params[':batalyon'] = $batalyon;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 3. Verifikasi User dan Password
    if ($user && password_verify($password, $user['password'])) {
        
        // 4. Logika Pengecekan Role
        // Blokir jika ada yang mencoba login sebagai admin dari form biasa
        if ($role === 'admin') {
            $_SESSION['error_message'] = "Login admin hanya bisa dilakukan melalui halaman login admin.";
            header('Location: ../pages/login.php');
            exit;
        }
        
        if ($role === 'organik') {
            // LANGSUNG LOGIN (SESI 24 JAM)
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['login_time'] = time(); // Penanda waktu login

            session_regenerate_id(true);

            header("Location: ../pages/dashboard_organik.php");
            exit;
            
        } elseif ($role === 'kadet') {
            
            // --- INI ADALAH LOGIKA BARU (SISTEM GABUNGAN) ---

            // Cek dulu ID Telegram
            $telegram_id = $user['telegram_id'];
            if (empty($telegram_id)) {
                $_SESSION['error_message'] = "Akun Kadet ini tidak memiliki ID Telegram terdaftar.";
                header('Location: ../pages/login.php');
                exit;
            }

            // PENGECEKAN JADWAL (ROSTER)
            $today = date('Y-m-d'); // (Pastikan timezone di config/db.php sudah 'Asia/Jakarta')
            // Kita gunakan LIKE untuk fleksibilitas (misal: "Budi, Dono" akan cocok jika "Budi" login)
            $nama_check = '%' . $user['nama'] . '%'; 

            $roster_sql = "SELECT id FROM tbl_piket_jaga 
                        WHERE tanggal_piket = :today 
                        AND batalyon = :batalyon 
                        AND nama_piket LIKE :nama";
            
            $roster_stmt = $pdo->prepare($roster_sql);
            $roster_stmt->execute([
                ':today' => $today,
                ':batalyon' => $user['batalyon'],
                ':nama' => $nama_check
            ]);
            
            $jadwal = $roster_stmt->fetch(PDO::FETCH_ASSOC);

            if ($jadwal) {
                // JIKA NAMA ADA DI JADWAL: Lanjutkan kirim token
                
                $token = rand(100000, 999999);
                $token_expire = date('Y-m-d H:i:s', time() + (5 * 60)); // Berlaku 5 menit

                // Simpan token ke database
                $update_stmt = $pdo->prepare("UPDATE users SET token = :token, token_expire = :token_expire WHERE id = :id");
                $update_stmt->execute([':token' => $token, ':token_expire' => $token_expire, ':id' => $user['id']]);

                // Kirim token via Telegram
                kirim_token_telegram($telegram_bot_token, $telegram_id, $token);

                // Simpan ID pengguna sementara di sesi untuk verifikasi
                $_SESSION['pending_user_id'] = $user['id'];

                // Arahkan ke halaman verifikasi token
                header('Location: ../pages/verify_token.php');
                exit;

            } else {
                // JIKA NAMA TIDAK ADA DI JADWAL: Blok login
                
                $_SESSION['error_message'] = "Login Gagal: Nama Anda (" . htmlspecialchars($user['nama']) . ") tidak terdaftar dalam jadwal piket hari ini.";
                header('Location: ../pages/login.php');
                exit;
            }
            // --- AKHIR LOGIKA GABUNGAN ---
        }

    } else {
        // Jika username, password, atau role/batalyon salah
        $_SESSION['error_message'] = "Kombinasi Nama, Password, Role, atau Batalyon salah.";
        header('Location: ../pages/login.php');
        exit;
    }

} catch (PDOException $e) {
    // Tangani error database
    $_SESSION['error_message'] = "Terjadi masalah koneksi: " . $e->getMessage();
    header('Location: ../pages/login.php');
    exit;
}
?>