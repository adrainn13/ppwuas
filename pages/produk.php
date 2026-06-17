<?php
require_once '../config/koneksi.php';

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ../login.php');
    exit;
}

$is_admin_page = true;
$page_title = 'Kelola Produk';
$success = '';
$error = '';
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id_produk = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get data untuk form
$kategori_list = $conn->query("SELECT * FROM kategori ORDER BY nama_kategori");
$kemasan_list = $conn->query("SELECT * FROM kemasan ORDER BY nama_kemasan");
$kondisi_list = $conn->query("SELECT * FROM kondisi_tanaman ORDER BY nama_kondisi");

// Process Form Submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = clean_input($_POST['nama_produk']);
    $id_kategori = (int)$_POST['id_kategori'];
    $id_kemasan = (int)$_POST['id_kemasan'];
    $id_kondisi = (int)$_POST['id_kondisi'];
    $harga = (float)$_POST['harga'];
    $stok = (int)$_POST['stok'];
    $kegunaan = clean_input($_POST['kegunaan']);
    $deskripsi = clean_input($_POST['deskripsi']);
    
    // Validation
    if (empty($nama_produk) || $id_kategori <= 0 || $harga <= 0) {
        $error = 'Mohon lengkapi semua field yang wajib diisi';
    } else {
        // Handle image upload
        $foto_produk = '';
        if (isset($_FILES['foto_produk']) && $_FILES['foto_produk']['error'] == UPLOAD_ERR_OK) {
            $upload_result = upload_image($_FILES['foto_produk'], 'produk');
            if ($upload_result['success']) {
                $foto_produk = $upload_result['filename'];
            } else {
                $error = $upload_result['message'];
            }
        } elseif (isset($_FILES['foto_produk']) && $_FILES['foto_produk']['error'] != UPLOAD_ERR_NO_FILE) {
            // Ada error upload selain "no file"
            $upload_errors = [
                UPLOAD_ERR_INI_SIZE => 'File terlalu besar (php.ini)',
                UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (form)',
                UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
                UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ada',
                UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
                UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh extension'
            ];
            $error = $upload_errors[$_FILES['foto_produk']['error']] ?? 'Error upload file';
        }
        
        if (empty($error)) {
            if ($action == 'add') {
                // INSERT dengan prepared statement
                $sql = "INSERT INTO produk (id_kategori, id_kemasan, id_kondisi, nama_produk, harga, stok, kegunaan, deskripsi, foto_produk) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                
                if ($stmt === false) {
                    $error = 'Prepare statement gagal: ' . $conn->error;
                } else {
                    $stmt->bind_param('iiisdisss', $id_kategori, $id_kemasan, $id_kondisi, $nama_produk, $harga, $stok, $kegunaan, $deskripsi, $foto_produk);
                    
                    if ($stmt->execute()) {
                        $success = 'Produk berhasil ditambahkan!';
                        $action = 'list';
                    } else {
                        $error = 'Gagal menambahkan produk: ' . $stmt->error;
                    }
                }
            } elseif ($action == 'edit' && $id_produk > 0) {
                // UPDATE dengan prepared statement
                if (!empty($foto_produk)) {
                    $sql = "UPDATE produk SET 
                            id_kategori=?, id_kemasan=?, id_kondisi=?, nama_produk=?, 
                            harga=?, stok=?, kegunaan=?, deskripsi=?, foto_produk=?
                            WHERE id_produk=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('iiisdisssi', $id_kategori, $id_kemasan, $id_kondisi, $nama_produk, $harga, $stok, $kegunaan, $deskripsi, $foto_produk, $id_produk);
                } else {
                    $sql = "UPDATE produk SET 
                            id_kategori=?, id_kemasan=?, id_kondisi=?, nama_produk=?, 
                            harga=?, stok=?, kegunaan=?, deskripsi=?
                            WHERE id_produk=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('iiisdissi', $id_kategori, $id_kemasan, $id_kondisi, $nama_produk, $harga, $stok, $kegunaan, $deskripsi, $id_produk);
                }
                
                if ($stmt->execute()) {
                    $success = 'Produk berhasil diupdate!';
                    $action = 'list';
                } else {
                    $error = 'Gagal mengupdate produk: ' . $conn->error;
                }
            }
        }
    }
}

// Get product data untuk edit
$produk_data = null;
if ($action == 'edit' && $id_produk > 0) {
    $stmt = $conn->prepare("SELECT * FROM produk WHERE id_produk = ?");
    $stmt->bind_param('i', $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    $produk_data = $result->fetch_assoc();
    
    if (!$produk_data) {
        header('Location: produk.php');
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
                    <i class="bi bi-box-seam"></i> Kelola Produk
                </h2>
                <p class="text-muted">Manajemen data produk katalog</p>
            </div>
            <a href="produk.php?action=add" class="btn-tambah">
                <i class="bi bi-plus-circle"></i> Tambah Produk
            </a>
        </div>
        
        <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>
        
        <div class="admin-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT p.*, k.nama_kategori 
                             FROM produk p 
                             JOIN kategori k ON p.id_kategori = k.id_kategori 
                             ORDER BY p.id_produk DESC";
                    $result = $conn->query($query);
                    
                    if (!$result) {
                        echo "<tr><td colspan='7' class='text-center text-danger'>Error: " . $conn->error . "</td></tr>";
                    } elseif ($result->num_rows == 0) {
                        echo "<tr><td colspan='7' class='text-center'>Belum ada produk. Silakan tambah produk baru.</td></tr>";
                    } else {
                        while ($row = $result->fetch_assoc()):
                            // Handle foto produk
                            $foto_src = '../assets/images/placeholder.svg';
                            if (!empty($row['foto_produk'])) {
                                $foto_path = '../uploads/produk/' . $row['foto_produk'];
                                if (file_exists($foto_path)) {
                                    $foto_src = $foto_path;
                                }
                            }
                    ?>
                    <tr>
                        <td><?php echo $row['id_produk']; ?></td>
                        <td>
                            <img src="<?php echo $foto_src; ?>" 
                                 class="table-image" 
                                 alt="<?php echo htmlspecialchars($row['nama_produk']); ?>"
                                 onerror="this.src='../assets/images/placeholder.svg'">
                        </td>
                        <td><strong><?php echo htmlspecialchars($row['nama_produk']); ?></strong></td>
                        <td><span class="badge" style="background: var(--secondary-green); color: var(--primary-green-dark);"><?php echo htmlspecialchars($row['nama_kategori']); ?></span></td>
                        <td><?php echo format_rupiah($row['harga']); ?></td>
                        <td>
                            <span class="<?php echo $row['stok'] > 20 ? 'text-success' : ($row['stok'] > 0 ? 'text-warning' : 'text-danger'); ?>">
                                <?php echo $row['stok']; ?>
                            </span>
                        </td>
                        <td>
                            <a href="produk.php?action=edit&id=<?php echo $row['id_produk']; ?>" class="btn btn-edit btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button class="btn btn-delete btn-sm" 
                                    data-id="<?php echo $row['id_produk']; ?>" 
                                    data-name="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php 
                        endwhile;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
    <?php else: ?>
        <!-- Form View (Add/Edit) -->
        <div class="mb-4">
            <a href="produk.php" class="btn-secondary-custom mb-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h2 style="font-weight: 700; color: var(--text-dark);">
                <i class="bi bi-<?php echo $action == 'add' ? 'plus-circle' : 'pencil'; ?>"></i>
                <?php echo $action == 'add' ? 'Tambah' : 'Edit'; ?> Produk
            </h2>
        </div>
        
        <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <div class="detail-container">
            <form method="POST" action="" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="nama_produk" 
                                   class="form-control" 
                                   value="<?php echo $produk_data['nama_produk'] ?? ''; ?>" 
                                   required>
                            <div class="invalid-feedback">Nama produk wajib diisi</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="id_kategori" class="form-control" required>
                                <option value="">Pilih Kategori</option>
                                <?php
                                $kategori_list->data_seek(0);
                                while ($kat = $kategori_list->fetch_assoc()):
                                ?>
                                <option value="<?php echo $kat['id_kategori']; ?>" 
                                        <?php echo (isset($produk_data) && $produk_data['id_kategori'] == $kat['id_kategori']) ? 'selected' : ''; ?>>
                                    <?php echo $kat['nama_kategori']; ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                            <div class="invalid-feedback">Kategori wajib dipilih</div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Kemasan</label>
                            <select name="id_kemasan" class="form-control">
                                <option value="1">Pilih Kemasan</option>
                                <?php
                                $kemasan_list->data_seek(0);
                                while ($kem = $kemasan_list->fetch_assoc()):
                                ?>
                                <option value="<?php echo $kem['id_kemasan']; ?>"
                                        <?php echo (isset($produk_data) && $produk_data['id_kemasan'] == $kem['id_kemasan']) ? 'selected' : ''; ?>>
                                    <?php echo $kem['nama_kemasan']; ?> - <?php echo $kem['volume_kemasan']; ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Harga (Rp) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   name="harga" 
                                   class="form-control" 
                                   value="<?php echo $produk_data['harga'] ?? ''; ?>" 
                                   min="0" 
                                   step="0.01" 
                                   required>
                            <div class="invalid-feedback">Harga wajib diisi</div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Stok</label>
                            <input type="number" 
                                   name="stok" 
                                   class="form-control" 
                                   value="<?php echo $produk_data['stok'] ?? '0'; ?>" 
                                   min="0">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kondisi Tanaman</label>
                    <select name="id_kondisi" class="form-control">
                        <option value="1">Pilih Kondisi</option>
                        <?php
                        $kondisi_list->data_seek(0);
                        while ($kon = $kondisi_list->fetch_assoc()):
                        ?>
                        <option value="<?php echo $kon['id_kondisi']; ?>"
                                <?php echo (isset($produk_data) && $produk_data['id_kondisi'] == $kon['id_kondisi']) ? 'selected' : ''; ?>>
                            <?php echo $kon['nama_kondisi']; ?>
                        </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kegunaan</label>
                    <textarea name="kegunaan" 
                              class="form-control" 
                              rows="3"><?php echo $produk_data['kegunaan'] ?? ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Deskripsi (Format: Brand | Bahan Aktif: xxx | Deskripsi)</label>
                    <textarea name="deskripsi" 
                              class="form-control" 
                              rows="3"><?php echo $produk_data['deskripsi'] ?? ''; ?></textarea>
                    <small class="form-text">Contoh: Syngenta | Bahan Aktif: Glyphosate 480 g/L | Herbisida sistemik...</small>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Foto Produk</label>
                    <input type="file" 
                           name="foto_produk" 
                           class="form-control" 
                           accept="image/*"
                           onchange="previewImage(this)">
                    <small class="form-text">Format: JPG, PNG, GIF (Max 5MB)</small>
                    <?php if (isset($produk_data) && $produk_data['foto_produk']): ?>
                    <div class="mt-2">
                        <img id="imagePreview" 
                             src="../uploads/produk/<?php echo $produk_data['foto_produk']; ?>" 
                             style="max-width: 200px; border-radius: 8px; box-shadow: var(--shadow);">
                    </div>
                    <?php else: ?>
                    <div class="mt-2">
                        <img id="imagePreview" 
                             src="" 
                             style="max-width: 200px; border-radius: 8px; box-shadow: var(--shadow); display: none;">
                    </div>
                    <?php endif; ?>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-save"></i> <?php echo $action == 'add' ? 'Tambah' : 'Update'; ?> Produk
                    </button>
                    <a href="produk.php" class="btn-secondary-custom">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
