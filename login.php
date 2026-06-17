<?php
require_once 'config/koneksi.php';

$error = '';

// Check if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
    header('Location: pages/produk.php');
    exit;
}

// Process login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];
    
    // Simple admin authentication (dalam production gunakan table users)
    // Username: admin, Password: admin123
    $admin_username = 'admin';
    $admin_password_hash = password_hash('admin123', PASSWORD_DEFAULT);
    
    if ($username === $admin_username && password_verify($password, $admin_password_hash)) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: pages/produk.php');
        exit;
    } else {
        $error = 'Username atau password salah!';
    }
}

$page_title = 'Login Admin';
include 'includes/header.php';
?>

<div class="container">
    <div class="row justify-content-center" style="min-height: 70vh; align-items: center;">
        <div class="col-md-5">
            <div class="detail-container fade-in">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-lock" style="font-size: 4rem; color: var(--primary-green);"></i>
                    <h2 class="mt-3" style="font-weight: 700;">Login Admin</h2>
                    <p class="text-muted">Masuk untuk mengelola katalog produk</p>
                </div>
                
                <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <?php echo $error; ?>
                </div>
                <?php endif; ?>
                
                <form method="POST" action="" class="needs-validation" novalidate>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-person"></i> Username
                        </label>
                        <input type="text" 
                               name="username" 
                               class="form-control" 
                               required 
                               autofocus
                               placeholder="Masukkan username">
                        <div class="invalid-feedback">Username harus diisi</div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-lock"></i> Password
                        </label>
                        <input type="password" 
                               name="password" 
                               class="form-control" 
                               required
                               placeholder="Masukkan password">
                        <div class="invalid-feedback">Password harus diisi</div>
                    </div>
                    
                    <button type="submit" class="btn-primary-custom w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </form>
                
                <div class="text-center mt-4">
                    <p class="text-muted small">
                        Default: username = <code>admin</code>, password = <code>admin123</code>
                    </p>
                    <a href="index.php" class="text-decoration-none" style="color: var(--primary-green);">
                        <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
