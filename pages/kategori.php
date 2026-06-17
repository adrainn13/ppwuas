<?php
require_once '../config/koneksi.php';

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ../login.php');
    exit;
}

$is_admin_page = true;
$page_title = 'Kelola Kategori';
$success = '';
$error = '';
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id_kategori = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Process Form Submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kategori = clean_input($_POST['nama_kategori']);
    $deskripsi = clean_input($_POST['deskripsi']);
    
    if (empty($nama_kategori)) {
        $error = 'Nama kategori wajib diisi';
    } else {
        if ($action == 'add') {
            $sql = "INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $nama_kategori, $deskripsi);
            
            if ($stmt->execute()) {
                $success = 'Kategori berhasil ditambahkan!';
                $action = 'list';
            } else {
                $error = 'Gagal menambahkan kategori: ' . $conn->error;
            }
        } elseif ($action == 'edit' && $id_kategori > 0) {
            $sql = "UPDATE kategori SET nama_kategori=?, deskripsi=? WHERE id_kategori=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssi', $nama_kategori, $deskripsi, $id_kategori);
            
            if ($stmt->execute()) {
                $success = 'Kategori berhasil diupdate!';
                $action = 'list';
            } else {
                $error = 'Gagal mengupdate kategori: ' . $conn->error;
            }
        }
    }
}

// Get category data untuk edit
$kategori_data = null;
if ($action == 'edit' && $id_kategori > 0) {
    $stmt = $conn->prepare("SELECT * FROM kategori WHERE id_kategori = ?");
    $stmt->bind_param('i', $id_kategori);
    $stmt->execute();
    $result = $stmt->get_result();
    $kategori_data = $result->fetch_assoc();
    
    if (!$kategori_data) {
        header('Location: kategori.php');
        exit;
    }
}

include '../includes/header.php';
?>

<div class="container my-5">
    <?php if ($action == 'list'): ?>
        <!-- List View -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 style="font-weight: 700; color: var(--text-dark);">
                    <i class="bi bi-tags"></i> Kelola Kategori
                </h2>
                <p class="text-muted">Manajemen kategori produk</p>
            </div>
            <a href="kategori.php?action=add" class="btn-tambah">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
            </a>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <div class="row">
            <?php
            // Menggunakan VIEW untuk statistik kategori
            $result = $conn->query("SELECT * FROM view_produk_kategori ORDER BY jumlah_produk DESC");
            while ($row = $result->fetch_assoc()):
            ?>
            <div class="col-md-6 mb-4">
                <div class="detail-container">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 style="font-weight: 700; color: var(--primary-green);">
                                <i class="bi bi-tag"></i> <?php echo $row['nama_kategori']; ?>
                            </h4>
                        </div>
                        <div>
                            <a href="kategori.php?action=edit&id=<?php echo $row['id_kategori']; ?>" class="btn btn-edit btn-sm me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-4">
                            <div class="text-center p-3" style="background: var(--secondary-green); border-radius: 8px;">
                                <h3 style="color: var(--primary-green); margin: 0;"><?php echo $row['jumlah_produk']; ?></h3>
                                <small class="text-muted">Produk</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3" style="background: var(--secondary-green); border-radius: 8px;">
                                <h3 style="color: var(--primary-green); margin: 0;"><?php echo number_format($row['total_stok']); ?></h3>
                                <small class="text-muted">Total Stok</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-3" style="background: var(--secondary-green); border-radius: 8px;">
                                <h3 style="color: var(--primary-green); margin: 0; font-size: 1rem;">
                                    <?php echo $row['jumlah_produk'] > 0 ? format_rupiah($row['rata_rata_harga']) : 'Rp 0'; ?>
                                </h3>
                                <small class="text-muted">Rata-rata</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        
    <?php else: ?>
        <!-- Form View (Add/Edit) -->
        <div class="mb-4">
            <a href="kategori.php" class="btn-secondary-custom mb-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h2 style="font-weight: 700; color: var(--text-dark);">
                <i class="bi bi-<?php echo $action == 'add' ? 'plus-circle' : 'pencil'; ?>"></i>
                <?php echo $action == 'add' ? 'Tambah' : 'Edit'; ?> Kategori
            </h2>
        </div>
        
        <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <div class="detail-container">
            <form method="POST" action="" class="needs-validation" novalidate>
                <div class="form-group">
                    <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" 
                           name="nama_kategori" 
                           class="form-control" 
                           value="<?php echo $kategori_data['nama_kategori'] ?? ''; ?>" 
                           required>
                    <div class="invalid-feedback">Nama kategori wajib diisi</div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" 
                              class="form-control" 
                              rows="3"><?php echo $kategori_data['deskripsi'] ?? ''; ?></textarea>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-save"></i> <?php echo $action == 'add' ? 'Tambah' : 'Update'; ?> Kategori
                    </button>
                    <a href="kategori.php" class="btn-secondary-custom">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
