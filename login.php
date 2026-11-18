<?php
session_start();
require_once 'api/koneksi.php';

// Jika sudah login, arahkan sesuai role
if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    if (($_SESSION['role'] ?? '') === 'admin') {
        header("Location: dashboard.php");
    } else {
        header("Location: menu_utama.php");
    }
    exit;
}

// Menyimpan sesi setelah lolos validasi JavaScript
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? 'user';
    $_SESSION['is_logged_in'] = true;
    $_SESSION['username']     = htmlspecialchars($_POST['username'] ?? 'User');
    $_SESSION['nama']         = htmlspecialchars($_POST['nama_user'] ?? 'Pengguna');
    $_SESSION['role']         = $role;

    if ($role === 'admin') {
        header("Location: dashboard.php");
    } else {
        header("Location: menu_utama.php");
    }
    exit;
}

// Ambil daftar user terdaftar untuk divalidasi oleh fungsi JS
$daftar_users = [];
$query = $conn->query("SELECT nama, username, password FROM users");
if ($query) {
    while ($row = $query->fetch_assoc()) {
        $daftar_users[] = $row;
    }
}

include 'includes/header.php';
?>

<main class="d-flex align-items-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold mt-2">Login Sistem</h3>
                            <p class="text-muted small">Masuk menggunakan akun terdaftar atau akun admin</p>
                        </div>

                        <!-- Alert Error JS -->
                        <div id="alertError" class="alert alert-danger d-none small" role="alert"></div>

                        <form id="loginForm" method="POST" action="login.php" onsubmit="return validasiLogin(event)">
                            <input type="hidden" id="roleInput" name="role" value="user">
                            <input type="hidden" id="namaUserInput" name="nama_user" value="">

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Masuk</button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="small text-muted mb-0">Belum punya akun? <a href="register.php" class="text-decoration-none">Daftar di sini</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Fungsi JavaScript Validasi Login menggunakan Percabangan If -->
<script>
    // Data akun terdaftar dari database MySQL
    const registeredUsers = <?= json_encode($daftar_users); ?>;

    function validasiLogin(event) {
        event.preventDefault();

        const user = document.getElementById('username').value.trim();
        const pass = document.getElementById('password').value.trim();
        const alertBox = document.getElementById('alertError');

        // Akun khusus Admin
        const adminUser = "admin";
        const adminPass = "12345";

        // Cari apakah cocok dengan akun hasil registrasi
        const userTerdaftar = registeredUsers.find(
            item => item.username === user && item.password === pass
        );

        if (user === "" || pass === "") {
            alertBox.textContent = "Username dan Password tidak boleh kosong!";
            alertBox.classList.remove("d-none");
        } else if (user === adminUser && pass === adminPass) {
            alertBox.classList.add("d-none");
            document.getElementById('roleInput').value = "admin";
            document.getElementById('namaUserInput').value = "Administrator";
            alert("Login Berhasil sebagai Admin! Mengalihkan ke Dashboard Admin...");
            document.getElementById('loginForm').submit();
        } else if (userTerdaftar) {
            alertBox.classList.add("d-none");
            document.getElementById('roleInput').value = "user";
            document.getElementById('namaUserInput').value = userTerdaftar.nama;
            alert("Login Berhasil! Mengalihkan ke Halaman Menu Utama...");
            document.getElementById('loginForm').submit();
        } else {
            alertBox.textContent = "Username atau Password salah! Silakan cek kembali atau daftar akun baru.";
            alertBox.classList.remove("d-none");
        }
    }
</script>

<?php include 'includes/footer.php'; ?>