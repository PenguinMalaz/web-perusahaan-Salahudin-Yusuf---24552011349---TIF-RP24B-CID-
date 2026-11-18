<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'koneksi.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    // 1. READ (GET)
    case 'GET':
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $stmt = $conn->prepare("SELECT * FROM informasi WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();

            if ($result) {
                echo json_encode(["status" => "success", "data" => $result]);
            } else {
                http_response_code(404);
                echo json_encode(["status" => "error", "message" => "Data tidak ditemukan"]);
            }
        } else {
            $query = $conn->query("SELECT * FROM informasi ORDER BY id ASC");
            $data = [];
            while ($row = $query->fetch_assoc()) {
                $data[] = $row;
            }
            echo json_encode(["status" => "success", "total" => count($data), "data" => $data]);
        }
        break;

    // 2. CREATE (POST)
    case 'POST':
        if (empty($input['judul']) || empty($input['kategori'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Field judul dan kategori wajib diisi"]);
            break;
        }

        $judul     = $input['judul'];
        $kategori  = $input['kategori'];
        $tanggal   = $input['tanggal'] ?? date('d F Y');
        $gambar    = $input['gambar'] ?? 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80';
        $ringkasan = $input['ringkasan'] ?? '';
        $konten    = $input['konten'] ?? '';

        $stmt = $conn->prepare("INSERT INTO informasi (judul, kategori, tanggal, gambar, ringkasan, konten) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $judul, $kategori, $tanggal, $gambar, $ringkasan, $konten);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode([
                "status" => "success",
                "message" => "Informasi baru berhasil ditambahkan",
                "id_baru" => $conn->insert_id
            ]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Gagal menyimpan data"]);
        }
        break;

    // 3. UPDATE (PUT)
    case 'PUT':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($input['id']) ? (int)$input['id'] : 0);

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "ID informasi wajib disertakan di URL (?id=...)"]);
            break;
        }

        $judul     = $input['judul'] ?? '';
        $kategori  = $input['kategori'] ?? '';
        $tanggal   = $input['tanggal'] ?? '';
        $gambar    = $input['gambar'] ?? '';
        $ringkasan = $input['ringkasan'] ?? '';
        $konten    = $input['konten'] ?? '';

        $stmt = $conn->prepare("UPDATE informasi SET judul=?, kategori=?, tanggal=?, gambar=?, ringkasan=?, konten=? WHERE id=?");
        $stmt->bind_param("ssssssi", $judul, $kategori, $tanggal, $gambar, $ringkasan, $konten, $id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Data ID $id berhasil diperbarui"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui data"]);
        }
        break;

    // 4. DELETE (DELETE)
    case 'DELETE':
        $id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($input['id']) ? (int)$input['id'] : 0);

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "ID informasi wajib disertakan di URL (?id=...)"]);
            break;
        }

        $stmt = $conn->prepare("DELETE FROM informasi WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo json_encode(["status" => "success", "message" => "Data ID $id berhasil dihapus"]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Data tidak ditemukan atau sudah terhapus"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Metode HTTP tidak diizinkan"]);
        break;
}
?>