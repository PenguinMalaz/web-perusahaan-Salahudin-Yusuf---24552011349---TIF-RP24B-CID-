<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_perusahaan";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Koneksi database gagal: " . $conn->connect_error
    ]);
    exit;
}
?>