<?php
require_once '../config/koneksi.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ../login.php');
    exit;
}

$is_admin_page = true;
$page_title = 'Kelola Kondisi Tanaman';
include '../includes/header.php';
?>

<div class="container my-5">
    <h2 style="font-weight: 700;"><i class="bi bi-droplet"></i> Kelola Kondisi Tanaman</h2>
    <p class="text-muted mb-4">Kondisi tanaman untuk rekomendasi produk</p>
    
    <div class="admin-table">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Kondisi</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM kondisi_tanaman ORDER BY id_kondisi");
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $row['id_kondisi']; ?></td>
                    <td><strong><?php echo $row['nama_kondisi']; ?></strong></td>
                    <td><?php echo $row['keterangan']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
