# Katalog Pestisida - Website Katalog Produk Pertanian
# PROJECT UAS PPW1 TRPL 25
Website katalog lengkap untuk pestisida, fungisida, herbisida, dan obat-obatan pertanian dengan fitur CRUD lengkap, search & filter, dan admin panel.

## Deskripsi

Website ini adalah platform katalog digital untuk produk-produk pertanian seperti pestisida, fungisida, herbisida, pupuk, dan ZPT. Dibangun dengan PHP, MySQL, Bootstrap 5, dan JavaScript untuk memberikan pengalaman pengguna yang modern dan responsif.

##  Fitur Utama

### Homepage
- Hero section dengan statistik produk
- Grid card produk dengan pagination (10 produk per halaman)
- Real-time search berdasarkan nama produk, merek, atau bahan aktif
- Filter produk berdasarkan kategori
- Design responsif mobile & desktop
- Animasi smooth dengan transisi CSS
- Corner radius 8px pada semua card

### Detail Produk
- Informasi lengkap produk (nama, kategori, harga, stok)
- Bahan aktif dan brand
- Target pengendalian hama/penyakit/gulma
- Kegunaan dan deskripsi lengkap
- Informasi kemasan dan kondisi tanaman
- Gambar produk beresolusi tinggi

### Admin Panel
- **Login System** dengan session dan password hash
- **CRUD Produk Lengkap**:
  - Create: Tambah produk dengan form validasi
  - Read: Tampilan tabel produk dengan pagination
  - Update: Edit produk dengan pre-filled form
  - Delete: Hapus produk dengan konfirmasi JavaScript
- Upload gambar produk (max 5MB)
- Form validasi client-side dan server-side
- Responsive admin interface

### Design
- Tema terang dengan warna hijau (#22c55e)
- Bootstrap 5 Grid System
- Bootstrap Icons
- Google Fonts (Inter)
- Smooth animations dan transitions
- Mobile-first responsive design

## Database

### Tabel (5 tabel):
1. **kategori** - Kategori produk (Herbisida, Insektisida, dll)
2. **kemasan** - Jenis kemasan produk
3. **kondisi_tanaman** - Kondisi tanaman target
4. **produk** - Data produk utama
5. **target** - Target hama/penyakit yang dikendalikan
6. **log_produk** - Log perubahan data (untuk trigger)

### Complex Queries (3+):
1. **JOIN Multiple Tables** - Mengambil produk dengan kategori, kemasan, dan kondisi
2. **JOIN dengan Subquery** - Detail produk dengan GROUP_CONCAT untuk target
3. **Aggregate Functions** - Statistik produk per kategori

### Views (2):
1. **view_produk_kategori** - Ringkasan statistik produk per kategori
2. **view_produk_lengkap** - Produk dengan semua detail dan status stok

### Functions (2):
1. **hitung_nilai_stok()** - Menghitung total nilai stok produk
2. **get_status_stok()** - Mendapatkan status stok (Aman/Rendah/Kritis/Habis)

### Triggers (2):
1. **trg_after_insert_produk** - Log otomatis saat produk baru ditambahkan
2. **trg_after_update_produk** - Log otomatis saat produk diupdate

## Teknologi

- **Backend**: PHP 8.0+
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Framework CSS**: Bootstrap 5.3
- **Icons**: Bootstrap Icons 1.11
- **Fonts**: Google Fonts (Inter)

## Struktur Folder

```
katalog-pestisida/
│
├── assets/
│   ├── css/
│   │   └── style.css          # Custom CSS dengan tema hijau terang
│   ├── js/
│   │   └── main.js            # JavaScript untuk search, filter, pagination
│   └── images/
│       └── placeholder.jpg     # Gambar placeholder
│
├── api/
│   ├── get_products.php       # API untuk load produk dengan AJAX
│   └── delete_product.php     # API untuk hapus produk
│
├── config/
│   └── koneksi.php            # Konfigurasi database dan fungsi helper
│
├── includes/
│   ├── header.php             # Header dengan navbar
│   └── footer.php             # Footer
│
├── pages/
│   ├── produk.php             # Admin CRUD produk
│   ├── kategori.php           # Kelola kategori
│   ├── kemasan.php            # Kelola kemasan
│   ├── target.php             # Kelola target
│   └── kondisi.php            # Kelola kondisi tanaman
│
├── uploads/
│   └── produk/                # Folder upload gambar produk
│
├── .gitignore                 # Git ignore config
├── README.md                  # Dokumentasi
├── login.php                  # Halaman login admin
├── logout.php                 # Proses logout
├── index.php                  # Halaman utama
├── detail.php                 # Halaman detail produk
└── database.sql               # SQL database lengkap
```

## Cara Instalasi

### 1. Prerequisites
- XAMPP / WAMP / LAMP (PHP 8.0+, MySQL)
- Web browser modern
- Text editor (VS Code, Sublime, dll)

### 2. Install Database

```sql
-- Buat database
CREATE DATABASE db_pestisida;

-- Import file database.sql
-- Melalui phpMyAdmin atau command line:
mysql -u root -p db_pestisida < database.sql
```

### 3. Konfigurasi

Edit file `config/koneksi.php`:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');           // Sesuaikan dengan password MySQL Anda
define('DB_NAME', 'db_pestisida');
```

### 4. Setup Folder

```bash
# Pastikan folder uploads memiliki permission write
chmod -R 777 uploads/
```

### 5. Akses Website

```
http://localhost/katalog-pestisida/
```

### 6. Login Admin

```
Username: admin
Password: admin123
```

## Screenshot

### Homepage
![Homepage](https://github.com/user-attachments/assets/bf1e358c-0ad3-4795-985c-ac5f6c3f1f70)
![Homepage](https://github.com/user-attachments/assets/41210223-7d4a-45c0-b17a-9933c26732b4)

- Hero section dengan statistik
- Search bar dan filter kategori
- Grid card produk dengan pagination

### Detail Produk
![Detail](https://github.com/user-attachments/assets/392c900d-4361-4895-954c-06d324a22f4c)
![Detail](https://github.com/user-attachments/assets/db316f01-3c44-4383-b4c4-e6c56da9bdd5)
![Detail](https://github.com/user-attachments/assets/d7beba3f-8dd2-4a05-9479-728898bfa376)

- Informasi lengkap produk
- Bahan aktif dan target pengendalian
- Harga dan stok

### Admin Panel
![Admin](https://github.com/user-attachments/assets/1c525152-3fc3-47c6-a0bf-71e343bd252a)
![Admin](https://github.com/user-attachments/assets/48132c5b-696c-441c-98d7-db11d98b046b)
![Admin](https://github.com/user-attachments/assets/7c5b2b0a-9c3d-4370-83b1-8f36e3313762)

![Admin](https://github.com/user-attachments/assets/9d264e03-67b2-4e86-b02d-7b91ffaeaec4)
![Admin](https://github.com/user-attachments/assets/41ba0f1b-bbb2-4a26-9428-75c1a1f0afee)
![Admin](https://github.com/user-attachments/assets/41ba0f1b-bbb2-4a26-9428-75c1a1f0afee)





- Tabel produk dengan aksi CRUD
- Form tambah/edit produk
- Konfirmasi hapus

## Security Features

- **Prepared Statements** untuk semua query (mencegah SQL Injection)
- **Password Hashing** dengan `password_hash()` dan `password_verify()`
- **Input Sanitization** dengan `htmlspecialchars()` dan `real_escape_string()`
- **Session Management** untuk autentikasi admin
- **File Upload Validation** (tipe file, ukuran, extension)
- **CSRF Protection** ready (dapat ditambahkan token)

## Fitur Database Lanjutan

### Complex Query Example:

```sql
-- Query dengan multiple JOIN dan subquery
SELECT 
    p.*,
    k.nama_kategori,
    (SELECT GROUP_CONCAT(t.nama_target) 
     FROM target t 
     WHERE t.id_produk = p.id_produk) as targets
FROM produk p
INNER JOIN kategori k ON p.id_kategori = k.id_kategori
WHERE p.stok > 0
ORDER BY p.created_at DESC;
```

### Menggunakan View:

```sql
-- Statistik per kategori
SELECT * FROM view_produk_kategori;

-- Produk lengkap dengan status
SELECT * FROM view_produk_lengkap WHERE status_stok = 'Tersedia';
```

### Menggunakan Function:

```sql
-- Hitung nilai total stok produk ID 1
SELECT hitung_nilai_stok(1);

-- Get status stok
SELECT nama_produk, stok, get_status_stok(stok) as status
FROM produk;
```

### Trigger Logs:

```sql
-- Lihat log perubahan produk
SELECT * FROM log_produk ORDER BY waktu DESC LIMIT 10;
```

## Design Guidelines

### Color Palette:
- Primary Green: `#22c55e`
- Primary Green Dark: `#16a34a`
- Primary Green Light: `#86efac`
- Secondary Green: `#dcfce7`
- Text Dark: `#1f2937`
- Text Gray: `#6b7280`
- Background: `#f9fafb`

### Typography:
- Font Family: Inter (Google Fonts)
- Heading: 700-800 weight
- Body: 400-500 weight
- Small: 0.875rem

### Spacing:
- Border Radius: 8px (cards)
- Border Radius: 20px (pills/badges)
- Gap: 1.5rem (grid)
- Padding: 1.25rem (card body)

## TODO / Future Enhancements

- [ ] Implementasi shopping cart
- [ ] Export data ke Excel/PDF
- [ ] Multi-user dengan role (admin, staff, viewer)
- [ ] Dashboard dengan chart statistik
- [ ] Notifikasi stok rendah
- [ ] Riwayat perubahan harga
- [ ] Rating dan review produk
- [ ] Integration dengan payment gateway
