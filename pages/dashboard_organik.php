<?php $page_title = "Monitoring Organik"; ?>
<?php
require '../includes/header.php';

// Cek Organik (Admin juga boleh intip)
if ($_SESSION['role'] !== 'organik' && $_SESSION['role'] !== 'admin') {
    $_SESSION['error_message'] = "Akses ditolak.";
    header('Location: login.php');
    exit;
}

require '../config/db.php';

// --- Logika Filter Data (Sama seperti sebelumnya) ---
$tanggal_filter = isset($_GET['t']) ? $_GET['t'] : date('Y-m-d');
$jenis_apel_filter = isset($_GET['apel']) ? $_GET['apel'] : 'semua';

try {
    $sql_params = [':tanggal' => $tanggal_filter];
    $where_apel = ""; 
    if ($jenis_apel_filter == 'pagi') $where_apel = "AND a.jenis_apel = 'pagi'";
    elseif ($jenis_apel_filter == 'malam') $where_apel = "AND a.jenis_apel = 'malam'";

    // Query Total
    $sql_rekap = "SELECT b.id AS batalyon_id, b.nama_batalyon, b.jumlah_total_personil, COALESCE(SUM(a.jumlah), 0) AS total_kurang
                  FROM tbl_batalyon b LEFT JOIN tbl_apel a ON b.nama_batalyon = a.batalyon AND a.tanggal_apel = :tanggal $where_apel
                  GROUP BY b.id, b.nama_batalyon, b.jumlah_total_personil ORDER BY b.nama_batalyon ASC";
    $stmt_rekap = $pdo->prepare($sql_rekap);
    $stmt_rekap->execute($sql_params);
    $rekap_batalyon = $stmt_rekap->fetchAll();

    // Query Detail untuk Modal
    $sql_detail = "SELECT a.*, u.nama AS nama_pelapor FROM tbl_apel a 
                   JOIN users u ON a.kadet_id = u.id 
                   WHERE a.tanggal_apel = :tanggal $where_apel ORDER BY a.batalyon ASC";
    $stmt_detail = $pdo->prepare($sql_detail);
    $stmt_detail->execute($sql_params);
    $detail_apel = $stmt_detail->fetchAll(PDO::FETCH_GROUP); 
} catch (PDOException $e) { die("Error: " . $e->getMessage()); }
?>

<link href="../assets/css/admin_genz.css" rel="stylesheet">

<div class="d-flex" id="wrapper">

    <?php require '../includes/sidebar_organik.php'; ?>

    <div id="page-content-wrapper">
        
        <nav class="top-navbar px-3">
            <button class="btn btn-light shadow-sm rounded-circle" id="menu-toggle">
                <i class="bi bi-list fs-4 text-maroon"></i>
            </button>
            
            <div class="d-flex align-items-center bg-white px-3 py-2 rounded-pill shadow-sm">
                <div class="me-2 text-end lh-1">
                    <span class="d-block fw-bold text-dark small"><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
                    <span class="d-block text-muted" style="font-size: 0.7rem;">Organik / Pengawas</span>
                </div>
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                    <i class="bi bi-person-fill"></i>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card-modern p-4">
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="fw-bold text-muted small text-uppercase">Tanggal Filter</label>
                                <input type="date" class="form-control border-0 bg-light rounded-pill px-3" name="t" value="<?php echo $tanggal_filter; ?>" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-4">
                                <label class="fw-bold text-muted small text-uppercase">Jenis Apel</label>
                                <select class="form-select border-0 bg-light rounded-pill px-3" name="apel" onchange="this.form.submit()">
                                    <option value="semua" <?php echo $jenis_apel_filter == 'semua' ? 'selected' : ''; ?>>Semua Apel</option>
                                    <option value="pagi" <?php echo $jenis_apel_filter == 'pagi' ? 'selected' : ''; ?>>Apel Pagi</option>
                                    <option value="malam" <?php echo $jenis_apel_filter == 'malam' ? 'selected' : ''; ?>>Apel Malam</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <?php foreach ($rekap_batalyon as $rekap): ?>
                    <?php
                        $batName = $rekap['nama_batalyon'];
                        $total = (int)$rekap['jumlah_total_personil'];
                        $kurang = (int)$rekap['total_kurang'];
                        $hadir = $total - $kurang;
                        $persen = ($total > 0) ? round(($hadir / $total) * 100) : 0;
                        
                        // Style Kartu Organik (Sedikit beda warna aksen biar fresh)
                        $bg_gradient = ($persen < 85) ? 'linear-gradient(135deg, #fff0f0, #ffffff)' : 'white';
                        $border_class = ($persen < 85) ? 'border-danger border-2' : 'border-0';
                    ?>
                    
                    <div class="col-md-6 col-lg-6">
                        <div class="card-modern h-100 p-4 border <?php echo $border_class; ?>" style="background: <?php echo $bg_gradient; ?>;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold mb-0 text-maroon">Batalyon <?php echo $batName; ?></h4>
                                <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modal<?php echo $batName; ?>">
                                    <i class="bi bi-eye-fill me-1"></i> Detail
                                </button>
                            </div>

                            <div class="row text-center mt-2">
                                <div class="col-4">
                                    <h3 class="fw-bold mb-0 text-dark"><?php echo $total; ?></h3>
                                    <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Total</small>
                                </div>
                                <div class="col-4 border-start border-end">
                                    <h3 class="fw-bold mb-0 text-success"><?php echo $hadir; ?></h3>
                                    <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Hadir</small>
                                </div>
                                <div class="col-4">
                                    <h3 class="fw-bold mb-0 text-danger"><?php echo $kurang; ?></h3>
                                    <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Kurang</small>
                                </div>
                            </div>
                            
                            <div class="progress mt-3 rounded-pill" style="height: 8px;">
                                <div class="progress-bar <?php echo ($persen < 85) ? 'bg-danger' : 'bg-success'; ?>" role="progressbar" style="width: <?php echo $persen; ?>%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="modal<?php echo $batName; ?>" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0">
                                <div class="modal-header bg-maroon text-white">
                                    <h5 class="modal-title fw-bold">Detail Batalyon <?php echo $batName; ?></h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <?php if (isset($detail_apel[$batName])): ?>
                                        <div class="list-group list-group-flush">
                                            <?php foreach ($detail_apel[$batName] as $d): ?>
                                                <div class="list-group-item p-3">
                                                    <div class="d-flex justify-content-between">
                                                        <strong class="text-danger"><?php echo htmlspecialchars($d['keterangan']); ?></strong>
                                                        <span class="badge bg-light text-dark border"><?php echo $d['jumlah']; ?> Org</span>
                                                    </div>
                                                    <p class="mb-1 text-muted small mt-1"><?php echo nl2br(htmlspecialchars($d['nama_nama'])); ?></p>
                                                    <small class="text-muted fst-italic" style="font-size: 0.7rem;">Lapor: <?php echo ucfirst($d['jenis_apel']); ?></small>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="p-4 text-center text-muted">Lengkap / Nihil.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
            
        </div>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
<script>
    var el = document.getElementById("wrapper");
    var toggleButton = document.getElementById("menu-toggle");
    toggleButton.onclick = function () { el.classList.toggle("toggled"); };
</script>
</body>
</html>