<?php $page_title = "Form Data Pengguna"; ?>
<?php
require '../includes/header.php';

// Cek Admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require '../config/db.php';

// Mode Tambah/Edit
$is_edit = false;
$user = ['id' => null, 'nama' => '', 'username' => '', 'role' => '', 'batalyon' => null, 'telegram_id' => '', 'phone' => ''];

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $is_edit = true;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $user = $stmt->fetch();
    if (!$user) { header('Location: dashboard_admin.php'); exit; }
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
            <div class="fw-bold text-maroon ms-3"><?php echo $is_edit ? 'EDIT PENGGUNA' : 'PENGGUNA BARU'; ?></div>
        </nav>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    
                    <div class="card-modern">
                        <div class="card-header-modern bg-maroon text-white text-center py-4" style="border-radius: 25px 25px 0 0; background: linear-gradient(135deg, #800000, #5c0000);">
                            <h4 class="mb-0 fw-bold">
                                <i class="bi <?php echo $is_edit ? 'bi-pencil-square' : 'bi-person-plus'; ?> me-2"></i>
                                <?php echo $is_edit ? 'Perbarui Data' : 'Tambah Personil'; ?>
                            </h4>
                        </div>
                        
                        <div class="card-body p-4 p-md-5">
                            <form action="../process/insert_user.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Nama Lengkap</label>
                                    <input type="text" class="form-control form-control-lg border-0 bg-light rounded-pill px-4" 
                                           name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                                </div>

                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Username</label>
                                        <input type="text" class="form-control form-control-lg border-0 bg-light rounded-pill px-4" 
                                               name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Role</label>
                                        <select class="form-select form-select-lg border-0 bg-light rounded-pill px-4" 
                                                id="role" name="role" required onchange="toggleBatalyon()">
                                            <option value="" disabled <?php echo !$is_edit ? 'selected' : ''; ?>>Pilih...</option>
                                            <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Administrator</option>
                                            <option value="organik" <?php echo $user['role'] == 'organik' ? 'selected' : ''; ?>>Organik</option>
                                            <option value="kadet" <?php echo $user['role'] == 'kadet' ? 'selected' : ''; ?>>Kadet</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4" id="batalyon-group" style="display: none;">
                                    <div class="p-3 bg-light rounded-4 border border-warning">
                                        <label class="form-label fw-bold text-dark mb-2">Pilih Batalyon</label>
                                        <div class="d-flex gap-3">
                                            <?php foreach(['I', 'II', 'III', 'IV'] as $bat): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="batalyon" id="bat<?php echo $bat; ?>" 
                                                           value="<?php echo $bat; ?>" <?php echo $user['batalyon'] == $bat ? 'checked' : ''; ?>>
                                                    <label class="form-check-label fw-bold" for="bat<?php echo $bat; ?>"><?php echo $bat; ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Telegram ID</label>
                                        <input type="text" class="form-control form-control-lg border-0 bg-light rounded-pill px-4" 
                                               name="telegram_id" value="<?php echo htmlspecialchars($user['telegram_id']); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold small text-muted text-uppercase">No. HP</label>
                                        <input type="text" class="form-control form-control-lg border-0 bg-light rounded-pill px-4" 
                                               name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>">
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Password</label>
                                    <input type="password" class="form-control form-control-lg border-0 bg-light rounded-pill px-4" 
                                           name="password" placeholder="<?php echo $is_edit ? 'Kosongkan jika tetap' : 'Password baru'; ?>" 
                                           <?php echo !$is_edit ? 'required' : ''; ?>>
                                </div>

                                <div class="d-grid gap-3">
                                    <button type="submit" class="btn btn-danger btn-lg rounded-pill shadow-sm fw-bold py-3" style="background-color: #800000;">
                                        SIMPAN DATA
                                    </button>
                                    <a href="dashboard_admin.php" class="btn btn-light btn-lg rounded-pill text-muted fw-bold">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById("menu-toggle").onclick = function () { document.getElementById("wrapper").classList.toggle("toggled"); };

    function toggleBatalyon() {
        var role = document.getElementById('role').value;
        var group = document.getElementById('batalyon-group');
        if (role === 'kadet') group.style.display = 'block';
        else group.style.display = 'none';
    }
    window.onload = toggleBatalyon;
</script>
</body>
</html>