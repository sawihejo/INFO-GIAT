<?php $page_title = "Input Rencana Kegiatan"; ?>
<?php $currentPage = 'input_piket'; ?>
<?php
require '../includes/header.php';
if ($_SESSION['role'] !== 'kadet') {
    header('Location: login.php'); exit;
}
require '../includes/navbar.php';
?>

<style>
    body { background-color: #F0F2F5; }
    .form-container { max-width: 700px; margin: 3rem auto; padding: 0 1rem; }
    .form-card { background: rgba(255,255,255,0.95); border-radius: 20px; padding: 2rem; box-shadow: 0 10px 30px rgba(128,0,0,0.06); position: relative; }
    .form-card::before { content: ''; position: absolute; top:0; left:0; right:0; height:4px; background: linear-gradient(90deg,#800000,#FFD700); }
    .form-title { font-weight:800; color:#2b0000; margin-bottom:0.5rem; }
    .form-sub { color:#666; margin-bottom:1.25rem; }
    .form-control { border-radius:12px; border:1px solid rgba(128,0,0,0.08); padding:.7rem 1rem; font-family:'Outfit', 'Segoe UI', sans-serif; }
    .btn-modern { border-radius:12px; padding:.6rem 1.25rem; font-weight:600; }
    .btn-back{ background:#f0f2f5; color:#2b0000 }
    .btn-submit{ background: linear-gradient(135deg,#ffc107,#ff9800); color:#fff }
</style>

<div class="form-container">
    <div class="form-card">
        <h2 class="form-title"><i class="bi bi-calendar-check-fill" style="color:#ffc107; margin-right:.5rem"></i>Rencana Kegiatan</h2>
        <p class="form-sub">Isi rencana kegiatan untuk Batalyon <?php echo htmlspecialchars($_SESSION['batalyon']); ?> hari ini.</p>

        <div id="ajax-message" class="mt-3" style="display:none;"></div>

        <form action="../process/insert_rencana.php" method="POST" id="formRencana">
            <div class="mb-3">
                <label for="tanggal_kegiatan" class="form-label">Tanggal Kegiatan</label>
                <input type="date" class="form-control" id="tanggal_kegiatan" name="tanggal_kegiatan" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="isi_rencana" class="form-label">Rencana Kegiatan</label>
                <textarea class="form-control" id="isi_rencana" name="isi_rencana" rows="10" placeholder="Tuliskan detail rencana kegiatan di sini..." required></textarea>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="dashboard_piket.php" class="btn btn-modern btn-back"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-modern btn-submit"><i class="bi bi-check-circle"></i> Simpan Rencana</button>
            </div>
        </form>
    </div>
</div>

<?php require '../includes/footer.php'; ?>
<script>
    const formRencana = document.getElementById('formRencana');
    const ajaxMessage = document.getElementById('ajax-message');

    formRencana.addEventListener('submit', function(event) {
        event.preventDefault(); // Cegah reload
        ajaxMessage.style.display = 'none';

        const formData = new FormData(formRencana);

        fetch('../process/insert_rencana.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            ajaxMessage.style.display = 'block';
            if (data.success) {
                ajaxMessage.className = 'alert alert-success';
                ajaxMessage.textContent = data.message;
                formRencana.reset();
            } else {
                ajaxMessage.className = 'alert alert-danger';
                ajaxMessage.textContent = 'Error: ' + data.message;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            ajaxMessage.style.display = 'block';
            ajaxMessage.className = 'alert alert-danger';
            ajaxMessage.textContent = 'Terjadi kesalahan koneksi atau respons server tidak valid.';
        });
    });
</script>