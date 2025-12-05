<?php $page_title = "Input Dokumentasi"; ?>
<?php $currentPage = 'input_piket'; ?>
<?php
require '../includes/header.php';
if ($_SESSION['role'] !== 'kadet') {
    header('Location: login.php'); exit;
}
require '../includes/navbar.php';
?>

<main class="container mt-4">
    <div class="card shadow-sm border-0 col-md-8 mx-auto">
        <div class="card-body p-4 p-md-5">
            <h2 class="h4 mb-4">Formulir Upload Dokumentasi</h2>
            <p class="text-muted">Unggah foto dokumentasi untuk Batalyon <?php echo htmlspecialchars($_SESSION['batalyon']); ?>.</p>
            <div id="ajax-message" class="mt-3" style="display: none;"></div> 
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
                    <a href="dashboard_piket.php" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Dokumentasi</button>
                </div>
            </form>
        </div>
    </div>
</main>

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
</script>

<?php require '../includes/footer.php'; ?>

<script>
    const formDokumentasi = document.getElementById('formDokumentasi');
    const ajaxMessage = document.getElementById('ajax-message');
    const fileInput = document.getElementById('file_dokumentasi'); // Ambil input file

    formDokumentasi.addEventListener('submit', function(event) {
        event.preventDefault(); 
        ajaxMessage.style.display = 'none'; // Sembunyikan pesan lama
        ajaxMessage.textContent = '';

        // Tampilkan pesan loading sederhana
        ajaxMessage.className = 'alert alert-info';
        ajaxMessage.textContent = 'Mengunggah file, mohon tunggu...';
        ajaxMessage.style.display = 'block';

        const formData = new FormData(formDokumentasi);

        fetch('../process/insert_dokumentasi.php', {
            method: 'POST',
            body: formData 
            // Tidak perlu 'Content-Type' header untuk FormData
        })
        .then(response => {
            // Cek jika respons OK tapi mungkin bukan JSON (error server)
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
                formDokumentasi.reset(); // Kosongkan form
                // Panggil fungsi toggleLainnya lagi untuk mereset field dinamis
                toggleLainnya(); 
            } else {
                ajaxMessage.className = 'alert alert-danger';
                ajaxMessage.textContent = 'Error: ' + data.message;
                // JANGAN reset form jika gagal, agar user tidak perlu isi ulang
            }
        })
        .catch(error => {
            console.error('Error:', error);
            ajaxMessage.style.display = 'block';
            ajaxMessage.className = 'alert alert-danger';
            ajaxMessage.textContent = 'Terjadi kesalahan koneksi atau respons server tidak valid. Pastikan file tidak terlalu besar dan formatnya benar.';
        });
    });

    // (Opsional) Reset pesan error jika user memilih file baru
    fileInput.addEventListener('change', function() {
        ajaxMessage.style.display = 'none';
        ajaxMessage.textContent = '';
    });
</script>