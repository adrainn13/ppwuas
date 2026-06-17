<?php
require_once '../config/koneksi.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ../login.php');
    exit;
}

$is_admin_page = true;
$page_title = 'Kelola Kemasan';
$success = '';
$error = '';
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id_kemasan = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_kemasan = clean_input($_POST['nama_kemasan']);
    $volume_kemasan = clean_input($_POST['volume_kemasan']);
    
    if (empty($nama_kemasan) || empty($volume_kemasan)) {
        $error = 'Semua field wajib diisi';
    } else {
        if ($action == 'add') {
            $sql = "INSERT INTO kemasan (nama_kemasan, volume_kemasan) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $nama_kemasan, $volume_kemasan);
            
            if ($stmt->execute()) {
                $success = 'Kemasan berhasil ditambahkan!';
                $action = 'list';
            } else {
                $error = 'Gagal menambahkan kemasan';
            }
        } elseif ($action == 'edit' && $id_kemasan > 0) {
            $sql = "UPDATE kemasan SET nama_kemasan=?, volume_kemasan=? WHERE id_kemasan=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssi', $nama_kemasan, $volume_kemasan, $id_kemasan);
            
            if ($stmt->execute()) {
                $success = 'Kemasan berhasil diupdate!';
                $action = 'list';
            } else {
                $error = 'Gagal mengupdate kemasan';
            }
        }
    }
}

$kemasan_data = null;
if ($action == 'edit' && $id_kemasan > 0) {
    $stmt = $conn->prepare("SELECT * FROM kemasan WHERE id_kemasan = ?");
    $stmt->bind_param('i', $id_kemasan);
    $stmt->execute();
    $kemasan_data = $stmt->get_result()->fetch_assoc();
}

include '../includes/header.php';
?>

<div class="container my-5">
    <?php if ($action == 'list'): ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-weight: 700;"><i class="bi bi-box"></i> Kelola Kemasan</h2>
            <a href="kemasan.php?action=add" class="btn-tambah">
                <i class="bi bi-plus-circle"></i> Tambah Kemasan
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
                        <th>Nama Kemasan</th>
                        <th>Volume</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $conn->query("SELECT * FROM kemasan ORDER BY id_kemasan");
                    while ($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $row['id_kemasan']; ?></td>
                        <td><?php echo $row['nama_kemasan']; ?></td>
                        <td><?php echo $row['volume_kemasan']; ?></td>
                        <td>
                            <a href="kemasan.php?action=edit&id=<?php echo $row['id_kemasan']; ?>" class="btn btn-edit btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
    <?php else: ?>
        <div class="mb-4">
            <a href="kemasan.php" class="btn-secondary-custom mb-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h2 style="font-weight: 700;">
                <?php echo $action == 'add' ? 'Tambah' : 'Edit'; ?> Kemasan
            </h2>
        </div>
        
        <?php if ($error): ?>
        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle"></i> <?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="detail-container">
            <form method="POST" action="" class="needs-validation" novalidate>
                <div class="form-group">
                    <label class="form-label">Nama Kemasan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_kemasan" class="form-control" 
                           value="<?php echo $kemasan_data['nama_kemasan'] ?? ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Volume/Ukuran <span class="text-danger">*</span></label>
                    <input type="text" name="volume_kemasan" class="form-control" 
                           value="<?php echo $kemasan_data['volume_kemasan'] ?? ''; ?>" 
                           placeholder="Contoh: 1 Liter, 500 ml, 1 Kg" required>
                </div>
                
                <hr class="my-4">
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn-primary-custom">
                        <i class="bi bi-save"></i> <?php echo $action == 'add' ? 'Tambah' : 'Update'; ?>
                    </button>
                    <a href="kemasan.php" class="btn-secondary-custom">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
