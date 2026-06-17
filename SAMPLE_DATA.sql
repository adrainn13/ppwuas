-- Sample Data untuk Testing
-- Jalankan query ini jika ingin menambah data produk sample

-- Insert lebih banyak produk sample
INSERT INTO `produk` (`id_kategori`, `id_kemasan`, `id_kondisi`, `nama_produk`, `harga`, `stok`, `kegunaan`, `deskripsi`, `foto_produk`) VALUES
(2, 1, 2, 'Curacron 500 EC', 145000.00, 46, 'Insektisida organofosfat untuk mengendalikan ulat grayak, kutu daun, dan trips pada tanaman cabai, bawang, dan kapas', 'Syngenta | Bahan Aktif: Profenofos 500 g/L | Insektisida dengan daya racun tinggi dan spektrum luas. Efektif terhadap hama yang resisten.', 'curacron_500_ec.jpg'),
(2, 1, 2, 'Mars 50 EC', 78000.00, 31, 'Insektisida organofosfat untuk mengendalikan hama penggerek batang, ulat, dan kutu pada tanaman padi dan sayuran', 'Hextar | Bahan Aktif: Chlorpyrifos 500 g/L | Insektisida ekonomis dengan daya racun tinggi. Efektif sebagai insektisida kontak dan lambung.', 'mars_50_ec.jpg'),
(3, 2, 3, 'Brestan 60 WP', 155000.00, 90, 'Fungisida untuk mengendalikan penyakit blas (hawar daun) pada tanaman padi', 'Bayer | Bahan Aktif: Fentin Acetate 60% | Fungisida spesifik untuk penyakit blas pada padi. Bekerja secara protektif dan kuratif.', 'brestan_60_wp.jpg'),
(2, 2, 2, 'Orthene 75 SP', 110000.00, 27, 'Insektisida sistemik untuk mengendalikan hama kutu daun, kutu kebul, dan ulat pada tanaman sayuran dan buah-buahan', 'Bayer | Bahan Aktif: Acephate 75% | Insektisida sistemik yang cepat meresap ke seluruh bagian tanaman. Efektif terhadap kutu-kutuan.', 'orthene_75_sp.jpg'),
(5, 2, 1, 'NPK Mutiara 16-16-16', 45000.00, 20, 'Pupuk majemak lengkap untuk memenuhi kebutuhan hara makro tanaman, meningkatkan pertumbuhan dan produksi', 'Petrokimia Gresik | Bahan Aktif: Nitrogen 16%, Fosfor 16%, Kalium 16% | Pupuk NPK dengan kandungan seimbang. Cocok untuk berbagai jenis tanaman pangan dan hortikultura.', 'npk_mutiara_161616.jpg'),
(5, 2, 1, 'Urea 46%', 32000.00, 50, 'Pupuk nitrogen untuk merangsang pertumbuhan vegetatif tanaman, mempercepat pertumbuhan daun dan batang', 'Pupuk Kaltim | Bahan Aktif: Nitrogen 46% | Sumber nitrogen terbaik untuk tanaman. Efektif meningkatkan pertumbuhan vegetatif dan produksi.', 'urea_46.jpg'),
(5, 2, 1, 'SP-36', 28000.00, 61, 'Pupuk fosfor untuk merangsang pembentukan akar, pembuahan, dan pematangan buah', 'Petrokimia Gresik | Bahan Aktif: P2O5 36% | Pupuk fosfat alam yang diolah. Sangat penting untuk fase pembentukan akar dan pembuahan.', 'sp36.jpg'),
(5, 2, 1, 'KCl 60%', 35000.00, 56, 'Pupuk kalium untuk meningkatkan ketahanan tanaman terhadap penyakit, kualitas buah, dan ketahanan terhadap kering', 'Pupuk Kaltim | Bahan Aktif: K2O 60% | Sumber kalium utama untuk tanaman. Meningkatkan kualitas hasil panen dan ketahanan tanaman.', 'kcl_60.jpg'),
(6, 2, 1, 'Pupuk Kandang Sapi', 15000.00, 61, 'Pupuk organik untuk memperbaiki struktur tanah, meningkatkan kesuburan, dan menyediakan hara lengkap', 'Lokal | Bahan Aktif: NPK alami + Materi Organik | Pupuk organik alami dari kotoran sapi yang telah difermentasi. Aman dan ramah lingkungan.', 'pupuk_kandang_sapi.jpg'),
(6, 2, 1, 'Kompos Premium', 18000.00, 39, 'Pupuk organik untuk memperbaiki struktur tanah, meningkatkan daya simpan air, dan menyediakan hara mikro', 'Green Agro | Bahan Aktif: Materi Organik 40%, C/N 15 | Kompos berkualitas premium dari bahan organik terpilah. Kaya akan mikroba tanah yang bermanfaat.', 'kompos_premium.jpg'),
(7, 1, 1, 'Atonik 6.25 L', 195000.00, 13, 'ZPT untuk merangsang pertumbuhan akar, mempercepat pemulihan tanaman pasca stres, dan meningkatkan hasil panen', 'Asahi Chemical | Bahan Aktif: Sodium Nitrophenolate 6.25% | Zat pengatur tumbuh yang merangsang metabolisme tanaman. Efektif untuk pemulihan pasca stres.', 'atonik_625_l.jpg'),
(7, 3, 1, 'Gibberellin GA3', 220000.00, 93, 'ZPT untuk merangsang pertumbuhan batang, memperpanjang sel batang, dan memacah dormansi biji', 'BASF | Bahan Aktif: Gibberellic Acid 90% | Hormon pertumbuhan alami yang merangsang pembelahan sel dan pemanjangan batang.', 'gibberellin_ga3.jpg'),
(7, 1, 1, 'Ethrel 480 SL', 175000.00, 13, 'ZPT untuk merangsang pematangan buah, pemerahan buah naga, dan pemudaran daun pada tanaman karet', 'Bayer | Bahan Aktif: Ethephon 480 g/L | ZPT yang melepaskan etilen untuk mempercepat pematangan dan pemerahan buah.', 'ethrel_480_sl.jpg'),
(8, 2, 2, 'Furadan 3GR', 68000.00, 91, 'Nematisida dan insektisida granul untuk mengendalikan hama tanah, nematoda, dan penggerek batang', 'FMC | Bahan Aktif: Carbofuran 3% | Bentuk granul yang mudah diaplikasikan pada saat penanaman. Perlindungan jangka panjang.', 'furadan_3gr.jpg'),
(9, 1, 2, 'Metindo 48 EC', 185000.00, 55, 'Akarisida untuk mengendalikan tungau merah (spider mite) pada tanaman cabai, tomat, dan stroberi', 'Syngenta | Bahan Aktif: Abamectin 18 g/L + Bifenazate 300 g/L | Kombinasi dua bahan aktif untuk mengendalikan tungau resisten. Efek kontak dan lambung.', 'metindo_48_ec.jpg'),
(9, 1, 2, 'Vertimec 1.8 EC', 155000.00, 21, 'Akarisida dan insektisida untuk mengendalikan tungau, trips, dan ulat pada tanaman sayuran dan buah-buahan', 'Syngenta | Bahan Aktif: Abamectin 18 g/L | Akarisida alami dari fermentasi bakteri. Efektif terhadap tungau dan hama kecil lainnya.', 'vertimec_18_ec.jpg'),
(2, 1, 2, 'BPMC 50 EC', 88000.00, 13, 'Insektisida karbamat untuk mengendalikan wereng coklat dan wereng hijau pada tanaman padi', 'Hokko | Bahan Aktif: Fenobucarb 500 g/L | Insektisida selektif untuk wereng pada padi. Aman terhadap musuh alami.', 'bpmc_50_ec.jpg'),
(2, 1, 2, 'Confidor 200 SL', 165000.00, 39, 'Insektisida neonicotinoid sistemik untuk mengendalikan kutu daun, kutu kebul, dan trips pada tanaman sayuran dan buah-buahan', 'Bayer | Bahan Aktif: Imidacloprid 200 g/L | Insektisida sistemik generasi baru. Tahan terhadap air hujan dan efektif jangka panjang.', 'confidor_200_sl.jpg'),
(2, 1, 2, 'Polytrin C 440 EC', 135000.00, 99, 'Insektisida kombinasi untuk mengendalikan ulat grayak, kutu daun, dan penggerek batang pada tanaman sayuran dan kapas', 'Hextar | Bahan Aktif: Cypermethrin 200 g/L + Profenofos 400 g/L | Kombinasi piretroid dan organofosfat untuk spektrum pengendalian yang lebih luas.', 'polytrin_c_440_ec.jpg'),
(3, 1, 3, 'Daconil 500 F', 115000.00, 57, 'Fungisida protektif untuk mencegah penyakit bercak daun, antraknosa, dan busuk buah pada tanaman sayuran dan buah-buahan', 'Syngenta | Bahan Aktif: Chlorothalonil 500 g/L | Fungisida protektif klasik dengan daya tahan yang baik terhadap air hujan.', 'daconil_500_f.jpg');

-- Insert target untuk produk baru
INSERT INTO `target` (`id_produk`, `nama_target`, `jenis_target`) VALUES
(11, 'Ulat', 'Hama'),
(11, 'Kutu/Thrips/Tungau', 'Hama'),
(12, 'Ulat', 'Hama'),
(12, 'Kutu/Thrips/Tungau', 'Hama'),
(12, 'Penggerek Batang', 'Hama'),
(13, 'Hawar/Blas/Patah Leher', 'Penyakit'),
(14, 'Ulat', 'Hama'),
(14, 'Kutu/Thrips/Tungau', 'Hama');

-- Test Query Complex dengan JOIN dan Subquery
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

-- Test VIEW
SELECT * FROM view_produk_kategori;
SELECT * FROM view_produk_lengkap WHERE status_stok = 'Tersedia';

-- Test FUNCTION
SELECT nama_produk, harga, stok, hitung_nilai_stok(id_produk) as nilai_stok
FROM produk
ORDER BY nilai_stok DESC
LIMIT 10;
