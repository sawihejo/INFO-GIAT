<?php $page_title = "Dashboard Piket"; ?>
<?php $currentPage = 'input_piket'; // <-- TAMBAHKAN INI ?>
<?php
// 1. Panggil Header (yang otomatis cek login, sesi 24 jam, dan cache)
require '../includes/header.php';

// 2. Cek Keamanan Spesifik: Hanya KADET yang boleh akses
if ($_SESSION['role'] !== 'kadet') {
    $_SESSION['error_message'] = "Hanya Kadet Piket yang bisa mengakses halaman ini.";
    header('Location: login.php');
    exit;
}

// 3. Panggil Navbar
require '../includes/navbar.php';

// Ambil info dari Session untuk ditampilkan
$nama_kadet = htmlspecialchars($_SESSION['nama']);
$batalyon_kadet = htmlspecialchars($_SESSION['batalyon']);
?>

<main class="container mt-4">
    
    <h1 class="h2 mb-4">
        <p class="lead text-muted mb-4">
        Hari ini: <strong><?php echo date('l, d F Y'); ?></strong> 
        </p>
        Dashboard Piket 
        <span class="text-primary fw-bold">
            Batalyon <?php echo $batalyon_kadet; ?>
        </span>
    </h1>
    
    <p class="lead text-muted">
        Selamat datang, <?php echo $nama_kadet; ?> 
        <span class="badge bg-secondary text-uppercase ms-1"><?php echo $_SESSION['role']; ?></span>. 
        Anda bertugas untuk Batalyon...
    </p>

    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <div class="row g-4 mt-3">
        
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="card h-100 shadow-sm border-0 lift-hover">
                <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                    <i class="bi bi-people-fill fs-1 text-success"></i>
                    <h5 class="card-title mt-3">Input Data Apel</h5>
                    <p class="card-text text-muted small">Laporkan data kehadiran dan absensi apel pagi/malam.</p>
                    <a href="form_apel.php" class="btn btn-success mt-auto">
                        <i class="bi bi-pencil-square"></i> Mulai Mengisi
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="card h-100 shadow-sm border-0 lift-hover">
                <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                    <i class="bi bi-camera-fill fs-1 text-info"></i>
                    <h5 class="card-title mt-3">Input Dokumentasi</h5>
                    <p class="card-text text-muted small">Unggah foto dokumentasi makan, apel, dan kegiatan lainnya.</p>
                    <a href="form_dokumentasi.php" class="btn btn-info mt-auto">
                        <i class="bi bi-upload"></i> Mulai Mengunggah
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="card h-100 shadow-sm border-0 lift-hover">
                <div class="card-body text-center d-flex flex-column justify-content-center p-4">
                    <i class="bi bi-calendar-check-fill fs-1 text-warning"></i>
                    <h5 class="card-title mt-3">Input Rencana Kegiatan</h5>
                    <p class="card-text text-muted small">Isi rencana kegiatan harian untuk Batalyon Anda.</p>
                    <a href="form_rencana.php" class="btn btn-warning mt-auto">
                        <i class="bi bi-calendar-plus"></i> Mulai Mengisi
                    </a>
                </div>
            </div>
        </div>

    </div>

    <style>
        .lift-hover {
            transition: box-shadow 0.2s ease-in-out, transform 0.2s ease-in-out;
        }
        .lift-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
        }
    </style>

</main>

<?php
// 5. Panggil Footer
require '../includes/footer.php';
?>