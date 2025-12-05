<?php $page_title = "Input Data Apel"; ?>
<?php $currentPage = 'input_piket'; ?>
<?php
require '../includes/header.php';
// Cek role
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
    .form-control { border-radius:12px; border:1px solid rgba(128,0,0,0.08); padding:.7rem 1rem; }
    .btn-modern { border-radius:12px; padding:.6rem 1.25rem; font-weight:600; }
    .btn-back{ background:#f0f2f5; color:#2b0000 }
    .btn-submit{ background: linear-gradient(135deg,#28a745,#20c997); color:#fff }
</style>

<div class="form-container">
    <div class="form-card">
        <h2 class="form-title"><i class="bi bi-people-fill" style="color:#28a745; margin-right:.5rem"></i>Formulir Input Data Apel</h2>
        <p class="form-sub">Masukkan data absen per kategori (misal: "Sakit", "Ibadah", "Piket CO", dll).</p>

        <div id="ajax-message" class="mt-3" style="display:none;"></div>

        <form action="../process/insert_apel.php" method="POST" id="formApel">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="jenis_apel" class="form-label">Jenis Apel</label>
                    <input type="text" class="form-control" id="jenis_apel" name="jenis_apel" placeholder="Contoh: Pagi, Malam, Luar Biasa" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tanggal_apel" class="form-label">Tanggal Apel</label>
                    <input type="date" class="form-control" id="tanggal_apel" name="tanggal_apel" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan Absen</label>
                <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Contoh: ibadah katolik, sakit di rs Helsa, giat imrc" required>
            </div>

            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" value="1" min="1" required>
            </div>

            <div class="mb-3">
                <label for="nama_nama" class="form-label">Nama-Nama Personil</label>
                <textarea class="form-control" id="nama_nama" name="nama_nama" rows="3" placeholder="Contoh: angel, eci, geby, tessa, quin" required></textarea>
                <div class="form-text">Pisahkan setiap nama dengan koma (,) atau baris baru.</div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="dashboard_piket.php" class="btn btn-modern btn-back"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
                <button type="submit" class="btn btn-modern btn-submit"><i class="bi bi-check-circle"></i> Simpan & Input Lagi</button>
            </div>
        </form>
    </div>
</div>

<?php require '../includes/footer.php'; ?>

<script>
    // Tangkap elemen form dan div pesan
    const formApel = document.getElementById('formApel');
    const ajaxMessage = document.getElementById('ajax-message');

    formApel.addEventListener('submit', function(event) {
        event.preventDefault();
        ajaxMessage.style.display = 'none';

        const formData = new FormData(formApel);

        fetch('../process/insert_apel.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            ajaxMessage.style.display = 'block';
            if (data.success) {
                ajaxMessage.className = 'alert alert-success';
                ajaxMessage.textContent = data.message + ' Silakan input data berikutnya.';
                formApel.reset();
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