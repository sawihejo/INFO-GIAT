<?php $page_title = "Jadwal Piket"; ?>
<?php
require '../includes/header.php';
if ($_SESSION['role'] !== 'admin') { header('Location: login.php'); exit; }
require '../config/db.php';

$tanggal = isset($_GET['t']) ? $_GET['t'] : date('Y-m-d');
try {
    $stmt = $pdo->prepare("SELECT * FROM tbl_piket_jaga WHERE tanggal_piket = :t ORDER BY batalyon ASC");
    $stmt->execute([':t' => $tanggal]);
    $roster = $stmt->fetchAll();
} catch (PDOException $e) { die($e->getMessage()); }
?>

<link href="../assets/css/admin_genz.css" rel="stylesheet">

<div class="d-flex" id="wrapper">
    <?php require '../includes/sidebar_admin.php'; ?>
    
    <div id="page-content-wrapper">
        <nav class="top-navbar px-3">
            <button class="btn btn-light shadow-sm rounded-circle" id="menu-toggle"><i class="bi bi-list fs-4 text-maroon"></i></button>
            <div class="fw-bold text-maroon">JADWAL PIKET</div>
        </nav>

        <div class="container-fluid">
            <div class="card-modern p-4 mb-4">
                <form method="GET" class="d-flex gap-3 align-items-center">
                    <label class="fw-bold text-muted">Tanggal:</label>
                    <input type="date" name="t" class="form-control border-0 bg-light rounded-pill w-auto px-3" value="<?php echo $tanggal; ?>" onchange="this.form.submit()">
                </form>
            </div>

            <div class="row g-4">
                <?php if (empty($roster)): ?>
                    <div class="col-12 text-center py-5">
                        <img src="../assets/img/no_data.svg" style="height: 150px; opacity: 0.5;" alt="Kosong">
                        <p class="text-muted mt-3 fw-bold">Belum ada jadwal untuk tanggal ini.</p>
                        <a href="manage_jadwal.php" class="btn btn-warning rounded-pill fw-bold mt-2">Buat Jadwal</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($roster as $r): ?>
                        <div class="col-md-6 col-xl-3">
                            <div class="card-modern h-100">
                                <div class="card-header-modern bg-maroon text-white text-center py-3" style="border-radius: 25px 25px 0 0; background: linear-gradient(135deg, #800000, #5c0000);">
                                    <h3 class="mb-0 fw-bold">BATALYON <?php echo $r['batalyon']; ?></h3>
                                </div>
                                <div class="card-body p-4 text-center">
                                    <h6 class="text-muted fw-bold mb-3">PETUGAS JAGA</h6>
                                    <?php
                                        // Nama_piket and kontak_piket may be stored as grouped lines, e.g. "Ketyon: A, B\nKorsis: C, D"
                                        $groups = preg_split('/\r?\n/', $r['nama_piket']);
                                        $kontak_groups = preg_split('/\r?\n/', $r['kontak_piket']);
                                        foreach ($groups as $idx => $grp) {
                                            // Expect format "Label: names..."; otherwise print raw
                                            if (strpos($grp, ':') !== false) {
                                                list($label, $members) = explode(':', $grp, 2);
                                                echo '<div class="mb-3">';
                                                echo '<h6 class="text-maroon fw-bold mb-1" style="font-size:0.95rem;">' . htmlspecialchars(trim($label)) . '</h6>';
                                                echo '<p class="fw-bold text-dark fs-6 mb-0">' . htmlspecialchars(trim($members)) . '</p>';
                                                echo '</div>';
                                            } else {
                                                echo '<p class="fw-bold text-dark fs-5 mb-4">' . htmlspecialchars($grp) . '</p>';
                                            }
                                        }
                                    ?>

                                    <hr class="border-light">

                                    <?php
                                        // Render kontak groups similarly (show icons per group)
                                        foreach ($kontak_groups as $idx => $kgrp) {
                                            if (strpos($kgrp, ':') !== false) {
                                                list($klabel, $kcontacts) = explode(':', $kgrp, 2);
                                                echo '<div class="d-flex align-items-center justify-content-center gap-2 mt-2">';
                                                echo '<span class="badge bg-light text-dark rounded-pill px-3 py-2 border"><i class="bi bi-telephone-fill text-success me-1"></i> ' . htmlspecialchars(trim($kcontacts)) . '</span>';
                                                echo '</div>';
                                            } else {
                                                echo '<div class="d-flex align-items-center justify-content-center gap-2 mt-2">';
                                                echo '<span class="badge bg-light text-dark rounded-pill px-3 py-2 border"><i class="bi bi-telephone-fill text-success me-1"></i> ' . htmlspecialchars($kgrp) . '</span>';
                                                echo '</div>';
                                            }
                                        }
                                    ?>
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