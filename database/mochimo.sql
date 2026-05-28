-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 26, 2026 at 03:20 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mochimo`
--

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `id_banner` int NOT NULL,
  `judul` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id_cart` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_produk` int DEFAULT NULL,
  `id_varian` int DEFAULT NULL,
  `qty` int DEFAULT '1',
  `subtotal` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ukuran` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id_cart`, `id_user`, `id_produk`, `id_varian`, `qty`, `subtotal`, `created_at`, `ukuran`) VALUES
(70, 2, 6, 0, 1, NULL, '2026-05-25 14:54:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int NOT NULL,
  `id_pesanan` int DEFAULT NULL,
  `id_produk` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `subtotal` int DEFAULT NULL,
  `harga_at_purchase` int NOT NULL DEFAULT '0',
  `ukuran` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `warna` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galeri_produk`
--

CREATE TABLE `galeri_produk` (
  `id_galeri` int NOT NULL,
  `id_produk` int DEFAULT NULL,
  `nama_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `galeri_produk`
--

INSERT INTO `galeri_produk` (`id_galeri`, `id_produk`, `nama_file`) VALUES
(1, 22, '1779347796_galeri_0_Screenshot_2026-05-21_121851.png');

-- --------------------------------------------------------

--
-- Table structure for table `gambar_produk`
--

CREATE TABLE `gambar_produk` (
  `id_gambar` int NOT NULL,
  `id_produk` int DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `gambar_produk`
--

INSERT INTO `gambar_produk` (`id_gambar`, `id_produk`, `gambar`) VALUES
(1, 17, '1779212484_0_mkn.png');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ikon` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `gambar`, `created_at`, `ikon`) VALUES
(1, 'Outfit Perempuan', NULL, '2026-05-10 05:28:33', '1779351087_outfit_perempuan.png'),
(2, 'Outfit Laki-Laki', NULL, '2026-05-10 05:28:47', '1779351072_outfit.png'),
(4, 'K-Pop', NULL, '2026-05-10 13:51:28', '1779294467_kpop.png'),
(6, 'Aksesoris', NULL, '2026-05-18 16:17:21', '1779351477_accessories.png'),
(7, 'Baju', NULL, '2026-05-18 16:22:55', '1779351129_shirt.png'),
(8, 'Sepatu', NULL, '2026-05-18 16:23:03', '1779351108_running-shoe.png'),
(9, 'Sandal', NULL, '2026-05-18 16:23:12', '1779351210_flip-flops.png'),
(10, 'Alat Makan', NULL, '2026-05-19 15:30:08', '1779350777_dining.png'),
(11, 'Makeup', NULL, '2026-05-20 16:35:55', '1779294955_cosmetics.png'),
(12, 'Skincare', NULL, '2026-05-20 16:37:26', '1779295046_1779294699_products.png');

-- --------------------------------------------------------

--
-- Table structure for table `konfirmasi_pembayaran`
--

CREATE TABLE `konfirmasi_pembayaran` (
  `id_konfirmasi` int NOT NULL,
  `id_pesanan` int DEFAULT NULL,
  `nama_pengirim` varchar(100) DEFAULT NULL,
  `bank_pengirim` varchar(50) DEFAULT NULL,
  `jumlah_transfer` int DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  `tanggal_konfirmasi` datetime DEFAULT NULL,
  `status` varchar(20) DEFAULT 'menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notifikasi` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `pesan` text,
  `tipe` varchar(100) DEFAULT NULL,
  `status` enum('belum','dibaca') DEFAULT 'belum',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `id_pesanan` int DEFAULT NULL,
  `metode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bukti_pembayaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','verified','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `tanggal_bayar` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `tanggal_pesan` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` int NOT NULL,
  `diskon` int NOT NULL,
  `total_bayar` int DEFAULT NULL,
  `status` enum('menunggu pembayaran','diproses','dikirim','selesai','dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'menunggu pembayaran',
  `alamat_pengiriman` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `metode_pembayaran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nomor_pesanan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bank_tujuan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bukti_pembayaran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_pembayaran` enum('pending','menunggu_verifikasi','dibayar','expired','belum dibayar') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `batas_pembayaran` datetime DEFAULT NULL,
  `waktu_dikirim` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `nomor_pembayaran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `status_tahap` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int NOT NULL,
  `id_kategori` int DEFAULT NULL,
  `nama_produk` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `harga` int NOT NULL,
  `stok` int DEFAULT '0',
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `gambar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rating` decimal(2,1) DEFAULT '0.0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ukuran` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `id_kategori`, `nama_produk`, `harga`, `stok`, `deskripsi`, `gambar`, `rating`, `created_at`, `ukuran`) VALUES
(4, 4, 'Tempat Photocard', 45000, 17, 'tempat ini untuk menyimpan foto-foto pacar kalian', 'binder_kpop.jpg', '0.0', '2026-05-10 14:11:54', 'All Size'),
(5, 2, 'Jam Tangan', 200000, 7, 'jam tangan ini cocok digunakan ketika acara formal\r\n', 'jam_tangan.jpg', '0.0', '2026-05-10 14:12:48', 'L,XL'),
(6, 1, 'Jeans ', 100000, 27, 'jeans ini sedang digemari oleh para cewek-cewek untuk berpergian', 'jeans_pr.jpg', '0.0', '2026-05-10 14:13:54', 'XL'),
(7, 2, 'Sepatu', 250000, 19, 'sepatu ini merupakan sepatu untuk jalan-jalan agar terlihat lebih keren', 'sepatu.jpg', '0.0', '2026-05-10 14:16:41', 'L,XL,XXL'),
(8, 4, 'Lighstick H2H', 980000, 17, 'Ini lightstick official h2h, dan dapatkan photocard acak dari salah satu member', 'lightstick_h2h.jpg', '0.0', '2026-05-10 14:18:19', NULL),
(9, 4, 'Lighstick NCT Dream Ver 2', 1200000, 2, 'ini lighstick vers 2 dari nct dream', 'lightstick_nct2.jpg', '0.0', '2026-05-10 14:19:44', NULL),
(12, 1, 'Set OOTD Kekinian', 149999, 7, 'beli semua lebih murah', 'set_pr.png', '0.0', '2026-05-10 14:47:20', 'All size'),
(15, 6, 'Kipas Portable', 30000, 4, 'Mini fan lucu dan anginnya kencang', 'fan.jfif', '0.0', '2026-05-18 10:06:58', ''),
(16, 8, 'Jeno Sepatu Sneakers Wanita Kasual Sport Shoes Pink 438', 200000, 2, 'Note : \r\n- PASTIKAN WARNA, UKURAN DAN JUMLAH PESANAN SESUAI, KAMI MENGIRIMKAN SESUAI PESANAN PADA SISTEM\r\n - JIKA INGIN MENGUBAH PESANAN ATAU ALAMAT HARAP MENGAJUKAN CANCEL - PASTIKAN ALAMAT PENERIMA SUDAH BENAR \r\n- PASTIKAN NOMOR HP PENERIMA AKTIF Selamat kakak telah masuk kedalam TOKO UTAMA Sepatu TER-NYAMAN di INDONESIA. \r\n\r\nSepatu Sneakers ini sangat cocok untuk kegiatan beraktifitas sehari hari,dengan menggunakan bahan dan material yang baik dan berkualitas,sepatu ini sangatlah nyaman dipakai dan tidak mudah rusak. Detail Produk Nama produk : Sneakers Material atas : Kulit Sintesis high quality Material bawah \r\n', 'Jeno Sepatu Sneakers Wanita Kasual Sport Shoes Pink 438.jfif', '0.0', '2026-05-18 16:31:56', ''),
(17, 10, 'Piring Hello Kitty', 20000, 14, 'Piring lucu dan terjangkau, sangat cocok untuk digunakan sehari-hari\r\n', '1779212484_mkn.png', '0.0', '2026-05-19 17:41:24', NULL),
(19, 12, 'Glad2Glow Niacinamide Serum Facial Wash 70ml Brightening', 80000, 10, 'Spesifikasi Produk\r\nKategori\r\nShopee\r\nicon arrow right\r\nPerawatan & Kecantikan\r\nicon arrow right\r\nPerawatan Wajah\r\nicon arrow right\r\nPembersih Wajah\r\nMerek\r\nGlade2Glow\r\nBerat Produk\r\n200g\r\nKomposisi\r\nCeramides, amino acid surfactants, ectoin + allantoin, ekstrak bunga sakura\r\nJenis Kulit\r\nSemua Jenis Kulit, Kusam, all skin type,dull\r\nManfaat Perawatan Kulit\r\nBerminyak, Whitening, Oil Control, Glowing, Cleansing\r\nUkuran Produk\r\nTravel Size\r\nNegara Asal\r\nIndonesia\r\nFormulasi\r\nKrim\r\nProduk Custom\r\nTidak\r\nTipe Paket\r\nSatuan\r\nJenis Edisi\r\nEdisi Reguler\r\nMasa Penyimpanan\r\nsebelu dibuka masa penyimpanan 2 tahun\r\nIngredient (Komposisi)\r\ncentella asiatica、salicylic acid、niacinamide\r\nJumlah Produk Dalam Kemasan\r\n1\r\nUkuran Per Produk\r\n70ML\r\nNo. Izin Edar (BPOM, PIRT)\r\n-\r\nDikirim Dari\r\nKAB. SLEMAN\r\nDeskripsi Produk\r\nGLAD2GLOW NIACINAMIDE SERUM FACIAL WASH BRIGHT GLOWING SAKURA FLOWER G2G SABUN MUKA FACE WASH BRIGHTENING GLAD 2 GLOW DEEP PORE HYDRATING SOTTHING', '1779295217_utama_Glad2Glow_Niacinamide_Serum_Facial_Wash_70ml_Brightening.jfif', '0.0', '2026-05-20 16:40:17', NULL),
(22, 12, 'SKINTIFIC - Niacinamide Brightening Essence Toner 80ml | Glowing Brightenin Toner Eksfoliasi Wajah', 150000, 1, 'Niacinamide Brightening Essence Toner\r\nBrightening toner with Triple Brightening Agents: the perfect combination of Niacinamide, Alpha Arbutin, and Tranexamic acid for maximum effect. Easy-to-absorb, watery texture but gives maximum hydration on the skin with a fresh feeling. Works as an effective brightening booster for maximum brightening effect when used together with Niacinamide Serum and MSH Niacinamide Moisturizer.\r\nSize: 80 ml \r\nNo BPOM: NA11231201016', '1779347796_utama_Screenshot_2026-05-21_121821.png', '0.0', '2026-05-21 07:16:36', NULL),
(23, 12, 'Hanasui Glow Expert Series', 50000, 9, '1. Hanasui Glow Expert Gel Cleanser\r\nPembersih wajah bertekstur gel lembut yang dirancang untuk membersihkan kulit tanpa merusak skin barrier.\r\nFungsi Utama: Membersihkan kotoran, sisa makeup, dan minyak berlebih secara mendalam.\r\nKandungan Unggulan:\r\nMild Surfactants: Membersihkan dengan lembut tanpa efek ketarik atau kering setelah cuci muka.\r\nGlycerin & Betaine: Menjaga kelembapan alami kulit.\r\nNiacinamide: Membantu mencerahkan kulit secara bertahap.\r\nKeunggulan: Formula pH seimbang yang aman bagi kulit sensitif dan tidak mengandung sabun keras.\r\n2. Hanasui Glow Expert Essence\r\nProduk langkah kedua setelah pembersihan (sebelum serum/pelembap) untuk menyiapkan kulit agar nutrisi produk selanjutnya terserap lebih maksimal.\r\nFungsi Utama: Memberikan hidrasi intensif, menenangkan kulit, dan mencerahkan tampilan wajah secara instan.\r\nKandungan Unggulan:\r\nHyaluronic Acid: Mengunci kadar air di dalam kulit agar tetap kenyal sepanjang hari.\r\nVitamin E & B3: Sebagai antioksidan dan agen pencerah kulit.\r\nNatural Extract (ex: Aloe Vera/Centella): Menenangkan iritasi ringan dan memberikan efek menyegarkan.\r\nKeunggulan: Tekstur cair yang ringan dan cepat meresap, memberikan rasa dingin dan nyaman saat diaplikasikan.\r\nCara Penggunaan untuk Hasil Optimal\r\nCleansing: Aplikasikan Glow Expert Gel Cleanser pada wajah yang basah, pijat lembut dengan gerakan melingkar, lalu bilas dengan air bersih. Gunakan dua kali sehari (pagi dan malam).\r\nEssence: Tuangkan Glow Expert Essence ke telapak tangan atau kapas, lalu tepuk-tepuk secara perlahan ke seluruh wajah dan leher yang sudah bersih hingga meresap. Lanjutkan dengan penggunaan serum atau moisturizer.', '1779717811_utama_Screenshot_2026-05-21_122139.png', '0.0', '2026-05-25 14:03:31', NULL),
(24, 7, 'Men Cotton Cartoon & Letter Graphic Tee', 70000, 9, 'Tampil Stylish & Casual dengan Koleksi Grafis Terbaru dari Mochimo!\r\nLengkapi outfit harianmu dengan Cotton Cartoon & Letter Graphic Tee yang dirancang khusus untuk kamu yang ingin tampil beda namun tetap nyaman. Kaos ini memadukan desain grafis modern dengan typography unik, memberikan kesan streetwear yang trendy dan kekinian.\r\nMengapa Kamu Harus Punya Kaos Ini?\r\nBahan Premium: Terbuat dari 100% Cotton Combed 30s yang lembut, menyerap keringat, dan tidak panas saat dipakai beraktivitas seharian.\r\nHigh-Quality Print: Sablon grafis menggunakan teknik Digital Printing terbaru, warna tajam, tidak mudah pecah, dan tahan lama meski dicuci berkali-kali.\r\nPotongan Nyaman: Cuttingan Regular Fit yang pas di badan, memberikan ruang gerak bebas bagi kamu yang aktif.\r\nVersatile: Sangat mudah dipadukan dengan celana jeans, cargo, atau chinos untuk tampilan santai maupun hangout bersama teman.\r\n\r\nSpesifikasi Produk:\r\nMaterial: Cotton Combed 30s (Adem & Lembut)\r\nSablon: High-Quality Screen Print / DTF (Awet & Halus)\r\nJenis Kerah: Round Neck (O-Neck)\r\nSize: S, M, L, XL, XXL (Lihat panduan ukuran di gambar)\r\n\r\nPanduan Ukuran (Panjang x Lebar):\r\nS: 66cm x 46cm\r\nM: 69cm x 49cm\r\nL: 72cm x 52cm\r\nXL: 75cm x 55cm\r\nXXL: 78cm x 58cm', '1779806067_utama_Men_Cotton_Cartoon___Letter_Graphic_Tee.jfif', '0.0', '2026-05-26 14:34:27', NULL),
(25, 7, 'Kaos wanita Korea Kaos Oversized 100% katun Motif kapibara lucu Kaos longgar wanita oversized', 79500, 7, 'Tampil chic dan santai dengan Kaos Oversized Korean Style kami! Didesain khusus untuk kamu yang menyukai kenyamanan maksimal tanpa mengesampingkan gaya. Kaos ini memiliki potongan loose (longgar) yang memberikan kesan jenjang dan modis, sempurna dipadukan dengan celana high-waisted, legging, atau biker shorts.\r\n\r\nDaya tarik utamanya ada pada grafis Capybara yang menggemaskan, memberikan sentuhan playful dan aesthetic ala street style Korea yang sedang tren saat ini.\r\n\r\n[Keunggulan Produk]\r\n100% Katun Premium: Bahan kaos sangat lembut, adem, dan menyerap keringat dengan baik. Sangat cocok untuk iklim tropis.\r\n\r\nDesain Oversized: Potongan dropped shoulder yang memberikan siluet santai dan kekinian.\r\n\r\nGrafis Berkualitas: Motif Capybara dicetak dengan teknik printing kualitas tinggi, tidak mudah pecah, dan tahan lama meski dicuci berkali-kali.\r\n\r\nVersatile: Bisa digunakan untuk hangout, kuliah, olahraga ringan, maupun loungewear di rumah.\r\n\r\n[Spesifikasi]\r\nBahan: 100% Cotton Combed (Lembut & Tidak Menerawang)\r\nModel: Oversized Fit / Loose\r\nMotif: Capybara Lucu (Korean Illustration Style)\r\nVarian Warna: [Contoh: Putih / Cream / Sage Green]\r\nUkuran: All Size fit to XL (Detail: Panjang Baju 70cm, Lingkar Dada 110cm)\r\n\r\n[Perawatan Produk]\r\nAgar kaos Capybara kesayanganmu tetap awet:\r\nBalik kaos saat dicuci (bagian luar di dalam).\r\nJangan gunakan pemutih pakaian.\r\nSetrika pada suhu sedang dan jangan menyetrika tepat di atas gambar (balik bagian dalam).\r\nHindari penggunaan mesin pengering (cukup jemur di tempat teduh/berangin).\r\n\r\n[Catatan Toko]\r\nFoto produk adalah realpict (95% kemiripan dengan asli, perbedaan bisa terjadi karena pencahayaan layar).\r\nMohon lakukan video unboxing saat paket diterima untuk klaim jika terjadi kerusakan/cacat produk.\r\nTambahkan ke keranjang sekarang dan lengkapi koleksi Korean look kamu dengan si Capybara lucu ini!', '1779807429_utama_Screenshot_2026-05-26_213619.png', '0.0', '2026-05-26 14:57:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id_review` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_produk` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `komentar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracking`
--

CREATE TABLE `tracking` (
  `id_tracking` int NOT NULL,
  `id_pesanan` int NOT NULL,
  `keterangan` text NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `waktu` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tracking`
--

INSERT INTO `tracking` (`id_tracking`, `id_pesanan`, `keterangan`, `lokasi`, `waktu`) VALUES
(1, 1, 'Pesanan telah diterima oleh pembeli.', 'Alamat Pembeli', '2026-05-14 04:21:38'),
(2, 10, 'Pesanan telah diserahkan ke kurir.', 'Hub Transit', '2026-05-14 07:21:08'),
(3, 11, 'Pesanan telah diserahkan ke kurir.', 'Hub Transit', '2026-05-14 07:28:36'),
(4, 11, 'Pesanan telah diterima oleh customer', 'Pesanan Selesai', '2026-05-14 07:42:15'),
(5, 12, 'Pesanan telah diserahkan ke kurir.', 'Hub Transit', '2026-05-14 08:16:34'),
(6, 12, 'Pesanan telah diterima oleh customer', 'Pesanan Selesai', '2026-05-14 08:25:46'),
(7, 17, 'Pesanan telah diserahkan ke kurir.', 'Hub Transit', '2026-05-16 04:57:01'),
(8, 17, 'Pesanan telah diterima oleh customer', 'Pesanan Selesai', '2026-05-16 04:59:49'),
(9, 23, 'Pesanan telah diserahkan ke kurir.', 'Hub Transit', '2026-05-16 06:03:08'),
(10, 18, 'Pesanan telah diserahkan ke kurir.', 'Hub Transit', '2026-05-19 15:29:54'),
(11, 44, 'Pesanan telah diserahkan ke kurir.', 'Hub Transit', '2026-05-20 06:03:53'),
(12, 53, 'Pesanan telah diserahkan ke kurir.', 'Hub Transit', '2026-05-20 17:09:45'),
(13, 54, 'Pesanan telah diserahkan ke kurir.', 'Hub Transit', '2026-05-20 17:09:56'),
(14, 44, 'Paket transit di DC Cakung', 'DC Cakung', '2026-05-20 17:26:00'),
(15, 44, 'Pesanan sudah sampai', 'Pesanan Telah Tiba', '2026-05-20 17:32:31'),
(16, 44, 'Pesanan telah selesai\r\n', 'selesai', '2026-05-20 17:37:53'),
(17, 44, 'Pesanan telah dikonfirmasi diterima oleh pembeli', 'Customer', '2026-05-20 17:54:10'),
(18, 53, 'dalam perjalanan\r\n', 'DC Cakung', '2026-05-21 04:28:16'),
(19, 59, 'Pesanan telah diserahkan ke kurir.', 'Hub Transit', '2026-05-21 04:28:39'),
(20, 53, 'Pesanan telah sampai di alamat tujuan', 'selesai', '2026-05-21 04:31:11'),
(21, 53, 'Pesanan telah dikonfirmasi diterima oleh pembeli', 'Customer', '2026-05-21 04:31:32'),
(22, 59, 'Paket diproses kargo bandara', 'Bandara Cirebon', '2026-05-21 05:29:51'),
(23, 59, 'Paket transit di bandara', 'Bandara Jakarta', '2026-05-21 05:30:00'),
(24, 59, 'Paket segera diantar oleh kurir', 'Kurir Express', '2026-05-21 05:30:07'),
(25, 59, 'Paket diterima oleh penerima', 'Tujuan', '2026-05-21 05:30:16'),
(26, 59, 'Pesanan telah dikonfirmasi diterima oleh pembeli', 'Customer', '2026-05-21 05:34:09'),
(27, 58, 'Pesanan telah diserahkan ke kurir.', 'Hub Transit', '2026-05-21 08:18:13'),
(28, 58, 'Paket telah diterima di Hub Cirebon', 'Hub Cirebon', '2026-05-21 08:18:45'),
(29, 58, 'Paket dalam proses sortir di Bandung', 'Sortir Bandung', '2026-05-21 08:18:54'),
(30, 58, 'Paket dalam perjalanan ke gudang terdekat', 'Kurir Transit', '2026-05-21 08:19:06'),
(31, 58, 'Paket sampai di alamat tujuan', 'Tujuan', '2026-05-21 08:19:13'),
(32, 58, 'Pesanan telah dikonfirmasi diterima oleh pembeli', 'Customer', '2026-05-21 08:30:13'),
(33, 51, 'Paket sedang disortir di Hub Transit.', 'Hub Transit', '2026-05-25 15:26:17'),
(34, 51, 'Paket sedang diantar oleh kurir ke alamat Anda.', 'Kurir', '2026-05-25 15:26:25'),
(35, 51, 'Paket telah diterima pembeli.', 'Tujuan', '2026-05-25 15:26:30'),
(36, 76, 'Paket sedang disortir di Hub Transit.', 'Hub Transit', '2026-05-25 15:26:38');

-- --------------------------------------------------------

--
-- Table structure for table `ukuran_produk`
--

CREATE TABLE `ukuran_produk` (
  `id_ukuran` int NOT NULL,
  `id_produk` int DEFAULT NULL,
  `ukuran` varchar(10) DEFAULT NULL,
  `stok` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ukuran_produk`
--

INSERT INTO `ukuran_produk` (`id_ukuran`, `id_produk`, `ukuran`, `stok`) VALUES
(1, 16, '34', 0),
(2, 16, '35', 0),
(3, 16, '36', 0),
(4, 16, '37', 0),
(5, 16, '38', 0),
(6, 16, '39', 1),
(7, 16, '40', 6),
(8, 16, '41', 1),
(9, 16, '42', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_hp` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `foto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('admin','customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gender` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `no_hp`, `alamat`, `foto`, `role`, `created_at`, `gender`) VALUES
(1, 'Admin', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', NULL, NULL, NULL, 'admin', '2026-05-10 04:49:53', NULL),
(2, 'karina', 'kn@gmail.com', '$2y$10$pRPHoAwJEgae1Rkht8yej.a.tsuMVH0Z5Xr6XVMTs.SYuY49yZ9ZO', '08112354567', 'Jl Kandang Perahu Nusa Indah', '1779352757_karina.png', 'customer', '2026-05-10 08:40:51', 'Perempuan');

-- --------------------------------------------------------

--
-- Table structure for table `varian_produk`
--

CREATE TABLE `varian_produk` (
  `id_varian` int NOT NULL,
  `id_produk` int NOT NULL,
  `nama_varian` varchar(255) DEFAULT NULL,
  `ukuran` varchar(50) DEFAULT NULL,
  `warna` varchar(50) DEFAULT NULL,
  `stok` int DEFAULT '0',
  `gambar_varian` varchar(255) DEFAULT NULL,
  `harga_varian` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `varian_produk`
--

INSERT INTO `varian_produk` (`id_varian`, `id_produk`, `nama_varian`, `ukuran`, `warna`, `stok`, `gambar_varian`, `harga_varian`) VALUES
(31, 12, NULL, 'M', 'Pink', 4, '', 0),
(32, 12, NULL, 'L', 'Pink', 1, '', 0),
(52, 24, '', 'M', '', 15, NULL, 60000),
(53, 24, '', 'S', '', 4, NULL, 55000),
(54, 24, '', 'L', '', 8, NULL, 75000),
(55, 24, '', 'XL', '', 3, NULL, 85000),
(56, 24, '', 'XXL', '', 6, NULL, 89999),
(60, 25, '', 'L', 'Putih', 8, '1779807429_variant_0_Screenshot_2026-05-26_214924.png', 80000),
(61, 25, '', 'M', 'Hitam', 10, '1779807429_variant_1_Screenshot_2026-05-26_214903.png', 75000),
(62, 25, '', 'L', 'Pink', 7, '1779807429_variant_2_Screenshot_2026-05-26_214842.png', 85000),
(65, 23, 'Hanasui Glow Expert Gel Cleanser', '', '', 4, '1779717811_variant_0_1779341834_variant_1_Screenshot_2026-05-21_122245.png', 28500),
(66, 23, 'Hanasui Glow Expert Essence', '', '', 3, '1779717811_variant_1_1779341834_variant_0_Screenshot_2026-05-21_122234.png', 30000);

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `id_voucher` int NOT NULL,
  `kode_voucher` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `diskon` int DEFAULT NULL,
  `minimal_belanja` int DEFAULT NULL,
  `expired` date DEFAULT NULL,
  `status` enum('aktif','nonaktif') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`id_voucher`, `kode_voucher`, `diskon`, `minimal_belanja`, `expired`, `status`) VALUES
(1, '12345', 20, 50000, '2026-05-13', 'nonaktif'),
(4, '777', 17, 50000, '2026-05-23', 'aktif'),
(5, '230400', 23, 100000, '2026-05-30', 'aktif'),
(6, 'Eid Al-Adha', 10, 100000, '2026-05-27', 'aktif');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id_wishlist` int NOT NULL,
  `id_user` int DEFAULT NULL,
  `id_produk` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id_banner`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `galeri_produk`
--
ALTER TABLE `galeri_produk`
  ADD PRIMARY KEY (`id_galeri`);

--
-- Indexes for table `gambar_produk`
--
ALTER TABLE `gambar_produk`
  ADD PRIMARY KEY (`id_gambar`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `konfirmasi_pembayaran`
--
ALTER TABLE `konfirmasi_pembayaran`
  ADD PRIMARY KEY (`id_konfirmasi`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notifikasi`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `tracking`
--
ALTER TABLE `tracking`
  ADD PRIMARY KEY (`id_tracking`);

--
-- Indexes for table `ukuran_produk`
--
ALTER TABLE `ukuran_produk`
  ADD PRIMARY KEY (`id_ukuran`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `varian_produk`
--
ALTER TABLE `varian_produk`
  ADD PRIMARY KEY (`id_varian`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`id_voucher`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id_wishlist`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_produk` (`id_produk`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `id_banner` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `galeri_produk`
--
ALTER TABLE `galeri_produk`
  MODIFY `id_galeri` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gambar_produk`
--
ALTER TABLE `gambar_produk`
  MODIFY `id_gambar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `konfirmasi_pembayaran`
--
ALTER TABLE `konfirmasi_pembayaran`
  MODIFY `id_konfirmasi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notifikasi` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id_review` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracking`
--
ALTER TABLE `tracking`
  MODIFY `id_tracking` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `ukuran_produk`
--
ALTER TABLE `ukuran_produk`
  MODIFY `id_ukuran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `varian_produk`
--
ALTER TABLE `varian_produk`
  MODIFY `id_varian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `voucher`
--
ALTER TABLE `voucher`
  MODIFY `id_voucher` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id_wishlist` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE;

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Constraints for table `ukuran_produk`
--
ALTER TABLE `ukuran_produk`
  ADD CONSTRAINT `ukuran_produk_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Constraints for table `varian_produk`
--
ALTER TABLE `varian_produk`
  ADD CONSTRAINT `varian_produk_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
