#  Quick Start Guide

Panduan cepat untuk memulai proyek dalam 5 menit!

##  5 Langkah Instalasi Cepat

### Setup Database (2 menit)

```bash
# Buka phpMyAdmin
http://localhost/phpmyadmin

# Buat database: db_pestisida
# Import file: database.sql
```

### Copy Folder (1 menit)

```bash
# Copy folder ke htdocs
cp -r katalog-pestisida /Applications/XAMPP/htdocs/

# Atau untuk Windows:
# Copy folder ke C:\xampp\htdocs\
```

###  Set Permission (1 menit)

```bash
cd /Applications/XAMPP/htdocs/katalog-pestisida
chmod -R 777 uploads/
```

###  Konfigurasi (30 detik)

Edit `config/koneksi.php` jika perlu ubah password MySQL:

```php
define('DB_PASS', '');  // Password MySQL Anda
```

### Akses Website (30 detik)

```
Homepage: http://localhost/katalog-pestisida/
Admin: http://localhost/katalog-pestisida/login.php

Username: admin
Password: admin123
```

---

## First Steps

### Sebagai Pengunjung

1. **Browse Produk**
   - Buka `http://localhost/katalog-pestisida/`
   - Lihat katalog produk dengan grid cards

2. **Search Produk**
   - Ketik nama produk, brand, atau bahan aktif di search bar
   - Hasil muncul real-time

3. **Filter Produk**
   - Klik chip kategori untuk filter
   - Contoh: Herbisida, Insektisida, Fungisida

4. **Lihat Detail**
   - Klik card produk
   - Lihat informasi lengkap: harga, stok, kegunaan, target

### Sebagai Admin

1. **Login Admin**
   ```
   URL: http://localhost/katalog-pestisida/login.php
   Username: admin
   Password: admin123
   ```

2. **Tambah Produk**
   - Klik "Tambah Produk" di navbar
   - Isi form dengan data produk
   - Upload gambar (optional)
   - Klik "Tambah Produk"

3. **Edit Produk**
   - Masuk ke "Kelola Produk"
   - Klik tombol Edit (icon pensil)
   - Update data
   - Klik "Update Produk"

4. **Hapus Produk**
   - Masuk ke "Kelola Produk"
   - Klik tombol Hapus (icon trash)
   - Konfirmasi penghapusan

5. **Kelola Kategori**
   - Masuk ke "Kategori"
   - Lihat statistik per kategori
   - Edit kategori jika perlu

---

## Testing Database Features

### Test Complex Query
```sql
-- Buka phpMyAdmin → SQL tab
-- Copy paste query ini:

SELECT 
    p.nama_produk,
    k.nama_kategori,
    p.harga,
    p.stok,
    (SELECT GROUP_CONCAT(t.nama_target) 
     FROM target t 
     WHERE t.id_produk = p.id_produk) as targets
FROM produk p
INNER JOIN kategori k ON p.id_kategori = k.id_kategori
WHERE p.stok > 0
LIMIT 10;
```

### Test VIEW
```sql
-- Lihat statistik kategori
SELECT * FROM view_produk_kategori;

-- Lihat produk lengkap
SELECT * FROM view_produk_lengkap WHERE status_stok = 'Tersedia';
```

### Test FUNCTION
```sql
-- Hitung nilai stok produk ID 1
SELECT hitung_nilai_stok(1) as nilai_stok;

-- Status stok semua produk
SELECT 
    nama_produk, 
    stok, 
    get_status_stok(stok) as status 
FROM produk;
```

### Test TRIGGER
```sql
-- Insert produk baru (akan otomatis log)
INSERT INTO produk (id_kategori, id_kemasan, id_kondisi, nama_produk, harga, stok)
VALUES (1, 1, 4, 'Test Product', 100000, 50);

-- Lihat log yang baru dibuat
SELECT * FROM log_produk ORDER BY waktu DESC LIMIT 5;
```

---

## Checklist Testing

Setelah instalasi, test fitur-fitur berikut:

- [ ] Homepage load dengan produk ✓
- [ ] Search produk berfungsi ✓
- [ ] Filter kategori berfungsi ✓
- [ ] Pagination berfungsi ✓
- [ ] Klik detail produk ✓
- [ ] Login admin berhasil ✓
- [ ] Tambah produk baru ✓
- [ ] Edit produk ✓
- [ ] Hapus produk ✓
- [ ] Lihat statistik kategori ✓

---

## Demo Data

Jika ingin menambah lebih banyak data produk:

```bash
# Buka phpMyAdmin
# Pilih database db_pestisida
# Import file SAMPLE_DATA.sql
```

---

## Troubleshooting Cepat

### Error: Cannot connect to database
**Fix:**
1. Start Apache & MySQL di XAMPP
2. Check password di `config/koneksi.php`

### Error: Upload failed
**Fix:**
```bash
chmod -R 777 uploads/
```

### Gambar tidak muncul
**Fix:**
- Pastikan path benar
- Check permission folder uploads
- Lihat browser console untuk error

### Page blank / error 500
**Fix:**
1. Check Apache error log
2. Enable display_errors di php.ini
3. Restart Apache

---

## Dokumentasi Lengkap

Untuk dokumentasi detail, baca:

- **README.md** - Dokumentasi utama & overview
- **INSTALASI.md** - Panduan instalasi lengkap
- **DATABASE_FEATURES.md** - Dokumentasi fitur database
- **CHECKLIST.md** - Checklist fitur & testing

---

## Default Credentials

```
Admin Login:
Username: admin
Password: admin123

Database:
Host: localhost
User: root
Password: (kosong)
Database: db_pestisida
```

---

## Tips

1. **Development Mode**
   - Enable error reporting di php.ini
   - Check browser console untuk JavaScript errors

2. **Production Mode**
   - Disable error reporting
   - Ubah password admin default
   - Set proper file permissions

3. **Backup**
   - Backup database secara berkala
   - Simpan folder uploads

---

## Next Steps

Setelah instalasi berhasil:

1. Explore semua fitur
2. Test CRUD operations
3. Test search & filter
4. Test database features (VIEW, FUNCTION, TRIGGER)
5. Customize data sesuai kebutuhan
6. Upload gambar produk yang sesuai

---
