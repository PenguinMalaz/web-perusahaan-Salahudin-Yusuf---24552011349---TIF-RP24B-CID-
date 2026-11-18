<?php 
include 'includes/data_dummy.php';
include 'includes/header.php'; 
?>

<main>
    <!-- Hero Section -->
    <section class="bg-white py-5 border-bottom">
        <div class="container text-center">
            <h1 class="display-5 fw-bold text-dark">Informasi & Berita Perusahaan</h1>
            <p class="lead text-muted mx-auto" style="max-width: 650px;">
                Temukan pembaruan terkini seputar inovasi teknologi, layanan bisnis, dan kegiatan resmi perusahaan kami.
            </p>
        </div>
    </section>

    <!-- Daftar Informasi -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <?php foreach ($informasi_perusahaan as $info) : ?>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm card-hover">
                            <img src="<?= $info['gambar']; ?>" class="card-img-top" alt="<?= $info['judul']; ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <span class="badge bg-primary-subtle text-primary"><?= $info['kategori']; ?></span>
                                    <small class="text-muted ms-2"><?= $info['tanggal']; ?></small>
                                </div>
                                <h5 class="card-title fw-bold"><?= $info['judul']; ?></h5>
                                <p class="card-text text-muted small flex-grow-1"><?= $info['ringkasan']; ?></p>
                                <!-- Mengirim parameter ID ke halaman detail -->
                                <a href="detail.php?id=<?= $info['id']; ?>" class="btn btn-outline-primary mt-3 w-100">
                                    Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>