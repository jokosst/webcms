-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 23, 2026 at 01:36 PM
-- Server version: 12.1.2-MariaDB
-- PHP Version: 8.3.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `allow_comments` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `seo_meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`seo_meta`)),
  `views_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `category_id`, `title`, `slug`, `featured_image`, `excerpt`, `content`, `status`, `allow_comments`, `published_at`, `seo_meta`, `views_count`, `created_at`, `updated_at`) VALUES
(4, 1, 6, 'Pelaksanaan Penyeliaan Pengawas TKA 2025', 'sosialisasi-dan-monitoring-cTb9', 'articles/DwdU0WrwltFQzvjqriNw8wRCNap2pswPtptoy50g.png', 'TKA UNTAN', '<p>-</p>\r\n<p>&nbsp;</p>', 'published', 0, '2025-08-05 09:58:00', '{\"meta_title\":null,\"meta_description\":null,\"meta_keywords\":null}', 9, '2026-08-05 09:58:15', '2026-08-14 07:03:28'),
(5, 1, 6, 'Pelaksanaan Tes Susbtantif PPG Calon Guru Tahun 2025', 'pelaksanaan-tes-susbtantif-ppg-calon-guru-tahun-2025-uZFO', 'articles/sb1vCKi5nGhlxYjEYcYPIC6kr4DCqiqSPRqnRKFn.png', 'PPG Untan', '<p>Pelaksanaan test berlangsung selama 4 hari yaitu dari tanggal 12 &ndash; 15 November 2025</p>', 'published', 0, '2025-08-11 02:25:00', '{\"meta_title\":null,\"meta_description\":null,\"meta_keywords\":null}', 20, '2026-08-11 02:25:52', '2026-08-15 20:32:31'),
(6, 1, 6, 'Sebelum Tes UTBK, Yuk Intip Ruangan UTBK di Universitas Tanjungpura (UNTAN)', 'sebelum-tes-utbk-yuk-intip-ruangan-utbk-di-universitas-tanjungpura-untan-emkc', 'articles/HalpirVSuVjKDDZfn5mrLHjkxpZigvMFBGYOljOj.png', 'Ruangan UTBK Untan', '<p><strong>Halo para pejuang UTBK 2021</strong>, Ujian Tulis Berbasis Komputer (UTBK) tahun 2021 akan dilaksanakan pada tanggal 12 &ndash; 18 April 2021 (gelombang 1) dan 26 April &ndash; 02 Mei 2021 (gelombang 2) di Universitas Tanjungpura (UNTAN). Tidak terasa kan, hanya tinggal hitungan hari. Bagi kalian para peserta UTBK yang akan melaksanakan tes UTBK di UNTAN (Universitas Tanjungpura), ada baiknya untuk mengetahui ruangan yang akan kamu gunakan saat tes nanti sesuai dengan yang tertera di kartu peserta UTBK yang sudah kalian download.</p>', 'published', 0, '2026-08-09 02:26:00', '{\"meta_title\":null,\"meta_description\":null,\"meta_keywords\":null}', 20, '2026-08-11 02:26:52', '2026-08-17 19:20:03');

-- --------------------------------------------------------

--
-- Table structure for table `article_comments`
--

CREATE TABLE `article_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `article_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `is_admin_reply` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'article',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `type`, `description`, `created_at`, `updated_at`) VALUES
(5, 'Edukasi', 'edukasi-qKsx', 'article', NULL, '2026-08-02 09:38:26', '2026-08-02 09:38:26'),
(6, 'Berita', 'berita-LCV9', 'article', NULL, '2026-08-05 01:58:46', '2026-08-05 01:58:46'),
(19, 'Sistem dan Aplikasi', 'sistem-dan-aplikasi-0u02', 'service', NULL, '2026-08-11 01:44:30', '2026-08-11 01:44:30'),
(20, 'Jaringan Internet', 'jaringan-internet-toS2', 'service', NULL, '2026-08-11 01:44:39', '2026-08-11 01:44:39'),
(21, 'Data Center', 'data-center-GVsa', 'service', NULL, '2026-08-11 01:44:47', '2026-08-11 01:44:47'),
(22, 'Sistem Informasi Kampus', 'sistem-informasi-kampus', 'portfolio', 'Aplikasi & Portal Sistem Informasi Akademik', '2026-08-11 02:54:49', '2026-08-11 02:54:49'),
(23, 'Internal Untan', 'internal-untan-m3Eq', 'portfolio', NULL, '2026-08-14 07:54:36', '2026-08-14 07:54:36');

-- --------------------------------------------------------

--
-- Table structure for table `company_profiles`
--

CREATE TABLE `company_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `announcement_ticker` text DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `favicon_path` varchar(255) DEFAULT NULL,
  `about_us` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `mission` text DEFAULT NULL,
  `history` text DEFAULT NULL,
  `stat_population` varchar(255) DEFAULT NULL,
  `stat_area` varchar(255) DEFAULT NULL,
  `stat_subdistricts` varchar(255) DEFAULT NULL,
  `stat_umkm` varchar(255) DEFAULT NULL,
  `leader_name` varchar(255) DEFAULT NULL,
  `leader_position` varchar(255) DEFAULT NULL,
  `leader_term` varchar(255) DEFAULT NULL,
  `leader_photo_path` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `google_maps_embed` text DEFAULT NULL,
  `social_media` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_media`)),
  `related_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`related_links`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_profiles`
--

INSERT INTO `company_profiles` (`id`, `company_name`, `tagline`, `announcement_ticker`, `logo_path`, `favicon_path`, `about_us`, `vision`, `mission`, `history`, `stat_population`, `stat_area`, `stat_subdistricts`, `stat_umkm`, `leader_name`, `leader_position`, `leader_term`, `leader_photo_path`, `email`, `phone`, `whatsapp`, `address`, `google_maps_embed`, `social_media`, `related_links`, `created_at`, `updated_at`) VALUES
(1, 'UPA. TIK', 'Universitas Tanjungpura', 'Jam Pelayanan Senin - Jum\'at, 08.00 - 15.00 WIB, untuk Sabtu dan Minggu: Tutup', 'company/prnnTYAdaMb501zLgzXtr8rhdcCj4OijA7dVenQb.png', 'company/DSN0HA00po7955bZdkQhEIUOe76JWGAPYzlxDiYY.png', 'Perkembangan teknologi informasi telah mendorong perguruan tinggi di seluruh dunia untuk beradaptasi dan berinovasi, tidak lagi hanya menjadikan TI sebagai penunjang, tetapi sebagai business enabler yang mendukung kinerja akademik, riset, dan pengabdian kepada masyarakat. UNTAN melalui UPA. TIK ini telah menetapkan arah strategis untuk menghadirkan ekosistem digital yang inovatif dan relevan dalam mendukung tri dharma perguruan tinggi, sekaligus memperkuat posisi UNTAN dalam kancah persaingan global.\r\nGuna mendukung visi UNTAN tersebut, maka maka Unit Pendukung Akademik Teknologi Informasi dan Komunikasi (UPA TIK) terus berkomitmen untuk mengembangkan layanan TI yang handal kepada seluruh pengguna layanan TI UNTAN.', '🚀 Membangun Ekosistem Digital dan Inovasi Menuju Universitas Siber.', '🟢 Menyelenggarakan pendidikan, penelitian, dan pengabdian kepada masyarakat secara Unggul (Excellent) dan Berdaya Saing Internasional (World Class University), yang menghasilkan luaran bermoral Pancasila.\r\n\r\n🟢 Mampu bersaing secara regional dan global, serta dapat mengembangkan dan memajukan ilmu pengetahuan, teknologi dan seni bertarap nasional, regional dan global.', NULL, '19 Pegawai', NULL, NULL, '50+', 'Novi Safriadi, S.T., M.T', 'Kepala UPA. TIK UNTAN', '2024 - 2028', 'company/3CyGYd2rsF0BoDF24LX0rrbdHtlDERVjNEJLrAtx.png', 'tik@untan.ac.id', '0858-2270-0959', '0858-2270-0959', NULL, '<iframe src=\"https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4161.253384745201!2d109.3442586!3d-0.060493!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e1d5990404808a1%3A0x4cdd74e96335678e!2sUPT.%20TIK%20UNTAN!5e1!3m2!1sid!2sid!4v1786440958489!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"strict-origin-when-cross-origin\"></iframe>', '{\"facebook\":\"https:\\/\\/facebook.com\\/tikuntan\",\"instagram\":\"https:\\/\\/instagram.com\\/tikuntan\",\"linkedin\":\"https:\\/\\/linkedin.com\\/company\\/tikuntan\",\"youtube\":\"https:\\/\\/youtube.com\\/@tikuntan\",\"x\":null}', '[{\"title\":\"Portal Utama Universitas\",\"url\":\"https:\\/\\/untan.ac.id\"},{\"title\":\"Sistem Informasi Akademik (SEVIMA)\",\"url\":\"https:\\/\\/satu.untan.ac.id\"},{\"title\":\"Perpustakaan Untan\",\"url\":\"https:\\/\\/perpustakaan.untan.ac.id\"},{\"title\":\"Laboratorium Terpadu\",\"url\":\"https:\\/\\/upt-labterpadu.untan.ac.id\"},{\"title\":\"Portal ULTRA\",\"url\":\"https:\\/\\/portal.untan.ac.id\\/\"}]', '2026-08-02 08:26:40', '2026-08-17 22:19:37');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Bagaimana cara mendapatkan atau mengaktifkan akun SSO / email resmi fakultas?', 'Akun SSO (Single Sign-On) dan email fakultas terbuat secara otomatis saat mahasiswa baru/dosen/tendik terdaftar resmi. Detail aktivasi dan kata sandi sementara dikirimkan ke email pribadi yang terdaftar saat registrasi awal. Jika belum menerima, silakan datang ke Layanan TIK dengan membawa Kartu Identitas (KTM/KTP/NIP).', 1, 1, '2026-08-05 05:59:02', '2026-08-11 01:06:52'),
(2, 'Saya lupa kata sandi akun SIAKAD / Email / LMS. Bagaimana cara meresetnya?', 'Anda dapat menggunakan fitur \"Lupa Password\" pada halaman login masing-masing sistem. Jika nomor ponsel atau email pemulihan tidak terakses, silakan ajukan permohonan reset password melalui Portal Desk Layanan TIK atau hadir langsung ke Helpdesk TIK Untan dengan membawa kartu identitas.', 2, 1, '2026-08-05 05:59:02', '2026-08-11 03:40:59'),
(3, 'Bagaimana cara mengajukan pembuatan sistem/aplikasi baru untuk unit/prodi di lingkungan fakultas?', 'Pengajuan pengembangan aplikasi baru dilakukan dengan mengirimkan Surat Permohonan / Usulan Pembuatan Sistem dari Pimpinan Unit/Ketua Program Studi yang ditujukan kepada Kepala Unit TIK Untan, dilampirkan dengan dokumen kebutuhan fungsional (KFR) singkat.', 3, 1, '2026-08-05 05:59:02', '2026-08-11 03:41:10'),
(4, 'Ke mana saya harus melapor jika terjadi gangguan TIK?', 'Anda dapat menghubungi Unit TIK melalui: Portal Tiket / Helpdesk, Lokasi Physical Helpdesk: Gedung UPA. TIK Untan, Jam Operasional: Senin – Jumat | 08.00 – 16.00 WIB', 4, 1, '2026-08-11 01:10:02', '2026-08-11 01:10:34');

-- --------------------------------------------------------

--
-- Table structure for table `hero_banners`
--

CREATE TABLE `hero_banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `badge_text` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hero_banners`
--

INSERT INTO `hero_banners` (`id`, `badge_text`, `title`, `subtitle`, `image_path`, `button_text`, `button_link`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(3, 'Pusat Operasional Teknologi Informasi & Komunikasi', 'Selamat Datang di Website', 'UPA. TIK UNTAN Merupakan pengelolaan terpadu sistem aplikasi web, infrastruktur jaringan fiber & wireless, ruang komputer/lab kompetensi, serta server multi-cloud enterprise.', 'banners/j3S25c7uQNcRBDgZLZLHpBGSQhIcunV9MaNC5mv8.jpg', 'Selengkapnya', '#layanan', 1, 1, '2026-08-11 01:27:37', '2026-08-14 10:18:28'),
(4, 'Pusat Operasional Teknologi Informasi & Komunikasi', 'UPA. TIK UNTAN', 'Pengelolaan terpadu sistem aplikasi web, infrastruktur jaringan fiber & wireless, ruang komputer/lab kompetensi, serta server multi-cloud enterprise.', 'banners/socBZ6Oz5MKm8uy54zhi6U2Z3Vz6kF7HkZOiI6iY.jpg', 'Selengkapnya', '#helpdesk', 1, 1, '2026-08-11 01:28:19', '2026-08-11 01:34:22');

-- --------------------------------------------------------

--
-- Table structure for table `inbox_leads`
--

CREATE TABLE `inbox_leads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read','replied') NOT NULL DEFAULT 'unread',
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inbox_leads`
--

INSERT INTO `inbox_leads` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `ip_address`, `created_at`, `updated_at`) VALUES
(3, 'fsdgfs', 'fa@gmail.com', '082255535084', 'Perbaikan Sistem Aplikasi Web', 'fsdfs', 'unread', '127.0.0.1', '2026-08-18 20:51:45', '2026-08-18 20:51:45');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_08_02_000001_create_company_profiles_table', 1),
(5, '2026_08_02_000002_create_hero_banners_table', 1),
(6, '2026_08_02_000003_create_categories_table', 1),
(7, '2026_08_02_000004_create_services_table', 1),
(8, '2026_08_02_000005_create_portfolios_table', 1),
(9, '2026_08_02_000006_create_articles_table', 1),
(10, '2026_08_02_000007_create_team_members_table', 1),
(11, '2026_08_02_000008_create_testimonials_table', 1),
(12, '2026_08_02_000009_create_inbox_leads_table', 1),
(13, '2026_08_02_000010_create_site_settings_table', 1),
(14, '2026_08_02_152652_create_personal_access_tokens_table', 2),
(15, '2026_08_02_000011_create_article_comments_table', 3),
(16, '2026_08_05_000001_add_leader_fields_to_company_profiles_table', 4),
(17, '2026_08_05_000002_add_announcement_ticker_to_company_profiles_table', 5),
(18, '2026_08_05_000003_add_badge_text_to_hero_banners_table', 6),
(19, '2026_08_05_000004_add_stats_fields_to_company_profiles_table', 7),
(20, '2026_08_05_000005_create_faqs_table', 8),
(21, '2026_08_11_000001_add_related_links_to_company_profiles_table', 9),
(22, '2026_08_14_000001_add_allow_comments_to_articles_table', 10),
(23, '2026_08_14_000002_add_two_factor_columns_to_users_table', 11),
(24, '2026_08_14_000003_add_sort_order_to_portfolios_table', 12);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `gallery_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery_images`)),
  `short_description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `project_url` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`id`, `category_id`, `title`, `slug`, `client_name`, `completion_date`, `thumbnail_path`, `gallery_images`, `short_description`, `content`, `project_url`, `is_featured`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(4, 22, 'SCMB UNTAN', 'executive-dashboard-eis-rektorat', 'Calon Mahasiswa Baru', '2026-01-15', 'portfolios/XDw1ABs1ajsAuzFoMmJEG62W7mW13XwweJizWssv.png', NULL, 'Seleksi Calon Mahasiswa Baru Universitas Tanjungpura', '<p>Sistem pendaftaran, seleksi, dan pelayanan administrasi terintegrasi secara profesional dan transparan.</p><p>Portal ini digunakan sebagai sarana terpadu bagi calon mahasiswa untuk mengikuti seleksi masuk Universitas Tanjungpura serta terdapat menu seperti jadwal, kouta kampus dan pengumuman lainnya yang tersaji di web scmb untan.</p>', 'https://scmb.untan.ac.id', 1, 1, 3, '2026-08-11 02:54:49', '2026-08-14 07:48:18'),
(5, 22, 'Sistem Informasi Tugas Akhir', 'portal-layanan-bkd-dosen', 'Dosen & Mahasiswa', '2026-03-20', 'portfolios/IID31SDzqUraq54ouCVX5YxQKJYCGIPKcifbsv3E.png', NULL, 'Sistem Informasi Tugas Akhir adalah platform terintegrasi yang mendigitalkan seluruh siklus penyelesaian tugas akhir', '<p>Sistem tugas akhir digunakan mahasiswa, mulai dari pengajuan judul/proposal, proses bimbingan, penjadwalan seminar/sidang, revisi, hingga publikasi/repositori.</p><p>Berikut adalah daftar sistem beserta fungsinya sesuai dengan yang tertera pada gambar:</p><ol><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>SISAGA:</strong> Aplikasi Tugas Akhir Fak. Kehutanan</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>SISTEMIK:</strong> Aplikasi Tugas Akhir Fak. Kedokteran Prodi Kedokteran</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>SATURASI:</strong> Aplikasi Tugas Akhir Fak. Kedokteran Prodi Keperawatan</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>SUSPENSI:</strong> Aplikasi Tugas Akhir Fak. Kedokteran Prodi Farmasi</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>SISTANI:</strong> Aplikasi Tugas Akhir Fak. Pertanian</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>SISTEMATIS:</strong> Aplikasi terintegrasi mahasiswa Teknik Sipil</li><li data-list=\"bullet\"><span class=\"ql-ui\" contenteditable=\"false\"></span><strong>SIMKMFEB:</strong> Aplikasi Manajemen Kemahasiswaan Fak. Ekonomi dan Bisnis</li></ol>', 'https://tik.untan.ac.id', 1, 1, 2, '2026-08-11 02:54:49', '2026-08-14 07:50:04'),
(6, 22, 'Portal Akademik SATU UNTAN', 'portal-akademik-siakad-mahasiswa', 'Dosen & Mahasiswa', '2026-05-10', 'portfolios/BnE9WvkdCdRU1TEtR6gqtss1ZapIcA8LgAMahgqn.jpg', NULL, 'SATU UNTAN - Sistem Administrasi Terintegrasi Universitas Tanjungpura adalah portal portal layanan digital terpadu berbasis Single Sign-On (SSO) milik Universitas Tanjungpura (UNTAN), Pontianak.', '<p>Portal ini dikembangkan untuk menyatukan berbagai modul administrasi akademik, kemahasiswaan, dan layanan institusi ke dalam satu ekosistem akun tunggal.</p><p>Memungkinkan sivitas akademika (mahasiswa, dosen, dan tenaga kependidikan) mengakses berbagai aplikasi dan layanan internal UNTAN hanya dengan satu akun (termasuk integrasi login Google / email institusi).</p>', 'https://satu.untan.ac.id', 1, 1, 1, '2026-08-11 02:54:49', '2026-08-14 07:47:56'),
(7, 22, 'Jurnal Untan', 'jurnal-untan-rQHr', 'Dosen', '2026-08-03', 'portfolios/tYMhDXhQQBvShEH7mIcl7z4eIHormne1yuH1BR0p.png', NULL, 'platform publikasi ilmiah elektronik terpadu milik Universitas Tanjungpura yang menggunakan sistem Open Journal Systems (OJS).', '<p>Portal ini berfungsi sebagai wadah utama penerbitan, pengelolaan, dan diseminasi artikel ilmiah dari hasil penelitian maupun pengabdian masyarakat yang dihasilkan oleh sivitas akademika UNTAN maupun peneliti eksternal.</p>', 'https://jurnal.untan.ac.id', 0, 1, 4, '2026-08-14 07:42:51', '2026-08-14 07:47:48'),
(8, 23, 'Sistem Keuangan dan Umum', 'sistem-keuangan-dan-umum-eD2E', 'Dosen dan Tendik', '2026-08-14', 'portfolios/2lvJRexbDH9DsAXE8SNZ2WIh9qFQE32nwlR7lGWa.png', NULL, 'layanan satu pintu terintegrasi kepegawaian dan keuangan untan', '<p>Modul aplikasi yang ada di portal : siremun, senja presensi, dawai, verifikator, siska, sirine, simkeu, piutang dan lainnya.</p>', 'https://portal.untan.ac.id', 1, 1, 5, '2026-08-14 08:00:05', '2026-08-14 08:02:31'),
(9, 22, 'Domain dan subdomain', 'domain-dan-subdomain-9rUW', 'Mahasiswa & Sivitas Akademika', '2026-08-14', 'portfolios/NnMx5GWp4o3kf7b2yGvee4KonuFqth2bG6er8fJA.png', NULL, 'Universitas Tanjungpura (UNTAN) menggunakan domain utama berakhiran .ac.id (domain resmi untuk institusi pendidikan tinggi di Indonesia).', '<p>Infrastruktur digital Universitas Tanjungpura (UNTAN) menginduk pada domain utama berakhiran institusi pendidikan tinggi untan.ac.id, yang kemudian dipetakan ke dalam berbagai subdomain terpusat untuk memisahkan fungsi tata kelola universitas, layanan akademik mahasiswa, administrasi fakultas, riset, serta sistem informasi tugas akhir berbasis program studi.</p>', 'https://untan.ac.id', 1, 1, 6, '2026-08-14 08:10:03', '2026-08-14 08:10:03');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `category_id`, `title`, `slug`, `icon`, `image_path`, `short_description`, `content`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Sistem Aplikasi Web', 'sistem-aplikasi-web', 'globe', NULL, 'Rancang bangun, integrasi API, dan maintenance portal web publik, SIAKAD, E-Government, dan aplikasi internal instansi.', 'Portal Web Resmi\nIntegrasi API Data\nKeamanan SSL & SSO', 1, 1, '2026-08-14 03:14:52', '2026-08-14 03:14:52'),
(2, NULL, 'Jaringan & Networking', 'jaringan-networking', 'wifi', NULL, 'Pengelolaan backbone kabel Fiber Optic (FO), titik Akses Wifi Publik, Mikrotik/Cisco Routing, & Firewall Keamanan.', 'Fiber Optic Backbone\nHigh Speed Wifi Mesh\nNetwork Traffic Monitor', 2, 1, '2026-08-14 03:14:52', '2026-08-14 03:14:52'),
(3, NULL, 'Ruang Lab Komputer', 'ruang-lab-komputer', 'monitor', NULL, 'Fasilitas ruang lab komputer ber-AC lengkap untuk kegiatan ujian kompetensi (CBT), workshop teknis, dan sertifikasi IT.', 'Ujian Komputer CBT\nPelatihan Kompetensi\nReservasi Gedung Lab', 3, 1, '2026-08-14 03:14:52', '2026-08-14 03:14:52'),
(4, NULL, 'Server House & Multi-Cloud', 'server-house-multi-cloud', 'server', NULL, 'Pengelolaan infrastruktur server gabungan On-Premise Server House (Bare-Metal/Virtualization) serta skema Multi-Cloud di Amazon Web Services (AWS) & Google Cloud (GCP).', 'AWS & Google Cloud (GCP)\nOn-Premise Server House\nAutomated Backup & Recovery', 4, 1, '2026-08-14 03:14:52', '2026-08-14 03:14:52');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('AymI5bZMWXCWScvgHCYtWGKGUF8kYzQKg0rhufTy', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJyaFZBVUtUWHJhcVVYUVdXcmlSNzR2OVpsMXA2RkFaNkNuUjZlMHJSIiwiY2FwdGNoYV9hbnN3ZXIiOjYsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC9jbXMudGVzdCIsInJvdXRlIjoiaG9tZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1787492158),
('FyPhwPdruLje7Y5tx2an0wBUTEDcd3qzEcNhndyF', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ2NWhHdVFhUnpXdnVjM2VZSE92c2xwSUIxMENKYnVaR2diOUZRREphIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2Ntcy50ZXN0Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1787109797),
('q8Jhig80MWRB41d7xQzBwtRXT570B5ZKFf7us3QV', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJZVlh4b2g0c2FydGp2WFFqMmhYRjJUWTl5Yk5FQWdySzdldTlTR3NxIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvY21zLnRlc3RcL2RhbGFtXC9hcnRpY2xlc1wvY3JlYXRlIiwicm91dGUiOiJhZG1pbi5hcnRpY2xlcy5jcmVhdGUifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1787392647),
('W1B1x7OH8SUWWpSb7DoYkwmGHMgjPi1rOfKvy9p4', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJYUHZZWHVWWThxaFh4ajk0U3hjVXU5TnJCWFlvbEQxalB0WGFQNklWIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2Ntcy50ZXN0Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwidXJsIjpbXSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsImNhcHRjaGFfYW5zd2VyIjo4fQ==', 1787111505);

-- --------------------------------------------------------

--
-- Table structure for table `site_settings`
--

CREATE TABLE `site_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_settings`
--

INSERT INTO `site_settings` (`id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Nusantara Digital Solusindo', 'general', '2026-08-02 08:26:40', '2026-08-02 08:26:40'),
(2, 'seo_default_title', 'UPA. Teknologi Informasi dan Komunikasi (TIK)', 'general', '2026-08-02 08:26:40', '2026-08-14 06:53:46'),
(3, 'seo_default_description', 'UPA. Teknologi Informasi dan Komunikasi  Univeristas Tanjungpura (UPA. TIK UNTAN), Pontianak, Kalimantan Barat', 'general', '2026-08-02 08:26:40', '2026-08-14 06:53:46'),
(4, 'google_analytics_id', 'G-XXXXXXX', 'general', '2026-08-02 08:26:40', '2026-08-05 00:58:39'),
(5, 'whatsapp_default_message', 'Halo, saya tertarik berkonsultasi mengenai layanan ini', 'general', '2026-08-05 00:58:39', '2026-08-14 03:06:43');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `social_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_links`)),
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `position`, `photo_path`, `bio`, `social_links`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Novi Safriadi, S.T., M.T.', 'Kepala UPA TIK / Dosen', 'team/CiSGBqwyr75o6JUr8zzZsePNCn0WTOpqEphN59TF.png', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 1, 1, '2026-08-02 08:26:40', '2026-08-11 01:03:32'),
(2, 'Imam Adhita Virya, S.T.', 'Pengelola sistem dan jaringan / Admin Server', 'team/Qlt7CkWOyjlSLEym5q0FExXZbG61gBeqUAEM8kMk.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 2, 1, '2026-08-02 08:26:40', '2026-08-11 03:21:59'),
(3, 'Muanuddin, S.T.', 'Pengelola Sistem dan Jaringan / Admin Jaringan', 'team/Wt0yrk0yJsczEX0ASc8oDdsmTCI8ByEArGvMc5zD.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 3, 1, '2026-08-05 06:15:26', '2026-08-11 01:01:15'),
(4, 'M. Ariansha Noer, S.T.', 'Pengelola Laman / BMN', 'team/n7PWHVlpsSYIRJEJTISz7hlGlgtjdBJn0lX6c7MH.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 4, 1, '2026-08-05 06:16:56', '2026-08-14 09:45:16'),
(10, 'Dio, S.Kom', 'Pranata Komputer / Sistem Analis / Pengembang Web / Admin AWS', 'team/9Nm9iT8qWyEbkN27NfIbQNhgCiIspPhVsIH0ZIPc.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 5, 1, '2026-08-14 09:43:05', '2026-08-14 09:55:07'),
(11, 'Muhammad Zuhri, S.T.', 'Penata Layanan Operasional / Teknisi Komputer dan Jaringan', 'team/qT9vrOkHqlfwxaPH56KxoorXTjLHztHfV11eHSz0.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 6, 1, '2026-08-14 09:48:28', '2026-08-14 09:48:28'),
(12, 'Dony Yuniardi, S.Pi', 'Penata Layanan Operasional / Admin Helpdesk', 'team/ymCsHAHDuY1IataO2Y7jbayc3MB1coX9tMP5BcMa.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 7, 1, '2026-08-14 09:49:33', '2026-08-14 09:49:33'),
(13, 'Herwandi', 'Operator Layanan Operasional / Admin Website', 'team/KjpMBWIX2oCUvIH8uASJnXQbopoPoZgK3LVuQZGN.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 8, 1, '2026-08-14 09:51:15', '2026-08-14 09:51:15'),
(14, 'Burhanudin', 'Operator Layanan Operasional / Teknisi Komputer dan Jaringan', 'team/DKPmzsEtgmA7UC6uzHevhWZjxHpPmbgNjCXzUEB7.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 9, 1, '2026-08-14 09:51:54', '2026-08-14 09:52:05'),
(15, 'Heryansyah. AL', 'Operator Layanan Operasional / Teknisi Komputer dan Jaringan', 'team/W8zvuBfZFP5XUo9yH94cknyh0ExkLQ2aJTQwSUlr.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 10, 1, '2026-08-14 09:53:26', '2026-08-14 09:53:26'),
(16, 'Joko Susanto, S.Kom, M.Pd.', 'Penata Layanan Operasional / Sistem Analis / Pengembang Web', 'team/oTPj5naZVpod5I4erCE0NikEl3ToTsPe0GEBdP0w.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 11, 1, '2026-08-14 09:54:44', '2026-08-14 09:54:44'),
(17, 'Dicky Jiro', 'Operator Layanan Operasional / Admin Aplikasi', 'team/5e3nq2rVuSEFhkCO8Jkrh0DJebJU8i5OWkJ8s8W4.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 12, 1, '2026-08-14 09:56:28', '2026-08-14 09:56:28'),
(18, 'Archil Aulia Najahy Majid', 'Operator Layanan Operasional / Pengembang Web', 'team/7CqaCU5PvUFBtXbO18u9ci1FqoV42IYKCbhgodRm.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 13, 1, '2026-08-14 09:57:40', '2026-08-14 09:57:40'),
(19, 'Ridho Prabowo, S.Kom', 'Penata Layanan Operasional / Pengembang Web', 'team/dX7pFWsGikRL741t1PCcV2Aq1xVyG0VmjsT616NZ.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 14, 1, '2026-08-14 09:58:48', '2026-08-14 09:58:48'),
(20, 'Fahri Ramadhan, S.Kom', 'Operator Layanan Operasional / Sistem Analis / Pengembang Web / Cyber Security', 'team/CXu5ZPqGiqBKcRbjjKjCatddQ8zv2acZk87KxbhJ.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 15, 1, '2026-08-14 10:00:07', '2026-08-14 10:00:07'),
(21, 'Mohani, S.Kom', 'Penata Layanan Operasional / Sistem Analis / Pengembang Web', 'team/VfXuuvIQdBM5XwHMwucDKvaatS8cw8DVQlkyGf17.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 16, 1, '2026-08-14 10:01:00', '2026-08-14 10:01:00'),
(22, 'Sy. Muhammad Fahri', 'Operator Layanan Operasional / Programmer Mobile', 'team/y0I21lzEJ4AWz8VqNv2yHpPSqv1X06qFLTI3wxRw.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 17, 1, '2026-08-14 10:01:52', '2026-08-14 10:01:52'),
(23, 'Ahmad Yunizar, S.Kom', 'Programmer / Web Desain / UI / UX', 'team/lfvjpi3vsXQHzkARhNVO6NUuz8mjUpSRRLKilfyT.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 18, 1, '2026-08-14 10:03:23', '2026-08-14 10:05:54'),
(24, 'M. Amin Rais, S.Kom', 'Sistem Analis / Pengembang Web', 'team/h0i4htWCMpvzYi7YCs6AwM4Pi8pChFOiMC5Ynxyf.jpg', NULL, '{\"linkedin\":null,\"twitter\":null,\"instagram\":null}', 19, 1, '2026-08-14 10:04:10', '2026-08-14 10:04:10');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_company` varchar(255) DEFAULT NULL,
  `client_position` varchar(255) DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL DEFAULT 5,
  `review_content` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'superadmin',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `role`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'jokosusanto@untan.ac.id', NULL, '$2y$12$T9TJnpt8kHrrLyEbUdFk0eu41mPZZVf.cj2BpATBKpnGGqM0qQhTW', NULL, NULL, NULL, 'superadmin', 1, NULL, '2026-08-02 08:26:40', '2026-08-17 22:35:28'),
(3, 'editor', 'editor@example.com', NULL, '$2y$12$M4epKLprXzDCU80O/3heeu8fyFMHj/CvLiDj6OTVwbINxE1aApSXq', NULL, NULL, NULL, 'editor', 1, NULL, '2026-08-14 03:52:04', '2026-08-14 03:52:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articles_slug_unique` (`slug`),
  ADD KEY `articles_user_id_foreign` (`user_id`),
  ADD KEY `articles_category_id_foreign` (`category_id`);

--
-- Indexes for table `article_comments`
--
ALTER TABLE `article_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_comments_article_id_foreign` (`article_id`),
  ADD KEY `article_comments_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `company_profiles`
--
ALTER TABLE `company_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_banners`
--
ALTER TABLE `hero_banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inbox_leads`
--
ALTER TABLE `inbox_leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `portfolios_slug_unique` (`slug`),
  ADD KEY `portfolios_category_id_foreign` (`category_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_slug_unique` (`slug`),
  ADD KEY `services_category_id_foreign` (`category_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_settings_key_unique` (`key`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `article_comments`
--
ALTER TABLE `article_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `company_profiles`
--
ALTER TABLE `company_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hero_banners`
--
ALTER TABLE `hero_banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inbox_leads`
--
ALTER TABLE `inbox_leads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `portfolios`
--
ALTER TABLE `portfolios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `site_settings`
--
ALTER TABLE `site_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `articles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `article_comments`
--
ALTER TABLE `article_comments`
  ADD CONSTRAINT `article_comments_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `article_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `article_comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD CONSTRAINT `portfolios_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
