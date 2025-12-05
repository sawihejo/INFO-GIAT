<?php $page_title = "Jadwal Piket"; ?>
<?php
require '../includes/header.php';
if ($_SESSION['role'] !== 'admin') { header('Location: login.php'); exit; }
require '../config/db.php';

$tanggal = isset($_GET['t']) ? $_GET['t'] : date('Y-m-d');
try {
    $stmt = $pdo->prepare("SELECT * FROM tbl_piket_jaga WHERE tanggal_piket = :t ORDER BY batalyon ASC");
    $stmt->execute([':t' => $tanggal]);
    $roster = $stmt->fetchAll();
} catch (PDOException $e) { die($e->getMessage()); }
?>

<link href="../assets/css/admin_genz.css" rel="stylesheet">

<div class="d-flex" id="wrapper">
    <?php require '../includes/sidebar_admin.php'; ?>
    
    <div id="page-content-wrapper">
        <nav class="top-navbar px-3">
            <button class="btn btn-light shadow-sm rounded-circle" id="menu-toggle"><i class="bi bi-list fs-4 text-maroon"></i></button>
            <div class="fw-bold text-maroon">JADWAL PIKET</div>
        </nav>

        <div class="container-fluid">
            <div class="card-modern p-4 mb-4">
                <form method="GET" class="d-flex gap-3 align-items-center">
                    <label class="fw-bold text-muted">Tanggal:</label>
                    <input type="date" name="t" class="form-control border-0 bg-light rounded-pill w-auto px-3" value="<?php echo $tanggal; ?>" onchange="this.form.submit()">
                </form>
            </div>

            <div class="row g-4">
                <?php if (empty($roster)): ?>
                    <div class="col-12 text-center py-5">
                        <img src="../assets/img/no_data.svg" style="height: 150px; opacity: 0.5;" alt="Kosong">
                        <p class="text-muted mt-3 fw-bold">Belum ada jadwal untuk tanggal ini.</p>
                        <a href="manage_jadwal.php" class="btn btn-warning rounded-pill fw-bold mt-2">Buat Jadwal</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($roster as $r): ?>
                        <div class="col-md-6 col-xl-3">
                            <div class="card-modern h-100">
                                <div class="card-header-modern bg-maroon text-white text-center py-3" style="border-radius: 25px 25px 0 0; background: linear-gradient(135deg, #800000, #5c0000);">
                                    <h3 class="mb-0 fw-bold">BATALYON <?php echo $r['batalyon']; ?></h3>
                                </div>
                                <div class="card-body p-4 text-center">
                                    <h6 class="text-muted fw-bold mb-3">PETUGAS JAGA</h6>
                                    <p class="fw-bold text-dark fs-5 mb-4"><?php echo nl2br(htmlspecialchars($r['nama_piket'])); ?></p>
                                    
                                    <hr class="border-light">
                                    
                                    <h6 class="text-muted fw-bold mb-2 text-uppercase" style="font-size: 0.75rem;">Kontak</h6>
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2 border">
                                        <i class="bi bi-telephone-fill text-success me-1"></i> <?php echo $r['kontak_piket']; ?>
                                    </span>
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
<script>document.getElementById("menu-toggle").onclick = function () { document.getElementById("wrapper").classList.toggle("toggled"); };</script>
</body>
</html>