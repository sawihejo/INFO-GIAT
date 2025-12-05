<?php $page_title = "Master Batalyon"; ?>
<?php
require '../includes/header.php';
// Cek Admin
if ($_SESSION['role'] !== 'admin') { header('Location: login.php'); exit; }
require '../config/db.php';

// Logika Update (Sederhana)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $jumlah = $_POST['jumlah'];
    $stmt = $pdo->prepare("UPDATE tbl_batalyon SET jumlah_total_personil = ? WHERE id = ?");
    $stmt->execute([$jumlah, $id]);
    $_SESSION['success'] = "Data berhasil diperbarui!";
    header('Location: manage_batalyon.php');
    exit;
}

$batalyon_list = $pdo->query("SELECT * FROM tbl_batalyon")->fetchAll();
?>

<link href="../assets/css/admin_genz.css" rel="stylesheet">

<div class="d-flex" id="wrapper">
    <?php require '../includes/sidebar_admin.php'; ?>
    
    <div id="page-content-wrapper">
        <nav class="top-navbar px-3">
            <button class="btn btn-light shadow-sm rounded-circle" id="menu-toggle"><i class="bi bi-list fs-4 text-maroon"></i></button>
            <div class="fw-bold text-maroon">DATA INDUK BATALYON</div>
        </nav>

        <div class="container-fluid">
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success rounded-4 shadow-sm mb-4">
                    <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <?php foreach ($batalyon_list as $bat): ?>
                    <div class="col-md-6 col-xl-3">
                        <div class="card-modern h-100">
                            <div class="card-header-modern bg-light text-center py-4">
                                <h1 class="display-3 fw-bold text-maroon mb-0"><?php echo $bat['nama_batalyon']; ?></h1>
                                <small class="text-muted fw-bold">BATALYON</small>
                            </div>
                            <div class="card-body p-4">
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?php echo $bat['id']; ?>">
                                    <label class="form-label text-muted small fw-bold">TOTAL PERSONIL</label>
                                    <div class="input-group">
                                        <input type="number" name="jumlah" class="form-control form-control-lg border-0 bg-light text-center fw-bold" value="<?php echo $bat['jumlah_total_personil']; ?>">
                                        <button class="btn btn-warning text-dark fw-bold" type="submit">
                                            <i class="bi bi-save-fill"></i>
                                        </button>
                                    </div>
                                </form>
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
    document.getElementById("menu-toggle").onclick = function () { 
        document.getElementById("wrapper").classList.toggle("toggled"); 
    };
</script>
</body>
</html>