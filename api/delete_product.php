<?php
// Enable error logging for debugging
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display, just log
ini_set('log_errors', 1);

require_once '../config/koneksi.php';

header('Content-Type: application/json');

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized - Please login']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$id_produk = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id_produk <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID produk tidak valid: ' . $id_produk]);
    exit;
}

try {
    // Get foto untuk dihapus
    $stmt = $conn->prepare("SELECT foto_produk FROM produk WHERE id_produk = ?");
    
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param('i', $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk = $result->fetch_assoc();
    
    if (!$produk) {
        echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan (ID: ' . $id_produk . ')']);
        exit;
    }
    
    // Delete produk (akan cascade delete ke tabel target karena ON DELETE CASCADE)
    $stmt = $conn->prepare("DELETE FROM produk WHERE id_produk = ?");
    
    if (!$stmt) {
        throw new Exception('Prepare delete failed: ' . $conn->error);
    }
    
    $stmt->bind_param('i', $id_produk);
    
    if ($stmt->execute()) {
        // Check if actually deleted
        if ($stmt->affected_rows > 0) {
            // Hapus file foto jika ada
            if ($produk && !empty($produk['foto_produk'])) {
                $foto_path = '../uploads/produk/' . $produk['foto_produk'];
                if (file_exists($foto_path)) {
                    @unlink($foto_path); // @ to suppress warnings
                }
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Produk berhasil dihapus',
                'id' => $id_produk
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Produk tidak ditemukan atau sudah dihapus (ID: ' . $id_produk . ')'
            ]);
        }
    } else {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Error: ' . $e->getMessage(),
        'id' => $id_produk
    ]);
}

$conn->close();
?>
