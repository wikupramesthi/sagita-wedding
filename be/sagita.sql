-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2026 at 07:22 AM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sagita`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `uuid` char(36) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `cover` char(36) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `album_foto`
--

CREATE TABLE `album_foto` (
  `album_uuid` char(36) NOT NULL,
  `banner_uuid` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `uuid` char(36) NOT NULL,
  `user_uuid` char(36) NOT NULL,
  `category_uuid` char(36) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `views` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `tagging` varchar(255) DEFAULT NULL,
  `status` enum('draft','published','scheduled') NOT NULL DEFAULT 'draft',
  `search_engine` enum('index','noindex') NOT NULL DEFAULT 'index',
  `link` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`uuid`, `user_uuid`, `category_uuid`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `scheduled_at`, `views`, `tagging`, `status`, `search_engine`, `link`, `video`, `created_at`, `updated_at`, `deleted_at`) VALUES
('63829ea9-8a32-4f82-94e1-7299120f822c', '787b72ea-59d0-4d54-848b-c200bddafdd2', '2162d145-9ef3-4e2f-8c55-81971a015bc5', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'pilates-vs-yoga-apa-bedanya-dan-mana-yang-cocok-untuk-anda', 'Pilates dan yoga sama-sama populer sebagai olahraga yang membantu meningkatkan kebugaran tubuh sekaligus memberikan manfaat bagi pikiran. Keduanya juga dapat dilakukan oleh pemula dan tidak selalu membutuhkan peralatan yang rumit.', '<p><strong>Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?</strong></p>\r\n\r\n<p>Pilates dan yoga sama-sama populer sebagai olahraga yang membantu meningkatkan kebugaran tubuh sekaligus memberikan manfaat bagi pikiran. Keduanya juga dapat dilakukan oleh pemula dan tidak selalu membutuhkan peralatan yang rumit.</p>\r\n\r\n<p>Namun, <strong>Pilates dan yoga memiliki beberapa perbedaan</strong>, mulai dari tujuan latihan, teknik gerakan, fokus tubuh, hingga pendekatan terhadap pernapasan. Lalu, mana yang lebih cocok untuk Anda?</p>\r\n\r\n<p>Berikut pembahasan lengkap mengenai perbedaan Pilates dan yoga agar Anda dapat menentukan jenis latihan yang sesuai dengan kebutuhan dan tujuan kebugaran Anda.</p>\r\n\r\n<p><strong>Apa Itu Yoga?</strong></p>\r\n\r\n<p>Yoga adalah latihan yang menggabungkan gerakan tubuh, pengaturan pernapasan, konsentrasi, dan relaksasi. Yoga memiliki berbagai jenis dan tingkat intensitas, mulai dari latihan yang lembut hingga latihan yang cukup dinamis.</p>\r\n\r\n<p>Selain membantu menjaga kebugaran tubuh, yoga dapat membantu meningkatkan fleksibilitas, keseimbangan, mobilitas, serta membantu tubuh menjadi lebih rileks.</p>\r\n\r\n<p>Beberapa jenis yoga yang populer antara lain:</p>\r\n\r\n<ul>\r\n    <li><strong>Hatha Yoga</strong>, dengan gerakan yang relatif terstruktur dan cocok untuk mengenal dasar-dasar yoga.</li>\r\n    <li><strong>Vinyasa Yoga</strong>, yang menghubungkan gerakan dengan ritme pernapasan secara lebih dinamis.</li>\r\n    <li><strong>Yin Yoga</strong>, yang menggunakan posisi tertentu dalam durasi lebih lama untuk melatih fleksibilitas.</li>\r\n    <li><strong>Restorative Yoga</strong>, yang berfokus pada relaksasi dengan gerakan yang lebih lembut.</li>\r\n</ul>\r\n\r\n<p><strong>Apa Itu Pilates?</strong></p>\r\n\r\n<p>Pilates merupakan latihan yang berfokus pada kontrol gerakan, stabilitas tubuh, kekuatan otot inti atau <strong>core</strong>, serta postur.</p>\r\n\r\n<p>Gerakan Pilates biasanya dilakukan secara terkontrol dan membutuhkan konsentrasi terhadap posisi tubuh serta teknik pernapasan.</p>\r\n\r\n<p>Pilates dapat dilakukan menggunakan matras maupun peralatan tertentu. Mat Pilates, misalnya, dapat dilakukan tanpa peralatan besar sehingga relatif mudah dimasukkan ke dalam rutinitas olahraga.</p>\r\n\r\n<p>Latihan Pilates banyak menekankan penggunaan otot core sehingga dapat membantu membangun kesadaran terhadap postur dan kontrol tubuh.</p>\r\n\r\n<p><strong>Perbedaan Yoga dan Pilates</strong></p>\r\n\r\n<p>Meskipun terlihat mirip, yoga dan Pilates memiliki fokus latihan yang berbeda. Berikut beberapa perbedaan utamanya.</p>\r\n\r\n<p><strong>1. Fokus Latihan</strong></p>\r\n\r\n<p>Yoga memiliki pendekatan yang lebih luas dengan menggabungkan latihan fisik, pernapasan, konsentrasi, relaksasi, dan kesadaran tubuh.</p>\r\n\r\n<p>Sementara itu, Pilates lebih menitikberatkan pada kontrol gerakan, stabilitas, kekuatan core, dan postur tubuh.</p>\r\n\r\n<p><strong>2. Fleksibilitas</strong></p>\r\n\r\n<p>Yoga umumnya sangat erat kaitannya dengan latihan fleksibilitas. Berbagai posisi yoga dapat membantu tubuh bergerak melalui rentang gerak yang lebih luas secara bertahap.</p>\r\n\r\n<p>Pilates juga melibatkan gerakan yang dapat membantu mobilitas dan fleksibilitas, tetapi fokus utamanya bukan hanya pada kelenturan tubuh.</p>\r\n\r\n<p><strong>3. Kekuatan Core</strong></p>\r\n\r\n<p>Pilates sangat dikenal dengan latihan yang melibatkan otot core. Banyak gerakannya membutuhkan stabilisasi bagian tengah tubuh agar gerakan dapat dilakukan dengan tepat.</p>\r\n\r\n<p>Yoga juga melatih core, terutama pada berbagai pose yang membutuhkan keseimbangan dan stabilitas. Namun, fokusnya dapat berbeda tergantung jenis yoga yang dilakukan.</p>\r\n\r\n<p><strong>4. Pernapasan</strong></p>\r\n\r\n<p>Pernapasan merupakan bagian penting dalam yoga. Pengaturan napas digunakan untuk membantu konsentrasi, mengontrol gerakan, dan menciptakan kondisi tubuh yang lebih rileks.</p>\r\n\r\n<p>Dalam Pilates, pernapasan juga penting dan digunakan untuk membantu kontrol serta koordinasi gerakan.</p>\r\n\r\n<p><strong>5. Relaksasi</strong></p>\r\n\r\n<p>Yoga umumnya memberikan perhatian lebih besar terhadap relaksasi, meditasi, dan kesadaran tubuh.</p>\r\n\r\n<p>Pilates lebih berorientasi pada latihan fisik dan kontrol gerakan, meskipun latihan yang dilakukan dengan fokus dan teratur juga dapat membantu tubuh terasa lebih rileks setelah berolahraga.</p>\r\n\r\n<p><strong>Pilates vs Yoga: Mana yang Lebih Baik?</strong></p>\r\n\r\n<p>Tidak ada jawaban bahwa Pilates selalu lebih baik daripada yoga, atau sebaliknya. Pilihan terbaik bergantung pada tujuan, preferensi, dan kebutuhan masing-masing orang.</p>\r\n\r\n<p>Jika Anda ingin meningkatkan fleksibilitas, melatih keseimbangan, sekaligus mendapatkan latihan yang menggabungkan gerakan dan relaksasi, <strong>yoga dapat menjadi pilihan yang menarik</strong>.</p>\r\n\r\n<p>Jika tujuan utama Anda adalah meningkatkan kekuatan core, kontrol tubuh, dan kesadaran terhadap postur, <strong>Pilates dapat menjadi pilihan yang sesuai</strong>.</p>\r\n\r\n<p>Namun, keduanya juga dapat dikombinasikan. Yoga dan Pilates memiliki karakteristik latihan yang berbeda sehingga dapat saling melengkapi dalam rutinitas kebugaran.</p>\r\n\r\n<p><strong>Yoga Cocok untuk Siapa?</strong></p>\r\n\r\n<p>Yoga dapat menjadi pilihan bagi Anda yang ingin:</p>\r\n\r\n<ul>\r\n    <li>Meningkatkan fleksibilitas tubuh.</li>\r\n    <li>Melatih keseimbangan dan mobilitas.</li>\r\n    <li>Meningkatkan kesadaran terhadap tubuh dan pernapasan.</li>\r\n    <li>Melakukan aktivitas fisik dengan intensitas yang dapat disesuaikan.</li>\r\n    <li>Menambahkan latihan relaksasi dalam rutinitas harian.</li>\r\n    <li>Memulai aktivitas olahraga secara bertahap.</li>\r\n</ul>\r\n\r\n<p><strong>Pilates Cocok untuk Siapa?</strong></p>\r\n\r\n<p>Pilates dapat menjadi pilihan bagi Anda yang ingin:</p>\r\n\r\n<ul>\r\n    <li>Melatih kekuatan otot core.</li>\r\n    <li>Meningkatkan kontrol gerakan.</li>\r\n    <li>Meningkatkan kesadaran terhadap postur tubuh.</li>\r\n    <li>Melatih stabilitas dan koordinasi.</li>\r\n    <li>Melakukan latihan dengan gerakan yang terkontrol.</li>\r\n</ul>\r\n\r\n<p><strong>Apakah Yoga dan Pilates Bisa Dilakukan Bersamaan?</strong></p>\r\n\r\n<p>Tentu saja. Yoga dan Pilates dapat menjadi kombinasi latihan yang menarik.</p>\r\n\r\n<p>Pilates dapat membantu melatih kekuatan dan stabilitas tubuh, sedangkan yoga dapat memberikan latihan fleksibilitas, keseimbangan, mobilitas, serta relaksasi.</p>\r\n\r\n<p>Anda dapat melakukan Pilates pada beberapa hari dalam seminggu dan menambahkan sesi yoga pada hari lainnya. Frekuensi dan intensitas latihan sebaiknya disesuaikan dengan kemampuan tubuh dan rutinitas masing-masing.</p>\r\n\r\n<p><strong>Tips Memilih Yoga atau Pilates</strong></p>\r\n\r\n<p>Sebelum memilih, coba tentukan tujuan utama Anda.</p>\r\n\r\n<p><strong>Pilih yoga jika Anda lebih tertarik pada:</strong></p>\r\n\r\n<ul>\r\n    <li>Fleksibilitas.</li>\r\n    <li>Keseimbangan.</li>\r\n    <li>Mobilitas.</li>\r\n    <li>Pernapasan.</li>\r\n    <li>Relaksasi dan mindfulness.</li>\r\n</ul>\r\n\r\n<p><strong>Pertimbangkan Pilates jika Anda lebih tertarik pada:</strong></p>\r\n\r\n<ul>\r\n    <li>Kekuatan core.</li>\r\n    <li>Stabilitas tubuh.</li>\r\n    <li>Kontrol gerakan.</li>\r\n    <li>Postur.</li>\r\n    <li>Latihan fisik yang terstruktur.</li>\r\n</ul>\r\n\r\n<p>Jika masih bingung, tidak ada salahnya mencoba keduanya. Pengalaman langsung dapat membantu Anda mengetahui latihan mana yang paling nyaman dan sesuai dengan kebutuhan Anda.</p>\r\n\r\n<p><strong>Kesimpulan</strong></p>\r\n\r\n<p>Jadi, <strong>apa perbedaan Pilates dan yoga?</strong> Secara sederhana, yoga memiliki pendekatan yang lebih luas dengan menggabungkan gerakan, pernapasan, keseimbangan, fleksibilitas, dan relaksasi. Sementara itu, Pilates lebih berfokus pada kontrol gerakan, stabilitas, kekuatan core, dan postur.</p>\r\n\r\n<p>Keduanya sama-sama dapat menjadi bagian dari gaya hidup aktif. Yang terpenting adalah memilih latihan yang sesuai dengan tujuan dan dapat dilakukan secara konsisten.</p>\r\n\r\n<p>Jika Anda ingin mencoba yoga, mulailah dari kelas yang sesuai dengan tingkat kemampuan Anda. Dengan instruktur yang tepat, Anda dapat mempelajari teknik dasar dengan lebih nyaman dan membangun kebiasaan latihan secara bertahap.</p>\r\n\r\n<p><strong>Siap Mencoba Yoga?</strong></p>\r\n\r\n<p>Temukan kelas yoga yang sesuai dengan kebutuhan dan tingkat kemampuan Anda. Mulai perjalanan menuju tubuh yang lebih aktif, fleksibel, dan seimbang bersama kelas yoga yang tepat.</p>', 'images/Q21BSTPfei8FVhkubCnpiCxQYWcL7P6XigMY6gBq.jpg', '2026-08-26 17:00:00', 51, 'yoga, tips yogaaa', 'published', 'index', NULL, NULL, '2026-08-27 04:59:46', '2026-11-18 19:19:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_uuid` char(36) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `event` varchar(30) NOT NULL,
  `auditable_type` varchar(255) DEFAULT NULL,
  `auditable_id` varchar(255) DEFAULT NULL,
  `auditable_label` varchar(255) DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `url` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_uuid`, `user_name`, `user_email`, `event`, `auditable_type`, `auditable_id`, `auditable_label`, `old_values`, `new_values`, `url`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(35, NULL, NULL, NULL, 'created', 'App\\Models\\User', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', NULL, '{\"name\":\"ESPRO Property\",\"email\":\"esproproperty.bekasi@gmail.com\",\"avatar\":\"https:\\/\\/lh3.googleusercontent.com\\/a\\/ACg8ocLitzhONjAN_zPOcC2rM16BHekNE3M2zU7C-e0d53-6M47R-PE=s96-c\",\"email_verified_at\":\"2026-10-02 14:44:00\",\"uuid\":\"c2187aff-68d0-40b9-a6f1-52738cee4ea4\",\"updated_at\":\"2026-10-02 14:44:01\",\"created_at\":\"2026-10-02 14:44:01\"}', 'http://localhost:8000/auth/google/callback?authuser=0&code=4%2F0AXlqoi4se13jSxyhha3hDQ7FxWdFdqc3M9i1t6UtTe4Iqztf7-q09u9LrB4C9SZHQyI1-Q&iss=https%3A%2F%2Faccounts.google.com&prompt=none&scope=email%20profile%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email%20openid', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-02 07:44:01', '2026-10-02 07:44:01'),
(36, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '24', 'Security', '{\"name\":\"Keamanan\"}', '{\"name\":\"Security\"}', 'http://localhost:8000/backend/menu/24', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-03 12:02:43', '2026-10-03 12:02:43'),
(37, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\MenuItem', '60', 'Website Identitas', NULL, '{\"route\":\"website-identity.index\",\"menu_group_id\":3,\"name\":\"Website Identitas\",\"status\":true,\"permission_name\":\"website-identity.index\",\"position\":99,\"updated_at\":\"2026-10-03 19:18:10\",\"created_at\":\"2026-10-03 19:18:10\",\"id\":60}', NULL, NULL, NULL, '2026-10-03 12:18:10', '2026-10-03 12:18:10'),
(38, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '213', 'Route #213', NULL, '{\"route\":\"website-identity.index\",\"permission_name\":\"website-identity.index\",\"status\":true,\"updated_at\":\"2026-10-03 19:18:10\",\"created_at\":\"2026-10-03 19:18:10\",\"id\":213}', NULL, NULL, NULL, '2026-10-03 12:18:10', '2026-10-03 12:18:10'),
(39, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '214', 'Route #214', NULL, '{\"route\":\"website-identity.update\",\"permission_name\":\"website-identity.update\",\"status\":true,\"updated_at\":\"2026-10-03 19:18:10\",\"created_at\":\"2026-10-03 19:18:10\",\"id\":214}', NULL, NULL, NULL, '2026-10-03 12:18:10', '2026-10-03 12:18:10'),
(40, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\WebsiteIdentity', '579ceea1-db69-41cf-a389-e242c04a2ff8', 'WebsiteIdentity #579ceea1-db69-41cf-a389-e242c04a2ff8', NULL, '{\"nama_website\":\"Laravel\",\"uuid\":\"579ceea1-db69-41cf-a389-e242c04a2ff8\",\"updated_at\":\"2026-10-03 19:19:22\",\"created_at\":\"2026-10-03 19:19:22\"}', 'http://localhost:8000/backend/website-identity', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-03 12:19:22', '2026-10-03 12:19:22'),
(41, NULL, NULL, NULL, 'created', 'App\\Models\\WebsiteIdentity', '39860859-6a83-4cae-aa27-659334baff1c', 'WebsiteIdentity #39860859-6a83-4cae-aa27-659334baff1c', NULL, '{\"site_name\":\"Laravel\",\"uuid\":\"39860859-6a83-4cae-aa27-659334baff1c\",\"updated_at\":\"2026-10-03 19:22:02\",\"created_at\":\"2026-10-03 19:22:02\"}', NULL, NULL, NULL, '2026-10-03 12:22:02', '2026-10-03 12:22:02'),
(42, NULL, NULL, NULL, 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '60', 'Website Identity', '{\"name\":\"Website Identitas\"}', '{\"name\":\"Website Identity\"}', NULL, NULL, NULL, '2026-10-03 12:24:24', '2026-10-03 12:24:24'),
(43, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '215', 'Route #215', NULL, '{\"route\":\"banner.storeVideo\",\"permission_name\":\"banner.store\",\"status\":true,\"updated_at\":\"2026-10-03 19:35:43\",\"created_at\":\"2026-10-03 19:35:43\",\"id\":215}', NULL, NULL, NULL, '2026-10-03 12:35:43', '2026-10-03 12:35:43'),
(44, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '216', 'Route #216', NULL, '{\"route\":\"banner.storeAlbum\",\"permission_name\":\"banner.store\",\"status\":true,\"updated_at\":\"2026-10-03 19:35:43\",\"created_at\":\"2026-10-03 19:35:43\",\"id\":216}', NULL, NULL, NULL, '2026-10-03 12:35:43', '2026-10-03 12:35:43'),
(45, NULL, NULL, NULL, 'created', 'App\\Models\\Banner', '5a003ead-e4ea-425e-af93-b5b1c74c89aa', 'Banner #5a003ead-e4ea-425e-af93-b5b1c74c89aa', NULL, '{\"tipe\":\"video\",\"nama\":\"TEST VIDEO\",\"deskripsi\":null,\"link\":\"https:\\/\\/www.youtube.com\\/watch?v=dQw4w9WgXcQ\",\"gambar\":\"https:\\/\\/i.ytimg.com\\/vi\\/dQw4w9WgXcQ\\/hqdefault.jpg\",\"posisi\":\"galeri\",\"status\":\"active\",\"updated_at\":\"2026-10-03 19:36:03\",\"created_at\":\"2026-10-03 19:36:03\"}', NULL, NULL, NULL, '2026-10-03 12:36:03', '2026-10-03 12:36:03'),
(46, NULL, NULL, NULL, 'created', 'App\\Models\\Banner', '8ddad511-e10e-4614-8d81-0c2e39c59ca6', 'Banner #8ddad511-e10e-4614-8d81-0c2e39c59ca6', NULL, '{\"tipe\":\"album\",\"nama\":\"TEST ALBUM\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/LBLHrpzB2IC8qoETFsI6albxj4BsLEmoMmuT3kKo.png\",\"posisi\":\"galeri\",\"status\":\"active\",\"updated_at\":\"2026-10-03 19:36:04\",\"created_at\":\"2026-10-03 19:36:04\"}', NULL, NULL, NULL, '2026-10-03 12:36:04', '2026-10-03 12:36:04'),
(47, NULL, NULL, NULL, 'created', 'App\\Models\\Banner', '0e5f5519-89a6-409b-a502-af2372df1b55', 'Banner #0e5f5519-89a6-409b-a502-af2372df1b55', NULL, '{\"tipe\":\"foto\",\"album_uuid\":\"8ddad511-e10e-4614-8d81-0c2e39c59ca6\",\"nama\":\"TEST FOTO\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/kvEM7surOc2ew2ruOdLGrHFCg6sseGdVFfNtmUu6.png\",\"posisi\":\"galeri\",\"status\":\"active\",\"updated_at\":\"2026-10-03 19:36:04\",\"created_at\":\"2026-10-03 19:36:04\"}', NULL, NULL, NULL, '2026-10-03 12:36:04', '2026-10-03 12:36:04'),
(48, NULL, NULL, NULL, 'deleted', 'App\\Models\\Banner', '8ddad511-e10e-4614-8d81-0c2e39c59ca6', 'Banner #8ddad511-e10e-4614-8d81-0c2e39c59ca6', '{\"uuid\":\"8ddad511-e10e-4614-8d81-0c2e39c59ca6\",\"tipe\":\"album\",\"album_uuid\":null,\"nama\":\"TEST ALBUM\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/LBLHrpzB2IC8qoETFsI6albxj4BsLEmoMmuT3kKo.png\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, NULL, NULL, NULL, '2026-10-03 12:36:05', '2026-10-03 12:36:05'),
(49, NULL, NULL, NULL, 'deleted', 'App\\Models\\Banner', '5a003ead-e4ea-425e-af93-b5b1c74c89aa', 'Banner #5a003ead-e4ea-425e-af93-b5b1c74c89aa', '{\"uuid\":\"5a003ead-e4ea-425e-af93-b5b1c74c89aa\",\"tipe\":\"video\",\"album_uuid\":null,\"nama\":\"TEST VIDEO\",\"deskripsi\":null,\"link\":\"https:\\/\\/www.youtube.com\\/watch?v=dQw4w9WgXcQ\",\"gambar\":\"https:\\/\\/i.ytimg.com\\/vi\\/dQw4w9WgXcQ\\/hqdefault.jpg\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, NULL, NULL, NULL, '2026-10-03 12:36:05', '2026-10-03 12:36:05'),
(50, NULL, NULL, NULL, 'deleted', 'App\\Models\\Banner', '0e5f5519-89a6-409b-a502-af2372df1b55', 'Banner #0e5f5519-89a6-409b-a502-af2372df1b55', '{\"uuid\":\"0e5f5519-89a6-409b-a502-af2372df1b55\",\"tipe\":\"foto\",\"album_uuid\":null,\"nama\":\"TEST FOTO\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/kvEM7surOc2ew2ruOdLGrHFCg6sseGdVFfNtmUu6.png\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, NULL, NULL, NULL, '2026-10-03 12:36:05', '2026-10-03 12:36:05'),
(51, NULL, NULL, NULL, 'created', 'App\\Models\\Banner', '74b5678a-4b5f-49dd-800d-9fce55a188bb', 'Banner #74b5678a-4b5f-49dd-800d-9fce55a188bb', NULL, '{\"tipe\":\"foto\",\"album_uuid\":null,\"nama\":\"TEST KOMPRES\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/95gmDoqPNzrJHZNVH2urILWmcK7xP1w6EjtqVA9r.jpg\",\"posisi\":\"galeri\",\"status\":\"active\",\"updated_at\":\"2026-10-03 19:39:09\",\"created_at\":\"2026-10-03 19:39:09\"}', NULL, NULL, NULL, '2026-10-03 12:39:09', '2026-10-03 12:39:09'),
(52, NULL, NULL, NULL, 'deleted', 'App\\Models\\Banner', '74b5678a-4b5f-49dd-800d-9fce55a188bb', 'Banner #74b5678a-4b5f-49dd-800d-9fce55a188bb', '{\"uuid\":\"74b5678a-4b5f-49dd-800d-9fce55a188bb\",\"tipe\":\"foto\",\"album_uuid\":null,\"nama\":\"TEST KOMPRES\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/95gmDoqPNzrJHZNVH2urILWmcK7xP1w6EjtqVA9r.jpg\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, NULL, NULL, NULL, '2026-10-03 12:39:09', '2026-10-03 12:39:09'),
(53, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '217', 'Route #217', NULL, '{\"route\":\"banner.loadMore\",\"permission_name\":\"banner.index\",\"status\":true,\"updated_at\":\"2026-10-03 19:49:08\",\"created_at\":\"2026-10-03 19:49:08\",\"id\":217}', NULL, NULL, NULL, '2026-10-03 12:49:08', '2026-10-03 12:49:08'),
(54, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '218', 'Route #218', NULL, '{\"route\":\"banner.fotoPicker\",\"permission_name\":\"banner.index\",\"status\":true,\"updated_at\":\"2026-10-03 19:49:08\",\"created_at\":\"2026-10-03 19:49:08\",\"id\":218}', NULL, NULL, NULL, '2026-10-03 12:49:08', '2026-10-03 12:49:08'),
(55, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '219', 'Route #219', NULL, '{\"route\":\"banner.albumFotos\",\"permission_name\":\"banner.index\",\"status\":true,\"updated_at\":\"2026-10-03 19:49:08\",\"created_at\":\"2026-10-03 19:49:08\",\"id\":219}', NULL, NULL, NULL, '2026-10-03 12:49:08', '2026-10-03 12:49:08'),
(56, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '220', 'Route #220', NULL, '{\"route\":\"banner.modal\",\"permission_name\":\"banner.index\",\"status\":true,\"updated_at\":\"2026-10-03 19:49:08\",\"created_at\":\"2026-10-03 19:49:08\",\"id\":220}', NULL, NULL, NULL, '2026-10-03 12:49:08', '2026-10-03 12:49:08'),
(57, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '221', 'Route #221', NULL, '{\"route\":\"banner.updateAlbum\",\"permission_name\":\"banner.update\",\"status\":true,\"updated_at\":\"2026-10-03 19:49:08\",\"created_at\":\"2026-10-03 19:49:08\",\"id\":221}', NULL, NULL, NULL, '2026-10-03 12:49:08', '2026-10-03 12:49:08'),
(58, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '222', 'Route #222', NULL, '{\"route\":\"banner.destroyAlbum\",\"permission_name\":\"banner.destroy\",\"status\":true,\"updated_at\":\"2026-10-03 19:49:08\",\"created_at\":\"2026-10-03 19:49:08\",\"id\":222}', NULL, NULL, NULL, '2026-10-03 12:49:08', '2026-10-03 12:49:08'),
(59, NULL, NULL, NULL, 'created', 'App\\Models\\Banner', '8fb90e6a-c118-4540-bedc-8fbde8124b69', 'Banner #8fb90e6a-c118-4540-bedc-8fbde8124b69', NULL, '{\"tipe\":\"foto\",\"nama\":\"T Foto\",\"deskripsi\":null,\"status\":\"active\",\"link\":null,\"posisi\":\"galeri\",\"gambar\":\"banners\\/YRzPl6OJ1GnjRdWCW6vAmPYgLtwEG3VMm7d7sE8m.jpg\",\"updated_at\":\"2026-10-03 19:49:26\",\"created_at\":\"2026-10-03 19:49:26\"}', NULL, NULL, NULL, '2026-10-03 12:49:26', '2026-10-03 12:49:26'),
(60, NULL, NULL, NULL, 'created', 'App\\Models\\Banner', 'aeec9934-304e-4415-99d9-9634472d4f16', 'Banner #aeec9934-304e-4415-99d9-9634472d4f16', NULL, '{\"tipe\":\"video\",\"nama\":\"T Video\",\"deskripsi\":null,\"status\":\"active\",\"link\":null,\"video_url\":\"https:\\/\\/www.youtube.com\\/watch?v=dQw4w9WgXcQ\",\"posisi\":\"lainnya\",\"gambar\":\"\",\"updated_at\":\"2026-10-03 19:49:26\",\"created_at\":\"2026-10-03 19:49:26\"}', NULL, NULL, NULL, '2026-10-03 12:49:26', '2026-10-03 12:49:26'),
(61, NULL, NULL, NULL, 'created', 'App\\Models\\Album', 'da38da82-aa73-45cd-95b1-d121236e3835', 'Album #da38da82-aa73-45cd-95b1-d121236e3835', NULL, '{\"nama\":\"T Album\",\"deskripsi\":null,\"status\":\"active\",\"cover\":\"8fb90e6a-c118-4540-bedc-8fbde8124b69\",\"updated_at\":\"2026-10-03 19:49:26\",\"created_at\":\"2026-10-03 19:49:26\"}', NULL, NULL, NULL, '2026-10-03 12:49:26', '2026-10-03 12:49:26'),
(62, NULL, NULL, NULL, 'deleted', 'App\\Models\\Album', 'da38da82-aa73-45cd-95b1-d121236e3835', 'Album #da38da82-aa73-45cd-95b1-d121236e3835', '{\"uuid\":\"da38da82-aa73-45cd-95b1-d121236e3835\",\"nama\":\"T Album\",\"deskripsi\":null,\"cover\":\"8fb90e6a-c118-4540-bedc-8fbde8124b69\",\"status\":\"active\"}', NULL, NULL, NULL, NULL, '2026-10-03 12:49:26', '2026-10-03 12:49:26'),
(63, NULL, NULL, NULL, 'deleted', 'App\\Models\\Banner', 'aeec9934-304e-4415-99d9-9634472d4f16', 'Banner #aeec9934-304e-4415-99d9-9634472d4f16', '{\"uuid\":\"aeec9934-304e-4415-99d9-9634472d4f16\",\"tipe\":\"video\",\"video_url\":\"https:\\/\\/www.youtube.com\\/watch?v=dQw4w9WgXcQ\",\"nama\":\"T Video\",\"deskripsi\":null,\"link\":null,\"gambar\":\"\",\"posisi\":\"lainnya\",\"status\":\"active\"}', NULL, NULL, NULL, NULL, '2026-10-03 12:49:26', '2026-10-03 12:49:26'),
(64, NULL, NULL, NULL, 'deleted', 'App\\Models\\Banner', '8fb90e6a-c118-4540-bedc-8fbde8124b69', 'Banner #8fb90e6a-c118-4540-bedc-8fbde8124b69', '{\"uuid\":\"8fb90e6a-c118-4540-bedc-8fbde8124b69\",\"tipe\":\"foto\",\"video_url\":null,\"nama\":\"T Foto\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/YRzPl6OJ1GnjRdWCW6vAmPYgLtwEG3VMm7d7sE8m.jpg\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, NULL, NULL, NULL, '2026-10-03 12:49:26', '2026-10-03 12:49:26'),
(65, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', 'created', 'App\\Models\\Banner', 'fead6d51-3ae1-463e-8f0f-2dbc5b0ea93f', 'Banner #fead6d51-3ae1-463e-8f0f-2dbc5b0ea93f', NULL, '{\"tipe\":\"foto\",\"nama\":\"QA Foto 1\",\"deskripsi\":null,\"status\":\"active\",\"link\":null,\"posisi\":\"galeri\",\"gambar\":\"banners\\/r34mlvwpIFQ8g6F5A30imDVUtyo3aU3ZxFhq9gAl.jpg\",\"updated_at\":\"2026-10-03 19:51:03\",\"created_at\":\"2026-10-03 19:51:03\"}', NULL, NULL, NULL, '2026-10-03 12:51:03', '2026-10-03 12:51:03'),
(66, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', 'created', 'App\\Models\\Banner', '844aab18-bf15-4daf-97d1-50286823f54e', 'Banner #844aab18-bf15-4daf-97d1-50286823f54e', NULL, '{\"tipe\":\"foto\",\"nama\":\"QA Foto 2\",\"deskripsi\":null,\"status\":\"active\",\"link\":null,\"posisi\":\"galeri\",\"gambar\":\"banners\\/iT6KTiSttcO9YsV7pjxltXD1XOoiZxKKuF4XWUjX.jpg\",\"updated_at\":\"2026-10-03 19:51:03\",\"created_at\":\"2026-10-03 19:51:03\"}', NULL, NULL, NULL, '2026-10-03 12:51:03', '2026-10-03 12:51:03'),
(67, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', 'created', 'App\\Models\\Album', '986f0b8a-49f9-40e6-8b68-f5ce0b180c02', 'Album #986f0b8a-49f9-40e6-8b68-f5ce0b180c02', NULL, '{\"nama\":\"QA Album\",\"deskripsi\":null,\"status\":\"active\",\"cover\":\"fead6d51-3ae1-463e-8f0f-2dbc5b0ea93f\",\"updated_at\":\"2026-10-03 19:51:03\",\"created_at\":\"2026-10-03 19:51:03\"}', NULL, NULL, NULL, '2026-10-03 12:51:03', '2026-10-03 12:51:03'),
(68, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', 'created', 'App\\Models\\Banner', '8815b64c-0949-4ffe-a614-1540b5451e32', 'Banner #8815b64c-0949-4ffe-a614-1540b5451e32', NULL, '{\"tipe\":\"foto\",\"nama\":\"QA Foto 3\",\"deskripsi\":null,\"status\":\"active\",\"link\":null,\"posisi\":\"galeri\",\"gambar\":\"banners\\/oyoD47E7DF3J6ol0B9uSY8UQQUbj5HtFDOvkvPAp.jpg\",\"updated_at\":\"2026-10-03 19:51:03\",\"created_at\":\"2026-10-03 19:51:03\"}', NULL, NULL, NULL, '2026-10-03 12:51:03', '2026-10-03 12:51:03'),
(69, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', 'updated', 'App\\Models\\Album', '986f0b8a-49f9-40e6-8b68-f5ce0b180c02', 'Album #986f0b8a-49f9-40e6-8b68-f5ce0b180c02', '{\"cover\":\"fead6d51-3ae1-463e-8f0f-2dbc5b0ea93f\"}', '{\"cover\":\"844aab18-bf15-4daf-97d1-50286823f54e\"}', NULL, NULL, NULL, '2026-10-03 12:51:03', '2026-10-03 12:51:03'),
(70, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', 'created', 'App\\Models\\Banner', 'f83ce68d-a62d-4421-b582-6461e859a25c', 'Banner #f83ce68d-a62d-4421-b582-6461e859a25c', NULL, '{\"uuid\":\"f83ce68d-a62d-4421-b582-6461e859a25c\",\"tipe\":\"video\",\"nama\":\"QA Vid\",\"video_url\":\"https:\\/\\/www.youtube.com\\/watch?v=dQw4w9WgXcQ\",\"gambar\":\"\",\"posisi\":\"lainnya\",\"status\":\"active\",\"updated_at\":\"2026-10-03 19:51:03\",\"created_at\":\"2026-10-03 19:51:03\"}', NULL, NULL, NULL, '2026-10-03 12:51:03', '2026-10-03 12:51:03'),
(71, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', 'updated', 'App\\Models\\Banner', 'f83ce68d-a62d-4421-b582-6461e859a25c', 'Banner #f83ce68d-a62d-4421-b582-6461e859a25c', '{\"video_url\":\"https:\\/\\/www.youtube.com\\/watch?v=dQw4w9WgXcQ\"}', '{\"video_url\":\"https:\\/\\/youtu.be\\/9bZkp7q19f0\"}', NULL, NULL, NULL, '2026-10-03 12:51:03', '2026-10-03 12:51:03'),
(72, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', 'deleted', 'App\\Models\\Album', '986f0b8a-49f9-40e6-8b68-f5ce0b180c02', 'Album #986f0b8a-49f9-40e6-8b68-f5ce0b180c02', '{\"uuid\":\"986f0b8a-49f9-40e6-8b68-f5ce0b180c02\",\"nama\":\"QA Album\",\"deskripsi\":null,\"cover\":\"844aab18-bf15-4daf-97d1-50286823f54e\",\"status\":\"active\"}', NULL, NULL, NULL, NULL, '2026-10-03 12:51:03', '2026-10-03 12:51:03'),
(73, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', 'deleted', 'App\\Models\\Banner', 'f83ce68d-a62d-4421-b582-6461e859a25c', 'Banner #f83ce68d-a62d-4421-b582-6461e859a25c', '{\"uuid\":\"f83ce68d-a62d-4421-b582-6461e859a25c\",\"tipe\":\"video\",\"video_url\":\"https:\\/\\/youtu.be\\/9bZkp7q19f0\",\"nama\":\"QA Vid\",\"deskripsi\":null,\"link\":null,\"gambar\":\"\",\"posisi\":\"lainnya\",\"status\":\"active\"}', NULL, NULL, NULL, NULL, '2026-10-03 12:51:03', '2026-10-03 12:51:03'),
(74, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', 'deleted', 'App\\Models\\Banner', 'fead6d51-3ae1-463e-8f0f-2dbc5b0ea93f', 'Banner #fead6d51-3ae1-463e-8f0f-2dbc5b0ea93f', '{\"uuid\":\"fead6d51-3ae1-463e-8f0f-2dbc5b0ea93f\",\"tipe\":\"foto\",\"video_url\":null,\"nama\":\"QA Foto 1\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/r34mlvwpIFQ8g6F5A30imDVUtyo3aU3ZxFhq9gAl.jpg\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, NULL, NULL, NULL, '2026-10-03 12:51:03', '2026-10-03 12:51:03'),
(75, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', 'deleted', 'App\\Models\\Banner', '844aab18-bf15-4daf-97d1-50286823f54e', 'Banner #844aab18-bf15-4daf-97d1-50286823f54e', '{\"uuid\":\"844aab18-bf15-4daf-97d1-50286823f54e\",\"tipe\":\"foto\",\"video_url\":null,\"nama\":\"QA Foto 2\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/iT6KTiSttcO9YsV7pjxltXD1XOoiZxKKuF4XWUjX.jpg\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, NULL, NULL, NULL, '2026-10-03 12:51:03', '2026-10-03 12:51:03'),
(76, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', 'deleted', 'App\\Models\\Banner', '8815b64c-0949-4ffe-a614-1540b5451e32', 'Banner #8815b64c-0949-4ffe-a614-1540b5451e32', '{\"uuid\":\"8815b64c-0949-4ffe-a614-1540b5451e32\",\"tipe\":\"foto\",\"video_url\":null,\"nama\":\"QA Foto 3\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/oyoD47E7DF3J6ol0B9uSY8UQQUbj5HtFDOvkvPAp.jpg\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, NULL, NULL, NULL, '2026-10-03 12:51:03', '2026-10-03 12:51:03'),
(77, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '223', 'Route #223', NULL, '{\"route\":\"articles.bulkDestroy\",\"permission_name\":\"articles.destroy\",\"status\":true,\"updated_at\":\"2026-10-03 20:13:45\",\"created_at\":\"2026-10-03 20:13:45\",\"id\":223}', NULL, NULL, NULL, '2026-10-03 13:13:45', '2026-10-03 13:13:45'),
(78, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\ManagementAccess\\Route', '95', 'Route #95', '{\"id\":95,\"route\":\"program.create\",\"permission_name\":\"program.create\",\"status\":true,\"description\":null}', NULL, 'http://localhost:8000/backend/route/95', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-03 13:35:55', '2026-10-03 13:35:55'),
(79, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\ManagementAccess\\Route', '100', 'Route #100', '{\"id\":100,\"route\":\"program.destroy\",\"permission_name\":\"program.destroy\",\"status\":true,\"description\":null}', NULL, 'http://localhost:8000/backend/route/100', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-03 13:36:09', '2026-10-03 13:36:09'),
(80, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\ManagementAccess\\Route', '105', 'Route #105', '{\"id\":105,\"route\":\"program.index\",\"permission_name\":\"program.index\",\"status\":true,\"description\":null}', NULL, 'http://localhost:8000/backend/route/105', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-03 13:36:24', '2026-10-03 13:36:24'),
(81, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\ManagementAccess\\Route', '110', 'Route #110', '{\"id\":110,\"route\":\"program.store\",\"permission_name\":\"program.store\",\"status\":true,\"description\":null}', NULL, 'http://localhost:8000/backend/route/110', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-03 13:36:35', '2026-10-03 13:36:35'),
(82, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\ManagementAccess\\Route', '115', 'Route #115', '{\"id\":115,\"route\":\"program.update\",\"permission_name\":\"program.update\",\"status\":true,\"description\":null}', NULL, 'http://localhost:8000/backend/route/115', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-03 13:36:45', '2026-10-03 13:36:45'),
(83, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '41', 'All Instructors', '{\"permission_name\":\"program.index\"}', '{\"permission_name\":\"instruktur.index\"}', 'http://localhost:8000/backend/menu/10/item/41', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-03 15:14:04', '2026-10-03 15:14:04'),
(84, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', 'updated', 'App\\Models\\User', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', '{\"no_hp\":null,\"sumber_informasi\":null}', '{\"no_hp\":\"43243277777\",\"sumber_informasi\":\"google\"}', 'http://localhost:8000/backend/dashboard/sumber-informasi', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-03 15:22:59', '2026-10-03 15:22:59'),
(85, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', 'created', 'App\\Models\\Payment\\Order', '01a1049e-afda-7188-b2f9-9fa3b404f6d3', 'Order #01a1049e-afda-7188-b2f9-9fa3b404f6d3', NULL, '{\"user_uuid\":\"c2187aff-68d0-40b9-a6f1-52738cee4ea4\",\"order_number\":\"ORD-I5HKUWFTWJ\",\"type\":\"package\",\"package_uuid\":\"01a095f2-d286-7241-b566-8027529b8196\",\"package_option_uuid\":\"01a09966-2965-7201-ab57-9f085e23bbc7\",\"class_schedule_uuid\":null,\"amount\":210000,\"status\":\"pending\",\"expired_at\":\"2026-10-05 08:54:31\",\"paid_at\":null,\"uuid\":\"01a1049e-afda-7188-b2f9-9fa3b404f6d3\",\"updated_at\":\"2026-10-04 08:54:31\",\"created_at\":\"2026-10-04 08:54:31\"}', 'http://localhost:8000/backend/orders', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-04 01:54:31', '2026-10-04 01:54:31'),
(86, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Class\\ClassModel', '01a07699-0698-73e1-8251-7afede34bbb1', 'Yin Yoga', '{\"image\":\"classes\\/XrR7ox1BqBNUKzCmgh6WEhsjbkT50AMjYQ3uiJYh.jpg\"}', '{\"image\":\"classes\\/klSn534j2UGQDMi3KFBImTLGx63CNMBOdTllJlUj.jpg\"}', 'http://127.0.0.1:8000/backend/classes/01a07699-0698-73e1-8251-7afede34bbb1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-04 02:04:07', '2026-10-04 02:04:07'),
(87, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Class\\ClassModel', '01a07699-0698-73e1-8251-7afede34bbb1', 'Yin Yoga', '{\"price\":\"1.00\"}', '{\"price\":\"0\"}', 'http://127.0.0.1:8000/backend/classes/01a07699-0698-73e1-8251-7afede34bbb1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-04 02:04:18', '2026-10-04 02:04:18'),
(88, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\MenuGroup', '26', 'Memberships', NULL, '{\"permission_name\":\"menu.memberships\",\"name\":\"Memberships\",\"icon\":\"bx-id-card\",\"status\":true,\"position\":55,\"updated_at\":\"2026-10-04 09:13:11\",\"created_at\":\"2026-10-04 09:13:11\",\"id\":26}', NULL, NULL, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(89, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\MenuItem', '61', 'All Members', NULL, '{\"route\":\"memberships.index\",\"menu_group_id\":26,\"name\":\"All Members\",\"status\":true,\"permission_name\":\"memberships.index\",\"position\":1,\"updated_at\":\"2026-10-04 09:13:11\",\"created_at\":\"2026-10-04 09:13:11\",\"id\":61}', NULL, NULL, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(90, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\MenuItem', '62', 'Bookings', NULL, '{\"route\":\"class-bookings.index\",\"menu_group_id\":26,\"name\":\"Bookings\",\"status\":true,\"permission_name\":\"class-bookings.index\",\"position\":2,\"updated_at\":\"2026-10-04 09:13:11\",\"created_at\":\"2026-10-04 09:13:11\",\"id\":62}', NULL, NULL, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(91, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '224', 'Route #224', NULL, '{\"route\":\"memberships.index\",\"permission_name\":\"memberships.index\",\"status\":true,\"updated_at\":\"2026-10-04 09:13:11\",\"created_at\":\"2026-10-04 09:13:11\",\"id\":224}', NULL, NULL, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(92, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '225', 'Route #225', NULL, '{\"route\":\"memberships.show\",\"permission_name\":\"memberships.show\",\"status\":true,\"updated_at\":\"2026-10-04 09:13:11\",\"created_at\":\"2026-10-04 09:13:11\",\"id\":225}', NULL, NULL, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(93, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '226', 'Route #226', NULL, '{\"route\":\"class-bookings.index\",\"permission_name\":\"class-bookings.index\",\"status\":true,\"updated_at\":\"2026-10-04 09:13:11\",\"created_at\":\"2026-10-04 09:13:11\",\"id\":226}', NULL, NULL, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(94, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '227', 'Route #227', NULL, '{\"route\":\"class-bookings.store\",\"permission_name\":\"class-bookings.store\",\"status\":true,\"updated_at\":\"2026-10-04 09:13:11\",\"created_at\":\"2026-10-04 09:13:11\",\"id\":227}', NULL, NULL, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(95, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '228', 'Route #228', NULL, '{\"route\":\"class-bookings.checkin\",\"permission_name\":\"class-bookings.checkin\",\"status\":true,\"updated_at\":\"2026-10-04 09:13:11\",\"created_at\":\"2026-10-04 09:13:11\",\"id\":228}', NULL, NULL, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(96, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '229', 'Route #229', NULL, '{\"route\":\"class-bookings.cancel\",\"permission_name\":\"class-bookings.cancel\",\"status\":true,\"updated_at\":\"2026-10-04 09:13:11\",\"created_at\":\"2026-10-04 09:13:11\",\"id\":229}', NULL, NULL, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(97, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '230', 'Route #230', NULL, '{\"route\":\"class-bookings.directCheckin\",\"permission_name\":\"class-bookings.checkin\",\"status\":true,\"updated_at\":\"2026-10-04 09:13:11\",\"created_at\":\"2026-10-04 09:13:11\",\"id\":230}', NULL, NULL, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(98, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '231', 'Route #231', NULL, '{\"route\":\"orders.approve\",\"permission_name\":\"orders.approve\",\"status\":true,\"updated_at\":\"2026-10-04 09:13:11\",\"created_at\":\"2026-10-04 09:13:11\",\"id\":231}', NULL, NULL, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(99, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '232', 'Route #232', NULL, '{\"route\":\"orders.reject\",\"permission_name\":\"orders.reject\",\"status\":true,\"updated_at\":\"2026-10-04 09:13:11\",\"created_at\":\"2026-10-04 09:13:11\",\"id\":232}', NULL, NULL, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(100, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '26', 'Memberships', '{\"permission_name\":\"menu.memberships\"}', '{\"permission_name\":\"memberships.index\"}', 'http://127.0.0.1:8000/backend/menu/26', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-04 02:23:55', '2026-10-04 02:23:55'),
(101, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\MenuGroup', '27', 'Memberships', NULL, '{\"permission_name\":\"menu.memberships\",\"name\":\"Memberships\",\"icon\":\"bx-id-card\",\"status\":true,\"position\":55,\"updated_at\":\"2026-10-04 09:31:55\",\"created_at\":\"2026-10-04 09:31:55\",\"id\":27}', NULL, NULL, NULL, '2026-10-04 02:31:55', '2026-10-04 02:31:55'),
(102, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\MenuItem', '63', 'All Members', NULL, '{\"route\":\"memberships.index\",\"menu_group_id\":27,\"name\":\"All Members\",\"status\":true,\"permission_name\":\"memberships.index\",\"position\":1,\"updated_at\":\"2026-10-04 09:31:55\",\"created_at\":\"2026-10-04 09:31:55\",\"id\":63}', NULL, NULL, NULL, '2026-10-04 02:31:55', '2026-10-04 02:31:55'),
(103, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\MenuItem', '64', 'Bookings', NULL, '{\"route\":\"class-bookings.index\",\"menu_group_id\":27,\"name\":\"Bookings\",\"status\":true,\"permission_name\":\"class-bookings.index\",\"position\":2,\"updated_at\":\"2026-10-04 09:31:55\",\"created_at\":\"2026-10-04 09:31:55\",\"id\":64}', NULL, NULL, NULL, '2026-10-04 02:31:55', '2026-10-04 02:31:55'),
(104, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '233', 'Route #233', NULL, '{\"route\":\"orders.proof\",\"permission_name\":\"orders.proof\",\"status\":true,\"updated_at\":\"2026-10-04 09:31:55\",\"created_at\":\"2026-10-04 09:31:55\",\"id\":233}', NULL, NULL, NULL, '2026-10-04 02:31:55', '2026-10-04 02:31:55'),
(108, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', 'updated', 'App\\Models\\Payment\\Order', '01a1049e-afda-7188-b2f9-9fa3b404f6d3', 'Order #01a1049e-afda-7188-b2f9-9fa3b404f6d3', '{\"proof_image_path\":null,\"proof_uploaded_at\":null}', '{\"proof_image_path\":\"proofs\\/lTa9j1FbqtOaGW4LDEw95vVjQ9JDz24MwX2UnFMi.png\",\"proof_uploaded_at\":\"2026-10-04 09:48:54\"}', 'http://localhost:8000/backend/orders/01a1049e-afda-7188-b2f9-9fa3b404f6d3/proof', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-04 02:48:55', '2026-10-04 02:48:55'),
(109, NULL, NULL, NULL, 'created', 'App\\Models\\ManagementAccess\\Route', '234', 'Route #234', NULL, '{\"route\":\"orders.proof.show\",\"permission_name\":\"orders.proof\",\"status\":true,\"updated_at\":\"2026-10-04 09:55:13\",\"created_at\":\"2026-10-04 09:55:13\",\"id\":234}', NULL, NULL, NULL, '2026-10-04 02:55:13', '2026-10-04 02:55:13'),
(110, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Payment\\Order', '01a1049e-afda-7188-b2f9-9fa3b404f6d3', 'Order #01a1049e-afda-7188-b2f9-9fa3b404f6d3', '{\"status\":\"pending\",\"paid_at\":null}', '{\"status\":\"paid\",\"paid_at\":\"2026-10-04 09:55:55\"}', 'http://127.0.0.1:8000/backend/orders/01a1049e-afda-7188-b2f9-9fa3b404f6d3/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-04 02:55:55', '2026-10-04 02:55:55'),
(111, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Payment\\Payment', '01a104d6-e859-7018-b76c-578ce8b292b2', 'Payment #01a104d6-e859-7018-b76c-578ce8b292b2', NULL, '{\"order_uuid\":\"01a1049e-afda-7188-b2f9-9fa3b404f6d3\",\"payment_gateway\":\"manual_transfer\",\"payment_type\":\"Bank Transfer\",\"gross_amount\":\"210000.00\",\"status\":\"paid\",\"paid_at\":\"2026-10-04 09:55:55\",\"uuid\":\"01a104d6-e859-7018-b76c-578ce8b292b2\",\"updated_at\":\"2026-10-04 09:55:55\",\"created_at\":\"2026-10-04 09:55:55\"}', 'http://127.0.0.1:8000/backend/orders/01a1049e-afda-7188-b2f9-9fa3b404f6d3/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-04 02:55:55', '2026-10-04 02:55:55'),
(112, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\UserPackage', '01a104d6-e85f-71b4-b9b8-676004bc9a16', 'UserPackage #01a104d6-e85f-71b4-b9b8-676004bc9a16', NULL, '{\"user_uuid\":\"c2187aff-68d0-40b9-a6f1-52738cee4ea4\",\"package_uuid\":\"01a095f2-d286-7241-b566-8027529b8196\",\"order_uuid\":\"01a1049e-afda-7188-b2f9-9fa3b404f6d3\",\"quota\":50,\"started_at\":\"2026-10-04 09:55:55\",\"expired_at\":\"2026-10-11 09:55:55\",\"status\":\"active\",\"uuid\":\"01a104d6-e85f-71b4-b9b8-676004bc9a16\",\"updated_at\":\"2026-10-04 09:55:55\",\"created_at\":\"2026-10-04 09:55:55\"}', 'http://127.0.0.1:8000/backend/orders/01a1049e-afda-7188-b2f9-9fa3b404f6d3/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-04 02:55:55', '2026-10-04 02:55:55'),
(113, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', 'Yogaroots.id', '{\"email_verified_at\":\"2026-09-11T07:41:34.000000Z\"}', '{\"email_verified_at\":\"2026-10-04 19:08:07\"}', 'http://127.0.0.1:8000/backend/user/57621d3c-c299-4cd2-b96a-9b887752cb73', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-04 12:08:07', '2026-10-04 12:08:07'),
(114, NULL, NULL, NULL, 'updated', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', '{\"views\":42}', '{\"views\":43}', NULL, NULL, NULL, '2026-10-04 12:11:01', '2026-10-04 12:11:01'),
(115, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', '{\"views\":43}', '{\"views\":44}', NULL, NULL, NULL, '2026-10-04 12:12:38', '2026-10-04 12:12:38'),
(116, '57621d3c-c299-4cd2-b96a-9b887752cb73', 'Yogaroots.id', 'tapayoga@yogaroots.id', 'updated', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', '{\"views\":44}', '{\"views\":45}', NULL, NULL, NULL, '2026-10-04 12:12:46', '2026-10-04 12:12:46'),
(117, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', '{\"views\":45}', '{\"views\":46}', NULL, NULL, NULL, '2026-10-04 12:12:53', '2026-10-04 12:12:53'),
(118, NULL, NULL, NULL, 'updated', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', '{\"views\":46}', '{\"views\":47}', NULL, NULL, NULL, '2026-10-04 12:21:48', '2026-10-04 12:21:48'),
(119, NULL, NULL, NULL, 'updated', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', '{\"views\":47}', '{\"views\":48}', NULL, NULL, NULL, '2026-10-04 12:24:36', '2026-10-04 12:24:36'),
(120, NULL, NULL, NULL, 'updated', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', '{\"views\":48}', '{\"views\":49}', NULL, NULL, NULL, '2026-10-04 12:26:48', '2026-10-04 12:26:48'),
(121, NULL, NULL, NULL, 'updated', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', '{\"views\":49}', '{\"views\":50}', NULL, NULL, NULL, '2026-10-04 12:42:41', '2026-10-04 12:42:41'),
(122, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Class\\ClassBooking', '01a108ed-d321-7097-80b2-199a86ce5d14', 'ClassBooking #01a108ed-d321-7097-80b2-199a86ce5d14', NULL, '{\"user_uuid\":\"c2187aff-68d0-40b9-a6f1-52738cee4ea4\",\"class_schedule_uuid\":\"01a07aba-eb15-700e-9ceb-7d37f2772c7e\",\"booking_date\":\"2026-10-05 00:00:00\",\"booking_type\":\"package\",\"quota_used\":1,\"status\":\"attended\",\"booked_at\":\"2026-10-05 04:59:26\",\"attended_at\":\"2026-10-05 04:59:26\",\"package_uuid\":\"01a095f2-d286-7241-b566-8027529b8196\",\"order_uuid\":\"01a1049e-afda-7188-b2f9-9fa3b404f6d3\",\"uuid\":\"01a108ed-d321-7097-80b2-199a86ce5d14\",\"updated_at\":\"2026-10-05 04:59:26\",\"created_at\":\"2026-10-05 04:59:26\"}', 'http://localhost:8000/backend/class-bookings/direct-check-in', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-04 21:59:26', '2026-10-04 21:59:26'),
(123, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\UserPackage', '01a104d6-e85f-71b4-b9b8-676004bc9a16', 'UserPackage #01a104d6-e85f-71b4-b9b8-676004bc9a16', '{\"quota\":50}', '{\"quota\":49}', 'http://localhost:8000/backend/class-bookings/direct-check-in', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-04 21:59:26', '2026-10-04 21:59:26'),
(124, NULL, NULL, NULL, 'updated', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', '{\"google_id\":null}', '{\"google_id\":\"112161865449406232159\"}', 'http://localhost:8000/auth/google/callback?authuser=0&code=4%2F0AXlqoi7EIQcm30RcZzGzxuTj-ahzZVgvCWvfxVHqHb93paIb_JljuL_zSH4_YImINHpaaw&iss=https%3A%2F%2Faccounts.google.com&prompt=none&scope=email%20profile%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile%20openid', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-06 10:06:14', '2026-10-06 10:06:14'),
(125, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'created', 'App\\Models\\Payment\\Order', '01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', 'Order #01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', NULL, '{\"user_uuid\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\",\"order_number\":\"ORD-BNEJIR8QFB\",\"type\":\"package\",\"package_uuid\":\"01a095f2-d286-7241-b566-8027529b8196\",\"package_option_uuid\":\"01a09966-2965-7201-ab57-9f085e23bbc7\",\"class_schedule_uuid\":null,\"amount\":210000,\"status\":\"pending\",\"expired_at\":\"2026-10-07 17:12:11\",\"paid_at\":null,\"uuid\":\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"updated_at\":\"2026-10-06 17:12:11\",\"created_at\":\"2026-10-06 17:12:11\"}', 'http://localhost:8000/backend/orders', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-10-06 10:12:11', '2026-10-06 10:12:11'),
(126, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Payment\\Order', '01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', 'Order #01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', '{\"proof_image_path\":null,\"proof_uploaded_at\":null}', '{\"proof_image_path\":\"proofs\\/WrmyZ098hpkUmpUTw9LmcOqDkUqtGOeLdNeyBjqM.png\",\"proof_uploaded_at\":\"2026-10-06 17:12:24\"}', 'http://localhost:8000/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be/proof', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-10-06 10:12:24', '2026-10-06 10:12:24'),
(127, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Payment\\Order', '01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', 'Order #01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', '{\"status\":\"pending\",\"paid_at\":null}', '{\"status\":\"paid\",\"paid_at\":\"2026-10-06 17:13:14\"}', 'http://127.0.0.1:8000/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-06 10:13:14', '2026-10-06 10:13:14'),
(128, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Payment\\Payment', '01a110b4-000d-7377-9dab-512dd7adc04c', 'Payment #01a110b4-000d-7377-9dab-512dd7adc04c', NULL, '{\"order_uuid\":\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"payment_gateway\":\"manual_transfer\",\"payment_type\":\"Bank Transfer\",\"gross_amount\":\"210000.00\",\"status\":\"paid\",\"paid_at\":\"2026-10-06 17:13:14\",\"uuid\":\"01a110b4-000d-7377-9dab-512dd7adc04c\",\"updated_at\":\"2026-10-06 17:13:14\",\"created_at\":\"2026-10-06 17:13:14\"}', 'http://127.0.0.1:8000/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-06 10:13:14', '2026-10-06 10:13:14'),
(129, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\UserPackage', '01a110b4-0015-7154-9381-8d3a9868f59e', 'UserPackage #01a110b4-0015-7154-9381-8d3a9868f59e', NULL, '{\"user_uuid\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\",\"package_uuid\":\"01a095f2-d286-7241-b566-8027529b8196\",\"order_uuid\":\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"quota\":50,\"started_at\":\"2026-10-06 17:13:14\",\"expired_at\":\"2026-10-13 17:13:14\",\"status\":\"active\",\"uuid\":\"01a110b4-0015-7154-9381-8d3a9868f59e\",\"updated_at\":\"2026-10-06 17:13:14\",\"created_at\":\"2026-10-06 17:13:14\"}', 'http://127.0.0.1:8000/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-06 10:13:14', '2026-10-06 10:13:14'),
(130, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'created', 'App\\Models\\Class\\ClassBooking', '01a110b4-7661-709d-bd19-b0e59c9caf20', 'ClassBooking #01a110b4-7661-709d-bd19-b0e59c9caf20', NULL, '{\"user_uuid\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\",\"class_schedule_uuid\":\"01a07699-68e3-71cc-892a-e0557ae317ee\",\"booking_date\":\"2026-10-06 00:00:00\",\"booking_type\":\"package\",\"quota_used\":0,\"status\":\"confirmed\",\"booked_at\":\"2026-10-06 17:13:44\",\"package_uuid\":\"01a095f2-d286-7241-b566-8027529b8196\",\"order_uuid\":\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"uuid\":\"01a110b4-7661-709d-bd19-b0e59c9caf20\",\"updated_at\":\"2026-10-06 17:13:44\",\"created_at\":\"2026-10-06 17:13:44\"}', 'http://localhost:8000/backend/class-bookings', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-10-06 10:13:44', '2026-10-06 10:13:44'),
(131, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Class\\ClassBooking', '01a110b4-7661-709d-bd19-b0e59c9caf20', 'ClassBooking #01a110b4-7661-709d-bd19-b0e59c9caf20', '{\"quota_used\":0,\"status\":\"confirmed\",\"attended_at\":null}', '{\"quota_used\":1,\"status\":\"attended\",\"attended_at\":\"2026-10-06 17:13:47\"}', 'http://localhost:8000/backend/class-bookings/01a110b4-7661-709d-bd19-b0e59c9caf20/check-in', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-10-06 10:13:47', '2026-10-06 10:13:47'),
(132, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\UserPackage', '01a110b4-0015-7154-9381-8d3a9868f59e', 'UserPackage #01a110b4-0015-7154-9381-8d3a9868f59e', '{\"quota\":50}', '{\"quota\":49}', 'http://localhost:8000/backend/class-bookings/01a110b4-7661-709d-bd19-b0e59c9caf20/check-in', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-10-06 10:13:47', '2026-10-06 10:13:47'),
(133, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'created', 'App\\Models\\Payment\\Order', '01a110cc-d5ec-7387-a3c5-87cde025d404', 'Order #01a110cc-d5ec-7387-a3c5-87cde025d404', NULL, '{\"user_uuid\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\",\"order_number\":\"ORD-E5WX8OSWPO\",\"type\":\"package\",\"package_uuid\":\"01a095f2-d286-7241-b566-8027529b8196\",\"package_option_uuid\":\"01a09966-2965-7201-ab57-9f085e23bbc7\",\"class_schedule_uuid\":null,\"amount\":210000,\"status\":\"pending\",\"expired_at\":\"2026-10-07 17:40:21\",\"paid_at\":null,\"uuid\":\"01a110cc-d5ec-7387-a3c5-87cde025d404\",\"updated_at\":\"2026-10-06 17:40:21\",\"created_at\":\"2026-10-06 17:40:21\"}', 'http://localhost:8000/backend/orders', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-10-06 10:40:22', '2026-10-06 10:40:22'),
(134, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'created', 'App\\Models\\Class\\ClassBooking', '01a110d1-2290-7247-9c97-caddec16187a', 'ClassBooking #01a110d1-2290-7247-9c97-caddec16187a', NULL, '{\"user_uuid\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\",\"class_schedule_uuid\":\"01a07699-68e3-71cc-892a-e0557ae317ee\",\"booking_date\":\"2026-10-13 00:00:00\",\"booking_type\":\"package\",\"quota_used\":0,\"status\":\"confirmed\",\"booked_at\":\"2026-10-06 17:45:03\",\"attended_at\":null,\"package_uuid\":\"01a095f2-d286-7241-b566-8027529b8196\",\"order_uuid\":\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"uuid\":\"01a110d1-2290-7247-9c97-caddec16187a\",\"updated_at\":\"2026-10-06 17:45:03\",\"created_at\":\"2026-10-06 17:45:03\"}', 'http://localhost:8000/backend/class-bookings', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-10-06 10:45:03', '2026-10-06 10:45:03'),
(135, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'created', 'App\\Models\\Class\\ClassBooking', '01a110d1-bfa6-7302-8c13-a5d11e084fee', 'ClassBooking #01a110d1-bfa6-7302-8c13-a5d11e084fee', NULL, '{\"user_uuid\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\",\"class_schedule_uuid\":\"01a07aba-eb15-700e-9ceb-7d37f2772c7e\",\"booking_date\":\"2026-10-12 00:00:00\",\"booking_type\":\"package\",\"quota_used\":0,\"status\":\"confirmed\",\"booked_at\":\"2026-10-06 17:45:43\",\"attended_at\":null,\"package_uuid\":\"01a095f2-d286-7241-b566-8027529b8196\",\"order_uuid\":\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"uuid\":\"01a110d1-bfa6-7302-8c13-a5d11e084fee\",\"updated_at\":\"2026-10-06 17:45:43\",\"created_at\":\"2026-10-06 17:45:43\"}', 'http://localhost:8000/backend/class-bookings', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-10-06 10:45:43', '2026-10-06 10:45:43'),
(136, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Class\\ClassBooking', '01a110d1-bfa6-7302-8c13-a5d11e084fee', 'ClassBooking #01a110d1-bfa6-7302-8c13-a5d11e084fee', '{\"status\":\"confirmed\"}', '{\"status\":\"cancelled\"}', 'http://localhost:8000/backend/class-bookings/01a110d1-bfa6-7302-8c13-a5d11e084fee/cancel', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-10-06 10:46:14', '2026-10-06 10:46:14');
INSERT INTO `audit_logs` (`id`, `user_uuid`, `user_name`, `user_email`, `event`, `auditable_type`, `auditable_id`, `auditable_label`, `old_values`, `new_values`, `url`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(137, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Class\\ClassBooking', '01a110d1-2290-7247-9c97-caddec16187a', 'ClassBooking #01a110d1-2290-7247-9c97-caddec16187a', '{\"status\":\"confirmed\"}', '{\"status\":\"cancelled\"}', 'http://localhost:8000/backend/class-bookings/01a110d1-2290-7247-9c97-caddec16187a/cancel', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-10-06 10:46:27', '2026-10-06 10:46:27'),
(138, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'created', 'App\\Models\\Class\\ClassBooking', '01a110f3-8832-70ac-94c1-332fe3519fae', 'ClassBooking #01a110f3-8832-70ac-94c1-332fe3519fae', NULL, '{\"user_uuid\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\",\"class_schedule_uuid\":\"01a07699-68e3-71cc-892a-e0557ae317ee\",\"booking_date\":\"2026-10-13 00:00:00\",\"booking_type\":\"package\",\"quota_used\":0,\"status\":\"confirmed\",\"booked_at\":\"2026-10-06 18:22:37\",\"attended_at\":null,\"package_uuid\":\"01a095f2-d286-7241-b566-8027529b8196\",\"order_uuid\":\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"uuid\":\"01a110f3-8832-70ac-94c1-332fe3519fae\",\"updated_at\":\"2026-10-06 18:22:38\",\"created_at\":\"2026-10-06 18:22:38\"}', 'http://localhost:8000/backend/class-bookings', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-10-06 11:22:38', '2026-10-06 11:22:38'),
(139, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Class\\ClassBooking', '01a110f3-8832-70ac-94c1-332fe3519fae', 'ClassBooking #01a110f3-8832-70ac-94c1-332fe3519fae', '{\"status\":\"confirmed\"}', '{\"status\":\"cancelled\"}', 'http://127.0.0.1:8000/backend/class-bookings/01a110f3-8832-70ac-94c1-332fe3519fae/cancel', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-06 11:31:43', '2026-10-06 11:31:43'),
(140, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'created', 'App\\Models\\Class\\ClassBooking', '01a110fc-4da0-71d1-8967-fc7e99344562', 'ClassBooking #01a110fc-4da0-71d1-8967-fc7e99344562', NULL, '{\"user_uuid\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\",\"class_schedule_uuid\":\"01a07aba-eb15-700e-9ceb-7d37f2772c7e\",\"booking_date\":\"2026-10-12 00:00:00\",\"booking_type\":\"package\",\"quota_used\":0,\"status\":\"confirmed\",\"booked_at\":\"2026-10-06 18:32:12\",\"attended_at\":null,\"package_uuid\":\"01a095f2-d286-7241-b566-8027529b8196\",\"order_uuid\":\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"uuid\":\"01a110fc-4da0-71d1-8967-fc7e99344562\",\"updated_at\":\"2026-10-06 18:32:12\",\"created_at\":\"2026-10-06 18:32:12\"}', 'http://localhost:8000/backend/class-bookings', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-06 11:32:12', '2026-10-06 11:32:12'),
(141, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Class\\ClassBooking', '01a11107-c2bf-735e-a6a8-8095e7b4234a', 'ClassBooking #01a11107-c2bf-735e-a6a8-8095e7b4234a', NULL, '{\"user_uuid\":\"c2187aff-68d0-40b9-a6f1-52738cee4ea4\",\"class_schedule_uuid\":\"01a07699-68e3-71cc-892a-e0557ae317ee\",\"booking_date\":\"2026-10-06 00:00:00\",\"booking_type\":\"package\",\"quota_used\":1,\"status\":\"attended\",\"booked_at\":\"2026-10-06 18:44:43\",\"attended_at\":\"2026-10-06 18:44:43\",\"package_uuid\":\"01a095f2-d286-7241-b566-8027529b8196\",\"order_uuid\":\"01a1049e-afda-7188-b2f9-9fa3b404f6d3\",\"uuid\":\"01a11107-c2bf-735e-a6a8-8095e7b4234a\",\"updated_at\":\"2026-10-06 18:44:43\",\"created_at\":\"2026-10-06 18:44:43\"}', 'http://127.0.0.1:8000/backend/class-bookings/direct-check-in', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-06 11:44:43', '2026-10-06 11:44:43'),
(142, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\UserPackage', '01a104d6-e85f-71b4-b9b8-676004bc9a16', 'UserPackage #01a104d6-e85f-71b4-b9b8-676004bc9a16', '{\"quota\":49}', '{\"quota\":48}', 'http://127.0.0.1:8000/backend/class-bookings/direct-check-in', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-06 11:44:43', '2026-10-06 11:44:43'),
(143, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'created', 'App\\Models\\Payment\\Order', '01a11182-8463-7057-a0f4-8cf88279c6c2', 'Order #01a11182-8463-7057-a0f4-8cf88279c6c2', NULL, '{\"user_uuid\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\",\"order_number\":\"ORD-DHVCIG8ZQL\",\"type\":\"package\",\"package_uuid\":\"01a095f2-d286-7241-b566-8027529b8196\",\"package_option_uuid\":\"01a09966-2965-7201-ab57-9f085e23bbc7\",\"class_schedule_uuid\":null,\"amount\":210000,\"status\":\"pending\",\"expired_at\":\"2026-10-07 20:58:48\",\"paid_at\":null,\"uuid\":\"01a11182-8463-7057-a0f4-8cf88279c6c2\",\"updated_at\":\"2026-10-06 20:58:48\",\"created_at\":\"2026-10-06 20:58:48\"}', 'http://localhost:8000/backend/orders', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-06 13:58:48', '2026-10-06 13:58:48'),
(144, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Class\\ClassBooking', '01a110fc-4da0-71d1-8967-fc7e99344562', 'ClassBooking #01a110fc-4da0-71d1-8967-fc7e99344562', '{\"status\":\"confirmed\"}', '{\"status\":\"cancelled\"}', 'http://localhost:8000/backend/class-bookings/01a110fc-4da0-71d1-8967-fc7e99344562/cancel', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', '2026-10-06 14:11:47', '2026-10-06 14:11:47'),
(145, NULL, NULL, NULL, 'updated', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', '{\"views\":50}', '{\"views\":51}', NULL, NULL, NULL, '2026-11-18 19:19:47', '2026-11-18 19:19:47'),
(146, NULL, NULL, NULL, 'updated', 'App\\Models\\WebsiteIdentity', '39860859-6a83-4cae-aa27-659334baff1c', 'WebsiteIdentity #39860859-6a83-4cae-aa27-659334baff1c', '{\"site_name\":\"Laravel\",\"site_title\":null,\"tagline\":null,\"short_description\":null}', '{\"site_name\":\"Sagita Wedding\",\"site_title\":\"Sagita Wedding\",\"tagline\":\"Wedding Planner\",\"short_description\":\"Wedding planning, concept, and celebration services designed with elegance and care.\"}', NULL, NULL, NULL, '2026-11-18 20:14:01', '2026-11-18 20:14:01'),
(147, NULL, NULL, NULL, 'created', 'App\\Models\\LoginLockout', '3', 'LoginLockout #3', NULL, '{\"type\":\"ip\",\"value\":\"127.0.0.1\",\"attempts\":1,\"last_attempt_at\":\"2026-11-19 05:47:12\",\"updated_at\":\"2026-11-19 05:47:12\",\"created_at\":\"2026-11-19 05:47:12\",\"id\":3}', 'http://127.0.0.1:8000/auth/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-18 22:47:12', '2026-11-18 22:47:12'),
(148, NULL, NULL, NULL, 'created', 'App\\Models\\LoginLockout', '4', 'LoginLockout #4', NULL, '{\"type\":\"email\",\"value\":\"super@admin.com\",\"attempts\":1,\"last_attempt_at\":\"2026-11-19 05:47:12\",\"updated_at\":\"2026-11-19 05:47:12\",\"created_at\":\"2026-11-19 05:47:12\",\"id\":4}', 'http://127.0.0.1:8000/auth/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-18 22:47:12', '2026-11-18 22:47:12'),
(149, NULL, NULL, NULL, 'updated', 'App\\Models\\LoginLockout', '3', 'LoginLockout #3', '{\"attempts\":1}', '{\"attempts\":2}', 'http://127.0.0.1:8000/auth/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-18 22:47:13', '2026-11-18 22:47:13'),
(150, NULL, NULL, NULL, 'updated', 'App\\Models\\LoginLockout', '4', 'LoginLockout #4', '{\"attempts\":1,\"last_attempt_at\":\"2026-11-19T05:47:12.000000Z\"}', '{\"attempts\":2,\"last_attempt_at\":\"2026-11-19 05:47:13\"}', 'http://127.0.0.1:8000/auth/login', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-18 22:47:13', '2026-11-18 22:47:13'),
(151, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\Banner', '9946050a-6755-4580-a102-d33fe42cea1b', 'Banner #9946050a-6755-4580-a102-d33fe42cea1b', '{\"uuid\":\"9946050a-6755-4580-a102-d33fe42cea1b\",\"tipe\":\"foto\",\"video_url\":null,\"nama\":\"galeri 7\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/QTkrOknw5o4OcAQK3AdkDxPwZOX0TUlg37oENXGY.jpg\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, 'http://127.0.0.1:8000/backend/banner/9946050a-6755-4580-a102-d33fe42cea1b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-18 23:27:59', '2026-11-18 23:27:59'),
(152, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\Banner', 'cd8f1370-54be-44de-94df-e0114b7d2f84', 'Banner #cd8f1370-54be-44de-94df-e0114b7d2f84', '{\"uuid\":\"cd8f1370-54be-44de-94df-e0114b7d2f84\",\"tipe\":\"foto\",\"video_url\":null,\"nama\":\"Banner slider\",\"deskripsi\":\"Start. Stabilize. Transform\",\"link\":null,\"gambar\":\"banners\\/ytsvrxIYf473coMMgB5x4Fl7xJhlTLskGuDuwvJ1.jpg\",\"posisi\":\"slider\",\"status\":\"active\"}', NULL, 'http://127.0.0.1:8000/backend/banner/cd8f1370-54be-44de-94df-e0114b7d2f84', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-18 23:28:02', '2026-11-18 23:28:02'),
(153, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\Banner', '47618df4-1983-4686-b9fd-f67aa7fae89f', 'Banner #47618df4-1983-4686-b9fd-f67aa7fae89f', '{\"uuid\":\"47618df4-1983-4686-b9fd-f67aa7fae89f\",\"tipe\":\"foto\",\"video_url\":null,\"nama\":\"galeri 6\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/LmAdqgq38oIHGL7svW6sb4int8WW0v1b4pXoXO28.jpg\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, 'http://127.0.0.1:8000/backend/banner/47618df4-1983-4686-b9fd-f67aa7fae89f', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-18 23:28:05', '2026-11-18 23:28:05'),
(154, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\Banner', '06c5d7af-ea7a-490a-8908-5aac3b6e20c1', 'Banner #06c5d7af-ea7a-490a-8908-5aac3b6e20c1', '{\"uuid\":\"06c5d7af-ea7a-490a-8908-5aac3b6e20c1\",\"tipe\":\"foto\",\"video_url\":null,\"nama\":\"Galeri 5\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/l2ZIgA9SVhyhxnkTNdy8ljwvLVtmnJHouXmhekyg.jpg\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, 'http://127.0.0.1:8000/backend/banner/06c5d7af-ea7a-490a-8908-5aac3b6e20c1', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-18 23:28:08', '2026-11-18 23:28:08'),
(155, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\Banner', '8da02e21-bc7f-4816-ac56-1d762d11a546', 'Banner #8da02e21-bc7f-4816-ac56-1d762d11a546', '{\"uuid\":\"8da02e21-bc7f-4816-ac56-1d762d11a546\",\"tipe\":\"foto\",\"video_url\":null,\"nama\":\"Galeri 4\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/HPy5cA2nP4JOTZy02wkMUToZoNPHKcfD4277jlfg.jpg\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, 'http://127.0.0.1:8000/backend/banner/8da02e21-bc7f-4816-ac56-1d762d11a546', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-18 23:28:11', '2026-11-18 23:28:11'),
(156, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\Banner', 'f7bd2370-bdad-42dc-909b-1239c040d0d3', 'Banner #f7bd2370-bdad-42dc-909b-1239c040d0d3', '{\"uuid\":\"f7bd2370-bdad-42dc-909b-1239c040d0d3\",\"tipe\":\"foto\",\"video_url\":null,\"nama\":\"galeri 2\",\"deskripsi\":\"Galeri 2\",\"link\":null,\"gambar\":\"banners\\/oAwFgPkCIzLItkoQcSMMBRrtz9whT5LKk4gicW39.jpg\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, 'http://127.0.0.1:8000/backend/banner/f7bd2370-bdad-42dc-909b-1239c040d0d3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-18 23:28:14', '2026-11-18 23:28:14'),
(157, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\Banner', 'c72bd89f-0849-40a1-927c-b9774e84bd2b', 'Banner #c72bd89f-0849-40a1-927c-b9774e84bd2b', '{\"uuid\":\"c72bd89f-0849-40a1-927c-b9774e84bd2b\",\"tipe\":\"foto\",\"video_url\":null,\"nama\":\"galeri 3\",\"deskripsi\":null,\"link\":null,\"gambar\":\"banners\\/3Uiy3wdss9hUamammpQfS97jQiKZD3EQ0o9V8mS3.jpg\",\"posisi\":\"galeri\",\"status\":\"active\"}', NULL, 'http://127.0.0.1:8000/backend/banner/c72bd89f-0849-40a1-927c-b9774e84bd2b', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-18 23:28:17', '2026-11-18 23:28:17'),
(158, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\VendorsCategory', '11e863ef-4739-4bcf-b7d1-d599b336bb09', 'Wedding Planner', '{\"name\":\"Alignment\",\"slug\":\"alignment\",\"description\":\"Focuses on proper body alignment, anatomical awareness, and safe movement to improve posture, stability, body mechanics, and movement efficiency.\"}', '{\"name\":\"Wedding Planner\",\"slug\":\"wedding-planner\",\"description\":\"Layanan profesional untuk merencanakan, mengatur, dan mengoordinasikan seluruh kebutuhan acara pernikahan.\"}', 'http://127.0.0.1:8000/backend/vendors_categories/11e863ef-4739-4bcf-b7d1-d599b336bb09', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-19 00:36:21', '2026-11-19 00:36:21'),
(159, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\VendorsCategory', '2146b32e-85f3-4df1-b8c6-4718bd786114', 'Dekorasi', '{\"name\":\"Hatha\",\"slug\":\"hatha\",\"description\":\"A foundational yoga practice combining physical postures, breathing techniques, and mindful awareness to develop strength, flexibility, balance, and relaxation.\"}', '{\"name\":\"Dekorasi\",\"slug\":\"dekorasi\",\"description\":\"Penyedia layanan dekorasi pernikahan untuk menciptakan suasana acara yang indah, elegan, dan berkesan.\"}', 'http://127.0.0.1:8000/backend/vendors_categories/2146b32e-85f3-4df1-b8c6-4718bd786114', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-19 00:36:32', '2026-11-19 00:36:32'),
(160, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\VendorsCategory', '28b37aad-a252-4bbf-9284-d25c63047b19', 'Katering', '{\"name\":\"Yin Yoga\",\"slug\":\"yin-yoga\",\"description\":\"A slow-paced practice involving longer-held postures to gently work with connective tissues, improve flexibility, and encourage deep relaxation.\"}', '{\"name\":\"Katering\",\"slug\":\"katering\",\"description\":\"Penyedia makanan dan minuman untuk memenuhi kebutuhan konsumsi tamu undangan selama acara pernikahan.\"}', 'http://127.0.0.1:8000/backend/vendors_categories/28b37aad-a252-4bbf-9284-d25c63047b19', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-19 00:36:43', '2026-11-19 00:36:43'),
(161, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\VendorsCategory', '3cddd283-5b50-493a-9cab-cb04eef88946', 'Busana & MUA', '{\"name\":\"Holistic\",\"slug\":\"holistic\",\"description\":\"A whole-person approach integrating movement, breathwork, mindfulness, meditation, and relaxation to support physical, mental, emotional, and overall well-being.\"}', '{\"name\":\"Busana & MUA\",\"slug\":\"busana-mua\",\"description\":\"Penyedia busana pengantin dan jasa tata rias untuk menciptakan penampilan terbaik di hari istimewa.\"}', 'http://127.0.0.1:8000/backend/vendors_categories/3cddd283-5b50-493a-9cab-cb04eef88946', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-19 00:36:55', '2026-11-19 00:36:55'),
(162, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\VendorsCategory', '4240ba9b-225f-4544-a558-cc4d30ddefa2', 'Dokumentasi', '{\"name\":\"Breathwork & Meditation\",\"slug\":\"breathwork-meditation\",\"description\":\"Combines conscious breathing techniques and meditation practices to develop self-awareness, promote relaxation, improve focus, and cultivate inner balance.\"}', '{\"name\":\"Dokumentasi\",\"slug\":\"dokumentasi\",\"description\":\"Penyedia layanan fotografi dan videografi untuk mengabadikan setiap momen berharga dalam pernikahan.\"}', 'http://127.0.0.1:8000/backend/vendors_categories/4240ba9b-225f-4544-a558-cc4d30ddefa2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-19 00:37:09', '2026-11-19 00:37:09'),
(163, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\VendorsCategory', '50b72628-5cd5-4f8d-937a-a41852efc90d', 'Venue', '{\"name\":\"Asthanga\",\"slug\":\"asthanga\",\"description\":\"A structured and physically demanding practice based on a progressive sequence of postures, emphasizing strength, flexibility, discipline, and breath control.\"}', '{\"name\":\"Venue\",\"slug\":\"venue\",\"description\":\"Penyedia lokasi atau tempat penyelenggaraan acara pernikahan sesuai konsep dan kebutuhan pasangan.\"}', 'http://127.0.0.1:8000/backend/vendors_categories/50b72628-5cd5-4f8d-937a-a41852efc90d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-19 00:37:42', '2026-11-19 00:37:42'),
(164, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\VendorsCategory', '50b72628-5cd5-4f8d-937a-a41852efc90d', 'Entertainment', '{\"name\":\"Venue\",\"slug\":\"venue\",\"description\":\"Penyedia lokasi atau tempat penyelenggaraan acara pernikahan sesuai konsep dan kebutuhan pasangan.\"}', '{\"name\":\"Entertainment\",\"slug\":\"entertainment\",\"description\":\"Penyedia hiburan dan pengisi acara untuk menciptakan suasana pernikahan yang meriah dan berkesan.\"}', 'http://127.0.0.1:8000/backend/vendors_categories/50b72628-5cd5-4f8d-937a-a41852efc90d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-19 00:37:56', '2026-11-19 00:37:56'),
(165, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\VendorsCategory', '6b28240c-d7d9-4b09-850b-6dcbb3875c18', 'Venue', '{\"name\":\"Restorative\",\"slug\":\"restorative\",\"description\":\"A gentle and supportive practice using props to promote deep relaxation, release physical tension, and support the body\'s natural recovery.\"}', '{\"name\":\"Venue\",\"slug\":\"venue\",\"description\":\"Penyedia lokasi atau tempat penyelenggaraan acara pernikahan sesuai konsep dan kebutuhan pasangan.\"}', 'http://127.0.0.1:8000/backend/vendors_categories/6b28240c-d7d9-4b09-850b-6dcbb3875c18', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-19 00:38:09', '2026-11-19 00:38:09'),
(166, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\VendorsCategory', 'ae3ca862-ed29-4025-bab9-4caff951820e', 'Souvenir & Undangan', '{\"name\":\"Prenatal\",\"slug\":\"prenatal\",\"description\":\"Yoga adapted for pregnancy to support mobility, strength, relaxation, breathing, and physical comfort throughout the different stages of pregnancy.\"}', '{\"name\":\"Souvenir & Undangan\",\"slug\":\"souvenir-undangan\",\"description\":\"Penyedia undangan dan souvenir pernikahan sebagai pelengkap serta kenang-kenangan bagi para tamu.\"}', 'http://127.0.0.1:8000/backend/vendors_categories/ae3ca862-ed29-4025-bab9-4caff951820e', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-19 00:38:20', '2026-11-19 00:38:20'),
(167, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\VendorsCategory', 'cd60d5c4-b89e-42cd-bafb-b10ded0538ec', 'Vinyasa', '{\"uuid\":\"cd60d5c4-b89e-42cd-bafb-b10ded0538ec\",\"name\":\"Vinyasa\",\"slug\":\"vinyasa\",\"description\":\"A dynamic practice that connects movement with breath through flowing sequences, helping improve strength, mobility, coordination, and body awareness.\",\"is_active\":\"active\"}', NULL, 'http://127.0.0.1:8000/backend/vendors_categories/cd60d5c4-b89e-42cd-bafb-b10ded0538ec', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', '2026-11-19 00:38:28', '2026-11-19 00:38:28'),
(168, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Faq', '4b9411a3-ccc5-44fd-bc24-e8dc7065773c', 'Faq #4b9411a3-ccc5-44fd-bc24-e8dc7065773c', '{\"pertanyaan\":\"When should we start planning our wedding?\",\"jawaban\":\"Ideally, start 6\\u201312 months before the big day. Setting the date, venue, and budget early gives you access to a wider range of options.\"}', '{\"pertanyaan\":\"Saya ingin menikah, tetapi belum tahu harus mulai dari mana. Apakah bisa dibantu?\",\"jawaban\":\"Tentu! Sagita Wedding Planner siap membantu Anda mulai dari menentukan konsep pernikahan, menyusun kebutuhan acara, memilih vendor, hingga mempersiapkan seluruh rangkaian acara pernikahan.\"}', 'http://127.0.0.1:8000/backend/faq/4b9411a3-ccc5-44fd-bc24-e8dc7065773c', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 01:44:00', '2026-11-20 01:44:00'),
(169, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Faq', 'fa2b17f9-f0d4-4849-8373-8d787b80113e', 'Faq #fa2b17f9-f0d4-4849-8373-8d787b80113e', '{\"pertanyaan\":\"What is included in a wedding package?\",\"kategori\":\"Paket & Layanan\",\"jawaban\":\"Packages can include decoration, photography, videography, MC, music, and event coordination. Details can be tailored to your needs.\"}', '{\"pertanyaan\":\"Kapan sebaiknya saya mulai menggunakan jasa Wedding Organizer?\",\"kategori\":\"Perencanaan Pernikahan\",\"jawaban\":\"Kami menyarankan untuk mulai berkonsultasi sekitar 6\\u201312 bulan sebelum hari pernikahan agar persiapan lebih matang. Namun, Anda tetap bisa menghubungi kami meskipun waktu pernikahan sudah dekat.\"}', 'http://127.0.0.1:8000/backend/faq/fa2b17f9-f0d4-4849-8373-8d787b80113e', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 01:44:32', '2026-11-20 01:44:32'),
(170, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Faq', '9f25d34e-2a60-42cf-8379-ed069dc50ac0', 'Faq #9f25d34e-2a60-42cf-8379-ed069dc50ac0', '{\"pertanyaan\":\"How do I book a package and make a payment?\",\"kategori\":\"Pemesanan & Pembayaran\",\"jawaban\":\"Choose a package and submit the booking form. Our team will send an invoice, and payment can be made by bank transfer based on the agreed schedule.\"}', '{\"pertanyaan\":\"Apakah saya harus sudah memiliki konsep pernikahan sebelum menghubungi Sagita Wedding Planner?\",\"kategori\":\"Perencanaan Pernikahan\",\"jawaban\":\"Tidak harus. Anda bisa berkonsultasi terlebih dahulu meskipun belum memiliki konsep. Tim kami akan membantu memberikan ide dan rekomendasi sesuai keinginan serta anggaran Anda.\"}', 'http://127.0.0.1:8000/backend/faq/9f25d34e-2a60-42cf-8379-ed069dc50ac0', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 01:45:07', '2026-11-20 01:45:07'),
(171, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Faq', '6bb623f8-916a-436b-85b3-a57035fe5ac5', 'Faq #6bb623f8-916a-436b-85b3-a57035fe5ac5', '{\"pertanyaan\":\"Can we choose our own vendors?\",\"kategori\":\"Vendor & Mitra\",\"jawaban\":\"Absolutely. You can select from our recommended vendors or propose your own. We will help make sure they fit your concept and budget.\"}', '{\"pertanyaan\":\"Apakah Sagita Wedding Planner melayani berbagai konsep dan adat pernikahan?\",\"kategori\":\"Perencanaan Pernikahan\",\"jawaban\":\"Ya, kami dapat membantu mempersiapkan berbagai konsep pernikahan, baik modern, tradisional, intimate wedding, maupun konsep lainnya sesuai kebutuhan dan kesepakatan.\"}', 'http://127.0.0.1:8000/backend/faq/6bb623f8-916a-436b-85b3-a57035fe5ac5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 01:45:52', '2026-11-20 01:45:52'),
(172, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Faq', 'fd90f57c-53aa-4b5d-8e11-faea4808686d', 'Faq #fd90f57c-53aa-4b5d-8e11-faea4808686d', '{\"pertanyaan\":\"What happens on the wedding day?\",\"kategori\":\"Pelaksanaan Acara\",\"jawaban\":\"Our team arrives early for setup, vendor coordination, and final checks, so you can enjoy the celebration with your guests.\",\"urutan\":5}', '{\"pertanyaan\":\"Layanan apa saja yang tersedia di Sagita Wedding Planner?\",\"kategori\":\"Paket & Layanan\",\"jawaban\":\"Kami menyediakan layanan perencanaan dan pengelolaan acara pernikahan yang dapat mencakup koordinasi acara, pengelolaan vendor, penyusunan rundown, dan kebutuhan lainnya sesuai paket yang dipilih.\",\"urutan\":\"1\"}', 'http://127.0.0.1:8000/backend/faq/fd90f57c-53aa-4b5d-8e11-faea4808686d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 01:47:58', '2026-11-20 01:47:58'),
(173, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', '30c48412-a82b-46c0-853b-540433f3a52f', 'Faq #30c48412-a82b-46c0-853b-540433f3a52f', NULL, '{\"pertanyaan\":\"Bagaimana cara mengetahui paket yang cocok untuk pernikahan saya?\",\"kategori\":\"Paket & Layanan\",\"jawaban\":\"Anda cukup menyampaikan tanggal pernikahan, lokasi acara, perkiraan jumlah tamu, dan kebutuhan yang diinginkan. Tim kami akan membantu merekomendasikan paket yang sesuai.\",\"urutan\":\"2\",\"status\":\"active\",\"uuid\":\"30c48412-a82b-46c0-853b-540433f3a52f\",\"updated_at\":\"2026-11-20 08:49:12\",\"created_at\":\"2026-11-20 08:49:12\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 01:49:12', '2026-11-20 01:49:12'),
(174, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', '588f2d39-d7bb-4d8f-a78f-63d03e9d95b6', 'Faq #588f2d39-d7bb-4d8f-a78f-63d03e9d95b6', NULL, '{\"pertanyaan\":\"Apakah paket pernikahan bisa disesuaikan dengan budget?\",\"kategori\":\"Paket & Layanan\",\"jawaban\":\"Tentu. Kami memahami setiap pasangan memiliki anggaran yang berbeda. Karena itu, Anda dapat berkonsultasi dengan tim kami untuk menyesuaikan layanan berdasarkan budget yang tersedia.\",\"urutan\":\"3\",\"status\":\"active\",\"uuid\":\"588f2d39-d7bb-4d8f-a78f-63d03e9d95b6\",\"updated_at\":\"2026-11-20 08:49:37\",\"created_at\":\"2026-11-20 08:49:37\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 01:49:37', '2026-11-20 01:49:37'),
(175, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', '9f04c66c-e310-480c-9342-d437b0da3dee', 'Faq #9f04c66c-e310-480c-9342-d437b0da3dee', NULL, '{\"pertanyaan\":\"Apakah saya bisa menggunakan layanan Wedding Organizer saja tanpa mengambil paket lengkap?\",\"kategori\":\"Paket & Layanan\",\"jawaban\":\"Bisa. Anda dapat memilih layanan sesuai kebutuhan pernikahan tanpa harus mengambil seluruh fasilitas dalam paket lengkap.\",\"urutan\":\"4\",\"status\":\"active\",\"uuid\":\"9f04c66c-e310-480c-9342-d437b0da3dee\",\"updated_at\":\"2026-11-20 08:50:53\",\"created_at\":\"2026-11-20 08:50:53\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 01:50:53', '2026-11-20 01:50:53'),
(176, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Faq', '95b200f1-69c7-4f1b-a76b-172bcf5fcc55', 'Faq #95b200f1-69c7-4f1b-a76b-172bcf5fcc55', '{\"pertanyaan\":\"Can we change the date or package after booking?\",\"kategori\":\"Perubahan & Pembatalan\",\"jawaban\":\"Changes can be requested up to 30 days before the event, subject to availability. Cancellations follow the terms stated in your booking agreement.\",\"urutan\":6}', '{\"pertanyaan\":\"Bagaimana cara melakukan pemesanan di Sagita Wedding Planner?\",\"kategori\":\"Pemesanan & Pembayaran\",\"jawaban\":\"Proses pemesanan sangat mudah. Anda cukup menghubungi tim Sagita Wedding Planner melalui WhatsApp untuk menyampaikan tanggal, lokasi, dan kebutuhan pernikahan Anda. Selanjutnya, tim kami akan membantu memberikan informasi mengenai pilihan paket, melakukan konsultasi, serta menjelaskan proses pemesanan dan pembayaran hingga booking Anda dikonfirmasi.\",\"urutan\":\"1\"}', 'http://127.0.0.1:8000/backend/faq/95b200f1-69c7-4f1b-a76b-172bcf5fcc55', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 01:53:04', '2026-11-20 01:53:04'),
(177, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', '7dd9fc23-58bd-4ae4-8a54-99379cd61d7b', 'Faq #7dd9fc23-58bd-4ae4-8a54-99379cd61d7b', NULL, '{\"pertanyaan\":\"Apakah konsultasi awal dikenakan biaya?\",\"kategori\":\"Pemesanan & Pembayaran\",\"jawaban\":\"Tidak. Sagita Wedding Planner menyediakan konsultasi awal secara gratis. Anda dapat berdiskusi langsung dengan tim kami mengenai konsep pernikahan, kebutuhan acara, pilihan paket, dan estimasi anggaran tanpa dikenakan biaya.\",\"urutan\":\"2\",\"status\":\"active\",\"uuid\":\"7dd9fc23-58bd-4ae4-8a54-99379cd61d7b\",\"updated_at\":\"2026-11-20 08:54:26\",\"created_at\":\"2026-11-20 08:54:26\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 01:54:26', '2026-11-20 01:54:26'),
(178, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', '075b6da4-1f56-41da-b755-4eef63cafddc', 'Faq #075b6da4-1f56-41da-b755-4eef63cafddc', NULL, '{\"pertanyaan\":\"Apakah pembayaran bisa dilakukan secara bertahap?\",\"kategori\":\"Pemesanan & Pembayaran\",\"jawaban\":\"Ya, Sagita Wedding Planner menyediakan sistem pembayaran secara bertahap, yaitu deposit sebesar 30% saat penandatanganan kontrak untuk mengunci tanggal pernikahan, pembayaran tahap kedua sebesar 40% pada H-90, dan pelunasan sebesar 30% paling lambat H-30 sebelum hari pernikahan. Seluruh tahapan pembayaran akan tercatat secara resmi dalam kontrak kerja sama sebagai bentuk transparansi dan kepastian bagi kedua belah pihak.\",\"urutan\":\"3\",\"status\":\"active\",\"uuid\":\"075b6da4-1f56-41da-b755-4eef63cafddc\",\"updated_at\":\"2026-11-20 08:56:50\",\"created_at\":\"2026-11-20 08:56:50\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 01:56:50', '2026-11-20 01:56:50'),
(179, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', 'ed20af4a-a58c-4f55-90e8-e00559a3f2be', 'Faq #ed20af4a-a58c-4f55-90e8-e00559a3f2be', NULL, '{\"pertanyaan\":\"Apakah Sagita menyediakan rekomendasi vendor pernikahan?\",\"kategori\":\"Vendor & Mitra\",\"jawaban\":\"Ya, kami dapat membantu memberikan rekomendasi vendor sesuai kebutuhan, konsep, dan anggaran pernikahan Anda.\",\"urutan\":\"1\",\"status\":\"active\",\"uuid\":\"ed20af4a-a58c-4f55-90e8-e00559a3f2be\",\"updated_at\":\"2026-11-20 08:58:35\",\"created_at\":\"2026-11-20 08:58:35\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 01:58:35', '2026-11-20 01:58:35'),
(180, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', 'ed0083e2-9c06-4331-9102-24c64d6d625d', 'Faq #ed0083e2-9c06-4331-9102-24c64d6d625d', NULL, '{\"pertanyaan\":\"Apakah Sagita membantu mengoordinasikan semua vendor pada hari pernikahan?\",\"kategori\":\"Vendor & Mitra\",\"jawaban\":\"Ya, koordinasi vendor termasuk dalam layanan sesuai cakupan paket yang dipilih. Kami akan membantu memastikan setiap vendor memahami tugas dan jadwal masing-masing.\",\"urutan\":\"2\",\"status\":\"active\",\"uuid\":\"ed0083e2-9c06-4331-9102-24c64d6d625d\",\"updated_at\":\"2026-11-20 08:59:12\",\"created_at\":\"2026-11-20 08:59:12\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 01:59:12', '2026-11-20 01:59:12'),
(181, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', 'df0ee7ce-ad9c-4583-9796-60225e945d06', 'Faq #df0ee7ce-ad9c-4583-9796-60225e945d06', NULL, '{\"pertanyaan\":\"Apakah tim Sagita akan hadir pada hari pernikahan?\",\"kategori\":\"Pelaksanaan Acara\",\"jawaban\":\"Tentu. Tim kami akan hadir sesuai jadwal dan layanan yang disepakati untuk membantu mengoordinasikan seluruh rangkaian acara.\",\"urutan\":\"1\",\"status\":\"active\",\"uuid\":\"df0ee7ce-ad9c-4583-9796-60225e945d06\",\"updated_at\":\"2026-11-20 09:00:27\",\"created_at\":\"2026-11-20 09:00:27\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 02:00:27', '2026-11-20 02:00:27'),
(182, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', '0dbb3b5d-21df-4fcb-9817-8345e0efda78', 'Faq #0dbb3b5d-21df-4fcb-9817-8345e0efda78', NULL, '{\"pertanyaan\":\"Apakah Sagita membantu membuat rundown acara?\",\"kategori\":\"Pelaksanaan Acara\",\"jawaban\":\"Ya, kami akan membantu menyusun rundown mulai dari persiapan, akad nikah atau pemberkatan, resepsi, hingga acara selesai sesuai konsep pernikahan.\",\"urutan\":\"2\",\"status\":\"active\",\"uuid\":\"0dbb3b5d-21df-4fcb-9817-8345e0efda78\",\"updated_at\":\"2026-11-20 09:00:47\",\"created_at\":\"2026-11-20 09:00:47\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 02:00:47', '2026-11-20 02:00:47'),
(183, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', 'b4492ad2-9278-40e3-bcf2-266847e854ea', 'Faq #b4492ad2-9278-40e3-bcf2-266847e854ea', NULL, '{\"pertanyaan\":\"Bagaimana jika terjadi kendala saat acara berlangsung?\",\"kategori\":\"Pelaksanaan Acara\",\"jawaban\":\"Jangan khawatir. Tim Sagita akan berusaha menangani kendala yang terjadi di lapangan dengan cepat dan melakukan koordinasi agar acara tetap berjalan dengan baik.\",\"urutan\":\"3\",\"status\":\"active\",\"uuid\":\"b4492ad2-9278-40e3-bcf2-266847e854ea\",\"updated_at\":\"2026-11-20 09:03:54\",\"created_at\":\"2026-11-20 09:03:54\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 02:03:54', '2026-11-20 02:03:54'),
(184, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', 'cef460e9-1af9-4821-8bd4-c3a20e0b3a5c', 'Faq #cef460e9-1af9-4821-8bd4-c3a20e0b3a5c', NULL, '{\"pertanyaan\":\"Apakah keluarga pengantin masih perlu mengurus acara saat hari pernikahan?\",\"kategori\":\"Pelaksanaan Acara\",\"jawaban\":\"Dengan adanya Wedding Organizer, keluarga dapat lebih fokus menikmati momen bahagia bersama tamu undangan. Tim kami akan membantu menangani koordinasi operasional sesuai layanan yang dipilih.\",\"urutan\":\"4\",\"status\":\"active\",\"uuid\":\"cef460e9-1af9-4821-8bd4-c3a20e0b3a5c\",\"updated_at\":\"2026-11-20 09:04:53\",\"created_at\":\"2026-11-20 09:04:53\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 02:04:53', '2026-11-20 02:04:53'),
(185, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', 'cfde4e9f-14d1-47a5-bf84-baeefb10f8a6', 'Faq #cfde4e9f-14d1-47a5-bf84-baeefb10f8a6', NULL, '{\"pertanyaan\":\"Apakah Sagita Wedding Planner melayani pernikahan di luar Jabodetabek?\",\"kategori\":\"Vendor & Mitra\",\"jawaban\":\"Ya, area layanan utama Sagita Wedding Planner mencakup Jabodetabek. Kami juga melayani acara pernikahan di luar kota, dan untuk lokasi di luar Jabodetabek, akan dikenakan biaya tambahan transportasi dan akomodasi tim yang diinformasikan secara transparan dan dirinci dalam penawaran sebelum pemesanan disepakati.\",\"urutan\":\"4\",\"status\":\"active\",\"uuid\":\"cfde4e9f-14d1-47a5-bf84-baeefb10f8a6\",\"updated_at\":\"2026-11-20 09:07:10\",\"created_at\":\"2026-11-20 09:07:10\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 02:07:10', '2026-11-20 02:07:10'),
(186, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', '285fea5f-45c0-46fc-8da4-707486a57609', 'Faq #285fea5f-45c0-46fc-8da4-707486a57609', NULL, '{\"pertanyaan\":\"Bagaimana jika terjadi hal di luar rencana saat hari pernikahan (hari-H)?\",\"kategori\":\"Pelaksanaan Acara\",\"jawaban\":\"Sagita Wedding Planner selalu menyiapkan rencana cadangan untuk mengantisipasi berbagai kemungkinan, seperti perubahan cuaca, keterlambatan vendor, maupun perubahan rundown acara. Koordinator utama kami akan bertanggung jawab mengambil keputusan dan mengatur koordinasi di lapangan, sehingga keluarga pengantin dapat tetap tenang dan menikmati momen bahagia tanpa harus memikirkan kendala teknis acara.\",\"urutan\":\"5\",\"status\":\"active\",\"uuid\":\"285fea5f-45c0-46fc-8da4-707486a57609\",\"updated_at\":\"2026-11-20 09:08:33\",\"created_at\":\"2026-11-20 09:08:33\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 02:08:33', '2026-11-20 02:08:33'),
(187, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', '5f3baf39-1307-4716-8844-2714852f3f8f', 'Faq #5f3baf39-1307-4716-8844-2714852f3f8f', NULL, '{\"pertanyaan\":\"Apakah deposit bisa dikembalikan jika terjadi pembatalan acara?\",\"kategori\":\"Perubahan & Pembatalan\",\"jawaban\":\"Deposit yang telah dibayarkan bersifat mengikat tanggal pemesanan dan tidak dapat dikembalikan apabila terjadi pembatalan. Namun, Sagita Wedding Planner memberikan fleksibilitas berupa pemindahan tanggal acara sebanyak satu kali tanpa biaya tambahan, dengan syarat permohonan perubahan disampaikan paling lambat 60 hari sebelum hari pernikahan (H-60) dan menyesuaikan ketersediaan jadwal tim kami.\",\"urutan\":\"1\",\"status\":\"active\",\"uuid\":\"5f3baf39-1307-4716-8844-2714852f3f8f\",\"updated_at\":\"2026-11-20 09:09:41\",\"created_at\":\"2026-11-20 09:09:41\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 02:09:41', '2026-11-20 02:09:41'),
(188, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Faq', 'b88fdc95-bfae-437e-8448-913f48a2bf80', 'Faq #b88fdc95-bfae-437e-8448-913f48a2bf80', NULL, '{\"pertanyaan\":\"Apakah tanggal pernikahan bisa diubah setelah melakukan booking?\",\"kategori\":\"Perubahan & Pembatalan\",\"jawaban\":\"Bisa. Sagita Wedding Planner memberikan kesempatan perubahan tanggal acara sebanyak satu kali tanpa biaya tambahan, dengan ketentuan permohonan perubahan disampaikan paling lambat 60 hari sebelum hari pernikahan (H-60) dan menyesuaikan ketersediaan jadwal tim kami. Perubahan tanggal selanjutnya akan mengikuti ketentuan yang tercantum dalam kontrak kerja sama.\",\"urutan\":\"2\",\"status\":\"active\",\"uuid\":\"b88fdc95-bfae-437e-8448-913f48a2bf80\",\"updated_at\":\"2026-11-20 09:10:20\",\"created_at\":\"2026-11-20 09:10:20\"}', 'http://127.0.0.1:8000/backend/faq', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 02:10:20', '2026-11-20 02:10:20'),
(189, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '40', 'Halaman Statis', '{\"name\":\"Static Pages\"}', '{\"name\":\"Halaman Statis\"}', 'http://127.0.0.1:8000/backend/menu/9/item/40', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 02:34:01', '2026-11-20 02:34:01'),
(190, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '50', 'Testimoni', '{\"name\":\"Testimonials\"}', '{\"name\":\"Testimoni\"}', 'http://127.0.0.1:8000/backend/menu/9/item/50', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 02:34:08', '2026-11-20 02:34:08'),
(191, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '23', 'Vendor & Mitra', '{\"name\":\"Venues\"}', '{\"name\":\"Vendor & Mitra\"}', 'http://127.0.0.1:8000/backend/menu/23', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 03:14:58', '2026-11-20 03:14:58'),
(192, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '52', 'Data Venue', '{\"name\":\"All Venues\"}', '{\"name\":\"Data Venue\"}', 'http://127.0.0.1:8000/backend/menu/23/item/52', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 03:15:33', '2026-11-20 03:15:33'),
(193, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '53', 'Data Vendor', '{\"name\":\"Add Venue\"}', '{\"name\":\"Data Vendor\"}', 'http://127.0.0.1:8000/backend/menu/23/item/53', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 03:16:07', '2026-11-20 03:16:07'),
(194, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\ManagementAccess\\MenuItem', '65', 'Kategori Vendor', NULL, '{\"name\":\"Kategori Vendor\",\"route\":\"vendors_categories.index\",\"permission_name\":\"vendors_categories.index\",\"status\":true,\"menu_group_id\":23,\"position\":3,\"updated_at\":\"2026-11-20 10:17:09\",\"created_at\":\"2026-11-20 10:17:09\",\"id\":65}', 'http://127.0.0.1:8000/backend/menu/23/item', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 03:17:09', '2026-11-20 03:17:09'),
(195, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\ManagementAccess\\MenuItem', '13', 'Vendor Category', '{\"id\":13,\"name\":\"Vendor Category\",\"icon\":null,\"route\":\"vendors_categories.index\",\"status\":true,\"permission_name\":\"vendors_categories.index\",\"menu_group_id\":10,\"position\":1}', NULL, 'http://127.0.0.1:8000/backend/menu/10/item/13', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 03:18:39', '2026-11-20 03:18:39'),
(196, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '53', 'Data Vendor', '{\"route\":\"venues.create\",\"permission_name\":\"venues.create\"}', '{\"route\":\"vendor.index\",\"permission_name\":\"vendor.index\"}', 'http://127.0.0.1:8000/backend/menu/23/item/53', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 03:40:22', '2026-11-20 03:40:22'),
(197, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\ManagementAccess\\MenuGroup', '10', 'Vendor', '{\"id\":10,\"name\":\"Vendor\",\"status\":true,\"permission_name\":\"vendor.index\",\"icon\":\"bx-user\",\"position\":7}', NULL, 'http://127.0.0.1:8000/backend/menu/eyJpdiI6IksvQ3Z0WHpWcDk4Z29wUnhoajZvNnc9PSIsInZhbHVlIjoiWVdWb0ZDL3NpZ2RmUG5WRGJ3citrZz09IiwibWFjIjoiYWZlZDIxZGIzNjhlNDg3N2Q2NzE5MTY4ZDliZTk1NWY5ODUxZjA0NjYwMGEwZjkwOWZjN2JlMzg2N2ExNjIyOSIsInRhZyI6IiJ9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 03:41:09', '2026-11-20 03:41:09'),
(198, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '8', 'Portfolio & Galeri', '{\"name\":\"Media Library\"}', '{\"name\":\"Portfolio & Galeri\"}', 'http://127.0.0.1:8000/backend/menu/8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 03:54:15', '2026-11-20 03:54:15'),
(199, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '10', 'Galeri Foto', '{\"name\":\"All Media\"}', '{\"name\":\"Galeri Foto\"}', 'http://127.0.0.1:8000/backend/menu/8/item/10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 03:55:43', '2026-11-20 03:55:43'),
(200, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\ManagementAccess\\MenuGroup', '11', 'Events', '{\"id\":11,\"name\":\"Events\",\"status\":true,\"permission_name\":\"events.index\",\"icon\":\"bxs-calendar\",\"position\":5}', NULL, 'http://127.0.0.1:8000/backend/menu/eyJpdiI6IkVEWHZ2NUNPeitxcFo5RVZIdllYcFE9PSIsInZhbHVlIjoiUnVtVDlNM3hRWEpnZkZJYTk1MWxUdz09IiwibWFjIjoiNjdhNWJmN2UxZTZjZGZhNmM2NWY1YjMyMjIwODkwNzM0OTc0YzFiZWFlMWMyNzE0MzI1YTU4MjI2OWM1YTY2MCIsInRhZyI6IiJ9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 04:14:00', '2026-11-20 04:14:00');
INSERT INTO `audit_logs` (`id`, `user_uuid`, `user_name`, `user_email`, `event`, `auditable_type`, `auditable_id`, `auditable_label`, `old_values`, `new_values`, `url`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(201, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '7', 'Publikasi', '{\"name\":\"Content\"}', '{\"name\":\"Publikasi\"}', 'http://127.0.0.1:8000/backend/menu/7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 04:14:09', '2026-11-20 04:14:09'),
(202, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '9', 'Artikel', '{\"name\":\"Articles\"}', '{\"name\":\"Artikel\"}', 'http://127.0.0.1:8000/backend/menu/7/item/9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 04:14:35', '2026-11-20 04:14:35'),
(203, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '8', 'Kategori Artikel', '{\"name\":\"Category\"}', '{\"name\":\"Kategori Artikel\"}', 'http://127.0.0.1:8000/backend/menu/7/item/8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 04:14:44', '2026-11-20 04:14:44'),
(204, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '12', 'Event', '{\"name\":\"Add Articles\",\"route\":\"articles.create\",\"permission_name\":\"articles.create\"}', '{\"name\":\"Event\",\"route\":\"events.index\",\"permission_name\":\"events.index\"}', 'http://127.0.0.1:8000/backend/menu/7/item/12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 04:15:01', '2026-11-20 04:15:01'),
(205, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Category', '2162d145-9ef3-4e2f-8c55-81971a015bc5', 'Tips Pernikahan', '{\"name\":\"Yoga\",\"slug\":\"yoga\",\"description\":\"Tips, teknik, pose, dan latihan yoga untuk meningkatkan kesehatan dan keseimbangan tubuh.\",\"icon\":\"fa-spa\"}', '{\"name\":\"Tips Pernikahan\",\"slug\":\"tips-pernikahan\",\"description\":\"Panduan dan tips mempersiapkan pernikahan.\",\"icon\":null}', 'http://127.0.0.1:8000/backend/categories/2162d145-9ef3-4e2f-8c55-81971a015bc5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 04:29:52', '2026-11-20 04:29:52'),
(206, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Category', '34103609-8116-4baf-bd17-557fc6989e8e', 'Inspirasi Pernikahan', '{\"name\":\"Wellness\",\"slug\":\"wellness\",\"description\":\"Informasi seputar kesehatan, kebugaran, nutrisi, dan gaya hidup sehat.\",\"icon\":\"fa-heart-pulse\"}', '{\"name\":\"Inspirasi Pernikahan\",\"slug\":\"inspirasi-pernikahan\",\"description\":\"Ide tema, konsep, dan dekorasi wedding.\",\"icon\":null}', 'http://127.0.0.1:8000/backend/categories/34103609-8116-4baf-bd17-557fc6989e8e', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 04:30:16', '2026-11-20 04:30:16'),
(207, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Category', '72561291-cdcb-484b-a556-0400fbd53c3d', 'Wedding Planning', '{\"name\":\"Mindfulness\",\"slug\":\"mindfulness\",\"description\":\"Panduan meditasi, ketenangan pikiran, kesadaran diri, dan keseimbangan mental.\",\"icon\":\"fa-brain\"}', '{\"name\":\"Wedding Planning\",\"slug\":\"wedding-planning\",\"description\":\"Anggaran, checklist, timeline, dan rundown.\",\"icon\":null}', 'http://127.0.0.1:8000/backend/categories/72561291-cdcb-484b-a556-0400fbd53c3d', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 04:30:35', '2026-11-20 04:30:35'),
(208, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Category', 'a58f5ebc-c8ec-48da-b6f9-a80b05ca40a0', 'Tren Pernikahan', '{\"name\":\"Kegiatan\",\"slug\":\"kegiatan\",\"description\":\"Informasi event, workshop, retreat, dan berbagai kegiatan YogaRoots.\",\"icon\":\"fa-calendar-days\"}', '{\"name\":\"Tren Pernikahan\",\"slug\":\"tren-pernikahan\",\"description\":\"Tren makeup, busana, dekorasi, dan konsep terbaru.\",\"icon\":null}', 'http://127.0.0.1:8000/backend/categories/a58f5ebc-c8ec-48da-b6f9-a80b05ca40a0', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 04:30:58', '2026-11-20 04:30:58'),
(209, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Category', 'bb006a6e-36bc-48cb-a84f-2a562489bb54', 'Berita', '{\"description\":\"Informasi dan kabar terbaru seputar YogaRoots, kelas, program, dan studio.\",\"icon\":\"fa-newspaper\"}', '{\"description\":\"Kegiatan, promo, dan informasi terbaru Sagita Wedding Planner.\",\"icon\":null}', 'http://127.0.0.1:8000/backend/categories/bb006a6e-36bc-48cb-a84f-2a562489bb54', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 04:31:11', '2026-11-20 04:31:11'),
(210, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '8', 'Portfolio & Galeri', '{\"name\":\"Perpustakaan Media\"}', '{\"name\":\"Portfolio & Galeri\"}', 'http://127.0.0.1:8000/backend/menu/8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 04:54:02', '2026-11-20 04:54:02'),
(211, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\ManagementAccess\\MenuItem', '49', 'All Members', '{\"id\":49,\"name\":\"All Members\",\"icon\":null,\"route\":\"pengguna.index\",\"status\":true,\"permission_name\":\"pengguna.index\",\"menu_group_id\":3,\"position\":5}', NULL, 'http://127.0.0.1:8000/backend/menu/3/item/49', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 05:29:04', '2026-11-20 05:29:04'),
(212, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '28', 'Calon Konsumen', '{\"name\":\"Konsultasi\"}', '{\"name\":\"Calon Konsumen\"}', 'http://127.0.0.1:8000/backend/menu/28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 05:29:32', '2026-11-20 05:29:32'),
(213, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\ManagementAccess\\MenuItem', '68', 'Data Prospek', NULL, '{\"name\":\"Data Prospek\",\"route\":\"pengguna.index\",\"permission_name\":\"pengguna.index\",\"status\":true,\"menu_group_id\":28,\"position\":2,\"updated_at\":\"2026-11-20 12:30:17\",\"created_at\":\"2026-11-20 12:30:17\",\"id\":68}', 'http://127.0.0.1:8000/backend/menu/28/item', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 05:30:17', '2026-11-20 05:30:17'),
(214, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '67', 'Konsultasi', '{\"name\":\"Pertanyaan Masuk\"}', '{\"name\":\"Konsultasi\"}', 'http://127.0.0.1:8000/backend/menu/28/item/67', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 05:30:34', '2026-11-20 05:30:34'),
(215, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '10', 'Galeri Foto', '{\"name\":\"Semua Media\"}', '{\"name\":\"Galeri Foto\"}', 'http://127.0.0.1:8000/backend/menu/8/item/10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 05:40:17', '2026-11-20 05:40:17'),
(216, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\User', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', '{\"prospect_status\":\"new_registered\",\"prospect_status_updated_at\":null}', '{\"prospect_status\":\"interested\",\"prospect_status_updated_at\":\"2026-11-20 12:41:13\"}', 'http://127.0.0.1:8000/backend/pengguna/c2187aff-68d0-40b9-a6f1-52738cee4ea4/status', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 05:41:13', '2026-11-20 05:41:13'),
(217, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\User', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', '{\"prospect_status\":\"interested\",\"prospect_status_updated_at\":\"2026-11-20T05:41:13.000000Z\"}', '{\"prospect_status\":\"new_registered\",\"prospect_status_updated_at\":\"2026-11-20 12:41:19\"}', 'http://127.0.0.1:8000/backend/pengguna/c2187aff-68d0-40b9-a6f1-52738cee4ea4/status', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 05:41:19', '2026-11-20 05:41:19'),
(218, NULL, NULL, NULL, 'created', 'App\\Models\\User', 'f8dbf191-7c45-4d71-8cfd-1e290747ade8', 'Madu Sarifa Official', NULL, '{\"name\":\"Madu Sarifa Official\",\"email\":\"madusarifaofficial@gmail.com\",\"google_id\":\"106420227434740471376\",\"avatar\":\"https:\\/\\/lh3.googleusercontent.com\\/a\\/ACg8ocKQSJX_sGFgxp5u06OvpTbaVaP7GtF_lcy8UmifKhRIkUKTwQ8=s96-c\",\"email_verified_at\":\"2026-11-20 12:52:43\",\"uuid\":\"f8dbf191-7c45-4d71-8cfd-1e290747ade8\",\"updated_at\":\"2026-11-20 12:52:43\",\"created_at\":\"2026-11-20 12:52:43\"}', 'http://localhost:8000/auth/google/callback?authuser=0&code=4%2F0AUn6Hd_0hrYcjD7VVFPVjKJwY1c_aig1sSLSCEHbMkVdYh0ThXxZlBCQIOUX_MCIbgu7LQ&iss=https%3A%2F%2Faccounts.google.com&prompt=consent&scope=email%20profile%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email%20openid', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 05:52:43', '2026-11-20 05:52:43'),
(219, 'f8dbf191-7c45-4d71-8cfd-1e290747ade8', 'Madu Sarifa Official', 'madusarifaofficial@gmail.com', 'updated', 'App\\Models\\User', 'f8dbf191-7c45-4d71-8cfd-1e290747ade8', 'Madu Sarifa Official', '{\"no_hp\":null,\"sumber_informasi\":null}', '{\"no_hp\":\"08112312313\",\"sumber_informasi\":\"friend\"}', 'http://localhost:8000/backend/dashboard/sumber-informasi', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 05:53:02', '2026-11-20 05:53:02'),
(220, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '28', 'Data Prospek', '{\"name\":\"Calon Konsumen\"}', '{\"name\":\"Data Prospek\"}', 'http://127.0.0.1:8000/backend/menu/28', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 06:00:30', '2026-11-20 06:00:30'),
(221, NULL, NULL, NULL, 'created', 'App\\Models\\User', 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', NULL, '{\"name\":\"QA Screenshot\",\"email\":\"qa.screenshot@example.test\",\"email_verified_at\":\"2026-11-20 13:07:57\",\"uuid\":\"fa921602-18c9-4e1b-a070-ea705ecfc488\",\"updated_at\":\"2026-11-20 13:07:57\",\"created_at\":\"2026-11-20 13:07:57\"}', NULL, NULL, NULL, '2026-11-20 06:07:57', '2026-11-20 06:07:57');

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `uuid` char(36) NOT NULL,
  `tipe` enum('foto','video','album') NOT NULL DEFAULT 'foto',
  `video_url` varchar(500) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `gambar` varchar(255) NOT NULL,
  `posisi` enum('slider','pengumuman','infografis','galeri','popup','mitra','lainnya') NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
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
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1795155808),
('5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1795155808;', 1795155808),
('a75f3f172bfb296f2e10cbfc6dfc1883', 'i:5;', 1795152086),
('a75f3f172bfb296f2e10cbfc6dfc1883:timer', 'i:1795152086;', 1795152086),
('contact_captcha_66acf5c1-b036-49b0-baf1-535e374b0e44', 'a:2:{s:6:\"answer\";i:18;s:8:\"attempts\";i:0;}', 1795146264),
('contact_captcha_fb20a1fc-6aad-4e16-849e-5530d9cdaa78', 'a:2:{s:6:\"answer\";i:16;s:8:\"attempts\";i:0;}', 1795152626),
('dashboard.admin.aggregate.a949b6ee52c3d43f07e7d7656f621d6d3fdf3bc8', 'a:9:{s:5:\"stats\";a:10:{i:0;a:8:{s:5:\"label\";s:11:\"Member baru\";s:5:\"value\";i:0;s:8:\"previous\";i:1;s:6:\"change\";i:-100;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:12:\"bi bi-people\";s:5:\"class\";s:6:\"purple\";s:5:\"route\";s:33:\"http://localhost/backend/pengguna\";}i:1;a:8:{s:5:\"label\";s:15:\"Instruktur baru\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:18:\"bi bi-person-badge\";s:5:\"class\";s:4:\"blue\";s:5:\"route\";s:35:\"http://localhost/backend/instruktur\";}i:2;a:8:{s:5:\"label\";s:11:\"Venue aktif\";s:5:\"value\";i:1;s:8:\"previous\";N;s:6:\"change\";N;s:5:\"spark\";a:0:{}s:4:\"icon\";s:13:\"bi bi-geo-alt\";s:5:\"class\";s:5:\"green\";s:5:\"route\";s:31:\"http://localhost/backend/venues\";}i:3;a:8:{s:5:\"label\";s:21:\"Kategori vendor aktif\";s:5:\"value\";i:8;s:8:\"previous\";N;s:6:\"change\";N;s:5:\"spark\";a:0:{}s:4:\"icon\";s:10:\"bi bi-shop\";s:5:\"class\";s:4:\"cyan\";s:5:\"route\";s:43:\"http://localhost/backend/vendors_categories\";}i:4;a:8:{s:5:\"label\";s:16:\"Pertanyaan masuk\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:20:\"bi bi-chat-left-text\";s:5:\"class\";s:9:\"turquoise\";s:5:\"route\";s:31:\"http://localhost/backend/kontak\";}i:5;a:8:{s:5:\"label\";s:12:\"Pesanan baru\";s:5:\"value\";i:0;s:8:\"previous\";i:4;s:6:\"change\";i:-100;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:14:\"bi bi-bag-plus\";s:5:\"class\";s:6:\"yellow\";s:5:\"route\";s:31:\"http://localhost/backend/orders\";}i:6;a:8:{s:5:\"label\";s:5:\"Paket\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:14:\"bi bi-box-seam\";s:5:\"class\";s:4:\"pink\";s:5:\"route\";s:33:\"http://localhost/backend/packages\";}i:7;a:8:{s:5:\"label\";s:5:\"Kelas\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:10:\"bi bi-book\";s:5:\"class\";s:3:\"red\";s:5:\"route\";s:32:\"http://localhost/backend/classes\";}i:8;a:8:{s:5:\"label\";s:5:\"Event\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:20:\"bi bi-calendar-event\";s:5:\"class\";s:5:\"green\";s:5:\"route\";s:31:\"http://localhost/backend/events\";}i:9;a:8:{s:5:\"label\";s:7:\"Artikel\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:23:\"bi bi-file-earmark-text\";s:5:\"class\";s:4:\"blue\";s:5:\"route\";s:33:\"http://localhost/backend/articles\";}}s:6:\"funnel\";a:4:{i:0;a:2:{s:5:\"label\";s:10:\"Pertanyaan\";s:5:\"value\";i:0;}i:1;a:2:{s:5:\"label\";s:7:\"Pesanan\";s:5:\"value\";i:0;}i:2;a:2:{s:5:\"label\";s:5:\"Lunas\";s:5:\"value\";i:0;}i:3;a:2:{s:5:\"label\";s:17:\"Keanggotaan aktif\";s:5:\"value\";i:0;}}s:14:\"newOrdersCount\";i:0;s:12:\"revenueMonth\";d:0;s:12:\"revenueTotal\";d:420000;s:18:\"newMembershipCount\";i:0;s:12:\"revenueChart\";a:3:{s:5:\"daily\";a:2:{s:6:\"labels\";a:30:{i:0;s:6:\"22 Oct\";i:1;s:6:\"23 Oct\";i:2;s:6:\"24 Oct\";i:3;s:6:\"25 Oct\";i:4;s:6:\"26 Oct\";i:5;s:6:\"27 Oct\";i:6;s:6:\"28 Oct\";i:7;s:6:\"29 Oct\";i:8;s:6:\"30 Oct\";i:9;s:6:\"31 Oct\";i:10;s:6:\"01 Nov\";i:11;s:6:\"02 Nov\";i:12;s:6:\"03 Nov\";i:13;s:6:\"04 Nov\";i:14;s:6:\"05 Nov\";i:15;s:6:\"06 Nov\";i:16;s:6:\"07 Nov\";i:17;s:6:\"08 Nov\";i:18;s:6:\"09 Nov\";i:19;s:6:\"10 Nov\";i:20;s:6:\"11 Nov\";i:21;s:6:\"12 Nov\";i:22;s:6:\"13 Nov\";i:23;s:6:\"14 Nov\";i:24;s:6:\"15 Nov\";i:25;s:6:\"16 Nov\";i:26;s:6:\"17 Nov\";i:27;s:6:\"18 Nov\";i:28;s:6:\"19 Nov\";i:29;s:6:\"20 Nov\";}s:4:\"data\";a:30:{i:0;d:0;i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;i:5;d:0;i:6;d:0;i:7;d:0;i:8;d:0;i:9;d:0;i:10;d:0;i:11;d:0;i:12;d:0;i:13;d:0;i:14;d:0;i:15;d:0;i:16;d:0;i:17;d:0;i:18;d:0;i:19;d:0;i:20;d:0;i:21;d:0;i:22;d:0;i:23;d:0;i:24;d:0;i:25;d:0;i:26;d:0;i:27;d:0;i:28;d:0;i:29;d:0;}}s:7:\"monthly\";a:2:{s:6:\"labels\";a:2:{i:0;s:8:\"Oct 2026\";i:1;s:8:\"Nov 2026\";}s:4:\"data\";a:2:{i:0;d:0;i:1;d:0;}}s:6:\"yearly\";a:2:{s:6:\"labels\";a:1:{i:0;s:4:\"2026\";}s:4:\"data\";a:1:{i:0;d:0;}}}s:8:\"security\";a:2:{s:12:\"failedLogins\";i:0;s:8:\"lockouts\";i:0;}s:4:\"site\";a:12:{s:18:\"visitorDailyLabels\";a:30:{i:0;s:6:\"22 Oct\";i:1;s:6:\"23 Oct\";i:2;s:6:\"24 Oct\";i:3;s:6:\"25 Oct\";i:4;s:6:\"26 Oct\";i:5;s:6:\"27 Oct\";i:6;s:6:\"28 Oct\";i:7;s:6:\"29 Oct\";i:8;s:6:\"30 Oct\";i:9;s:6:\"31 Oct\";i:10;s:6:\"01 Nov\";i:11;s:6:\"02 Nov\";i:12;s:6:\"03 Nov\";i:13;s:6:\"04 Nov\";i:14;s:6:\"05 Nov\";i:15;s:6:\"06 Nov\";i:16;s:6:\"07 Nov\";i:17;s:6:\"08 Nov\";i:18;s:6:\"09 Nov\";i:19;s:6:\"10 Nov\";i:20;s:6:\"11 Nov\";i:21;s:6:\"12 Nov\";i:22;s:6:\"13 Nov\";i:23;s:6:\"14 Nov\";i:24;s:6:\"15 Nov\";i:25;s:6:\"16 Nov\";i:26;s:6:\"17 Nov\";i:27;s:6:\"18 Nov\";i:28;s:6:\"19 Nov\";i:29;s:6:\"20 Nov\";}s:17:\"visitorDailyTotal\";a:30:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:0;i:27;i:0;i:28;i:24;i:29;i:9;}s:18:\"visitorDailyUnique\";a:30:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:0;i:27;i:0;i:28;i:10;i:29;i:2;}s:18:\"visitorMonthLabels\";a:2:{i:0;s:8:\"Oct 2026\";i:1;s:8:\"Nov 2026\";}s:17:\"visitorMonthTotal\";a:2:{i:0;i:0;i:1;i:33;}s:18:\"visitorMonthUnique\";a:2:{i:0;i:0;i:1;i:12;}s:14:\"activeVisitors\";i:2;s:23:\"activeVisitorsUpdatedAt\";s:5:\"08.09\";s:22:\"analyticsContentLabels\";a:7:{i:0;s:1:\"/\";i:1;s:12:\"/instructors\";i:2;s:10:\"/schedules\";i:3;s:6:\"/event\";i:4;s:9:\"/packages\";i:5;s:14:\"/art-of-living\";i:6;s:8:\"/classes\";}s:22:\"analyticsContentCounts\";a:7:{i:0;i:18;i:1;i:4;i:2;i:3;i:3;i:3;i:4;i:2;i:5;i:2;i:6;i:1;}s:21:\"analyticsDeviceLabels\";a:3:{i:0;s:7:\"Desktop\";i:1;s:6:\"Ponsel\";i:2;s:6:\"Tablet\";}s:21:\"analyticsDeviceCounts\";a:3:{i:0;i:33;i:1;i:0;i:2;i:0;}}}', 1795137253),
('dashboard.admin.aggregate.eda8e133a3679f81a49c0af2c51ad648e9167fab', 'a:11:{s:5:\"stats\";a:10:{i:0;a:8:{s:5:\"label\";s:11:\"Member baru\";s:5:\"value\";i:1;s:8:\"previous\";i:0;s:6:\"change\";i:100;s:5:\"spark\";a:13:{i:0;i:0;i:1;i:0;i:2;i:1;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}s:4:\"icon\";s:12:\"bi bi-people\";s:5:\"class\";s:6:\"purple\";s:5:\"route\";s:33:\"http://localhost/backend/pengguna\";}i:1;a:8:{s:5:\"label\";s:15:\"Instruktur baru\";s:5:\"value\";i:0;s:8:\"previous\";i:7;s:6:\"change\";i:-100;s:5:\"spark\";a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}s:4:\"icon\";s:18:\"bi bi-person-badge\";s:5:\"class\";s:4:\"blue\";s:5:\"route\";s:35:\"http://localhost/backend/instruktur\";}i:2;a:8:{s:5:\"label\";s:11:\"Venue aktif\";s:5:\"value\";i:1;s:8:\"previous\";N;s:6:\"change\";N;s:5:\"spark\";a:0:{}s:4:\"icon\";s:13:\"bi bi-geo-alt\";s:5:\"class\";s:5:\"green\";s:5:\"route\";s:31:\"http://localhost/backend/venues\";}i:3;a:8:{s:5:\"label\";s:21:\"Kategori vendor aktif\";s:5:\"value\";i:8;s:8:\"previous\";N;s:6:\"change\";N;s:5:\"spark\";a:0:{}s:4:\"icon\";s:10:\"bi bi-shop\";s:5:\"class\";s:4:\"cyan\";s:5:\"route\";s:43:\"http://localhost/backend/vendors_categories\";}i:4;a:8:{s:5:\"label\";s:16:\"Pertanyaan masuk\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}s:4:\"icon\";s:20:\"bi bi-chat-left-text\";s:5:\"class\";s:9:\"turquoise\";s:5:\"route\";s:31:\"http://localhost/backend/kontak\";}i:5;a:8:{s:5:\"label\";s:12:\"Pesanan baru\";s:5:\"value\";i:4;s:8:\"previous\";i:5;s:6:\"change\";i:-20;s:5:\"spark\";a:13:{i:0;i:0;i:1;i:0;i:2;i:1;i:3;i:3;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}s:4:\"icon\";s:14:\"bi bi-bag-plus\";s:5:\"class\";s:6:\"yellow\";s:5:\"route\";s:31:\"http://localhost/backend/orders\";}i:6;a:8:{s:5:\"label\";s:5:\"Paket\";s:5:\"value\";i:0;s:8:\"previous\";i:7;s:6:\"change\";i:-100;s:5:\"spark\";a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}s:4:\"icon\";s:14:\"bi bi-box-seam\";s:5:\"class\";s:4:\"pink\";s:5:\"route\";s:33:\"http://localhost/backend/packages\";}i:7;a:8:{s:5:\"label\";s:5:\"Kelas\";s:5:\"value\";i:0;s:8:\"previous\";i:3;s:6:\"change\";i:-100;s:5:\"spark\";a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}s:4:\"icon\";s:10:\"bi bi-book\";s:5:\"class\";s:3:\"red\";s:5:\"route\";s:32:\"http://localhost/backend/classes\";}i:8;a:8:{s:5:\"label\";s:5:\"Event\";s:5:\"value\";i:0;s:8:\"previous\";i:3;s:6:\"change\";i:-100;s:5:\"spark\";a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}s:4:\"icon\";s:20:\"bi bi-calendar-event\";s:5:\"class\";s:5:\"green\";s:5:\"route\";s:31:\"http://localhost/backend/events\";}i:9;a:8:{s:5:\"label\";s:7:\"Artikel\";s:5:\"value\";i:0;s:8:\"previous\";i:1;s:6:\"change\";i:-100;s:5:\"spark\";a:13:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;}s:4:\"icon\";s:23:\"bi bi-file-earmark-text\";s:5:\"class\";s:4:\"blue\";s:5:\"route\";s:33:\"http://localhost/backend/articles\";}}s:6:\"funnel\";a:4:{i:0;a:2:{s:5:\"label\";s:10:\"Pertanyaan\";s:5:\"value\";i:0;}i:1;a:2:{s:5:\"label\";s:7:\"Pesanan\";s:5:\"value\";i:4;}i:2;a:2:{s:5:\"label\";s:5:\"Lunas\";s:5:\"value\";i:2;}i:3;a:2:{s:5:\"label\";s:17:\"Keanggotaan aktif\";s:5:\"value\";i:2;}}s:14:\"newOrdersCount\";i:2;s:17:\"newInquiriesCount\";i:0;s:12:\"revenueMonth\";d:420000;s:12:\"revenueTotal\";d:420000;s:18:\"newMembershipCount\";i:2;s:12:\"revenueChart\";a:3:{s:5:\"daily\";a:2:{s:6:\"labels\";a:61:{i:0;s:6:\"21 Sep\";i:1;s:6:\"22 Sep\";i:2;s:6:\"23 Sep\";i:3;s:6:\"24 Sep\";i:4;s:6:\"25 Sep\";i:5;s:6:\"26 Sep\";i:6;s:6:\"27 Sep\";i:7;s:6:\"28 Sep\";i:8;s:6:\"29 Sep\";i:9;s:6:\"30 Sep\";i:10;s:6:\"01 Oct\";i:11;s:6:\"02 Oct\";i:12;s:6:\"03 Oct\";i:13;s:6:\"04 Oct\";i:14;s:6:\"05 Oct\";i:15;s:6:\"06 Oct\";i:16;s:6:\"07 Oct\";i:17;s:6:\"08 Oct\";i:18;s:6:\"09 Oct\";i:19;s:6:\"10 Oct\";i:20;s:6:\"11 Oct\";i:21;s:6:\"12 Oct\";i:22;s:6:\"13 Oct\";i:23;s:6:\"14 Oct\";i:24;s:6:\"15 Oct\";i:25;s:6:\"16 Oct\";i:26;s:6:\"17 Oct\";i:27;s:6:\"18 Oct\";i:28;s:6:\"19 Oct\";i:29;s:6:\"20 Oct\";i:30;s:6:\"21 Oct\";i:31;s:6:\"22 Oct\";i:32;s:6:\"23 Oct\";i:33;s:6:\"24 Oct\";i:34;s:6:\"25 Oct\";i:35;s:6:\"26 Oct\";i:36;s:6:\"27 Oct\";i:37;s:6:\"28 Oct\";i:38;s:6:\"29 Oct\";i:39;s:6:\"30 Oct\";i:40;s:6:\"31 Oct\";i:41;s:6:\"01 Nov\";i:42;s:6:\"02 Nov\";i:43;s:6:\"03 Nov\";i:44;s:6:\"04 Nov\";i:45;s:6:\"05 Nov\";i:46;s:6:\"06 Nov\";i:47;s:6:\"07 Nov\";i:48;s:6:\"08 Nov\";i:49;s:6:\"09 Nov\";i:50;s:6:\"10 Nov\";i:51;s:6:\"11 Nov\";i:52;s:6:\"12 Nov\";i:53;s:6:\"13 Nov\";i:54;s:6:\"14 Nov\";i:55;s:6:\"15 Nov\";i:56;s:6:\"16 Nov\";i:57;s:6:\"17 Nov\";i:58;s:6:\"18 Nov\";i:59;s:6:\"19 Nov\";i:60;s:6:\"20 Nov\";}s:4:\"data\";a:61:{i:0;d:0;i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;i:5;d:0;i:6;d:0;i:7;d:0;i:8;d:0;i:9;d:0;i:10;d:0;i:11;d:0;i:12;d:0;i:13;d:210000;i:14;d:0;i:15;d:210000;i:16;d:0;i:17;d:0;i:18;d:0;i:19;d:0;i:20;d:0;i:21;d:0;i:22;d:0;i:23;d:0;i:24;d:0;i:25;d:0;i:26;d:0;i:27;d:0;i:28;d:0;i:29;d:0;i:30;d:0;i:31;d:0;i:32;d:0;i:33;d:0;i:34;d:0;i:35;d:0;i:36;d:0;i:37;d:0;i:38;d:0;i:39;d:0;i:40;d:0;i:41;d:0;i:42;d:0;i:43;d:0;i:44;d:0;i:45;d:0;i:46;d:0;i:47;d:0;i:48;d:0;i:49;d:0;i:50;d:0;i:51;d:0;i:52;d:0;i:53;d:0;i:54;d:0;i:55;d:0;i:56;d:0;i:57;d:0;i:58;d:0;i:59;d:0;i:60;d:0;}}s:7:\"monthly\";a:2:{s:6:\"labels\";a:3:{i:0;s:8:\"Sep 2026\";i:1;s:8:\"Oct 2026\";i:2;s:8:\"Nov 2026\";}s:4:\"data\";a:3:{i:0;d:0;i:1;d:420000;i:2;d:0;}}s:6:\"yearly\";a:2:{s:6:\"labels\";a:1:{i:0;s:4:\"2026\";}s:4:\"data\";a:1:{i:0;d:420000;}}}s:8:\"security\";a:2:{s:12:\"failedLogins\";i:0;s:8:\"lockouts\";i:0;}s:4:\"site\";a:12:{s:18:\"visitorDailyLabels\";a:61:{i:0;s:6:\"21 Sep\";i:1;s:6:\"22 Sep\";i:2;s:6:\"23 Sep\";i:3;s:6:\"24 Sep\";i:4;s:6:\"25 Sep\";i:5;s:6:\"26 Sep\";i:6;s:6:\"27 Sep\";i:7;s:6:\"28 Sep\";i:8;s:6:\"29 Sep\";i:9;s:6:\"30 Sep\";i:10;s:6:\"01 Oct\";i:11;s:6:\"02 Oct\";i:12;s:6:\"03 Oct\";i:13;s:6:\"04 Oct\";i:14;s:6:\"05 Oct\";i:15;s:6:\"06 Oct\";i:16;s:6:\"07 Oct\";i:17;s:6:\"08 Oct\";i:18;s:6:\"09 Oct\";i:19;s:6:\"10 Oct\";i:20;s:6:\"11 Oct\";i:21;s:6:\"12 Oct\";i:22;s:6:\"13 Oct\";i:23;s:6:\"14 Oct\";i:24;s:6:\"15 Oct\";i:25;s:6:\"16 Oct\";i:26;s:6:\"17 Oct\";i:27;s:6:\"18 Oct\";i:28;s:6:\"19 Oct\";i:29;s:6:\"20 Oct\";i:30;s:6:\"21 Oct\";i:31;s:6:\"22 Oct\";i:32;s:6:\"23 Oct\";i:33;s:6:\"24 Oct\";i:34;s:6:\"25 Oct\";i:35;s:6:\"26 Oct\";i:36;s:6:\"27 Oct\";i:37;s:6:\"28 Oct\";i:38;s:6:\"29 Oct\";i:39;s:6:\"30 Oct\";i:40;s:6:\"31 Oct\";i:41;s:6:\"01 Nov\";i:42;s:6:\"02 Nov\";i:43;s:6:\"03 Nov\";i:44;s:6:\"04 Nov\";i:45;s:6:\"05 Nov\";i:46;s:6:\"06 Nov\";i:47;s:6:\"07 Nov\";i:48;s:6:\"08 Nov\";i:49;s:6:\"09 Nov\";i:50;s:6:\"10 Nov\";i:51;s:6:\"11 Nov\";i:52;s:6:\"12 Nov\";i:53;s:6:\"13 Nov\";i:54;s:6:\"14 Nov\";i:55;s:6:\"15 Nov\";i:56;s:6:\"16 Nov\";i:57;s:6:\"17 Nov\";i:58;s:6:\"18 Nov\";i:59;s:6:\"19 Nov\";i:60;s:6:\"20 Nov\";}s:17:\"visitorDailyTotal\";a:61:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:0;i:27;i:0;i:28;i:0;i:29;i:0;i:30;i:0;i:31;i:0;i:32;i:0;i:33;i:0;i:34;i:0;i:35;i:0;i:36;i:0;i:37;i:0;i:38;i:0;i:39;i:0;i:40;i:0;i:41;i:0;i:42;i:0;i:43;i:0;i:44;i:0;i:45;i:0;i:46;i:0;i:47;i:0;i:48;i:0;i:49;i:0;i:50;i:0;i:51;i:0;i:52;i:0;i:53;i:0;i:54;i:0;i:55;i:0;i:56;i:0;i:57;i:0;i:58;i:0;i:59;i:24;i:60;i:0;}s:18:\"visitorDailyUnique\";a:61:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:0;i:27;i:0;i:28;i:0;i:29;i:0;i:30;i:0;i:31;i:0;i:32;i:0;i:33;i:0;i:34;i:0;i:35;i:0;i:36;i:0;i:37;i:0;i:38;i:0;i:39;i:0;i:40;i:0;i:41;i:0;i:42;i:0;i:43;i:0;i:44;i:0;i:45;i:0;i:46;i:0;i:47;i:0;i:48;i:0;i:49;i:0;i:50;i:0;i:51;i:0;i:52;i:0;i:53;i:0;i:54;i:0;i:55;i:0;i:56;i:0;i:57;i:0;i:58;i:0;i:59;i:10;i:60;i:0;}s:18:\"visitorMonthLabels\";a:3:{i:0;s:8:\"Sep 2026\";i:1;s:8:\"Oct 2026\";i:2;s:8:\"Nov 2026\";}s:17:\"visitorMonthTotal\";a:3:{i:0;i:0;i:1;i:0;i:2;i:24;}s:18:\"visitorMonthUnique\";a:3:{i:0;i:0;i:1;i:0;i:2;i:10;}s:14:\"activeVisitors\";i:0;s:23:\"activeVisitorsUpdatedAt\";s:5:\"08.04\";s:22:\"analyticsContentLabels\";a:6:{i:0;s:1:\"/\";i:1;s:12:\"/instructors\";i:2;s:6:\"/event\";i:3;s:10:\"/schedules\";i:4;s:9:\"/packages\";i:5;s:14:\"/art-of-living\";}s:22:\"analyticsContentCounts\";a:6:{i:0;i:15;i:1;i:3;i:2;i:3;i:3;i:1;i:4;i:1;i:5;i:1;}s:21:\"analyticsDeviceLabels\";a:3:{i:0;s:7:\"Desktop\";i:1;s:6:\"Ponsel\";i:2;s:6:\"Tablet\";}s:21:\"analyticsDeviceCounts\";a:3:{i:0;i:24;i:1;i:0;i:2;i:0;}}s:13:\"contentTotals\";a:3:{s:3:\"faq\";i:6;s:11:\"testimonial\";i:3;s:4:\"poll\";i:1;}}', 1795136983),
('dashboard.admin.charts.a949b6ee52c3d43f07e7d7656f621d6d3fdf3bc8', 'a:2:{s:12:\"revenueChart\";a:3:{s:5:\"daily\";a:2:{s:6:\"labels\";a:30:{i:0;s:6:\"22 Oct\";i:1;s:6:\"23 Oct\";i:2;s:6:\"24 Oct\";i:3;s:6:\"25 Oct\";i:4;s:6:\"26 Oct\";i:5;s:6:\"27 Oct\";i:6;s:6:\"28 Oct\";i:7;s:6:\"29 Oct\";i:8;s:6:\"30 Oct\";i:9;s:6:\"31 Oct\";i:10;s:6:\"01 Nov\";i:11;s:6:\"02 Nov\";i:12;s:6:\"03 Nov\";i:13;s:6:\"04 Nov\";i:14;s:6:\"05 Nov\";i:15;s:6:\"06 Nov\";i:16;s:6:\"07 Nov\";i:17;s:6:\"08 Nov\";i:18;s:6:\"09 Nov\";i:19;s:6:\"10 Nov\";i:20;s:6:\"11 Nov\";i:21;s:6:\"12 Nov\";i:22;s:6:\"13 Nov\";i:23;s:6:\"14 Nov\";i:24;s:6:\"15 Nov\";i:25;s:6:\"16 Nov\";i:26;s:6:\"17 Nov\";i:27;s:6:\"18 Nov\";i:28;s:6:\"19 Nov\";i:29;s:6:\"20 Nov\";}s:4:\"data\";a:30:{i:0;d:0;i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;i:5;d:0;i:6;d:0;i:7;d:0;i:8;d:0;i:9;d:0;i:10;d:0;i:11;d:0;i:12;d:0;i:13;d:0;i:14;d:0;i:15;d:0;i:16;d:0;i:17;d:0;i:18;d:0;i:19;d:0;i:20;d:0;i:21;d:0;i:22;d:0;i:23;d:0;i:24;d:0;i:25;d:0;i:26;d:0;i:27;d:0;i:28;d:0;i:29;d:0;}}s:7:\"monthly\";a:2:{s:6:\"labels\";a:2:{i:0;s:8:\"Oct 2026\";i:1;s:8:\"Nov 2026\";}s:4:\"data\";a:2:{i:0;d:0;i:1;d:0;}}s:6:\"yearly\";a:2:{s:6:\"labels\";a:1:{i:0;s:4:\"2026\";}s:4:\"data\";a:1:{i:0;d:0;}}}s:4:\"site\";a:12:{s:18:\"visitorDailyLabels\";a:30:{i:0;s:6:\"22 Oct\";i:1;s:6:\"23 Oct\";i:2;s:6:\"24 Oct\";i:3;s:6:\"25 Oct\";i:4;s:6:\"26 Oct\";i:5;s:6:\"27 Oct\";i:6;s:6:\"28 Oct\";i:7;s:6:\"29 Oct\";i:8;s:6:\"30 Oct\";i:9;s:6:\"31 Oct\";i:10;s:6:\"01 Nov\";i:11;s:6:\"02 Nov\";i:12;s:6:\"03 Nov\";i:13;s:6:\"04 Nov\";i:14;s:6:\"05 Nov\";i:15;s:6:\"06 Nov\";i:16;s:6:\"07 Nov\";i:17;s:6:\"08 Nov\";i:18;s:6:\"09 Nov\";i:19;s:6:\"10 Nov\";i:20;s:6:\"11 Nov\";i:21;s:6:\"12 Nov\";i:22;s:6:\"13 Nov\";i:23;s:6:\"14 Nov\";i:24;s:6:\"15 Nov\";i:25;s:6:\"16 Nov\";i:26;s:6:\"17 Nov\";i:27;s:6:\"18 Nov\";i:28;s:6:\"19 Nov\";i:29;s:6:\"20 Nov\";}s:17:\"visitorDailyTotal\";a:30:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:0;i:27;i:0;i:28;i:24;i:29;i:35;}s:18:\"visitorDailyUnique\";a:30:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:0;i:27;i:0;i:28;i:10;i:29;i:11;}s:18:\"visitorMonthLabels\";a:2:{i:0;s:8:\"Oct 2026\";i:1;s:8:\"Nov 2026\";}s:17:\"visitorMonthTotal\";a:2:{i:0;i:0;i:1;i:59;}s:18:\"visitorMonthUnique\";a:2:{i:0;i:0;i:1;i:21;}s:14:\"activeVisitors\";i:0;s:23:\"activeVisitorsUpdatedAt\";s:5:\"13.21\";s:22:\"analyticsContentLabels\";a:8:{i:0;s:1:\"/\";i:1;s:4:\"/faq\";i:2;s:12:\"/instructors\";i:3;s:10:\"/schedules\";i:4;s:14:\"/art-of-living\";i:5;s:6:\"/event\";i:6;s:9:\"/packages\";i:7;s:6:\"/about\";}s:22:\"analyticsContentCounts\";a:8:{i:0;i:25;i:1;i:13;i:2;i:5;i:3;i:3;i:4;i:3;i:5;i:3;i:6;i:2;i:7;i:2;}s:21:\"analyticsDeviceLabels\";a:3:{i:0;s:7:\"Desktop\";i:1;s:6:\"Ponsel\";i:2;s:6:\"Tablet\";}s:21:\"analyticsDeviceCounts\";a:3:{i:0;i:59;i:1;i:0;i:2;i:0;}}}', 1795155995),
('dashboard.admin.charts.eda8e133a3679f81a49c0af2c51ad648e9167fab', 'a:2:{s:12:\"revenueChart\";a:3:{s:5:\"daily\";a:2:{s:6:\"labels\";a:61:{i:0;s:6:\"21 Sep\";i:1;s:6:\"22 Sep\";i:2;s:6:\"23 Sep\";i:3;s:6:\"24 Sep\";i:4;s:6:\"25 Sep\";i:5;s:6:\"26 Sep\";i:6;s:6:\"27 Sep\";i:7;s:6:\"28 Sep\";i:8;s:6:\"29 Sep\";i:9;s:6:\"30 Sep\";i:10;s:6:\"01 Oct\";i:11;s:6:\"02 Oct\";i:12;s:6:\"03 Oct\";i:13;s:6:\"04 Oct\";i:14;s:6:\"05 Oct\";i:15;s:6:\"06 Oct\";i:16;s:6:\"07 Oct\";i:17;s:6:\"08 Oct\";i:18;s:6:\"09 Oct\";i:19;s:6:\"10 Oct\";i:20;s:6:\"11 Oct\";i:21;s:6:\"12 Oct\";i:22;s:6:\"13 Oct\";i:23;s:6:\"14 Oct\";i:24;s:6:\"15 Oct\";i:25;s:6:\"16 Oct\";i:26;s:6:\"17 Oct\";i:27;s:6:\"18 Oct\";i:28;s:6:\"19 Oct\";i:29;s:6:\"20 Oct\";i:30;s:6:\"21 Oct\";i:31;s:6:\"22 Oct\";i:32;s:6:\"23 Oct\";i:33;s:6:\"24 Oct\";i:34;s:6:\"25 Oct\";i:35;s:6:\"26 Oct\";i:36;s:6:\"27 Oct\";i:37;s:6:\"28 Oct\";i:38;s:6:\"29 Oct\";i:39;s:6:\"30 Oct\";i:40;s:6:\"31 Oct\";i:41;s:6:\"01 Nov\";i:42;s:6:\"02 Nov\";i:43;s:6:\"03 Nov\";i:44;s:6:\"04 Nov\";i:45;s:6:\"05 Nov\";i:46;s:6:\"06 Nov\";i:47;s:6:\"07 Nov\";i:48;s:6:\"08 Nov\";i:49;s:6:\"09 Nov\";i:50;s:6:\"10 Nov\";i:51;s:6:\"11 Nov\";i:52;s:6:\"12 Nov\";i:53;s:6:\"13 Nov\";i:54;s:6:\"14 Nov\";i:55;s:6:\"15 Nov\";i:56;s:6:\"16 Nov\";i:57;s:6:\"17 Nov\";i:58;s:6:\"18 Nov\";i:59;s:6:\"19 Nov\";i:60;s:6:\"20 Nov\";}s:4:\"data\";a:61:{i:0;d:0;i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;i:5;d:0;i:6;d:0;i:7;d:0;i:8;d:0;i:9;d:0;i:10;d:0;i:11;d:0;i:12;d:0;i:13;d:210000;i:14;d:0;i:15;d:210000;i:16;d:0;i:17;d:0;i:18;d:0;i:19;d:0;i:20;d:0;i:21;d:0;i:22;d:0;i:23;d:0;i:24;d:0;i:25;d:0;i:26;d:0;i:27;d:0;i:28;d:0;i:29;d:0;i:30;d:0;i:31;d:0;i:32;d:0;i:33;d:0;i:34;d:0;i:35;d:0;i:36;d:0;i:37;d:0;i:38;d:0;i:39;d:0;i:40;d:0;i:41;d:0;i:42;d:0;i:43;d:0;i:44;d:0;i:45;d:0;i:46;d:0;i:47;d:0;i:48;d:0;i:49;d:0;i:50;d:0;i:51;d:0;i:52;d:0;i:53;d:0;i:54;d:0;i:55;d:0;i:56;d:0;i:57;d:0;i:58;d:0;i:59;d:0;i:60;d:0;}}s:7:\"monthly\";a:2:{s:6:\"labels\";a:3:{i:0;s:8:\"Sep 2026\";i:1;s:8:\"Oct 2026\";i:2;s:8:\"Nov 2026\";}s:4:\"data\";a:3:{i:0;d:0;i:1;d:420000;i:2;d:0;}}s:6:\"yearly\";a:2:{s:6:\"labels\";a:1:{i:0;s:4:\"2026\";}s:4:\"data\";a:1:{i:0;d:420000;}}}s:4:\"site\";a:12:{s:18:\"visitorDailyLabels\";a:61:{i:0;s:6:\"21 Sep\";i:1;s:6:\"22 Sep\";i:2;s:6:\"23 Sep\";i:3;s:6:\"24 Sep\";i:4;s:6:\"25 Sep\";i:5;s:6:\"26 Sep\";i:6;s:6:\"27 Sep\";i:7;s:6:\"28 Sep\";i:8;s:6:\"29 Sep\";i:9;s:6:\"30 Sep\";i:10;s:6:\"01 Oct\";i:11;s:6:\"02 Oct\";i:12;s:6:\"03 Oct\";i:13;s:6:\"04 Oct\";i:14;s:6:\"05 Oct\";i:15;s:6:\"06 Oct\";i:16;s:6:\"07 Oct\";i:17;s:6:\"08 Oct\";i:18;s:6:\"09 Oct\";i:19;s:6:\"10 Oct\";i:20;s:6:\"11 Oct\";i:21;s:6:\"12 Oct\";i:22;s:6:\"13 Oct\";i:23;s:6:\"14 Oct\";i:24;s:6:\"15 Oct\";i:25;s:6:\"16 Oct\";i:26;s:6:\"17 Oct\";i:27;s:6:\"18 Oct\";i:28;s:6:\"19 Oct\";i:29;s:6:\"20 Oct\";i:30;s:6:\"21 Oct\";i:31;s:6:\"22 Oct\";i:32;s:6:\"23 Oct\";i:33;s:6:\"24 Oct\";i:34;s:6:\"25 Oct\";i:35;s:6:\"26 Oct\";i:36;s:6:\"27 Oct\";i:37;s:6:\"28 Oct\";i:38;s:6:\"29 Oct\";i:39;s:6:\"30 Oct\";i:40;s:6:\"31 Oct\";i:41;s:6:\"01 Nov\";i:42;s:6:\"02 Nov\";i:43;s:6:\"03 Nov\";i:44;s:6:\"04 Nov\";i:45;s:6:\"05 Nov\";i:46;s:6:\"06 Nov\";i:47;s:6:\"07 Nov\";i:48;s:6:\"08 Nov\";i:49;s:6:\"09 Nov\";i:50;s:6:\"10 Nov\";i:51;s:6:\"11 Nov\";i:52;s:6:\"12 Nov\";i:53;s:6:\"13 Nov\";i:54;s:6:\"14 Nov\";i:55;s:6:\"15 Nov\";i:56;s:6:\"16 Nov\";i:57;s:6:\"17 Nov\";i:58;s:6:\"18 Nov\";i:59;s:6:\"19 Nov\";i:60;s:6:\"20 Nov\";}s:17:\"visitorDailyTotal\";a:61:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:0;i:27;i:0;i:28;i:0;i:29;i:0;i:30;i:0;i:31;i:0;i:32;i:0;i:33;i:0;i:34;i:0;i:35;i:0;i:36;i:0;i:37;i:0;i:38;i:0;i:39;i:0;i:40;i:0;i:41;i:0;i:42;i:0;i:43;i:0;i:44;i:0;i:45;i:0;i:46;i:0;i:47;i:0;i:48;i:0;i:49;i:0;i:50;i:0;i:51;i:0;i:52;i:0;i:53;i:0;i:54;i:0;i:55;i:0;i:56;i:0;i:57;i:0;i:58;i:0;i:59;i:24;i:60;i:9;}s:18:\"visitorDailyUnique\";a:61:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:0;i:27;i:0;i:28;i:0;i:29;i:0;i:30;i:0;i:31;i:0;i:32;i:0;i:33;i:0;i:34;i:0;i:35;i:0;i:36;i:0;i:37;i:0;i:38;i:0;i:39;i:0;i:40;i:0;i:41;i:0;i:42;i:0;i:43;i:0;i:44;i:0;i:45;i:0;i:46;i:0;i:47;i:0;i:48;i:0;i:49;i:0;i:50;i:0;i:51;i:0;i:52;i:0;i:53;i:0;i:54;i:0;i:55;i:0;i:56;i:0;i:57;i:0;i:58;i:0;i:59;i:10;i:60;i:2;}s:18:\"visitorMonthLabels\";a:3:{i:0;s:8:\"Sep 2026\";i:1;s:8:\"Oct 2026\";i:2;s:8:\"Nov 2026\";}s:17:\"visitorMonthTotal\";a:3:{i:0;i:0;i:1;i:0;i:2;i:33;}s:18:\"visitorMonthUnique\";a:3:{i:0;i:0;i:1;i:0;i:2;i:12;}s:14:\"activeVisitors\";i:1;s:23:\"activeVisitorsUpdatedAt\";s:5:\"08.10\";s:22:\"analyticsContentLabels\";a:7:{i:0;s:1:\"/\";i:1;s:12:\"/instructors\";i:2;s:10:\"/schedules\";i:3;s:6:\"/event\";i:4;s:9:\"/packages\";i:5;s:14:\"/art-of-living\";i:6;s:8:\"/classes\";}s:22:\"analyticsContentCounts\";a:7:{i:0;i:18;i:1;i:4;i:2;i:3;i:3;i:3;i:4;i:2;i:5;i:2;i:6;i:1;}s:21:\"analyticsDeviceLabels\";a:3:{i:0;s:7:\"Desktop\";i:1;s:6:\"Ponsel\";i:2;s:6:\"Tablet\";}s:21:\"analyticsDeviceCounts\";a:3:{i:0;i:33;i:1;i:0;i:2;i:0;}}}', 1795137329),
('dashboard.artofliving.courses', 'a:1:{i:0;a:108:{s:11:\"bundle_type\";s:17:\"course_event_info\";s:14:\"formatted_date\";s:0:\"\";s:15:\"weekday_timings\";s:19:\"10:30 AM - 12:00 PM\";s:15:\"weekend_timings\";s:18:\"9:30 AM - 11:30 AM\";s:3:\"wkt\";s:29:\"POINT (106.879852 -6.1504392)\";s:4:\"type\";s:5:\"point\";s:8:\"end_date\";s:19:\"2026-11-24 05:00:00\";s:10:\"start_date\";s:19:\"2026-11-24 03:30:00\";s:17:\"last_updated_date\";s:19:\"2026-11-17 05:08:26\";s:12:\"created_date\";s:19:\"2026-05-19 08:22:05\";s:12:\"contact_name\";s:21:\"Artofliving Indonesia\";s:13:\"contact_names\";N;s:13:\"center_emails\";N;s:11:\"coordinates\";a:2:{i:0;d:106.879852;i:1;d:-6.1504392;}s:5:\"ctype\";s:6:\"344663\";s:12:\"common_ctype\";s:0:\"\";s:6:\"mctype\";s:4:\"1006\";s:7:\"address\";s:111:\"The Art of Living Center - Jakarta,  Jl. Danau Indah Raya Blok A1 No. 2 Sunter, North Jakarta, Indonesia, 14350\";s:13:\"address_short\";s:100:\"The Art of Living Center - Jakarta,  Jl. Danau Indah Raya Blok A1 No. 2 Sunter, North Jakarta, 14350\";s:16:\"street_address_1\";s:0:\"\";s:16:\"street_address_2\";s:41:\"Jl. Danau Indah Raya Blok A1 No. 2 Sunter\";s:4:\"city\";s:13:\"North Jakarta\";s:5:\"state\";s:0:\"\";s:15:\"zip_postal_code\";s:5:\"14350\";s:7:\"country\";s:2:\"ID\";s:5:\"email\";s:19:\"info@artofliving.id\";s:2:\"id\";i:1675701;s:6:\"sao_id\";i:740;s:8:\"sao_type\";i:1;s:9:\"course_id\";s:6:\"AK0589\";s:4:\"link\";s:37:\"www.artofliving.org/id-id/program/740\";s:16:\"local_center_url\";s:37:\"www.artofliving.org/id-id/node/196046\";s:14:\"many_day_event\";b:0;s:6:\"phones\";a:1:{i:0;s:10:\"0216513123\";}s:19:\"recur_event_display\";s:68:\"RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=TU;UNTIL=20270519T235959Z;WKST=SU\";s:12:\"is_recurring\";b:1;s:12:\"register_url\";s:90:\"https://register.artofliving.org/id/civicrm/event/register?id=791&lcMessages=en_GB&reset=1\";s:8:\"teachers\";N;s:13:\"teachers_info\";N;s:23:\"assistant_teachers_info\";N;s:14:\"organizer_info\";N;s:12:\"max_capacity\";s:0:\"\";s:5:\"title\";s:39:\"Sudarshan Kriya (SKY) Follow-up Session\";s:14:\"course_expense\";i:0;s:10:\"course_fee\";i:0;s:16:\"course_extra_fee\";i:0;s:16:\"early_course_fee\";i:0;s:19:\"early_repeaters_fee\";i:0;s:17:\"early_seniors_fee\";i:0;s:18:\"early_students_fee\";i:0;s:15:\"late_course_fee\";i:0;s:18:\"late_repeaters_fee\";i:0;s:15:\"late_senior_fee\";i:0;s:16:\"late_student_fee\";i:0;s:12:\"repeater_fee\";i:0;s:10:\"senior_fee\";i:0;s:11:\"student_fee\";i:0;s:8:\"currency\";s:3:\"IDR\";s:5:\"notes\";s:37:\"<p>Wekly : Long Kriya&nbsp;</p><br/> \";s:11:\"description\";s:0:\"\";s:9:\"image_url\";s:0:\"\";s:8:\"timezone\";s:12:\"Asia/Jakarta\";s:14:\"accommodations\";N;s:15:\"complex_timings\";s:0:\"\";s:21:\"course_complex_timing\";s:0:\"\";s:17:\"is_complex_timing\";b:0;s:21:\"registration_required\";b:1;s:18:\"residential_course\";b:0;s:11:\"max_tickets\";s:1:\"1\";s:23:\"info_for_group_required\";s:1:\"1\";s:16:\"center_image_url\";s:0:\"\";s:11:\"center_type\";s:0:\"\";s:16:\"field_is_country\";i:0;s:14:\"field_is_state\";i:0;s:11:\"center_name\";s:21:\"Art of Living Jakarta\";s:37:\"is_accommodation_fee_pay_mode_offline\";b:0;s:31:\"is_expense_fee_pay_mode_offline\";b:0;s:7:\"is_paid\";b:1;s:9:\"is_parent\";i:1;s:22:\"is_event_capacity_full\";s:1:\"0\";s:14:\"has_early_bird\";s:0:\"\";s:19:\"early_birt_end_date\";s:0:\"\";s:15:\"is_online_event\";i:0;s:15:\"course_language\";a:0:{}s:19:\"translate_languages\";a:0:{}s:14:\"event_locality\";s:0:\"\";s:10:\"is_private\";i:0;s:15:\"shankara_course\";b:0;s:11:\"week_number\";i:0;s:8:\"campaign\";s:0:\"\";s:26:\"field_eligibility_criteria\";i:0;s:11:\"event_dates\";a:4:{i:0;a:3:{s:10:\"start_date\";s:19:\"2026-11-24T12:00:00\";s:8:\"end_date\";s:19:\"2026-11-24T12:00:00\";s:6:\"r_rule\";s:68:\"RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=TU;UNTIL=20270519T235959Z;WKST=SU\";}i:1;a:3:{s:10:\"start_date\";s:19:\"2026-12-01T12:00:00\";s:8:\"end_date\";s:19:\"2026-12-01T12:00:00\";s:6:\"r_rule\";s:68:\"RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=TU;UNTIL=20270519T235959Z;WKST=SU\";}i:2;a:3:{s:10:\"start_date\";s:19:\"2026-12-08T12:00:00\";s:8:\"end_date\";s:19:\"2026-12-08T12:00:00\";s:6:\"r_rule\";s:68:\"RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=TU;UNTIL=20270519T235959Z;WKST=SU\";}i:3;a:3:{s:10:\"start_date\";s:19:\"2026-12-15T12:00:00\";s:8:\"end_date\";s:19:\"2026-12-15T12:00:00\";s:6:\"r_rule\";s:68:\"RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=TU;UNTIL=20270519T235959Z;WKST=SU\";}}s:16:\"facilitator_name\";s:0:\"\";s:17:\"bx_course_country\";s:0:\"\";s:23:\"sponsoring_organization\";s:0:\"\";s:17:\"bx_course_timings\";N;s:15:\"course_subtitle\";N;s:22:\"field_bx_accommodation\";a:3:{s:12:\"accomadation\";a:1:{s:7:\"options\";N;}s:14:\"early_end_date\";s:0:\"\";s:8:\"is_early\";b:0;}s:28:\"field_bx_event_accommodation\";a:3:{s:12:\"accomadation\";N;s:14:\"early_end_date\";s:0:\"\";s:8:\"is_early\";i:0;}s:13:\"course_source\";s:2:\"HX\";s:15:\"field_time_zone\";s:0:\"\";s:32:\"bx_registration_closed_date_time\";s:0:\"\";s:22:\"is_registration_closed\";b:0;s:10:\"price_sets\";N;s:14:\"register_count\";i:0;s:13:\"shopamine_url\";s:38:\"https://www.artofliving.org/id-en/i_23\";s:24:\"is_registration_required\";b:0;s:26:\"course_group_shopmine_urls\";N;}}', 1795157335),
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:132:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"menu.main-menu\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:20:\"menu.role-permission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:22:\"menu.access-management\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:15:\"dashboard.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"user.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:10:\"user.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:11:\"user.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"user.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:16:\"menu-group.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"menu-group.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:17:\"menu-group.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:18:\"menu-group.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:15:\"menu-item.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:15:\"menu-item.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:16:\"menu-item.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:17:\"menu-item.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:11:\"route.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:11:\"route.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:12:\"route.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:13:\"route.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:10:\"role.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:10:\"role.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:11:\"role.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"role.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:16:\"permission.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:16:\"permission.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:17:\"permission.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:18:\"permission.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:11:\"faq.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:9:\"faq.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:9:\"faq.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:10:\"faq.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:20:\"menu.main-portofolio\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:33;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:13:\"company.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:13:\"company.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:14:\"company.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:18:\"categories.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:37;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:16:\"categories.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:38;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:16:\"categories.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:39;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:17:\"categories.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:40;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:14:\"banner.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:41;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:12:\"banner.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:42;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:12:\"banner.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:43;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:13:\"banner.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:44;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:16:\"articles.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:45;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:15:\"articles.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:46;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:14:\"articles.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:47;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:14:\"articles.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:48;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:15:\"articles.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:49;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:14:\"dashboard.form\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:50;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:12:\"poll.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:51;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:10:\"poll.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:52;a:4:{s:1:\"a\";i:70;s:1:\"b\";s:10:\"poll.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:53;a:4:{s:1:\"a\";i:75;s:1:\"b\";s:11:\"poll.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:54;a:4:{s:1:\"a\";i:85;s:1:\"b\";s:13:\"account.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:55;a:4:{s:1:\"a\";i:90;s:1:\"b\";s:14:\"account.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:56;a:3:{s:1:\"a\";i:125;s:1:\"b\";s:20:\"filedownload.destroy\";s:1:\"c\";s:3:\"web\";}i:57;a:4:{s:1:\"a\";i:130;s:1:\"b\";s:18:\"filedownload.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:5;}}i:58;a:3:{s:1:\"a\";i:135;s:1:\"b\";s:18:\"filedownload.store\";s:1:\"c\";s:3:\"web\";}i:59;a:3:{s:1:\"a\";i:140;s:1:\"b\";s:19:\"filedownload.update\";s:1:\"c\";s:3:\"web\";}i:60;a:4:{s:1:\"a\";i:146;s:1:\"b\";s:12:\"vendor.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:61;a:4:{s:1:\"a\";i:147;s:1:\"b\";s:12:\"pages.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:62;a:4:{s:1:\"a\";i:148;s:1:\"b\";s:13:\"pages.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:63;a:4:{s:1:\"a\";i:149;s:1:\"b\";s:11:\"pages.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:64;a:4:{s:1:\"a\";i:150;s:1:\"b\";s:11:\"pages.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:65;a:4:{s:1:\"a\";i:151;s:1:\"b\";s:12:\"pages.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:66;a:4:{s:1:\"a\";i:152;s:1:\"b\";s:14:\"vendor.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:67;a:4:{s:1:\"a\";i:153;s:1:\"b\";s:12:\"vendor.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:68;a:4:{s:1:\"a\";i:154;s:1:\"b\";s:13:\"vendor.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:69;a:4:{s:1:\"a\";i:155;s:1:\"b\";s:19:\"testimonial.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:70;a:4:{s:1:\"a\";i:156;s:1:\"b\";s:17:\"testimonial.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:71;a:4:{s:1:\"a\";i:157;s:1:\"b\";s:18:\"testimonial.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:72;a:4:{s:1:\"a\";i:158;s:1:\"b\";s:17:\"testimonial.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:73;a:4:{s:1:\"a\";i:159;s:1:\"b\";s:12:\"events.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:74;a:4:{s:1:\"a\";i:160;s:1:\"b\";s:14:\"events.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:75;a:4:{s:1:\"a\";i:161;s:1:\"b\";s:12:\"events.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:76;a:4:{s:1:\"a\";i:162;s:1:\"b\";s:13:\"events.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:77;a:4:{s:1:\"a\";i:163;s:1:\"b\";s:24:\"vendors_categories.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:78;a:4:{s:1:\"a\";i:164;s:1:\"b\";s:26:\"vendors_categories.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:79;a:4:{s:1:\"a\";i:165;s:1:\"b\";s:25:\"vendors_categories.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:80;a:4:{s:1:\"a\";i:166;s:1:\"b\";s:24:\"vendors_categories.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:81;a:4:{s:1:\"a\";i:167;s:1:\"b\";s:15:\"package.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:82;a:4:{s:1:\"a\";i:168;s:1:\"b\";s:13:\"package.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:83;a:4:{s:1:\"a\";i:169;s:1:\"b\";s:14:\"package.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:84;a:4:{s:1:\"a\";i:170;s:1:\"b\";s:13:\"package.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:85;a:4:{s:1:\"a\";i:171;s:1:\"b\";s:14:\"package.member\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:86;a:4:{s:1:\"a\";i:172;s:1:\"b\";s:15:\"classes.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:87;a:4:{s:1:\"a\";i:173;s:1:\"b\";s:13:\"classes.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:88;a:4:{s:1:\"a\";i:174;s:1:\"b\";s:13:\"classes.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:89;a:4:{s:1:\"a\";i:175;s:1:\"b\";s:14:\"classes.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:90;a:4:{s:1:\"a\";i:176;s:1:\"b\";s:22:\"dashboard.submitSumber\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:91;a:4:{s:1:\"a\";i:177;s:1:\"b\";s:14:\"classes.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:92;a:4:{s:1:\"a\";i:178;s:1:\"b\";s:12:\"classes.edit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:93;a:4:{s:1:\"a\";i:179;s:1:\"b\";s:20:\"classes.change-level\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:94;a:4:{s:1:\"a\";i:180;s:1:\"b\";s:23:\"class-schedules.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:95;a:4:{s:1:\"a\";i:181;s:1:\"b\";s:21:\"class-schedules.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:96;a:4:{s:1:\"a\";i:182;s:1:\"b\";s:21:\"class-schedules.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:97;a:4:{s:1:\"a\";i:183;s:1:\"b\";s:22:\"class-schedules.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:98;a:4:{s:1:\"a\";i:184;s:1:\"b\";s:21:\"class-schedules.print\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:99;a:4:{s:1:\"a\";i:185;s:1:\"b\";s:14:\"pengguna.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:100;a:4:{s:1:\"a\";i:186;s:1:\"b\";s:15:\"pengguna.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:101;a:4:{s:1:\"a\";i:187;s:1:\"b\";s:12:\"venues.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:102;a:4:{s:1:\"a\";i:188;s:1:\"b\";s:14:\"venues.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:103;a:4:{s:1:\"a\";i:189;s:1:\"b\";s:13:\"venues.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:104;a:4:{s:1:\"a\";i:190;s:1:\"b\";s:12:\"venues.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:105;a:4:{s:1:\"a\";i:191;s:1:\"b\";s:13:\"venues.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:106;a:4:{s:1:\"a\";i:192;s:1:\"b\";s:13:\"vendor.mobile\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:107;a:4:{s:1:\"a\";i:193;s:1:\"b\";s:16:\"checkout.package\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:108;a:4:{s:1:\"a\";i:194;s:1:\"b\";s:12:\"orders.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:109;a:4:{s:1:\"a\";i:195;s:1:\"b\";s:11:\"orders.show\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:110;a:4:{s:1:\"a\";i:196;s:1:\"b\";s:12:\"orders.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:111;a:4:{s:1:\"a\";i:197;s:1:\"b\";s:13:\"menu.keamanan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:112;a:4:{s:1:\"a\";i:198;s:1:\"b\";s:20:\"login-activity.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:113;a:4:{s:1:\"a\";i:199;s:1:\"b\";s:22:\"login-activity.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:114;a:4:{s:1:\"a\";i:200;s:1:\"b\";s:19:\"login-lockout.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:115;a:4:{s:1:\"a\";i:201;s:1:\"b\";s:21:\"login-lockout.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:116;a:4:{s:1:\"a\";i:202;s:1:\"b\";s:15:\"audit-log.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:117;a:4:{s:1:\"a\";i:203;s:1:\"b\";s:17:\"audit-log.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:118;a:4:{s:1:\"a\";i:204;s:1:\"b\";s:18:\"failed-login.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:119;a:4:{s:1:\"a\";i:205;s:1:\"b\";s:20:\"failed-login.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:120;a:4:{s:1:\"a\";i:206;s:1:\"b\";s:22:\"website-identity.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:121;a:4:{s:1:\"a\";i:207;s:1:\"b\";s:23:\"website-identity.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:122;a:4:{s:1:\"a\";i:208;s:1:\"b\";s:17:\"memberships.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:123;a:4:{s:1:\"a\";i:209;s:1:\"b\";s:16:\"memberships.show\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:124;a:4:{s:1:\"a\";i:210;s:1:\"b\";s:20:\"class-bookings.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:125;a:4:{s:1:\"a\";i:211;s:1:\"b\";s:20:\"class-bookings.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:126;a:4:{s:1:\"a\";i:212;s:1:\"b\";s:22:\"class-bookings.checkin\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:127;a:4:{s:1:\"a\";i:213;s:1:\"b\";s:21:\"class-bookings.cancel\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:128;a:4:{s:1:\"a\";i:214;s:1:\"b\";s:14:\"orders.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:129;a:4:{s:1:\"a\";i:215;s:1:\"b\";s:13:\"orders.reject\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:130;a:4:{s:1:\"a\";i:216;s:1:\"b\";s:12:\"orders.proof\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:131;a:4:{s:1:\"a\";i:217;s:1:\"b\";s:16:\"konsultasi.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super-admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:4:\"user\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:6:\"vendor\";s:1:\"c\";s:3:\"web\";}}}', 1795238856),
('website-identity:public', 'a:19:{s:9:\"site_name\";s:14:\"Sagita Wedding\";s:10:\"site_title\";s:14:\"Sagita Wedding\";s:7:\"tagline\";s:15:\"Wedding Planner\";s:17:\"short_description\";s:84:\"Wedding planning, concept, and celebration services designed with elegance and care.\";s:5:\"email\";N;s:5:\"phone\";N;s:7:\"address\";N;s:12:\"facebook_url\";N;s:13:\"instagram_url\";N;s:11:\"youtube_url\";N;s:10:\"tiktok_url\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:13:\"meta_keywords\";N;s:19:\"google_analytics_id\";N;s:24:\"google_site_verification\";N;s:8:\"logo_url\";N;s:11:\"favicon_url\";N;s:12:\"og_image_url\";N;}', 1795155626);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`uuid`, `name`, `slug`, `description`, `icon`, `created_at`, `updated_at`) VALUES
('2162d145-9ef3-4e2f-8c55-81971a015bc5', 'Tips Pernikahan', 'tips-pernikahan', 'Panduan dan tips mempersiapkan pernikahan.', NULL, '2025-09-16 01:03:22', '2026-11-20 04:29:52'),
('34103609-8116-4baf-bd17-557fc6989e8e', 'Inspirasi Pernikahan', 'inspirasi-pernikahan', 'Ide tema, konsep, dan dekorasi wedding.', NULL, '2025-09-16 01:03:16', '2026-11-20 04:30:16'),
('72561291-cdcb-484b-a556-0400fbd53c3d', 'Wedding Planning', 'wedding-planning', 'Anggaran, checklist, timeline, dan rundown.', NULL, '2025-10-31 03:07:08', '2026-11-20 04:30:35'),
('a58f5ebc-c8ec-48da-b6f9-a80b05ca40a0', 'Tren Pernikahan', 'tren-pernikahan', 'Tren makeup, busana, dekorasi, dan konsep terbaru.', NULL, '2026-06-20 12:47:44', '2026-11-20 04:30:58'),
('bb006a6e-36bc-48cb-a84f-2a562489bb54', 'Berita', 'berita', 'Kegiatan, promo, dan informasi terbaru Sagita Wedding Planner.', NULL, '2025-09-16 01:03:27', '2026-11-20 04:31:11');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `quota_cost` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `vendor_uuid` char(36) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `level` enum('foundation','intermediate','advance') NOT NULL,
  `duration` mediumint(10) DEFAULT NULL,
  `is_active` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`uuid`, `name`, `slug`, `description`, `price`, `quota_cost`, `vendor_uuid`, `image`, `level`, `duration`, `is_active`, `created_at`, `updated_at`) VALUES
('01a07699-0698-73e1-8251-7afede34bbb1', 'Yin Yoga', 'yin-yoga', 'Yin yoga is a style of yoga which targets the connective tissue through poses which are held for longer periods of time, typically 3-5 minutes.', 0.00, 1, '95294c43-b3d8-4b46-b938-e4eea1f3a359', 'classes/klSn534j2UGQDMi3KFBImTLGx63CNMBOdTllJlUj.jpg', 'foundation', 60, 'active', '2026-09-06 05:02:15', '2026-10-04 02:04:18'),
('01a0aab9-cdc8-71d6-9dfc-bd30601b7ecb', 'Hatha', 'hatha', 'Grounding Hatha draws from the classical roots of Hatha yoga to create a steady, breath-centered practice that builds both physical awareness and inner stillness.', 0.00, 1, '787b72ea-59d0-4d54-848b-c200bddafdd2', NULL, 'foundation', 60, 'active', '2026-09-16 14:58:18', '2026-09-16 14:59:41'),
('01a0aac6-831b-73b8-8746-869d56f563a4', 'Vinyasa', 'vinyasa', 'A dynamic, breath-led flow linking movement with breath to build strength, flexibility, and focus', 0.00, 1, '787b72ea-59d0-4d54-848b-c200bddafdd2', NULL, 'foundation', 60, 'active', '2026-09-16 15:12:11', '2026-09-16 15:12:11');

-- --------------------------------------------------------

--
-- Table structure for table `class_bookings`
--

CREATE TABLE `class_bookings` (
  `uuid` char(36) NOT NULL,
  `user_uuid` char(36) NOT NULL,
  `class_schedule_uuid` char(36) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_type` enum('package','membership','direct') NOT NULL,
  `quota_used` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` enum('booked','confirmed','waiting_list','attended','cancelled','no_show') NOT NULL DEFAULT 'booked',
  `booked_at` timestamp NULL DEFAULT NULL,
  `attended_at` timestamp NULL DEFAULT NULL,
  `reminded_24h_at` timestamp NULL DEFAULT NULL,
  `reminded_2h_at` timestamp NULL DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `rating_comment` varchar(500) DEFAULT NULL,
  `package_uuid` char(36) DEFAULT NULL,
  `order_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_bookings`
--

INSERT INTO `class_bookings` (`uuid`, `user_uuid`, `class_schedule_uuid`, `booking_date`, `booking_type`, `quota_used`, `status`, `booked_at`, `attended_at`, `reminded_24h_at`, `reminded_2h_at`, `rating`, `rating_comment`, `package_uuid`, `order_uuid`, `created_at`, `updated_at`) VALUES
('01a108ed-d321-7097-80b2-199a86ce5d14', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', '01a07aba-eb15-700e-9ceb-7d37f2772c7e', '2026-10-05', 'package', 1, 'attended', '2026-10-04 21:59:26', '2026-10-04 21:59:26', NULL, NULL, NULL, NULL, '01a095f2-d286-7241-b566-8027529b8196', '01a1049e-afda-7188-b2f9-9fa3b404f6d3', '2026-10-04 21:59:26', '2026-10-04 21:59:26'),
('01a110b4-7661-709d-bd19-b0e59c9caf20', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '01a07699-68e3-71cc-892a-e0557ae317ee', '2026-10-06', 'package', 1, 'attended', '2026-10-06 10:13:44', '2026-10-06 10:13:47', NULL, NULL, NULL, NULL, '01a095f2-d286-7241-b566-8027529b8196', '01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', '2026-10-06 10:13:44', '2026-10-06 10:13:47'),
('01a110d1-2290-7247-9c97-caddec16187a', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '01a07699-68e3-71cc-892a-e0557ae317ee', '2026-10-13', 'package', 0, 'cancelled', '2026-10-06 10:45:03', NULL, NULL, NULL, NULL, NULL, '01a095f2-d286-7241-b566-8027529b8196', '01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', '2026-10-06 10:45:03', '2026-10-06 10:46:27'),
('01a110d1-bfa6-7302-8c13-a5d11e084fee', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '01a07aba-eb15-700e-9ceb-7d37f2772c7e', '2026-10-12', 'package', 0, 'cancelled', '2026-10-06 10:45:43', NULL, NULL, NULL, NULL, NULL, '01a095f2-d286-7241-b566-8027529b8196', '01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', '2026-10-06 10:45:43', '2026-10-06 10:46:14'),
('01a110f3-8832-70ac-94c1-332fe3519fae', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '01a07699-68e3-71cc-892a-e0557ae317ee', '2026-10-13', 'package', 0, 'cancelled', '2026-10-06 11:22:37', NULL, NULL, NULL, NULL, NULL, '01a095f2-d286-7241-b566-8027529b8196', '01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', '2026-10-06 11:22:38', '2026-10-06 11:31:43'),
('01a110fc-4da0-71d1-8967-fc7e99344562', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '01a07aba-eb15-700e-9ceb-7d37f2772c7e', '2026-10-12', 'package', 0, 'cancelled', '2026-10-06 11:32:12', NULL, NULL, NULL, NULL, NULL, '01a095f2-d286-7241-b566-8027529b8196', '01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', '2026-10-06 11:32:12', '2026-10-06 14:11:47'),
('01a11107-c2bf-735e-a6a8-8095e7b4234a', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', '01a07699-68e3-71cc-892a-e0557ae317ee', '2026-10-06', 'package', 1, 'attended', '2026-10-06 11:44:43', '2026-10-06 11:44:43', NULL, NULL, NULL, NULL, '01a095f2-d286-7241-b566-8027529b8196', '01a1049e-afda-7188-b2f9-9fa3b404f6d3', '2026-10-06 11:44:43', '2026-10-06 11:44:43');

-- --------------------------------------------------------

--
-- Table structure for table `class_schedules`
--

CREATE TABLE `class_schedules` (
  `uuid` char(36) NOT NULL,
  `class_uuid` char(36) NOT NULL,
  `venue_uuid` char(36) DEFAULT NULL,
  `day` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `capacity` int(10) UNSIGNED NOT NULL DEFAULT 10,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_schedules`
--

INSERT INTO `class_schedules` (`uuid`, `class_uuid`, `venue_uuid`, `day`, `start_time`, `end_time`, `capacity`, `status`, `created_at`, `updated_at`) VALUES
('01a07699-68e3-71cc-892a-e0557ae317ee', '01a07699-0698-73e1-8251-7afede34bbb1', '363f49dc-b34f-4ac2-ad18-95b140c6335e', 'tuesday', '19:00:00', '20:00:00', 20, 'active', '2026-09-06 05:02:40', '2026-09-14 11:45:38'),
('01a07aba-eb15-700e-9ceb-7d37f2772c7e', '01a07699-0698-73e1-8251-7afede34bbb1', '363f49dc-b34f-4ac2-ad18-95b140c6335e', 'monday', '14:00:00', '15:00:00', 1, 'active', '2026-09-07 00:17:45', '2026-09-14 11:46:23'),
('01a09092-6304-73de-bacb-ead567c883b5', '01a07699-0698-73e1-8251-7afede34bbb1', '363f49dc-b34f-4ac2-ad18-95b140c6335e', 'saturday', '20:04:00', '20:08:00', 10, 'inactive', '2026-09-11 06:05:07', '2026-09-11 06:15:47'),
('01a092b9-6448-7308-9328-96caf2fc5466', '01a07699-0698-73e1-8251-7afede34bbb1', '363f49dc-b34f-4ac2-ad18-95b140c6335e', 'saturday', '11:06:00', '11:18:00', 1, 'inactive', '2026-09-11 16:06:58', '2026-09-12 01:11:47');

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `uuid` char(36) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `whatsapp` varchar(20) NOT NULL,
  `wedding_date` date DEFAULT NULL,
  `guest_estimate` enum('Di bawah 200 tamu','200–500 tamu','500–1.000 tamu','Di atas 1.000 tamu') DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `wedding_concept` text DEFAULT NULL,
  `consultation_date` date DEFAULT NULL,
  `consultation_time` time DEFAULT NULL,
  `consultation_method` enum('Online','Offline') DEFAULT NULL,
  `consultation_status` enum('Pending','Confirmed','Rescheduled','Completed','Cancelled','No Show') NOT NULL DEFAULT 'Pending',
  `admin_notes` text DEFAULT NULL,
  `handled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `follow_up_at` datetime DEFAULT NULL,
  `lead_status` enum('New','Contacted','Interested','Negotiation','Converted','Lost') NOT NULL DEFAULT 'New',
  `source` varchar(50) NOT NULL DEFAULT 'Website',
  `converted_to_booking_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `disabilities`
--

CREATE TABLE `disabilities` (
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `disabilities`
--

INSERT INTO `disabilities` (`uuid`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
('486f36ec-f749-463e-ab20-d06b1d28bf8e', 'Autis', 'autis', 'Gangguan Spektrum Autisme adalah kondisi perkembangan saraf yang memengaruhuhi komunikasi, interaksi sosial dan perilaku.', '2025-11-21 04:17:52', '2025-11-21 04:22:52'),
('538deecf-f902-4394-bb11-ed55207e912e', 'Tunanetra (A)', 'tunanetra-a', 'Tunanetra adalah seseorang yang memiliki gangguan penglihatan, baik sebagian maupun total, yang menghabat aktivitas sehari-hari dengan tingkat gangguan totally blind yang tidak bisa melihat sama sekali dan low vision yang masih bisa  melihat sebagian baik dan ataupun tanpa alat bantu.', '2025-11-11 06:12:12', '2025-11-21 02:35:57'),
('7ae1645d-d10d-44e0-86be-22e44b1db039', 'Tunadaksa (D, D1)', 'tunadaksa-d-d1', 'Tunadaksa adalah kondisi fisik yang menyebabkan keterbatasan dalam mengendalikan gerakan tubuh akibat kelainan pada sistem otot, tulang atau saraf.', '2025-11-21 04:32:42', '2025-11-21 04:33:12'),
('e35fed39-3a2f-4a33-80f6-5820c71a7fd6', 'Tunagrahita (C, C1)', 'tunagrahita-c-c1', 'Tunagrahita adalah kondisi gangguan perkembangan intelektual yang mempengaruhi kemampuan belajar dan menyesuaikan diri.', '2025-11-21 04:16:12', '2025-11-21 04:33:25');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `uuid` char(36) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `deskripsi` longtext DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `waktu_mulai` time DEFAULT NULL,
  `waktu_selesai` time DEFAULT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `kapasitas` int(11) DEFAULT NULL,
  `status` enum('draft','published','cancelled','completed') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`uuid`, `judul`, `slug`, `deskripsi`, `gambar`, `tanggal`, `waktu_mulai`, `waktu_selesai`, `lokasi`, `kapasitas`, `status`, `created_at`, `updated_at`) VALUES
('02471b9c-9346-409c-b9b0-5331d91456ed', 'Growing Together in Nature', 'growing-together-in-nature', '<p><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">A special morning created for mama-to-be.</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Move gently, breathe deeply, connect with your little one, and enjoy a peaceful morning in nature. 🤍</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">📅 Sunday, 13 September 2026</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">⏰ 07.30–10.00 AM</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">📍 Kebun Raya Bogor – Taman Melchior</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">💗 150K / pax</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Includes Prenatal Yoga, Pregnancy Talk, Mini MCU, Garden Picnic, Goodies &amp; Giveaways! ✨</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">🎟️ Register Now:</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">https://bit.ly/PrenatalGentleYogaatKebunRaya</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Come grow, breathe &amp; connect with us. 🌿</span></p>', 'events/NLg7WQHCoKb9lC1eYoe4r8Ca1tUW2sDfOdFrdj3l.jpg', '2026-09-13', '07:30:00', '10:00:00', 'Kebun Raya Bogor – Taman Melchior', 35, 'published', '2026-08-31 18:39:08', '2026-09-01 03:02:52'),
('3191142d-9185-421c-be1d-791c843f6161', 'Yoga Night at the Museum is calling', 'yoga-night-at-the-museum-is-calling', '<p><div class=\"html-div x14z9mp x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x9f619 xjbqb8w x78zum5 x15mokao x1ga7v0g x16uus16 xbiv7yw x1xmf6yo x12nagc x1n2onr6 x1plvlek xryxfnj x1c4vz4f x2lah0s xdt5ytf xqjyukv x1qjc9v5 x1oa3qoh x1nhvcw1\" style=\"margin-inline: 0px; border-start-start-radius: 0px; border-end-end-radius: 0px; padding-inline: 0px; flex-grow: 0; border-start-end-radius: 0px; position: relative; place-content: stretch flex-start; align-self: auto; align-items: stretch; flex-shrink: 0; display: flex; box-sizing: border-box; border-end-start-radius: 0px; flex-direction: column; background-color: rgb(255, 255, 255); margin-bottom: 4px; padding-bottom: 0px; overflow: visible; margin-top: 8px; padding-top: 0px; color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"></div></p><div class=\"xt0psk2\" style=\"display: inline; color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(255, 255, 255); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><h1 class=\"_ap3a _aaco _aacu _aacx _aad7 _aade\" dir=\"auto\" style=\"color: rgb(12, 16, 20); font-size: 14px; font-weight: 400; margin: 0px !important; padding: 0px; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; line-height: 18px; display: inline !important;\">Rasakan sensasi ketenangan malam dan keseimbangan jiwa dalam perayaan Anniversary Yoga Roots yang ke 3 tahun🌿<br><br>📅 Jumat, 18 September 2026<br>⏰ 18.30-19.30 WIB<br>📍 Taman Arca, Museum Nasional Indonesia<br>🎟️ IDR 150K — sudah termasuk tiket masuk museum<br><br>Jangan lupa bawa yoga mat &amp; botol minum pribadi.<br><br>Yuk, luangkan waktu untuk move, breathe, and reconnect di malam yang spesial🤍</h1></div>', 'events/7k4NwTxmHUtn6jbXllpKeB3m6jxe35KGp4shy3Zn.jpg', '2026-09-18', '18:30:00', '19:30:00', 'Taman Arca, Museum Nasional Indonesia', 50, 'published', '2026-09-11 06:50:04', '2026-09-11 07:28:46'),
('64e369a4-ed0f-4009-996e-362f879a681e', 'Sunset Serenity', 'sunset-serenity', '<p><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Take a moment to slow down, breathe deeply, and reconnect with yourself as the sunsets.</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Join us for a relaxing Sunset Yoga Session with Coach Mega at Balcony Skechers, Senayan Park.</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">📅 20 September 2026</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">📍 Balcony Skechers, Senayan Park</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">🕟 4:30 PM</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">🧘‍♀️ Beginner Friendly</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">💫 Only IDR 65,000</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">🎁 Includes Goodie Bag</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Come as you are, flow at your own pace, and end the day feeling refreshed.</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Save your spot and let the sunset be your reset.</span></p>', 'events/duaDypZpAvPRHnf5Wdry1rNUMXK7S4vZPbxxInbq.webp', '2026-09-20', '16:30:00', '16:30:00', 'Balcony Skechers, Senayan Park', 50, 'published', '2026-09-11 07:28:06', '2026-09-11 07:28:06');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_logins`
--

CREATE TABLE `failed_logins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `user_uuid` char(36) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `attempted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_logins`
--

INSERT INTO `failed_logins` (`id`, `email`, `user_uuid`, `ip_address`, `user_agent`, `reason`, `attempted_at`, `created_at`, `updated_at`) VALUES
(2, 'super@admin.com', '787b72ea-59d0-4d54-848b-c200bddafdd2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'credentials', '2026-11-18 22:47:12', '2026-11-18 22:47:12', '2026-11-18 22:47:12'),
(3, 'super@admin.com', '787b72ea-59d0-4d54-848b-c200bddafdd2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'credentials', '2026-11-18 22:47:12', '2026-11-18 22:47:12', '2026-11-18 22:47:12');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `uuid` char(36) NOT NULL,
  `pertanyaan` varchar(255) NOT NULL,
  `kategori` varchar(100) NOT NULL DEFAULT 'Perencanaan Pernikahan',
  `jawaban` text NOT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`uuid`, `pertanyaan`, `kategori`, `jawaban`, `urutan`, `status`, `created_at`, `updated_at`) VALUES
('4b9411a3-ccc5-44fd-bc24-e8dc7065773c', 'When should we start planning our wedding?', 'Perencanaan Pernikahan', 'Ideally, start 6–12 months before the big day. Setting the date, venue, and budget early gives you access to a wider range of options.', 1, 'active', '2026-11-20 02:25:31', '2026-11-20 02:25:31'),
('6bb623f8-916a-436b-85b3-a57035fe5ac5', 'Can we choose our own vendors?', 'Vendor & Mitra', 'Absolutely. You can select from our recommended vendors or propose your own. We will help make sure they fit your concept and budget.', 4, 'active', '2026-11-20 02:25:31', '2026-11-20 02:25:31'),
('95b200f1-69c7-4f1b-a76b-172bcf5fcc55', 'Can we change the date or package after booking?', 'Perubahan & Pembatalan', 'Changes can be requested up to 30 days before the event, subject to availability. Cancellations follow the terms stated in your booking agreement.', 6, 'active', '2026-11-20 02:25:31', '2026-11-20 02:25:31'),
('9f25d34e-2a60-42cf-8379-ed069dc50ac0', 'How do I book a package and make a payment?', 'Pemesanan & Pembayaran', 'Choose a package and submit the booking form. Our team will send an invoice, and payment can be made by bank transfer based on the agreed schedule.', 3, 'active', '2026-11-20 02:25:31', '2026-11-20 02:25:31'),
('fa2b17f9-f0d4-4849-8373-8d787b80113e', 'What is included in a wedding package?', 'Paket & Layanan', 'Packages can include decoration, photography, videography, MC, music, and event coordination. Details can be tailored to your needs.', 2, 'active', '2026-11-20 02:25:31', '2026-11-20 02:25:31'),
('fd90f57c-53aa-4b5d-8e11-faea4808686d', 'What happens on the wedding day?', 'Pelaksanaan Acara', 'Our team arrives early for setup, vendor coordination, and final checks, so you can enjoy the celebration with your guests.', 5, 'active', '2026-11-20 02:25:31', '2026-11-20 02:25:31');

-- --------------------------------------------------------

--
-- Table structure for table `file_downloads`
--

CREATE TABLE `file_downloads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `file` varchar(255) NOT NULL,
  `kategori` enum('akademik','informasi','laporan','edaran') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
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
-- Table structure for table `kecamatans`
--

CREATE TABLE `kecamatans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kecamatans`
--

INSERT INTO `kecamatans` (`id`, `nama`, `created_at`, `updated_at`) VALUES
(1, 'Bantar Gebang', NULL, NULL),
(2, 'Bekasi Barat', NULL, NULL),
(3, 'Bekasi Selatan', NULL, NULL),
(4, 'Bekasi Timur', NULL, NULL),
(5, 'Bekasi Utara', NULL, NULL),
(6, 'Jatiasih', NULL, NULL),
(7, 'Jatisampurna', NULL, NULL),
(8, 'Medan Satria', NULL, NULL),
(9, 'Mustika Jaya', NULL, NULL),
(10, 'Pondok Gede', NULL, NULL),
(11, 'Pondok Melati', NULL, NULL),
(12, 'Rawalumbu', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kelurahans`
--

CREATE TABLE `kelurahans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kecamatan_id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kodepos` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelurahans`
--

INSERT INTO `kelurahans` (`id`, `kecamatan_id`, `nama`, `kodepos`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bantargebang', 17151, NULL, NULL),
(2, 1, 'Ciketing Udik', 17153, NULL, NULL),
(3, 1, 'Cikiwul', 17152, NULL, NULL),
(4, 1, 'Sumur Batu', 17154, NULL, NULL),
(5, 2, 'Bintara', 17134, NULL, NULL),
(6, 2, 'Bintara Jaya', 17136, NULL, NULL),
(7, 2, 'Jakasampurna', 17145, NULL, NULL),
(8, 2, 'Kota Baru', 17133, NULL, NULL),
(9, 2, 'Kranji', 17135, NULL, NULL),
(10, 3, 'Jakamulya', 17146, NULL, NULL),
(11, 3, 'Jakasetia', 17147, NULL, NULL),
(12, 3, 'Kayuringin Jaya', 17144, NULL, NULL),
(13, 3, 'Marga Jaya', 17141, NULL, NULL),
(14, 3, 'Pekayon Jaya', 17148, NULL, NULL),
(15, 4, 'Aren Jaya', 17111, NULL, NULL),
(16, 4, 'Bekasi Jaya', 17112, NULL, NULL),
(17, 4, 'Duren Jaya', 17111, NULL, NULL),
(18, 4, 'Margahayu', 17113, NULL, NULL),
(19, 5, 'Harapan Baru', 17123, NULL, NULL),
(20, 5, 'Harapan Jaya', 17124, NULL, NULL),
(21, 5, 'Kaliabang Tengah', 17125, NULL, NULL),
(22, 5, 'Marga Mulya', 17142, NULL, NULL),
(23, 5, 'Perwira', 17122, NULL, NULL),
(24, 5, 'Teluk Pucung', 17121, NULL, NULL),
(25, 6, 'Jatiasih', 17423, NULL, NULL),
(26, 6, 'Jatikramat', 17421, NULL, NULL),
(27, 6, 'Jatiluhur', 17425, NULL, NULL),
(28, 6, 'Jatimekar', 17422, NULL, NULL),
(29, 6, 'Jatirasa', 17424, NULL, NULL),
(30, 6, 'Jatisari', 17426, NULL, NULL),
(31, 7, 'Jatikarya', 17435, NULL, NULL),
(32, 7, 'Jatiraden', 17433, NULL, NULL),
(33, 7, 'Jatirangga', 17434, NULL, NULL),
(34, 7, 'Jatiranggon', 17432, NULL, NULL),
(35, 7, 'Jatisampurna', 17433, NULL, NULL),
(36, 8, 'Harapan Mulya', 17143, NULL, NULL),
(37, 8, 'Kali Baru', 17133, NULL, NULL),
(38, 8, 'Medan Satria', 17132, NULL, NULL),
(39, 8, 'Pejuang', 17131, NULL, NULL),
(40, 9, 'Cimuning', 17155, NULL, NULL),
(41, 9, 'Mustikajaya', 17158, NULL, NULL),
(42, 9, 'Mustikasari', 17157, NULL, NULL),
(43, 9, 'Padurenan', 17156, NULL, NULL),
(44, 10, 'Jatibening', 17412, NULL, NULL),
(45, 10, 'Jatibening Baru', 17412, NULL, NULL),
(46, 10, 'Jaticempaka', 17411, NULL, NULL),
(47, 10, 'Jatimakmur', 17413, NULL, NULL),
(48, 10, 'Jatiwaringin', 17411, NULL, NULL),
(49, 11, 'Jatimelati', 17414, NULL, NULL),
(50, 11, 'Jatimurni', 17431, NULL, NULL),
(51, 11, 'Jatirahayu', 17414, NULL, NULL),
(52, 11, 'Jatiwarna', 17415, NULL, NULL),
(53, 12, 'Bojong Menteng', 17117, NULL, NULL),
(54, 12, 'Bojong Rawalumbu', 17116, NULL, NULL),
(55, 12, 'Pengasinan', 17115, NULL, NULL),
(56, 12, 'Sepanjang Jaya', 17114, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kontak`
--

CREATE TABLE `kontak` (
  `uuid` char(36) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `no_telp` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `respon` text DEFAULT NULL,
  `status` enum('open','in_progress','resolved','closed','rejected') NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_activities`
--

CREATE TABLE `login_activities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_uuid` char(36) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `guard` varchar(50) DEFAULT NULL,
  `logged_in_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `login_activities`
--

INSERT INTO `login_activities` (`id`, `user_uuid`, `name`, `email`, `ip_address`, `user_agent`, `guard`, `logged_in_at`, `created_at`, `updated_at`) VALUES
(17, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-02 07:17:47', '2026-10-02 07:17:47', '2026-10-02 07:17:47'),
(18, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-02 07:17:47', '2026-10-02 07:17:47', '2026-10-02 07:17:47'),
(19, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-02 07:44:01', '2026-10-02 07:44:01', '2026-10-02 07:44:01'),
(20, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-02 07:44:01', '2026-10-02 07:44:01', '2026-10-02 07:44:01'),
(21, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-03 12:01:00', '2026-10-03 12:01:00', '2026-10-03 12:01:00'),
(22, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-03 12:01:00', '2026-10-03 12:01:00', '2026-10-03 12:01:00'),
(23, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-03 15:12:29', '2026-10-03 15:12:29', '2026-10-03 15:12:29'),
(24, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-03 15:12:29', '2026-10-03 15:12:29', '2026-10-03 15:12:29'),
(25, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-03 15:22:48', '2026-10-03 15:22:48', '2026-10-03 15:22:48'),
(26, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-03 15:22:48', '2026-10-03 15:22:48', '2026-10-03 15:22:48'),
(27, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 01:33:42', '2026-10-04 01:33:42', '2026-10-04 01:33:42'),
(28, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 01:33:42', '2026-10-04 01:33:42', '2026-10-04 01:33:42'),
(29, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 01:54:09', '2026-10-04 01:54:09', '2026-10-04 01:54:09'),
(30, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 01:54:09', '2026-10-04 01:54:09', '2026-10-04 01:54:09'),
(31, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 02:19:42', '2026-10-04 02:19:42', '2026-10-04 02:19:42'),
(32, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 02:19:42', '2026-10-04 02:19:42', '2026-10-04 02:19:42'),
(33, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 07:37:30', '2026-10-04 07:37:30', '2026-10-04 07:37:30'),
(34, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 07:37:30', '2026-10-04 07:37:30', '2026-10-04 07:37:30'),
(35, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 09:05:52', '2026-10-04 09:05:52', '2026-10-04 09:05:52'),
(36, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 09:05:52', '2026-10-04 09:05:52', '2026-10-04 09:05:52'),
(37, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'curl/8.21.0', 'web', '2026-10-04 10:53:14', '2026-10-04 10:53:14', '2026-10-04 10:53:14'),
(38, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'curl/8.21.0', 'web', '2026-10-04 10:53:14', '2026-10-04 10:53:14', '2026-10-04 10:53:14'),
(39, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'curl/8.21.0', 'web', '2026-10-04 11:20:18', '2026-10-04 11:20:18', '2026-10-04 11:20:18'),
(40, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'curl/8.21.0', 'web', '2026-10-04 11:20:18', '2026-10-04 11:20:18', '2026-10-04 11:20:18'),
(41, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 11:39:46', '2026-10-04 11:39:46', '2026-10-04 11:39:46'),
(42, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 11:39:46', '2026-10-04 11:39:46', '2026-10-04 11:39:46'),
(43, '57621d3c-c299-4cd2-b96a-9b887752cb73', 'Yogaroots.id', 'tapayoga@yogaroots.id', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 12:08:50', '2026-10-04 12:08:50', '2026-10-04 12:08:50'),
(44, '57621d3c-c299-4cd2-b96a-9b887752cb73', 'Yogaroots.id', 'tapayoga@yogaroots.id', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 12:08:50', '2026-10-04 12:08:50', '2026-10-04 12:08:50'),
(45, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 12:26:38', '2026-10-04 12:26:38', '2026-10-04 12:26:38'),
(46, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 12:26:38', '2026-10-04 12:26:38', '2026-10-04 12:26:38'),
(47, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 21:42:47', '2026-10-04 21:42:47', '2026-10-04 21:42:47'),
(48, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 21:42:47', '2026-10-04 21:42:47', '2026-10-04 21:42:47'),
(49, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 21:48:15', '2026-10-04 21:48:15', '2026-10-04 21:48:15'),
(50, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 21:48:15', '2026-10-04 21:48:15', '2026-10-04 21:48:15'),
(51, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 21:49:14', '2026-10-04 21:49:14', '2026-10-04 21:49:14'),
(52, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 21:49:14', '2026-10-04 21:49:14', '2026-10-04 21:49:14'),
(53, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 21:49:24', '2026-10-04 21:49:24', '2026-10-04 21:49:24'),
(54, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-04 21:49:24', '2026-10-04 21:49:24', '2026-10-04 21:49:24'),
(55, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-06 10:06:14', '2026-10-06 10:06:14', '2026-10-06 10:06:14'),
(56, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-06 10:06:14', '2026-10-06 10:06:14', '2026-10-06 10:06:14'),
(57, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-06 10:12:55', '2026-10-06 10:12:55', '2026-10-06 10:12:55'),
(58, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-06 10:12:55', '2026-10-06 10:12:55', '2026-10-06 10:12:55'),
(59, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-06 10:44:03', '2026-10-06 10:44:03', '2026-10-06 10:44:03'),
(60, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-06 10:44:03', '2026-10-06 10:44:03', '2026-10-06 10:44:03'),
(61, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-06 10:44:12', '2026-10-06 10:44:12', '2026-10-06 10:44:12'),
(62, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-06 10:44:12', '2026-10-06 10:44:12', '2026-10-06 10:44:12'),
(63, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-06 11:21:21', '2026-10-06 11:21:21', '2026-10-06 11:21:21'),
(64, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-06 11:21:21', '2026-10-06 11:21:21', '2026-10-06 11:21:21'),
(65, '57621d3c-c299-4cd2-b96a-9b887752cb73', 'Yogaroots.id', 'tapayoga@yogaroots.id', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-06 12:05:47', '2026-10-06 12:05:47', '2026-10-06 12:05:47'),
(66, '57621d3c-c299-4cd2-b96a-9b887752cb73', 'Yogaroots.id', 'tapayoga@yogaroots.id', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'web', '2026-10-06 12:05:47', '2026-10-06 12:05:47', '2026-10-06 12:05:47'),
(67, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', 'web', '2026-11-18 20:49:48', '2026-11-18 20:49:48', '2026-11-18 20:49:48'),
(68, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', 'web', '2026-11-18 20:49:48', '2026-11-18 20:49:48', '2026-11-18 20:49:48'),
(69, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', 'web', '2026-11-18 22:33:18', '2026-11-18 22:33:18', '2026-11-18 22:33:18'),
(70, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.146.0 Chrome/150.0.7871.250 Electron/43.8.6 Safari/537.36', 'web', '2026-11-18 22:33:18', '2026-11-18 22:33:18', '2026-11-18 22:33:18'),
(71, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-18 22:47:20', '2026-11-18 22:47:20', '2026-11-18 22:47:20'),
(72, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-18 22:47:20', '2026-11-18 22:47:20', '2026-11-18 22:47:20'),
(73, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 00:48:54', '2026-11-20 00:48:54', '2026-11-20 00:48:54'),
(74, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 00:48:54', '2026-11-20 00:48:54', '2026-11-20 00:48:54'),
(75, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:03:55', '2026-11-20 01:03:55', '2026-11-20 01:03:55'),
(76, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:03:55', '2026-11-20 01:03:55', '2026-11-20 01:03:55'),
(77, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:04:13', '2026-11-20 01:04:13', '2026-11-20 01:04:13'),
(78, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:04:13', '2026-11-20 01:04:13', '2026-11-20 01:04:13'),
(79, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:26:28', '2026-11-20 01:26:28', '2026-11-20 01:26:28'),
(80, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:26:28', '2026-11-20 01:26:28', '2026-11-20 01:26:28'),
(81, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:26:41', '2026-11-20 01:26:41', '2026-11-20 01:26:41'),
(82, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:26:41', '2026-11-20 01:26:41', '2026-11-20 01:26:41'),
(83, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:28:03', '2026-11-20 01:28:03', '2026-11-20 01:28:03'),
(84, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:28:03', '2026-11-20 01:28:03', '2026-11-20 01:28:03'),
(85, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:28:59', '2026-11-20 01:28:59', '2026-11-20 01:28:59'),
(86, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:28:59', '2026-11-20 01:28:59', '2026-11-20 01:28:59'),
(87, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:36:23', '2026-11-20 01:36:23', '2026-11-20 01:36:23'),
(88, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:36:23', '2026-11-20 01:36:23', '2026-11-20 01:36:23'),
(89, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:40:44', '2026-11-20 01:40:44', '2026-11-20 01:40:44'),
(90, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:40:44', '2026-11-20 01:40:44', '2026-11-20 01:40:44'),
(91, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:43:00', '2026-11-20 01:43:00', '2026-11-20 01:43:00'),
(92, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:43:01', '2026-11-20 01:43:01', '2026-11-20 01:43:01'),
(93, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:51:24', '2026-11-20 01:51:24', '2026-11-20 01:51:24'),
(94, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:51:24', '2026-11-20 01:51:24', '2026-11-20 01:51:24'),
(95, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:54:15', '2026-11-20 01:54:15', '2026-11-20 01:54:15'),
(96, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:54:15', '2026-11-20 01:54:15', '2026-11-20 01:54:15'),
(97, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:59:02', '2026-11-20 01:59:02', '2026-11-20 01:59:02'),
(98, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', 'stella@yogaroots.id', '127.0.0.1', 'Symfony', 'web', '2026-11-20 01:59:02', '2026-11-20 01:59:02', '2026-11-20 01:59:02'),
(99, 'f8dbf191-7c45-4d71-8cfd-1e290747ade8', 'Madu Sarifa Official', 'madusarifaofficial@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 05:52:43', '2026-11-20 05:52:43', '2026-11-20 05:52:43'),
(100, 'f8dbf191-7c45-4d71-8cfd-1e290747ade8', 'Madu Sarifa Official', 'madusarifaofficial@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 05:52:43', '2026-11-20 05:52:43', '2026-11-20 05:52:43'),
(101, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:10:58', '2026-11-20 06:10:58', '2026-11-20 06:10:58'),
(102, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:10:58', '2026-11-20 06:10:58', '2026-11-20 06:10:58'),
(103, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:12:06', '2026-11-20 06:12:06', '2026-11-20 06:12:06'),
(104, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:12:06', '2026-11-20 06:12:06', '2026-11-20 06:12:06'),
(105, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:12:56', '2026-11-20 06:12:56', '2026-11-20 06:12:56'),
(106, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:12:56', '2026-11-20 06:12:56', '2026-11-20 06:12:56'),
(107, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:13:45', '2026-11-20 06:13:45', '2026-11-20 06:13:45'),
(108, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:13:45', '2026-11-20 06:13:45', '2026-11-20 06:13:45'),
(109, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:14:49', '2026-11-20 06:14:49', '2026-11-20 06:14:49'),
(110, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:14:49', '2026-11-20 06:14:49', '2026-11-20 06:14:49'),
(111, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:14:56', '2026-11-20 06:14:56', '2026-11-20 06:14:56'),
(112, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:14:56', '2026-11-20 06:14:56', '2026-11-20 06:14:56'),
(113, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:16:24', '2026-11-20 06:16:24', '2026-11-20 06:16:24'),
(114, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:16:24', '2026-11-20 06:16:24', '2026-11-20 06:16:24'),
(115, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:16:27', '2026-11-20 06:16:27', '2026-11-20 06:16:27'),
(116, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:16:27', '2026-11-20 06:16:27', '2026-11-20 06:16:27'),
(117, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:16:51', '2026-11-20 06:16:51', '2026-11-20 06:16:51'),
(118, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:16:51', '2026-11-20 06:16:51', '2026-11-20 06:16:51'),
(119, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:16:56', '2026-11-20 06:16:56', '2026-11-20 06:16:56'),
(120, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:16:56', '2026-11-20 06:16:56', '2026-11-20 06:16:56'),
(121, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:21:23', '2026-11-20 06:21:23', '2026-11-20 06:21:23'),
(122, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:21:23', '2026-11-20 06:21:23', '2026-11-20 06:21:23'),
(123, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:21:35', '2026-11-20 06:21:35', '2026-11-20 06:21:35'),
(124, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:21:35', '2026-11-20 06:21:35', '2026-11-20 06:21:35'),
(125, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:22:20', '2026-11-20 06:22:20', '2026-11-20 06:22:20'),
(126, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:22:20', '2026-11-20 06:22:20', '2026-11-20 06:22:20'),
(127, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:22:29', '2026-11-20 06:22:29', '2026-11-20 06:22:29'),
(128, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', 'qa.screenshot@example.test', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'web', '2026-11-20 06:22:29', '2026-11-20 06:22:29', '2026-11-20 06:22:29');

-- --------------------------------------------------------

--
-- Table structure for table `login_lockouts`
--

CREATE TABLE `login_lockouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(10) NOT NULL,
  `value` varchar(255) NOT NULL,
  `attempts` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `reason` varchar(255) DEFAULT NULL,
  `blocked_until` timestamp NULL DEFAULT NULL,
  `last_attempt_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_groups`
--

CREATE TABLE `menu_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `permission_name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `position` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_groups`
--

INSERT INTO `menu_groups` (`id`, `name`, `status`, `permission_name`, `icon`, `position`, `created_at`, `updated_at`) VALUES
(2, 'Role dan Akses', 1, 'menu.role-permission', 'bx-shield-quarter', 11, '2024-09-26 23:27:05', '2024-09-26 23:56:50'),
(3, 'Settings', 1, 'menu-item.index', 'bx-cog', 12, '2024-09-26 23:27:05', '2026-09-06 19:40:05'),
(4, 'Orders', 1, 'orders.index', 'bx-receipt', 3, '2024-09-26 23:35:30', '2026-09-13 14:27:12'),
(7, 'Publikasi', 1, 'articles.index', 'bxs-file', 9, '2024-09-29 12:37:06', '2026-11-20 04:14:09'),
(8, 'Portfolio & Galeri', 1, 'banner.index', 'bx-camera', 9, '2024-10-13 22:33:06', '2026-11-20 04:54:02'),
(9, 'Master Data', 1, 'testimonial.index', 'bx-folder-open', 10, '2025-07-21 17:56:19', '2026-08-30 19:07:27'),
(12, 'Packages', 1, 'package.member', 'bx-package', 1, '2025-09-14 12:05:35', '2026-09-07 20:00:15'),
(22, 'Classes', 1, 'class-schedules.index', 'bx-book', 2, '2026-09-02 15:16:09', '2026-09-06 03:39:03'),
(23, 'Vendor & Mitra', 1, 'venues.index', 'bx-camera-home', 6, '2026-09-08 06:44:24', '2026-11-20 03:14:58'),
(24, 'Security', 1, 'menu.keamanan', 'bx-shield-alt-2', 13, '2026-10-02 00:30:47', '2026-10-03 12:02:43'),
(26, 'Memberships', 1, 'memberships.index', 'bx-id-card', 4, '2026-10-04 02:13:11', '2026-10-04 02:23:55'),
(27, 'Memberships', 1, 'menu.memberships', 'bx-id-card', 55, '2026-10-04 02:31:55', '2026-10-04 02:31:55'),
(28, 'Data Prospek', 1, 'konsultasi.index', 'bx-message-detail', 8, '2026-11-20 04:55:03', '2026-11-20 06:00:30');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `route` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `permission_name` varchar(255) NOT NULL,
  `menu_group_id` bigint(20) UNSIGNED NOT NULL,
  `position` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `icon`, `route`, `status`, `permission_name`, `menu_group_id`, `position`, `created_at`, `updated_at`) VALUES
(1, 'Informasi Umum', NULL, 'company.index', 1, 'company.index', 1, 1, '2024-09-26 23:27:05', '2024-09-27 01:56:11'),
(2, 'Module Role', NULL, 'permission.index', 1, 'permission.index', 2, 1, '2024-09-26 23:27:05', '2024-09-26 23:58:37'),
(3, 'Master Role', NULL, 'role.index', 1, 'role.index', 2, 2, '2024-09-26 23:27:05', '2024-09-26 23:58:15'),
(4, 'Daftar Pengguna', NULL, 'user.index', 1, 'user.index', 3, 1, '2024-09-26 23:27:05', '2024-09-26 23:39:25'),
(5, 'Module Aplikasi', NULL, 'route.index', 1, 'route.index', 3, 2, '2024-09-26 23:27:05', '2024-09-26 23:44:54'),
(6, 'Menu Manager', NULL, 'menu.index', 1, 'menu-group.index', 3, 3, '2024-09-26 23:27:05', '2024-09-26 23:44:09'),
(8, 'Kategori Artikel', NULL, 'categories.index', 1, 'categories.index', 7, 3, '2024-09-29 12:39:24', '2026-11-20 04:14:44'),
(9, 'Artikel', NULL, 'articles.index', 1, 'articles.index', 7, 1, '2024-09-30 12:08:38', '2026-11-20 04:14:35'),
(10, 'Galeri Foto', NULL, 'banner.index', 1, 'banner.index', 8, 1, '2024-10-13 22:38:24', '2026-11-20 05:40:17'),
(12, 'Acara', NULL, 'events.index', 1, 'events.index', 7, 2, '2025-07-22 12:59:27', '2026-11-20 04:54:37'),
(14, 'Semua Acara', NULL, 'events.index', 1, 'events.index', 11, 1, '2025-07-25 16:55:36', '2026-09-03 05:05:58'),
(40, 'Halaman Statis', NULL, 'pages.index', 1, 'pages.index', 9, 3, '2025-10-21 03:46:02', '2026-11-20 02:34:01'),
(41, 'Daftar Vendor', NULL, 'vendor.index', 1, 'vendor.index', 10, 2, '2025-11-12 04:02:59', '2026-10-03 15:14:04'),
(44, 'Packages', NULL, 'packages.index', 1, 'package.index', 12, 1, '2026-09-02 04:59:49', '2026-09-02 04:59:49'),
(45, 'Browse Packages', NULL, 'packages.member', 1, 'package.member', 12, 2, '2026-09-02 05:52:23', '2026-09-07 20:00:31'),
(46, 'Class List', NULL, 'classes.index', 1, 'classes.index', 22, 1, '2026-09-02 15:18:03', '2026-09-02 15:18:03'),
(47, 'Schedule', NULL, 'class-schedules.index', 1, 'class-schedules.index', 22, 2, '2026-09-06 03:39:53', '2026-09-06 03:39:53'),
(50, 'Testimoni', NULL, 'testimonial.index', 1, 'testimonial.index', 9, 4, '2026-09-07 23:00:45', '2026-11-20 02:34:08'),
(51, 'Faq & Answer', NULL, 'faq.index', 1, 'faq.index', 9, 5, '2026-09-07 23:04:55', '2026-09-07 23:04:55'),
(52, 'Data Venue', NULL, 'venues.index', 1, 'venues.index', 23, 1, '2026-09-08 07:14:52', '2026-11-20 03:15:33'),
(53, 'Data Vendor', NULL, 'vendor.index', 1, 'vendor.index', 23, 2, '2026-09-08 07:15:14', '2026-11-20 03:40:22'),
(54, 'Order List', NULL, 'orders.index', 1, 'banner.index', 4, 1, '2026-09-13 14:23:49', '2026-09-13 14:23:49'),
(55, 'My Orders', NULL, 'orders.index', 1, 'filedownload.index', 4, 2, '2026-09-13 14:27:33', '2026-09-13 14:27:33'),
(56, 'Login Activity', NULL, 'security.login-activity.index', 1, 'login-activity.index', 24, 1, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(57, 'Audit Log', NULL, 'security.audit-log.index', 1, 'audit-log.index', 24, 2, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(58, 'Failed Login', NULL, 'security.failed-login.index', 1, 'failed-login.index', 24, 3, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(59, 'Blokir Login', NULL, 'security.login-lockout.index', 1, 'login-lockout.index', 24, 4, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(60, 'Website Identity', NULL, 'website-identity.index', 1, 'website-identity.index', 3, 99, '2026-10-03 12:18:10', '2026-10-03 12:24:24'),
(61, 'All Members', NULL, 'memberships.index', 1, 'memberships.index', 26, 1, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(62, 'Bookings', NULL, 'class-bookings.index', 1, 'class-bookings.index', 26, 2, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(63, 'All Members', NULL, 'memberships.index', 1, 'memberships.index', 27, 1, '2026-10-04 02:31:55', '2026-10-04 02:31:55'),
(64, 'Bookings', NULL, 'class-bookings.index', 1, 'class-bookings.index', 27, 2, '2026-10-04 02:31:55', '2026-10-04 02:31:55'),
(65, 'Kategori Vendor', NULL, 'vendors_categories.index', 1, 'vendors_categories.index', 23, 3, '2026-11-20 03:17:09', '2026-11-20 03:17:09'),
(66, 'Arsip Vendor', 'bx-archive', 'vendor.archive', 1, 'vendor.index', 10, 3, '2026-11-20 04:08:23', '2026-11-20 04:08:23'),
(67, 'Konsultasi', NULL, 'konsultasi.index', 1, 'konsultasi.index', 28, 1, '2026-11-20 05:27:33', '2026-11-20 05:30:34'),
(68, 'Calon Pelanggan', NULL, 'pengguna.index', 1, 'pengguna.index', 28, 2, '2026-11-20 05:30:17', '2026-11-20 05:30:17');

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
(4, '2014_10_12_100000_create_password_resets_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2024_07_25_034354_create_permission_tables', 1),
(7, '2024_07_25_045022_create_routes_table', 1),
(8, '2024_07_26_043548_create_menu_groups_table', 1),
(9, '2024_07_31_012301_create_banners_table', 1),
(10, '2024_07_31_033121_create_kontaks_table', 1),
(11, '2024_08_26_084211_create_menu_items_table', 1),
(12, '2024_10_15_032323_create_categories_table', 1),
(13, '2024_10_16_032117_create_articles_table', 1),
(14, '2024_10_16_032437_create_seo_table', 1),
(15, '2025_07_21_125740_add_google_id_column', 1),
(16, '2025_07_22_061051_create_ebooks_table', 1),
(17, '2025_07_24_021857_create_kecamatans_table', 1),
(18, '2025_07_24_021932_create_kelurahans_table', 1),
(19, '2025_07_24_071157_add_profile_fields_to_users_table', 1),
(20, '2025_07_25_120837_create_file_downloads_table', 1),
(21, '2025_07_30_070942_create_programs_table', 1),
(22, '2025_08_06_040605_create_pages_table', 1),
(23, '2025_08_08_023001_change_model_id_to_uuid_in_seo_table', 1),
(24, '2025_08_15_025639_add_tagging_status_searchengine_to_articles_table', 1),
(25, '2025_09_16_021217_create_notifications_table', 1),
(26, '2025_09_16_023931_alter_notifications_table_for_uuid', 1),
(27, '2025_09_16_061424_create_faqs_table', 1),
(28, '2025_09_16_061553_create_testimonials_table', 1),
(29, '2025_09_16_063145_create_polls_table', 1),
(30, '2025_09_16_074843_create_poll_votes_table', 1),
(31, '2025_09_22_030137_add_deleted_at_to_users_table', 2),
(32, '2025_10_03_015100_add_socials_and_biografi_to_users_table', 3),
(33, '2024_10_16_012301_create_banners_table', 4),
(34, '2025_10_20_120827_create_services_table', 5),
(35, '2025_10_19_120827_create_services_table', 6),
(36, '2025_10_21_120827_create_services_table', 7),
(37, '2025_11_11_033008_create_disabilities_table', 8),
(38, '2025_11_11_070942_create_programs_table', 9),
(39, '2026_08_28_033008_create_specializations_table', 10),
(40, '2026_08_31_120827_create_events_table', 11),
(41, '2026_08_31_125113_create_user_specialization_table', 12),
(42, '2026_09_02_073504_create_packages_table', 13),
(43, '2026_09_02_073857_create_package_features_table', 13),
(44, '2026_09_02_073943_create_classes_table', 13),
(45, '2026_09_02_074219_create_class_schedules_table', 13),
(46, '2026_09_02_074643_add_membership_fields_to_users_table', 13),
(47, '2026_09_13_074803_create_orders_table', 13),
(48, '2026_09_02_074832_create_payments_table', 13),
(49, '2026_09_02_074849_create_class_bookings_table', 13),
(50, '2026_09_06_222620_create_user_packages_table', 14),
(51, '2026_09_08_131616_create_user_studios_table', 15),
(52, '2026_09_08_131617_create_studios_table', 16),
(54, '2026_09_12_195536_create_package_options_table', 18),
(55, '2026_10_01_000001_create_login_activities_table', 19),
(56, '2026_10_01_000002_create_failed_logins_table', 20),
(57, '2026_10_01_000003_create_audit_logs_table', 21),
(58, '2026_10_01_000004_create_login_lockouts_table', 22),
(59, '2026_10_04_000000_create_website_identities_table', 23),
(60, '2026_10_04_000001_rebuild_website_identities_for_dbmsda', 24),
(61, '2026_10_04_000002_add_tipe_album_to_banner_table', 25),
(62, '2026_10_04_000003_dbmsda_media_library', 26),
(63, '2026_10_04_100000_add_admin_note_to_orders_table', 27),
(64, '2026_10_04_110000_add_booking_date_to_class_bookings_table', 28),
(65, '2026_10_04_120000_add_proof_to_orders_table', 29),
(66, '2026_10_05_045507_fix_class_bookings_enums', 30),
(67, '2026_10_06_000000_add_rating_to_class_bookings_table', 31),
(68, '2026_10_07_000000_add_reminders_to_class_bookings_table', 31),
(69, '2026_10_07_000001_fix_testimonials_is_active', 32),
(70, '2026_10_07_000002_add_orders_fk_to_payments_and_packages', 32),
(71, '2026_10_07_000003_sync_class_tables_schema', 33),
(72, '2026_10_07_000004_make_users_avatar_nullable', 33),
(73, '2026_11_19_000001_create_site_visits_table', 34),
(74, '2026_11_19_064303_rename_studio_module_to_venues', 35),
(75, '2026_11_19_070000_rename_specialization_module_to_vendors_categories', 36),
(76, '2026_11_20_000000_add_kategori_to_faqs_table', 37),
(77, '2026_11_20_000001_replace_legacy_faqs_with_wedding_faqs', 38),
(78, '2026_11_20_000002_normalize_faqs_to_english', 39),
(79, '2026_11_20_000003_rename_instruktur_module_to_vendor', 40),
(80, '2026_11_20_000004_add_vendor_archive', 41),
(81, '2026_11_20_000005_translate_article_menu_labels', 42),
(82, '2026_11_20_000005_translate_events_menu_labels', 42),
(83, '2026_11_20_000006_translate_banner_menu_labels', 43),
(84, '2026_11_20_112128_translate_categories_menu_label_to_indonesian', 43),
(85, '2026_11_20_000007_translate_remaining_event_menu_label', 44),
(86, '2026_11_20_000008_create_contact_inquiries_table', 45),
(87, '2026_11_20_000009_rename_kontak_module_to_konsultasi', 46),
(88, '2026_11_20_000010_add_prospect_status_to_users_table', 47);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', '3099cb1c-6c37-4e5a-bbb5-d11615a1b0ff'),
(1, 'App\\Models\\User', '5e21baa9-0ca0-4c94-9939-85068e363f57'),
(1, 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2'),
(2, 'App\\Models\\User', '0dd616be-4e66-463c-9b42-6529b1c7cb4f'),
(2, 'App\\Models\\User', '130214ce-3612-455f-ae39-f6f2a5c2b90d'),
(2, 'App\\Models\\User', '370a8be5-8531-453a-a504-2a610ed8538f'),
(2, 'App\\Models\\User', '3e354d68-8b35-410c-9fd1-827ec786b007'),
(2, 'App\\Models\\User', '6bdf7417-66c8-407c-828b-77577006dda2'),
(2, 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5'),
(2, 'App\\Models\\User', '95271004-d8a8-41a4-93eb-62a766c2758d'),
(2, 'App\\Models\\User', 'a82db5a8-5d69-499b-a9a1-3d98ad0c47e4'),
(2, 'App\\Models\\User', 'b5a9eb64-a547-4464-a576-e261ede0d4db'),
(2, 'App\\Models\\User', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4'),
(2, 'App\\Models\\User', 'c4536499-9979-4be5-a0ad-775fe9bec19d'),
(2, 'App\\Models\\User', 'd56cc48a-b1fa-4c03-9b72-c8f92766efb3'),
(2, 'App\\Models\\User', 'd91ccba3-e95b-4692-8f8c-63d543549331'),
(2, 'App\\Models\\User', 'dfd391fa-c167-4547-ad17-27d52d9ffc28'),
(2, 'App\\Models\\User', 'e5a05967-00cb-49f6-97bf-95c1bccee6e0'),
(2, 'App\\Models\\User', 'f8dbf191-7c45-4d71-8cfd-1e290747ade8'),
(3, 'App\\Models\\User', '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3'),
(3, 'App\\Models\\User', '44af87c7-cd3f-4f9d-af63-177da19727fb'),
(3, 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73'),
(3, 'App\\Models\\User', '57f63489-134b-498c-ad05-78a3652cb916'),
(3, 'App\\Models\\User', 'e2c23ff6-eda1-4c5c-86a4-d2d1782ed930'),
(3, 'App\\Models\\User', 'fa921602-18c9-4e1b-a070-ea705ecfc488'),
(5, 'App\\Models\\User', '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3'),
(5, 'App\\Models\\User', '1c3ea59a-f83a-4bdf-aa70-f38ff66f49a2'),
(5, 'App\\Models\\User', '2f040883-ec48-424e-872d-3691d7c1a714'),
(5, 'App\\Models\\User', '33b681e2-efc3-4017-9415-e5d5d197f2fe'),
(5, 'App\\Models\\User', '4a85c36a-caa6-4cc9-9ab6-47f77c0a9a67'),
(5, 'App\\Models\\User', '4c0ff420-34e5-4ecd-bb07-358862f9906a'),
(5, 'App\\Models\\User', '514d04e5-f79b-403a-95f4-2254784db0f0'),
(5, 'App\\Models\\User', '537f352e-a1e3-4daa-86b2-fc6b3366881c'),
(5, 'App\\Models\\User', '56f0bbe7-c52e-4d97-833f-17fba920421f'),
(5, 'App\\Models\\User', '755e0eaa-50e4-46db-9003-7c1fc6674876'),
(5, 'App\\Models\\User', '7d84490a-7661-415a-921c-141c05f38ded'),
(5, 'App\\Models\\User', '896ff4a7-ec81-4be5-ad12-ba4e3bb277de'),
(5, 'App\\Models\\User', '897f6253-86e1-4866-9072-e3e10bb51e5e'),
(5, 'App\\Models\\User', '950e18b7-9af4-468a-8468-edc338b7deec'),
(5, 'App\\Models\\User', '95294c43-b3d8-4b46-b938-e4eea1f3a359'),
(5, 'App\\Models\\User', 'a52ef0ee-6805-4b04-9f23-d6d6410829c5'),
(5, 'App\\Models\\User', 'b497082d-dd9f-4d1c-a611-9e1331eb5393'),
(5, 'App\\Models\\User', 'b55eb675-019c-4fa5-936e-579e2835527d'),
(5, 'App\\Models\\User', 'bace7196-8ce8-4253-baa7-04901e31592e'),
(5, 'App\\Models\\User', 'beaaf326-874c-48c2-b6b8-6bbe655c4df2'),
(5, 'App\\Models\\User', 'd405298f-5481-4038-8474-2ec0283b7608'),
(5, 'App\\Models\\User', 'dc25dea7-9969-46eb-b1ac-fd7f1b8dab77'),
(5, 'App\\Models\\User', 'dffd1060-8999-4d7b-8623-74caf47ab546'),
(5, 'App\\Models\\User', 'f7010485-3236-4341-a22a-48b70260a607'),
(5, 'App\\Models\\User', 'fab7c79d-a7dc-421f-b343-aaf81bdd20da');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('170abde0-6d7b-48a2-b78f-8cc232e48461', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"New order received\",\"message\":\"ORD-BNEJIR8QFB \\u2014 Wiku Pramesthi Bagaswara ordered First Timer Package \\u2014 First Class Experience (Rp 210.000)\",\"path\":\"\\/backend\\/orders\\/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"icon\":\"bi-receipt\"}', '2026-10-06 14:25:26', '2026-10-06 10:12:11', '2026-10-06 14:25:26'),
('1ef127f7-4089-43aa-a1f2-c4f2688a8280', 'App\\Notifications\\ProofUploadedNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Transfer proof uploaded\",\"message\":\"ORD-I5HKUWFTWJ \\u2014 ESPRO Property uploaded transfer proof (Rp 210.000). Please verify.\",\"path\":\"\\/backend\\/orders\\/01a1049e-afda-7188-b2f9-9fa3b404f6d3\",\"icon\":\"bi-file-earmark-check\"}', '2026-10-04 02:49:21', '2026-10-04 02:48:56', '2026-10-04 02:49:21'),
('288c5468-e17d-4cc4-b7c3-5f022e4a7e96', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Unusual login detected\",\"message\":\"super-admin (super@admin.com) signed in from 127.0.0.1 \\u2014 unusual hour\",\"path\":\"\\/backend\\/security\\/login-activity\",\"icon\":\"bi-shield-exclamation\"}', NULL, '2026-11-18 20:49:48', '2026-11-18 20:49:48'),
('30266322-5b40-4c84-8ee6-4b689ed46102', 'App\\Notifications\\MemberCheckedInNotification', 'App\\Models\\User', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', '{\"judul_kegiatan\":\"Checked in successfully\",\"message\":\"Admin checked you in to Yin Yoga (Tuesday 19:00). Remaining quota: 48.\",\"path\":\"\\/backend\\/class-bookings\",\"icon\":\"bi-check2-circle\"}', NULL, '2026-10-06 11:44:43', '2026-10-06 11:44:43'),
('3042b54f-0eed-4e61-ad42-2def83dd9b3c', 'App\\Notifications\\OrderExpiredNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Order expired\",\"message\":\"ORD-FG2TCGJ55W was automatically cancelled after 24 hours without payment. Please place a new order if you still want the membership.\",\"path\":\"\\/backend\\/packages\\/members\",\"icon\":\"bi-hourglass-bottom\"}', '2026-10-06 14:07:14', '2026-10-04 02:48:58', '2026-10-06 14:07:14'),
('32e1f1c5-9072-4825-83cd-1018cb73d62f', 'App\\Notifications\\ProgramStatusChanged', 'App\\Models\\User', 'b5a9eb64-a547-4464-a576-e261ede0d4db', '{\"program_id\":null,\"judul_kegiatan\":null,\"old_status\":\"pending\",\"new_status\":\"hadir\",\"message\":\"Status program \'\' berubah dari \'pending\' menjadi \'hadir\'.\"}', '2026-06-17 19:03:03', '2026-06-17 19:02:26', '2026-06-17 19:03:03'),
('3a0644a0-403a-407c-ba4c-74b6c363f693', 'App\\Notifications\\MemberActiveNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Membership activated\",\"message\":\"Payment for ORD-BNEJIR8QFB was verified. Your membership is now active. Active until 13 Oct 2026.\",\"path\":\"\\/backend\\/orders\\/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"icon\":\"bi-check-circle\"}', '2026-10-06 14:07:14', '2026-10-06 10:13:14', '2026-10-06 14:07:14'),
('4ee83ebe-638e-45cb-aa71-02cf87721984', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"New order received\",\"message\":\"ORD-DHVCIG8ZQL \\u2014 Wiku Pramesthi Bagaswara ordered First Timer Package \\u2014 First Class Experience (Rp 210.000)\",\"path\":\"\\/backend\\/orders\\/01a11182-8463-7057-a0f4-8cf88279c6c2\",\"icon\":\"bi-receipt\"}', NULL, '2026-10-06 13:58:48', '2026-10-06 13:58:48'),
('54fbcbf6-8ebc-4f5e-a62f-7b184c3a8c55', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"New order received\",\"message\":\"ORD-BNEJIR8QFB \\u2014 Wiku Pramesthi Bagaswara ordered First Timer Package \\u2014 First Class Experience (Rp 210.000)\",\"path\":\"\\/backend\\/orders\\/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"icon\":\"bi-receipt\"}', NULL, '2026-10-06 10:12:11', '2026-10-06 10:12:11'),
('5e0c56a7-c2b0-4fe2-9a1b-dbe081f039d2', 'App\\Notifications\\OrderExpiredNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Order expired\",\"message\":\"ORD-QCQL0MKBRP was automatically cancelled after 24 hours without payment. Please place a new order if you still want the membership.\",\"path\":\"\\/backend\\/packages\\/members\",\"icon\":\"bi-hourglass-bottom\"}', '2026-10-06 14:07:14', '2026-10-04 02:48:58', '2026-10-06 14:07:14'),
('62c848cd-a1d4-46ff-b0fe-d6593f138caa', 'App\\Notifications\\ProofUploadedNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Transfer proof uploaded\",\"message\":\"ORD-BNEJIR8QFB \\u2014 Wiku Pramesthi Bagaswara uploaded transfer proof (Rp 210.000). Please verify.\",\"path\":\"\\/backend\\/orders\\/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"icon\":\"bi-file-earmark-check\"}', '2026-10-06 14:25:26', '2026-10-06 10:12:24', '2026-10-06 14:25:26'),
('6f95fb80-2e8d-441d-850a-741dcdc7d1bc', 'App\\Notifications\\ProgramStatusChanged', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"program_id\":null,\"judul_kegiatan\":null,\"old_status\":\"pending\",\"new_status\":\"hadir\",\"message\":\"Status program \'\' berubah dari \'pending\' menjadi \'hadir\'.\"}', '2026-09-02 06:50:51', '2026-05-24 21:21:26', '2026-09-02 06:50:51'),
('7040f077-8fe0-4701-b733-3e66a7bca72c', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Unusual login detected\",\"message\":\"Wiku Pramesthi Bagaswara (wikupb@gmail.com) signed in from 127.0.0.1 \\u2014 unusual hour\",\"path\":\"\\/backend\\/security\\/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-04 21:51:30', '2026-10-04 21:49:14', '2026-10-04 21:51:30'),
('7630f926-083f-4c4a-a189-ff681e4ce6b9', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Unusual login detected\",\"message\":\"ESPRO Property (esproproperty.bekasi@gmail.com) signed in from 127.0.0.1 \\u2014 unusual hour\",\"path\":\"\\/backend\\/security\\/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-04 21:51:30', '2026-10-04 21:42:47', '2026-10-04 21:51:30'),
('89cd6964-dea1-44b8-9c8c-ff7886de4786', 'App\\Notifications\\OrderExpiredNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Order expired\",\"message\":\"ORD-9UJ1QLPEAW was automatically cancelled after 24 hours without payment. Please place a new order if you still want the membership.\",\"path\":\"\\/backend\\/packages\\/members\",\"icon\":\"bi-hourglass-bottom\"}', '2026-10-06 14:07:14', '2026-10-04 02:48:58', '2026-10-06 14:07:14'),
('a2e5d335-3192-4c81-851a-c8b6ea33dabf', 'App\\Notifications\\MemberActiveNotification', 'App\\Models\\User', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', '{\"judul_kegiatan\":\"Membership activated\",\"message\":\"Payment for ORD-I5HKUWFTWJ was verified. Your membership is now active. Active until 11 Oct 2026.\",\"path\":\"\\/backend\\/orders\\/01a1049e-afda-7188-b2f9-9fa3b404f6d3\",\"icon\":\"bi-check-circle\"}', '2026-10-04 10:01:41', '2026-10-04 02:55:55', '2026-10-04 10:01:41'),
('a480fee2-6ed8-4579-88f0-fc1f2968b895', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"New order received\",\"message\":\"ORD-DHVCIG8ZQL \\u2014 Wiku Pramesthi Bagaswara ordered First Timer Package \\u2014 First Class Experience (Rp 210.000)\",\"path\":\"\\/backend\\/orders\\/01a11182-8463-7057-a0f4-8cf88279c6c2\",\"icon\":\"bi-receipt\"}', '2026-10-06 14:25:26', '2026-10-06 13:58:48', '2026-10-06 14:25:26'),
('a4cbf43e-868b-4970-9d5d-55f2f5e8df60', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"New order received\",\"message\":\"ORD-E5WX8OSWPO \\u2014 Wiku Pramesthi Bagaswara ordered First Timer Package \\u2014 First Class Experience (Rp 210.000)\",\"path\":\"\\/backend\\/orders\\/01a110cc-d5ec-7387-a3c5-87cde025d404\",\"icon\":\"bi-receipt\"}', NULL, '2026-10-06 10:40:22', '2026-10-06 10:40:22'),
('ac5b2d37-85ab-4267-b572-70b998039556', 'App\\Notifications\\ProgramStatusChanged', 'App\\Models\\User', 'dfd391fa-c167-4547-ad17-27d52d9ffc28', '{\"program_id\":null,\"judul_kegiatan\":null,\"old_status\":\"pending\",\"new_status\":\"hadir\",\"message\":\"Status program \'\' berubah dari \'pending\' menjadi \'hadir\'.\"}', '2026-05-24 21:51:12', '2026-05-24 21:48:19', '2026-05-24 21:51:12'),
('b0274b03-ffaa-462e-9098-423fe6c82b13', 'App\\Notifications\\OrderExpiredNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Order expired\",\"message\":\"ORD-G07KVIJM1Y was automatically cancelled after 24 hours without payment. Please place a new order if you still want the membership.\",\"path\":\"\\/backend\\/packages\\/members\",\"icon\":\"bi-hourglass-bottom\"}', '2026-10-06 14:07:14', '2026-10-04 02:48:58', '2026-10-06 14:07:14'),
('b041d44e-604a-472c-9899-f54f15ee3eff', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Unusual login detected\",\"message\":\"Wiku Pramesthi Bagaswara (wikupb@gmail.com) signed in from 127.0.0.1 \\u2014 unusual hour\",\"path\":\"\\/backend\\/security\\/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-06 14:25:26', '2026-10-04 21:49:14', '2026-10-06 14:25:26'),
('b4638c57-0813-4336-9c41-433770e4a340', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"New order received\",\"message\":\"ORD-E5WX8OSWPO \\u2014 Wiku Pramesthi Bagaswara ordered First Timer Package \\u2014 First Class Experience (Rp 210.000)\",\"path\":\"\\/backend\\/orders\\/01a110cc-d5ec-7387-a3c5-87cde025d404\",\"icon\":\"bi-receipt\"}', '2026-10-06 14:25:26', '2026-10-06 10:40:22', '2026-10-06 14:25:26'),
('c0706708-ee37-4619-85c7-e8e3d71918e0', 'App\\Notifications\\OrderExpiredNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Order expired\",\"message\":\"ORD-C4LCIYGBRX was automatically cancelled after 24 hours without payment. Please place a new order if you still want the membership.\",\"path\":\"\\/backend\\/packages\\/members\",\"icon\":\"bi-hourglass-bottom\"}', '2026-10-06 14:07:14', '2026-10-04 02:48:58', '2026-10-06 14:07:14'),
('c10a4720-561e-421e-acec-ff4bc5e00b21', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Unusual login detected\",\"message\":\"Wiku Pramesthi Bagaswara (wikupb@gmail.com) signed in from 127.0.0.1 \\u2014 unusual hour\",\"path\":\"\\/backend\\/security\\/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-04 21:51:30', '2026-10-04 21:49:14', '2026-10-04 21:51:30'),
('c964ebfc-7e12-407e-8e10-52ad87801ba1', 'App\\Notifications\\ProofUploadedNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Transfer proof uploaded\",\"message\":\"ORD-BNEJIR8QFB \\u2014 Wiku Pramesthi Bagaswara uploaded transfer proof (Rp 210.000). Please verify.\",\"path\":\"\\/backend\\/orders\\/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"icon\":\"bi-file-earmark-check\"}', NULL, '2026-10-06 10:12:24', '2026-10-06 10:12:24'),
('d93aa418-cae7-47c0-8af5-479119c52593', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Unusual login detected\",\"message\":\"ESPRO Property (esproproperty.bekasi@gmail.com) signed in from 127.0.0.1 \\u2014 unusual hour\",\"path\":\"\\/backend\\/security\\/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-04 21:51:30', '2026-10-04 21:42:47', '2026-10-04 21:51:30'),
('e53d8181-76ed-4616-9627-e150cee6c956', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Unusual login detected\",\"message\":\"ESPRO Property (esproproperty.bekasi@gmail.com) signed in from 127.0.0.1 \\u2014 unusual hour\",\"path\":\"\\/backend\\/security\\/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-06 14:25:26', '2026-10-04 21:42:47', '2026-10-06 14:25:26'),
('e81dc652-ce3a-4b7e-a695-27b508d02e19', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Unusual login detected\",\"message\":\"ESPRO Property (esproproperty.bekasi@gmail.com) signed in from 127.0.0.1 \\u2014 unusual hour\",\"path\":\"\\/backend\\/security\\/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-06 14:25:26', '2026-10-04 21:42:47', '2026-10-06 14:25:26'),
('e86f6aba-11c6-44bc-ab7c-a47af541ccdb', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Unusual login detected\",\"message\":\"Wiku Pramesthi Bagaswara (wikupb@gmail.com) signed in from 127.0.0.1 \\u2014 unusual hour\",\"path\":\"\\/backend\\/security\\/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-06 14:25:26', '2026-10-04 21:49:14', '2026-10-06 14:25:26'),
('ea6cf512-c215-4936-93bf-1e7f744796fc', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Unusual login detected\",\"message\":\"super-admin (super@admin.com) signed in from 127.0.0.1 \\u2014 unusual hour\",\"path\":\"\\/backend\\/security\\/login-activity\",\"icon\":\"bi-shield-exclamation\"}', NULL, '2026-11-18 20:49:48', '2026-11-18 20:49:48'),
('f071f5b7-d4aa-44ba-9449-cbd36724f7c8', 'App\\Notifications\\ProgramStatusChanged', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"program_id\":null,\"judul_kegiatan\":null,\"old_status\":\"pending\",\"new_status\":\"hadir\",\"message\":\"Status program \'\' berubah dari \'pending\' menjadi \'hadir\'.\"}', '2026-05-25 20:36:48', '2026-05-24 21:41:40', '2026-05-25 20:36:48'),
('f0a45da5-5b08-453c-bbda-f036f84dfe44', 'App\\Notifications\\MemberCheckedInNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Checked in successfully\",\"message\":\"You checked in to Yin Yoga (Tuesday 19:00). Remaining quota: 49.\",\"path\":\"\\/backend\\/class-bookings\",\"icon\":\"bi-check2-circle\"}', '2026-10-06 10:29:05', '2026-10-06 10:13:48', '2026-10-06 10:29:05'),
('fbcf4c2e-ea84-4629-be0d-47925e4fc0ca', 'App\\Notifications\\MemberCheckedInNotification', 'App\\Models\\User', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', '{\"judul_kegiatan\":\"Checked in successfully\",\"message\":\"Admin checked you in to Yin Yoga (Monday 14:00). Remaining quota: 49.\",\"path\":\"\\/backend\\/class-bookings\",\"icon\":\"bi-check2-circle\"}', NULL, '2026-10-04 21:59:26', '2026-10-04 21:59:26');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `uuid` char(36) NOT NULL,
  `user_uuid` char(36) NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `type` enum('package','class') NOT NULL,
  `package_uuid` char(36) DEFAULT NULL,
  `package_option_uuid` char(36) DEFAULT NULL,
  `class_schedule_uuid` char(36) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('pending','paid','failed','expired','cancelled') NOT NULL DEFAULT 'pending',
  `expired_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `proof_image_path` varchar(255) DEFAULT NULL,
  `proof_uploaded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`uuid`, `user_uuid`, `order_number`, `type`, `package_uuid`, `package_option_uuid`, `class_schedule_uuid`, `amount`, `status`, `expired_at`, `paid_at`, `admin_note`, `proof_image_path`, `proof_uploaded_at`, `created_at`, `updated_at`) VALUES
('01a09b0a-4dd3-7318-9203-f08140b94b97', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-G07KVIJM1Y', 'package', '01a06234-aac1-722c-84d6-c46d4150e70f', '01a09966-af8f-73fd-94d6-680b288dd1ba', NULL, 878000.00, 'expired', '2026-09-14 13:52:18', NULL, NULL, NULL, NULL, '2026-09-13 13:52:18', '2026-10-04 02:48:58'),
('01a09b11-520c-7314-bc73-86d595a54f57', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-FG2TCGJ55W', 'package', '01a06234-aac1-722c-84d6-c46d4150e70f', '01a09966-af8d-7252-bbf0-f1665358611d', NULL, 230000.00, 'expired', '2026-09-14 13:59:58', NULL, NULL, NULL, NULL, '2026-09-13 13:59:58', '2026-10-04 02:48:58'),
('01a09b16-5931-73fb-866d-5e723294a477', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-C4LCIYGBRX', 'package', '01a06234-aac1-722c-84d6-c46d4150e70f', '01a09966-af8d-7252-bbf0-f1665358611d', NULL, 230000.00, 'expired', '2026-09-14 14:05:28', NULL, NULL, NULL, NULL, '2026-09-13 14:05:28', '2026-10-04 02:48:58'),
('01a09b1e-7b00-7391-bb4a-a50a77fde0e4', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-9UJ1QLPEAW', 'package', '01a06234-aac1-722c-84d6-c46d4150e70f', '01a09966-af8d-7252-bbf0-f1665358611d', NULL, 230000.00, 'expired', '2026-09-14 14:14:21', NULL, NULL, NULL, NULL, '2026-09-13 14:14:21', '2026-10-04 02:48:58'),
('01a09b1e-c44e-70ae-98b1-d19e19c7b4fe', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-QCQL0MKBRP', 'package', '01a06234-aac1-722c-84d6-c46d4150e70f', '01a09966-af8d-7252-bbf0-f1665358611d', NULL, 230000.00, 'expired', '2026-09-14 14:14:39', NULL, NULL, NULL, NULL, '2026-09-13 14:14:39', '2026-10-04 02:48:58'),
('01a1049e-afda-7188-b2f9-9fa3b404f6d3', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ORD-I5HKUWFTWJ', 'package', '01a095f2-d286-7241-b566-8027529b8196', '01a09966-2965-7201-ab57-9f085e23bbc7', NULL, 210000.00, 'paid', '2026-10-05 01:54:31', '2026-10-04 02:55:55', NULL, 'proofs/lTa9j1FbqtOaGW4LDEw95vVjQ9JDz24MwX2UnFMi.png', '2026-10-04 02:48:54', '2026-10-04 01:54:31', '2026-10-04 02:55:55'),
('01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-BNEJIR8QFB', 'package', '01a095f2-d286-7241-b566-8027529b8196', '01a09966-2965-7201-ab57-9f085e23bbc7', NULL, 210000.00, 'paid', '2026-10-07 10:12:11', '2026-10-06 10:13:14', NULL, 'proofs/WrmyZ098hpkUmpUTw9LmcOqDkUqtGOeLdNeyBjqM.png', '2026-10-06 10:12:24', '2026-10-06 10:12:11', '2026-10-06 10:13:14'),
('01a110cc-d5ec-7387-a3c5-87cde025d404', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-E5WX8OSWPO', 'package', '01a095f2-d286-7241-b566-8027529b8196', '01a09966-2965-7201-ab57-9f085e23bbc7', NULL, 210000.00, 'pending', '2026-10-07 10:40:21', NULL, NULL, NULL, NULL, '2026-10-06 10:40:21', '2026-10-06 10:40:21'),
('01a11182-8463-7057-a0f4-8cf88279c6c2', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-DHVCIG8ZQL', 'package', '01a095f2-d286-7241-b566-8027529b8196', '01a09966-2965-7201-ab57-9f085e23bbc7', NULL, 210000.00, 'pending', '2026-10-07 13:58:48', NULL, NULL, NULL, NULL, '2026-10-06 13:58:48', '2026-10-06 13:58:48');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `price` decimal(15,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `uuid`, `name`, `slug`, `description`, `is_popular`, `is_active`, `created_at`, `updated_at`, `price`) VALUES
(1, '01a06234-aac1-722c-84d6-c46d4150e70f', 'Studio Class Pack - 60 Minutes', 'studio-class-pack-60-minutes', 'Your perfect escape from the everyday. Step into the Yogaroots studio, move with intention, breathe deeply, and leave feeling refreshed.', 0, 'active', '2026-09-02 06:00:13', '2026-09-13 06:13:58', 0.00),
(2, '01a095f2-d286-7241-b566-8027529b8196', 'First Timer Package', 'first-timer-package', 'A welcoming first step into your yoga journey at Yogaroots. Enjoy a guided studio session designed to help you feel comfortable, refreshed, and ready to move.', 1, 'active', '2026-09-12 14:08:33', '2026-09-13 06:13:24', 0.00),
(3, '01a09615-73d8-7345-a20f-2d5eeb6b1d1a', 'Unlimited Package', 'unlimited-package', 'Practice freely with unlimited access to Yogaroots classes. Move at your own pace, explore different styles, and make yoga part of your everyday routine.', 0, 'active', '2026-09-12 14:46:23', '2026-09-12 14:46:23', 0.00),
(4, '01a098c6-e509-7154-869f-8d336d44ebc4', 'Sharing Package', 'sharing-package', 'A flexible membership designed to be shared with someone special. Enjoy Yogaroots classes together and make your yoga journey a shared experience.', 0, 'active', '2026-09-13 03:19:26', '2026-09-13 06:13:12', 0.00),
(5, '01a098cd-3db0-73e5-bbe6-ba63e290f1d0', 'Studio Class Pack - 90 Minutes', 'studio-class-pack-90-minutes', 'A deeper escape from the everyday. Step into the Yogaroots studio, move with intention, breathe deeply, and give yourself more time to reconnect, recharge, and leave feeling refreshed.', 0, 'active', '2026-09-13 03:26:22', '2026-09-13 03:26:22', 0.00),
(6, '01a098d2-a02c-72ee-a8eb-b9dc6786761d', '1 On 1 Private Class', '1-on-1-private-class', 'A personalized yoga experience designed around you. Practice one-on-one with our instructor, receive focused guidance, and build a practice that supports your individual goals.', 0, 'active', '2026-09-13 03:32:15', '2026-09-13 03:32:15', 0.00),
(7, '01a098e0-878e-7296-b1c0-a550bf0327b0', 'Couple\'s Private Class', 'couples-private-class', 'A private yoga experience to enjoy together. Practice side by side with personalized guidance from our instructor, deepen your connection, and make every session a meaningful shared experience.', 0, 'active', '2026-09-13 03:47:26', '2026-09-13 03:47:26', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `package_features`
--

CREATE TABLE `package_features` (
  `uuid` char(36) NOT NULL,
  `package_uuid` char(36) NOT NULL,
  `feature` varchar(255) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `package_features`
--

INSERT INTO `package_features` (`uuid`, `package_uuid`, `feature`, `sort_order`, `created_at`, `updated_at`) VALUES
('01a09615-73fb-702f-8c0a-b5012141236e', '01a09615-73d8-7345-a20f-2d5eeb6b1d1a', 'Unlimited Class Access', 0, '2026-09-12 14:46:23', '2026-09-12 14:46:23'),
('01a09615-7401-7073-add9-f090d187e2f8', '01a09615-73d8-7345-a20f-2d5eeb6b1d1a', 'Yoga Mat Provided', 1, '2026-09-12 14:46:23', '2026-09-12 14:46:23'),
('01a09615-7402-71bd-a8b8-9e5f3acd8ad7', '01a09615-73d8-7345-a20f-2d5eeb6b1d1a', 'Locker Access', 2, '2026-09-12 14:46:23', '2026-09-12 14:46:23'),
('01a09615-7402-71bd-a8b8-9e5f3ae8a3fb', '01a09615-73d8-7345-a20f-2d5eeb6b1d1a', 'Shower Facilities', 3, '2026-09-12 14:46:23', '2026-09-12 14:46:23'),
('01a09615-7409-70e7-9271-8596c85a0a7d', '01a09615-73d8-7345-a20f-2d5eeb6b1d1a', 'Access to All Studio Classes', 4, '2026-09-12 14:46:23', '2026-09-12 14:46:23'),
('01a098d3-1959-73c1-84e8-f2fd4240cbd4', '01a098cd-3db0-73e5-bbe6-ba63e290f1d0', 'Premium Yoga Mat', 0, '2026-09-13 03:32:46', '2026-09-13 03:32:46'),
('01a098d3-1960-70fd-90f6-1ceed0d1d517', '01a098cd-3db0-73e5-bbe6-ba63e290f1d0', 'Personal Locker', 1, '2026-09-13 03:32:46', '2026-09-13 03:32:46'),
('01a098d3-1961-7365-8471-3b94c5c1b0a2', '01a098cd-3db0-73e5-bbe6-ba63e290f1d0', 'Shower & Changing Room', 2, '2026-09-13 03:32:46', '2026-09-13 03:32:46'),
('01a098d3-1962-7322-a85e-eab4d6238c35', '01a098cd-3db0-73e5-bbe6-ba63e290f1d0', 'Complimentary Drinking Water', 3, '2026-09-13 03:32:46', '2026-09-13 03:32:46'),
('01a098d3-1962-7322-a85e-eab4d6948939', '01a098cd-3db0-73e5-bbe6-ba63e290f1d0', '90-Minute Yoga Session', 4, '2026-09-13 03:32:46', '2026-09-13 03:32:46'),
('01a098de-ec82-701f-bb24-c2dbbe0c59b9', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', 'Private 1-on-1 Session', 0, '2026-09-13 03:45:41', '2026-09-13 03:45:41'),
('01a098de-ec83-7166-a29d-85d9cd8131b5', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', 'Personalized Yoga Practice', 1, '2026-09-13 03:45:41', '2026-09-13 03:45:41'),
('01a098de-ec83-7166-a29d-85d9cdfbb9df', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', 'Dedicated Instructor', 2, '2026-09-13 03:45:41', '2026-09-13 03:45:41'),
('01a098de-ec84-71fb-82be-6092568d7513', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', 'Individual Guidance & Corrections', 3, '2026-09-13 03:45:41', '2026-09-13 03:45:41'),
('01a098de-ec86-70ea-a38d-1c994c52404a', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', 'Practice Tailored to Your Goals', 4, '2026-09-13 03:45:41', '2026-09-13 03:45:41'),
('01a098de-ec87-70ea-ad3c-c41d16e6f542', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', 'Flexible Session Focus', 5, '2026-09-13 03:45:41', '2026-09-13 03:45:41'),
('01a098e0-8799-703c-a314-911481118e4e', '01a098e0-878e-7296-b1c0-a550bf0327b0', 'Private Session for Two', 0, '2026-09-13 03:47:26', '2026-09-13 03:47:26'),
('01a098e0-879f-715a-8e83-0ae3ed067c6f', '01a098e0-878e-7296-b1c0-a550bf0327b0', 'Personalized Yoga Practice', 1, '2026-09-13 03:47:26', '2026-09-13 03:47:26'),
('01a098e0-87a0-705c-94e4-64e7348fd306', '01a098e0-878e-7296-b1c0-a550bf0327b0', 'Dedicated Instructor', 2, '2026-09-13 03:47:26', '2026-09-13 03:47:26'),
('01a098e0-87a1-7275-b6b4-5e5b4873a277', '01a098e0-878e-7296-b1c0-a550bf0327b0', 'Individual Guidance & Corrections', 3, '2026-09-13 03:47:26', '2026-09-13 03:47:26'),
('01a098e0-87a2-72dc-8631-635b1abff726', '01a098e0-878e-7296-b1c0-a550bf0327b0', 'Partner-Based Practice', 4, '2026-09-13 03:47:26', '2026-09-13 03:47:26'),
('01a098e0-87a3-72d4-b233-8ba8254b8bb6', '01a098e0-878e-7296-b1c0-a550bf0327b0', 'Practice Tailored to Your Goals', 5, '2026-09-13 03:47:26', '2026-09-13 03:47:26'),
('01a09965-fbf3-7255-bb66-859b3a4e1bff', '01a098c6-e509-7154-869f-8d336d44ebc4', 'Share Your Class Quota', 0, '2026-09-13 06:13:12', '2026-09-13 06:13:12'),
('01a09965-fbf9-7032-b9db-53e383f6b6c9', '01a098c6-e509-7154-869f-8d336d44ebc4', 'Flexible Class Access', 1, '2026-09-13 06:13:12', '2026-09-13 06:13:12'),
('01a09965-fbfa-72c8-9335-c298673b2bec', '01a098c6-e509-7154-869f-8d336d44ebc4', 'Locker Access', 2, '2026-09-13 06:13:12', '2026-09-13 06:13:12'),
('01a09965-fbfa-72c8-9335-c298680c415e', '01a098c6-e509-7154-869f-8d336d44ebc4', 'Shower Facilities', 3, '2026-09-13 06:13:12', '2026-09-13 06:13:12'),
('01a09965-fbfb-72aa-8e43-d33cdbde0650', '01a098c6-e509-7154-869f-8d336d44ebc4', 'Access to Studio Classes', 4, '2026-09-13 06:13:12', '2026-09-13 06:13:12'),
('01a09966-2967-7153-8bac-e05d58fabab9', '01a095f2-d286-7241-b566-8027529b8196', 'First Studio Session', 0, '2026-09-13 06:13:24', '2026-09-13 06:13:24'),
('01a09966-296d-7188-bf8c-90a9e73adf27', '01a095f2-d286-7241-b566-8027529b8196', 'Yoga Mat Provided', 1, '2026-09-13 06:13:24', '2026-09-13 06:13:24'),
('01a09966-296d-7188-bf8c-90a9e8268917', '01a095f2-d286-7241-b566-8027529b8196', 'Locker Access', 2, '2026-09-13 06:13:24', '2026-09-13 06:13:24'),
('01a09966-296e-71e6-a0f5-c78ca826aad2', '01a095f2-d286-7241-b566-8027529b8196', 'Shower Facilities', 3, '2026-09-13 06:13:24', '2026-09-13 06:13:24'),
('01a09966-296e-71e6-a0f5-c78ca89e5199', '01a095f2-d286-7241-b566-8027529b8196', 'Guidance from Our Instructor', 4, '2026-09-13 06:13:24', '2026-09-13 06:13:24'),
('01a09966-af91-7058-9ac6-0b9ce8883251', '01a06234-aac1-722c-84d6-c46d4150e70f', 'Premium Yoga Mat', 0, '2026-09-13 06:13:58', '2026-09-13 06:13:58'),
('01a09966-af97-7236-bf4d-703e3e28a1b5', '01a06234-aac1-722c-84d6-c46d4150e70f', 'Personal Locker', 1, '2026-09-13 06:13:58', '2026-09-13 06:13:58'),
('01a09966-af98-724e-8431-3f3e29806170', '01a06234-aac1-722c-84d6-c46d4150e70f', 'Shower & Changing Room', 2, '2026-09-13 06:13:58', '2026-09-13 06:13:58'),
('01a09966-af98-724e-8431-3f3e2996fecb', '01a06234-aac1-722c-84d6-c46d4150e70f', 'Complimentary Drinking Water', 3, '2026-09-13 06:13:58', '2026-09-13 06:13:58'),
('01a09966-af99-7238-b1f5-a35b1f405ff9', '01a06234-aac1-722c-84d6-c46d4150e70f', '60-Minute Yoga Session', 4, '2026-09-13 06:13:58', '2026-09-13 06:13:58');

-- --------------------------------------------------------

--
-- Table structure for table `package_options`
--

CREATE TABLE `package_options` (
  `uuid` char(36) NOT NULL,
  `package_uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quota` int(10) UNSIGNED NOT NULL,
  `price` bigint(20) UNSIGNED NOT NULL,
  `discount_price` bigint(20) UNSIGNED DEFAULT NULL,
  `duration` int(10) UNSIGNED NOT NULL,
  `duration_unit` enum('day','week','month','year') NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `package_options`
--

INSERT INTO `package_options` (`uuid`, `package_uuid`, `name`, `quota`, `price`, `discount_price`, `duration`, `duration_unit`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
('01a09615-73f9-72ee-85e5-f2be4ef4c502', '01a09615-73d8-7345-a20f-2d5eeb6b1d1a', 'All Classes', 50, 3000000, 2400000, 1, 'month', 0, 1, '2026-09-12 14:46:23', '2026-09-12 14:46:23'),
('01a098d3-1951-7151-a2fb-64955a2f9d09', '01a098cd-3db0-73e5-bbe6-ba63e290f1d0', 'Single drop in', 50, 280000, NULL, 1, 'day', 0, 1, '2026-09-13 03:32:46', '2026-09-13 03:32:46'),
('01a098d3-1955-72ab-8da5-5f9e03ae8831', '01a098cd-3db0-73e5-bbe6-ba63e290f1d0', '3 Classes', 50, 795000, NULL, 10, 'day', 1, 1, '2026-09-13 03:32:46', '2026-09-13 03:32:46'),
('01a098d3-1956-713d-af97-f5237ace5eb4', '01a098cd-3db0-73e5-bbe6-ba63e290f1d0', '5 Classes', 50, 1250000, NULL, 20, 'day', 2, 1, '2026-09-13 03:32:46', '2026-09-13 03:32:46'),
('01a098d3-1957-7323-b837-fbbb3e2ed42b', '01a098cd-3db0-73e5-bbe6-ba63e290f1d0', '10 Classes', 50, 2350000, NULL, 2, 'month', 3, 1, '2026-09-13 03:32:46', '2026-09-13 03:32:46'),
('01a098de-ec50-73bf-b928-c37f31d37568', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', 'Single drop in', 1, 1000000, NULL, 1, 'week', 0, 1, '2026-09-13 03:45:41', '2026-09-13 03:45:41'),
('01a098de-ec7d-7352-9076-67a2defe3fde', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', '5 Private Classes', 1, 4500000, NULL, 5, 'week', 1, 1, '2026-09-13 03:45:41', '2026-09-13 03:45:41'),
('01a098de-ec7f-7275-a7f8-be27d7caa4ff', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', '10 Private Classes', 1, 8100000, NULL, 10, 'week', 2, 1, '2026-09-13 03:45:41', '2026-09-13 03:45:41'),
('01a098e0-8795-71b4-84a4-de9c8a0b517e', '01a098e0-878e-7296-b1c0-a550bf0327b0', 'Single drop in', 2, 1200000, NULL, 1, 'week', 0, 1, '2026-09-13 03:47:26', '2026-09-13 03:47:26'),
('01a098e0-8797-7309-954d-6caac6a8f4ee', '01a098e0-878e-7296-b1c0-a550bf0327b0', '5 Private Classes', 2, 5400000, NULL, 5, 'week', 1, 1, '2026-09-13 03:47:26', '2026-09-13 03:47:26'),
('01a098e0-8798-707b-b260-c1379231361e', '01a098e0-878e-7296-b1c0-a550bf0327b0', '10 Private Classes', 2, 9720000, NULL, 10, 'week', 2, 1, '2026-09-13 03:47:26', '2026-09-13 03:47:26'),
('01a09965-fbf0-7307-ba83-dfa1382cf270', '01a098c6-e509-7154-869f-8d336d44ebc4', '25 Classes', 50, 6999000, 5599000, 1, 'month', 0, 1, '2026-09-13 06:13:12', '2026-09-13 06:13:12'),
('01a09966-2965-7201-ab57-9f085e23bbc7', '01a095f2-d286-7241-b566-8027529b8196', 'First Class Experience', 50, 210000, NULL, 1, 'week', 0, 1, '2026-09-13 06:13:24', '2026-09-13 06:13:24'),
('01a09966-af8d-7252-bbf0-f1665358611d', '01a06234-aac1-722c-84d6-c46d4150e70f', 'Single drop in', 50, 230000, NULL, 1, 'day', 0, 1, '2026-09-13 06:13:58', '2026-09-13 06:13:58'),
('01a09966-af8e-7055-b1a5-460286a5403f', '01a06234-aac1-722c-84d6-c46d4150e70f', '3 Classes', 50, 599000, NULL, 10, 'day', 1, 1, '2026-09-13 06:13:58', '2026-09-13 06:13:58'),
('01a09966-af8f-73fd-94d6-680b288dd1ba', '01a06234-aac1-722c-84d6-c46d4150e70f', '5 Classes', 50, 878000, NULL, 20, 'day', 2, 1, '2026-09-13 06:13:58', '2026-09-13 06:13:58'),
('01a09966-af8f-73fd-94d6-680b28b8b248', '01a06234-aac1-722c-84d6-c46d4150e70f', '10 Classes', 50, 1555000, NULL, 2, 'month', 3, 1, '2026-09-13 06:13:58', '2026-09-13 06:13:58');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `uuid` char(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` timestamp NULL DEFAULT NULL,
  `user_uuid` char(36) DEFAULT NULL,
  `has_sidebar` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`uuid`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `is_published`, `published_at`, `user_uuid`, `has_sidebar`, `created_at`, `updated_at`) VALUES
('5380a3e5-adfa-437c-a0fa-bc2193d55cb2', 'Privacy Policy', 'privacy-policy', 'By accessing and using the YogaRoots website and services, you acknowledge that you have read, understood, and agreed to this Privacy Policy.', '<h2><strong style=\"color: rgb(71, 85, 105); font-size: 16px; letter-spacing: 0.1px;\">Last Updated:</strong><span style=\"color: rgb(71, 85, 105); font-size: 16px; font-weight: 400; letter-spacing: 0.1px;\"> August 30, 2026</span></h2>\r\n\r\n<p>\r\n  Welcome to <strong>YogaRoots</strong>.\r\n</p>\r\n\r\n<p>\r\n  We value your trust and are committed to protecting the privacy and security of your personal information. This Privacy Policy explains how YogaRoots collects, uses, stores, and protects your information when you visit our website, register for an account, contact us, or use our services.\r\n</p>\r\n\r\n<p>\r\n  By accessing and using the YogaRoots website and services, you acknowledge that you have read, understood, and agreed to this Privacy Policy.\r\n</p>\r\n\r\n<h3>1. Information We Collect</h3>\r\n\r\n<p>\r\n  We may collect information that you voluntarily provide when using the YogaRoots website or services, including:\r\n</p>\r\n\r\n<ul>\r\n  <li>Full name</li>\r\n  <li>Email address</li>\r\n  <li>Phone number</li>\r\n  <li>Information submitted through contact forms</li>\r\n  <li>Class registration or booking information</li>\r\n  <li>Account information if you use our login features</li>\r\n  <li>Other information you voluntarily provide to us</li>\r\n</ul>\r\n\r\n<p>\r\n  We may also automatically collect certain technical information when you access our website, such as your device type, browser type, IP address, and information about your interactions with and use of the website.\r\n</p>\r\n\r\n<h3>2. How We Use Your Information</h3>\r\n\r\n<p>\r\n  The information we collect may be used to:\r\n</p>\r\n\r\n<ul>\r\n  <li>Process class registrations and bookings</li>\r\n  <li>Manage your account and membership</li>\r\n  <li>Respond to your questions, requests, or inquiries</li>\r\n  <li>Provide information about YogaRoots classes, schedules, programs, and services</li>\r\n  <li>Send important information related to the services you use</li>\r\n  <li>Improve our website, services, and overall user experience</li>\r\n  <li>Maintain website security and prevent misuse or unauthorized activity</li>\r\n  <li>Comply with applicable legal and regulatory requirements</li>\r\n</ul>\r\n\r\n<p>\r\n  We strive to use your personal information only for purposes that are relevant and necessary to provide and improve YogaRoots services.\r\n</p>\r\n\r\n<h3>3. Protection of Your Information</h3>\r\n\r\n<p>\r\n  YogaRoots takes reasonable administrative, technical, and organizational measures to protect your personal information from unauthorized access, use, alteration, disclosure, or loss.\r\n</p>\r\n\r\n<p>\r\n  However, no storage system or transmission of information over the internet can be guaranteed to be completely secure. Therefore, while we make reasonable efforts to protect your information, we cannot guarantee absolute security.\r\n</p>\r\n\r\n<h3>4. Third-Party Services</h3>\r\n\r\n<p>\r\n  To operate and improve our services, YogaRoots may work with trusted third-party service providers, including technology providers, payment processors, authentication services, analytics providers, communication platforms, and other service partners.\r\n</p>\r\n\r\n<p>\r\n  Information may be shared with these third parties only to the extent reasonably necessary to provide, maintain, or improve the relevant services.\r\n</p>\r\n\r\n<p>\r\n  Third-party providers may have their own privacy policies and terms of use. We encourage you to review the privacy policies of any third-party services you choose to use.\r\n</p>\r\n\r\n<h3>5. Cookies</h3>\r\n\r\n<p>\r\n  The YogaRoots website may use cookies and similar technologies to help the website function properly, remember your preferences, understand how visitors use our website, and improve your overall experience.\r\n</p>\r\n\r\n<p>\r\n  You can manage or disable cookies through your browser settings. Please note that disabling certain cookies may affect the functionality or availability of some features on our website.\r\n</p>\r\n\r\n<h3>6. Information You Provide to Us</h3>\r\n\r\n<p>\r\n  When you contact YogaRoots through our contact forms, email, WhatsApp, or other communication channels, the information you provide may be used to respond to your inquiry and provide the assistance or information you need.\r\n</p>\r\n\r\n<p>\r\n  We do not sell or rent your personal information to third parties.\r\n</p>\r\n\r\n<h3>7. Data Retention</h3>\r\n\r\n<p>\r\n  We may retain your personal information for as long as necessary to provide our services, fulfill the purposes for which the information was collected, complete transactions, comply with legal obligations, or address legitimate administrative and operational needs.\r\n</p>\r\n\r\n<p>\r\n  When your information is no longer required, we may delete or anonymize it in accordance with our internal policies and applicable laws and regulations.\r\n</p>\r\n\r\n<h3>8. Your Rights</h3>\r\n\r\n<p>\r\n  You may contact YogaRoots if you wish to:\r\n</p>\r\n\r\n<ul>\r\n  <li>Update your personal information</li>\r\n  <li>Correct inaccurate or incomplete information</li>\r\n  <li>Ask about the personal information we hold about you</li>\r\n  <li>Request the deletion of certain information, where permitted and where such deletion does not conflict with applicable legal or service requirements</li>\r\n  <li>Ask how your personal information is collected, used, or processed</li>\r\n</ul>\r\n\r\n<p>\r\n  We will review and respond to requests in accordance with applicable laws, our policies, and the circumstances of each request.\r\n</p>\r\n\r\n<h3>9. Children\'s Privacy</h3>\r\n\r\n<p>\r\n  YogaRoots respects the privacy of children and does not knowingly intend to collect personal information from children without appropriate involvement or consent from a parent or legal guardian.\r\n</p>\r\n\r\n<p>\r\n  If you believe that a child has provided personal information to us without appropriate consent, please contact us so that we can review the matter and take appropriate action.\r\n</p>\r\n\r\n<h3>10. Changes to This Privacy Policy</h3>\r\n\r\n<p>\r\n  YogaRoots may update this Privacy Policy from time to time to reflect changes in our services, technology, business practices, or applicable laws and regulations.\r\n</p>\r\n\r\n<p>\r\n  Any changes will be published on this page, and the \"Last Updated\" date will be revised accordingly.\r\n</p>\r\n\r\n<p>\r\n  We encourage you to review this page periodically to stay informed about how we collect, use, and protect your personal information.\r\n</p>\r\n\r\n<h3>11. Contact Us</h3>\r\n\r\n<p>\r\n  If you have any questions about this Privacy Policy or how YogaRoots handles your personal information, please contact us through the following channels:\r\n</p>\r\n\r\n<p>\r\n  <strong>YogaRoots</strong>\r\n</p>\r\n\r\n<p>\r\n  <strong>Email:</strong>&nbsp;\r\n</p>\r\n\r\n<p>\r\n  <strong>Phone / WhatsApp:</strong> +62 813 2122 1270\r\n</p>\r\n\r\n<p>\r\n  <strong>Address:</strong> Roots Prasasta Building, Jl. Kawi Raya No.37, RT.6/RW.2, Guntur, Setiabudi, South Jakarta City, Jakarta 12980\r\n</p>\r\n\r\n<p>\r\n  We will do our best to assist you and provide the information you need regarding your privacy and the handling of your personal information.\r\n</p>', 'pages/KGpKHJFc1aFrX0OocaW7I9uDWxmWiRAc9XA3gCzQ.png', 1, '2026-08-30 17:00:00', NULL, 0, '2025-10-16 04:07:16', '2026-09-06 21:25:55'),
('9e55815c-a4dd-4df2-8cb5-27f7de7249fd', 'Terms & Conditions', 'terms-conditions', 'By accessing or using the YogaRoots website and services, you acknowledge that you have read, understood, and agreed to these Terms & Conditions.', '`<strong style=\"letter-spacing: 0.1px;\">Last Updated:</strong><span style=\"letter-spacing: 0.1px;\"> August 30, 2026</span><p>\r\n  Welcome to <strong>YogaRoots</strong>.\r\n</p>\r\n\r\n<p>\r\n  These Terms &amp; Conditions explain the rules and guidelines for using the YogaRoots website, purchasing memberships, booking classes, making payments, and using our services.\r\n</p>\r\n\r\n<p>\r\n  By accessing or using the YogaRoots website and services, you acknowledge that you have read, understood, and agreed to these Terms &amp; Conditions.\r\n</p>\r\n\r\n<h3>1. Website Use</h3>\r\n\r\n<p>\r\n  You agree to use the YogaRoots website responsibly and only for lawful purposes.\r\n</p>\r\n\r\n<p>\r\n  You must not use the website to:\r\n</p>\r\n\r\n<ul>\r\n  <li>Provide false, misleading, or inaccurate information</li>\r\n  <li>Attempt to gain unauthorized access to accounts or systems</li>\r\n  <li>Interfere with or disrupt the operation of the website</li>\r\n  <li>Use the website for fraudulent, abusive, or unlawful activities</li>\r\n  <li>Copy, reproduce, or misuse YogaRoots content without permission</li>\r\n</ul>\r\n\r\n<p>\r\n  YogaRoots reserves the right to restrict or suspend access to the website or services if we reasonably believe that these Terms &amp; Conditions have been violated.\r\n</p>\r\n\r\n<h3>2. Account Registration</h3>\r\n\r\n<p>\r\n  Certain YogaRoots services may require you to create an account.\r\n</p>\r\n\r\n<p>\r\n  When creating an account, you agree to provide accurate and up-to-date information and to keep your account information secure.\r\n</p>\r\n\r\n<ul>\r\n  <li>You are responsible for maintaining the confidentiality of your login credentials.</li>\r\n  <li>You are responsible for activities performed through your account.</li>\r\n  <li>You must notify YogaRoots if you believe your account has been accessed without authorization.</li>\r\n  <li>You must not create an account using another person\'s identity or information without permission.</li>\r\n</ul>\r\n\r\n<h3>3. Membership</h3>\r\n\r\n<p>\r\n  YogaRoots offers different membership packages and options designed to suit different practice needs.\r\n</p>\r\n\r\n<p>\r\n  Each membership option may have different pricing, class quota, duration, benefits, and conditions.\r\n</p>\r\n\r\n<ul>\r\n  <li>Memberships are personal and may only be used by the registered member.</li>\r\n  <li>Memberships cannot be transferred, resold, or shared with another person.</li>\r\n  <li>A membership becomes active after the payment has been successfully completed and confirmed.</li>\r\n  <li>Membership validity follows the duration stated in the selected membership option.</li>\r\n  <li>Unused class quota may expire when the membership period ends.</li>\r\n  <li>Unlimited memberships may provide unlimited class access during the applicable membership period, subject to class availability and booking requirements.</li>\r\n</ul>\r\n\r\n<p>\r\n  YogaRoots reserves the right to update membership packages, pricing, benefits, availability, or terms from time to time.\r\n</p>\r\n\r\n<h3>4. Membership Options</h3>\r\n\r\n<p>\r\n  A membership package may contain one or more membership options. Each option may have its own price, duration, and class quota.\r\n</p>\r\n\r\n<p>\r\n  When purchasing a membership, you are responsible for reviewing the selected option before completing your payment.\r\n</p>\r\n\r\n<p>\r\n  The membership option selected at checkout determines the applicable price, duration, quota, and benefits associated with your membership.\r\n</p>\r\n\r\n<h3>5. Class Booking</h3>\r\n\r\n<p>\r\n  Class availability is limited and subject to the capacity of each scheduled class.\r\n</p>\r\n\r\n<p>\r\n  Members must complete the booking process through the available YogaRoots booking system before attending a class.\r\n</p>\r\n\r\n<ul>\r\n  <li>A booking is considered confirmed once the booking process has been successfully completed.</li>\r\n  <li>Class availability may change at any time based on capacity.</li>\r\n  <li>Members are encouraged to arrive at least 10 minutes before the scheduled class.</li>\r\n  <li>Late arrival may result in the member being unable to join the class if entering the studio may disturb the ongoing practice.</li>\r\n  <li>Members must follow the instructions provided by the instructor and YogaRoots staff.</li>\r\n</ul>\r\n\r\n<h3>6. Class Cancellation &amp; No-Show</h3>\r\n\r\n<p>\r\n  We understand that plans can change. However, we ask members to cancel their bookings as early as possible so that another member may have the opportunity to attend.\r\n</p>\r\n\r\n<ul>\r\n  <li>Class cancellations must be made through the available YogaRoots booking system.</li>\r\n  <li>Late cancellations may result in the applicable class quota being deducted.</li>\r\n  <li>No-shows may result in the applicable class quota being deducted.</li>\r\n  <li>Repeated cancellations or no-shows may affect future booking privileges.</li>\r\n</ul>\r\n\r\n<p>\r\n  Specific cancellation periods or policies may apply to certain classes, workshops, events, or special programs.\r\n</p>\r\n\r\n<h3>7. Payments</h3>\r\n\r\n<p>\r\n  All prices displayed on the YogaRoots website are stated in Indonesian Rupiah (IDR), unless otherwise specified.\r\n</p>\r\n\r\n<ul>\r\n  <li>A membership or service is considered purchased only after payment has been successfully completed.</li>\r\n  <li>Prices, discounts, and promotional offers may change from time to time.</li>\r\n  <li>Promotional prices may only be available for a specific period or under specific conditions.</li>\r\n  <li>The final amount payable will be displayed during the checkout process before payment is completed.</li>\r\n  <li>Payment transactions may be processed through third-party payment providers.</li>\r\n</ul>\r\n\r\n<p>\r\n  By proceeding with a payment, you agree to the applicable payment terms and conditions provided by YogaRoots and the relevant payment provider.\r\n</p>\r\n\r\n<h3>8. Refunds &amp; Cancellations</h3>\r\n\r\n<p>\r\n  Refund eligibility may depend on the type of purchase, payment status, membership status, booking status, and applicable YogaRoots policies.\r\n</p>\r\n\r\n<p>\r\n  Membership purchases and completed payments may not be refundable unless otherwise stated or required under applicable laws and regulations.\r\n</p>\r\n\r\n<p>\r\n  If you believe that a payment was made incorrectly or you experience a payment-related issue, please contact YogaRoots as soon as possible so that we can review the transaction.\r\n</p>\r\n\r\n<h3>9. Studio Rules</h3>\r\n\r\n<p>\r\n  YogaRoots is a shared space where everyone should feel comfortable, safe, and respected.\r\n</p>\r\n\r\n<p>\r\n  Members are expected to:\r\n</p>\r\n\r\n<ul>\r\n  <li>Respect instructors, staff, and fellow members.</li>\r\n  <li>Keep phones on silent during classes.</li>\r\n  <li>Maintain cleanliness and cleanliness of shared studio areas.</li>\r\n  <li>Return studio equipment after use.</li>\r\n  <li>Follow instructor and staff instructions.</li>\r\n  <li>Avoid behavior that may disturb or make other members uncomfortable.</li>\r\n  <li>Take reasonable care of YogaRoots facilities and equipment.</li>\r\n</ul>\r\n\r\n<p>\r\n  YogaRoots may take reasonable action if a member\'s behavior negatively affects the safety, comfort, or experience of other members.\r\n</p>\r\n\r\n<h3>10. Health &amp; Safety</h3>\r\n\r\n<p>\r\n  Yoga is a physical activity and may involve physical risks. Every member is responsible for practicing according to their own physical ability and condition.\r\n</p>\r\n\r\n<ul>\r\n  <li>Members should inform the instructor about relevant physical limitations, injuries, or conditions before participating.</li>\r\n  <li>Members should not force movements beyond their comfortable limits.</li>\r\n  <li>If you experience pain, dizziness, discomfort, or other concerning symptoms, stop practicing and inform the instructor immediately.</li>\r\n  <li>Members are responsible for determining whether they are physically able to participate in a class.</li>\r\n</ul>\r\n\r\n<p>\r\n  YogaRoots and its instructors are not a substitute for professional medical advice, diagnosis, or treatment.\r\n</p>\r\n\r\n<p>\r\n  If you have concerns about whether yoga or a particular class is suitable for you, we recommend consulting a qualified healthcare professional before participating.\r\n</p>\r\n\r\n<h3>11. Personal Belongings</h3>\r\n\r\n<p>\r\n  Members are responsible for their personal belongings while visiting YogaRoots.\r\n</p>\r\n\r\n<p>\r\n  We recommend keeping valuable items secure and bringing only essential belongings into the studio.\r\n</p>\r\n\r\n<p>\r\n  YogaRoots is not responsible for loss, theft, or damage to personal belongings except where responsibility cannot legally be excluded.\r\n</p>\r\n\r\n<h3>12. Class Schedules &amp; Instructors</h3>\r\n\r\n<p>\r\n  YogaRoots makes reasonable efforts to maintain the published class schedule. However, schedules and instructors may occasionally change due to operational requirements, instructor availability, maintenance, special events, or other circumstances.\r\n</p>\r\n\r\n<p>\r\n  YogaRoots reserves the right to modify, reschedule, replace, or cancel a class when necessary.\r\n</p>\r\n\r\n<p>\r\n  Where reasonably possible, YogaRoots will provide notice of significant schedule changes to affected members.\r\n</p>\r\n\r\n<h3>13. Promotions &amp; Special Offers</h3>\r\n\r\n<p>\r\n  YogaRoots may offer promotional packages, discounts, introductory offers, events, workshops, or other special programs from time to time.\r\n</p>\r\n\r\n<p>\r\n  Each promotion may have its own eligibility requirements, validity period, pricing, limitations, and terms.\r\n</p>\r\n\r\n<ul>\r\n  <li>Promotions may only be available for a limited period.</li>\r\n  <li>Promotions may not be combined with other offers unless explicitly stated.</li>\r\n  <li>Promotional benefits cannot be exchanged for cash unless otherwise stated.</li>\r\n  <li>YogaRoots may modify or discontinue a promotion in accordance with its applicable terms.</li>\r\n</ul>\r\n\r\n<h3>14. Intellectual Property</h3>\r\n\r\n<p>\r\n  All content available on the YogaRoots website, including logos, photographs, graphics, illustrations, text, videos, designs, and other materials, is owned by or licensed to YogaRoots unless otherwise stated.\r\n</p>\r\n\r\n<p>\r\n  You may access and use the content for personal, non-commercial purposes only.\r\n</p>\r\n\r\n<p>\r\n  You may not reproduce, distribute, modify, publish, sell, or commercially use YogaRoots content without prior written permission.\r\n</p>\r\n\r\n<h3>15. Third-Party Services</h3>\r\n\r\n<p>\r\n  YogaRoots may use or provide access to third-party services, including payment providers, communication platforms, analytics services, authentication services, and other technology providers.\r\n</p>\r\n\r\n<p>\r\n  Your use of third-party services may be subject to the terms and policies of the relevant third-party provider.\r\n</p>\r\n\r\n<p>\r\n  YogaRoots is not responsible for the policies, availability, or practices of third-party services that are outside our control.\r\n</p>\r\n\r\n<h3>16. Website Availability</h3>\r\n\r\n<p>\r\n  YogaRoots aims to keep the website and services available and functioning properly. However, we cannot guarantee that the website will always be available, uninterrupted, or free from errors.\r\n</p>\r\n\r\n<p>\r\n  Website availability may occasionally be affected by maintenance, technical issues, network problems, security incidents, or circumstances beyond our reasonable control.\r\n</p>\r\n\r\n<h3>17. Limitation of Responsibility</h3>\r\n\r\n<p>\r\n  YogaRoots strives to provide a safe, comfortable, and high-quality experience for all members.\r\n</p>\r\n\r\n<p>\r\n  However, participation in yoga and physical activities involves inherent risks. Members are responsible for practicing within their own capabilities and following reasonable instructions provided by instructors and staff.\r\n</p>\r\n\r\n<p>\r\n  Nothing in these Terms &amp; Conditions is intended to exclude or limit any rights, responsibilities, or liabilities that cannot legally be excluded or limited under applicable laws and regulations.\r\n</p>\r\n\r\n<h3>18. Changes to These Terms</h3>\r\n\r\n<p>\r\n  YogaRoots may update these Terms &amp; Conditions from time to time to reflect changes in our services, business practices, technology, or applicable laws and regulations.\r\n</p>\r\n\r\n<p>\r\n  Any changes will be published on this page, and the \"Last Updated\" date will be revised accordingly.\r\n</p>\r\n\r\n<p>\r\n  We encourage you to review this page periodically to stay informed about the terms that apply to your use of YogaRoots services.\r\n</p>\r\n\r\n<h3>19. Contact Us</h3>\r\n\r\n<p>\r\n  If you have any questions about these Terms &amp; Conditions, membership, class bookings, payments, or other YogaRoots services, please contact us through the following channels:\r\n</p>\r\n\r\n<p>\r\n  <strong>YogaRoots</strong>\r\n</p>\r\n\r\n<p>\r\n  <strong>Email:</strong>&nbsp;\r\n</p>\r\n\r\n<p>\r\n  <strong>Phone / WhatsApp:</strong> +62 813 2122 1270\r\n</p>\r\n\r\n<p>\r\n  <strong>Address:</strong> Roots Prasasta Building, Jl. Kawi Raya No.37, RT.6/RW.2, Guntur, Setiabudi, South Jakarta City, Jakarta 12980\r\n</p>\r\n\r\n<p>\r\n  We will do our best to assist you and provide the information you need regarding our services, memberships, bookings, and these Terms &amp; Conditions.\r\n</p>', 'pages/UYCMzdX1OfuyrVRPYwicwNu1JZnHYBZhRSUjL4ki.jpg', 1, '2026-09-12 17:00:00', '787b72ea-59d0-4d54-848b-c200bddafdd2', 0, '2026-09-13 03:01:40', '2026-09-13 03:01:40');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `uuid` char(36) NOT NULL,
  `order_uuid` char(36) NOT NULL,
  `payment_gateway` varchar(255) NOT NULL DEFAULT 'midtrans',
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `gross_amount` decimal(15,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `raw_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_response`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`uuid`, `order_uuid`, `payment_gateway`, `transaction_id`, `payment_type`, `gross_amount`, `status`, `paid_at`, `raw_response`, `created_at`, `updated_at`) VALUES
('01a104d6-e859-7018-b76c-578ce8b292b2', '01a1049e-afda-7188-b2f9-9fa3b404f6d3', 'manual_transfer', NULL, 'Bank Transfer', 210000.00, 'paid', '2026-10-04 02:55:55', NULL, '2026-10-04 02:55:55', '2026-10-04 02:55:55'),
('01a110b4-000d-7377-9dab-512dd7adc04c', '01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', 'manual_transfer', NULL, 'Bank Transfer', 210000.00, 'paid', '2026-10-06 10:13:14', NULL, '2026-10-06 10:13:14', '2026-10-06 10:13:14');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'menu.main-menu', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(2, 'menu.role-permission', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(3, 'menu.access-management', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(4, 'dashboard.index', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(5, 'user.index', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(6, 'user.store', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(7, 'user.update', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(8, 'user.destroy', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(9, 'menu-group.index', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(10, 'menu-group.store', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(11, 'menu-group.update', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(12, 'menu-group.destroy', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(13, 'menu-item.index', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(14, 'menu-item.store', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(15, 'menu-item.update', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(16, 'menu-item.destroy', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(17, 'route.index', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(18, 'route.store', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(19, 'route.update', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(20, 'route.destroy', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(21, 'role.index', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(22, 'role.store', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(23, 'role.update', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(24, 'role.destroy', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(25, 'permission.index', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(26, 'permission.store', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(27, 'permission.update', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(28, 'permission.destroy', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(29, 'faq.destroy', 'web', '2024-09-27 00:07:55', '2025-09-16 01:45:11'),
(30, 'faq.index', 'web', '2024-09-27 00:08:52', '2025-09-16 01:45:00'),
(31, 'faq.store', 'web', '2024-09-27 00:09:11', '2025-09-16 01:45:36'),
(32, 'faq.update', 'web', '2024-09-27 00:09:36', '2025-09-16 01:45:20'),
(33, 'menu.main-portofolio', 'web', '2024-09-27 00:11:21', '2024-09-27 00:11:21'),
(35, 'company.index', 'web', '2024-09-27 01:42:51', '2024-09-27 01:52:59'),
(36, 'company.store', 'web', '2024-09-27 01:43:04', '2024-09-27 01:53:19'),
(37, 'company.update', 'web', '2024-09-27 01:43:16', '2024-09-27 01:53:10'),
(38, 'categories.destroy', 'web', '2024-09-29 12:28:15', '2024-10-15 13:50:56'),
(39, 'categories.index', 'web', '2024-09-29 12:28:26', '2024-10-15 13:51:03'),
(40, 'categories.store', 'web', '2024-09-29 12:28:42', '2024-10-15 13:51:13'),
(41, 'categories.update', 'web', '2024-09-29 12:28:54', '2024-10-15 13:51:20'),
(46, 'banner.destroy', 'web', '2024-09-30 12:05:12', '2024-10-13 22:29:29'),
(47, 'banner.index', 'web', '2024-09-30 12:05:28', '2024-10-13 22:29:37'),
(48, 'banner.store', 'web', '2024-09-30 12:05:41', '2024-10-13 22:29:45'),
(49, 'banner.update', 'web', '2024-09-30 12:05:59', '2024-10-13 22:29:53'),
(50, 'articles.destroy', 'web', '2024-10-15 14:19:19', '2024-10-15 14:19:19'),
(51, 'articles.create', 'web', '2024-10-15 14:19:37', '2024-10-15 14:20:03'),
(52, 'articles.index', 'web', '2024-10-15 14:20:16', '2024-10-15 14:20:16'),
(53, 'articles.store', 'web', '2024-10-15 14:20:34', '2024-10-15 14:20:34'),
(54, 'articles.update', 'web', '2024-10-15 14:20:52', '2024-10-15 14:20:52'),
(55, 'dashboard.form', 'web', '2025-07-21 15:03:35', '2025-07-21 16:36:22'),
(60, 'poll.destroy', 'web', '2025-07-21 17:50:21', '2025-09-17 22:51:57'),
(65, 'poll.index', 'web', '2025-07-21 17:50:33', '2025-09-17 22:52:13'),
(70, 'poll.store', 'web', '2025-07-21 17:50:49', '2025-09-17 22:52:25'),
(75, 'poll.update', 'web', '2025-07-21 17:51:14', '2025-09-17 22:52:37'),
(85, 'account.index', 'web', '2025-07-22 23:35:48', '2025-07-22 23:35:48'),
(90, 'account.update', 'web', '2025-07-22 23:36:13', '2025-07-22 23:36:13'),
(125, 'filedownload.destroy', 'web', '2025-07-25 16:48:18', '2025-07-25 16:48:18'),
(130, 'filedownload.index', 'web', '2025-07-25 16:48:48', '2025-07-25 16:48:48'),
(135, 'filedownload.store', 'web', '2025-07-25 16:49:09', '2025-07-25 16:49:09'),
(140, 'filedownload.update', 'web', '2025-07-25 16:49:23', '2025-07-25 16:49:23'),
(146, 'vendor.index', 'web', '2025-09-14 12:04:29', '2026-09-01 14:30:03'),
(147, 'pages.create', 'web', '2025-09-17 23:27:58', '2025-09-17 23:27:58'),
(148, 'pages.destroy', 'web', '2025-09-17 23:28:08', '2025-09-17 23:28:08'),
(149, 'pages.index', 'web', '2025-09-17 23:28:19', '2025-09-17 23:28:19'),
(150, 'pages.store', 'web', '2025-09-17 23:28:28', '2025-09-17 23:28:28'),
(151, 'pages.update', 'web', '2025-09-17 23:28:36', '2025-09-17 23:28:36'),
(152, 'vendor.destroy', 'web', '2025-09-18 01:25:31', '2026-09-01 14:29:43'),
(153, 'vendor.store', 'web', '2025-09-18 01:25:43', '2026-09-01 14:30:14'),
(154, 'vendor.update', 'web', '2025-09-18 01:29:03', '2026-09-01 14:30:28'),
(155, 'testimonial.destroy', 'web', '2025-09-21 21:43:09', '2025-09-21 21:43:09'),
(156, 'testimonial.index', 'web', '2025-09-21 21:43:15', '2025-09-21 21:43:15'),
(157, 'testimonial.update', 'web', '2025-09-21 21:43:32', '2025-09-21 21:43:32'),
(158, 'testimonial.store', 'web', '2025-09-21 21:43:59', '2025-09-21 21:43:59'),
(159, 'events.index', 'web', '2025-10-20 08:44:13', '2026-08-30 19:30:03'),
(160, 'events.destroy', 'web', '2025-10-20 08:46:31', '2026-08-30 19:29:50'),
(161, 'events.store', 'web', '2025-10-20 08:47:29', '2026-08-30 19:30:13'),
(162, 'events.update', 'web', '2025-10-20 08:48:18', '2026-08-30 19:30:24'),
(163, 'vendors_categories.index', 'web', '2025-11-11 03:38:59', '2026-08-27 17:28:55'),
(164, 'vendors_categories.destroy', 'web', '2025-11-11 03:39:23', '2026-08-27 17:28:42'),
(165, 'vendors_categories.update', 'web', '2025-11-11 03:39:36', '2026-08-27 17:29:13'),
(166, 'vendors_categories.store', 'web', '2025-11-11 03:39:47', '2026-08-27 17:29:05'),
(167, 'package.destroy', 'web', '2026-09-02 04:53:13', '2026-09-02 04:54:21'),
(168, 'package.index', 'web', '2026-09-02 04:53:33', '2026-09-02 04:53:33'),
(169, 'package.update', 'web', '2026-09-02 04:53:48', '2026-09-02 04:53:48'),
(170, 'package.store', 'web', '2026-09-02 04:55:05', '2026-09-02 04:55:05'),
(171, 'package.member', 'web', '2026-09-02 05:46:57', '2026-09-02 05:46:57'),
(172, 'classes.destroy', 'web', '2026-09-02 14:45:03', '2026-09-02 14:45:03'),
(173, 'classes.index', 'web', '2026-09-02 14:45:18', '2026-09-02 14:45:18'),
(174, 'classes.store', 'web', '2026-09-02 14:45:44', '2026-09-02 14:45:44'),
(175, 'classes.update', 'web', '2026-09-02 14:45:59', '2026-09-02 14:45:59'),
(176, 'dashboard.submitSumber', 'web', '2026-09-04 07:15:06', '2026-09-04 07:15:06'),
(177, 'classes.create', 'web', '2026-09-05 23:52:57', '2026-09-05 23:52:57'),
(178, 'classes.edit', 'web', '2026-09-05 23:53:12', '2026-09-05 23:53:12'),
(179, 'classes.change-level', 'web', '2026-09-06 00:43:33', '2026-09-06 00:45:13'),
(180, 'class-schedules.destroy', 'web', '2026-09-06 03:36:48', '2026-09-06 03:36:48'),
(181, 'class-schedules.index', 'web', '2026-09-06 03:37:03', '2026-09-06 03:37:03'),
(182, 'class-schedules.store', 'web', '2026-09-06 03:37:18', '2026-09-06 03:37:18'),
(183, 'class-schedules.update', 'web', '2026-09-06 03:37:32', '2026-09-06 03:37:32'),
(184, 'class-schedules.print', 'web', '2026-09-06 03:50:48', '2026-09-06 03:50:48'),
(185, 'pengguna.index', 'web', '2026-09-06 20:38:51', '2026-09-06 20:38:51'),
(186, 'pengguna.export', 'web', '2026-09-06 20:39:08', '2026-09-06 20:39:08'),
(187, 'venues.index', 'web', '2026-09-08 06:29:57', '2026-09-08 06:29:57'),
(188, 'venues.destroy', 'web', '2026-09-08 06:30:24', '2026-09-08 06:30:24'),
(189, 'venues.create', 'web', '2026-09-08 06:30:57', '2026-09-08 06:30:57'),
(190, 'venues.store', 'web', '2026-09-08 06:31:17', '2026-09-08 06:31:17'),
(191, 'venues.update', 'web', '2026-09-08 06:31:34', '2026-09-08 06:31:34'),
(192, 'vendor.mobile', 'web', '2026-09-12 06:49:00', '2026-09-12 06:49:00'),
(193, 'checkout.package', 'web', '2026-09-12 15:39:44', '2026-09-12 15:39:44'),
(194, 'orders.index', 'web', '2026-09-13 13:23:41', '2026-09-13 13:23:41'),
(195, 'orders.show', 'web', '2026-09-13 13:23:57', '2026-09-13 13:23:57'),
(196, 'orders.store', 'web', '2026-09-13 13:24:11', '2026-09-13 13:24:11'),
(197, 'menu.keamanan', 'web', '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(198, 'login-activity.index', 'web', '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(199, 'login-activity.destroy', 'web', '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(200, 'login-lockout.index', 'web', '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(201, 'login-lockout.destroy', 'web', '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(202, 'audit-log.index', 'web', '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(203, 'audit-log.destroy', 'web', '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(204, 'failed-login.index', 'web', '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(205, 'failed-login.destroy', 'web', '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(206, 'website-identity.index', 'web', '2026-10-03 12:18:10', '2026-10-03 12:18:10'),
(207, 'website-identity.update', 'web', '2026-10-03 12:18:10', '2026-10-03 12:18:10'),
(208, 'memberships.index', 'web', '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(209, 'memberships.show', 'web', '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(210, 'class-bookings.index', 'web', '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(211, 'class-bookings.store', 'web', '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(212, 'class-bookings.checkin', 'web', '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(213, 'class-bookings.cancel', 'web', '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(214, 'orders.approve', 'web', '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(215, 'orders.reject', 'web', '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(216, 'orders.proof', 'web', '2026-10-04 02:31:55', '2026-10-04 02:31:55'),
(217, 'konsultasi.index', 'web', '2026-11-20 05:26:55', '2026-11-20 05:26:55');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

CREATE TABLE `polls` (
  `uuid` char(36) NOT NULL,
  `question` varchar(255) NOT NULL,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`options`)),
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `polls`
--

INSERT INTO `polls` (`uuid`, `question`, `options`, `status`, `created_at`, `updated_at`) VALUES
('022cc9a2-dd4a-4dd2-8063-391ae8831214', 'Bagaimana menurut Anda terkait informasi yang tersedia pada website kami?', '[\"Baik\",\"Cukup Baik\",\"Kurang\"]', 'active', '2025-11-11 06:16:57', '2025-11-11 06:16:57');

-- --------------------------------------------------------

--
-- Table structure for table `poll_votes`
--

CREATE TABLE `poll_votes` (
  `uuid` char(36) NOT NULL,
  `poll_uuid` char(36) NOT NULL,
  `option` varchar(255) NOT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(2, 'user', 'web', '2024-09-26 23:27:05', '2024-09-26 23:27:05'),
(3, 'admin', 'web', '2024-09-27 01:40:58', '2024-09-27 01:40:58'),
(5, 'vendor', 'web', '2025-07-21 13:29:16', '2026-09-01 14:24:16');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 3),
(2, 1),
(3, 1),
(3, 3),
(4, 1),
(4, 2),
(4, 3),
(4, 5),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(13, 3),
(14, 1),
(14, 3),
(15, 1),
(15, 3),
(16, 1),
(16, 3),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(29, 3),
(30, 1),
(30, 3),
(31, 1),
(31, 3),
(32, 1),
(32, 3),
(33, 1),
(33, 3),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(38, 3),
(39, 1),
(39, 3),
(40, 1),
(40, 3),
(41, 1),
(41, 3),
(46, 1),
(46, 3),
(47, 1),
(47, 3),
(48, 1),
(48, 3),
(49, 1),
(49, 3),
(50, 1),
(50, 3),
(51, 1),
(51, 3),
(51, 5),
(52, 1),
(52, 3),
(52, 5),
(53, 1),
(53, 3),
(53, 5),
(54, 1),
(54, 3),
(54, 5),
(55, 1),
(55, 2),
(55, 3),
(55, 5),
(60, 1),
(60, 3),
(65, 1),
(65, 3),
(70, 1),
(70, 3),
(75, 1),
(75, 3),
(85, 1),
(85, 2),
(85, 3),
(85, 5),
(90, 1),
(90, 2),
(90, 3),
(90, 5),
(130, 2),
(130, 5),
(146, 1),
(146, 3),
(147, 1),
(147, 3),
(148, 1),
(148, 3),
(149, 1),
(149, 3),
(150, 1),
(150, 3),
(151, 1),
(151, 3),
(152, 1),
(152, 3),
(153, 1),
(153, 3),
(154, 1),
(154, 3),
(155, 1),
(155, 3),
(156, 1),
(156, 3),
(157, 1),
(157, 3),
(158, 1),
(158, 3),
(159, 1),
(159, 3),
(160, 1),
(160, 3),
(161, 1),
(161, 3),
(162, 1),
(162, 3),
(163, 1),
(163, 3),
(164, 1),
(164, 3),
(165, 1),
(165, 3),
(166, 1),
(166, 3),
(167, 1),
(167, 3),
(168, 1),
(168, 3),
(169, 1),
(169, 3),
(170, 1),
(170, 3),
(171, 1),
(171, 2),
(171, 3),
(172, 1),
(172, 3),
(172, 5),
(173, 1),
(173, 3),
(173, 5),
(174, 1),
(174, 3),
(174, 5),
(175, 1),
(175, 3),
(175, 5),
(176, 1),
(176, 2),
(177, 1),
(177, 3),
(177, 5),
(178, 1),
(178, 3),
(178, 5),
(179, 1),
(179, 3),
(180, 1),
(180, 3),
(181, 1),
(181, 2),
(181, 3),
(181, 5),
(182, 1),
(182, 3),
(183, 1),
(183, 3),
(184, 1),
(184, 2),
(184, 3),
(184, 5),
(185, 1),
(185, 3),
(186, 1),
(186, 3),
(187, 1),
(188, 1),
(189, 1),
(190, 1),
(191, 1),
(192, 1),
(192, 2),
(193, 1),
(193, 2),
(193, 3),
(194, 1),
(194, 2),
(194, 3),
(195, 1),
(195, 2),
(195, 3),
(196, 1),
(196, 2),
(196, 3),
(197, 1),
(197, 3),
(198, 1),
(198, 3),
(199, 1),
(199, 3),
(200, 1),
(200, 3),
(201, 1),
(201, 3),
(202, 1),
(202, 3),
(203, 1),
(203, 3),
(204, 1),
(204, 3),
(205, 1),
(205, 3),
(206, 1),
(206, 3),
(207, 1),
(207, 3),
(208, 1),
(208, 2),
(208, 3),
(209, 1),
(209, 2),
(209, 3),
(210, 1),
(210, 2),
(210, 3),
(211, 1),
(211, 2),
(211, 3),
(212, 1),
(212, 2),
(212, 3),
(213, 1),
(213, 2),
(213, 3),
(214, 1),
(214, 3),
(215, 1),
(215, 3),
(216, 1),
(216, 2),
(216, 3),
(217, 1),
(217, 3);

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `route` varchar(255) NOT NULL,
  `permission_name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `description` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `route`, `permission_name`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, 'dashboard.index', 'dashboard.index', 1, NULL, NULL, NULL),
(2, 'user.index', 'user.index', 1, NULL, NULL, NULL),
(3, 'user.store', 'user.store', 1, NULL, NULL, NULL),
(4, 'user.update', 'user.update', 1, NULL, NULL, NULL),
(5, 'user.destroy', 'user.destroy', 1, NULL, NULL, NULL),
(6, 'menu.index', 'menu-group.index', 1, NULL, NULL, NULL),
(7, 'menu.store', 'menu-group.store', 1, NULL, NULL, NULL),
(8, 'menu.update', 'menu-group.update', 1, NULL, NULL, NULL),
(9, 'menu.destroy', 'menu-group.destroy', 1, NULL, NULL, NULL),
(10, 'menu.item.index', 'menu-item.index', 1, NULL, NULL, NULL),
(11, 'menu.item.store', 'menu-item.store', 1, NULL, NULL, NULL),
(12, 'menu.item.update', 'menu-item.update', 1, NULL, NULL, NULL),
(13, 'menu.item.destroy', 'menu-item.destroy', 1, NULL, NULL, NULL),
(14, 'route.index', 'route.index', 1, NULL, NULL, NULL),
(15, 'route.store', 'route.store', 1, NULL, NULL, NULL),
(16, 'route.update', 'route.update', 1, NULL, NULL, NULL),
(17, 'route.destroy', 'route.destroy', 1, NULL, NULL, NULL),
(18, 'role.index', 'role.index', 1, NULL, NULL, NULL),
(19, 'role.store', 'role.store', 1, NULL, NULL, NULL),
(20, 'role.update', 'role.update', 1, NULL, NULL, NULL),
(21, 'role.destroy', 'role.destroy', 1, NULL, NULL, NULL),
(22, 'permission.index', 'permission.index', 1, NULL, NULL, NULL),
(23, 'permission.store', 'permission.store', 1, NULL, NULL, NULL),
(24, 'permission.update', 'permission.update', 1, NULL, NULL, NULL),
(25, 'permission.destroy', 'permission.destroy', 1, NULL, NULL, NULL),
(26, 'faq.index', 'faq.index', 1, NULL, '2024-09-27 00:20:45', '2024-09-27 00:20:45'),
(27, 'faq.store', 'faq.store', 1, NULL, '2024-09-27 01:45:13', '2024-09-27 01:45:13'),
(28, 'faq.update', 'faq.update', 1, NULL, '2024-09-27 01:45:36', '2024-09-27 01:45:52'),
(29, 'faq.destroy', 'faq.destroy', 1, NULL, '2024-09-27 01:46:45', '2024-09-27 01:46:45'),
(30, 'company.index', 'company.index', 1, NULL, '2024-09-27 01:53:58', '2024-09-27 01:53:58'),
(32, 'company.update', 'company.update', 1, NULL, '2024-09-27 01:55:03', '2024-09-27 01:55:09'),
(33, 'company.store', 'company.store', 1, NULL, '2024-09-27 01:55:29', '2024-09-27 01:55:29'),
(34, 'categories.destroy', 'categories.destroy', 1, NULL, '2024-09-29 12:31:56', '2024-10-15 13:51:53'),
(35, 'categories.index', 'categories.index', 1, NULL, '2024-09-29 12:32:17', '2024-10-15 13:52:11'),
(36, 'categories.store', 'categories.store', 1, NULL, '2024-09-29 12:32:37', '2024-10-15 13:52:26'),
(37, 'categories.update', 'categories.update', 1, NULL, '2024-09-29 12:32:56', '2024-10-15 13:52:45'),
(42, 'banner.destroy', 'banner.destroy', 1, NULL, '2024-09-30 12:06:31', '2024-10-13 22:36:22'),
(43, 'banner.index', 'banner.index', 1, NULL, '2024-09-30 12:06:55', '2024-10-13 22:36:39'),
(44, 'banner.store', 'banner.store', 1, NULL, '2024-09-30 12:07:34', '2024-10-13 22:37:03'),
(45, 'banner.update', 'banner.update', 1, NULL, '2024-09-30 12:07:57', '2024-10-13 22:37:24'),
(46, 'articles.create', 'articles.create', 1, NULL, '2024-10-15 14:22:17', '2024-10-15 14:22:17'),
(47, 'articles.destroy', 'articles.destroy', 1, NULL, '2024-10-15 14:22:51', '2024-10-15 14:22:51'),
(48, 'articles.index', 'articles.index', 1, NULL, '2024-10-15 14:23:15', '2024-10-15 14:23:15'),
(49, 'articles.store', 'articles.store', 1, NULL, '2024-10-15 14:23:49', '2024-10-15 14:23:49'),
(50, 'articles.update', 'articles.update', 1, NULL, '2024-10-15 14:24:09', '2024-10-15 14:24:09'),
(55, 'dashboard.submitSumber', 'dashboard.form', 1, NULL, '2025-07-21 16:35:06', '2025-07-21 16:35:06'),
(60, 'poll.destroy', 'poll.destroy', 1, NULL, '2025-07-21 17:53:17', '2025-09-17 22:55:14'),
(65, 'polls.index', 'poll.index', 1, NULL, '2025-07-21 17:53:47', '2025-09-17 22:54:20'),
(70, 'poll.store', 'poll.store', 1, NULL, '2025-07-21 17:54:16', '2025-09-17 22:55:28'),
(75, 'poll.update', 'poll.update', 1, NULL, '2025-07-21 17:54:35', '2025-09-17 22:55:42'),
(85, 'account.index', 'account.index', 1, NULL, '2025-07-22 23:36:37', '2025-07-22 23:36:37'),
(90, 'account.update', 'account.update', 1, NULL, '2025-07-22 23:37:03', '2025-07-22 23:37:03'),
(120, 'filedownload.index', 'filedownload.index', 1, NULL, '2025-07-25 16:50:03', '2025-07-25 16:50:03'),
(125, 'filedownload.destroy', 'filedownload.destroy', 1, NULL, '2025-07-25 16:50:24', '2025-07-25 16:50:24'),
(130, 'filedownload.store', 'filedownload.store', 1, NULL, '2025-07-25 16:50:51', '2025-07-25 16:50:51'),
(135, 'filedownload.update', 'filedownload.update', 1, NULL, '2025-07-25 16:51:29', '2025-07-25 16:51:29'),
(145, 'banner.update', 'banner.update', 1, NULL, '2025-08-07 12:52:06', '2025-08-07 12:52:06'),
(146, 'vendor.destroy', 'vendor.destroy', 1, NULL, '2025-09-14 12:04:52', '2026-09-01 14:31:34'),
(147, 'pages.create', 'pages.create', 1, NULL, '2025-09-17 23:29:27', '2025-09-17 23:29:27'),
(148, 'pages.index', 'pages.index', 1, NULL, '2025-09-17 23:29:41', '2025-09-17 23:29:41'),
(149, 'pages.store', 'pages.store', 1, NULL, '2025-09-17 23:29:59', '2025-09-17 23:29:59'),
(150, 'pages.destroy', 'pages.destroy', 1, NULL, '2025-09-17 23:30:31', '2025-09-17 23:30:31'),
(151, 'pages.update', 'pages.update', 1, NULL, '2025-09-17 23:30:45', '2025-09-17 23:30:45'),
(152, 'vendor.index', 'vendor.index', 1, NULL, '2025-09-18 01:27:46', '2026-09-01 14:32:00'),
(153, 'vendor.store', 'vendor.store', 1, NULL, '2025-09-18 01:27:59', '2026-09-01 14:32:18'),
(154, 'vendor.update', 'vendor.update', 1, NULL, '2025-09-18 01:31:29', '2026-09-01 14:32:46'),
(155, 'testimonial.destroy', 'testimonial.destroy', 1, NULL, '2025-09-21 21:44:13', '2025-09-21 21:44:13'),
(156, 'testimonial.index', 'testimonial.index', 1, NULL, '2025-09-21 21:44:25', '2025-09-21 21:44:25'),
(157, 'testimonial.store', 'testimonial.store', 1, NULL, '2025-09-21 21:44:40', '2025-09-21 21:44:40'),
(158, 'testimonial.update', 'testimonial.update', 1, NULL, '2025-09-21 21:44:53', '2025-09-21 21:44:53'),
(159, 'events.destroy', 'events.destroy', 1, NULL, '2025-10-21 02:47:54', '2026-08-30 19:52:08'),
(160, 'events.index', 'events.index', 1, NULL, '2025-10-21 03:15:35', '2026-08-30 19:52:34'),
(161, 'events.store', 'events.store', 1, NULL, '2025-10-21 03:15:47', '2026-08-30 19:52:53'),
(162, 'events.update', 'events.update', 1, NULL, '2025-10-21 03:15:58', '2026-08-30 19:53:11'),
(163, 'vendors_categories.destroy', 'vendors_categories.destroy', 1, NULL, '2025-11-11 03:40:24', '2026-08-27 17:32:06'),
(164, 'vendors_categories.index', 'vendors_categories.index', 1, NULL, '2025-11-11 03:40:41', '2026-08-27 17:32:27'),
(165, 'vendors_categories.store', 'vendors_categories.store', 1, NULL, '2025-11-11 03:40:58', '2026-08-27 17:32:51'),
(166, 'vendors_categories.update', 'vendors_categories.update', 1, NULL, '2025-11-11 03:42:13', '2026-08-27 17:33:20'),
(167, 'packages.destroy', 'package.destroy', 1, NULL, '2026-09-02 04:55:42', '2026-09-02 04:55:42'),
(168, 'packages.index', 'package.index', 1, NULL, '2026-09-02 04:56:09', '2026-09-02 04:56:09'),
(169, 'packages.store', 'package.store', 1, NULL, '2026-09-02 04:56:34', '2026-09-02 04:56:34'),
(170, 'packages.update', 'package.update', 1, NULL, '2026-09-02 04:57:18', '2026-09-02 04:57:18'),
(171, 'package.member', 'package.member', 1, NULL, '2026-09-02 05:47:34', '2026-09-02 05:47:34'),
(172, 'classes.destroy', 'classes.destroy', 1, NULL, '2026-09-02 15:12:54', '2026-09-02 15:12:54'),
(173, 'classes.index', 'classes.index', 1, NULL, '2026-09-02 15:13:08', '2026-09-02 15:13:08'),
(174, 'classes.update', 'classes.update', 1, NULL, '2026-09-02 15:13:24', '2026-09-02 15:13:24'),
(175, 'classes.store', 'classes.store', 1, NULL, '2026-09-02 15:13:40', '2026-09-02 15:13:40'),
(176, 'dashboard.submitSumber', 'dashboard.submitSumber', 1, NULL, '2026-09-04 07:15:41', '2026-09-04 07:15:41'),
(177, 'classes.create', 'classes.create', 1, NULL, '2026-09-05 23:56:12', '2026-09-05 23:56:12'),
(178, 'classes.edit', 'classes.edit', 1, NULL, '2026-09-05 23:56:28', '2026-09-14 11:42:35'),
(179, 'classes.change-level', 'classes.change-level', 1, NULL, '2026-09-06 00:46:10', '2026-09-06 00:46:10'),
(180, 'class-schedules.destroy', 'class-schedules.destroy', 1, NULL, '2026-09-06 03:37:54', '2026-09-06 03:37:54'),
(181, 'class-schedules.index', 'class-schedules.index', 1, NULL, '2026-09-06 03:38:11', '2026-09-06 03:38:11'),
(182, 'class-schedules.store', 'class-schedules.store', 1, NULL, '2026-09-06 03:38:30', '2026-09-06 03:38:30'),
(183, 'class-schedules.update', 'class-schedules.update', 1, NULL, '2026-09-06 03:38:48', '2026-09-06 03:38:48'),
(184, 'class-schedules.print', 'class-schedules.print', 1, NULL, '2026-09-06 03:51:13', '2026-09-06 03:51:13'),
(185, 'pengguna.index', 'pengguna.index', 1, NULL, '2026-09-06 20:39:23', '2026-09-06 20:39:23'),
(186, 'pengguna.export', 'pengguna.export', 1, NULL, '2026-09-06 20:39:35', '2026-09-06 20:39:35'),
(187, 'venues.create', 'venues.create', 1, NULL, '2026-09-08 06:32:44', '2026-09-08 06:32:44'),
(188, 'venues.index', 'venues.index', 1, NULL, '2026-09-08 06:32:59', '2026-09-08 06:32:59'),
(189, 'venues.destroy', 'venues.destroy', 1, NULL, '2026-09-08 06:33:15', '2026-09-08 06:33:15'),
(190, 'venues.update', 'venues.update', 1, NULL, '2026-09-08 06:33:33', '2026-09-08 06:33:33'),
(191, 'venues.store', 'venues.store', 1, NULL, '2026-09-08 06:33:55', '2026-09-08 06:33:55'),
(192, 'vendor.mobile', 'vendor.mobile', 1, NULL, '2026-09-12 06:49:32', '2026-09-12 06:49:32'),
(193, 'checkout.package', 'checkout.package', 1, NULL, '2026-09-12 15:40:03', '2026-09-12 15:40:03'),
(194, 'orders.index', 'orders.index', 1, NULL, '2026-09-13 13:24:29', '2026-09-13 13:24:29'),
(195, 'orders.store', 'orders.store', 1, NULL, '2026-09-13 13:24:43', '2026-09-13 13:24:43'),
(196, 'orders.show', 'orders.show', 1, NULL, '2026-09-13 13:24:58', '2026-09-13 13:24:58'),
(197, 'security.login-activity.index', 'login-activity.index', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(198, 'security.login-activity.bulkDestroy', 'login-activity.destroy', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(199, 'security.login-activity.clear', 'login-activity.destroy', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(200, 'security.login-activity.destroy', 'login-activity.destroy', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(201, 'security.login-lockout.index', 'login-lockout.index', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(202, 'security.login-lockout.bulkDestroy', 'login-lockout.destroy', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(203, 'security.login-lockout.destroy', 'login-lockout.destroy', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(204, 'security.audit-log.index', 'audit-log.index', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(205, 'security.audit-log.exportPdf', 'audit-log.index', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(206, 'security.audit-log.bulkDestroy', 'audit-log.destroy', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(207, 'security.audit-log.clear', 'audit-log.destroy', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(208, 'security.audit-log.destroy', 'audit-log.destroy', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(209, 'security.failed-login.index', 'failed-login.index', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(210, 'security.failed-login.bulkDestroy', 'failed-login.destroy', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(211, 'security.failed-login.clear', 'failed-login.destroy', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(212, 'security.failed-login.destroy', 'failed-login.destroy', 1, NULL, '2026-10-02 00:30:47', '2026-10-02 00:30:47'),
(213, 'website-identity.index', 'website-identity.index', 1, NULL, '2026-10-03 12:18:10', '2026-10-03 12:18:10'),
(214, 'website-identity.update', 'website-identity.update', 1, NULL, '2026-10-03 12:18:10', '2026-10-03 12:18:10'),
(215, 'banner.storeVideo', 'banner.store', 1, NULL, '2026-10-03 12:35:43', '2026-10-03 12:35:43'),
(216, 'banner.storeAlbum', 'banner.store', 1, NULL, '2026-10-03 12:35:43', '2026-10-03 12:35:43'),
(217, 'banner.loadMore', 'banner.index', 1, NULL, '2026-10-03 12:49:08', '2026-10-03 12:49:08'),
(218, 'banner.fotoPicker', 'banner.index', 1, NULL, '2026-10-03 12:49:08', '2026-10-03 12:49:08'),
(219, 'banner.albumFotos', 'banner.index', 1, NULL, '2026-10-03 12:49:08', '2026-10-03 12:49:08'),
(220, 'banner.modal', 'banner.index', 1, NULL, '2026-10-03 12:49:08', '2026-10-03 12:49:08'),
(221, 'banner.updateAlbum', 'banner.update', 1, NULL, '2026-10-03 12:49:08', '2026-10-03 12:49:08'),
(222, 'banner.destroyAlbum', 'banner.destroy', 1, NULL, '2026-10-03 12:49:08', '2026-10-03 12:49:08'),
(223, 'articles.bulkDestroy', 'articles.destroy', 1, NULL, '2026-10-03 13:13:45', '2026-10-03 13:13:45'),
(224, 'memberships.index', 'memberships.index', 1, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(225, 'memberships.show', 'memberships.show', 1, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(226, 'class-bookings.index', 'class-bookings.index', 1, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(227, 'class-bookings.store', 'class-bookings.store', 1, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(228, 'class-bookings.checkin', 'class-bookings.checkin', 1, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(229, 'class-bookings.cancel', 'class-bookings.cancel', 1, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(230, 'class-bookings.directCheckin', 'class-bookings.checkin', 1, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(231, 'orders.approve', 'orders.approve', 1, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(232, 'orders.reject', 'orders.reject', 1, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(233, 'orders.proof', 'orders.proof', 1, NULL, '2026-10-04 02:31:55', '2026-10-04 02:31:55'),
(234, 'orders.proof.show', 'orders.proof', 1, NULL, '2026-10-04 02:55:13', '2026-10-04 02:55:13'),
(235, 'vendor.archive', 'vendor.index', 1, NULL, '2026-11-20 04:08:23', '2026-11-20 04:08:23'),
(236, 'vendor.restore', 'vendor.update', 1, NULL, '2026-11-20 04:08:23', '2026-11-20 04:08:23'),
(237, 'konsultasi.index', 'konsultasi.index', 1, NULL, '2026-11-20 05:27:33', '2026-11-20 05:27:33'),
(238, 'konsultasi.create', 'konsultasi.index', 1, NULL, '2026-11-20 05:27:33', '2026-11-20 05:27:33'),
(239, 'konsultasi.store', 'konsultasi.index', 1, NULL, '2026-11-20 05:27:33', '2026-11-20 05:27:33'),
(240, 'konsultasi.show', 'konsultasi.index', 1, NULL, '2026-11-20 05:27:33', '2026-11-20 05:27:33'),
(241, 'konsultasi.edit', 'konsultasi.index', 1, NULL, '2026-11-20 05:27:33', '2026-11-20 05:27:33'),
(242, 'konsultasi.update', 'konsultasi.index', 1, NULL, '2026-11-20 05:27:33', '2026-11-20 05:27:33'),
(243, 'konsultasi.destroy', 'konsultasi.index', 1, NULL, '2026-11-20 05:27:33', '2026-11-20 05:27:33'),
(244, 'pengguna.update-status', 'pengguna.index', 1, NULL, '2026-11-20 05:38:38', '2026-11-20 05:38:38');

-- --------------------------------------------------------

--
-- Table structure for table `seo`
--

CREATE TABLE `seo` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` longtext DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `robots` varchar(255) DEFAULT NULL,
  `canonical_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` char(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seo`
--

INSERT INTO `seo` (`id`, `description`, `title`, `image`, `author`, `robots`, `canonical_url`, `created_at`, `updated_at`, `model_type`, `model_id`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 00:51:45', '2025-09-16 00:51:45', 'App\\Models\\Category', '0f6f80de-b410-4b7a-88b2-08411c5a063c'),
(2, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 01:03:16', '2025-09-16 01:03:16', 'App\\Models\\Category', '34103609-8116-4baf-bd17-557fc6989e8e'),
(3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 01:03:22', '2025-09-16 01:03:22', 'App\\Models\\Category', '2162d145-9ef3-4e2f-8c55-81971a015bc5'),
(4, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 01:03:27', '2025-09-16 01:03:27', 'App\\Models\\Category', 'bb006a6e-36bc-48cb-a84f-2a562489bb54'),
(5, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 01:03:50', '2025-09-16 01:03:50', 'App\\Models\\Category', 'f16ac019-a6b8-4ca6-984a-6c87624d06e9'),
(6, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 01:03:56', '2025-09-16 01:03:56', 'App\\Models\\Category', 'd9c59085-6120-487f-bd89-8924f043b70f'),
(7, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 01:04:08', '2025-09-16 01:04:08', 'App\\Models\\Category', 'cbe3bfd5-2852-4616-ad1e-e64510623354'),
(8, 'dasdsa', 'dsadas', 'images/BkDwT5z1GU7ge3jsY7cbjqxitH9GVI5bGWrVJXVf.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/dsadas', '2025-09-21 21:24:40', '2025-09-21 21:24:40', 'App\\Models\\Article', 'a7a1ddfd-e34d-4a9b-b1f1-8c3b3f980101'),
(9, 'das', 'dsad', 'images/vOOiSvvssJ0m7N5ZuDV1kqiOAlmAFT1LFkrWIVZM.png', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/dsad', '2025-09-21 23:45:55', '2025-09-21 23:45:55', 'App\\Models\\Article', '9fea3519-9e45-42b6-b0a7-4eda3d36336c'),
(10, 'Kunjungan kerja Pj Wali Kota Bekasi Gani Muhamad didampingi Istri Yolla Kusuma', 'Dedikasi Guru SLB, Pj Wali Kota Bekasi Panjatkan Syukur dan Bangga', 'images/dqG6N4LdZt2BpFYbkRYEipCXhu49cY8Hn4feSGYT.jpg', 'Admin Sekolah', 'index', 'http://103.76.148.154:8000/backend/articles/dedikasi-guru-slb-pj-wali-kota-bekasi-panjatkan-syukur-dan-bangga', '2025-09-29 08:50:56', '2025-09-29 08:50:56', 'App\\Models\\Article', '225a5ce5-27a3-4ebe-a15a-1151ea17f0b2'),
(11, 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'images/4Y8xWeFxXgrHTaGGrRR49cTzRgBbnylh8RT8pUcw.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/dorong-umkm-naik-kelas-pemkot-bekasi-gelar-sosialisasi-program-kur-bank-bjb', '2025-09-30 04:31:02', '2025-09-30 04:31:02', 'App\\Models\\Article', 'e26fc79e-6747-4852-8e1b-711cae82c811'),
(12, 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'images/4Y8xWeFxXgrHTaGGrRR49cTzRgBbnylh8RT8pUcw.jpg', 'Admin Sekolah', 'index', 'http://103.76.148.154:8000/backend/articles/dorong-umkm-naik-kelas-pemkot-bekasi-gelar-sosialisasi-program-kur-bank-bjb', '2025-09-30 04:36:50', '2025-09-30 04:36:50', 'App\\Models\\Article', 'e26fc79e-6747-4852-8e1b-711cae82c811'),
(13, 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'images/4Y8xWeFxXgrHTaGGrRR49cTzRgBbnylh8RT8pUcw.jpg', 'Admin Sekolah', 'index', 'http://103.76.148.154:8000/backend/articles/dorong-umkm-naik-kelas-pemkot-bekasi-gelar-sosialisasi-program-kur-bank-bjb', '2025-09-30 04:37:18', '2025-09-30 04:37:18', 'App\\Models\\Article', 'e26fc79e-6747-4852-8e1b-711cae82c811'),
(14, 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'images/4Y8xWeFxXgrHTaGGrRR49cTzRgBbnylh8RT8pUcw.jpg', 'Admin Sekolah', 'index', 'http://103.76.148.154:8000/backend/articles/dorong-umkm-naik-kelas-pemkot-bekasi-gelar-sosialisasi-program-kur-bank-bjb', '2025-09-30 04:37:35', '2025-09-30 04:37:35', 'App\\Models\\Article', 'e26fc79e-6747-4852-8e1b-711cae82c811'),
(15, 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'images/89rXVtDwjTG9EmdgFPogoeFPwAJIirofhidjdrEu.jpg', 'Admin Sekolah', 'index', 'http://103.76.148.154:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', '2025-09-30 04:39:50', '2025-09-30 04:39:50', 'App\\Models\\Article', 'b74e1fd1-0417-4980-b207-eb8a9901a878'),
(16, 'fsdf', 'fsdf', 'images/58jRWycX0LL0Mmk20xVxBZuLhb13b7gCtnbVFpLf.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/fsdf', '2025-09-30 04:51:15', '2025-09-30 04:51:15', 'App\\Models\\Article', 'ef525ad4-9e6b-40ea-81fd-71de34a4e3a9'),
(17, 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'images/89rXVtDwjTG9EmdgFPogoeFPwAJIirofhidjdrEu.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', '2025-09-30 05:26:51', '2025-09-30 05:26:51', 'App\\Models\\Article', 'b74e1fd1-0417-4980-b207-eb8a9901a878'),
(18, 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'images/89rXVtDwjTG9EmdgFPogoeFPwAJIirofhidjdrEu.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', '2025-09-30 05:29:34', '2025-09-30 05:29:34', 'App\\Models\\Article', 'b74e1fd1-0417-4980-b207-eb8a9901a878'),
(19, 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'images/89rXVtDwjTG9EmdgFPogoeFPwAJIirofhidjdrEu.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', '2025-09-30 05:29:45', '2025-09-30 05:29:45', 'App\\Models\\Article', 'b74e1fd1-0417-4980-b207-eb8a9901a878'),
(20, 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'images/FzjAPJph4NTicYYWBBVghzb9RwjxwfLrr8Jl6wD4.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', '2025-09-30 05:33:35', '2025-09-30 05:33:35', 'App\\Models\\Article', 'b74e1fd1-0417-4980-b207-eb8a9901a878'),
(21, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 06:23:46', '2025-10-01 06:23:46', 'App\\Models\\Category', 'e7472961-7151-4c89-bfa8-04eee74d9111'),
(22, 'Juara 3 Lomba Menyanyi', 'Dinda Nurohman', 'images/ZGZUKCAV10bEiRXlkcvW0lKT9J2n50XglYts9jWI.jpg', 'Admin Sekolah', 'index', 'http://103.76.148.154:8000/backend/articles/dinda-nurohman', '2025-10-02 02:24:30', '2025-10-02 02:24:30', 'App\\Models\\Article', 'ef525ad4-9e6b-40ea-81fd-71de34a4e3a9'),
(23, 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'images/bLT73ZQ6e8aVFwZ7hycMkyi8GmfJeuYGvVFuvoK8.jpg', 'Admin Sekolah', 'index', 'http://103.76.148.154:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', '2025-10-02 02:25:05', '2025-10-02 02:25:05', 'App\\Models\\Article', 'b74e1fd1-0417-4980-b207-eb8a9901a878'),
(24, 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'Dorong UMKM Naik Kelas, Pemkot Bekasi Gelar Sosialisasi Program KUR Bank BJB', 'images/qF6sCsDs9ffRTwq44Ofa41hrriVirZ5H47Uee1Bd.jpg', 'Admin Sekolah', 'index', 'http://103.76.148.154:8000/backend/articles/dorong-umkm-naik-kelas-pemkot-bekasi-gelar-sosialisasi-program-kur-bank-bjb', '2025-10-02 02:25:16', '2025-10-02 02:25:16', 'App\\Models\\Article', 'e26fc79e-6747-4852-8e1b-711cae82c811'),
(25, 'Juara lomba menyanyi solo berhasil diraih Adinda eko Subagio', 'Adinda Eko Subagio Juara 1 Lomba menyanyi solo', 'images/FQ4F5RMM3u0ljXS9yWYHDnXsFsPDofYnO3sqgBgV.jpg', 'Admin Sekolah', 'index', 'http://103.76.148.154:8000/backend/articles/adinda-eko-subagio-juara-1-lomba-menyanyi-solo', '2025-10-02 02:30:38', '2025-10-02 02:30:38', 'App\\Models\\Article', '3f026350-7718-43bf-bfd0-088a021e8089'),
(26, 'Juara lomba menyanyi solo berhasil diraih Adinda eko Subagio', 'Adinda Eko Subagio Juara 1 Lomba menyanyi solo', 'images/7vGyW3v4tjrT4MS8i8Alf3KVGE7mzSV3dXd7E4XC.png', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/adinda-eko-subagio-juara-1-lomba-menyanyi-solo', '2025-10-02 05:50:10', '2025-10-02 05:50:10', 'App\\Models\\Article', '3f026350-7718-43bf-bfd0-088a021e8089'),
(27, 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'Tri Adhianto dan Wiwiek Hargono Terima Anugerah Keluarga Termaslahat dari LKKNU', 'images/bLT73ZQ6e8aVFwZ7hycMkyi8GmfJeuYGvVFuvoK8.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/tri-adhianto-dan-wiwiek-hargono-terima-anugerah-keluarga-termaslahat-dari-lkknu', '2025-10-02 06:17:51', '2025-10-02 06:17:51', 'App\\Models\\Article', 'b74e1fd1-0417-4980-b207-eb8a9901a878'),
(28, 'Kunjungan kerja Pj Wali Kota Bekasi Gani Muhamad didampingi Istri Yolla Kusuma', 'Dedikasi Guru SLB, Pj Wali Kota Bekasi Panjatkan Syukur dan Bangga', 'images/dqG6N4LdZt2BpFYbkRYEipCXhu49cY8Hn4feSGYT.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/dedikasi-guru-slb-pj-wali-kota-bekasi-panjatkan-syukur-dan-bangga', '2025-10-02 06:19:06', '2025-10-02 06:19:06', 'App\\Models\\Article', '225a5ce5-27a3-4ebe-a15a-1151ea17f0b2'),
(29, 'Juara lomba menyanyi solo berhasil diraih Adinda eko Subagio', 'Adinda Eko Subagio Juara 1 Lomba menyanyi solo', 'images/pLAsYqB3RjhFGxjr9r3ym0aGIboEuOo9mQqBxmvg.jpg', 'Admin Sekolah', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/adinda-eko-subagio-juara-1-lomba-menyanyi-solo', '2025-10-16 03:16:27', '2025-10-16 03:16:27', 'App\\Models\\Article', '3f026350-7718-43bf-bfd0-088a021e8089'),
(30, 'Kebersaman pemimpin daerah dengan Anak-anak Down Syndrome', 'Wali dan Wakil Wali Kota Bekasi Nyanyi Bersama Anak Down Syndrome di CFD Kota Bekasi', 'images/ESvkOUk4fUn3QIxfGzTdnBDtqcSiQDop8TtFDYvP.jpg', 'Admin Sekolah', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/wali-dan-wakil-wali-kota-bekasi-nyanyi-bersama-anak-down-syndrome-di-cfd-kota-bekasi', '2025-10-16 03:19:35', '2025-10-16 03:19:35', 'App\\Models\\Article', '4922f25e-24ab-45d8-a7bb-ff650c55a578'),
(31, 'Wali Kota Bekasi Tri Adhianto Terima Kunjungan Edukasi Siswa SLB Patriot', 'Wali Kota Bekasi Tri Adhianto Terima Kunjungan Edukasi Siswa SLB Patriot', 'images/jjIvZ0LsGljnaJnPO0VwRRaKIIeoJlGC549B69OZ.jpg', 'super-admin', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/wali-kota-bekasi-tri-adhianto-terima-kunjungan-edukasi-siswa-slb-patriot', '2025-10-17 07:47:37', '2025-10-17 07:47:37', 'App\\Models\\Article', '66ed2a58-530d-4460-b1da-a6d41933540e'),
(32, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-31 03:07:08', '2025-10-31 03:07:08', 'App\\Models\\Category', '72561291-cdcb-484b-a556-0400fbd53c3d'),
(33, 'fsdfsdfsd', 'ffdsdsfsd', 'images/QmD0ntU3jgYsDD1rEXPWdRvz9S1wFj1Ncl1j6lNb.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/ffdsdsfsd', '2025-10-31 03:31:16', '2025-10-31 03:31:16', 'App\\Models\\Article', '1ca939a6-229f-46f2-9ef9-90f6ecf368ef'),
(34, 'fsdfsdfsd', 'ffdsdsfsd', 'images/QmD0ntU3jgYsDD1rEXPWdRvz9S1wFj1Ncl1j6lNb.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/ffdsdsfsd', '2025-10-31 03:41:18', '2025-10-31 03:41:18', 'App\\Models\\Article', '1ca939a6-229f-46f2-9ef9-90f6ecf368ef'),
(35, 'Menyanyi Solo SMPLB FLS3N Disabilitas 2025', 'SMPLB FLS3N Disabilitas 2025', 'images/GXWIGs16PIQbg7gi3yhpcQb1gg58JVu4i6QuOLIt.png', 'Admin Sekolah', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/smplb-fls3n-disabilitas-2025', '2025-10-31 06:48:45', '2025-10-31 06:48:45', 'App\\Models\\Article', '1ca939a6-229f-46f2-9ef9-90f6ecf368ef'),
(36, 'Menyanyi Solo SMPLB FLS3N Disabilitas 2025', 'SMPLB FLS3N Disabilitas 2025', 'images/GXWIGs16PIQbg7gi3yhpcQb1gg58JVu4i6QuOLIt.png', 'Admin Sekolah', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/smplb-fls3n-disabilitas-2025', '2025-11-11 08:00:48', '2025-11-11 08:00:48', 'App\\Models\\Article', '1ca939a6-229f-46f2-9ef9-90f6ecf368ef'),
(37, NULL, 'SLB Patriot Bekasi di Bawah Yayasan Dharma Wanita: Hadir untuk ABK dengan Biaya Sekolah Berkeadilan', NULL, 'Ainun Mutia Zalfina', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/slb-patriot-bekasi-di-bawah-yayasan-dharma-wanita-hadir-untuk-abk-dengan-biaya-sekolah-berkeadilan', '2026-04-17 07:08:11', '2026-04-17 07:08:11', 'App\\Models\\Article', '60dd784c-461e-4ce9-93ae-60d545e5c545'),
(38, NULL, 'SLB Patriot Bekasi di Bawah Yayasan Dharma Wanita: Hadir untuk ABK dengan Biaya Sekolah Berkeadilan', NULL, 'Ainun Mutia Zalfina', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/slb-patriot-bekasi-di-bawah-yayasan-dharma-wanita-hadir-untuk-abk-dengan-biaya-sekolah-berkeadilan', '2026-04-17 07:10:27', '2026-04-17 07:10:27', 'App\\Models\\Article', '60dd784c-461e-4ce9-93ae-60d545e5c545'),
(39, NULL, 'SLB Patriot Bekasi di Bawah Yayasan Dharma Wanita: Hadir untuk ABK dengan Biaya Sekolah Berkeadilan', 'images/8osmllekNYHfwNj1DOfoqmnaW87Cei8CcwavT0pQ.jpg', 'Admin Sekolah', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/slb-patriot-bekasi-di-bawah-yayasan-dharma-wanita-hadir-untuk-abk-dengan-biaya-sekolah-berkeadilan', '2026-05-13 09:15:26', '2026-05-13 09:15:26', 'App\\Models\\Article', '60dd784c-461e-4ce9-93ae-60d545e5c545'),
(40, NULL, 'SLB Patriot Bekasi di Bawah Yayasan Dharma Wanita: Hadir untuk ABK dengan Biaya Sekolah Berkeadilan', 'images/8osmllekNYHfwNj1DOfoqmnaW87Cei8CcwavT0pQ.jpg', 'Admin Sekolah', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/slb-patriot-bekasi-di-bawah-yayasan-dharma-wanita-hadir-untuk-abk-dengan-biaya-sekolah-berkeadilan', '2026-05-13 09:16:41', '2026-05-13 09:16:41', 'App\\Models\\Article', '60dd784c-461e-4ce9-93ae-60d545e5c545'),
(41, 'PENGUMUMAN PENERIMAAN MURID BARU (SPMB) SLB PATRIOT KOTA BEKASI - TA 2026/2027 SLB Patriot Kota Bekasi membuka kesempatan bagi anak berkebutuhan khusus (ABK)', 'SPMB (Sistem Penerimaan Murid Baru) SLB PATRIOT KOTA BEKASI', NULL, 'Ainun Mutia Zalfina', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/spmb-sistem-penerimaan-murid-baru-slb-patriot-kota-bekasi', '2026-05-13 20:51:19', '2026-05-13 20:51:19', 'App\\Models\\Article', '04df33b0-155e-41fc-a904-731c126c75d4'),
(42, 'PENGUMUMAN PENERIMAAN MURID BARU (SPMB) SLB PATRIOT KOTA BEKASI - TA 2026/2027 SLB Patriot Kota Bekasi membuka kesempatan bagi anak berkebutuhan khusus (ABK)', 'SPMB (Sistem Penerimaan Murid Baru) SLB PATRIOT KOTA BEKASI', NULL, 'Ainun Mutia Zalfina', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/spmb-sistem-penerimaan-murid-baru-slb-patriot-kota-bekasi', '2026-05-13 20:57:18', '2026-05-13 20:57:18', 'App\\Models\\Article', '04df33b0-155e-41fc-a904-731c126c75d4'),
(43, 'PENGUMUMAN PENERIMAAN MURID BARU (SPMB) SLB PATRIOT KOTA BEKASI - TA 2026/2027 SLB Patriot Kota Bekasi membuka kesempatan bagi anak berkebutuhan khusus (ABK)', 'SPMB (Sistem Penerimaan Murid Baru) SLB PATRIOT KOTA BEKASI', 'images/DhUdFwOukcm2aJMYNLkSQlOflprS55zlIS2KFK93.jpg', 'Ainun Mutia Zalfina', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/spmb-sistem-penerimaan-murid-baru-slb-patriot-kota-bekasi', '2026-05-14 01:33:57', '2026-05-14 01:33:57', 'App\\Models\\Article', '04df33b0-155e-41fc-a904-731c126c75d4'),
(44, NULL, 'Hadrah', 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'Gesik', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', '2026-06-07 23:22:19', '2026-06-07 23:22:19', 'App\\Models\\Article', 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'),
(45, NULL, 'Kegiatan ASAS (Assessment Sumatif Akhir) Genap tahun ajaran 2025/2026', 'images/VwcmPvohyFLOgGOlLLNSZwohnu5edSVzNDQIufJp.jpg', 'Gesik', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/kegiatan-asas-assessment-sumatif-akhir-genap-tahun-ajaran-20252026', '2026-06-17 20:02:59', '2026-06-17 20:02:59', 'App\\Models\\Article', '44cefb8d-2f87-4670-9bbc-631746daba9c'),
(46, 'Ekstrakurikuler Hadroh di SLB Patriot Kota Bekasi merupakan salah satu wadah pembinaan seni musik Islami dan spiritual bagi para peserta didik. Kegiatan ini dirancang khusus untuk memfasilitasi minat dan bakat siswa-siswi berkebutuhan khusus dalam seni tabuh rebana dan seni tarik suara (selawat).  Melalui pendekatan yang sabar, adaptif, dan penuh kasih sayang, ekstra kurikuler ini membuktikan bahwa keterbatasan fisik maupun kognitif bukanlah penghalang untuk menghasilkan harmoni nada yang indah dan menyentuh hati.', 'Hadrah', 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'Gesik', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', '2026-06-19 03:05:59', '2026-06-19 03:05:59', 'App\\Models\\Article', 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'),
(47, 'Ekstrakurikuler Hadroh di SLB Patriot Kota Bekasi merupakan salah satu wadah pembinaan seni musik Islami dan spiritual bagi para peserta didik. Kegiatan ini dirancang khusus untuk memfasilitasi minat dan bakat siswa-siswi berkebutuhan khusus dalam seni tabuh rebana dan seni tarik suara (selawat).  Melalui pendekatan yang sabar, adaptif, dan penuh kasih sayang, ekstra kurikuler ini membuktikan bahwa keterbatasan fisik maupun kognitif bukanlah penghalang untuk menghasilkan harmoni nada yang indah dan menyentuh hati.', 'Hadrah', 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'Gesik', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', '2026-06-19 03:19:58', '2026-06-19 03:19:58', 'App\\Models\\Article', 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'),
(48, 'Ekstrakurikuler Hadroh di SLB Patriot Kota Bekasi merupakan salah satu wadah pembinaan seni musik Islami dan spiritual bagi para peserta didik. Kegiatan ini dirancang khusus untuk memfasilitasi minat dan bakat siswa-siswi berkebutuhan khusus dalam seni tabuh rebana dan seni tarik suara (selawat).  Melalui pendekatan yang sabar, adaptif, dan penuh kasih sayang, ekstra kurikuler ini membuktikan bahwa keterbatasan fisik maupun kognitif bukanlah penghalang untuk menghasilkan harmoni nada yang indah dan menyentuh hati.', 'Hadrah', 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'Gesik', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', '2026-06-19 03:21:12', '2026-06-19 03:21:12', 'App\\Models\\Article', 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'),
(49, 'Ekstrakurikuler Hadroh di SLB Patriot Kota Bekasi merupakan salah satu wadah pembinaan seni musik Islami dan spiritual bagi para peserta didik. Kegiatan ini dirancang khusus untuk memfasilitasi minat dan bakat siswa-siswi berkebutuhan khusus dalam seni tabuh rebana dan seni tarik suara (selawat).  Melalui pendekatan yang sabar, adaptif, dan penuh kasih sayang, ekstra kurikuler ini membuktikan bahwa keterbatasan fisik maupun kognitif bukanlah penghalang untuk menghasilkan harmoni nada yang indah dan menyentuh hati.', 'Hadrah', 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'Gesik', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', '2026-06-19 03:22:51', '2026-06-19 03:22:51', 'App\\Models\\Article', 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'),
(50, NULL, 'Hadrah', 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'Gesik', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', '2026-06-19 03:23:52', '2026-06-19 03:23:52', 'App\\Models\\Article', 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'),
(51, NULL, 'Hadrah', 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'Gesik', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', '2026-06-19 03:34:22', '2026-06-19 03:34:22', 'App\\Models\\Article', 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'),
(52, NULL, 'Hadrah', 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'Gesik', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', '2026-06-19 03:36:25', '2026-06-19 03:36:25', 'App\\Models\\Article', 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'),
(53, NULL, 'Hadrah', 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'Gesik', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', '2026-06-19 03:39:38', '2026-06-19 03:39:38', 'App\\Models\\Article', 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'),
(54, NULL, 'Hadrah', 'images/pKXpNusHLfmir8RYgpkZMheYh03IhYrR39s6l96g.jpg', 'Gesik', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/hadrah', '2026-06-19 03:41:59', '2026-06-19 03:41:59', 'App\\Models\\Article', 'd5f1b860-3d91-4484-b8d3-fe463a0d72f6'),
(55, 'tes', 'tes kurikuler', 'images/6g8ACLsBH6dVRSH5HrBWY61iwAaRwYQlnCq9b6Jy.png', 'Admin Sekolah', 'index', 'https://be.slbpatriotkotabekasi.sch.id/backend/articles/tes-kurikuler', '2026-06-20 09:12:25', '2026-06-20 09:12:25', 'App\\Models\\Article', '0ce7a99d-145f-4dce-9c69-fe8be79de363'),
(56, NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-20 12:47:44', '2026-06-20 12:47:44', 'App\\Models\\Category', 'a58f5ebc-c8ec-48da-b6f9-a80b05ca40a0'),
(57, 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'images/QfojvfEegY7UFF07HkRp7xw9nsI4LZNVx92HxNfJ.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/pilates-vs-yoga-apa-bedanya-dan-mana-yang-cocok-untuk-anda', '2026-08-27 04:59:46', '2026-08-27 04:59:46', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c'),
(58, 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'images/QfojvfEegY7UFF07HkRp7xw9nsI4LZNVx92HxNfJ.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/pilates-vs-yoga-apa-bedanya-dan-mana-yang-cocok-untuk-anda', '2026-08-27 05:02:14', '2026-08-27 05:02:14', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c'),
(59, 'Pilates dan yoga sama-sama populer sebagai olahraga yang membantu meningkatkan kebugaran tubuh sekaligus memberikan manfaat bagi pikiran. Keduanya juga dapat dilakukan oleh pemula dan tidak selalu membutuhkan peralatan yang rumit.', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'images/QfojvfEegY7UFF07HkRp7xw9nsI4LZNVx92HxNfJ.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/pilates-vs-yoga-apa-bedanya-dan-mana-yang-cocok-untuk-anda', '2026-08-27 05:50:43', '2026-08-27 05:50:43', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c'),
(60, 'Pilates dan yoga sama-sama populer sebagai olahraga yang membantu meningkatkan kebugaran tubuh sekaligus memberikan manfaat bagi pikiran. Keduanya juga dapat dilakukan oleh pemula dan tidak selalu membutuhkan peralatan yang rumit.', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'images/3MeMVIEBB3e5NU9Tm1Ijda7s7iDDcrmdWakjl1C7.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/pilates-vs-yoga-apa-bedanya-dan-mana-yang-cocok-untuk-anda', '2026-08-30 18:12:39', '2026-08-30 18:12:39', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c'),
(61, 'Pilates dan yoga sama-sama populer sebagai olahraga yang membantu meningkatkan kebugaran tubuh sekaligus memberikan manfaat bagi pikiran. Keduanya juga dapat dilakukan oleh pemula dan tidak selalu membutuhkan peralatan yang rumit.', 'Pilates vs Yoga: Apa Bedanya dan Mana yang Cocok untuk Anda?', 'images/Q21BSTPfei8FVhkubCnpiCxQYWcL7P6XigMY6gBq.jpg', 'super-admin', 'index', 'http://127.0.0.1:8000/backend/articles/pilates-vs-yoga-apa-bedanya-dan-mana-yang-cocok-untuk-anda', '2026-09-01 03:03:46', '2026-09-01 03:03:46', 'App\\Models\\Article', '63829ea9-8a32-4f82-94e1-7299120f822c');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0J6FcyA0u6YvoEYpsFylqXaIsx6fsEhd66sRYEju', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiS0dzNkdJU0M5WnJPRlZUWnVWc2dGYWJyUGx6S2gzVmc3UlQ5MzBIayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hP3N1bWJlcj1nb29nbGUiO3M6NToicm91dGUiO3M6MTQ6InBlbmdndW5hLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO3M6MzY6ImZhOTIxNjAyLTE4YzktNGUxYi1hMDcwLWVhNzA1ZWNmYzQ4OCI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImM3NDllYzc5OTM4ZmY0ZmMwNmM0ODFjYTFlODE2NGJjODA5YmJlMmQzN2U5MTk1Y2YwNzJkNTRkOWQ5MDRmYmMiO3M6MTM6Imxhc3RfYWN0aXZpdHkiO2k6MTc5NTE1NTc0Nzt9', 1795155747),
('7GZfYmpqxPuvxVOxzPGAu2edQZctTpzjrd49pOJn', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiNW5pS0t6QUdnbnBtSlJ5SEFhaHRReTdQR1FhNEFnNDNjMXVYTkQyViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hIjtzOjU6InJvdXRlIjtzOjE0OiJwZW5nZ3VuYS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiJmYTkyMTYwMi0xOGM5LTRlMWItYTA3MC1lYTcwNWVjZmM0ODgiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjNzQ5ZWM3OTkzOGZmNGZjMDZjNDgxY2ExZTgxNjRiYzgwOWJiZTJkMzdlOTE5NWNmMDcyZDU0ZDlkOTA0ZmJjIjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUxNTUwNjc7fQ==', 1795155070),
('cHeKE4egaeIhQQsW8Fg7FfFuNHSWgLrBC1LU2Nz5', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoicGlYUHRHT1VxUVJ3elQ5c2RZaDdhQ015T1NaRE5aUmhhazJwUmlIUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hIjtzOjU6InJvdXRlIjtzOjE0OiJwZW5nZ3VuYS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiJmYTkyMTYwMi0xOGM5LTRlMWItYTA3MC1lYTcwNWVjZmM0ODgiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjNzQ5ZWM3OTkzOGZmNGZjMDZjNDgxY2ExZTgxNjRiYzgwOWJiZTJkMzdlOTE5NWNmMDcyZDU0ZDlkOTA0ZmJjIjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUxNTU3NTA7fQ==', 1795155750),
('euE3TNmO8jKSJWZgibxflEMpkFUhVFaXKKyq73gK', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiY2xSQkhJZGh0d3ZIRkR5VkFuSWNyMWkxdU4zYXQ2MlZXVDg2WVVFSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hIjtzOjU6InJvdXRlIjtzOjE0OiJwZW5nZ3VuYS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiJmYTkyMTYwMi0xOGM5LTRlMWItYTA3MC1lYTcwNWVjZmM0ODgiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjNzQ5ZWM3OTkzOGZmNGZjMDZjNDgxY2ExZTgxNjRiYzgwOWJiZTJkMzdlOTE5NWNmMDcyZDU0ZDlkOTA0ZmJjIjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUxNTUzODU7fQ==', 1795155385),
('fR8qerB7t65C5n2oh1A2kmod64Ru5w4WK1Nidorx', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiUzlPTTJSOGZCR01MNFE0WDV3RGdWRGl0T1hJQ2ppZWxrVEF4cnVrNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hIjtzOjU6InJvdXRlIjtzOjE0OiJwZW5nZ3VuYS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiJmYTkyMTYwMi0xOGM5LTRlMWItYTA3MC1lYTcwNWVjZmM0ODgiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjNzQ5ZWM3OTkzOGZmNGZjMDZjNDgxY2ExZTgxNjRiYzgwOWJiZTJkMzdlOTE5NWNmMDcyZDU0ZDlkOTA0ZmJjIjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUxNTUxNzc7fQ==', 1795155179),
('KaqDchQ7nWliI54O7DZHqBXbp3DKFavUbpXwNcNP', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVWhvelV0c3lRVEd6RkhnRlRTb2RMYWk3bWNNWnpGQVU4eWgxcHJBVyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2JhY2tlbmQvcGVuZ2d1bmEiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozMzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2F1dGgvZ29vZ2xlIjtzOjU6InJvdXRlIjtzOjEwOiJnb29nbGVBdXRoIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1795153955),
('L1YZJ2tZJQySXmMxmRJtnISRJxmoee0Tl5OBcU2K', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiSjJRUWdra1hEU0w3aWFaak1lcnJtUFNGNVRSbjlRN2xWeThQc01TayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hIjtzOjU6InJvdXRlIjtzOjE0OiJwZW5nZ3VuYS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiJmYTkyMTYwMi0xOGM5LTRlMWItYTA3MC1lYTcwNWVjZmM0ODgiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjNzQ5ZWM3OTkzOGZmNGZjMDZjNDgxY2ExZTgxNjRiYzgwOWJiZTJkMzdlOTE5NWNmMDcyZDU0ZDlkOTA0ZmJjIjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUxNTUxMjk7fQ==', 1795155130),
('obcaKZHjHh5Vn18Ah2w7sEcApL2lrJrxErD9G813', 'f8dbf191-7c45-4d71-8cfd-1e290747ade8', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiODk5ZzVJQ2VOakw5SURqeEJEOWl5dkZMUWlyQzhOWk9VbjBrQ2dkbyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO3M6MzY6ImY4ZGJmMTkxLTdjNDUtNGQ3MS04Y2ZkLTFlMjkwNzQ3YWRlOCI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6IjgwMjY3ZmY4ZmU3ZTIyMzEwOGQ1NzA4ZWU2NjVlYjlmMWRjN2Y0OGE2NTZmMWYwMDg2N2U3OGUzNDY5YjFjNmIiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM5OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYmFja2VuZC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImRhc2hib2FyZC5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MTM6Imxhc3RfYWN0aXZpdHkiO2k6MTc5NTE1NDAzMDtzOjExOiJtZW1iZXJfdmlldyI7czo2OiJtb2JpbGUiO30=', 1795154030),
('oOnvlSJ3QbHgoa9WBTHs3MqeC21XakHyk2kPvHRO', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiOWVVQnhoMFc4UDcwNW5NemRqdTEzN1plU00zaTBWYkVHdm94QXZXayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hIjtzOjU6InJvdXRlIjtzOjE0OiJwZW5nZ3VuYS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiJmYTkyMTYwMi0xOGM5LTRlMWItYTA3MC1lYTcwNWVjZmM0ODgiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjNzQ5ZWM3OTkzOGZmNGZjMDZjNDgxY2ExZTgxNjRiYzgwOWJiZTJkMzdlOTE5NWNmMDcyZDU0ZDlkOTA0ZmJjIjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUxNTUzODg7fQ==', 1795155388),
('QhO2ZIenBkOJVlmhTEGBOLBrWIrCCeTuvarx8F3U', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiRDlHQzF2ZG0zdGhwSGY2Nm9Rc0tZaXFodGdNbGEyaXk0aGFPYVRSZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hIjtzOjU6InJvdXRlIjtzOjE0OiJwZW5nZ3VuYS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiJmYTkyMTYwMi0xOGM5LTRlMWItYTA3MC1lYTcwNWVjZmM0ODgiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjNzQ5ZWM3OTkzOGZmNGZjMDZjNDgxY2ExZTgxNjRiYzgwOWJiZTJkMzdlOTE5NWNmMDcyZDU0ZDlkOTA0ZmJjIjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUxNTUyOTc7fQ==', 1795155297),
('R5gCSo0pmHwdlb9dL6hti232C9ElbIc9hArA9cC1', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiUllnRnBzOVk1OHFZQ21ldDZQZ2lOM2RXREpGTlhBc3VFQ3FHbFpNQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hP3N1bWJlcj1nb29nbGUiO3M6NToicm91dGUiO3M6MTQ6InBlbmdndW5hLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO3M6MzY6ImZhOTIxNjAyLTE4YzktNGUxYi1hMDcwLWVhNzA1ZWNmYzQ4OCI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImM3NDllYzc5OTM4ZmY0ZmMwNmM0ODFjYTFlODE2NGJjODA5YmJlMmQzN2U5MTk1Y2YwNzJkNTRkOWQ5MDRmYmMiO3M6MTM6Imxhc3RfYWN0aXZpdHkiO2k6MTc5NTE1NTY5Mjt9', 1795155692),
('rLMbVbwgtn6fALghgZaG60lPZPU1Ncl5n5sSs4aS', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiZk12M0taY004Y0tkSE91dVdKU3lzTm1MUlRSbTdTREl0QXJpb0p1WSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hIjtzOjU6InJvdXRlIjtzOjE0OiJwZW5nZ3VuYS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiJmYTkyMTYwMi0xOGM5LTRlMWItYTA3MC1lYTcwNWVjZmM0ODgiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjNzQ5ZWM3OTkzOGZmNGZjMDZjNDgxY2ExZTgxNjRiYzgwOWJiZTJkMzdlOTE5NWNmMDcyZDU0ZDlkOTA0ZmJjIjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUxNTU0MTc7fQ==', 1795155417),
('S7Pz34LbaLsKlyWCK518BMfTzO93mkzbSt0WXHHf', '787b72ea-59d0-4d54-848b-c200bddafdd2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiNlZwN0hsVWVNMFF4bmhjdnBmcjZOVXpiNEZQdEd4dnU1NXdWQjBnRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hIjtzOjU6InJvdXRlIjtzOjE0OiJwZW5nZ3VuYS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiI3ODdiNzJlYS01OWQwLTRkNTQtODQ4Yi1jMjAwYmRkYWZkZDIiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjZmM1YmIwMThkYmVhMmVmZTVjZTQ4YmFiYzVlODEyNTU5NDBmMGEyODZkNzc3MmE1YzVlODNjZWIyZmMyYmM5IjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUxNTU3Mjk7fQ==', 1795155729),
('x8F8mAZAZ5jcRLC49QxNaQHAmYSnHIOxjJ6pcs8W', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVk8xVGJXOHVibWtONEZIRmdDY1pTNzFkTmtMUnZlNFZBS2pSTnJudyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hIjtzOjU6InJvdXRlIjtzOjE0OiJwZW5nZ3VuYS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiJmYTkyMTYwMi0xOGM5LTRlMWItYTA3MC1lYTcwNWVjZmM0ODgiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjNzQ5ZWM3OTkzOGZmNGZjMDZjNDgxY2ExZTgxNjRiYzgwOWJiZTJkMzdlOTE5NWNmMDcyZDU0ZDlkOTA0ZmJjIjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUxNTU2OTg7fQ==', 1795155698),
('YgjaQGPdTDhyKGPTr1Jj0RPClACdNKLHmP4e4Iud', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoia0pVdVRnVGhpUWt6QlZURVRDZjdMS2hVVzJWZTZzcUEzajdTVEY0eCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hIjtzOjU6InJvdXRlIjtzOjE0OiJwZW5nZ3VuYS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiJmYTkyMTYwMi0xOGM5LTRlMWItYTA3MC1lYTcwNWVjZmM0ODgiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjNzQ5ZWM3OTkzOGZmNGZjMDZjNDgxY2ExZTgxNjRiYzgwOWJiZTJkMzdlOTE5NWNmMDcyZDU0ZDlkOTA0ZmJjIjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUxNTU0MTM7fQ==', 1795155413),
('ZwyV9nARgm0EkKAgniKqJz5hGlQhiKO9WWULfsyl', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiYjhuaXFxTHJKdFdsNVJzRGhPYnhmTjFmM0VGb3h2a1Jhc21xUVFjTyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hIjtzOjU6InJvdXRlIjtzOjE0OiJwZW5nZ3VuYS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiJmYTkyMTYwMi0xOGM5LTRlMWItYTA3MC1lYTcwNWVjZmM0ODgiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjNzQ5ZWM3OTkzOGZmNGZjMDZjNDgxY2ExZTgxNjRiYzgwOWJiZTJkMzdlOTE5NWNmMDcyZDU0ZDlkOTA0ZmJjIjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUxNTUyOTE7fQ==', 1795155291),
('ZY0h3MPND8G5gTprIekE3wEggKlsapa10gjK0zv8', 'fa921602-18c9-4e1b-a070-ea705ecfc488', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/157.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiaFA1cGhneFhxMTFFR2VyTUhhaERQNEtxbjdUSkZtc3RCS3NRazY4RyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9iYWNrZW5kL3BlbmdndW5hIjtzOjU6InJvdXRlIjtzOjE0OiJwZW5nZ3VuYS5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiJmYTkyMTYwMi0xOGM5LTRlMWItYTA3MC1lYTcwNWVjZmM0ODgiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjNzQ5ZWM3OTkzOGZmNGZjMDZjNDgxY2ExZTgxNjRiYzgwOWJiZTJkMzdlOTE5NWNmMDcyZDU0ZDlkOTA0ZmJjIjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUxNTUyMjc7fQ==', 1795155228);

-- --------------------------------------------------------

--
-- Table structure for table `site_visits`
--

CREATE TABLE `site_visits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `visitor_hash` char(64) NOT NULL,
  `path` varchar(255) NOT NULL,
  `device` varchar(16) NOT NULL,
  `visited_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_visits`
--

INSERT INTO `site_visits` (`id`, `visitor_hash`, `path`, `device`, `visited_at`, `created_at`, `updated_at`) VALUES
(1, '752ff8a6f571a30b30a3d1ca473845e8e576501b75e846f851e8c97f3fccde65', '/', 'desktop', '2026-11-18 21:33:26', '2026-11-18 21:33:26', '2026-11-18 21:33:26'),
(2, '752ff8a6f571a30b30a3d1ca473845e8e576501b75e846f851e8c97f3fccde65', '/', 'desktop', '2026-11-18 21:35:44', '2026-11-18 21:35:44', '2026-11-18 21:35:44'),
(3, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/', 'desktop', '2026-11-18 21:44:04', '2026-11-18 21:44:04', '2026-11-18 21:44:04'),
(4, '5bb1f96e8875d23ef0416933d8918bf667d1661c271a459c3a93f1407284c56e', '/', 'desktop', '2026-11-18 21:47:16', '2026-11-18 21:47:16', '2026-11-18 21:47:16'),
(5, 'bd8bcd1cfab05d9cd40a5b3b90d16c155a5bf5a35fb4ab847ad69a10e49a8cbb', '/', 'desktop', '2026-11-18 21:47:42', '2026-11-18 21:47:42', '2026-11-18 21:47:42'),
(6, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/schedules', 'desktop', '2026-11-18 21:48:05', '2026-11-18 21:48:05', '2026-11-18 21:48:05'),
(7, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/packages', 'desktop', '2026-11-18 21:48:06', '2026-11-18 21:48:06', '2026-11-18 21:48:06'),
(8, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/instructors', 'desktop', '2026-11-18 21:48:07', '2026-11-18 21:48:07', '2026-11-18 21:48:07'),
(9, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/instructors', 'desktop', '2026-11-18 21:48:08', '2026-11-18 21:48:08', '2026-11-18 21:48:08'),
(10, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/art-of-living', 'desktop', '2026-11-18 21:48:08', '2026-11-18 21:48:08', '2026-11-18 21:48:08'),
(11, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/instructors', 'desktop', '2026-11-18 21:48:09', '2026-11-18 21:48:09', '2026-11-18 21:48:09'),
(12, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/', 'desktop', '2026-11-18 21:48:10', '2026-11-18 21:48:10', '2026-11-18 21:48:10'),
(13, '8cedc52fcfba44b9fccffcb2f635e282e5e3f860a0aea086b213d7a0aa3f421e', '/', 'desktop', '2026-11-18 21:48:47', '2026-11-18 21:48:47', '2026-11-18 21:48:47'),
(14, 'dd63b1720d1de144c8bb05c678eb5b1d8373a8ec9475c1b11efebdfca3979d8b', '/', 'desktop', '2026-11-18 22:01:48', '2026-11-18 22:01:48', '2026-11-18 22:01:48'),
(15, '1faa21538d02fcb4d4aeae1e400ef663648f1f99c63400bb62a48510886fc217', '/event', 'desktop', '2026-11-18 22:01:48', '2026-11-18 22:01:48', '2026-11-18 22:01:48'),
(16, '014cc3dd456941dbf2f74a207f97e2519d914526edff1561f40843c0b04db5fb', '/', 'desktop', '2026-11-18 22:33:07', '2026-11-18 22:33:07', '2026-11-18 22:33:07'),
(17, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/', 'desktop', '2026-11-18 22:33:58', '2026-11-18 22:33:58', '2026-11-18 22:33:58'),
(18, 'c2b6b9ac5572b2bbac0f85c27ae7dca93404c4406a13a647b6b67fed6cae8fed', '/event', 'desktop', '2026-11-18 22:34:28', '2026-11-18 22:34:28', '2026-11-18 22:34:28'),
(19, '5c73e6b733ac00031ae5889eedde3bea074c3f9aab948e16994a610e4afa4955', '/event', 'desktop', '2026-11-18 22:34:28', '2026-11-18 22:34:28', '2026-11-18 22:34:28'),
(20, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/', 'desktop', '2026-11-18 22:46:33', '2026-11-18 22:46:33', '2026-11-18 22:46:33'),
(21, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/', 'desktop', '2026-11-18 22:46:41', '2026-11-18 22:46:41', '2026-11-18 22:46:41'),
(22, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/', 'desktop', '2026-11-18 22:56:36', '2026-11-18 22:56:36', '2026-11-18 22:56:36'),
(23, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/', 'desktop', '2026-11-18 23:00:37', '2026-11-18 23:00:37', '2026-11-18 23:00:37'),
(24, '1e117a1e4043869d6eef03f660b49423f487671fc0b787173fed08340aaf96e7', '/', 'desktop', '2026-11-18 23:01:04', '2026-11-18 23:01:04', '2026-11-18 23:01:04'),
(25, 'e160f1edfcea1590a359a466578d2d18995eea6c9cc79f9ab1049265d804f217', '/', 'desktop', '2026-11-20 01:04:57', '2026-11-20 01:04:57', '2026-11-20 01:04:57'),
(26, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 01:05:59', '2026-11-20 01:05:59', '2026-11-20 01:05:59'),
(27, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/classes', 'desktop', '2026-11-20 01:06:56', '2026-11-20 01:06:56', '2026-11-20 01:06:56'),
(28, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/schedules', 'desktop', '2026-11-20 01:06:57', '2026-11-20 01:06:57', '2026-11-20 01:06:57'),
(29, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/schedules', 'desktop', '2026-11-20 01:06:59', '2026-11-20 01:06:59', '2026-11-20 01:06:59'),
(30, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/packages', 'desktop', '2026-11-20 01:07:00', '2026-11-20 01:07:00', '2026-11-20 01:07:00'),
(31, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/instructors', 'desktop', '2026-11-20 01:07:01', '2026-11-20 01:07:01', '2026-11-20 01:07:01'),
(32, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/art-of-living', 'desktop', '2026-11-20 01:07:02', '2026-11-20 01:07:02', '2026-11-20 01:07:02'),
(33, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 01:07:05', '2026-11-20 01:07:05', '2026-11-20 01:07:05'),
(34, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 02:11:11', '2026-11-20 02:11:11', '2026-11-20 02:11:11'),
(35, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/instructors', 'desktop', '2026-11-20 02:11:13', '2026-11-20 02:11:13', '2026-11-20 02:11:13'),
(36, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/art-of-living', 'desktop', '2026-11-20 02:11:14', '2026-11-20 02:11:14', '2026-11-20 02:11:14'),
(37, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 02:12:01', '2026-11-20 02:12:01', '2026-11-20 02:12:01'),
(38, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'desktop', '2026-11-20 02:14:23', '2026-11-20 02:14:23', '2026-11-20 02:14:23'),
(39, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'desktop', '2026-11-20 02:14:31', '2026-11-20 02:14:31', '2026-11-20 02:14:31'),
(40, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 02:14:32', '2026-11-20 02:14:32', '2026-11-20 02:14:32'),
(41, '073d4e07c56884784999e56406e63beded0549992f27a2cd06e0b04f77f6fda3', '/faq', 'desktop', '2026-11-20 02:24:07', '2026-11-20 02:24:07', '2026-11-20 02:24:07'),
(42, '3ea4dc6e9a2e752256657c33530f1b0bf8a8335793c91f1f83240946f94d2fc4', '/faq', 'desktop', '2026-11-20 02:25:58', '2026-11-20 02:25:58', '2026-11-20 02:25:58'),
(43, 'b427c94ea17ed16c4c0916ba0cb52dc74a34a817c40f32a86930cdb180cd5bc5', '/faq', 'desktop', '2026-11-20 02:26:08', '2026-11-20 02:26:08', '2026-11-20 02:26:08'),
(44, '7069c09779acb358e5f59b58bf3ca813c5cb2c03c01c8ebe2f28fc7833d64f68', '/faq', 'desktop', '2026-11-20 02:26:08', '2026-11-20 02:26:08', '2026-11-20 02:26:08'),
(45, '06eaf5c90a737689d9584326b6f0200114ccc4f69dbec89fc9d5cfc3efdf3b25', '/', 'desktop', '2026-11-20 02:26:15', '2026-11-20 02:26:15', '2026-11-20 02:26:15'),
(46, '123efbfdb9dc1cdde3670c39b23a9f211a812e8496b9acb98a1c9c0428a11041', '/', 'desktop', '2026-11-20 02:26:21', '2026-11-20 02:26:21', '2026-11-20 02:26:21'),
(47, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/faq', 'desktop', '2026-11-20 02:27:17', '2026-11-20 02:27:17', '2026-11-20 02:27:17'),
(48, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/faq', 'desktop', '2026-11-20 02:27:27', '2026-11-20 02:27:27', '2026-11-20 02:27:27'),
(49, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/faq', 'desktop', '2026-11-20 02:27:32', '2026-11-20 02:27:32', '2026-11-20 02:27:32'),
(50, '342a29bc7b7701da061f82f37b2d72948015158d6fc929ac53b9cc301ec02f3e', '/faq', 'desktop', '2026-11-20 02:30:16', '2026-11-20 02:30:16', '2026-11-20 02:30:16'),
(51, 'c46377fff53f0b5affcd29391121d00fbf133c30b07dc4111d29f36641d51092', '/faq', 'desktop', '2026-11-20 02:30:16', '2026-11-20 02:30:16', '2026-11-20 02:30:16'),
(52, 'f499ecb77852795f7c378368a51d098aa4fb86e5ee84f0a8bfa1be56e6824340', '/faq', 'desktop', '2026-11-20 02:30:33', '2026-11-20 02:30:33', '2026-11-20 02:30:33'),
(53, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/faq', 'desktop', '2026-11-20 03:31:03', '2026-11-20 03:31:03', '2026-11-20 03:31:03'),
(54, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/faq', 'desktop', '2026-11-20 03:31:41', '2026-11-20 03:31:41', '2026-11-20 03:31:41'),
(55, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 03:32:05', '2026-11-20 03:32:05', '2026-11-20 03:32:05'),
(56, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/faq', 'desktop', '2026-11-20 03:34:22', '2026-11-20 03:34:22', '2026-11-20 03:34:22'),
(57, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/contact', 'desktop', '2026-11-20 03:34:24', '2026-11-20 03:34:24', '2026-11-20 03:34:24'),
(58, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/contact', 'desktop', '2026-11-20 05:20:26', '2026-11-20 05:20:26', '2026-11-20 05:20:26'),
(59, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 05:20:27', '2026-11-20 05:20:27', '2026-11-20 05:20:27');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `uuid` char(36) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `isi_testimoni` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `is_active` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`uuid`, `nama`, `jabatan`, `isi_testimoni`, `foto`, `urutan`, `is_active`, `created_at`, `updated_at`) VALUES
('267d9987-ea8e-422a-a32d-a26e7e9cea0f', 'Fhadillah Cherly Yunanda', 'Member Since 2024', 'Best yoga place in jkt so far! Very clean, very nice lighting with natural sunlight, and the most important thing - so engaging instructor! Will be back offf course!!!', 'testimoni/ObU2uLK401gA8ZvTKHaLiWZd9Ve4AVloUD1T15PJ.jpg', 2, 'active', '2026-09-11 00:21:17', '2026-09-11 00:21:53'),
('8a7bbb78-eb33-4f1b-941c-cbdcd2b9c5a3', 'Jessica Gloria Mogi', 'Member Since 2024', 'The studio was beautiful, plenty of natural sunlight, it was not stuffy at all. They have a minimalist shower with some skincare products and medium-sized lockers. It\'s a great experience and I\'m coming back tomorrow :)', 'testimoni/6HcmtKnmhk7z2RoV1BkNkQSBo1xrbCbGCC0LBzvx.jpg', 1, 'active', '2026-09-11 00:19:26', '2026-09-11 00:19:44'),
('dcfd5ae2-e3a0-4c4b-a3c4-937af0a086d7', 'Melda Auditia', 'Member Since 2025', 'Amazing experience. Great instructor. Space is quiet, beautiful & clean, with nice shower room for yoga before work. Would definitely come back again👍🏻', 'testimoni/B4JRMECCVIhAeVGY9yo8duoZzS6l4njN4IknQ7Mu.jpg', 3, 'active', '2026-09-11 00:22:56', '2026-09-11 00:23:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `kecamatan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kelurahan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `agama` enum('Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya') DEFAULT NULL,
  `pengalaman` varchar(255) DEFAULT NULL,
  `is_active` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `tiktok` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `biografi` text DEFAULT NULL,
  `package_uuid` char(36) DEFAULT NULL,
  `sumber_informasi` enum('google','sosmed','friend','community','event','website','other') DEFAULT NULL,
  `prospect_status` enum('new_registered','interested','consultation','negotiation','customer','inactive') NOT NULL DEFAULT 'new_registered',
  `prospect_status_updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `uuid`, `name`, `no_hp`, `alamat`, `kecamatan_id`, `kelurahan_id`, `avatar`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `google_id`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `pengalaman`, `is_active`, `deleted_at`, `facebook`, `instagram`, `twitter`, `tiktok`, `youtube`, `biografi`, `package_uuid`, `sumber_informasi`, `prospect_status`, `prospect_status_updated_at`) VALUES
(1, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', '6281321221189', NULL, NULL, NULL, 'avatars/9PmebLAmtzYHiguvCksa3jhkQXhIDZwzeOzA8Lkv.jpg', 'stella@yogaroots.id', '2026-09-10 19:16:05', '$2y$12$XTedTKKKf5hI8GCp6n9KQ.rMl9po5GSegd8GVucZUkef3lXRUHG1y', NULL, '2026-09-10 19:16:05', '2026-09-10 19:20:42', NULL, NULL, 'dasd', '2026-09-11', 'P', 'Lainnya', '5 years', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'With a practice spanning nearly three decades, Stella brings depth, versatility, and a deeply inclusive spirit to her teaching. Her Vinyasa classes flow with clarity and purpose, while her extensive training allows her to thoughtfully adapt yoga for different bodies, ages, and needs—from beginners and children to seniors, prenatal students, and those working with specific conditions. She believes yoga is ultimately about more than the body; it is a practice that can create meaningful and lasting change from within. Having first stepped onto a yoga mat in Jakarta in 1998, Stella has witnessed the practice evolve—and continues to share it with the same curiosity and dedication.', NULL, NULL, 'new_registered', NULL),
(2, '4a85c36a-caa6-4cc9-9ab6-47f77c0a9a67', 'Andrea', '6281321221270', NULL, NULL, NULL, 'avatars/NVMVFyR5dLWAggqVdWc1fMVclwK346qZrgu14qMo.png', 'andrea@yogaroots.id', '2026-09-10 19:18:50', '$2y$12$VOU976RIB.YtryiO5x/FLOUT3t4Y1F.lj157lKfmsfniGJdEuTrYq', NULL, '2026-09-10 19:18:50', '2026-09-10 19:20:27', NULL, NULL, 'dsad', '2026-09-11', 'P', 'Lainnya', '4 years', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Andrea’s yoga journey began as a personal quest to navigate life’s everyday pressures in her career as a researcher. She has experienced firsthand how mindfulness can nurture inner peace and a more compassionate, purpose-driven life—both on and off the mat.', NULL, NULL, 'new_registered', NULL),
(3, '57621d3c-c299-4cd2-b96a-9b887752cb73', 'Yogaroots.id', '85691333321', NULL, NULL, NULL, NULL, 'tapayoga@yogaroots.id', '2026-10-04 12:08:07', '$2y$12$vgbZekTedJzp2ClDwMQrTOv4QFSMZuQBWukJ23jHyIwSfHx47jbNS', NULL, '2026-09-06 04:46:09', '2026-10-04 12:08:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sosmed', 'new_registered', NULL),
(4, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', '085691333321', 'jalan gandaria selatan', NULL, NULL, 'avatars/r5YzPXmlkmO6PaSdbPUjr1dL5J9LoYUYVu5eWW9v.jpg', 'wikupb@gmail.com', '2026-09-04 07:49:02', '$2y$12$WXO13gY21gOqgS1s7M743.Oe5UdAxQDykEbmTf0xJ2LnyNdPMzsj6', NULL, '2025-11-12 03:39:36', '2026-10-06 10:06:14', '112161865449406232159', NULL, 'Pati', '1992-03-30', 'L', 'Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '01a06234-aac1-722c-84d6-c46d4150e70f', 'website', 'new_registered', NULL),
(5, '755e0eaa-50e4-46db-9003-7c1fc6674876', 'Margaretta', NULL, NULL, NULL, NULL, 'avatars/5qVq34VWSWBHYl4FgxzeSZqIEYRFotuSvA6qswLA.jpg', 'margaretta@yogaroots.id', '2026-09-10 19:09:33', '$2y$12$QxXHeDYBv0huosR6U1kUEe2Hq4uDAsdqJlo98wJhBbMiYRpAeZqKu', NULL, '2026-09-10 19:09:33', '2026-09-10 19:21:22', NULL, NULL, 'dasdas', '2026-09-11', 'P', 'Lainnya', '5 years', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'With a practice grounded in Hatha and Ashtanga, and refined through the precision of Iyengar alignment, Margaretta brings a thoughtful and therapeutic approach to teaching. Her work is especially focused on supporting the body through injury, recovery, and the transformative journey of pregnancy. She believes yoga is not about performance, but about creating a deeper relationship with the body, breath, and mind—small practices that can quietly transform the way we move through everyday life. Beyond the mat, she continues to explore holistic approaches to wellbeing through Access Bars and Qi Gong.', NULL, NULL, 'new_registered', NULL),
(6, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', NULL, 'Jl. Gandaria', 4, 17, 'avatars/u5KMRU9jG95SYcdq0vhxnksQ0EFatee9WxrPxrtH.jpg', 'super@admin.com', '2025-09-16 00:23:37', '$2y$12$PRZJcd.nlREU6NRq3jvIVemYmlwxVPTb7En4URyNgyQSfKjWUzwDi', 'N5YbCnIhU9R5rASs3ky4dl59VeZhdsTr0vC0MMxPXtcPOv23rbym4ji5oUom', '2025-09-16 00:23:37', '2026-09-06 23:43:32', NULL, NULL, 'dasda', '2025-09-24', 'P', 'Lainnya', '10 tahun', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sdsddsad', NULL, NULL, 'new_registered', NULL),
(7, '95294c43-b3d8-4b46-b938-e4eea1f3a359', 'Soni', '8534324202', 'sdcsddfs', NULL, NULL, 'avatars/ImiYBm1Cjs7Sw0eCNBO1Dz6w5XDpNYBWnxoPy5Yc.jpg', 'soni@yogaroots.id', '2026-09-03 18:34:37', '$2y$12$jj/33jNGDxqzXGloYFeLMO5mozoP0v2atkzwf/S7ogXydL8n1hf4G', NULL, '2026-09-03 18:34:37', '2026-09-06 23:44:15', NULL, NULL, 'dasdas', '2026-09-04', 'L', 'Kristen', '20 years', '2026-09-04', NULL, NULL, NULL, NULL, NULL, NULL, 'Soni is a certified RIMYI Introductory Level 2 yoga teacher with over 20 years of experience. His athletic background includes sepak takraw and college hockey, which inspired his path to becoming a sports teacher. His extensive experience allows him to skillfully guide and mentor his students, nurturing them into better practitioners. Join him on a transformative yoga journey, where his years of experience and unwavering dedication are at your service, helping you thrive as a yoga student', NULL, NULL, 'new_registered', NULL),
(8, 'b497082d-dd9f-4d1c-a611-9e1331eb5393', 'Ophellia', NULL, NULL, NULL, NULL, 'avatars/Mo3tGjHS10BSdIshei0ejPknsfvfxj0tiTxNg1xF.png', 'ophellia@yogaroots.id', '2026-09-10 19:24:15', '$2y$12$EYxioqiLzM1FKBBE0XsTzeEX41St.5ocxnaLflhUHOHd0JMAY.yX.', NULL, '2026-09-10 19:24:15', '2026-09-10 19:24:15', NULL, NULL, 'dsad', '2026-09-24', 'L', 'Lainnya', '4 years', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Rooted in mindful movement and conscious alignment, her teaching invites students to cultivate greater awareness, presence, and ease in their practice. With over 800 hours of yoga education and teacher training, she has studied Yoga Fundamentals at Iyengar Yoga Institute Indonesia. Since April 2024, she has been part of the Yoga Roots teaching community, offering a grounded and attentive approach to every practice.', NULL, NULL, 'new_registered', NULL),
(9, 'b55eb675-019c-4fa5-936e-579e2835527d', 'Yuniar', NULL, NULL, NULL, NULL, 'avatars/XjtPl3c2Q5k9OB5ukHIJRmwX2o6vdiPX465T0uZy.jpg', 'yuniar@yogaroots.id', '2026-09-10 19:07:05', '$2y$12$10Z.D3afA7m8Dlt16WfINORNIOzHJadrDcJCx.RChxCnRvlm.oikO', NULL, '2026-09-10 19:07:05', '2026-09-10 19:07:05', NULL, NULL, 'dsad', '2026-09-11', 'P', 'Islam', '20 years', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'With a practice rooted in Iyengar Yoga, Yuniar brings warmth, clarity, and a natural sense of ease to her teaching. She began practising and teaching in 2007, and since then has dedicated her practice to helping students feel at home on the mat. With a particular strength in alignment and beginner guidance, her classes offer a welcoming space to begin—or begin again—with curiosity and confidence. Her teaching is natural, friendly, and joyful, inviting each student to discover that yoga can be both precise and deeply personal.', NULL, NULL, 'new_registered', NULL),
(10, 'beaaf326-874c-48c2-b6b8-6bbe655c4df2', 'Sita Manwani', NULL, NULL, NULL, NULL, 'avatars/5xS7NpjOu3vPmgLh75j4rPpNSRWDr2JDAxYD2v1c.jpg', 'sita@yogaroots.id', '2026-09-10 19:13:42', '$2y$12$D7l1Tg6O9KnxTkHWNKOsouAYcxoTVuqDxqg3Zi9LDtFibOdlO1lw6', NULL, '2026-09-10 19:13:42', '2026-09-10 23:58:20', NULL, NULL, 'dasdas', '2026-09-11', 'P', 'Lainnya', '10 years', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sita is a dedicated and certified yoga teacher with a journey that began at the Art of Living Foundation in 2006. There, she completed the Sri Sri Yoga Teachers Training Course and subsequently became an Art of Living teacher, where she learned how to teach Sudarshan Kriya breathwork. She holds accreditation from the Iyengar Yoga Institute and certifications from both the Sri Sri School of Yoga and the Indian Yoga Association. Her strong belief in yoga\'s potential to unlock the meditative mind\'s power and enhance life drives her passion. As the senior instructor at Yoga Roots, she leads the beginner yoga classes and the SKY breath work classes.\r\n\r\nSita has just completed 100hour Advanced Yoga Teacher Training including Traditional Chinese Medicine Principles, 5 Elements Theory, applied Meridian Sequences for Mandala Vinyasa System and Osteo - Thai Massage.', NULL, NULL, 'new_registered', NULL),
(11, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', '43243277777', NULL, NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocLitzhONjAN_zPOcC2rM16BHekNE3M2zU7C-e0d53-6M47R-PE=s96-c', 'esproproperty.bekasi@gmail.com', '2026-10-02 07:44:00', '$2y$12$qyrn4UD8fK4o9BJnKrDoxOH6BkCyZkyHhhlcU1/1rvohjf7NAUygW', NULL, '2026-10-02 07:44:01', '2026-11-20 05:41:19', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'google', 'new_registered', '2026-11-20 05:41:19'),
(12, 'f8dbf191-7c45-4d71-8cfd-1e290747ade8', 'Madu Sarifa Official', '08112312313', NULL, NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocKQSJX_sGFgxp5u06OvpTbaVaP7GtF_lcy8UmifKhRIkUKTwQ8=s96-c', 'madusarifaofficial@gmail.com', '2026-11-20 05:52:43', '$2y$12$vYIOTaCZjtxEPuN5wn23YuXiLMbOBiP0WcMUIQ0pLcWT91DX8cYdq', NULL, '2026-11-20 05:52:43', '2026-11-20 05:53:02', '106420227434740471376', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'friend', 'new_registered', NULL),
(13, 'fa921602-18c9-4e1b-a070-ea705ecfc488', 'QA Screenshot', NULL, NULL, NULL, NULL, NULL, 'qa.screenshot@example.test', '2026-11-20 06:07:57', '$2y$12$IeGIu0xQ9p4wsuLfDqr2gOOJetGznGUet3IY3BPo4jCQyPBU8jBre', NULL, '2026-11-20 06:07:57', '2026-11-20 06:07:57', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'new_registered', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_packages`
--

CREATE TABLE `user_packages` (
  `uuid` char(36) NOT NULL,
  `user_uuid` char(36) NOT NULL,
  `package_uuid` char(36) NOT NULL,
  `order_uuid` char(36) DEFAULT NULL,
  `quota` int(10) UNSIGNED DEFAULT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','expired','cancelled') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_packages`
--

INSERT INTO `user_packages` (`uuid`, `user_uuid`, `package_uuid`, `order_uuid`, `quota`, `started_at`, `expired_at`, `status`, `created_at`, `updated_at`) VALUES
('01a104d6-e85f-71b4-b9b8-676004bc9a16', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', '01a095f2-d286-7241-b566-8027529b8196', '01a1049e-afda-7188-b2f9-9fa3b404f6d3', 48, '2026-10-04 02:55:55', '2026-10-11 02:55:55', 'active', '2026-10-04 02:55:55', '2026-10-06 11:44:43'),
('01a110b4-0015-7154-9381-8d3a9868f59e', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '01a095f2-d286-7241-b566-8027529b8196', '01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', 49, '2026-10-06 10:13:14', '2026-10-13 10:13:14', 'active', '2026-10-06 10:13:14', '2026-10-06 10:13:47');

-- --------------------------------------------------------

--
-- Table structure for table `user_vendors_category`
--

CREATE TABLE `user_vendors_category` (
  `uuid` char(36) NOT NULL,
  `user_uuid` char(36) NOT NULL,
  `vendors_category_uuid` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_vendors_category`
--

INSERT INTO `user_vendors_category` (`uuid`, `user_uuid`, `vendors_category_uuid`, `created_at`, `updated_at`) VALUES
('012ddcb9-f54e-46ef-a934-a9598fe92d78', '755e0eaa-50e4-46db-9003-7c1fc6674876', '2146b32e-85f3-4df1-b8c6-4718bd786114', NULL, NULL),
('0a8a1ddd-3bc4-454d-83e2-75e1e6757166', '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', '3cddd283-5b50-493a-9cab-cb04eef88946', NULL, NULL),
('2be9213a-c244-40ae-aad4-608e2a68a7d5', 'b55eb675-019c-4fa5-936e-579e2835527d', '11e863ef-4739-4bcf-b7d1-d599b336bb09', NULL, NULL),
('566affb7-3d73-4746-bf50-c4d4f22471e8', '755e0eaa-50e4-46db-9003-7c1fc6674876', '50b72628-5cd5-4f8d-937a-a41852efc90d', NULL, NULL),
('73bdbe77-22b2-406c-9a95-d2e23ba60294', '95294c43-b3d8-4b46-b938-e4eea1f3a359', '11e863ef-4739-4bcf-b7d1-d599b336bb09', NULL, NULL),
('8e72d2bf-17c2-4bdf-b23c-92b0b4513a2f', '787b72ea-59d0-4d54-848b-c200bddafdd2', '50b72628-5cd5-4f8d-937a-a41852efc90d', NULL, NULL),
('90cdacd4-a8a3-430b-9635-ae84f965a508', '4a85c36a-caa6-4cc9-9ab6-47f77c0a9a67', '28b37aad-a252-4bbf-9284-d25c63047b19', NULL, NULL),
('ae1928ba-912e-4816-b32a-9e02d137f9ba', 'b497082d-dd9f-4d1c-a611-9e1331eb5393', '11e863ef-4739-4bcf-b7d1-d599b336bb09', NULL, NULL),
('bcea797f-fbe6-4100-affa-90d9fd25eb2a', '95294c43-b3d8-4b46-b938-e4eea1f3a359', '50b72628-5cd5-4f8d-937a-a41852efc90d', NULL, NULL),
('c967bcae-b240-45bd-9030-36cc68ce2a64', '95294c43-b3d8-4b46-b938-e4eea1f3a359', 'ae3ca862-ed29-4025-bab9-4caff951820e', NULL, NULL),
('e5aea86d-50ab-4dc6-a2ab-f10c679f5fe5', '787b72ea-59d0-4d54-848b-c200bddafdd2', '4240ba9b-225f-4544-a558-cc4d30ddefa2', NULL, NULL),
('e78a95e8-373a-4d30-96ee-9eefb6494f78', '787b72ea-59d0-4d54-848b-c200bddafdd2', '2146b32e-85f3-4df1-b8c6-4718bd786114', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vendors_categories`
--

CREATE TABLE `vendors_categories` (
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors_categories`
--

INSERT INTO `vendors_categories` (`uuid`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
('11e863ef-4739-4bcf-b7d1-d599b336bb09', 'Wedding Planner', 'wedding-planner', 'Layanan profesional untuk merencanakan, mengatur, dan mengoordinasikan seluruh kebutuhan acara pernikahan.', 'active', '2026-09-05 08:39:02', '2026-11-19 00:36:21'),
('2146b32e-85f3-4df1-b8c6-4718bd786114', 'Dekorasi', 'dekorasi', 'Penyedia layanan dekorasi pernikahan untuk menciptakan suasana acara yang indah, elegan, dan berkesan.', 'active', '2026-08-27 18:05:32', '2026-11-19 00:36:32'),
('28b37aad-a252-4bbf-9284-d25c63047b19', 'Katering', 'katering', 'Penyedia makanan dan minuman untuk memenuhi kebutuhan konsumsi tamu undangan selama acara pernikahan.', 'active', '2026-09-05 08:33:45', '2026-11-19 00:36:43'),
('3cddd283-5b50-493a-9cab-cb04eef88946', 'Busana & MUA', 'busana-mua', 'Penyedia busana pengantin dan jasa tata rias untuk menciptakan penampilan terbaik di hari istimewa.', 'active', '2026-09-05 08:39:55', '2026-11-19 00:36:55'),
('4240ba9b-225f-4544-a558-cc4d30ddefa2', 'Dokumentasi', 'dokumentasi', 'Penyedia layanan fotografi dan videografi untuk mengabadikan setiap momen berharga dalam pernikahan.', 'active', '2026-09-05 08:36:05', '2026-11-19 00:37:09'),
('50b72628-5cd5-4f8d-937a-a41852efc90d', 'Entertainment', 'entertainment', 'Penyedia hiburan dan pengisi acara untuk menciptakan suasana pernikahan yang meriah dan berkesan.', 'active', '2026-09-05 08:36:28', '2026-11-19 00:37:56'),
('6b28240c-d7d9-4b09-850b-6dcbb3875c18', 'Venue', 'venue', 'Penyedia lokasi atau tempat penyelenggaraan acara pernikahan sesuai konsep dan kebutuhan pasangan.', 'active', '2026-09-05 08:37:49', '2026-11-19 00:38:09'),
('ae3ca862-ed29-4025-bab9-4caff951820e', 'Souvenir & Undangan', 'souvenir-undangan', 'Penyedia undangan dan souvenir pernikahan sebagai pelengkap serta kenang-kenangan bagi para tamu.', 'active', '2026-09-05 08:38:07', '2026-11-19 00:38:20');

-- --------------------------------------------------------

--
-- Table structure for table `venues`
--

CREATE TABLE `venues` (
  `uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `address` text NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `google_maps_url` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `venues`
--

INSERT INTO `venues` (`uuid`, `name`, `slug`, `excerpt`, `description`, `address`, `phone`, `email`, `google_maps_url`, `image`, `status`, `created_at`, `updated_at`) VALUES
('363f49dc-b34f-4ac2-ad18-95b140c6335e', 'Yoga Roots Studio', 'yoga-roots-studio', NULL, 'At Yoga Roots Studio, we see yoga as a journey of connection — between the body, mind, and breath. Through thoughtful guidance, you’ll deepen your practice, refine your understanding of each posture, and cultivate greater clarity, balance, and inner peace.', 'Roots Prasasta Building, Jl. Kawi Raya No.37, RT.6/RW.2, Guntur, Setiabudi, South Jakarta City, Jakarta 12980', '0813-2122-1270', 'info@yogaroots.id', NULL, 'studios/Pf3qptHBGxeJgryUNs5MdFVtEKADalPo5BdeNtqy.webp', 'active', '2026-09-08 18:30:24', '2026-09-08 18:30:24');

-- --------------------------------------------------------

--
-- Table structure for table `website_identities`
--

CREATE TABLE `website_identities` (
  `uuid` char(36) NOT NULL,
  `site_name` varchar(255) NOT NULL DEFAULT 'YogaRoots',
  `site_title` varchar(255) DEFAULT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `tiktok_url` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `google_analytics_id` varchar(50) DEFAULT NULL,
  `google_site_verification` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `website_identities`
--

INSERT INTO `website_identities` (`uuid`, `site_name`, `site_title`, `tagline`, `short_description`, `email`, `phone`, `address`, `facebook_url`, `instagram_url`, `youtube_url`, `tiktok_url`, `meta_title`, `meta_description`, `meta_keywords`, `google_analytics_id`, `google_site_verification`, `logo`, `favicon`, `og_image`, `created_at`, `updated_at`) VALUES
('39860859-6a83-4cae-aa27-659334baff1c', 'Sagita Wedding', 'Sagita Wedding', 'Wedding Planner', 'Wedding planning, concept, and celebration services designed with elegance and care.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-10-03 12:22:02', '2026-11-18 20:14:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `albums_cover_index` (`cover`);

--
-- Indexes for table `album_foto`
--
ALTER TABLE `album_foto`
  ADD PRIMARY KEY (`album_uuid`,`banner_uuid`),
  ADD KEY `album_foto_banner_uuid_index` (`banner_uuid`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `articles_slug_unique` (`slug`),
  ADD KEY `articles_user_uuid_foreign` (`user_uuid`),
  ADD KEY `articles_category_uuid_foreign` (`category_uuid`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `audit_logs_created_at_index` (`created_at`),
  ADD KEY `audit_logs_user_uuid_index` (`user_uuid`),
  ADD KEY `audit_logs_event_index` (`event`);

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`uuid`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `classes_slug_unique` (`slug`),
  ADD KEY `classes_vendor_uuid_index` (`vendor_uuid`),
  ADD KEY `classes_is_active_index` (`is_active`);

--
-- Indexes for table `class_bookings`
--
ALTER TABLE `class_bookings`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `class_bookings_user_uuid_status_index` (`user_uuid`,`status`),
  ADD KEY `class_bookings_class_schedule_uuid_status_index` (`class_schedule_uuid`,`status`),
  ADD KEY `class_bookings_package_uuid_index` (`package_uuid`),
  ADD KEY `class_bookings_order_uuid_index` (`order_uuid`),
  ADD KEY `bookings_schedule_date_idx` (`class_schedule_uuid`,`booking_date`);

--
-- Indexes for table `class_schedules`
--
ALTER TABLE `class_schedules`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `class_schedules_class_uuid_date_index` (`class_uuid`,`day`),
  ADD KEY `class_schedules_date_status_index` (`day`,`status`),
  ADD KEY `class_schedules_day_status_index` (`day`,`status`);

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`uuid`);

--
-- Indexes for table `disabilities`
--
ALTER TABLE `disabilities`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `disabilities_name_unique` (`name`),
  ADD UNIQUE KEY `disabilities_slug_unique` (`slug`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `events_slug_unique` (`slug`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `failed_logins`
--
ALTER TABLE `failed_logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `failed_logins_email_index` (`email`),
  ADD KEY `failed_logins_user_uuid_index` (`user_uuid`),
  ADD KEY `failed_logins_ip_address_index` (`ip_address`),
  ADD KEY `failed_logins_attempted_at_index` (`attempted_at`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `faqs_kategori_index` (`kategori`);

--
-- Indexes for table `file_downloads`
--
ALTER TABLE `file_downloads`
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
-- Indexes for table `kecamatans`
--
ALTER TABLE `kecamatans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelurahans`
--
ALTER TABLE `kelurahans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kelurahans_kecamatan_id_foreign` (`kecamatan_id`);

--
-- Indexes for table `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`uuid`);

--
-- Indexes for table `login_activities`
--
ALTER TABLE `login_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `login_activities_user_uuid_index` (`user_uuid`),
  ADD KEY `login_activities_email_index` (`email`),
  ADD KEY `login_activities_logged_in_at_index` (`logged_in_at`);

--
-- Indexes for table `login_lockouts`
--
ALTER TABLE `login_lockouts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_lockouts_type_value_unique` (`type`,`value`),
  ADD KEY `login_lockouts_value_index` (`value`),
  ADD KEY `login_lockouts_blocked_until_index` (`blocked_until`);

--
-- Indexes for table `menu_groups`
--
ALTER TABLE `menu_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_uuid_status_index` (`user_uuid`,`status`),
  ADD KEY `orders_package_uuid_index` (`package_uuid`),
  ADD KEY `orders_class_schedule_uuid_index` (`class_schedule_uuid`),
  ADD KEY `orders_package_option_uuid_index` (`package_option_uuid`) USING BTREE;

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `packages_slug_unique` (`slug`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `packages_is_active_index` (`is_active`),
  ADD KEY `packages_is_popular_index` (`is_popular`);

--
-- Indexes for table `package_features`
--
ALTER TABLE `package_features`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `package_features_package_uuid_index` (`package_uuid`);

--
-- Indexes for table `package_options`
--
ALTER TABLE `package_options`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `package_options_package_uuid_index` (`package_uuid`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`),
  ADD KEY `pages_user_uuid_foreign` (`user_uuid`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `payments_transaction_id_index` (`transaction_id`),
  ADD KEY `payments_order_uuid_status_index` (`order_uuid`,`status`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `polls`
--
ALTER TABLE `polls`
  ADD PRIMARY KEY (`uuid`);

--
-- Indexes for table `poll_votes`
--
ALTER TABLE `poll_votes`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `poll_votes_poll_uuid_foreign` (`poll_uuid`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seo`
--
ALTER TABLE `seo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seo_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `site_visits`
--
ALTER TABLE `site_visits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_visits_visited_at_visitor_hash_index` (`visited_at`,`visitor_hash`),
  ADD KEY `site_visits_visited_at_path_index` (`visited_at`,`path`),
  ADD KEY `site_visits_visited_at_device_index` (`visited_at`,`device`),
  ADD KEY `site_visits_visited_at_index` (`visited_at`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`uuid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `users_no_hp_unique` (`no_hp`),
  ADD KEY `users_kecamatan_id_foreign` (`kecamatan_id`),
  ADD KEY `users_kelurahan_id_foreign` (`kelurahan_id`),
  ADD KEY `users_package_uuid_index` (`package_uuid`),
  ADD KEY `users_membership_status_index` (`sumber_informasi`);

--
-- Indexes for table `user_packages`
--
ALTER TABLE `user_packages`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `user_packages_user_uuid_status_index` (`user_uuid`,`status`),
  ADD KEY `user_packages_package_uuid_index` (`package_uuid`),
  ADD KEY `user_packages_order_uuid_index` (`order_uuid`),
  ADD KEY `user_packages_expired_at_index` (`expired_at`);

--
-- Indexes for table `user_vendors_category`
--
ALTER TABLE `user_vendors_category`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `user_vendors_category_user_uuid_vendors_category_uuid_unique` (`user_uuid`,`vendors_category_uuid`),
  ADD KEY `user_vendors_category_vendors_category_uuid_foreign` (`vendors_category_uuid`);

--
-- Indexes for table `vendors_categories`
--
ALTER TABLE `vendors_categories`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `vendors_categories_name_unique` (`name`),
  ADD UNIQUE KEY `vendors_categories_slug_unique` (`slug`);

--
-- Indexes for table `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `venues_slug_unique` (`slug`);

--
-- Indexes for table `website_identities`
--
ALTER TABLE `website_identities`
  ADD PRIMARY KEY (`uuid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_logins`
--
ALTER TABLE `failed_logins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `file_downloads`
--
ALTER TABLE `file_downloads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kecamatans`
--
ALTER TABLE `kecamatans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `kelurahans`
--
ALTER TABLE `kelurahans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `login_activities`
--
ALTER TABLE `login_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `login_lockouts`
--
ALTER TABLE `login_lockouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `menu_groups`
--
ALTER TABLE `menu_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT for table `seo`
--
ALTER TABLE `seo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `site_visits`
--
ALTER TABLE `site_visits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_category_uuid_foreign` FOREIGN KEY (`category_uuid`) REFERENCES `categories` (`uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `articles_user_uuid_foreign` FOREIGN KEY (`user_uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_vendor_uuid_foreign` FOREIGN KEY (`vendor_uuid`) REFERENCES `users` (`uuid`) ON DELETE SET NULL;

--
-- Constraints for table `class_bookings`
--
ALTER TABLE `class_bookings`
  ADD CONSTRAINT `class_bookings_class_schedule_uuid_foreign` FOREIGN KEY (`class_schedule_uuid`) REFERENCES `class_schedules` (`uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_bookings_order_uuid_foreign` FOREIGN KEY (`order_uuid`) REFERENCES `orders` (`uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `class_bookings_package_uuid_foreign` FOREIGN KEY (`package_uuid`) REFERENCES `packages` (`uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `class_bookings_user_uuid_foreign` FOREIGN KEY (`user_uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `class_schedules`
--
ALTER TABLE `class_schedules`
  ADD CONSTRAINT `class_schedules_class_uuid_foreign` FOREIGN KEY (`class_uuid`) REFERENCES `classes` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `kelurahans`
--
ALTER TABLE `kelurahans`
  ADD CONSTRAINT `kelurahans_kecamatan_id_foreign` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_class_schedule_uuid_foreign` FOREIGN KEY (`class_schedule_uuid`) REFERENCES `class_schedules` (`uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_package_option_uuid_foreign` FOREIGN KEY (`package_option_uuid`) REFERENCES `package_options` (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_package_uuid_foreign` FOREIGN KEY (`package_uuid`) REFERENCES `packages` (`uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_uuid_foreign` FOREIGN KEY (`user_uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `package_features`
--
ALTER TABLE `package_features`
  ADD CONSTRAINT `package_features_package_uuid_foreign` FOREIGN KEY (`package_uuid`) REFERENCES `packages` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `package_options`
--
ALTER TABLE `package_options`
  ADD CONSTRAINT `package_options_package_uuid_foreign` FOREIGN KEY (`package_uuid`) REFERENCES `packages` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_user_uuid_foreign` FOREIGN KEY (`user_uuid`) REFERENCES `users` (`uuid`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_uuid_foreign` FOREIGN KEY (`order_uuid`) REFERENCES `orders` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `poll_votes`
--
ALTER TABLE `poll_votes`
  ADD CONSTRAINT `poll_votes_poll_uuid_foreign` FOREIGN KEY (`poll_uuid`) REFERENCES `polls` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_kecamatan_id_foreign` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_kelurahan_id_foreign` FOREIGN KEY (`kelurahan_id`) REFERENCES `kelurahans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_package_uuid_foreign` FOREIGN KEY (`package_uuid`) REFERENCES `packages` (`uuid`) ON DELETE SET NULL;

--
-- Constraints for table `user_packages`
--
ALTER TABLE `user_packages`
  ADD CONSTRAINT `user_packages_order_uuid_foreign` FOREIGN KEY (`order_uuid`) REFERENCES `orders` (`uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_packages_package_uuid_foreign` FOREIGN KEY (`package_uuid`) REFERENCES `packages` (`uuid`),
  ADD CONSTRAINT `user_packages_user_uuid_foreign` FOREIGN KEY (`user_uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `user_vendors_category`
--
ALTER TABLE `user_vendors_category`
  ADD CONSTRAINT `user_vendors_category_user_uuid_foreign` FOREIGN KEY (`user_uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_vendors_category_vendors_category_uuid_foreign` FOREIGN KEY (`vendors_category_uuid`) REFERENCES `vendors_categories` (`uuid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
