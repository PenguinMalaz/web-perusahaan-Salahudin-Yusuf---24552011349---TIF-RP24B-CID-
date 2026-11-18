<?php
session_start();
require_once 'api/koneksi.php';

$error_msg = "";
$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($nama === '' || $email === '' || $username === '' || $password === '') {
        $error_msg = "Semua kolom wajib diisi!";
    } elseif ($username === 'admin') {
        $error_msg = "Username 'admin' sudah digunakan sistem. Gunakan username lain.";
    } else {
        // Cek apakah username sudah terdaftar
        $cek = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $cek->bind_param("s", $username);
        $cek->execute();
        $cek->store_result();

        if ($cek->num_rows > 0) {
            $error_msg = "Username sudah terdaftar! Silakan pilih username lain.";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (nama, email, username, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama, $email, $username, $password);

            if ($stmt->execute()) {
                echo "<script>
                    alert('Registrasi berhasil! Silakan login menggunakan akun Anda.');
                    window.location.href = 'login.php';
                </script>";
                exit;
            } else {
                $error_msg = "Terjadi kesalahan saat menyimpan data.";
            }
        }
    }
}

include 'includes/header.php';
?>

<main class="d-flex align-items-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold">Registrasi Akun Baru</h3>
                            <p class="text-muted small">Lengkapi data diri Anda untuk membuat akun</p>
                        </div>

                        <?php if ($error_msg !== "") : ?>
                            <div class="alert alert-danger small"><?= htmlspecialchars($error_msg); ?></div>
                        <?php endif; ?>

                        <form id="registerForm" method="POST" action="register.php" onsubmit="return prosesRegistrasi(event)">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" placeholder="Contoh: Perusahaan 1" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Perusahaan</label>
                                <input type="email" name="email" class="form-control" placeholder="nama@perusahaan.com" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Buat username" required>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" id="regPass" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" id="regConfirmPass" class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Daftar Sekarang</button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="small text-muted mb-0">Sudah memiliki akun? <a href="login.php" class="text-decoration-none">Login di sini</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    function prosesRegistrasi(event) {
        event.preventDefault();
        const pass = document.getElementById('regPass').value;
        const confirm = document.getElementById('regConfirmPass').value;

        if (pass !== confirm) {
            alert("Konfirmasi password tidak cocok!");
        } else {
            document.getElementById('registerForm').submit();
        }
    }
</script>

<?php include 'includes/footer.php'; ?>