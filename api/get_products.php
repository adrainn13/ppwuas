<?php
require_once '../config/koneksi.php';

header('Content-Type: application/json');

// Get parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
$search = isset($_GET['search']) ? clean_input($_GET['search']) : '';
$category = isset($_GET['category']) ? clean_input($_GET['category']) : '';

$offset = ($page - 1) * $per_page;

// Build query dengan JOIN untuk mendapatkan data relasi
$where = ["1=1"];
$params = [];
$types = '';

if (!empty($search)) {
    $where[] = "(p.nama_produk LIKE ? OR p.deskripsi LIKE ? OR p.kegunaan LIKE ?)";
    $search_param = "%{$search}%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'sss';
}

if (!empty($category)) {
    $where[] = "p.id_kategori = ?";
    $params[] = $category;
    $types .= 'i';
}

$where_clause = implode(" AND ", $where);

// Query dengan JOIN (Complex Query #1)
$sql = "SELECT 
            p.id_produk,
            p.nama_produk,
            p.harga,
            p.stok,
            p.kegunaan,
            p.deskripsi,
            p.foto_produk,
            k.nama_kategori,
            km.nama_kemasan,
            km.volume_kemasan,
            kt.nama_kondisi,
            CONCAT('uploads/produk/', p.foto_produk) as foto_path
        FROM produk p
        INNER JOIN kategori k ON p.id_kategori = k.id_kategori
        INNER JOIN kemasan km ON p.id_kemasan = km.id_kemasan
        INNER JOIN kondisi_tanaman kt ON p.id_kondisi = kt.id_kondisi
        WHERE {$where_clause}
        ORDER BY p.id_produk DESC
        LIMIT ? OFFSET ?";

$params[] = $per_page;
$params[] = $offset;
$types .= 'ii';

// Execute query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Get total count
$count_where = $where;
array_pop($count_where); // Remove LIMIT params
$count_where_clause = implode(" AND ", $count_where);

$count_sql = "SELECT COUNT(*) as total 
              FROM produk p 
              WHERE " . implode(" AND ", $where);

$count_stmt = $conn->prepare($count_sql);
if (!empty($types) && strlen($types) > 2) {
    $count_types = substr($types, 0, -2); // Remove last 2 chars (LIMIT params)
    $count_params = array_slice($params, 0, -2);
    if (!empty($count_params)) {
        $count_stmt->bind_param($count_types, ...$count_params);
    }
}
$count_stmt->execute();
$total = $count_stmt->get_result()->fetch_assoc()['total'];

// Response
echo json_encode([
    'success' => true,
    'products' => $products,
    'total' => (int)$total,
    'per_page' => $per_page,
    'current_page' => $page,
    'total_pages' => ceil($total / $per_page)
]);

$conn->close();
?>
