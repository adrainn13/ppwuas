<?php
require_once '../config/koneksi.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: ../login.php');
    exit;
}

$is_admin_page = true;
$page_title = 'Target Hama/Penyakit';
include '../includes/header.php';
?>

<div class="container my-5">
    <h2 style="font-weight: 700;"><i class="bi bi-bug"></i> Target Hama/Penyakit</h2>
    <p class="text-muted mb-4">Daftar target pengendalian produk</p>
    
    <div class="admin-table">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Produk</th>
                    <th>Target</th>
                    <th>Jenis</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT t.*, p.nama_produk 
                        FROM target t 
                        JOIN produk p ON t.id_produk = p.id_produk 
                        ORDER BY t.id_produk, t.jenis_target";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $row['id_target']; ?></td>
                    <td><?php echo $row['nama_produk']; ?></td>
                    <td><strong><?php echo $row['nama_target']; ?></strong></td>
                    <td>
                        <span class="badge" style="background: var(--secondary-green); color: var(--primary-green-dark);">
                            <?php echo $row['jenis_target']; ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
