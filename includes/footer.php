    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="bi bi-flower1"></i> TaniMakmur</h5>
                    <p>Database terlengkap pestisida, fungisida, herbisida, dan obat-obatan pertanian. Lengkap dengan fungsi, dosis, dan harga terkini.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Kategori Produk</h5>
                    <p><a href="index.php">Herbisida</a></p>
                    <p><a href="index.php">Insektisida</a></p>
                    <p><a href="index.php">Fungisida</a></p>
                    <p><a href="index.php">Pupuk</a></p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Informasi</h5>
                    <p>Untuk informasi lengkap, konsultasikan dengan ahli pertanian</p>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.2);">
            <div class="text-center">
                <p class="mb-0">&copy; 2026 TaniPestisida. Dibuat untuk memudahkan petani Indonesia.</p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo isset($is_admin_page) && $is_admin_page ? '../' : ''; ?>assets/js/main.js"></script>
</body>
</html>
