<?php $page_title = "Input Rencana Kegiatan"; ?>
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
            <h2 class="h4 mb-4">Formulir Rencana Kegiatan</h2>
            <p class="text-muted">Isi rencana kegiatan untuk Batalyon <?php echo htmlspecialchars($_SESSION['batalyon']); ?> hari ini.</p>
            <div id="ajax-message" class="mt-3" style="display: none;"></div>
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
                    <a href="dashboard_piket.php" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Rencana</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require '../includes/footer.php'; ?>
<script>
    const formRencana = document.getElementById('formRencana');
    const ajaxMessage = document.getElementById('ajax-message');

    formRencana.addEventListener('submit', function(event) {
        event.preventDefault(); // Cegah reload
        ajaxMessage.style.display = 'none'; // Sembunyikan pesan lama

        const formData = new FormData(formRencana);

        fetch('../process/insert_rencana.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            ajaxMessage.style.display = 'block'; // Tampilkan div pesan

            if (data.success) {
                ajaxMessage.className = 'alert alert-success';
                ajaxMessage.textContent = data.message;
                formRencana.reset(); // Kosongkan form jika sukses
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