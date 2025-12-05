<?php $page_title = "Rencana Kegiatan"; ?>
<?php
require '../includes/header.php';
if ($_SESSION['role'] !== 'admin') { header('Location: login.php'); exit; }
require '../config/db.php';

$tanggal = isset($_GET['t']) ? $_GET['t'] : date('Y-m-d');
try {
    $stmt = $pdo->prepare("SELECT r.*, u.nama as nama_kadet FROM tbl_rencana r JOIN users u ON r.kadet_id = u.id WHERE r.tanggal_kegiatan = :t ORDER BY r.batalyon ASC");
    $stmt->execute([':t' => $tanggal]);
    $rencana = $stmt->fetchAll();
} catch (PDOException $e) { die($e->getMessage()); }
?>

<link href="../assets/css/admin_genz.css" rel="stylesheet">

<div class="d-flex" id="wrapper">
    <?php require '../includes/sidebar_admin.php'; ?>
    
    <div id="page-content-wrapper">
        <nav class="top-navbar px-3">
            <button class="btn btn-light shadow-sm rounded-circle" id="menu-toggle"><i class="bi bi-list fs-4 text-maroon"></i></button>
            <div class="fw-bold text-maroon">RENCANA HARIAN</div>
        </nav>

        <div class="container-fluid">
            <div class="card-modern p-4 mb-4">
                <form method="GET" class="d-flex gap-3 align-items-center">
                    <label class="fw-bold text-muted">Tanggal:</label>
                    <input type="date" name="t" class="form-control border-0 bg-light rounded-pill w-auto px-3" value="<?php echo $tanggal; ?>" onchange="this.form.submit()">
                </form>
            </div>

            <div class="row g-4">
                <?php if (empty($rencana)): ?>
                    <div class="col-12 text-center py-5">
                        <img src="../assets/img/no_data.svg" style="height: 150px; opacity: 0.5;" alt="Kosong">
                        <p class="text-muted mt-3">Belum ada rencana kegiatan yang diinput.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($rencana as $r): ?>
                        <div class="col-12">
                            <div class="card-modern p-4 d-flex align-items-start gap-4">
                                <div class="bg-maroon text-white rounded-4 p-3 text-center" style="min-width: 100px;">
                                    <h4 class="mb-0 fw-bold"><?php echo $r['batalyon']; ?></h4>
                                    <small class="text-white-50" style="font-size: 0.7rem;">BATALYON</small>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold text-dark mb-1">Rencana Kegiatan</h5>
                                    <p class="text-muted mb-3" style="font-size: 0.9rem;">
                                        Oleh: <span class="fw-bold text-maroon"><?php echo htmlspecialchars($r['nama_kadet']); ?></span>
                                    </p>
                                    <div class="bg-light p-3 rounded-4 border-start border-4 border-warning">
                                        <?php echo nl2br(htmlspecialchars($r['isi_rencana'])); ?>
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
<script>document.getElementById("menu-toggle").onclick = function () { document.getElementById("wrapper").classList.toggle("toggled"); };</script>
</body>
</html>