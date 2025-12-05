<?php $page_title = "Galeri Dokumentasi"; ?>
<?php
require '../includes/header.php';

// 1. Cek Akses: Boleh Admin ATAU Organik
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'organik') {
    header('Location: login.php');
    exit;
}

require '../config/db.php';
$tanggal = isset($_GET['t']) ? $_GET['t'] : date('Y-m-d');

try {
    $stmt = $pdo->prepare("SELECT d.*, u.nama as nama_kadet FROM tbl_dokumentasi d JOIN users u ON d.kadet_id = u.id WHERE DATE(d.created_at) = :t ORDER BY d.created_at DESC");
    $stmt->execute([':t' => $tanggal]);
    $docs = $stmt->fetchAll();
} catch (PDOException $e) { die($e->getMessage()); }
?>

<link href="../assets/css/admin_genz.css" rel="stylesheet">

<div class="d-flex" id="wrapper">
    
    <?php 
    if ($_SESSION['role'] == 'admin') {
        require '../includes/sidebar_admin.php'; 
    } else {
        require '../includes/sidebar_organik.php'; 
    }
    ?>
    
    <div id="page-content-wrapper">
        <nav class="top-navbar px-3">
            <button class="btn btn-light shadow-sm rounded-circle" id="menu-toggle">
                <i class="bi bi-list fs-4 text-maroon"></i>
            </button>
            <div class="fw-bold text-maroon">GALERI DOKUMENTASI</div>
        </nav>

        <div class="container-fluid">
            <div class="card-modern p-4 mb-4">
                <form method="GET" class="d-flex gap-3 align-items-center">
                    <label class="fw-bold text-muted">Tanggal:</label>
                    <input type="date" name="t" class="form-control border-0 bg-light rounded-pill w-auto px-3" value="<?php echo $tanggal; ?>" onchange="this.form.submit()">
                </form>
            </div>

            <div class="row g-4">
                <?php if (empty($docs)): ?>
                    <div class="col-12 text-center py-5">
                        <p class="text-muted mt-3">Tidak ada dokumentasi pada tanggal ini.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($docs as $d): ?>
                        <div class="col-md-4 col-xl-3">
                            <div class="card-modern h-100 overflow-hidden">
                                <div class="position-relative">
                                    <img src="../<?php echo htmlspecialchars($d['file_path']); ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Dokumentasi">
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-warning text-dark rounded-pill shadow-sm">
                                        Bn. <?php echo htmlspecialchars($d['batalyon']); ?>
                                    </span>
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="fw-bold text-dark mb-1 text-uppercase"><?php echo htmlspecialchars($d['kategori']); ?></h6>
                                    <p class="text-muted small mb-2 text-truncate"><?php echo htmlspecialchars($d['keterangan_lainnya'] ?? '-'); ?></p>
                                    <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top border-light">
                                        <div class="bg-maroon text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 25px; height: 25px; font-size: 0.7rem;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <small class="text-muted fw-bold" style="font-size: 0.75rem;"><?php echo htmlspecialchars($d['nama_kadet']); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById("menu-toggle").onclick = function () { document.getElementById("wrapper").classList.toggle("toggled"); };
</script>
</body>
</html>