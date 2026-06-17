# Panduan Instalasi Lengkap

## Requirement Sistem

- **Web Server**: Apache 2.4+
- **PHP**: 8.0 atau lebih tinggi
- **Database**: MySQL 5.7+ / MariaDB 10.4+
- **Browser**: Chrome, Firefox, Safari, Edge (versi terbaru)

## Langkah-langkah Instalasi

### 1. Download & Extract

```bash
# Clone atau extract proyek ke folder htdocs
cd /Applications/XAMPP/htdocs/
# atau untuk MAMP
cd /Applications/MAMP/htdocs/

# Extract atau copy folder katalog-pestisida
```

### 2. Setup Database

#### Opsi A: Melalui phpMyAdmin

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Klik tab "Database"
3. Buat database baru bernama `db_pestisida`
4. Pilih database yang baru dibuat
5. Klik tab "Import"
6. Pilih file `database.sql`
7. Klik "Go" atau "Kirim"

#### Opsi B: Melalui Command Line

```bash
# Masuk ke MySQL
mysql -u root -p

# Buat database
CREATE DATABASE db_pestisida;

# Keluar dari MySQL
exit

# Import database
mysql -u root -p db_pestisida < database.sql
```

### 3. Konfigurasi Database

Edit file `config/koneksi.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');           // Sesuaikan dengan user MySQL
define('DB_PASS', '');               // Sesuaikan dengan password MySQL
define('DB_NAME', 'db_pestisida');
```

### 4. Set Permission Folder

#### Untuk macOS/Linux:

```bash
# Berikan permission write untuk folder uploads
chmod -R 777 uploads/

# Atau lebih aman:
chmod -R 755 uploads/
chown -R www-data:www-data uploads/
```

#### Untuk Windows:
Klik kanan folder `uploads` → Properties → Security → Edit → Berikan Full Control untuk Users

### 5. Start Web Server

#### XAMPP:
1. Buka XAMPP Control Panel
2. Start Apache
3. Start MySQL

#### MAMP:
1. Buka MAMP
2. Klik "Start Servers"

### 6. Akses Website

Buka browser dan akses:
```
http://localhost/katalog-pestisida/
```

### 7. Login Admin

```
URL: http://localhost/katalog-pestisida/login.php
Username: admin
Password: admin123
```

## Testing Database Features

### Test VIEW

```sql
-- Lihat statistik per kategori
SELECT * FROM view_produk_kategori;

-- Lihat produk lengkap
SELECT * FROM view_produk_lengkap;
```

### Test FUNCTION

```sql
-- Hitung nilai stok produk ID 1
SELECT hitung_nilai_stok(1);

-- Cek status stok semua produk
SELECT 
    nama_produk, 
    stok, 
    get_status_stok(stok) as status_stok 
FROM produk;
```

### Test TRIGGER

```sql
-- Update produk (akan otomatis log)
UPDATE produk SET stok = 100 WHERE id_produk = 1;

-- Lihat log perubahan
SELECT * FROM log_produk ORDER BY waktu DESC;
```

## Troubleshooting

### Error: "Can't connect to database"

**Solusi:**
1. Pastikan MySQL sudah running
2. Cek username dan password di `config/koneksi.php`
3. Pastikan database `db_pestisida` sudah dibuat

### Error: "Upload failed"

**Solusi:**
1. Cek permission folder `uploads/`
2. Pastikan ukuran file < 5MB
3. Pastikan format file jpg/png/gif

### Error: "Page not found"

**Solusi:**
1. Pastikan path URL benar: `http://localhost/katalog-pestisida/`
2. Cek Apache sudah running
3. Cek file index.php ada di root folder

### Halaman blank atau error 500

**Solusi:**
1. Cek error log PHP di XAMPP/MAMP
2. Pastikan PHP versi 8.0+
3. Enable error reporting di php.ini:
   ```
   display_errors = On
   error_reporting = E_ALL
   ```

### Gambar tidak muncul

**Solusi:**
1. Pastikan path gambar benar
2. Cek permission folder uploads
3. Gunakan placeholder.svg jika gambar tidak ada

## Konfigurasi Tambahan (Opsional)

### Ubah Password Admin

Edit file `login.php` line 18-19:

```php
$admin_username = 'admin';
$admin_password_hash = password_hash('passwordbaru', PASSWORD_DEFAULT);
```

### Increase Upload Limit

Edit `php.ini`:

```ini
upload_max_filesize = 10M
post_max_size = 10M
```

Restart Apache setelah perubahan.

### Pretty URL (mod_rewrite)

Buat file `.htaccess` di root:

```apache
RewriteEngine On
RewriteBase /katalog-pestisida/

# Redirect detail
RewriteRule ^produk/([0-9]+)$ detail.php?id=$1 [L]
```

## Maintenance

### Backup Database

```bash
mysqldump -u root -p db_pestisida > backup_$(date +%Y%m%d).sql
```

### Clear Logs

```sql
TRUNCATE TABLE log_produk;
```

### Update Data

Gunakan admin panel atau jalankan query SQL melalui phpMyAdmin.