<?php
session_start();

// Cek apakah sudah login
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Jika bukan admin, alihkan ke Menu Utama biasa
if (($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: menu_utama.php");
    exit;
}

require_once 'api/koneksi.php';

// PROSES CRUD (CREATE, UPDATE, DELETE)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi'])) {
    $aksi = $_POST['aksi'];

    // 1. TAMBAH DATA (CREATE)
    if ($aksi === 'tambah') {
        $judul     = $_POST['judul'];
        $kategori  = $_POST['kategori'];
        $tanggal   = $_POST['tanggal'];
        $gambar    = !empty($_POST['gambar']) ? $_POST['gambar'] : 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80';
        $ringkasan = $_POST['ringkasan'];
        $konten    = $_POST['konten'];

        $stmt = $conn->prepare("INSERT INTO informasi (judul, kategori, tanggal, gambar, ringkasan, konten) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $judul, $kategori, $tanggal, $gambar, $ringkasan, $konten);
        $stmt->execute();

        $_SESSION['flash_msg'] = "Informasi baru berhasil ditambahkan!";
        header("Location: dashboard.php");
        exit;
    }

    // 2. EDIT DATA (UPDATE)
    if ($aksi === 'edit') {
        $id        = (int)$_POST['id'];
        $judul     = $_POST['judul'];
        $kategori  = $_POST['kategori'];
        $tanggal   = $_POST['tanggal'];
        $gambar    = $_POST['gambar'];
        $ringkasan = $_POST['ringkasan'];
        $konten    = $_POST['konten'];

        $stmt = $conn->prepare("UPDATE informasi SET judul=?, kategori=?, tanggal=?, gambar=?, ringkasan=?, konten=? WHERE id=?");
        $stmt->bind_param("ssssssi", $judul, $kategori, $tanggal, $gambar, $ringkasan, $konten, $id);
        $stmt->execute();

        $_SESSION['flash_msg'] = "Data informasi berhasil diperbarui!";
        header("Location: dashboard.php");
        exit;
    }

    // 3. HAPUS DATA (DELETE)
    if ($aksi === 'hapus') {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM informasi WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $_SESSION['flash_msg'] = "Data informasi berhasil dihapus!";
        header("Location: dashboard.php");
        exit;
    }
}

// AMBIL SEMUA DATA DARI DATABASE (READ)
$informasi_perusahaan = [];
$query = $conn->query("SELECT * FROM informasi ORDER BY id ASC");
if ($query) {
    while ($row = $query->fetch_assoc()) {
        $informasi_perusahaan[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Utama - Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; background-color: #f4f6f9; }
        main { flex: 1; }
    </style>
</head>
<body>

<!-- Navbar Khusus Setelah Login -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            Dashboard Admin
        </a>
        <div class="ms-auto d-flex align-items-center">
            <span class="text-light small me-3 d-none d-sm-inline">Halo, <strong><?= htmlspecialchars($_SESSION['username']); ?></strong></span>
            <a href="index.php" class="btn btn-outline-light btn-sm me-2">Lihat Web</a>
            <a href="logout.php" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin keluar?')">Logout</a>
        </div>
    </div>
</nav>

<main class="py-5">
    <div class="container">
        <!-- Notifikasi Berhasil CRUD -->
        <?php if (isset($_SESSION['flash_msg'])) : ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['flash_msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['flash_msg']); ?>
        <?php endif; ?>

        <!-- Ringkasan Statistik -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body">
                        <h6 class="text-uppercase small">Total Informasi</h6>
                        <h2 class="fw-bold mb-0"><?= count($informasi_perusahaan); ?> Artikel</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body">
                        <h6 class="text-uppercase small">Status Server</h6>
                        <h2 class="fw-bold mb-0">Aktif (Laragon)</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-info text-white">
                    <div class="card-body">
                        <h6 class="text-uppercase small">Database</h6>
                        <h2 class="fw-bold mb-0">Terhubung MySQL</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Manajemen Data CRUD -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Kelola Informasi Perusahaan</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Informasi
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Judul Informasi</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($informasi_perusahaan)) : ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data informasi. Silakan tambah data baru.</td>
                                </tr>
                            <?php else : ?>
                                <?php $no = 1; foreach ($informasi_perusahaan as $info) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td class="fw-semibold"><?= htmlspecialchars($info['judul']); ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($info['kategori']); ?></span></td>
                                        <td><?= htmlspecialchars($info['tanggal']); ?></td>
                                        <td class="text-center">
                                            <!-- Tombol Lihat Detail -->
                                            <a href="detail.php?id=<?= $info['id']; ?>" class="btn btn-sm btn-info text-white" title="Lihat">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- Tombol Edit (Memicu Modal Edit) -->
                                            <button type="button" class="btn btn-sm btn-warning btn-edit" title="Edit"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEdit"
                                                data-id="<?= $info['id']; ?>"
                                                data-judul="<?= htmlspecialchars($info['judul']); ?>"
                                                data-kategori="<?= htmlspecialchars($info['kategori']); ?>"
                                                data-tanggal="<?= htmlspecialchars($info['tanggal']); ?>"
                                                data-gambar="<?= htmlspecialchars($info['gambar']); ?>"
                                                data-ringkasan="<?= htmlspecialchars($info['ringkasan']); ?>"
                                                data-konten="<?= htmlspecialchars($info['konten']); ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <!-- Form Hapus -->
                                            <form method="POST" action="dashboard.php" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus informasi ini?')">
                                                <input type="hidden" name="aksi" value="hapus">
                                                <input type="hidden" name="id" value="<?= $info['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- MODAL TAMBAH INFORMASI -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="dashboard.php">
                <input type="hidden" name="aksi" value="tambah">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Tambah Informasi Perusahaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Informasi</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="kategori" class="form-control" placeholder="Contoh: Teknologi, Karir, Penghargaan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="text" name="tanggal" class="form-control" value="<?= date('d F Y'); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL Gambar</label>
                        <input type="url" name="gambar" class="form-control" placeholder="https://..." value="https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ringkasan Singkat</label>
                        <textarea name="ringkasan" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konten Lengkap</label>
                        <textarea name="konten" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT INFORMASI -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="dashboard.php">
                <input type="hidden" name="aksi" value="edit">
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Informasi Perusahaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Informasi</label>
                        <input type="text" name="judul" id="edit-judul" class="form-control" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <input type="text" name="kategori" id="edit-kategori" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal</label>
                            <input type="text" name="tanggal" id="edit-tanggal" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL Gambar</label>
                        <input type="url" name="gambar" id="edit-gambar" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ringkasan Singkat</label>
                        <textarea name="ringkasan" id="edit-ringkasan" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konten Lengkap</label>
                        <textarea name="konten" id="edit-konten" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-semibold">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script untuk mengisi data ke Modal Edit -->
<script>
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('edit-id').value = this.dataset.id;
            document.getElementById('edit-judul').value = this.dataset.judul;
            document.getElementById('edit-kategori').value = this.dataset.kategori;
            document.getElementById('edit-tanggal').value = this.dataset.tanggal;
            document.getElementById('edit-gambar').value = this.dataset.gambar;
            document.getElementById('edit-ringkasan').value = this.dataset.ringkasan;
            document.getElementById('edit-konten').value = this.dataset.konten;
        });
    });
</script>

<?php include 'includes/footer.php'; ?>