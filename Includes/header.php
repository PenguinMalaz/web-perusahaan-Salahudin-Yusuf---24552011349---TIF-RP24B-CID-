<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Informasi Perusahaan</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        main {
            flex: 1;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: 0.3s ease;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="bi bi-building me-2"></i>TechCorp Indonesia
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Beranda</a>
                </li>

                <?php if ($isLoggedIn) : ?>
                    <?php if (($_SESSION['role'] ?? '') === 'admin') : ?>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-light btn-sm px-3 fw-semibold text-primary" href="dashboard.php">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard Admin
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-outline-light btn-sm px-3" href="menu_utama.php">
                            <i class="bi bi-grid me-1"></i> Menu Utama
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-danger btn-sm px-3" href="logout.php" onclick="return confirm('Yakin ingin keluar?')">
                            Logout
                        </a>
                    </li>
                <?php else : ?>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-outline-light btn-sm px-3" href="login.php">Login</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-warning btn-sm px-3 fw-semibold" href="register.php">Registrasi</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>