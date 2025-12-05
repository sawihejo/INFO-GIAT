<?php $page_title = "Dashboard Piket"; ?>
<?php $currentPage = 'input_piket'; ?>
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

<style>
    /* Dashboard Piket Modern Gen Z */
    body {
        background-color: #F0F2F5;
        font-family: 'Outfit', 'Segoe UI', sans-serif;
        overflow-x: hidden;
    }

    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }

    .dashboard-header {
        margin-bottom: 3rem;
    }

    .header-greeting {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .header-date {
        font-size: 0.95rem;
        color: #666;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .header-date i {
        color: #800000;
        font-size: 1.1rem;
    }

    .header-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #2b0000;
        letter-spacing: -1px;
    }

    .header-subtitle {
        font-size: 1.1rem;
        color: #666;
        margin-top: 0.5rem;
    }

    .batalyon-badge {
        display: inline-block;
        background: linear-gradient(135deg, #800000, #2b0000);
        color: #FFD700;
        padding: 0.5rem 1.25rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.95rem;
        margin-left: 0.75rem;
    }

    /* Alert Styles */
    .alert-success-custom {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05));
        border: 1px solid rgba(40, 167, 69, 0.3);
        border-radius: 15px;
        padding: 1rem 1.5rem;
        color: #155724;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-success-custom i {
        font-size: 1.25rem;
        color: #28a745;
    }

    .alert-danger-custom {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05));
        border: 1px solid rgba(220, 53, 69, 0.3);
        border-radius: 15px;
        padding: 1rem 1.5rem;
        color: #721c24;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .alert-danger-custom i {
        font-size: 1.25rem;
        color: #dc3545;
    }

    /* Cards Modern Gen Z */
    .card-modern-piket {
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 25px;
        box-shadow: 0 10px 30px rgba(128, 0, 0, 0.08);
        padding: 2rem;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .card-modern-piket::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #800000, #FFD700);
    }

    .card-modern-piket:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(128, 0, 0, 0.15);
        background: white;
    }

    .card-icon-container {
        width: 80px;
        height: 80px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        font-size: 2rem;
        transition: all 0.3s ease;
    }

    .card-modern-piket:hover .card-icon-container {
        transform: scale(1.1) rotate(5deg);
    }

    .card-icon-apel {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.15), rgba(40, 167, 69, 0.05));
        color: #28a745;
    }

    .card-icon-dokumentasi {
        background: linear-gradient(135deg, rgba(23, 162, 184, 0.15), rgba(23, 162, 184, 0.05));
        color: #17a2b8;
    }

    .card-icon-rencana {
        background: linear-gradient(135deg, rgba(255, 193, 7, 0.15), rgba(255, 193, 7, 0.05));
        color: #ffc107;
    }

    .card-title-piket {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2b0000;
        margin-bottom: 0.75rem;
    }

    .card-description {
        font-size: 0.95rem;
        color: #666;
        margin-bottom: 1.5rem;
        flex-grow: 1;
    }

    .card-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .card-button-apel {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .card-button-apel:hover {
        background: linear-gradient(135deg, #218838, #1aa179);
        transform: translateX(3px);
    }

    .card-button-dokumentasi {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
    }

    .card-button-dokumentasi:hover {
        background: linear-gradient(135deg, #138496, #0c5460);
        transform: translateX(3px);
    }

    .card-button-rencana {
        background: linear-gradient(135deg, #ffc107, #ff9800);
        color: white;
    }

    .card-button-rencana:hover {
        background: linear-gradient(135deg, #ff9800, #f57c00);
        transform: translateX(3px);
    }

    /* Grid Layout */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-title {
            font-size: 1.8rem;
        }

        .dashboard-container {
            padding: 1.5rem 1rem;
        }

        .header-greeting {
            margin-bottom: 1rem;
        }

        .dashboard-grid {
            gap: 1.5rem;
        }

        .card-modern-piket {
            padding: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .header-title {
            font-size: 1.5rem;
        }

        .batalyon-badge {
            display: block;
            margin-left: 0;
            margin-top: 0.5rem;
        }

        .card-icon-container {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }

        .card-title-piket {
            font-size: 1.2rem;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="header-date">
            <i class="bi bi-calendar-event"></i>
            Hari ini: <strong><?php echo date('l, d F Y'); ?></strong>
        </div>
        <h1 class="header-title">
            Dashboard Piket
            <span class="batalyon-badge">Batalyon <?php echo $batalyon_kadet; ?></span>
        </h1>
        <p class="header-subtitle">
            Selamat datang, <strong><?php echo $nama_kadet; ?></strong>! Mulai dengan mengisi data harian Batalyon Anda.
        </p>
    </div>

    <!-- Alerts -->
    <?php
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert-success-custom"><i class="bi bi-check-circle"></i>' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="alert-danger-custom"><i class="bi bi-exclamation-circle"></i>' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
    ?>

    <!-- Cards Grid -->
    <div class="dashboard-grid">
        
        <!-- Input Data Apel -->
        <a href="form_apel.php" class="card-modern-piket">
            <div class="card-icon-container card-icon-apel">
                <i class="bi bi-people-fill"></i>
            </div>
            <h2 class="card-title-piket">Input Data Apel</h2>
            <p class="card-description">Laporkan data kehadiran dan absensi apel pagi/malam dengan detail lengkap.</p>
            <button type="button" class="card-button card-button-apel">
                <i class="bi bi-pencil-square"></i>
                <span>Mulai Mengisi</span>
            </button>
        </a>

        <!-- Input Dokumentasi -->
        <a href="form_dokumentasi.php" class="card-modern-piket">
            <div class="card-icon-container card-icon-dokumentasi">
                <i class="bi bi-camera-fill"></i>
            </div>
            <h2 class="card-title-piket">Input Dokumentasi</h2>
            <p class="card-description">Unggah foto dokumentasi makan, apel, dan kegiatan penting lainnya.</p>
            <button type="button" class="card-button card-button-dokumentasi">
                <i class="bi bi-upload"></i>
                <span>Mulai Mengunggah</span>
            </button>
        </a>

        <!-- Input Rencana Kegiatan -->
        <a href="form_rencana.php" class="card-modern-piket">
            <div class="card-icon-container card-icon-rencana">
                <i class="bi bi-calendar-check-fill"></i>
            </div>
            <h2 class="card-title-piket">Input Rencana Kegiatan</h2>
            <p class="card-description">Isi rencana kegiatan harian untuk Batalyon Anda dengan detail yang jelas.</p>
            <button type="button" class="card-button card-button-rencana">
                <i class="bi bi-calendar-plus"></i>
                <span>Mulai Mengisi</span>
            </button>
        </a>

    </div>

</div>

<?php
// 5. Panggil Footer
require '../includes/footer.php';
?>