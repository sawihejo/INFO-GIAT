<?php $page_title = "Dashboard Admin"; ?>
<?php
require '../includes/header.php';

// Cek Admin
if ($_SESSION['role'] !== 'admin') {
    $_SESSION['error_message'] = "Akses ditolak.";
    header('Location: login.php');
    exit;
}

require '../config/db.php';
try {
    $stmt = $pdo->query("SELECT id, nama, role, batalyon, telegram_id, phone FROM users ORDER BY role, nama");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<link href="../assets/css/admin_genz.css" rel="stylesheet">

<div class="d-flex" id="wrapper">

    <?php require '../includes/sidebar_admin.php'; ?>

    <div id="page-content-wrapper">
        
        <nav class="top-navbar px-3">
            <button class="btn btn-light shadow-sm rounded-circle" id="menu-toggle">
                <i class="bi bi-list fs-4 text-maroon"></i>
            </button>
            
            <div class="d-flex align-items-center bg-white px-3 py-2 rounded-pill shadow-sm">
                <div class="me-2 text-end lh-1">
                    <span class="d-block fw-bold text-dark small"><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                    <span class="d-block text-muted" style="font-size: 0.7rem;">Administrator</span>
                </div>
                <div class="bg-maroon text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card-modern p-4 text-white" style="background: linear-gradient(135deg, #800000, #B22222);">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h2 class="fw-bold mb-1">Hello, Admin! 👋</h2>
                                <p class="mb-0 text-white-50">Kelola semua data personil dengan mudah.</p>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="manage_roster.php" class="btn btn-light rounded-pill fw-bold text-maroon px-4 shadow-sm">
                                    <i class="bi bi-calendar-check me-1"></i> Kelola Jadwal
                                </a>

                                <a href="manage_user.php" class="btn btn-light rounded-pill fw-bold text-maroon px-4 shadow-sm">
                                    <i class="bi bi-person-plus-fill me-1"></i> User Baru
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-modern p-4">
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <h5 class="fw-bold text-maroon mb-0">Database Personil</h5>
                    <span class="badge bg-light text-dark rounded-pill border"><?php echo count($users); ?> Total</span>
                </div>
                
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success rounded-4 border-0 shadow-sm mb-3">
                        <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tabelPengguna">
                        <thead class="bg-light">
                            <tr>
                                <th>Nama</th>
                                <th>Role</th>
                                <th>Batalyon</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($user['nama']); ?></td>
                                    <td>
                                        <span class="badge rounded-pill px-3 <?php echo ($user['role']=='admin')?'bg-danger':(($user['role']=='organik')?'bg-success':'bg-secondary'); ?>">
                                            <?php echo strtoupper($user['role']); ?>
                                        </span>
                                    </td>
                                    <td class="text-muted fw-bold"><?php echo htmlspecialchars($user['batalyon'] ?? '-'); ?></td>
                                    <td class="text-end">
                                        <a href="manage_user.php?id=<?php echo $user['id']; ?>" class="btn btn-light text-warning btn-sm rounded-circle shadow-sm border me-1"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="../process/delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-light text-danger btn-sm rounded-circle shadow-sm border" onclick="return confirm('Hapus permanen?');"><i class="bi bi-trash-fill"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
<script>
    // FUNGSI TOGGLE SIDEBAR (Wajib Ada)
    var el = document.getElementById("wrapper");
    var toggleButton = document.getElementById("menu-toggle");
    toggleButton.onclick = function () {
        el.classList.toggle("toggled");
    };
    
    $(document).ready(function() { $('#tabelPengguna').DataTable(); });
</script>
</body>
</html>