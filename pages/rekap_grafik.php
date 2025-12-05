<?php $page_title = "Grafik Visual"; ?>
<?php
require '../includes/header.php';

// 1. Cek Akses: Boleh Admin ATAU Organik
if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'organik') {
    header('Location: login.php');
    exit;
}

require '../config/db.php';

// Logika Data (Sama untuk keduanya)
$range = isset($_GET['range']) ? $_GET['range'] : '7';
$end_date = date('Y-m-d');
$start_date = date('Y-m-d', strtotime("-$range days"));

try {
    $sql = "SELECT b.nama_batalyon, SUM(a.jumlah) as total_kurang 
            FROM tbl_batalyon b
            LEFT JOIN tbl_apel a ON b.nama_batalyon = a.batalyon 
            AND a.tanggal_apel BETWEEN '$start_date' AND '$end_date'
            GROUP BY b.nama_batalyon ORDER BY b.nama_batalyon ASC";
    $stmt = $pdo->query($sql);
    $data_chart = $stmt->fetchAll();
    
    $labels = []; $values = [];
    foreach($data_chart as $d) {
        $labels[] = "Batalyon " . $d['nama_batalyon'];
        $values[] = $d['total_kurang'];
    }
} catch (PDOException $e) { die($e->getMessage()); }
?>

<link href="../assets/css/admin_genz.css" rel="stylesheet">

<div class="d-flex" id="wrapper">
    
    <?php 
    if ($_SESSION['role'] == 'admin') {
        require '../includes/sidebar_admin.php'; 
    } else {
        require '../includes/sidebar_organik.php'; // Pastikan file ini sudah dibuat
    }
    ?>
    
    <div id="page-content-wrapper">
        <nav class="top-navbar px-3">
            <button class="btn btn-light shadow-sm rounded-circle" id="menu-toggle">
                <i class="bi bi-list fs-4 text-maroon"></i>
            </button>
            <div class="fw-bold text-maroon">VISUALISASI DATA</div>
        </nav>

        <div class="container-fluid">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card-modern p-4">
                        <form method="GET" class="d-flex gap-3 align-items-center">
                            <label class="fw-bold text-muted">Rentang Waktu:</label>
                            <select name="range" class="form-select border-0 bg-light rounded-pill w-auto" onchange="this.form.submit()">
                                <option value="7" <?php echo $range=='7'?'selected':''; ?>>7 Hari Terakhir</option>
                                <option value="30" <?php echo $range=='30'?'selected':''; ?>>30 Hari Terakhir</option>
                                <option value="90" <?php echo $range=='90'?'selected':''; ?>>90 Hari Terakhir</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-modern p-4">
                <h5 class="fw-bold mb-4 text-center text-maroon">Tren Ketidakhadiran (Jumlah Kurang)</h5>
                <div style="height: 400px; width: 100%;">
                    <canvas id="rekapChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.getElementById("menu-toggle").onclick = function () { 
        document.getElementById("wrapper").classList.toggle("toggled"); 
    };

    const ctx = document.getElementById('rekapChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Total Kurang',
                data: <?php echo json_encode($values); ?>,
                backgroundColor: ['#800000', '#A52A2A', '#B22222', '#DC143C'],
                borderRadius: 10,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
</body>
</html>