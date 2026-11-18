<?php 
include 'includes/data_dummy.php';
include 'includes/header.php'; 

// Menangkap ID dari URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$detail = null;

foreach ($informasi_perusahaan as $item) {
    if ((int)$item['id'] === $id) {
        $detail = $item;
        break;
    }
}
?>

<main class="py-5">
    <div class="container">
        <?php if ($detail) : ?>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <a href="index.php" class="btn btn-sm btn-secondary mb-4">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                    </a>
                    <div class="card border-0 shadow-sm">
                        <img src="<?= $detail['gambar']; ?>" class="card-img-top" alt="<?= $detail['judul']; ?>" style="max-height: 400px; object-fit: cover;">
                        <div class="card-body p-4 p-md-5">
                            <div class="mb-3">
                                <span class="badge bg-primary"><?= $detail['kategori']; ?></span>
                                <span class="text-muted ms-2"><i class="bi bi-calendar3 me-1"></i><?= $detail['tanggal']; ?></span>
                            </div>
                            <h2 class="fw-bold mb-4"><?= $detail['judul']; ?></h2>
                            <p class="fs-5 text-secondary lh-lg"><?= $detail['konten']; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="alert alert-warning text-center">
                <h4>Informasi tidak ditemukan!</h4>
                <p>Data yang Anda cari tidak tersedia atau telah dihapus.</p>
                <a href="index.php" class="btn btn-primary">Kembali ke Halaman Utama</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>