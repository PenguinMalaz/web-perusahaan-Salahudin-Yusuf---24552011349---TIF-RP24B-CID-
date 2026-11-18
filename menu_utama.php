<?php
session_start();

// Wajib login untuk mengakses Menu Utama
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

include 'includes/data_dummy.php';
include 'includes/header.php';
?>

<main class="py-5">
    <div class="container">
        <!-- Banner Selamat Datang -->
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <span class="badge bg-warning text-dark mb-2">Menu Utama Pengguna</span>
                    <h2 class="fw-bold mb-1">Selamat Datang, <?= htmlspecialchars($_SESSION['nama'] ?? $_SESSION['username']); ?>!</h2>
                    <p class="mb-0 opacity-75">Anda login sebagai <strong><?= htmlspecialchars($_SESSION['username']); ?></strong>. Silakan jelajahi layanan dan informasi resmi perusahaan.</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="index.php" class="btn btn-light text-primary fw-semibold me-2">
                        <i class="bi bi-house-door me-1"></i> Beranda Publik
                    </a>
                    <a href="logout.php" class="btn btn-danger" onclick="return confirm('Yakin ingin keluar?')">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Menu Navigasi Cepat -->
        <div class="row g-3 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary-subtle text-primary rounded-3 p-3 me-3">
                            <i class="bi bi-person-badge fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Status Akun</h6>
                            <p class="text-muted small mb-0">Aktif & Terverifikasi</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-success-subtle text-success rounded-3 p-3 me-3">
                            <i class="bi bi-newspaper fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Informasi Tersedia</h6>
                            <p class="text-muted small mb-0"><?= count($informasi_perusahaan); ?> Pengumuman Perusahaan</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-info-subtle text-info rounded-3 p-3 me-3">
                            <i class="bi bi-headset fs-3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Layanan Mitra</h6>
                            <p class="text-muted small mb-0">Akses Informasi Penuh</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Informasi Perusahaan -->
        <h4 class="fw-bold mb-3">Informasi & Pengumuman Terbaru</h4>
        <div class="row g-4">
            <?php foreach ($informasi_perusahaan as $info) : ?>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm card-hover">
                        <img src="<?= htmlspecialchars($info['gambar']); ?>" class="card-img-top" alt="<?= htmlspecialchars($info['judul']); ?>" style="height: 190px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-primary-subtle text-primary"><?= htmlspecialchars($info['kategori']); ?></span>
                                <small class="text-muted ms-2"><?= htmlspecialchars($info['tanggal']); ?></small>
                            </div>
                            <h5 class="card-title fw-bold"><?= htmlspecialchars($info['judul']); ?></h5>
                            <p class="card-text text-muted small flex-grow-1"><?= htmlspecialchars($info['ringkasan']); ?></p>
                            <a href="detail.php?id=<?= $info['id']; ?>" class="btn btn-primary mt-3 w-100">
                                Baca Detail Informasi <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>