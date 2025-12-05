<?php
// Dapatkan nama file saat ini tanpa path
$current_page = basename($_SERVER['PHP_SELF']); 
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Piket Batalyon</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav me-auto">
        <?php 
        // Inisialisasi variabel untuk menghindari error jika tidak di-set
        if (!isset($currentPage)) {
            $currentPage = '';
        }
        ?>

        <?php // Menu Dinamis Berdasarkan Role ?>

        <?php if ($_SESSION['role'] == 'admin'): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'dashboard_admin.php') ? 'active' : ''; ?>" href="dashboard_admin.php">Manajemen Pengguna</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'manage_batalyon.php') ? 'active' : ''; ?>" href="manage_batalyon.php">Master Batalyon</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'dashboard_organik.php') ? 'active' : ''; ?>" href="dashboard_organik.php">Pantau Rekap</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'rekap_grafik.php') ? 'active' : ''; ?>" href="rekap_grafik.php">Grafik Rekap</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'lihat_roster.php') ? 'active' : ''; ?>" href="lihat_roster.php">Lihat Jadwal Piket</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'lihat_rencana.php') ? 'active' : ''; ?>" href="lihat_rencana.php">Lihat Rencana</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'lihat_dokumentasi.php') ? 'active' : ''; ?>" href="lihat_dokumentasi.php">Lihat Dokumentasi</a>
            </li>
            

        <?php elseif ($_SESSION['role'] == 'organik'): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'dashboard_organik.php') ? 'active' : ''; ?>" href="dashboard_organik.php">Pantau Rekap</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'rekap_grafik.php') ? 'active' : ''; ?>" href="rekap_grafik.php">Grafik Rekap</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'lihat_roster.php') ? 'active' : ''; ?>" href="lihat_roster.php">Lihat Jadwal Piket</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'lihat_rencana.php') ? 'active' : ''; ?>" href="lihat_rencana.php">Lihat Rencana</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'lihat_dokumentasi.php') ? 'active' : ''; ?>" href="lihat_dokumentasi.php">Lihat Dokumentasi</a>
            </li>

        <?php elseif ($_SESSION['role'] == 'kadet'): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'dashboard_piket.php') ? 'active' : ''; ?>" href="dashboard_piket.php">Input Piket</a>
            </li>
            <?php endif; ?>

</ul>
    
    <ul class="navbar-nav ms-auto">
        ...
    </ul>
</div>

            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> 
                        <?php 
                            // Tampilkan nama
                            echo htmlspecialchars($_SESSION['nama']); 
                            
                            // TAMBAHKAN INI: Jika dia kadet dan info batalyon ada, tampilkan
                            if (isset($_SESSION['batalyon']) && $_SESSION['role'] == 'kadet') {
                                echo ' (Batalyon ' . htmlspecialchars($_SESSION['batalyon']) . ')';
                            }
                        ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                        <li><a class="dropdown-item" href="#">Ganti Password</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="../process/logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>