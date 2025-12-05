<?php $page_title = "Kelola Jadwal Piket"; ?>
<?php
require '../includes/header.php';

// Cek Admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require '../config/db.php';

// Ambil SEMUA kadet untuk dropdown (disiapkan untuk filter JS)
try {
    $stmt = $pdo->prepare("SELECT id, nama, phone, batalyon FROM users WHERE role = 'kadet' ORDER BY batalyon, nama");
    $stmt->execute();
    $all_kadets = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error mengambil data kadet: " . $e->getMessage());
}
?>

<link href="../assets/css/admin_genz.css" rel="stylesheet">

<div class="d-flex" id="wrapper">
    <?php require '../includes/sidebar_admin.php'; ?>
    
    <div id="page-content-wrapper">
        
        <nav class="top-navbar px-3">
            <button id="menu-toggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div class="fw-bold text-maroon">INPUT JADWAL PIKET</div>
        </nav>

        <div class="container-fluid">
            
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success rounded-4 border-0 shadow-sm mb-4 animate__animated animate__fadeInDown">
                            <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger rounded-4 border-0 shadow-sm mb-4 animate__animated animate__fadeInDown">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="card-modern">
                        <div class="card-header-modern bg-maroon text-white text-center py-4" style="border-radius: 25px 25px 0 0; background: linear-gradient(135deg, #800000, #5c0000);">
                            <h4 class="mb-0 fw-bold"><i class="bi bi-calendar-plus me-2"></i> Form Jadwal</h4>
                            <p class="text-white-50 mb-0 small mt-1">Tentukan petugas piket harian</p>
                        </div>
                        
                        <div class="card-body p-4 p-md-5">
                            <form action="../process/manage_roster_process.php" method="POST">
                                
                                <div class="mb-4">
                                    <label for="tanggal_piket" class="form-label fw-bold text-muted small text-uppercase">Tanggal Piket</label>
                                    <input type="date" class="form-control form-control-lg border-0 bg-light rounded-pill px-4" 
                                        id="tanggal_piket" name="tanggal_piket" required>
                                </div>

                                <div class="mb-4">
                                    <label for="batalyon" class="form-label fw-bold text-muted small text-uppercase">Pilih Batalyon</label>
                                    <select class="form-select form-select-lg border-0 bg-light rounded-pill px-4" 
                                            id="batalyon" name="batalyon" required onchange="filterKadet()">
                                        <option value="" selected disabled>-- Pilih Batalyon --</option>
                                        <option value="I">Batalyon I</option>
                                        <option value="II">Batalyon II</option>
                                        <option value="III">Batalyon III</option>
                                        <option value="IV">Batalyon IV</option>
                                    </select>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Pilih Personil Jaga per Grup <span class="text-danger">*</span></label>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                                <div class="bg-light rounded-4 p-3 border-0" style="max-height: 240px; overflow-y: auto;">
                                                    <label class="small fw-semibold">Ketyon</label>
                                                    <input id="search_ketyon" class="form-control form-control-sm mb-2" type="search" placeholder="Cari nama..." oninput="applySearchFilters()">
                                                    <select class="form-select border-0 bg-transparent" 
                                                            id="nama_ketyon" name="nama_ketyon[]" multiple required style="height: 150px;">
                                                        <option value="" disabled>Pilih Batalyon Terlebih Dahulu...</option>
                                                        <?php foreach ($all_kadets as $k): ?>
                                                            <option value="<?php echo htmlspecialchars($k['nama']); ?>|<?php echo htmlspecialchars($k['phone']); ?>" 
                                                                    data-batalyon="<?php echo $k['batalyon']; ?>" 
                                                                    style="display: none;">
                                                                <?php echo htmlspecialchars($k['nama']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="bg-light rounded-4 p-3 border-0" style="max-height: 240px; overflow-y: auto;">
                                                <label class="small fw-semibold">Korsis</label>
                                                <input id="search_korsis" class="form-control form-control-sm mb-2" type="search" placeholder="Cari nama..." oninput="applySearchFilters()">
                                                <select class="form-select border-0 bg-transparent" 
                                                        id="nama_korsis" name="nama_korsis[]" multiple required style="height: 150px;">
                                                    <option value="" disabled>Pilih Batalyon Terlebih Dahulu...</option>
                                                    <?php foreach ($all_kadets as $k): ?>
                                                        <option value="<?php echo htmlspecialchars($k['nama']); ?>|<?php echo htmlspecialchars($k['phone']); ?>" 
                                                                data-batalyon="<?php echo $k['batalyon']; ?>" 
                                                                style="display: none;">
                                                            <?php echo htmlspecialchars($k['nama']); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-text mt-2 text-end">
                                        <small><i class="bi bi-info-circle"></i> Tahan tombol <b>CTRL</b> (Windows) atau <b>CMD</b> (Mac) untuk memilih lebih dari satu orang.</small>
                                    </div>
                                </div>

                                <div class="d-grid gap-3">
                                    <button type="submit" class="btn btn-maroon btn-lg rounded-pill shadow-sm fw-bold py-3">
                                        <i class="bi bi-save-fill me-2"></i> SIMPAN JADWAL
                                    </button>
                                    <a href="lihat_roster.php" class="btn btn-light btn-lg rounded-pill text-muted fw-bold">
                                        Batal / Kembali
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // 1. Toggle Sidebar
    document.getElementById("menu-toggle").onclick = function () { 
        document.getElementById("wrapper").classList.toggle("toggled"); 
    };

    // 2. Filter Kadet Berdasarkan Batalyon (update both Ketyon & Korsis selects)
    function filterKadet() {
        var batalyonPilihan = document.getElementById('batalyon').value;
        var selects = [document.getElementById('nama_ketyon'), document.getElementById('nama_korsis')];
        var adaKadet = false;

        selects.forEach(function(sel) {
            // Reset selection
            if (sel) sel.selectedIndex = -1;
            var options = sel ? sel.getElementsByTagName('option') : [];

            for (var i = 0; i < options.length; i++) {
                var opt = options[i];
                if (opt.disabled) continue;
                if (opt.getAttribute('data-batalyon') === batalyonPilihan) {
                    opt.style.display = 'block';
                    adaKadet = true;
                } else {
                    opt.style.display = 'none';
                }
            }

            // Update placeholder text per select
            if (options.length > 0) {
                if (!adaKadet) {
                    options[0].text = "Tidak ada kadet di Batalyon ini";
                    options[0].style.display = 'block';
                } else {
                    options[0].text = "-- Pilih Personil (Tahan CTRL untuk banyak) --";
                    options[0].style.display = 'block';
                }
            }
        });

        // Setelah mengubah tampilan berdasarkan batalyon, terapkan filter pencarian
        applySearchFilters();
    }

    // 3. Pencarian cepat: filter opsi di setiap select berdasarkan input pencarian
    function applySearchFilters() {
        var qK = document.getElementById('search_ketyon') ? document.getElementById('search_ketyon').value.trim().toLowerCase() : '';
        var qKo = document.getElementById('search_korsis') ? document.getElementById('search_korsis').value.trim().toLowerCase() : '';
        var batalyonPilihan = document.getElementById('batalyon').value;

        filterOptionsByQuery('nama_ketyon', qK, batalyonPilihan);
        filterOptionsByQuery('nama_korsis', qKo, batalyonPilihan);
    }

    function filterOptionsByQuery(selectId, query, batalyonPilihan) {
        var sel = document.getElementById(selectId);
        if (!sel) return;
        var options = sel.getElementsByTagName('option');
        var anyVisible = false;

        for (var i = 0; i < options.length; i++) {
            var opt = options[i];
            if (opt.disabled) continue; // keep placeholder

            // First, check batalyon match (if not match, keep hidden)
            var matchesBatalyon = (opt.getAttribute('data-batalyon') === batalyonPilihan);

            if (!matchesBatalyon) {
                opt.style.display = 'none';
                continue;
            }

            // Then check text match
            var text = (opt.textContent || opt.innerText || '').toLowerCase();
            if (query === '' || text.indexOf(query) !== -1) {
                opt.style.display = 'block';
                anyVisible = true;
            } else {
                opt.style.display = 'none';
            }
        }

        // Update placeholder first option depending on anyVisible
        if (options.length > 0) {
            if (!anyVisible) {
                options[0].text = "Tidak ada kadet yang cocok";
                options[0].style.display = 'block';
            } else {
                options[0].text = "-- Pilih Personil (Tahan CTRL untuk banyak) --";
                options[0].style.display = 'block';
            }
        }
    }
</script>

<style>
    .btn-maroon {
        background-color: #800000;
        color: white;
        border: none;
        transition: all 0.3s;
    }
    .btn-maroon:hover {
        background-color: #5c0000;
        color: #FFD700;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(128, 0, 0, 0.3);
    }
</style>

</body>
</html>