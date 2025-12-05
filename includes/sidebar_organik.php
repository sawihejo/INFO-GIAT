<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div id="sidebar-wrapper">
    <div class="sidebar-heading">
        <i class="bi bi-person-badge-fill me-2"></i> ORGANIK
    </div>

    <div class="list-group list-group-flush mt-3">
        
        <a href="dashboard_organik.php" class="list-group-item list-group-item-action <?php echo ($current_page == 'dashboard_organik.php') ? 'active' : ''; ?>">
            <i class="bi bi-grid-fill"></i> Monitoring Apel
        </a>
        
        <a href="rekap_grafik.php" class="list-group-item list-group-item-action <?php echo ($current_page == 'rekap_grafik.php') ? 'active' : ''; ?>">
            <i class="bi bi-pie-chart-fill"></i> Data Visual
        </a>

        <a href="lihat_dokumentasi.php" class="list-group-item list-group-item-action <?php echo ($current_page == 'lihat_dokumentasi.php') ? 'active' : ''; ?>">
            <i class="bi bi-images"></i> Galeri Giat
        </a>

        <div class="mt-5 border-top border-white-50 pt-3 mx-3">
            <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.7rem;">Akun</small>
        </div>

        <a href="../process/logout.php" class="list-group-item list-group-item-action text-danger">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>
</div>