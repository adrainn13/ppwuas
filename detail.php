<?php
require_once 'config/koneksi.php';

// Get product ID
$id_produk = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_produk <= 0) {
    header('Location: index.php');
    exit;
}

// Complex Query #2: JOIN multiple tables dengan subquery untuk target
$sql = "SELECT 
            p.*,
            k.nama_kategori,
            k.deskripsi as kategori_desc,
            km.nama_kemasan,
            km.volume_kemasan,
            kt.nama_kondisi,
            kt.keterangan as kondisi_keterangan,
            CONCAT('uploads/produk/', p.foto_produk) as foto_path,
            (SELECT GROUP_CONCAT(CONCAT(t.nama_target, ' (', t.jenis_target, ')') SEPARATOR ', ') 
             FROM target t 
             WHERE t.id_produk = p.id_produk) as target_hama
        FROM produk p
        INNER JOIN kategori k ON p.id_kategori = k.id_kategori
        INNER JOIN kemasan km ON p.id_kemasan = km.id_kemasan
        INNER JOIN kondisi_tanaman kt ON p.id_kondisi = kt.id_kondisi
        WHERE p.id_produk = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_produk);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php');
    exit;
}

$produk = $result->fetch_assoc();
$page_title = $produk['nama_produk'];

// Parse deskripsi untuk mendapatkan brand dan bahan aktif
$desc_parts = explode('|', $produk['deskripsi']);
$brand = trim($desc_parts[0] ?? 'Brand');
$bahan_aktif = '';
$deskripsi_lengkap = '';

if (count($desc_parts) >= 2) {
    $bahan_aktif_part = trim($desc_parts[1] ?? '');
    if (strpos($bahan_aktif_part, 'Bahan Aktif:') !== false) {
        $bahan_aktif = str_replace('Bahan Aktif:', '', $bahan_aktif_part);
        $bahan_aktif = trim($bahan_aktif);
    }
}

if (count($desc_parts) >= 3) {
    $deskripsi_lengkap = trim($desc_parts[2] ?? '');
}

include 'includes/header.php';
?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" style="color: var(--primary-green);">Beranda</a></li>
            <li class="breadcrumb-item active"><?php echo $produk['nama_produk']; ?></li>
        </ol>
    </nav>
    
    <div class="detail-container fade-in">
        <div class="row">
            <!-- Image Column -->
            <div class="col-lg-5 mb-4">
                <img src="<?php echo file_exists($produk['foto_path']) ? $produk['foto_path'] : 'assets/images/placeholder.svg'; ?>" 
                     alt="<?php echo $produk['nama_produk']; ?>" 
                     class="detail-image"
                     onerror="this.src='assets/images/placeholder.svg'">
            </div>
            
            <!-- Info Column -->
            <div class="col-lg-7">
                <div class="detail-badge">
                    <i class="bi bi-tag"></i>
                    <?php echo $produk['nama_kategori']; ?>
                </div>
                
                <h1 class="detail-title"><?php echo $produk['nama_produk']; ?></h1>
                
                <div class="detail-meta">
                    <div class="meta-item">
                        <i class="bi bi-building"></i>
                        <strong>Brand:</strong> <?php echo $brand; ?>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-box"></i>
                        <strong>Kemasan:</strong> <?php echo $produk['nama_kemasan']; ?> - <?php echo $produk['volume_kemasan']; ?>
                    </div>
                    <div class="meta-item <?php echo $produk['stok'] > 20 ? 'text-success' : ($produk['stok'] > 0 ? 'text-warning' : 'text-danger'); ?>">
                        <i class="bi bi-stack"></i>
                        <strong>Stok:</strong> <?php echo $produk['stok'] > 0 ? $produk['stok'] . ' unit' : 'Habis'; ?>
                    </div>
                </div>
                
                <div class="detail-price">
                    <?php echo format_rupiah($produk['harga']); ?>
                    <small style="font-size: 1rem; color: var(--text-gray);">/ <?php echo $produk['volume_kemasan']; ?></small>
                </div>
                
                <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']): ?>
                <div class="mt-4">
                    <a href="pages/produk.php?action=edit&id=<?php echo $produk['id_produk']; ?>" class="btn-primary-custom">
                        <i class="bi bi-pencil"></i> Edit Produk
                    </a>
                    <a href="index.php" class="btn-secondary-custom ms-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
                <?php else: ?>
                <div class="mt-4">
                    <a href="index.php" class="btn-secondary-custom">
                        <i class="bi bi-arrow-left"></i> Kembali ke Katalog
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <hr class="my-5">
        
        <!-- Detail Information -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="info-section">
                    <h3><i class="bi bi-info-circle"></i> Informasi Produk</h3>
                    <table class="info-table">
                        <tr>
                            <td>Kategori</td>
                            <td><?php echo $produk['nama_kategori']; ?></td>
                        </tr>
                        <tr>
                            <td>Brand</td>
                            <td><?php echo $brand; ?></td>
                        </tr>
                        <?php if ($bahan_aktif): ?>
                        <tr>
                            <td>Bahan Aktif</td>
                            <td><?php echo $bahan_aktif; ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td>Kemasan</td>
                            <td><?php echo $produk['nama_kemasan']; ?> - <?php echo $produk['volume_kemasan']; ?></td>
                        </tr>
                        <tr>
                            <td>Kondisi Target</td>
                            <td><?php echo $produk['nama_kondisi']; ?></td>
                        </tr>
                        <tr>
                            <td>Harga</td>
                            <td><strong><?php echo format_rupiah($produk['harga']); ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="info-section">
                    <h3><i class="bi bi-bug"></i> Target Pengendalian</h3>
                    <?php if ($produk['target_hama']): ?>
                        <p><?php echo $produk['target_hama']; ?></p>
                    <?php else: ?>
                        <p class="text-muted">Informasi target tidak tersedia</p>
                    <?php endif; ?>
                </div>
                
                <div class="info-section mt-4">
                    <h3><i class="bi bi-chat-left-text"></i> Kondisi Tanaman</h3>
                    <p><strong><?php echo $produk['nama_kondisi']; ?></strong></p>
                    <p><?php echo $produk['kondisi_keterangan']; ?></p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="info-section">
                    <h3><i class="bi bi-clipboard-check"></i> Kegunaan & Manfaat</h3>
                    <p><?php echo nl2br($produk['kegunaan']); ?></p>
                </div>
                
                <?php if ($deskripsi_lengkap): ?>
                <div class="info-section">
                    <h3><i class="bi bi-file-text"></i> Deskripsi Lengkap</h3>
                    <p><?php echo nl2br($deskripsi_lengkap); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
