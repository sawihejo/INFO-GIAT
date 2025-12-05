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

<main class="container mt-4">
    <div class="card shadow-sm border-0 col-md-8 mx-auto">
        <div class="card-body p-4 p-md-5">
            <h2 class="h4 mb-4">Formulir Input Data Apel</h2>
            <p class="text-muted">Masukkan data absen per kategori (misal: "Sakit", "Ibadah", "Piket CO", dll). Anda bisa mengulangi form ini untuk setiap kategori yang berbeda.</p>
            
            <p class="text-muted">Masukkan data absen per kategori...</p>

            <div id="ajax-message" class="mt-3" style="display: none;"></div> 
            <form action="../process/insert_apel.php" method="POST" id="formApel">

            <form action="../process/insert_apel.php" method="POST">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                    <label for="jenis_apel" class="form-label">Jenis Apel</label>
                    <input type="text" class="form-control" id="jenis_apel" name="jenis_apel" 
                    placeholder="Contoh: Pagi, Malam, Luar Biasa" required>
                </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_apel" class="form-label">Tanggal Apel</label>
                        <input type="date" class="form-control" id="tanggal_apel" name="tanggal_apel" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan Absen</label>
                    <input type="text" class="form-control" id="keterangan" name="keterangan" 
                    placeholder="Contoh: ibadah katolik, sakit di rs Helsa, giat imrc" required>
                </div>

                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input type="number" class="form-control" id="jumlah" name="jumlah" value="1" min="1" required>
                </div>
                
                <div class="mb-3">
                    <label for="nama_nama" class="form-label">Nama-Nama Personil</label>
                    <textarea class="form-control" id="nama_nama" name="nama_nama" rows="3" 
                            placeholder="Contoh: angel, eci, geby, tessa, quin" required></textarea>
                    <div class="form-text">Pisahkan setiap nama dengan koma (,) atau baris baru.</div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="dashboard_piket.php" class="btn btn-secondary">Kembali ke Dashboard</a>
                    <button type="submit" class="btn btn-primary">Simpan & Input Lagi</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require '../includes/footer.php'; ?>

<script>
    // Tangkap elemen form dan div pesan
    const formApel = document.getElementById('formApel');
    const ajaxMessage = document.getElementById('ajax-message');

    // Tambahkan event listener saat form di-submit
    formApel.addEventListener('submit', function(event) {
        // 1. Cegah form submit normal (agar tidak reload)
        event.preventDefault(); 

        // 2. Ambil data dari form
        const formData = new FormData(formApel);

        // 3. Kirim data menggunakan Fetch API (AJAX)
        fetch('../process/insert_apel.php', {
            method: 'POST',
            body: formData 
        })
        .then(response => response.json()) // Ubah respons menjadi JSON
        .then(data => {
            // 4. Proses respons JSON dari PHP
            ajaxMessage.style.display = 'block'; // Tampilkan div pesan

            if (data.success) {
                // Jika sukses: tampilkan pesan sukses, reset form
                ajaxMessage.className = 'alert alert-success';
                ajaxMessage.textContent = data.message + ' Silakan input data berikutnya.';
                formApel.reset(); // Kosongkan isian form
                // (Opsional) Sembunyikan pesan setelah beberapa detik
                // setTimeout(() => { ajaxMessage.style.display = 'none'; }, 5000);
            } else {
                // Jika gagal: tampilkan pesan error
                ajaxMessage.className = 'alert alert-danger';
                ajaxMessage.textContent = 'Error: ' + data.message;
            }
        })
        .catch(error => {
            // Tangani jika ada error jaringan atau JSON tidak valid
            console.error('Error:', error);
            ajaxMessage.style.display = 'block';
            ajaxMessage.className = 'alert alert-danger';
            ajaxMessage.textContent = 'Terjadi kesalahan koneksi atau respons server tidak valid.';
        });
    });
</script>