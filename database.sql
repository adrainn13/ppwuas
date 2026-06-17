-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 16, 2026 at 11:12 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pestisida`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Herbisida', 'Kategori pestisida / agrokimia jenis Herbisida'),
(2, 'Insektisida', 'Kategori pestisida / agrokimia jenis Insektisida'),
(3, 'Fungisida', 'Kategori pestisida / agrokimia jenis Fungisida'),
(4, 'Nematisida', 'Kategori pestisida / agrokimia jenis Nematisida'),
(5, 'Pupuk NPK', 'Kategori pestisida / agrokimia jenis Pupuk NPK'),
(6, 'Pupuk Organik', 'Kategori pestisida / agrokimia jenis Pupuk Organik'),
(7, 'ZPT', 'Kategori pestisida / agrokimia jenis ZPT'),
(8, 'Rodentisida', 'Kategori pestisida / agrokimia jenis Rodentisida'),
(9, 'Akarisida', 'Kategori pestisida / agrokimia jenis Akarisida');

-- --------------------------------------------------------

--
-- Table structure for table `kemasan`
--

CREATE TABLE `kemasan` (
  `id_kemasan` int(11) NOT NULL,
  `nama_kemasan` varchar(100) NOT NULL,
  `volume_kemasan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kemasan`
--

INSERT INTO `kemasan` (`id_kemasan`, `nama_kemasan`, `volume_kemasan`) VALUES
(1, 'Botol / Jerigen', '1 Liter'),
(2, 'Karung / Sak', '1 Kg'),
(3, 'Bungkus / Sachet', '250 Gram');

-- --------------------------------------------------------

--
-- Table structure for table `kondisi_tanaman`
--

CREATE TABLE `kondisi_tanaman` (
  `id_kondisi` int(11) NOT NULL,
  `nama_kondisi` varchar(100) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kondisi_tanaman`
--

INSERT INTO `kondisi_tanaman` (`id_kondisi`, `nama_kondisi`, `keterangan`) VALUES
(1, 'Normal / Masa Pertumbuhan', 'Tanaman dalam kondisi sehat, membutuhkan nutrisi atau perangsang tumbuh.'),
(2, 'Terserang Hama Serangga/Hewan', 'Tanaman menunjukkan gejala kerusakan akibat gigitan, hisapan, atau serangan hama hewan/serangga.'),
(3, 'Terserang Penyakit Jamur/Bakteri', 'Tanaman menunjukkan gejala bercak, busuk, layu, atau hawar akibat infeksi patogen.'),
(4, 'Kompetisi dengan Gulma', 'Lahan budidaya dipenuhi oleh rumput liar atau gulma berdaun lebar/sempit yang mengganggu.');

-- --------------------------------------------------------

--
-- Table structure for table `log_produk`
--

CREATE TABLE `log_produk` (
  `id_log` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `aksi` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `nama_produk_lama` varchar(150) DEFAULT NULL,
  `nama_produk_baru` varchar(150) DEFAULT NULL,
  `harga_lama` decimal(12,2) DEFAULT NULL,
  `harga_baru` decimal(12,2) DEFAULT NULL,
  `stok_lama` int(11) DEFAULT NULL,
  `stok_baru` int(11) DEFAULT NULL,
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_kategori`, `id_kemasan`, `id_kondisi`, `nama_produk`, `harga`, `stok`, `kegunaan`, `deskripsi`, `foto_produk`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 4, 'Roundup 486 SL', 185000.00, 52, 'Herbisida sistemik untuk mengendalikan gulma berdaun lebar dan berumput pada lahan pertanian, kebun, dan perkebunan', 'Monsanto | Bahan Aktif: Glyphosate 480 g/L | Roundup adalah herbisida paling populer di dunia. Bekerja secara sistemik dari daun ke akar, mematikan gulma hingga ke akarnya.', 'roundup_486_sl.jpg', '2026-06-14 04:41:10', '2026-06-14 04:41:10'),
(2, 1, 1, 4, 'Gramoxone 276 SL', 95000.00, 64, 'Herbisida kontak untuk mengendalikan gulma sebelum dan sesudah tumbuh pada tanaman pangan dan perkebunan', 'Syngenta | Bahan Aktif: Paraquat Dichloride 276 g/L | Bekerja sangat cepat sebagai herbisida kontak. Cocok untuk pengendalian gulma pada lahan kering.', 'gramoxone_276_sl.jpg', '2026-06-14 04:41:10', '2026-06-14 04:41:10'),
(3, 1, 1, 4, 'Basmil 500 EC', 75000.00, 90, 'Herbisida selektif untuk pengendalian gulma pada tanaman padi sawah', 'BASF | Bahan Aktif: Butachlor 500 g/L | Herbisida selektif yang aman untuk tanaman padi. Efektif mengendalikan gulma berdaun lebar dan berumput.', 'basmil_500_ec.jpg', '2026-06-14 04:41:10', '2026-06-14 04:41:10'),
(4, 2, 1, 2, 'Decis 2.5 EC', 120000.00, 66, 'Insektisida piretroid untuk mengendalikan hama penggerek batang, ulat grayak, dan thrips pada tanaman padi dan sayuran', 'Bayer | Bahan Aktif: Deltamethrin 25 g/L | Insektisida kontak dan lambung dengan daya residu yang baik. Efektif melawan berbagai jenis hama.', 'decis_25_ec.jpg', '2026-06-14 04:41:10', '2026-06-14 04:41:10'),
(5, 2, 1, 2, 'Karate 2.5 EC', 135000.00, 30, 'Insektisida piretroid untuk mengendalikan hama penggerek batang, ulat, dan kutu pada tanaman padi, sayuran, dan buah-buahan', 'Syngenta | Bahan Aktif: Lambda-cyhalothrin 25 g/L | Insektisida dengan daya racun tinggi dan efek penolakan (repellent). Cepat bekerja dan tahan lama.', 'karate_25_ec.jpg', '2026-06-14 04:41:10', '2026-06-14 04:41:10'),
(6, 2, 1, 2, 'Regent 50 SC', 210000.00, 80, 'Insektisida sistemik untuk mengendalikan hama wereng coklat, penggerek batang, dan hama tanah pada tanaman padi', 'BASF | Bahan Aktif: Fipronil 50 g/L | Insektisida generasi baru dengan mekanisme aksi unik. Sangat efektif terhadap wereng coklat.', 'regent_50_sc.jpg', '2026-06-14 04:41:10', '2026-06-14 04:41:10'),
(7, 3, 2, 3, 'Dithane M-45', 85000.00, 93, 'Fungisida protektif untuk mencegah dan mengendalikan penyakit busuk buah, bercak daun, dan karat pada tanaman sayuran dan buah-buahan', 'Dow AgroSciences | Bahan Aktif: Mancozeb 80% | Fungisida protektif klasik dengan spektrum luas. Aman untuk tanaman dan lingkungan.', 'dithane_m45.jpg', '2026-06-14 04:41:10', '2026-06-14 04:41:10'),
(8, 3, 2, 3, 'Ridomil Gold MZ 68 WG', 165000.00, 78, 'Fungisida sistemik dan protektif untuk mengendalikan penyakit busuk pangkal batang, layu, dan rebah semai', 'Syngenta | Bahan Aktif: Metalaxyl-M 4% + Mancozeb 64% | Kombinasi fungisida sistemik dan protektif untuk perlindungan ganda terhadap penyakit tular tanah.', 'ridomil_gold_mz_68_wg.jpg', '2026-06-14 04:41:10', '2026-06-14 04:41:10'),
(9, 3, 2, 3, 'Antracol 70 WP', 92000.00, 62, 'Fungisida protektif untuk mengendalikan penyakit bercak daun, antraknosa, dan busuk buah pada tanaman cabai, tomat, dan buah-buahan', 'Bayer | Bahan Aktif: Propineb 70% | Fungisida dengan kandungan seng yang memberikan efek nutrisi tambahan pada tanaman.', 'antracol_70_wp.jpg', '2026-06-14 04:41:10', '2026-06-14 04:41:10'),
(10, 4, 2, 2, 'Furadan 3G', 65000.00, 99, 'Nematisida dan insektisida untuk mengendalikan nematoda, hama tanah, dan penggerek batang pada tanaman padi, kentang, dan sayuran', 'FMC | Bahan Aktif: Carbofuran 3% | Bentuk granul yang mudah diaplikasikan. Bekerja sebagai nematisida dan insektisida sistemik.', '6a30f0b9c45c4_1781592249.png', '2026-06-14 04:41:10', '2026-06-16 06:44:09'),
(12, 2, 1, 1, 'Regent', 20000.00, 3, '', '', '6a30f269b9d93_1781592681.png', '2026-06-16 06:51:21', '2026-06-16 06:51:21'),
(13, 2, 1, 2, 'Curacron 500 EC', 145000.00, 46, 'Insektisida organofosfat untuk mengendalikan ulat grayak, kutu daun, dan trips pada tanaman cabai, bawang, dan kapas', 'Syngenta | Bahan Aktif: Profenofos 500 g/L | Insektisida dengan daya racun tinggi dan spektrum luas. Efektif terhadap hama yang resisten.', 'curacron_500_ec.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(14, 2, 1, 2, 'Mars 50 EC', 78000.00, 31, 'Insektisida organofosfat untuk mengendalikan hama penggerek batang, ulat, dan kutu pada tanaman padi dan sayuran', 'Hextar | Bahan Aktif: Chlorpyrifos 500 g/L | Insektisida ekonomis dengan daya racun tinggi. Efektif sebagai insektisida kontak dan lambung.', 'mars_50_ec.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(15, 3, 2, 3, 'Brestan 60 WP', 155000.00, 90, 'Fungisida untuk mengendalikan penyakit blas (hawar daun) pada tanaman padi', 'Bayer | Bahan Aktif: Fentin Acetate 60% | Fungisida spesifik untuk penyakit blas pada padi. Bekerja secara protektif dan kuratif.', 'brestan_60_wp.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(16, 2, 2, 2, 'Orthene 75 SP', 110000.00, 27, 'Insektisida sistemik untuk mengendalikan hama kutu daun, kutu kebul, dan ulat pada tanaman sayuran dan buah-buahan', 'Bayer | Bahan Aktif: Acephate 75% | Insektisida sistemik yang cepat meresap ke seluruh bagian tanaman. Efektif terhadap kutu-kutuan.', 'orthene_75_sp.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(17, 5, 2, 1, 'NPK Mutiara 16-16-16', 45000.00, 20, 'Pupuk majemak lengkap untuk memenuhi kebutuhan hara makro tanaman, meningkatkan pertumbuhan dan produksi', 'Petrokimia Gresik | Bahan Aktif: Nitrogen 16%, Fosfor 16%, Kalium 16% | Pupuk NPK dengan kandungan seimbang. Cocok untuk berbagai jenis tanaman pangan dan hortikultura.', 'npk_mutiara_161616.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(18, 5, 2, 1, 'Urea 46%', 32000.00, 50, 'Pupuk nitrogen untuk merangsang pertumbuhan vegetatif tanaman, mempercepat pertumbuhan daun dan batang', 'Pupuk Kaltim | Bahan Aktif: Nitrogen 46% | Sumber nitrogen terbaik untuk tanaman. Efektif meningkatkan pertumbuhan vegetatif dan produksi.', 'urea_46.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(19, 5, 2, 1, 'SP-36', 28000.00, 61, 'Pupuk fosfor untuk merangsang pembentukan akar, pembuahan, dan pematangan buah', 'Petrokimia Gresik | Bahan Aktif: P2O5 36% | Pupuk fosfat alam yang diolah. Sangat penting untuk fase pembentukan akar dan pembuahan.', 'sp36.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(20, 5, 2, 1, 'KCl 60%', 35000.00, 56, 'Pupuk kalium untuk meningkatkan ketahanan tanaman terhadap penyakit, kualitas buah, dan ketahanan terhadap kering', 'Pupuk Kaltim | Bahan Aktif: K2O 60% | Sumber kalium utama untuk tanaman. Meningkatkan kualitas hasil panen dan ketahanan tanaman.', 'kcl_60.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(21, 6, 2, 1, 'Pupuk Kandang Sapi', 15000.00, 61, 'Pupuk organik untuk memperbaiki struktur tanah, meningkatkan kesuburan, dan menyediakan hara lengkap', 'Lokal | Bahan Aktif: NPK alami + Materi Organik | Pupuk organik alami dari kotoran sapi yang telah difermentasi. Aman dan ramah lingkungan.', 'pupuk_kandang_sapi.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(22, 6, 2, 1, 'Kompos Premium', 18000.00, 39, 'Pupuk organik untuk memperbaiki struktur tanah, meningkatkan daya simpan air, dan menyediakan hara mikro', 'Green Agro | Bahan Aktif: Materi Organik 40%, C/N 15 | Kompos berkualitas premium dari bahan organik terpilah. Kaya akan mikroba tanah yang bermanfaat.', 'kompos_premium.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(23, 7, 1, 1, 'Atonik 6.25 L', 195000.00, 13, 'ZPT untuk merangsang pertumbuhan akar, mempercepat pemulihan tanaman pasca stres, dan meningkatkan hasil panen', 'Asahi Chemical | Bahan Aktif: Sodium Nitrophenolate 6.25% | Zat pengatur tumbuh yang merangsang metabolisme tanaman. Efektif untuk pemulihan pasca stres.', 'atonik_625_l.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(24, 7, 3, 1, 'Gibberellin GA3', 220000.00, 93, 'ZPT untuk merangsang pertumbuhan batang, memperpanjang sel batang, dan memacah dormansi biji', 'BASF | Bahan Aktif: Gibberellic Acid 90% | Hormon pertumbuhan alami yang merangsang pembelahan sel dan pemanjangan batang.', 'gibberellin_ga3.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(25, 7, 1, 1, 'Ethrel 480 SL', 175000.00, 13, 'ZPT untuk merangsang pematangan buah, pemerahan buah naga, dan pemudaran daun pada tanaman karet', 'Bayer | Bahan Aktif: Ethephon 480 g/L | ZPT yang melepaskan etilen untuk mempercepat pematangan dan pemerahan buah.', 'ethrel_480_sl.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(26, 8, 2, 2, 'Furadan 3GR', 68000.00, 91, 'Nematisida dan insektisida granul untuk mengendalikan hama tanah, nematoda, dan penggerek batang', 'FMC | Bahan Aktif: Carbofuran 3% | Bentuk granul yang mudah diaplikasikan pada saat penanaman. Perlindungan jangka panjang.', 'furadan_3gr.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(27, 9, 1, 2, 'Metindo 48 EC', 185000.00, 55, 'Akarisida untuk mengendalikan tungau merah (spider mite) pada tanaman cabai, tomat, dan stroberi', 'Syngenta | Bahan Aktif: Abamectin 18 g/L + Bifenazate 300 g/L | Kombinasi dua bahan aktif untuk mengendalikan tungau resisten. Efek kontak dan lambung.', 'metindo_48_ec.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(28, 9, 1, 2, 'Vertimec 1.8 EC', 155000.00, 21, 'Akarisida dan insektisida untuk mengendalikan tungau, trips, dan ulat pada tanaman sayuran dan buah-buahan', 'Syngenta | Bahan Aktif: Abamectin 18 g/L | Akarisida alami dari fermentasi bakteri. Efektif terhadap tungau dan hama kecil lainnya.', 'vertimec_18_ec.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(29, 2, 1, 2, 'BPMC 50 EC', 88000.00, 13, 'Insektisida karbamat untuk mengendalikan wereng coklat dan wereng hijau pada tanaman padi', 'Hokko | Bahan Aktif: Fenobucarb 500 g/L | Insektisida selektif untuk wereng pada padi. Aman terhadap musuh alami.', 'bpmc_50_ec.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(30, 2, 1, 2, 'Confidor 200 SL', 165000.00, 39, 'Insektisida neonicotinoid sistemik untuk mengendalikan kutu daun, kutu kebul, dan trips pada tanaman sayuran dan buah-buahan', 'Bayer | Bahan Aktif: Imidacloprid 200 g/L | Insektisida sistemik generasi baru. Tahan terhadap air hujan dan efektif jangka panjang.', 'confidor_200_sl.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(31, 2, 1, 2, 'Polytrin C 440 EC', 135000.00, 99, 'Insektisida kombinasi untuk mengendalikan ulat grayak, kutu daun, dan penggerek batang pada tanaman sayuran dan kapas', 'Hextar | Bahan Aktif: Cypermethrin 200 g/L + Profenofos 400 g/L | Kombinasi piretroid dan organofosfat untuk spektrum pengendalian yang lebih luas.', 'polytrin_c_440_ec.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36'),
(32, 3, 1, 3, 'Daconil 500 F', 115000.00, 57, 'Fungisida protektif untuk mencegah penyakit bercak daun, antraknosa, dan busuk buah pada tanaman sayuran dan buah-buahan', 'Syngenta | Bahan Aktif: Chlorothalonil 500 g/L | Fungisida protektif klasik dengan daya tahan yang baik terhadap air hujan.', 'daconil_500_f.jpg', '2026-06-16 08:33:36', '2026-06-16 08:33:36');

-- --------------------------------------------------------

--
-- Table structure for table `target`
--

CREATE TABLE `target` (
  `id_target` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `nama_target` varchar(100) NOT NULL,
  `jenis_target` enum('Hama','Penyakit','Gulma') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `target`
--

INSERT INTO `target` (`id_target`, `id_produk`, `nama_target`, `jenis_target`) VALUES
(1, 1, 'Gulma Berdaun Lebar', 'Gulma'),
(2, 1, 'Gulma Berdaun Sempit (Rumput)', 'Gulma'),
(3, 2, 'Gulma Umum', 'Gulma'),
(4, 3, 'Gulma Umum', 'Gulma'),
(5, 4, 'Ulat', 'Hama'),
(6, 4, 'Kutu/Thrips/Tungau', 'Hama'),
(7, 4, 'Penggerek Batang', 'Hama'),
(8, 5, 'Ulat', 'Hama'),
(9, 5, 'Kutu/Thrips/Tungau', 'Hama'),
(10, 5, 'Penggerek Batang', 'Hama');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_produk_kategori`
-- (See below for the actual view)
--
CREATE TABLE `view_produk_kategori` (
`id_kategori` int(11)
,`nama_kategori` varchar(100)
,`jumlah_produk` bigint(21)
,`total_stok` decimal(32,0)
,`rata_rata_harga` decimal(16,6)
,`harga_terendah` decimal(12,2)
,`harga_tertinggi` decimal(12,2)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_produk_lengkap`
-- (See below for the actual view)
--
CREATE TABLE `view_produk_lengkap` (
`id_produk` int(11)
,`nama_produk` varchar(150)
,`harga` decimal(12,2)
,`stok` int(11)
,`kegunaan` text
,`deskripsi` text
,`nama_kategori` varchar(100)
,`nama_kemasan` varchar(100)
,`volume_kemasan` varchar(50)
,`nama_kondisi` varchar(100)
,`kondisi_keterangan` text
,`target_hama` mediumtext
,`status_stok` varchar(11)
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Structure for view `view_produk_kategori`
--
DROP TABLE IF EXISTS `view_produk_kategori`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_produk_kategori`  AS SELECT `k`.`id_kategori` AS `id_kategori`, `k`.`nama_kategori` AS `nama_kategori`, count(`p`.`id_produk`) AS `jumlah_produk`, sum(`p`.`stok`) AS `total_stok`, avg(`p`.`harga`) AS `rata_rata_harga`, min(`p`.`harga`) AS `harga_terendah`, max(`p`.`harga`) AS `harga_tertinggi` FROM (`kategori` `k` left join `produk` `p` on(`k`.`id_kategori` = `p`.`id_kategori`)) GROUP BY `k`.`id_kategori`, `k`.`nama_kategori` ORDER BY count(`p`.`id_produk`) DESC ;

-- --------------------------------------------------------

--
-- Structure for view `view_produk_lengkap`
--
DROP TABLE IF EXISTS `view_produk_lengkap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_produk_lengkap`  AS SELECT `p`.`id_produk` AS `id_produk`, `p`.`nama_produk` AS `nama_produk`, `p`.`harga` AS `harga`, `p`.`stok` AS `stok`, `p`.`kegunaan` AS `kegunaan`, `p`.`deskripsi` AS `deskripsi`, `k`.`nama_kategori` AS `nama_kategori`, `km`.`nama_kemasan` AS `nama_kemasan`, `km`.`volume_kemasan` AS `volume_kemasan`, `kt`.`nama_kondisi` AS `nama_kondisi`, `kt`.`keterangan` AS `kondisi_keterangan`, group_concat(distinct `t`.`nama_target` separator ', ') AS `target_hama`, CASE WHEN `p`.`stok` = 0 THEN 'Habis' WHEN `p`.`stok` <= 20 THEN 'Stok Rendah' ELSE 'Tersedia' END AS `status_stok`, `p`.`created_at` AS `created_at`, `p`.`updated_at` AS `updated_at` FROM ((((`produk` `p` join `kategori` `k` on(`p`.`id_kategori` = `k`.`id_kategori`)) join `kemasan` `km` on(`p`.`id_kemasan` = `km`.`id_kemasan`)) join `kondisi_tanaman` `kt` on(`p`.`id_kondisi` = `kt`.`id_kondisi`)) left join `target` `t` on(`p`.`id_produk` = `t`.`id_produk`)) GROUP BY `p`.`id_produk` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `kemasan`
--
ALTER TABLE `kemasan`
  ADD PRIMARY KEY (`id_kemasan`);

--
-- Indexes for table `kondisi_tanaman`
--
ALTER TABLE `kondisi_tanaman`
  ADD PRIMARY KEY (`id_kondisi`);

--
-- Indexes for table `log_produk`
--
ALTER TABLE `log_produk`
  ADD PRIMARY KEY (`id_log`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `target`
--
ALTER TABLE `target`
  ADD PRIMARY KEY (`id_target`),
  ADD KEY `fk_target_produk` (`id_produk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `kemasan`
--
ALTER TABLE `kemasan`
  MODIFY `id_kemasan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kondisi_tanaman`
--
ALTER TABLE `kondisi_tanaman`
  MODIFY `id_kondisi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `log_produk`
--
ALTER TABLE `log_produk`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `target`
--
ALTER TABLE `target`
  MODIFY `id_target` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `target`
--
ALTER TABLE `target`
  ADD CONSTRAINT `fk_target_produk` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
