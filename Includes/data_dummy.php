<?php
require_once __DIR__ . '/../api/koneksi.php';

$informasi_perusahaan = [];
$query = $conn->query("SELECT * FROM informasi ORDER BY id ASC");

if ($query) {
    while ($row = $query->fetch_assoc()) {
        $informasi_perusahaan[] = $row;
    }
}
?>