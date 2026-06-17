# 📊 Dokumentasi Fitur Database

## Overview

Database `db_pestisida` dirancang dengan fitur-fitur advanced untuk memenuhi requirement Basis Data 1:

- ✅ **6 Tabel** (kategori, kemasan, kondisi_tanaman, produk, target, log_produk)
- ✅ **3+ Complex Queries** (JOIN, Subquery, Aggregate)
- ✅ **2 Views** untuk reporting
- ✅ **2 Functions** untuk kalkulasi
- ✅ **2 Triggers** untuk audit logging

---

## 1. Struktur Tabel

### 1.1 Tabel `kategori`
Menyimpan kategori produk (Herbisida, Insektisida, Fungisida, dll)

```sql
CREATE TABLE `kategori` (
  `id_kategori` int(11) PRIMARY KEY AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
);
```

### 1.2 Tabel `kemasan`
Menyimpan jenis kemasan dan volume

```sql
CREATE TABLE `kemasan` (
  `id_kemasan` int(11) PRIMARY KEY AUTO_INCREMENT,
  `nama_kemasan` varchar(100) NOT NULL,
  `volume_kemasan` varchar(50) NOT NULL
);
```

### 1.3 Tabel `kondisi_tanaman`
Kondisi tanaman untuk rekomendasi produk

```sql
CREATE TABLE `kondisi_tanaman` (
  `id_kondisi` int(11) PRIMARY KEY AUTO_INCREMENT,
  `nama_kondisi` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL
);
```

### 1.4 Tabel `produk` (Tabel Utama)
Menyimpan data produk dengan foreign key ke tabel lain

```sql
CREATE TABLE `produk` (
  `id_produk` int(11) PRIMARY KEY AUTO_INCREMENT,
  `id_kategori` int(11) NOT NULL,
  `id_kemasan` int(11) NOT NULL,
  `id_kondisi` int(11) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `kegunaan` text DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `foto_produk` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  
  FOREIGN KEY (`id_kategori`) REFERENCES `kategori`(`id_kategori`) ON UPDATE CASCADE,
  FOREIGN KEY (`id_kemasan`) REFERENCES `kemasan`(`id_kemasan`) ON UPDATE CASCADE,
  FOREIGN KEY (`id_kondisi`) REFERENCES `kondisi_tanaman`(`id_kondisi`) ON UPDATE CASCADE
);
```

### 1.5 Tabel `target`
Target hama/penyakit/gulma yang dikendalikan

```sql
CREATE TABLE `target` (
  `id_target` int(11) PRIMARY KEY AUTO_INCREMENT,
  `id_produk` int(11) NOT NULL,
  `nama_target` varchar(100) NOT NULL,
  `jenis_target` enum('Hama','Penyakit','Gulma') NOT NULL,
  
  FOREIGN KEY (`id_produk`) REFERENCES `produk`(`id_produk`) 
    ON DELETE CASCADE ON UPDATE CASCADE
);
```

### 1.6 Tabel `log_produk`
Menyimpan log perubahan produk (untuk trigger)

```sql
CREATE TABLE `log_produk` (
  `id_log` int(11) PRIMARY KEY AUTO_INCREMENT,
  `id_produk` int(11) NOT NULL,
  `aksi` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `nama_produk_lama` varchar(150) DEFAULT NULL,
  `nama_produk_baru` varchar(150) DEFAULT NULL,
  `harga_lama` decimal(12,2) DEFAULT NULL,
  `harga_baru` decimal(12,2) DEFAULT NULL,
  `stok_lama` int(11) DEFAULT NULL,
  `stok_baru` int(11) DEFAULT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
);
```

---

## 2. Complex Queries

### 2.1 JOIN Multiple Tables
Digunakan di `api/get_products.php` untuk menampilkan produk dengan detail lengkap:

```sql
SELECT 
    p.id_produk,
    p.nama_produk,
    p.harga,
    p.stok,
    p.kegunaan,
    p.deskripsi,
    k.nama_kategori,
    km.nama_kemasan,
    km.volume_kemasan,
    kt.nama_kondisi,
    CONCAT('uploads/produk/', p.foto_produk) as foto_path
FROM produk p
INNER JOIN kategori k ON p.id_kategori = k.id_kategori
INNER JOIN kemasan km ON p.id_kemasan = km.id_kemasan
INNER JOIN kondisi_tanaman kt ON p.id_kondisi = kt.id_kondisi
WHERE p.nama_produk LIKE '%search%'
ORDER BY p.id_produk DESC;
```

### 2.2 JOIN dengan Subquery
Digunakan di `detail.php` untuk menampilkan detail produk dengan daftar target:

```sql
SELECT 
    p.*,
    k.nama_kategori,
    km.nama_kemasan,
    km.volume_kemasan,
    kt.nama_kondisi,
    (SELECT GROUP_CONCAT(CONCAT(t.nama_target, ' (', t.jenis_target, ')') SEPARATOR ', ') 
     FROM target t 
     WHERE t.id_produk = p.id_produk) as target_hama
FROM produk p
INNER JOIN kategori k ON p.id_kategori = k.id_kategori
INNER JOIN kemasan km ON p.id_kemasan = km.id_kemasan
INNER JOIN kondisi_tanaman kt ON p.id_kondisi = kt.id_kondisi
WHERE p.id_produk = ?;
```

### 2.3 Aggregate Functions dengan GROUP BY
Query untuk statistik produk per kategori:

```sql
SELECT 
    k.id_kategori,
    k.nama_kategori,
    COUNT(p.id_produk) as jumlah_produk,
    SUM(p.stok) as total_stok,
    AVG(p.harga) as rata_rata_harga,
    MIN(p.harga) as harga_terendah,
    MAX(p.harga) as harga_tertinggi
FROM kategori k
LEFT JOIN produk p ON k.id_kategori = p.id_kategori
GROUP BY k.id_kategori, k.nama_kategori
HAVING COUNT(p.id_produk) > 0
ORDER BY jumlah_produk DESC;
```

---

## 3. Views (Database Views)

### 3.1 VIEW: `view_produk_kategori`
Ringkasan statistik produk per kategori

```sql
CREATE OR REPLACE VIEW view_produk_kategori AS
SELECT 
    k.id_kategori,
    k.nama_kategori,
    COUNT(p.id_produk) as jumlah_produk,
    SUM(p.stok) as total_stok,
    AVG(p.harga) as rata_rata_harga,
    MIN(p.harga) as harga_terendah,
    MAX(p.harga) as harga_tertinggi
FROM kategori k
LEFT JOIN produk p ON k.id_kategori = p.id_kategori
GROUP BY k.id_kategori, k.nama_kategori
ORDER BY jumlah_produk DESC;
```

**Cara Menggunakan:**
```sql
-- Lihat semua statistik
SELECT * FROM view_produk_kategori;

-- Filter kategori tertentu
SELECT * FROM view_produk_kategori WHERE nama_kategori = 'Herbisida';
```

**Digunakan di:** `pages/kategori.php`

### 3.2 VIEW: `view_produk_lengkap`
Detail produk lengkap dengan status stok otomatis

```sql
CREATE OR REPLACE VIEW view_produk_lengkap AS
SELECT 
    p.id_produk,
    p.nama_produk,
    p.harga,
    p.stok,
    k.nama_kategori,
    km.nama_kemasan,
    kt.nama_kondisi,
    GROUP_CONCAT(DISTINCT t.nama_target SEPARATOR ', ') as target_hama,
    CASE 
        WHEN p.stok = 0 THEN 'Habis'
        WHEN p.stok <= 20 THEN 'Stok Rendah'
        ELSE 'Tersedia'
    END as status_stok,
    p.created_at,
    p.updated_at
FROM produk p
INNER JOIN kategori k ON p.id_kategori = k.id_kategori
INNER JOIN kemasan km ON p.id_kemasan = km.id_kemasan
INNER JOIN kondisi_tanaman kt ON p.id_kondisi = kt.id_kondisi
LEFT JOIN target t ON p.id_produk = t.id_produk
GROUP BY p.id_produk;
```

**Cara Menggunakan:**
```sql
-- Lihat produk dengan stok tersedia
SELECT * FROM view_produk_lengkap WHERE status_stok = 'Tersedia';

-- Lihat produk dengan stok rendah
SELECT * FROM view_produk_lengkap WHERE status_stok = 'Stok Rendah';
```

---

## 4. Functions (Stored Functions)

### 4.1 FUNCTION: `hitung_nilai_stok()`
Menghitung total nilai stok produk (harga × stok)

```sql
DELIMITER $$
CREATE FUNCTION hitung_nilai_stok(p_id_produk INT)
RETURNS DECIMAL(15,2)
DETERMINISTIC
BEGIN
    DECLARE total_nilai DECIMAL(15,2);
    
    SELECT harga * stok INTO total_nilai
    FROM produk
    WHERE id_produk = p_id_produk;
    
    RETURN IFNULL(total_nilai, 0);
END$$
DELIMITER ;
```

**Cara Menggunakan:**
```sql
-- Hitung nilai stok produk ID 1
SELECT hitung_nilai_stok(1);

-- Hitung nilai stok semua produk
SELECT 
    id_produk,
    nama_produk,
    harga,
    stok,
    hitung_nilai_stok(id_produk) as nilai_total
FROM produk
ORDER BY nilai_total DESC;
```

**Use Case:** Laporan nilai inventori, analisis aset

### 4.2 FUNCTION: `get_status_stok()`
Mendapatkan label status stok berdasarkan jumlah

```sql
DELIMITER $$
CREATE FUNCTION get_status_stok(p_stok INT)
RETURNS VARCHAR(20)
DETERMINISTIC
BEGIN
    DECLARE status VARCHAR(20);
    
    IF p_stok = 0 THEN
        SET status = 'Habis';
    ELSEIF p_stok <= 10 THEN
        SET status = 'Kritis';
    ELSEIF p_stok <= 20 THEN
        SET status = 'Rendah';
    ELSE
        SET status = 'Aman';
    END IF;
    
    RETURN status;
END$$
DELIMITER ;
```

**Cara Menggunakan:**
```sql
-- Get status stok produk
SELECT 
    nama_produk,
    stok,
    get_status_stok(stok) as status
FROM produk
WHERE get_status_stok(stok) IN ('Kritis', 'Rendah');
```

**Use Case:** Alert stok rendah, dashboard monitoring

---

## 5. Triggers (Audit Logging)

### 5.1 TRIGGER: `trg_after_insert_produk`
Log otomatis saat produk baru ditambahkan

```sql
DELIMITER $$
CREATE TRIGGER trg_after_insert_produk
AFTER INSERT ON produk
FOR EACH ROW
BEGIN
    INSERT INTO log_produk (
        id_produk,
        aksi,
        nama_produk_baru,
        harga_baru,
        stok_baru
    ) VALUES (
        NEW.id_produk,
        'INSERT',
        NEW.nama_produk,
        NEW.harga,
        NEW.stok
    );
END$$
DELIMITER ;
```

### 5.2 TRIGGER: `trg_after_update_produk`
Log otomatis saat produk diupdate

```sql
DELIMITER $$
CREATE TRIGGER trg_after_update_produk
AFTER UPDATE ON produk
FOR EACH ROW
BEGIN
    INSERT INTO log_produk (
        id_produk,
        aksi,
        nama_produk_lama,
        nama_produk_baru,
        harga_lama,
        harga_baru,
        stok_lama,
        stok_baru
    ) VALUES (
        NEW.id_produk,
        'UPDATE',
        OLD.nama_produk,
        NEW.nama_produk,
        OLD.harga,
        NEW.harga,
        OLD.stok,
        NEW.stok
    );
END$$
DELIMITER ;
```

**Cara Melihat Log:**
```sql
-- Lihat semua log
SELECT * FROM log_produk ORDER BY waktu DESC;

-- Lihat log produk tertentu
SELECT * FROM log_produk WHERE id_produk = 1 ORDER BY waktu DESC;

-- Lihat perubahan harga
SELECT * FROM log_produk WHERE harga_lama != harga_baru ORDER BY waktu DESC;
```

**Use Case:** Audit trail, tracking perubahan data

---

## 6. Testing Queries

### Test Complex Query dengan JOIN dan Subquery
```sql
SELECT 
    p.nama_produk,
    k.nama_kategori,
    p.harga,
    p.stok,
    (SELECT GROUP_CONCAT(t.nama_target SEPARATOR ', ')
     FROM target t
     WHERE t.id_produk = p.id_produk) as daftar_target,
    hitung_nilai_stok(p.id_produk) as nilai_total_stok,
    get_status_stok(p.stok) as status_stok
FROM produk p
INNER JOIN kategori k ON p.id_kategori = k.id_kategori
WHERE p.stok > 0
ORDER BY p.harga DESC
LIMIT 10;
```

### Test VIEW
```sql
-- Statistik per kategori
SELECT * FROM view_produk_kategori;

-- Produk tersedia
SELECT * FROM view_produk_lengkap WHERE status_stok = 'Tersedia';
```

### Test FUNCTION
```sql
-- Top 10 produk dengan nilai stok terbesar
SELECT 
    nama_produk,
    harga,
    stok,
    hitung_nilai_stok(id_produk) as nilai_stok
FROM produk
ORDER BY nilai_stok DESC
LIMIT 10;
```

### Test TRIGGER
```sql
-- Insert produk baru (otomatis log)
INSERT INTO produk (id_kategori, id_kemasan, id_kondisi, nama_produk, harga, stok, kegunaan)
VALUES (1, 1, 4, 'Test Product', 100000, 50, 'Test kegunaan');

-- Update produk (otomatis log)
UPDATE produk SET stok = 100, harga = 150000 WHERE nama_produk = 'Test Product';

-- Lihat log yang baru dibuat
SELECT * FROM log_produk WHERE id_produk = LAST_INSERT_ID() ORDER BY waktu DESC;
```

---

## 7. Query Optimization Tips

### Indexing
```sql
-- Index untuk performa search
CREATE INDEX idx_nama_produk ON produk(nama_produk);
CREATE INDEX idx_harga ON produk(harga);
CREATE INDEX idx_stok ON produk(stok);

-- Index untuk foreign keys (sudah otomatis)
```

### Query Caching
```sql
-- Enable query cache di my.cnf
query_cache_type = 1
query_cache_size = 64M
```

---

## 8. Database Diagram (Relationships)

```
kategori (1) ----< (N) produk
kemasan (1) ----< (N) produk
kondisi_tanaman (1) ----< (N) produk
produk (1) ----< (N) target
produk (1) ----< (N) log_produk
```

**Relasi:**
- One-to-Many: kategori → produk
- One-to-Many: kemasan → produk
- One-to-Many: kondisi_tanaman → produk
- One-to-Many: produk → target (CASCADE DELETE)
- One-to-Many: produk → log_produk

---

## Summary Requirement ✅

| Requirement | Status | Keterangan |
|------------|--------|------------|
| Min. 5 Tabel | ✅ | 6 tabel (kategori, kemasan, kondisi_tanaman, produk, target, log_produk) |
| Min. 3 Complex Query | ✅ | JOIN multiple tables, Subquery, Aggregate GROUP BY |
| Min. 2 View | ✅ | view_produk_kategori, view_produk_lengkap |
| Min. 2 Function | ✅ | hitung_nilai_stok(), get_status_stok() |
| Min. 2 Trigger | ✅ | trg_after_insert_produk, trg_after_update_produk |

---

