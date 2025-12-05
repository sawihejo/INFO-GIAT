<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div id="sidebar-wrapper">
    <div class="sidebar-heading">
        <i class="bi bi-shield-lock-fill me-2"></i> ADMIN PANEL
    </div>
    <div class="list-group list-group-flush mt-3">
        
        <a href="dashboard_admin.php" class="list-group-item list-group-item-action <?php echo ($current_page == 'dashboard_admin.php') ? 'active' : ''; ?>">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>

        <a href="manage_batalyon.php" class="list-group-item list-group-item-action <?php echo ($current_page == 'manage_batalyon.php') ? 'active' : ''; ?>">
            <i class="bi bi-building-fill"></i> Master Batalyon
        </a>

        <a href="admin_rekap.php" class="list-group-item list-group-item-action <?php echo ($current_page == 'admin_rekap.php') ? 'active' : ''; ?>">
            <i class="bi bi-bar-chart-line-fill"></i> Pantau Rekap
        </a>
        
        <a href="rekap_grafik.php" class="list-group-item list-group-item-action <?php echo ($current_page == 'rekap_grafik.php') ? 'active' : ''; ?>">
            <i class="bi bi-pie-chart-fill"></i> Grafik Visual
        </a>

        <a href="lihat_roster.php" class="list-group-item list-group-item-action <?php echo ($current_page == 'lihat_roster.php') ? 'active' : ''; ?>">
            <i class="bi bi-calendar-week-fill"></i> Lihat Jadwal
        </a>

        <a href="lihat_rencana.php" class="list-group-item list-group-item-action <?php echo ($current_page == 'lihat_rencana.php') ? 'active' : ''; ?>">
            <i class="bi bi-journal-text"></i> Rencana Giat
        </a>
        <a href="lihat_dokumentasi.php" class="list-group-item list-group-item-action <?php echo ($current_page == 'lihat_dokumentasi.php') ? 'active' : ''; ?>">
            <i class="bi bi-images"></i> Dokumentasi
        </a>

        <div class="mt-5 border-top border-white-50 pt-3 mx-3">
            <small class="text-white-50 text-uppercase fw-bold" style="font-size: 0.7rem;">Akun</small>
        </div>

        <a href="../process/logout.php" class="list-group-item list-group-item-action text-danger">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>
</div>