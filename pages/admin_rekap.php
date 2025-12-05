<?php $page_title = "Pantau Rekap - Admin Mode"; ?>
<?php
require '../includes/header.php';

// 1. Cek Admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// 2. Koneksi & Logika Data (Sama seperti dashboard_organik.php)
require '../config/db.php';

$tanggal_filter = isset($_GET['t']) ? $_GET['t'] : date('Y-m-d');
$jenis_apel_filter = isset($_GET['apel']) ? $_GET['apel'] : 'semua';

try {
    $sql_params = [':tanggal' => $tanggal_filter];
    $where_apel = ""; 
    
    if ($jenis_apel_filter == 'pagi') {
        $where_apel = "AND a.jenis_apel = 'pagi'";
    } elseif ($jenis_apel_filter == 'malam') {
        $where_apel = "AND a.jenis_apel = 'malam'";
    }

    $sql_rekap = "
        SELECT 
            b.id AS batalyon_id,
            b.nama_batalyon,
            b.jumlah_total_personil,
            COALESCE(SUM(a.jumlah), 0) AS total_kurang
        FROM tbl_batalyon b
        LEFT JOIN tbl_apel a ON b.nama_batalyon = a.batalyon 
            AND a.tanggal_apel = :tanggal
            $where_apel
        GROUP BY b.id, b.nama_batalyon, b.jumlah_total_personil
        ORDER BY b.nama_batalyon ASC
    ";

    $stmt_rekap = $pdo->prepare($sql_rekap);
    $stmt_rekap->execute($sql_params);
    $rekap_batalyon = $stmt_rekap->fetchAll();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<link href="../assets/css/admin_genz.css" rel="stylesheet">

<div class="d-flex" id="wrapper">
    <?php require '../includes/sidebar_admin.php'; ?>

    <div id="page-content-wrapper">
        <nav class="top-navbar px-3">
            <button class="btn btn-light shadow-sm rounded-circle" id="menu-toggle"><i class="bi bi-list fs-4 text-maroon"></i></button>
            <div class="fw-bold text-maroon">MONITORING HARIAN</div>
        </nav>

        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card-modern p-4">
                        <form method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small">TANGGAL FILTER</label>
                                <input type="date" class="form-control border-0 bg-light rounded-pill px-3" name="t" value="<?php echo $tanggal_filter; ?>" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small">JENIS APEL</label>
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
                        $batalyon = $rekap['nama_batalyon'];
                        $total = (int)$rekap['jumlah_total_personil'];
                        $kurang = (int)$rekap['total_kurang'];
                        $hadir = $total - $kurang;
                        $persen_hadir = ($total > 0) ? round(($hadir / $total) * 100) : 0;
                        
                        // Warna Kartu Dinamis
                        $bg_gradient = ($persen_hadir < 80) 
                            ? 'linear-gradient(135deg, #e74c3c, #c0392b)' // Merah jika hadir < 80%
                            : 'linear-gradient(135deg, #ffffff, #f8f9fa)'; // Putih normal
                        $text_class = ($persen_hadir < 80) ? 'text-white' : 'text-dark';
                    ?>
                    
                    <div class="col-md-6 col-lg-6">
                        <div class="card-modern h-100 p-4" style="background: <?php echo $bg_gradient; ?>;">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="fw-bold mb-0 <?php echo $text_class; ?>">Batalyon <?php echo $batalyon; ?></h4>
                                <span class="badge bg-warning text-dark rounded-pill shadow-sm">
                                    <i class="bi bi-people-fill"></i> <?php echo $total; ?> Personil
                                </span>
                            </div>

                            <div class="row text-center mt-4">
                                <div class="col-6">
                                    <h2 class="display-4 fw-bold mb-0 <?php echo $text_class; ?>"><?php echo $hadir; ?></h2>
                                    <small class="text-uppercase fw-bold <?php echo $text_class; ?>" style="opacity: 0.7;">Hadir</small>
                                </div>
                                <div class="col-6 border-start border-light">
                                    <h2 class="display-4 fw-bold mb-0 <?php echo $text_class; ?>"><?php echo $kurang; ?></h2>
                                    <small class="text-uppercase fw-bold <?php echo $text_class; ?>" style="opacity: 0.7;">Kurang</small>
                                </div>
                            </div>
                            
                            <div class="progress mt-4 rounded-pill" style="height: 10px; background: rgba(0,0,0,0.1);">
                                <div class="progress-bar bg-success rounded-pill" role="progressbar" style="width: <?php echo $persen_hadir; ?>%"></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var el = document.getElementById("wrapper");
    var toggleButton = document.getElementById("menu-toggle");
    toggleButton.onclick = function () { el.classList.toggle("toggled"); };
</script>
</body>
</html>