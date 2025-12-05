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
                                    <h6 class="text-muteSebend fw-bold mb-3">PETUGAS JAGA</h6>
                                    <?php
                                        // Nama_piket and kontak_piket are stored as grouped lines, e.g. "Ketyon: A, B\nKorsis: C, D"
                                        $groups = preg_split('/\r?\n/', $r['nama_piket'] ?? '');
                                        $kontak_groups = preg_split('/\r?\n/', $r['kontak_piket'] ?? '');

                                        // Map groups to associative array for easier rendering
                                        $group_map = ['ketyon' => '', 'korsis' => ''];
                                        foreach ($groups as $grp) {
                                            $grp = trim($grp);
                                            if ($grp === '') continue;
                                            if (strpos($grp, ':') !== false) {
                                                list($label, $members) = explode(':', $grp, 2);
                                                $key = strtolower(trim($label));
                                                $key = $key === 'ketyon' ? 'ketyon' : ($key === 'korsis' ? 'korsis' : $key);
                                                $group_map[$key] = trim($members);
                                            } else {
                                                // Put unnamed group into ketyon by default if empty
                                                if (empty($group_map['ketyon'])) $group_map['ketyon'] = $grp;
                                            }
                                        }

                                        // Map kontak groups
                                        $kontak_map = ['ketyon' => '', 'korsis' => ''];
                                        foreach ($kontak_groups as $kgrp) {
                                            $kgrp = trim($kgrp);
                                            if ($kgrp === '') continue;
                                            if (strpos($kgrp, ':') !== false) {
                                                list($klabel, $kcontacts) = explode(':', $kgrp, 2);
                                                $kkey = strtolower(trim($klabel));
                                                $kkey = $kkey === 'ketyon' ? 'ketyon' : ($kkey === 'korsis' ? 'korsis' : $kkey);
                                                $kontak_map[$kkey] = trim($kcontacts);
                                            } else {
                                                if (empty($kontak_map['ketyon'])) $kontak_map['ketyon'] = $kgrp;
                                            }
                                        }

                                        // Responsive two-column layout with a center divider
                                        echo '<div class="d-flex flex-column flex-md-row align-items-start gap-3">';

                                        // Left: Ketyon
                                        echo '<div class="flex-fill text-start">';
                                        echo '<h6 class="text-maroon fw-bold mb-1" style="font-size:0.95rem;">Ketyon</h6>';
                                        if (!empty($group_map['ketyon'])) {
                                            echo '<p class="fw-bold text-dark fs-6 mb-0">' . nl2br(htmlspecialchars($group_map['ketyon'])) . '</p>';
                                        } else {
                                            echo '<p class="text-muted small mb-0">- Tidak ada Ketyon -</p>';
                                        }
                                        // kontak ketyon
                                        if (!empty($kontak_map['ketyon'])) {
                                            echo '<div class="mt-3">';
                                            echo '<span class="badge bg-light text-dark rounded-pill px-3 py-2 border"><i class="bi bi-telephone-fill text-success me-1"></i> ' . htmlspecialchars($kontak_map['ketyon']) . '</span>';
                                            echo '</div>';
                                        }
                                        echo '</div>';

                                        // Middle divider (visible on md+)
                                        echo '<div class="d-none d-md-block" style="width:1px; background: rgba(0,0,0,0.06); height: 100%; margin: 0 12px;"></div>';

                                        // Right: Korsis
                                        echo '<div class="flex-fill text-start">';
                                        echo '<h6 class="text-maroon fw-bold mb-1" style="font-size:0.95rem;">Korsis</h6>';
                                        if (!empty($group_map['korsis'])) {
                                            echo '<p class="fw-bold text-dark fs-6 mb-0">' . nl2br(htmlspecialchars($group_map['korsis'])) . '</p>';
                                        } else {
                                            echo '<p class="text-muted small mb-0">- Tidak ada Korsis -</p>';
                                        }
                                        // kontak korsis
                                        if (!empty($kontak_map['korsis'])) {
                                            echo '<div class="mt-3">';
                                            echo '<span class="badge bg-light text-dark rounded-pill px-3 py-2 border"><i class="bi bi-telephone-fill text-success me-1"></i> ' . htmlspecialchars($kontak_map['korsis']) . '</span>';
                                            echo '</div>';
                                        }
                                        echo '</div>';

                                        echo '</div>'; // end two-column
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