<?php $page_title = "Input Dokumentasi"; ?>
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
    .form-control, .form-select { border-radius:12px; border:1px solid rgba(128,0,0,0.08); padding:.7rem 1rem; }
    .btn-modern { border-radius:12px; padding:.6rem 1.25rem; font-weight:600; }
    .btn-back{ background:#f0f2f5; color:#2b0000 }
    .btn-submit{ background: linear-gradient(135deg,#17a2b8,#138496); color:#fff }
</style>

<div class="form-container">
    <div class="form-card">
        <h2 class="form-title"><i class="bi bi-camera-fill" style="color:#17a2b8; margin-right:.5rem"></i>Upload Dokumentasi</h2>
        <p class="form-sub">Unggah foto dokumentasi untuk Batalyon <?php echo htmlspecialchars($_SESSION['batalyon']); ?>.</p>

        <div id="ajax-message" class="mt-3" style="display:none;"></div>

        <form action="../process/insert_dokumentasi.php" method="POST" enctype="multipart/form-data" id="formDokumentasi">
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori Dokumentasi</label>
                <select class="form-select" id="kategori" name="kategori" required onchange="toggleLainnya()">
                    <option value="" selected disabled>Pilih Kategori...</option>
                    <option value="makan_pagi">Makan Pagi</option>
                    <option value="makan_siang">Makan Siang</option>
                    <option value="makan_malam">Makan Malam</option>
                    <option value="apel_pagi">Apel Pagi</option>
                    <option value="apel_malam">Apel Malam</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div class="mb-3" id="grup_keterangan_lainnya" style="display: none;">
                <label for="keterangan_lainnya" class="form-label">Keterangan (Lainnya)</label>
                <input type="text" class="form-control" id="keterangan_lainnya" name="keterangan_lainnya" placeholder="Misal: Kegiatan Olahraga Pagi">
            </div>

            <div class="mb-3">
                <label for="file_dokumentasi" class="form-label">Upload Foto</label>
                <input class="form-control" type="file" id="file_dokumentasi" name="file_dokumentasi" accept="image/jpeg, image/png" required>
                <div class="form-text">Hanya file .jpg, .jpeg, atau .png. Ukuran maks 5MB.</div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="dashboard_piket.php" class="btn btn-modern btn-back"><i class="bi bi-arrow-left"></i> Kembali</a>
                <button type="submit" class="btn btn-modern btn-submit"><i class="bi bi-cloud-upload"></i> Simpan Dokumentasi</button>
            </div>
        </form>
    </div>
</div>

<?php require '../includes/footer.php'; ?>

<script>
    function toggleLainnya() {
        var kategori = document.getElementById('kategori').value;
        var grupLainnya = document.getElementById('grup_keterangan_lainnya');
        var inputLainnya = document.getElementById('keterangan_lainnya');

        if (kategori === 'lainnya') {
            grupLainnya.style.display = 'block';
            inputLainnya.required = true;
        } else {
            grupLainnya.style.display = 'none';
            inputLainnya.required = false;
        }
    }

    const formDokumentasi = document.getElementById('formDokumentasi');
    const ajaxMessage = document.getElementById('ajax-message');
    const fileInput = document.getElementById('file_dokumentasi');

    formDokumentasi.addEventListener('submit', function(event) {
        event.preventDefault();
        ajaxMessage.style.display = 'none';
        ajaxMessage.textContent = '';

        ajaxMessage.className = 'alert alert-info';
        ajaxMessage.textContent = 'Mengunggah file, mohon tunggu...';
        ajaxMessage.style.display = 'block';

        const formData = new FormData(formDokumentasi);

        fetch('../process/insert_dokumentasi.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            ajaxMessage.style.display = 'block';
            if (data.success) {
                ajaxMessage.className = 'alert alert-success';
                ajaxMessage.textContent = data.message + ' Silakan unggah dokumentasi berikutnya.';
                formDokumentasi.reset();
                toggleLainnya();
            } else {
                ajaxMessage.className = 'alert alert-danger';
                ajaxMessage.textContent = 'Error: ' + data.message;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            ajaxMessage.style.display = 'block';
            ajaxMessage.className = 'alert alert-danger';
            ajaxMessage.textContent = 'Terjadi kesalahan koneksi atau respons server tidak valid. Pastikan file tidak terlalu besar dan formatnya benar.';
        });
    });

    fileInput.addEventListener('change', function() {
        ajaxMessage.style.display = 'none';
        ajaxMessage.textContent = '';
    });
</script>