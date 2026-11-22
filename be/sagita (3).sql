-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2026 at 04:35 AM
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
(258, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '24', 'Keamanan & Aktivitas', '{\"name\":\"Security\"}', '{\"name\":\"Keamanan & Aktivitas\"}', 'http://127.0.0.1:8000/backend/menu/24', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 14:23:49', '2026-11-20 14:23:49'),
(259, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '24', 'Keamanan', '{\"name\":\"Keamanan & Aktivitas\"}', '{\"name\":\"Keamanan\"}', 'http://127.0.0.1:8000/backend/menu/24', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 14:24:03', '2026-11-20 14:24:03'),
(260, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '56', 'Aktivitas Login', '{\"name\":\"Login Activity\"}', '{\"name\":\"Aktivitas Login\"}', 'http://127.0.0.1:8000/backend/menu/24/item/56', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 14:24:24', '2026-11-20 14:24:24'),
(261, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '57', 'Log Aktivitas', '{\"name\":\"Audit Log\"}', '{\"name\":\"Log Aktivitas\"}', 'http://127.0.0.1:8000/backend/menu/24/item/57', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 14:24:43', '2026-11-20 14:24:43'),
(262, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '58', 'Percobaan Login', '{\"name\":\"Failed Login\"}', '{\"name\":\"Percobaan Login\"}', 'http://127.0.0.1:8000/backend/menu/24/item/58', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 14:25:03', '2026-11-20 14:25:03'),
(263, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '59', 'Blokir Akses', '{\"name\":\"Blokir Login\"}', '{\"name\":\"Blokir Akses\"}', 'http://127.0.0.1:8000/backend/menu/24/item/59', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 14:25:23', '2026-11-20 14:25:23'),
(264, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '3', 'Pengaturan', '{\"name\":\"Settings\"}', '{\"name\":\"Pengaturan\"}', 'http://127.0.0.1:8000/backend/menu/3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 14:26:03', '2026-11-20 14:26:03'),
(265, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '60', 'Identitas Website', '{\"name\":\"Website Identity\"}', '{\"name\":\"Identitas Website\"}', 'http://127.0.0.1:8000/backend/menu/3/item/60', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-20 14:26:27', '2026-11-20 14:26:27'),
(266, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '4', 'Riwayat Pemesanan', '{\"name\":\"Orders\"}', '{\"name\":\"Riwayat Pemesanan\"}', 'http://127.0.0.1:8000/backend/menu/4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-21 13:29:21', '2026-11-21 13:29:21'),
(267, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\ManagementAccess\\MenuItem', '54', 'Order List', '{\"id\":54,\"name\":\"Order List\",\"icon\":null,\"route\":\"orders.index\",\"status\":true,\"permission_name\":\"banner.index\",\"menu_group_id\":4,\"position\":1}', NULL, 'http://127.0.0.1:8000/backend/menu/4/item/54', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-21 13:29:56', '2026-11-21 13:29:56'),
(268, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '55', 'Pesanan Saya', '{\"name\":\"My Orders\",\"permission_name\":\"filedownload.index\"}', '{\"name\":\"Pesanan Saya\",\"permission_name\":\"orders.index\"}', 'http://127.0.0.1:8000/backend/menu/4/item/55', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-21 13:30:10', '2026-11-21 13:30:10'),
(269, NULL, NULL, NULL, 'updated', 'App\\Models\\User', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', '{\"google_id\":null}', '{\"google_id\":\"100949998292022765140\"}', 'http://localhost:8000/auth/google/callback?authuser=0&code=4%2F0AUn6Hd8s3-Svrcn4r7I8EEdiP2G88HFEabjHC7OqiVTnZLiInINdwYYhUWnw_8fbw9tMXQ&iss=https%3A%2F%2Faccounts.google.com&prompt=none&scope=email%20profile%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.email%20openid%20https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fuserinfo.profile', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-21 13:31:57', '2026-11-21 13:31:57'),
(270, NULL, NULL, NULL, 'updated', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', '{\"password\":\"$2y$12$WXO13gY21gOqgS1s7M743.Oe5UdAxQDykEbmTf0xJ2LnyNdPMzsj6\"}', '{\"password\":\"$2y$12$Q3X8BAqOUpYNjsGtmFH4eeZhYntLpkiTd9p\\/CWxf.hdm5fhNthr5a\"}', NULL, NULL, NULL, '2026-11-21 23:17:27', '2026-11-21 23:17:27'),
(271, NULL, NULL, NULL, 'updated', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', '{\"password\":\"$2y$12$Q3X8BAqOUpYNjsGtmFH4eeZhYntLpkiTd9p\\/CWxf.hdm5fhNthr5a\"}', '{\"password\":\"$2y$12$zXAvvf5TAYZg1O7CxzG6xOcUeWCEvbNLVndvd6Ja5ZD.ZN\\/.D3Bwu\"}', NULL, NULL, NULL, '2026-11-21 23:19:20', '2026-11-21 23:19:20'),
(272, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '27', 'Riwayat Pembayaran', '{\"name\":\"Memberships\",\"permission_name\":\"menu.memberships\"}', '{\"name\":\"Riwayat Pembayaran\",\"permission_name\":\"account.index\"}', 'http://localhost:8000/backend/menu/27', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 00:33:17', '2026-11-22 00:33:17'),
(273, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\ManagementAccess\\MenuGroup', '27', 'Riwayat Pembayaran', '{\"id\":27,\"name\":\"Riwayat Pembayaran\",\"status\":true,\"permission_name\":\"account.index\",\"icon\":\"bx-id-card\",\"position\":14}', NULL, 'http://localhost:8000/backend/menu/eyJpdiI6IkxmaGdqWGxHSGJLVVFaeXZ2cllJRmc9PSIsInZhbHVlIjoiRGlKWjhnbzhEWkVYL3pHTkh3NmZ1UT09IiwibWFjIjoiYTVmMzAxY2Q1NDM2Y2IwODQ5MWRlOTA4MmIxNjM1NTg4ODVhZmI0ZjU4NTFmYjk3NjRmMzE0ZTNjYmZmZDEyZiIsInRhZyI6IiJ9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 00:35:08', '2026-11-22 00:35:08'),
(274, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '26', 'Riwayat Pembayaran', '{\"name\":\"Memberships\"}', '{\"name\":\"Riwayat Pembayaran\"}', 'http://localhost:8000/backend/menu/26', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 00:35:21', '2026-11-22 00:35:21'),
(275, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuGroup', '26', 'Pembayaran & Invoice', '{\"name\":\"Riwayat Pembayaran\"}', '{\"name\":\"Pembayaran & Invoice\"}', 'http://localhost:8000/backend/menu/26', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 00:38:46', '2026-11-22 00:38:46'),
(276, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\ManagementAccess\\MenuItem', '61', 'Riwayat Pembayaran', '{\"name\":\"All Members\"}', '{\"name\":\"Riwayat Pembayaran\"}', 'http://localhost:8000/backend/menu/26/item/61', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 00:39:01', '2026-11-22 00:39:01'),
(277, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Banner', '55ef87f4-ea5c-481a-aecf-d1b660417340', 'Banner #55ef87f4-ea5c-481a-aecf-d1b660417340', NULL, '{\"tipe\":\"foto\",\"nama\":\"Banner 1\",\"deskripsi\":null,\"status\":\"active\",\"link\":null,\"posisi\":\"slider\",\"gambar\":\"banners\\/m3oToKM6YyYpTu0jXmWB0Ugn6ApnTvykmnEbkReD.jpg\",\"updated_at\":\"2026-11-22 10:01:05\",\"created_at\":\"2026-11-22 10:01:05\"}', 'http://localhost:8000/backend/banner', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 03:01:05', '2026-11-22 03:01:05'),
(278, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Banner', '6c72aaea-fcde-4aec-911c-4c288cf975ee', 'Banner #6c72aaea-fcde-4aec-911c-4c288cf975ee', NULL, '{\"tipe\":\"foto\",\"nama\":\"Banner 2\",\"deskripsi\":null,\"status\":\"active\",\"link\":null,\"posisi\":\"slider\",\"gambar\":\"banners\\/KeY0gZZHOXpGhKqkSZHfaKlY8DujUZZtqWoNNFxg.jpg\",\"updated_at\":\"2026-11-22 10:01:20\",\"created_at\":\"2026-11-22 10:01:20\"}', 'http://localhost:8000/backend/banner', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 03:01:20', '2026-11-22 03:01:20'),
(279, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\Package\\Package', '01a06234-aac1-722c-84d6-c46d4150e70f', 'Studio Class Pack - 60 Minutes', '{\"id\":1,\"uuid\":\"01a06234-aac1-722c-84d6-c46d4150e70f\",\"name\":\"Studio Class Pack - 60 Minutes\",\"slug\":\"studio-class-pack-60-minutes\",\"description\":\"Your perfect escape from the everyday. Step into the Yogaroots studio, move with intention, breathe deeply, and leave feeling refreshed.\",\"guest_capacity\":null,\"event_duration_hours\":null,\"venue_note\":null,\"cover_url\":null,\"is_popular\":false,\"is_active\":\"active\",\"is_published\":true,\"published_at\":null,\"lead_time_days\":0,\"price\":\"0.00\"}', NULL, 'http://localhost:8000/backend/packages/01a06234-aac1-722c-84d6-c46d4150e70f', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 03:16:33', '2026-11-22 03:16:33'),
(280, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\Package\\Package', '01a098cd-3db0-73e5-bbe6-ba63e290f1d0', 'Studio Class Pack - 90 Minutes', '{\"id\":5,\"uuid\":\"01a098cd-3db0-73e5-bbe6-ba63e290f1d0\",\"name\":\"Studio Class Pack - 90 Minutes\",\"slug\":\"studio-class-pack-90-minutes\",\"description\":\"A deeper escape from the everyday. Step into the Yogaroots studio, move with intention, breathe deeply, and give yourself more time to reconnect, recharge, and leave feeling refreshed.\",\"guest_capacity\":null,\"event_duration_hours\":null,\"venue_note\":null,\"cover_url\":null,\"is_popular\":false,\"is_active\":\"active\",\"is_published\":true,\"published_at\":null,\"lead_time_days\":0,\"price\":\"0.00\"}', NULL, 'http://localhost:8000/backend/packages/01a098cd-3db0-73e5-bbe6-ba63e290f1d0', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 03:16:46', '2026-11-22 03:16:46');

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

--
-- Dumping data for table `banner`
--

INSERT INTO `banner` (`uuid`, `tipe`, `video_url`, `nama`, `deskripsi`, `link`, `gambar`, `posisi`, `status`, `created_at`, `updated_at`) VALUES
('55ef87f4-ea5c-481a-aecf-d1b660417340', 'foto', NULL, 'Banner 1', NULL, NULL, 'banners/m3oToKM6YyYpTu0jXmWB0Ugn6ApnTvykmnEbkReD.jpg', 'slider', 'active', '2026-11-22 03:01:05', '2026-11-22 03:01:05'),
('6c72aaea-fcde-4aec-911c-4c288cf975ee', 'foto', NULL, 'Banner 2', NULL, NULL, 'banners/KeY0gZZHOXpGhKqkSZHfaKlY8DujUZZtqWoNNFxg.jpg', 'slider', 'active', '2026-11-22 03:01:20', '2026-11-22 03:01:20');

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
('5c785c036466adea360111aa28563bfd556b5fba', 'i:1;', 1795316492),
('5c785c036466adea360111aa28563bfd556b5fba:timer', 'i:1795316492;', 1795316492),
('a75f3f172bfb296f2e10cbfc6dfc1883', 'i:9;', 1795316619),
('a75f3f172bfb296f2e10cbfc6dfc1883:timer', 'i:1795316619;', 1795316619),
('dashboard.admin.action.4a86a1ef-3d85-43aa-b720-a09eabea6daf.1', 'a:4:{s:6:\"orders\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:10:\"orderCount\";i:0;s:8:\"messages\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\Consultation\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:13:\"consultations\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:4:\"uuid\";s:36:\"01b1f7d3-e121-7dba-b36f-69539d1cb839\";s:9:\"full_name\";s:12:\"Rani & Dimas\";s:5:\"email\";s:17:\"rani@example.test\";s:8:\"whatsapp\";s:12:\"081234567890\";s:11:\"lead_status\";s:3:\"New\";s:12:\"follow_up_at\";s:19:\"2026-11-21 00:00:00\";}s:11:\"\0*\0original\";a:6:{s:4:\"uuid\";s:36:\"01b1f7d3-e121-7dba-b36f-69539d1cb839\";s:9:\"full_name\";s:12:\"Rani & Dimas\";s:5:\"email\";s:17:\"rani@example.test\";s:8:\"whatsapp\";s:12:\"081234567890\";s:11:\"lead_status\";s:3:\"New\";s:12:\"follow_up_at\";s:19:\"2026-11-21 00:00:00\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:7:{s:12:\"wedding_date\";s:10:\"date:Y-m-d\";s:17:\"consultation_date\";s:10:\"date:Y-m-d\";s:12:\"follow_up_at\";s:8:\"datetime\";s:16:\"reminder_sent_at\";s:8:\"datetime\";s:23:\"converted_to_booking_at\";s:8:\"datetime\";s:9:\"merged_at\";s:8:\"datetime\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:25:{i:0;s:9:\"full_name\";i:1;s:5:\"email\";i:2;s:8:\"whatsapp\";i:3;s:12:\"wedding_date\";i:4;s:14:\"guest_estimate\";i:5;s:5:\"venue\";i:6;s:10:\"package_id\";i:7;s:15:\"wedding_concept\";i:8;s:17:\"consultation_date\";i:9;s:17:\"consultation_time\";i:10;s:19:\"consultation_method\";i:11;s:19:\"consultation_status\";i:12;s:11:\"admin_notes\";i:13;s:10:\"handled_by\";i:14;s:12:\"follow_up_at\";i:15;s:16:\"reminder_sent_at\";i:16;s:11:\"lead_status\";i:17;s:6:\"source\";i:18;s:23:\"converted_to_booking_at\";i:19;s:16:\"merged_into_uuid\";i:20;s:9:\"merged_at\";i:21;s:9:\"merged_by\";i:22;s:10:\"sort_order\";i:23;s:16:\"email_dedupe_key\";i:24;s:19:\"whatsapp_dedupe_key\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:12:\"messageCount\";i:1;}', 1795307725),
('dashboard.admin.action.50d173a0-1732-495d-9853-3057695035cd.1', 'a:4:{s:6:\"orders\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:10:\"orderCount\";i:0;s:8:\"messages\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\Consultation\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:13:\"consultations\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:4:\"uuid\";s:36:\"01b1f7d3-e121-7dba-b36f-69539d1cb839\";s:9:\"full_name\";s:12:\"Rani & Dimas\";s:5:\"email\";s:17:\"rani@example.test\";s:8:\"whatsapp\";s:12:\"081234567890\";s:11:\"lead_status\";s:3:\"New\";s:12:\"follow_up_at\";s:19:\"2026-11-21 00:00:00\";}s:11:\"\0*\0original\";a:6:{s:4:\"uuid\";s:36:\"01b1f7d3-e121-7dba-b36f-69539d1cb839\";s:9:\"full_name\";s:12:\"Rani & Dimas\";s:5:\"email\";s:17:\"rani@example.test\";s:8:\"whatsapp\";s:12:\"081234567890\";s:11:\"lead_status\";s:3:\"New\";s:12:\"follow_up_at\";s:19:\"2026-11-21 00:00:00\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:7:{s:12:\"wedding_date\";s:10:\"date:Y-m-d\";s:17:\"consultation_date\";s:10:\"date:Y-m-d\";s:12:\"follow_up_at\";s:8:\"datetime\";s:16:\"reminder_sent_at\";s:8:\"datetime\";s:23:\"converted_to_booking_at\";s:8:\"datetime\";s:9:\"merged_at\";s:8:\"datetime\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:25:{i:0;s:9:\"full_name\";i:1;s:5:\"email\";i:2;s:8:\"whatsapp\";i:3;s:12:\"wedding_date\";i:4;s:14:\"guest_estimate\";i:5;s:5:\"venue\";i:6;s:10:\"package_id\";i:7;s:15:\"wedding_concept\";i:8;s:17:\"consultation_date\";i:9;s:17:\"consultation_time\";i:10;s:19:\"consultation_method\";i:11;s:19:\"consultation_status\";i:12;s:11:\"admin_notes\";i:13;s:10:\"handled_by\";i:14;s:12:\"follow_up_at\";i:15;s:16:\"reminder_sent_at\";i:16;s:11:\"lead_status\";i:17;s:6:\"source\";i:18;s:23:\"converted_to_booking_at\";i:19;s:16:\"merged_into_uuid\";i:20;s:9:\"merged_at\";i:21;s:9:\"merged_by\";i:22;s:10:\"sort_order\";i:23;s:16:\"email_dedupe_key\";i:24;s:19:\"whatsapp_dedupe_key\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:12:\"messageCount\";i:1;}', 1795316678),
('dashboard.admin.action.a0e9f49c-2abc-4b0e-a246-121f739f4259.1', 'a:4:{s:6:\"orders\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:10:\"orderCount\";i:0;s:8:\"messages\";O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\Consultation\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:13:\"consultations\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:6:{s:4:\"uuid\";s:36:\"01b1f7d3-e121-7dba-b36f-69539d1cb839\";s:9:\"full_name\";s:12:\"Rani & Dimas\";s:5:\"email\";s:17:\"rani@example.test\";s:8:\"whatsapp\";s:12:\"081234567890\";s:11:\"lead_status\";s:3:\"New\";s:12:\"follow_up_at\";s:19:\"2026-11-21 00:00:00\";}s:11:\"\0*\0original\";a:6:{s:4:\"uuid\";s:36:\"01b1f7d3-e121-7dba-b36f-69539d1cb839\";s:9:\"full_name\";s:12:\"Rani & Dimas\";s:5:\"email\";s:17:\"rani@example.test\";s:8:\"whatsapp\";s:12:\"081234567890\";s:11:\"lead_status\";s:3:\"New\";s:12:\"follow_up_at\";s:19:\"2026-11-21 00:00:00\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:7:{s:12:\"wedding_date\";s:10:\"date:Y-m-d\";s:17:\"consultation_date\";s:10:\"date:Y-m-d\";s:12:\"follow_up_at\";s:8:\"datetime\";s:16:\"reminder_sent_at\";s:8:\"datetime\";s:23:\"converted_to_booking_at\";s:8:\"datetime\";s:9:\"merged_at\";s:8:\"datetime\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:25:{i:0;s:9:\"full_name\";i:1;s:5:\"email\";i:2;s:8:\"whatsapp\";i:3;s:12:\"wedding_date\";i:4;s:14:\"guest_estimate\";i:5;s:5:\"venue\";i:6;s:10:\"package_id\";i:7;s:15:\"wedding_concept\";i:8;s:17:\"consultation_date\";i:9;s:17:\"consultation_time\";i:10;s:19:\"consultation_method\";i:11;s:19:\"consultation_status\";i:12;s:11:\"admin_notes\";i:13;s:10:\"handled_by\";i:14;s:12:\"follow_up_at\";i:15;s:16:\"reminder_sent_at\";i:16;s:11:\"lead_status\";i:17;s:6:\"source\";i:18;s:23:\"converted_to_booking_at\";i:19;s:16:\"merged_into_uuid\";i:20;s:9:\"merged_at\";i:21;s:9:\"merged_by\";i:22;s:10:\"sort_order\";i:23;s:16:\"email_dedupe_key\";i:24;s:19:\"whatsapp_dedupe_key\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:12:\"messageCount\";i:1;}', 1795307192),
('dashboard.admin.recent-consultations.4a86a1ef-3d85-43aa-b720-a09eabea6daf.1', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\Consultation\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:13:\"consultations\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:4:\"uuid\";s:36:\"01b1f7d3-e121-7dba-b36f-69539d1cb839\";s:9:\"full_name\";s:12:\"Rani & Dimas\";s:5:\"email\";s:17:\"rani@example.test\";s:8:\"whatsapp\";s:12:\"081234567890\";s:11:\"lead_status\";s:3:\"New\";s:12:\"follow_up_at\";s:19:\"2026-11-21 00:00:00\";s:10:\"created_at\";s:19:\"2026-11-20 16:59:17\";}s:11:\"\0*\0original\";a:7:{s:4:\"uuid\";s:36:\"01b1f7d3-e121-7dba-b36f-69539d1cb839\";s:9:\"full_name\";s:12:\"Rani & Dimas\";s:5:\"email\";s:17:\"rani@example.test\";s:8:\"whatsapp\";s:12:\"081234567890\";s:11:\"lead_status\";s:3:\"New\";s:12:\"follow_up_at\";s:19:\"2026-11-21 00:00:00\";s:10:\"created_at\";s:19:\"2026-11-20 16:59:17\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:7:{s:12:\"wedding_date\";s:10:\"date:Y-m-d\";s:17:\"consultation_date\";s:10:\"date:Y-m-d\";s:12:\"follow_up_at\";s:8:\"datetime\";s:16:\"reminder_sent_at\";s:8:\"datetime\";s:23:\"converted_to_booking_at\";s:8:\"datetime\";s:9:\"merged_at\";s:8:\"datetime\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:25:{i:0;s:9:\"full_name\";i:1;s:5:\"email\";i:2;s:8:\"whatsapp\";i:3;s:12:\"wedding_date\";i:4;s:14:\"guest_estimate\";i:5;s:5:\"venue\";i:6;s:10:\"package_id\";i:7;s:15:\"wedding_concept\";i:8;s:17:\"consultation_date\";i:9;s:17:\"consultation_time\";i:10;s:19:\"consultation_method\";i:11;s:19:\"consultation_status\";i:12;s:11:\"admin_notes\";i:13;s:10:\"handled_by\";i:14;s:12:\"follow_up_at\";i:15;s:16:\"reminder_sent_at\";i:16;s:11:\"lead_status\";i:17;s:6:\"source\";i:18;s:23:\"converted_to_booking_at\";i:19;s:16:\"merged_into_uuid\";i:20;s:9:\"merged_at\";i:21;s:9:\"merged_by\";i:22;s:10:\"sort_order\";i:23;s:16:\"email_dedupe_key\";i:24;s:19:\"whatsapp_dedupe_key\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1795307725),
('dashboard.admin.recent-consultations.50d173a0-1732-495d-9853-3057695035cd.1', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\Consultation\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:13:\"consultations\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:4:\"uuid\";s:36:\"01b1f7d3-e121-7dba-b36f-69539d1cb839\";s:9:\"full_name\";s:12:\"Rani & Dimas\";s:5:\"email\";s:17:\"rani@example.test\";s:8:\"whatsapp\";s:12:\"081234567890\";s:11:\"lead_status\";s:3:\"New\";s:12:\"follow_up_at\";s:19:\"2026-11-21 00:00:00\";s:10:\"created_at\";s:19:\"2026-11-20 16:59:17\";}s:11:\"\0*\0original\";a:7:{s:4:\"uuid\";s:36:\"01b1f7d3-e121-7dba-b36f-69539d1cb839\";s:9:\"full_name\";s:12:\"Rani & Dimas\";s:5:\"email\";s:17:\"rani@example.test\";s:8:\"whatsapp\";s:12:\"081234567890\";s:11:\"lead_status\";s:3:\"New\";s:12:\"follow_up_at\";s:19:\"2026-11-21 00:00:00\";s:10:\"created_at\";s:19:\"2026-11-20 16:59:17\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:7:{s:12:\"wedding_date\";s:10:\"date:Y-m-d\";s:17:\"consultation_date\";s:10:\"date:Y-m-d\";s:12:\"follow_up_at\";s:8:\"datetime\";s:16:\"reminder_sent_at\";s:8:\"datetime\";s:23:\"converted_to_booking_at\";s:8:\"datetime\";s:9:\"merged_at\";s:8:\"datetime\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:25:{i:0;s:9:\"full_name\";i:1;s:5:\"email\";i:2;s:8:\"whatsapp\";i:3;s:12:\"wedding_date\";i:4;s:14:\"guest_estimate\";i:5;s:5:\"venue\";i:6;s:10:\"package_id\";i:7;s:15:\"wedding_concept\";i:8;s:17:\"consultation_date\";i:9;s:17:\"consultation_time\";i:10;s:19:\"consultation_method\";i:11;s:19:\"consultation_status\";i:12;s:11:\"admin_notes\";i:13;s:10:\"handled_by\";i:14;s:12:\"follow_up_at\";i:15;s:16:\"reminder_sent_at\";i:16;s:11:\"lead_status\";i:17;s:6:\"source\";i:18;s:23:\"converted_to_booking_at\";i:19;s:16:\"merged_into_uuid\";i:20;s:9:\"merged_at\";i:21;s:9:\"merged_by\";i:22;s:10:\"sort_order\";i:23;s:16:\"email_dedupe_key\";i:24;s:19:\"whatsapp_dedupe_key\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1795316678),
('dashboard.admin.recent-consultations.a0e9f49c-2abc-4b0e-a246-121f739f4259.1', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"App\\Models\\Consultation\":35:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:13:\"consultations\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:4:\"uuid\";s:36:\"01b1f7d3-e121-7dba-b36f-69539d1cb839\";s:9:\"full_name\";s:12:\"Rani & Dimas\";s:5:\"email\";s:17:\"rani@example.test\";s:8:\"whatsapp\";s:12:\"081234567890\";s:11:\"lead_status\";s:3:\"New\";s:12:\"follow_up_at\";s:19:\"2026-11-21 00:00:00\";s:10:\"created_at\";s:19:\"2026-11-20 16:59:17\";}s:11:\"\0*\0original\";a:7:{s:4:\"uuid\";s:36:\"01b1f7d3-e121-7dba-b36f-69539d1cb839\";s:9:\"full_name\";s:12:\"Rani & Dimas\";s:5:\"email\";s:17:\"rani@example.test\";s:8:\"whatsapp\";s:12:\"081234567890\";s:11:\"lead_status\";s:3:\"New\";s:12:\"follow_up_at\";s:19:\"2026-11-21 00:00:00\";s:10:\"created_at\";s:19:\"2026-11-20 16:59:17\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:7:{s:12:\"wedding_date\";s:10:\"date:Y-m-d\";s:17:\"consultation_date\";s:10:\"date:Y-m-d\";s:12:\"follow_up_at\";s:8:\"datetime\";s:16:\"reminder_sent_at\";s:8:\"datetime\";s:23:\"converted_to_booking_at\";s:8:\"datetime\";s:9:\"merged_at\";s:8:\"datetime\";s:10:\"deleted_at\";s:8:\"datetime\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:25:{i:0;s:9:\"full_name\";i:1;s:5:\"email\";i:2;s:8:\"whatsapp\";i:3;s:12:\"wedding_date\";i:4;s:14:\"guest_estimate\";i:5;s:5:\"venue\";i:6;s:10:\"package_id\";i:7;s:15:\"wedding_concept\";i:8;s:17:\"consultation_date\";i:9;s:17:\"consultation_time\";i:10;s:19:\"consultation_method\";i:11;s:19:\"consultation_status\";i:12;s:11:\"admin_notes\";i:13;s:10:\"handled_by\";i:14;s:12:\"follow_up_at\";i:15;s:16:\"reminder_sent_at\";i:16;s:11:\"lead_status\";i:17;s:6:\"source\";i:18;s:23:\"converted_to_booking_at\";i:19;s:16:\"merged_into_uuid\";i:20;s:9:\"merged_at\";i:21;s:9:\"merged_by\";i:22;s:10:\"sort_order\";i:23;s:16:\"email_dedupe_key\";i:24;s:19:\"whatsapp_dedupe_key\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:16:\"\0*\0forceDeleting\";b:0;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1795307192),
('dashboard.admin.recent-orders.4a86a1ef-3d85-43aa-b720-a09eabea6daf.1', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a11182-8463-7057-a0f4-8cf88279c6c2\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-DHVCIG8ZQL\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"210000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"pending\";s:11:\"pending_key\";s:73:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5|01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"expired_at\";s:19:\"2026-10-07 20:58:48\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 20:58:48\";s:10:\"updated_at\";s:19:\"2026-10-06 20:58:48\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a11182-8463-7057-a0f4-8cf88279c6c2\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-DHVCIG8ZQL\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"210000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"pending\";s:11:\"pending_key\";s:73:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5|01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"expired_at\";s:19:\"2026-10-07 20:58:48\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 20:58:48\";s:10:\"updated_at\";s:19:\"2026-10-06 20:58:48\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:2:{s:4:\"user\";N;s:7:\"package\";N;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a110cc-d5ec-7387-a3c5-87cde025d404\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-E5WX8OSWPO\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"210000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"pending\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-07 17:40:21\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 17:40:21\";s:10:\"updated_at\";s:19:\"2026-10-06 17:40:21\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a110cc-d5ec-7387-a3c5-87cde025d404\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-E5WX8OSWPO\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"210000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"pending\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-07 17:40:21\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 17:40:21\";s:10:\"updated_at\";s:19:\"2026-10-06 17:40:21\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:2:{s:4:\"user\";N;s:7:\"package\";N;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-BNEJIR8QFB\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:9:\"210000.00\";s:16:\"remaining_amount\";s:4:\"0.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:4:\"paid\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-07 17:12:11\";s:7:\"paid_at\";s:19:\"2026-10-06 17:13:14\";s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";s:51:\"proofs/WrmyZ098hpkUmpUTw9LmcOqDkUqtGOeLdNeyBjqM.png\";s:17:\"proof_uploaded_at\";s:19:\"2026-10-06 17:12:24\";s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 17:12:11\";s:10:\"updated_at\";s:19:\"2026-10-06 17:13:14\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-BNEJIR8QFB\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:9:\"210000.00\";s:16:\"remaining_amount\";s:4:\"0.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:4:\"paid\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-07 17:12:11\";s:7:\"paid_at\";s:19:\"2026-10-06 17:13:14\";s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";s:51:\"proofs/WrmyZ098hpkUmpUTw9LmcOqDkUqtGOeLdNeyBjqM.png\";s:17:\"proof_uploaded_at\";s:19:\"2026-10-06 17:12:24\";s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 17:12:11\";s:10:\"updated_at\";s:19:\"2026-10-06 17:13:14\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:2:{s:4:\"user\";N;s:7:\"package\";N;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a1049e-afda-7188-b2f9-9fa3b404f6d3\";s:9:\"user_uuid\";s:36:\"c2187aff-68d0-40b9-a6f1-52738cee4ea4\";s:12:\"order_number\";s:14:\"ORD-I5HKUWFTWJ\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:9:\"210000.00\";s:16:\"remaining_amount\";s:4:\"0.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:4:\"paid\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-05 08:54:31\";s:7:\"paid_at\";s:19:\"2026-10-04 09:55:55\";s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";s:51:\"proofs/lTa9j1FbqtOaGW4LDEw95vVjQ9JDz24MwX2UnFMi.png\";s:17:\"proof_uploaded_at\";s:19:\"2026-10-04 09:48:54\";s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-04 08:54:31\";s:10:\"updated_at\";s:19:\"2026-10-04 09:55:55\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a1049e-afda-7188-b2f9-9fa3b404f6d3\";s:9:\"user_uuid\";s:36:\"c2187aff-68d0-40b9-a6f1-52738cee4ea4\";s:12:\"order_number\";s:14:\"ORD-I5HKUWFTWJ\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:9:\"210000.00\";s:16:\"remaining_amount\";s:4:\"0.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:4:\"paid\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-05 08:54:31\";s:7:\"paid_at\";s:19:\"2026-10-04 09:55:55\";s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";s:51:\"proofs/lTa9j1FbqtOaGW4LDEw95vVjQ9JDz24MwX2UnFMi.png\";s:17:\"proof_uploaded_at\";s:19:\"2026-10-04 09:48:54\";s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-04 08:54:31\";s:10:\"updated_at\";s:19:\"2026-10-04 09:55:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:2:{s:4:\"user\";N;s:7:\"package\";N;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a09b1e-c44e-70ae-98b1-d19e19c7b4fe\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-QCQL0MKBRP\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a06234-aac1-722c-84d6-c46d4150e70f\";s:19:\"package_option_uuid\";s:36:\"01a09966-af8d-7252-bbf0-f1665358611d\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"230000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"230000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"230000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"expired\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-09-14 21:14:39\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-09-13 21:14:39\";s:10:\"updated_at\";s:19:\"2026-10-04 09:48:58\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a09b1e-c44e-70ae-98b1-d19e19c7b4fe\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-QCQL0MKBRP\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a06234-aac1-722c-84d6-c46d4150e70f\";s:19:\"package_option_uuid\";s:36:\"01a09966-af8d-7252-bbf0-f1665358611d\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"230000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"230000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"230000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"expired\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-09-14 21:14:39\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-09-13 21:14:39\";s:10:\"updated_at\";s:19:\"2026-10-04 09:48:58\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:2:{s:4:\"user\";N;s:7:\"package\";N;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1795307725);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('dashboard.admin.recent-orders.50d173a0-1732-495d-9853-3057695035cd.1', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a11182-8463-7057-a0f4-8cf88279c6c2\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-DHVCIG8ZQL\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"210000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"pending\";s:11:\"pending_key\";s:73:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5|01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"expired_at\";s:19:\"2026-10-07 20:58:48\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 20:58:48\";s:10:\"updated_at\";s:19:\"2026-10-06 20:58:48\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a11182-8463-7057-a0f4-8cf88279c6c2\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-DHVCIG8ZQL\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"210000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"pending\";s:11:\"pending_key\";s:73:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5|01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"expired_at\";s:19:\"2026-10-07 20:58:48\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 20:58:48\";s:10:\"updated_at\";s:19:\"2026-10-06 20:58:48\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:4:\"user\";O:15:\"App\\Models\\User\":37:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"users\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:2:{s:4:\"uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:4:\"name\";s:24:\"Wiku Pramesthi Bagaswara\";}s:11:\"\0*\0original\";a:2:{s:4:\"uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:4:\"name\";s:24:\"Wiku Pramesthi Bagaswara\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:8:{s:10:\"deleted_at\";s:8:\"datetime\";s:17:\"email_verified_at\";s:8:\"datetime\";s:8:\"password\";s:6:\"hashed\";s:26:\"prospect_status_updated_at\";s:8:\"datetime\";s:21:\"membership_start_date\";s:4:\"date\";s:19:\"membership_end_date\";s:4:\"date\";s:11:\"total_quota\";s:7:\"integer\";s:15:\"remaining_quota\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:2:{i:0;s:8:\"password\";i:1;s:14:\"remember_token\";}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:24:{i:0;s:4:\"uuid\";i:1;s:6:\"avatar\";i:2;s:4:\"name\";i:3;s:8:\"password\";i:4;s:17:\"email_verified_at\";i:5;s:5:\"email\";i:6;s:9:\"google_id\";i:7;s:5:\"no_hp\";i:8;s:12:\"tempat_lahir\";i:9;s:13:\"tanggal_lahir\";i:10;s:13:\"jenis_kelamin\";i:11;s:5:\"agama\";i:12;s:10:\"pengalaman\";i:13;s:9:\"is_active\";i:14;s:6:\"alamat\";i:15;s:8:\"facebook\";i:16;s:9:\"instagram\";i:17;s:7:\"twitter\";i:18;s:6:\"tiktok\";i:19;s:7:\"youtube\";i:20;s:8:\"biografi\";i:21;s:16:\"sumber_informasi\";i:22;s:15:\"prospect_status\";i:23;s:26:\"prospect_status_updated_at\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:19:\"\0*\0authPasswordName\";s:8:\"password\";s:20:\"\0*\0rememberTokenName\";s:14:\"remember_token\";s:16:\"\0*\0forceDeleting\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a110cc-d5ec-7387-a3c5-87cde025d404\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-E5WX8OSWPO\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"210000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"pending\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-07 17:40:21\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 17:40:21\";s:10:\"updated_at\";s:19:\"2026-10-06 17:40:21\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a110cc-d5ec-7387-a3c5-87cde025d404\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-E5WX8OSWPO\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"210000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"pending\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-07 17:40:21\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 17:40:21\";s:10:\"updated_at\";s:19:\"2026-10-06 17:40:21\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:4:\"user\";r:104;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-BNEJIR8QFB\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:9:\"210000.00\";s:16:\"remaining_amount\";s:4:\"0.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:4:\"paid\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-07 17:12:11\";s:7:\"paid_at\";s:19:\"2026-10-06 17:13:14\";s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";s:51:\"proofs/WrmyZ098hpkUmpUTw9LmcOqDkUqtGOeLdNeyBjqM.png\";s:17:\"proof_uploaded_at\";s:19:\"2026-10-06 17:12:24\";s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 17:12:11\";s:10:\"updated_at\";s:19:\"2026-10-06 17:13:14\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-BNEJIR8QFB\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:9:\"210000.00\";s:16:\"remaining_amount\";s:4:\"0.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:4:\"paid\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-07 17:12:11\";s:7:\"paid_at\";s:19:\"2026-10-06 17:13:14\";s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";s:51:\"proofs/WrmyZ098hpkUmpUTw9LmcOqDkUqtGOeLdNeyBjqM.png\";s:17:\"proof_uploaded_at\";s:19:\"2026-10-06 17:12:24\";s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 17:12:11\";s:10:\"updated_at\";s:19:\"2026-10-06 17:13:14\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:4:\"user\";r:104;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a1049e-afda-7188-b2f9-9fa3b404f6d3\";s:9:\"user_uuid\";s:36:\"c2187aff-68d0-40b9-a6f1-52738cee4ea4\";s:12:\"order_number\";s:14:\"ORD-I5HKUWFTWJ\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:9:\"210000.00\";s:16:\"remaining_amount\";s:4:\"0.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:4:\"paid\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-05 08:54:31\";s:7:\"paid_at\";s:19:\"2026-10-04 09:55:55\";s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";s:51:\"proofs/lTa9j1FbqtOaGW4LDEw95vVjQ9JDz24MwX2UnFMi.png\";s:17:\"proof_uploaded_at\";s:19:\"2026-10-04 09:48:54\";s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-04 08:54:31\";s:10:\"updated_at\";s:19:\"2026-10-04 09:55:55\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a1049e-afda-7188-b2f9-9fa3b404f6d3\";s:9:\"user_uuid\";s:36:\"c2187aff-68d0-40b9-a6f1-52738cee4ea4\";s:12:\"order_number\";s:14:\"ORD-I5HKUWFTWJ\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:9:\"210000.00\";s:16:\"remaining_amount\";s:4:\"0.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:4:\"paid\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-05 08:54:31\";s:7:\"paid_at\";s:19:\"2026-10-04 09:55:55\";s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";s:51:\"proofs/lTa9j1FbqtOaGW4LDEw95vVjQ9JDz24MwX2UnFMi.png\";s:17:\"proof_uploaded_at\";s:19:\"2026-10-04 09:48:54\";s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-04 08:54:31\";s:10:\"updated_at\";s:19:\"2026-10-04 09:55:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:4:\"user\";O:15:\"App\\Models\\User\":37:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:5:\"users\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:2:{s:4:\"uuid\";s:36:\"c2187aff-68d0-40b9-a6f1-52738cee4ea4\";s:4:\"name\";s:14:\"ESPRO Property\";}s:11:\"\0*\0original\";a:2:{s:4:\"uuid\";s:36:\"c2187aff-68d0-40b9-a6f1-52738cee4ea4\";s:4:\"name\";s:14:\"ESPRO Property\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:8:{s:10:\"deleted_at\";s:8:\"datetime\";s:17:\"email_verified_at\";s:8:\"datetime\";s:8:\"password\";s:6:\"hashed\";s:26:\"prospect_status_updated_at\";s:8:\"datetime\";s:21:\"membership_start_date\";s:4:\"date\";s:19:\"membership_end_date\";s:4:\"date\";s:11:\"total_quota\";s:7:\"integer\";s:15:\"remaining_quota\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:2:{i:0;s:8:\"password\";i:1;s:14:\"remember_token\";}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:24:{i:0;s:4:\"uuid\";i:1;s:6:\"avatar\";i:2;s:4:\"name\";i:3;s:8:\"password\";i:4;s:17:\"email_verified_at\";i:5;s:5:\"email\";i:6;s:9:\"google_id\";i:7;s:5:\"no_hp\";i:8;s:12:\"tempat_lahir\";i:9;s:13:\"tanggal_lahir\";i:10;s:13:\"jenis_kelamin\";i:11;s:5:\"agama\";i:12;s:10:\"pengalaman\";i:13;s:9:\"is_active\";i:14;s:6:\"alamat\";i:15;s:8:\"facebook\";i:16;s:9:\"instagram\";i:17;s:7:\"twitter\";i:18;s:6:\"tiktok\";i:19;s:7:\"youtube\";i:20;s:8:\"biografi\";i:21;s:16:\"sumber_informasi\";i:22;s:15:\"prospect_status\";i:23;s:26:\"prospect_status_updated_at\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}s:19:\"\0*\0authPasswordName\";s:8:\"password\";s:20:\"\0*\0rememberTokenName\";s:14:\"remember_token\";s:16:\"\0*\0forceDeleting\";b:0;}}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a09b1e-c44e-70ae-98b1-d19e19c7b4fe\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-QCQL0MKBRP\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a06234-aac1-722c-84d6-c46d4150e70f\";s:19:\"package_option_uuid\";s:36:\"01a09966-af8d-7252-bbf0-f1665358611d\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"230000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"230000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"230000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"expired\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-09-14 21:14:39\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-09-13 21:14:39\";s:10:\"updated_at\";s:19:\"2026-10-04 09:48:58\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a09b1e-c44e-70ae-98b1-d19e19c7b4fe\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-QCQL0MKBRP\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a06234-aac1-722c-84d6-c46d4150e70f\";s:19:\"package_option_uuid\";s:36:\"01a09966-af8d-7252-bbf0-f1665358611d\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"230000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"230000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"230000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"expired\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-09-14 21:14:39\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-09-13 21:14:39\";s:10:\"updated_at\";s:19:\"2026-10-04 09:48:58\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:1:{s:4:\"user\";r:104;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1795316678);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('dashboard.admin.recent-orders.a0e9f49c-2abc-4b0e-a246-121f739f4259.1', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a11182-8463-7057-a0f4-8cf88279c6c2\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-DHVCIG8ZQL\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"210000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"pending\";s:11:\"pending_key\";s:73:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5|01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"expired_at\";s:19:\"2026-10-07 20:58:48\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 20:58:48\";s:10:\"updated_at\";s:19:\"2026-10-06 20:58:48\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a11182-8463-7057-a0f4-8cf88279c6c2\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-DHVCIG8ZQL\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"210000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"pending\";s:11:\"pending_key\";s:73:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5|01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"expired_at\";s:19:\"2026-10-07 20:58:48\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 20:58:48\";s:10:\"updated_at\";s:19:\"2026-10-06 20:58:48\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:2:{s:4:\"user\";N;s:7:\"package\";N;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a110cc-d5ec-7387-a3c5-87cde025d404\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-E5WX8OSWPO\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"210000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"pending\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-07 17:40:21\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 17:40:21\";s:10:\"updated_at\";s:19:\"2026-10-06 17:40:21\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a110cc-d5ec-7387-a3c5-87cde025d404\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-E5WX8OSWPO\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"210000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"pending\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-07 17:40:21\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 17:40:21\";s:10:\"updated_at\";s:19:\"2026-10-06 17:40:21\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:2:{s:4:\"user\";N;s:7:\"package\";N;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-BNEJIR8QFB\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:9:\"210000.00\";s:16:\"remaining_amount\";s:4:\"0.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:4:\"paid\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-07 17:12:11\";s:7:\"paid_at\";s:19:\"2026-10-06 17:13:14\";s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";s:51:\"proofs/WrmyZ098hpkUmpUTw9LmcOqDkUqtGOeLdNeyBjqM.png\";s:17:\"proof_uploaded_at\";s:19:\"2026-10-06 17:12:24\";s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 17:12:11\";s:10:\"updated_at\";s:19:\"2026-10-06 17:13:14\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-BNEJIR8QFB\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:9:\"210000.00\";s:16:\"remaining_amount\";s:4:\"0.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:4:\"paid\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-07 17:12:11\";s:7:\"paid_at\";s:19:\"2026-10-06 17:13:14\";s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";s:51:\"proofs/WrmyZ098hpkUmpUTw9LmcOqDkUqtGOeLdNeyBjqM.png\";s:17:\"proof_uploaded_at\";s:19:\"2026-10-06 17:12:24\";s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-06 17:12:11\";s:10:\"updated_at\";s:19:\"2026-10-06 17:13:14\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:2:{s:4:\"user\";N;s:7:\"package\";N;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a1049e-afda-7188-b2f9-9fa3b404f6d3\";s:9:\"user_uuid\";s:36:\"c2187aff-68d0-40b9-a6f1-52738cee4ea4\";s:12:\"order_number\";s:14:\"ORD-I5HKUWFTWJ\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:9:\"210000.00\";s:16:\"remaining_amount\";s:4:\"0.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:4:\"paid\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-05 08:54:31\";s:7:\"paid_at\";s:19:\"2026-10-04 09:55:55\";s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";s:51:\"proofs/lTa9j1FbqtOaGW4LDEw95vVjQ9JDz24MwX2UnFMi.png\";s:17:\"proof_uploaded_at\";s:19:\"2026-10-04 09:48:54\";s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-04 08:54:31\";s:10:\"updated_at\";s:19:\"2026-10-04 09:55:55\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a1049e-afda-7188-b2f9-9fa3b404f6d3\";s:9:\"user_uuid\";s:36:\"c2187aff-68d0-40b9-a6f1-52738cee4ea4\";s:12:\"order_number\";s:14:\"ORD-I5HKUWFTWJ\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a095f2-d286-7241-b566-8027529b8196\";s:19:\"package_option_uuid\";s:36:\"01a09966-2965-7201-ab57-9f085e23bbc7\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"210000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"210000.00\";s:11:\"amount_paid\";s:9:\"210000.00\";s:16:\"remaining_amount\";s:4:\"0.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:4:\"paid\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-10-05 08:54:31\";s:7:\"paid_at\";s:19:\"2026-10-04 09:55:55\";s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";s:51:\"proofs/lTa9j1FbqtOaGW4LDEw95vVjQ9JDz24MwX2UnFMi.png\";s:17:\"proof_uploaded_at\";s:19:\"2026-10-04 09:48:54\";s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-10-04 08:54:31\";s:10:\"updated_at\";s:19:\"2026-10-04 09:55:55\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:2:{s:4:\"user\";N;s:7:\"package\";N;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:4;O:24:\"App\\Models\\Payment\\Order\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"orders\";s:13:\"\0*\0primaryKey\";s:4:\"uuid\";s:10:\"\0*\0keyType\";s:6:\"string\";s:12:\"incrementing\";b:0;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:30:{s:4:\"uuid\";s:36:\"01a09b1e-c44e-70ae-98b1-d19e19c7b4fe\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-QCQL0MKBRP\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a06234-aac1-722c-84d6-c46d4150e70f\";s:19:\"package_option_uuid\";s:36:\"01a09966-af8d-7252-bbf0-f1665358611d\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"230000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"230000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"230000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"expired\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-09-14 21:14:39\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-09-13 21:14:39\";s:10:\"updated_at\";s:19:\"2026-10-04 09:48:58\";}s:11:\"\0*\0original\";a:30:{s:4:\"uuid\";s:36:\"01a09b1e-c44e-70ae-98b1-d19e19c7b4fe\";s:9:\"user_uuid\";s:36:\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\";s:12:\"order_number\";s:14:\"ORD-QCQL0MKBRP\";s:4:\"type\";s:7:\"package\";s:12:\"package_uuid\";s:36:\"01a06234-aac1-722c-84d6-c46d4150e70f\";s:19:\"package_option_uuid\";s:36:\"01a09966-af8d-7252-bbf0-f1665358611d\";s:10:\"event_date\";N;s:6:\"amount\";s:9:\"230000.00\";s:14:\"payment_scheme\";s:4:\"full\";s:12:\"total_amount\";s:9:\"230000.00\";s:11:\"amount_paid\";s:4:\"0.00\";s:16:\"remaining_amount\";s:9:\"230000.00\";s:14:\"balance_due_at\";N;s:24:\"balance_reminder_sent_at\";N;s:6:\"status\";s:7:\"expired\";s:11:\"pending_key\";N;s:10:\"expired_at\";s:19:\"2026-09-14 21:14:39\";s:7:\"paid_at\";N;s:10:\"admin_note\";N;s:19:\"assigned_admin_uuid\";N;s:16:\"proof_image_path\";N;s:17:\"proof_uploaded_at\";N;s:15:\"sla_reminded_at\";N;s:17:\"approval_snapshot\";N;s:6:\"addons\";N;s:10:\"promo_code\";N;s:14:\"promo_discount\";i:0;s:14:\"promo_snapshot\";N;s:10:\"created_at\";s:19:\"2026-09-13 21:14:39\";s:10:\"updated_at\";s:19:\"2026-10-04 09:48:58\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:15:{s:6:\"amount\";s:9:\"decimal:2\";s:12:\"total_amount\";s:9:\"decimal:2\";s:11:\"amount_paid\";s:9:\"decimal:2\";s:16:\"remaining_amount\";s:9:\"decimal:2\";s:14:\"balance_due_at\";s:8:\"datetime\";s:24:\"balance_reminder_sent_at\";s:8:\"datetime\";s:10:\"event_date\";s:4:\"date\";s:10:\"expired_at\";s:8:\"datetime\";s:7:\"paid_at\";s:8:\"datetime\";s:15:\"sla_reminded_at\";s:8:\"datetime\";s:17:\"proof_uploaded_at\";s:8:\"datetime\";s:17:\"approval_snapshot\";s:5:\"array\";s:6:\"addons\";s:5:\"array\";s:14:\"promo_snapshot\";s:5:\"array\";s:14:\"promo_discount\";s:7:\"integer\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:2:{s:4:\"user\";N;s:7:\"package\";N;}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:1;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:27:{i:0;s:9:\"user_uuid\";i:1;s:12:\"order_number\";i:2;s:4:\"type\";i:3;s:12:\"package_uuid\";i:4;s:19:\"package_option_uuid\";i:5;s:10:\"event_date\";i:6;s:6:\"amount\";i:7;s:14:\"payment_scheme\";i:8;s:12:\"total_amount\";i:9;s:11:\"amount_paid\";i:10;s:16:\"remaining_amount\";i:11;s:14:\"balance_due_at\";i:12;s:24:\"balance_reminder_sent_at\";i:13;s:6:\"status\";i:14;s:10:\"expired_at\";i:15;s:7:\"paid_at\";i:16;s:10:\"admin_note\";i:17;s:19:\"assigned_admin_uuid\";i:18;s:16:\"proof_image_path\";i:19;s:17:\"proof_uploaded_at\";i:20;s:15:\"sla_reminded_at\";i:21;s:17:\"approval_snapshot\";i:22;s:11:\"pending_key\";i:23;s:6:\"addons\";i:24;s:10:\"promo_code\";i:25;s:14:\"promo_discount\";i:26;s:14:\"promo_snapshot\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1795307192),
('dashboard.admin.summary.v3.4a86a1ef-3d85-43aa-b720-a09eabea6daf.d1bd69aa28fdb3950b3fa4bc9bf89d87cf07a728', 'a:11:{s:12:\"revenueChart\";a:3:{s:5:\"daily\";a:2:{s:6:\"labels\";a:30:{i:0;s:6:\"24 Okt\";i:1;s:6:\"25 Okt\";i:2;s:6:\"26 Okt\";i:3;s:6:\"27 Okt\";i:4;s:6:\"28 Okt\";i:5;s:6:\"29 Okt\";i:6;s:6:\"30 Okt\";i:7;s:6:\"31 Okt\";i:8;s:6:\"01 Nov\";i:9;s:6:\"02 Nov\";i:10;s:6:\"03 Nov\";i:11;s:6:\"04 Nov\";i:12;s:6:\"05 Nov\";i:13;s:6:\"06 Nov\";i:14;s:6:\"07 Nov\";i:15;s:6:\"08 Nov\";i:16;s:6:\"09 Nov\";i:17;s:6:\"10 Nov\";i:18;s:6:\"11 Nov\";i:19;s:6:\"12 Nov\";i:20;s:6:\"13 Nov\";i:21;s:6:\"14 Nov\";i:22;s:6:\"15 Nov\";i:23;s:6:\"16 Nov\";i:24;s:6:\"17 Nov\";i:25;s:6:\"18 Nov\";i:26;s:6:\"19 Nov\";i:27;s:6:\"20 Nov\";i:28;s:6:\"21 Nov\";i:29;s:6:\"22 Nov\";}s:4:\"data\";a:30:{i:0;d:0;i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;i:5;d:0;i:6;d:0;i:7;d:0;i:8;d:0;i:9;d:0;i:10;d:0;i:11;d:0;i:12;d:0;i:13;d:0;i:14;d:0;i:15;d:0;i:16;d:0;i:17;d:0;i:18;d:0;i:19;d:0;i:20;d:0;i:21;d:0;i:22;d:0;i:23;d:0;i:24;d:0;i:25;d:0;i:26;d:0;i:27;d:0;i:28;d:0;i:29;d:0;}}s:7:\"monthly\";a:2:{s:6:\"labels\";a:2:{i:0;s:8:\"Okt 2026\";i:1;s:8:\"Nov 2026\";}s:4:\"data\";a:2:{i:0;d:0;i:1;d:0;}}s:6:\"yearly\";a:2:{s:6:\"labels\";a:1:{i:0;s:4:\"2026\";}s:4:\"data\";a:1:{i:0;d:0;}}}s:4:\"site\";a:12:{s:18:\"visitorDailyLabels\";a:30:{i:0;s:6:\"24 Okt\";i:1;s:6:\"25 Okt\";i:2;s:6:\"26 Okt\";i:3;s:6:\"27 Okt\";i:4;s:6:\"28 Okt\";i:5;s:6:\"29 Okt\";i:6;s:6:\"30 Okt\";i:7;s:6:\"31 Okt\";i:8;s:6:\"01 Nov\";i:9;s:6:\"02 Nov\";i:10;s:6:\"03 Nov\";i:11;s:6:\"04 Nov\";i:12;s:6:\"05 Nov\";i:13;s:6:\"06 Nov\";i:14;s:6:\"07 Nov\";i:15;s:6:\"08 Nov\";i:16;s:6:\"09 Nov\";i:17;s:6:\"10 Nov\";i:18;s:6:\"11 Nov\";i:19;s:6:\"12 Nov\";i:20;s:6:\"13 Nov\";i:21;s:6:\"14 Nov\";i:22;s:6:\"15 Nov\";i:23;s:6:\"16 Nov\";i:24;s:6:\"17 Nov\";i:25;s:6:\"18 Nov\";i:26;s:6:\"19 Nov\";i:27;s:6:\"20 Nov\";i:28;s:6:\"21 Nov\";i:29;s:6:\"22 Nov\";}s:17:\"visitorDailyTotal\";a:30:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:24;i:27;i:399;i:28;i:0;i:29;i:0;}s:18:\"visitorDailyUnique\";a:30:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:10;i:27;i:19;i:28;i:0;i:29;i:0;}s:18:\"visitorMonthLabels\";a:2:{i:0;s:8:\"Okt 2026\";i:1;s:8:\"Nov 2026\";}s:17:\"visitorMonthTotal\";a:2:{i:0;i:0;i:1;i:423;}s:18:\"visitorMonthUnique\";a:2:{i:0;i:0;i:1;i:29;}s:14:\"activeVisitors\";i:0;s:23:\"activeVisitorsUpdatedAt\";s:5:\"07.34\";s:22:\"analyticsContentLabels\";a:8:{i:0;s:8:\"/vendors\";i:1;s:4:\"/faq\";i:2;s:1:\"/\";i:3;s:6:\"/about\";i:4;s:20:\"/formulir-konsultasi\";i:5;s:8:\"/contact\";i:6;s:10:\"/schedules\";i:7;s:14:\"/art-of-living\";}s:22:\"analyticsContentCounts\";a:8:{i:0;i:159;i:1;i:108;i:2;i:62;i:3;i:28;i:4;i:28;i:5;i:7;i:6;i:6;i:7;i:6;}s:21:\"analyticsDeviceLabels\";a:3:{i:0;s:7:\"Desktop\";i:1;s:6:\"Ponsel\";i:2;s:6:\"Tablet\";}s:21:\"analyticsDeviceCounts\";a:3:{i:0;i:417;i:1;i:6;i:2;i:0;}}s:5:\"stats\";a:9:{i:0;a:8:{s:5:\"label\";s:11:\"Member baru\";s:5:\"value\";i:1;s:8:\"previous\";i:1;s:6:\"change\";i:0;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:1;}s:4:\"icon\";s:12:\"bi bi-people\";s:5:\"class\";s:6:\"purple\";s:5:\"route\";s:38:\"http://localhost:8000/backend/pengguna\";}i:1;a:8:{s:5:\"label\";s:11:\"Vendor baru\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:18:\"bi bi-person-badge\";s:5:\"class\";s:4:\"blue\";s:5:\"route\";s:36:\"http://localhost:8000/backend/vendor\";}i:2;a:8:{s:5:\"label\";s:11:\"Venue aktif\";s:5:\"value\";i:1;s:8:\"previous\";N;s:6:\"change\";N;s:5:\"spark\";a:0:{}s:4:\"icon\";s:13:\"bi bi-geo-alt\";s:5:\"class\";s:5:\"green\";s:5:\"route\";s:36:\"http://localhost:8000/backend/venues\";}i:3;a:8:{s:5:\"label\";s:9:\"Lead baru\";s:5:\"value\";i:1;s:8:\"previous\";i:0;s:6:\"change\";i:100;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:1;}s:4:\"icon\";s:17:\"bi bi-person-plus\";s:5:\"class\";s:4:\"cyan\";s:5:\"route\";s:49:\"http://localhost:8000/backend/konsultasi?lead=New\";}i:4;a:8:{s:5:\"label\";s:18:\"Follow-up terlewat\";s:5:\"value\";i:1;s:8:\"previous\";N;s:6:\"change\";N;s:5:\"spark\";a:0:{}s:4:\"icon\";s:19:\"bi bi-clock-history\";s:5:\"class\";s:3:\"red\";s:5:\"route\";s:58:\"http://localhost:8000/backend/konsultasi?follow_up=overdue\";}i:5;a:8:{s:5:\"label\";s:12:\"Pesanan baru\";s:5:\"value\";i:0;s:8:\"previous\";i:4;s:6:\"change\";i:-100;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:14:\"bi bi-bag-plus\";s:5:\"class\";s:6:\"yellow\";s:5:\"route\";s:36:\"http://localhost:8000/backend/orders\";}i:6;a:8:{s:5:\"label\";s:5:\"Paket\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:14:\"bi bi-box-seam\";s:5:\"class\";s:4:\"pink\";s:5:\"route\";s:38:\"http://localhost:8000/backend/packages\";}i:7;a:8:{s:5:\"label\";s:5:\"Event\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:20:\"bi bi-calendar-event\";s:5:\"class\";s:5:\"green\";s:5:\"route\";s:36:\"http://localhost:8000/backend/events\";}i:8;a:8:{s:5:\"label\";s:7:\"Artikel\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:23:\"bi bi-file-earmark-text\";s:5:\"class\";s:4:\"blue\";s:5:\"route\";s:38:\"http://localhost:8000/backend/articles\";}}s:20:\"consultationPipeline\";a:6:{i:0;a:6:{s:3:\"key\";s:3:\"New\";s:5:\"label\";s:4:\"Baru\";s:5:\"count\";i:1;s:5:\"limit\";i:20;s:5:\"badge\";s:12:\"bg-secondary\";s:5:\"route\";s:49:\"http://localhost:8000/backend/konsultasi?lead=New\";}i:1;a:6:{s:3:\"key\";s:9:\"Contacted\";s:5:\"label\";s:15:\"Sudah Dihubungi\";s:5:\"count\";i:0;s:5:\"limit\";i:20;s:5:\"badge\";s:17:\"bg-info text-dark\";s:5:\"route\";s:55:\"http://localhost:8000/backend/konsultasi?lead=Contacted\";}i:2;a:6:{s:3:\"key\";s:10:\"Interested\";s:5:\"label\";s:8:\"Berminat\";s:5:\"count\";i:0;s:5:\"limit\";i:15;s:5:\"badge\";s:10:\"bg-primary\";s:5:\"route\";s:56:\"http://localhost:8000/backend/konsultasi?lead=Interested\";}i:3;a:6:{s:3:\"key\";s:11:\"Negotiation\";s:5:\"label\";s:9:\"Negosiasi\";s:5:\"count\";i:0;s:5:\"limit\";i:10;s:5:\"badge\";s:20:\"bg-warning text-dark\";s:5:\"route\";s:57:\"http://localhost:8000/backend/konsultasi?lead=Negotiation\";}i:4;a:6:{s:3:\"key\";s:9:\"Converted\";s:5:\"label\";s:11:\"Terkonversi\";s:5:\"count\";i:0;s:5:\"limit\";i:0;s:5:\"badge\";s:10:\"bg-success\";s:5:\"route\";s:55:\"http://localhost:8000/backend/konsultasi?lead=Converted\";}i:5;a:6:{s:3:\"key\";s:4:\"Lost\";s:5:\"label\";s:12:\"Tidak Lanjut\";s:5:\"count\";i:0;s:5:\"limit\";i:0;s:5:\"badge\";s:9:\"bg-danger\";s:5:\"route\";s:50:\"http://localhost:8000/backend/konsultasi?lead=Lost\";}}s:22:\"consultationHighlights\";a:4:{s:15:\"conversion_rate\";i:0;s:10:\"sla_breach\";i:1;s:11:\"sla_warning\";i:0;s:9:\"converted\";i:0;}s:6:\"funnel\";a:6:{i:0;a:2:{s:5:\"label\";s:15:\"Lead konsultasi\";s:5:\"value\";i:1;}i:1;a:2:{s:5:\"label\";s:10:\"Lead panas\";s:5:\"value\";i:0;}i:2;a:2:{s:5:\"label\";s:11:\"Terkonversi\";s:5:\"value\";i:0;}i:3;a:2:{s:5:\"label\";s:7:\"Pesanan\";s:5:\"value\";i:0;}i:4;a:2:{s:5:\"label\";s:5:\"Lunas\";s:5:\"value\";i:0;}i:5;a:2:{s:5:\"label\";s:17:\"Keanggotaan aktif\";s:5:\"value\";i:0;}}s:14:\"newOrdersCount\";i:0;s:18:\"newMembershipCount\";i:0;s:12:\"revenueMonth\";d:0;s:12:\"revenueTotal\";d:420000;s:8:\"security\";a:2:{s:12:\"failedLogins\";i:0;s:8:\"lockouts\";i:0;}}', 1795307965),
('dashboard.admin.summary.v3.50d173a0-1732-495d-9853-3057695035cd.d1bd69aa28fdb3950b3fa4bc9bf89d87cf07a728', 'a:11:{s:12:\"revenueChart\";a:3:{s:5:\"daily\";a:2:{s:6:\"labels\";a:30:{i:0;s:6:\"24 Okt\";i:1;s:6:\"25 Okt\";i:2;s:6:\"26 Okt\";i:3;s:6:\"27 Okt\";i:4;s:6:\"28 Okt\";i:5;s:6:\"29 Okt\";i:6;s:6:\"30 Okt\";i:7;s:6:\"31 Okt\";i:8;s:6:\"01 Nov\";i:9;s:6:\"02 Nov\";i:10;s:6:\"03 Nov\";i:11;s:6:\"04 Nov\";i:12;s:6:\"05 Nov\";i:13;s:6:\"06 Nov\";i:14;s:6:\"07 Nov\";i:15;s:6:\"08 Nov\";i:16;s:6:\"09 Nov\";i:17;s:6:\"10 Nov\";i:18;s:6:\"11 Nov\";i:19;s:6:\"12 Nov\";i:20;s:6:\"13 Nov\";i:21;s:6:\"14 Nov\";i:22;s:6:\"15 Nov\";i:23;s:6:\"16 Nov\";i:24;s:6:\"17 Nov\";i:25;s:6:\"18 Nov\";i:26;s:6:\"19 Nov\";i:27;s:6:\"20 Nov\";i:28;s:6:\"21 Nov\";i:29;s:6:\"22 Nov\";}s:4:\"data\";a:30:{i:0;d:0;i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;i:5;d:0;i:6;d:0;i:7;d:0;i:8;d:0;i:9;d:0;i:10;d:0;i:11;d:0;i:12;d:0;i:13;d:0;i:14;d:0;i:15;d:0;i:16;d:0;i:17;d:0;i:18;d:0;i:19;d:0;i:20;d:0;i:21;d:0;i:22;d:0;i:23;d:0;i:24;d:0;i:25;d:0;i:26;d:0;i:27;d:0;i:28;d:0;i:29;d:0;}}s:7:\"monthly\";a:2:{s:6:\"labels\";a:2:{i:0;s:8:\"Okt 2026\";i:1;s:8:\"Nov 2026\";}s:4:\"data\";a:2:{i:0;d:0;i:1;d:0;}}s:6:\"yearly\";a:2:{s:6:\"labels\";a:1:{i:0;s:4:\"2026\";}s:4:\"data\";a:1:{i:0;d:0;}}}s:4:\"site\";a:12:{s:18:\"visitorDailyLabels\";a:30:{i:0;s:6:\"24 Okt\";i:1;s:6:\"25 Okt\";i:2;s:6:\"26 Okt\";i:3;s:6:\"27 Okt\";i:4;s:6:\"28 Okt\";i:5;s:6:\"29 Okt\";i:6;s:6:\"30 Okt\";i:7;s:6:\"31 Okt\";i:8;s:6:\"01 Nov\";i:9;s:6:\"02 Nov\";i:10;s:6:\"03 Nov\";i:11;s:6:\"04 Nov\";i:12;s:6:\"05 Nov\";i:13;s:6:\"06 Nov\";i:14;s:6:\"07 Nov\";i:15;s:6:\"08 Nov\";i:16;s:6:\"09 Nov\";i:17;s:6:\"10 Nov\";i:18;s:6:\"11 Nov\";i:19;s:6:\"12 Nov\";i:20;s:6:\"13 Nov\";i:21;s:6:\"14 Nov\";i:22;s:6:\"15 Nov\";i:23;s:6:\"16 Nov\";i:24;s:6:\"17 Nov\";i:25;s:6:\"18 Nov\";i:26;s:6:\"19 Nov\";i:27;s:6:\"20 Nov\";i:28;s:6:\"21 Nov\";i:29;s:6:\"22 Nov\";}s:17:\"visitorDailyTotal\";a:30:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:24;i:27;i:399;i:28;i:0;i:29;i:10;}s:18:\"visitorDailyUnique\";a:30:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:10;i:27;i:19;i:28;i:0;i:29;i:1;}s:18:\"visitorMonthLabels\";a:2:{i:0;s:8:\"Okt 2026\";i:1;s:8:\"Nov 2026\";}s:17:\"visitorMonthTotal\";a:2:{i:0;i:0;i:1;i:433;}s:18:\"visitorMonthUnique\";a:2:{i:0;i:0;i:1;i:29;}s:14:\"activeVisitors\";i:1;s:23:\"activeVisitorsUpdatedAt\";s:5:\"10.03\";s:22:\"analyticsContentLabels\";a:8:{i:0;s:8:\"/vendors\";i:1;s:4:\"/faq\";i:2;s:1:\"/\";i:3;s:6:\"/about\";i:4;s:20:\"/formulir-konsultasi\";i:5;s:8:\"/contact\";i:6;s:10:\"/schedules\";i:7;s:9:\"/packages\";}s:22:\"analyticsContentCounts\";a:8:{i:0;i:160;i:1;i:108;i:2;i:68;i:3;i:29;i:4;i:28;i:5;i:7;i:6;i:6;i:7;i:6;}s:21:\"analyticsDeviceLabels\";a:3:{i:0;s:7:\"Desktop\";i:1;s:6:\"Ponsel\";i:2;s:6:\"Tablet\";}s:21:\"analyticsDeviceCounts\";a:3:{i:0;i:427;i:1;i:6;i:2;i:0;}}s:5:\"stats\";a:9:{i:0;a:8:{s:5:\"label\";s:11:\"Member baru\";s:5:\"value\";i:1;s:8:\"previous\";i:1;s:6:\"change\";i:0;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:1;}s:4:\"icon\";s:12:\"bi bi-people\";s:5:\"class\";s:6:\"purple\";s:5:\"route\";s:38:\"http://localhost:8000/backend/pengguna\";}i:1;a:8:{s:5:\"label\";s:11:\"Vendor baru\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:18:\"bi bi-person-badge\";s:5:\"class\";s:4:\"blue\";s:5:\"route\";s:36:\"http://localhost:8000/backend/vendor\";}i:2;a:8:{s:5:\"label\";s:11:\"Venue aktif\";s:5:\"value\";i:1;s:8:\"previous\";N;s:6:\"change\";N;s:5:\"spark\";a:0:{}s:4:\"icon\";s:13:\"bi bi-geo-alt\";s:5:\"class\";s:5:\"green\";s:5:\"route\";s:36:\"http://localhost:8000/backend/venues\";}i:3;a:8:{s:5:\"label\";s:9:\"Lead baru\";s:5:\"value\";i:1;s:8:\"previous\";i:0;s:6:\"change\";i:100;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:1;}s:4:\"icon\";s:17:\"bi bi-person-plus\";s:5:\"class\";s:4:\"cyan\";s:5:\"route\";s:49:\"http://localhost:8000/backend/konsultasi?lead=New\";}i:4;a:8:{s:5:\"label\";s:18:\"Follow-up terlewat\";s:5:\"value\";i:1;s:8:\"previous\";N;s:6:\"change\";N;s:5:\"spark\";a:0:{}s:4:\"icon\";s:19:\"bi bi-clock-history\";s:5:\"class\";s:3:\"red\";s:5:\"route\";s:58:\"http://localhost:8000/backend/konsultasi?follow_up=overdue\";}i:5;a:8:{s:5:\"label\";s:12:\"Pesanan baru\";s:5:\"value\";i:0;s:8:\"previous\";i:4;s:6:\"change\";i:-100;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:14:\"bi bi-bag-plus\";s:5:\"class\";s:6:\"yellow\";s:5:\"route\";s:36:\"http://localhost:8000/backend/orders\";}i:6;a:8:{s:5:\"label\";s:5:\"Paket\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:14:\"bi bi-box-seam\";s:5:\"class\";s:4:\"pink\";s:5:\"route\";s:38:\"http://localhost:8000/backend/packages\";}i:7;a:8:{s:5:\"label\";s:5:\"Event\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:20:\"bi bi-calendar-event\";s:5:\"class\";s:5:\"green\";s:5:\"route\";s:36:\"http://localhost:8000/backend/events\";}i:8;a:8:{s:5:\"label\";s:7:\"Artikel\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:23:\"bi bi-file-earmark-text\";s:5:\"class\";s:4:\"blue\";s:5:\"route\";s:38:\"http://localhost:8000/backend/articles\";}}s:20:\"consultationPipeline\";a:6:{i:0;a:6:{s:3:\"key\";s:3:\"New\";s:5:\"label\";s:4:\"Baru\";s:5:\"count\";i:1;s:5:\"limit\";i:20;s:5:\"badge\";s:12:\"bg-secondary\";s:5:\"route\";s:49:\"http://localhost:8000/backend/konsultasi?lead=New\";}i:1;a:6:{s:3:\"key\";s:9:\"Contacted\";s:5:\"label\";s:15:\"Sudah Dihubungi\";s:5:\"count\";i:0;s:5:\"limit\";i:20;s:5:\"badge\";s:17:\"bg-info text-dark\";s:5:\"route\";s:55:\"http://localhost:8000/backend/konsultasi?lead=Contacted\";}i:2;a:6:{s:3:\"key\";s:10:\"Interested\";s:5:\"label\";s:8:\"Berminat\";s:5:\"count\";i:0;s:5:\"limit\";i:15;s:5:\"badge\";s:10:\"bg-primary\";s:5:\"route\";s:56:\"http://localhost:8000/backend/konsultasi?lead=Interested\";}i:3;a:6:{s:3:\"key\";s:11:\"Negotiation\";s:5:\"label\";s:9:\"Negosiasi\";s:5:\"count\";i:0;s:5:\"limit\";i:10;s:5:\"badge\";s:20:\"bg-warning text-dark\";s:5:\"route\";s:57:\"http://localhost:8000/backend/konsultasi?lead=Negotiation\";}i:4;a:6:{s:3:\"key\";s:9:\"Converted\";s:5:\"label\";s:11:\"Terkonversi\";s:5:\"count\";i:0;s:5:\"limit\";i:0;s:5:\"badge\";s:10:\"bg-success\";s:5:\"route\";s:55:\"http://localhost:8000/backend/konsultasi?lead=Converted\";}i:5;a:6:{s:3:\"key\";s:4:\"Lost\";s:5:\"label\";s:12:\"Tidak Lanjut\";s:5:\"count\";i:0;s:5:\"limit\";i:0;s:5:\"badge\";s:9:\"bg-danger\";s:5:\"route\";s:50:\"http://localhost:8000/backend/konsultasi?lead=Lost\";}}s:22:\"consultationHighlights\";a:4:{s:15:\"conversion_rate\";i:0;s:10:\"sla_breach\";i:1;s:11:\"sla_warning\";i:0;s:9:\"converted\";i:0;}s:6:\"funnel\";a:6:{i:0;a:2:{s:5:\"label\";s:15:\"Lead konsultasi\";s:5:\"value\";i:1;}i:1;a:2:{s:5:\"label\";s:10:\"Lead panas\";s:5:\"value\";i:0;}i:2;a:2:{s:5:\"label\";s:11:\"Terkonversi\";s:5:\"value\";i:0;}i:3;a:2:{s:5:\"label\";s:7:\"Pesanan\";s:5:\"value\";i:0;}i:4;a:2:{s:5:\"label\";s:5:\"Lunas\";s:5:\"value\";i:0;}i:5;a:2:{s:5:\"label\";s:17:\"Keanggotaan aktif\";s:5:\"value\";i:0;}}s:14:\"newOrdersCount\";i:0;s:18:\"newMembershipCount\";i:0;s:12:\"revenueMonth\";d:0;s:12:\"revenueTotal\";d:420000;s:8:\"security\";a:2:{s:12:\"failedLogins\";i:0;s:8:\"lockouts\";i:0;}}', 1795316918),
('dashboard.admin.summary.v3.a0e9f49c-2abc-4b0e-a246-121f739f4259.d1bd69aa28fdb3950b3fa4bc9bf89d87cf07a728', 'a:11:{s:12:\"revenueChart\";a:3:{s:5:\"daily\";a:2:{s:6:\"labels\";a:30:{i:0;s:6:\"24 Okt\";i:1;s:6:\"25 Okt\";i:2;s:6:\"26 Okt\";i:3;s:6:\"27 Okt\";i:4;s:6:\"28 Okt\";i:5;s:6:\"29 Okt\";i:6;s:6:\"30 Okt\";i:7;s:6:\"31 Okt\";i:8;s:6:\"01 Nov\";i:9;s:6:\"02 Nov\";i:10;s:6:\"03 Nov\";i:11;s:6:\"04 Nov\";i:12;s:6:\"05 Nov\";i:13;s:6:\"06 Nov\";i:14;s:6:\"07 Nov\";i:15;s:6:\"08 Nov\";i:16;s:6:\"09 Nov\";i:17;s:6:\"10 Nov\";i:18;s:6:\"11 Nov\";i:19;s:6:\"12 Nov\";i:20;s:6:\"13 Nov\";i:21;s:6:\"14 Nov\";i:22;s:6:\"15 Nov\";i:23;s:6:\"16 Nov\";i:24;s:6:\"17 Nov\";i:25;s:6:\"18 Nov\";i:26;s:6:\"19 Nov\";i:27;s:6:\"20 Nov\";i:28;s:6:\"21 Nov\";i:29;s:6:\"22 Nov\";}s:4:\"data\";a:30:{i:0;d:0;i:1;d:0;i:2;d:0;i:3;d:0;i:4;d:0;i:5;d:0;i:6;d:0;i:7;d:0;i:8;d:0;i:9;d:0;i:10;d:0;i:11;d:0;i:12;d:0;i:13;d:0;i:14;d:0;i:15;d:0;i:16;d:0;i:17;d:0;i:18;d:0;i:19;d:0;i:20;d:0;i:21;d:0;i:22;d:0;i:23;d:0;i:24;d:0;i:25;d:0;i:26;d:0;i:27;d:0;i:28;d:0;i:29;d:0;}}s:7:\"monthly\";a:2:{s:6:\"labels\";a:2:{i:0;s:8:\"Okt 2026\";i:1;s:8:\"Nov 2026\";}s:4:\"data\";a:2:{i:0;d:0;i:1;d:0;}}s:6:\"yearly\";a:2:{s:6:\"labels\";a:1:{i:0;s:4:\"2026\";}s:4:\"data\";a:1:{i:0;d:0;}}}s:4:\"site\";a:12:{s:18:\"visitorDailyLabels\";a:30:{i:0;s:6:\"24 Okt\";i:1;s:6:\"25 Okt\";i:2;s:6:\"26 Okt\";i:3;s:6:\"27 Okt\";i:4;s:6:\"28 Okt\";i:5;s:6:\"29 Okt\";i:6;s:6:\"30 Okt\";i:7;s:6:\"31 Okt\";i:8;s:6:\"01 Nov\";i:9;s:6:\"02 Nov\";i:10;s:6:\"03 Nov\";i:11;s:6:\"04 Nov\";i:12;s:6:\"05 Nov\";i:13;s:6:\"06 Nov\";i:14;s:6:\"07 Nov\";i:15;s:6:\"08 Nov\";i:16;s:6:\"09 Nov\";i:17;s:6:\"10 Nov\";i:18;s:6:\"11 Nov\";i:19;s:6:\"12 Nov\";i:20;s:6:\"13 Nov\";i:21;s:6:\"14 Nov\";i:22;s:6:\"15 Nov\";i:23;s:6:\"16 Nov\";i:24;s:6:\"17 Nov\";i:25;s:6:\"18 Nov\";i:26;s:6:\"19 Nov\";i:27;s:6:\"20 Nov\";i:28;s:6:\"21 Nov\";i:29;s:6:\"22 Nov\";}s:17:\"visitorDailyTotal\";a:30:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:24;i:27;i:399;i:28;i:0;i:29;i:0;}s:18:\"visitorDailyUnique\";a:30:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;i:10;i:0;i:11;i:0;i:12;i:0;i:13;i:0;i:14;i:0;i:15;i:0;i:16;i:0;i:17;i:0;i:18;i:0;i:19;i:0;i:20;i:0;i:21;i:0;i:22;i:0;i:23;i:0;i:24;i:0;i:25;i:0;i:26;i:10;i:27;i:19;i:28;i:0;i:29;i:0;}s:18:\"visitorMonthLabels\";a:2:{i:0;s:8:\"Okt 2026\";i:1;s:8:\"Nov 2026\";}s:17:\"visitorMonthTotal\";a:2:{i:0;i:0;i:1;i:423;}s:18:\"visitorMonthUnique\";a:2:{i:0;i:0;i:1;i:29;}s:14:\"activeVisitors\";i:0;s:23:\"activeVisitorsUpdatedAt\";s:5:\"07.25\";s:22:\"analyticsContentLabels\";a:8:{i:0;s:8:\"/vendors\";i:1;s:4:\"/faq\";i:2;s:1:\"/\";i:3;s:6:\"/about\";i:4;s:20:\"/formulir-konsultasi\";i:5;s:8:\"/contact\";i:6;s:10:\"/schedules\";i:7;s:14:\"/art-of-living\";}s:22:\"analyticsContentCounts\";a:8:{i:0;i:159;i:1;i:108;i:2;i:62;i:3;i:28;i:4;i:28;i:5;i:7;i:6;i:6;i:7;i:6;}s:21:\"analyticsDeviceLabels\";a:3:{i:0;s:7:\"Desktop\";i:1;s:6:\"Ponsel\";i:2;s:6:\"Tablet\";}s:21:\"analyticsDeviceCounts\";a:3:{i:0;i:417;i:1;i:6;i:2;i:0;}}s:5:\"stats\";a:9:{i:0;a:8:{s:5:\"label\";s:11:\"Member baru\";s:5:\"value\";i:1;s:8:\"previous\";i:1;s:6:\"change\";i:0;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:1;}s:4:\"icon\";s:12:\"bi bi-people\";s:5:\"class\";s:6:\"purple\";s:5:\"route\";s:38:\"http://localhost:8000/backend/pengguna\";}i:1;a:8:{s:5:\"label\";s:11:\"Vendor baru\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:18:\"bi bi-person-badge\";s:5:\"class\";s:4:\"blue\";s:5:\"route\";s:36:\"http://localhost:8000/backend/vendor\";}i:2;a:8:{s:5:\"label\";s:11:\"Venue aktif\";s:5:\"value\";i:1;s:8:\"previous\";N;s:6:\"change\";N;s:5:\"spark\";a:0:{}s:4:\"icon\";s:13:\"bi bi-geo-alt\";s:5:\"class\";s:5:\"green\";s:5:\"route\";s:36:\"http://localhost:8000/backend/venues\";}i:3;a:8:{s:5:\"label\";s:9:\"Lead baru\";s:5:\"value\";i:1;s:8:\"previous\";i:0;s:6:\"change\";i:100;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:1;}s:4:\"icon\";s:17:\"bi bi-person-plus\";s:5:\"class\";s:4:\"cyan\";s:5:\"route\";s:49:\"http://localhost:8000/backend/konsultasi?lead=New\";}i:4;a:8:{s:5:\"label\";s:18:\"Follow-up terlewat\";s:5:\"value\";i:1;s:8:\"previous\";N;s:6:\"change\";N;s:5:\"spark\";a:0:{}s:4:\"icon\";s:19:\"bi bi-clock-history\";s:5:\"class\";s:3:\"red\";s:5:\"route\";s:58:\"http://localhost:8000/backend/konsultasi?follow_up=overdue\";}i:5;a:8:{s:5:\"label\";s:12:\"Pesanan baru\";s:5:\"value\";i:0;s:8:\"previous\";i:4;s:6:\"change\";i:-100;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:14:\"bi bi-bag-plus\";s:5:\"class\";s:6:\"yellow\";s:5:\"route\";s:36:\"http://localhost:8000/backend/orders\";}i:6;a:8:{s:5:\"label\";s:5:\"Paket\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:14:\"bi bi-box-seam\";s:5:\"class\";s:4:\"pink\";s:5:\"route\";s:38:\"http://localhost:8000/backend/packages\";}i:7;a:8:{s:5:\"label\";s:5:\"Event\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:20:\"bi bi-calendar-event\";s:5:\"class\";s:5:\"green\";s:5:\"route\";s:36:\"http://localhost:8000/backend/events\";}i:8;a:8:{s:5:\"label\";s:7:\"Artikel\";s:5:\"value\";i:0;s:8:\"previous\";i:0;s:6:\"change\";N;s:5:\"spark\";a:10:{i:0;i:0;i:1;i:0;i:2;i:0;i:3;i:0;i:4;i:0;i:5;i:0;i:6;i:0;i:7;i:0;i:8;i:0;i:9;i:0;}s:4:\"icon\";s:23:\"bi bi-file-earmark-text\";s:5:\"class\";s:4:\"blue\";s:5:\"route\";s:38:\"http://localhost:8000/backend/articles\";}}s:20:\"consultationPipeline\";a:6:{i:0;a:6:{s:3:\"key\";s:3:\"New\";s:5:\"label\";s:4:\"Baru\";s:5:\"count\";i:1;s:5:\"limit\";i:20;s:5:\"badge\";s:12:\"bg-secondary\";s:5:\"route\";s:49:\"http://localhost:8000/backend/konsultasi?lead=New\";}i:1;a:6:{s:3:\"key\";s:9:\"Contacted\";s:5:\"label\";s:15:\"Sudah Dihubungi\";s:5:\"count\";i:0;s:5:\"limit\";i:20;s:5:\"badge\";s:17:\"bg-info text-dark\";s:5:\"route\";s:55:\"http://localhost:8000/backend/konsultasi?lead=Contacted\";}i:2;a:6:{s:3:\"key\";s:10:\"Interested\";s:5:\"label\";s:8:\"Berminat\";s:5:\"count\";i:0;s:5:\"limit\";i:15;s:5:\"badge\";s:10:\"bg-primary\";s:5:\"route\";s:56:\"http://localhost:8000/backend/konsultasi?lead=Interested\";}i:3;a:6:{s:3:\"key\";s:11:\"Negotiation\";s:5:\"label\";s:9:\"Negosiasi\";s:5:\"count\";i:0;s:5:\"limit\";i:10;s:5:\"badge\";s:20:\"bg-warning text-dark\";s:5:\"route\";s:57:\"http://localhost:8000/backend/konsultasi?lead=Negotiation\";}i:4;a:6:{s:3:\"key\";s:9:\"Converted\";s:5:\"label\";s:11:\"Terkonversi\";s:5:\"count\";i:0;s:5:\"limit\";i:0;s:5:\"badge\";s:10:\"bg-success\";s:5:\"route\";s:55:\"http://localhost:8000/backend/konsultasi?lead=Converted\";}i:5;a:6:{s:3:\"key\";s:4:\"Lost\";s:5:\"label\";s:12:\"Tidak Lanjut\";s:5:\"count\";i:0;s:5:\"limit\";i:0;s:5:\"badge\";s:9:\"bg-danger\";s:5:\"route\";s:50:\"http://localhost:8000/backend/konsultasi?lead=Lost\";}}s:22:\"consultationHighlights\";a:4:{s:15:\"conversion_rate\";i:0;s:10:\"sla_breach\";i:1;s:11:\"sla_warning\";i:0;s:9:\"converted\";i:0;}s:6:\"funnel\";a:6:{i:0;a:2:{s:5:\"label\";s:15:\"Lead konsultasi\";s:5:\"value\";i:1;}i:1;a:2:{s:5:\"label\";s:10:\"Lead panas\";s:5:\"value\";i:0;}i:2;a:2:{s:5:\"label\";s:11:\"Terkonversi\";s:5:\"value\";i:0;}i:3;a:2:{s:5:\"label\";s:7:\"Pesanan\";s:5:\"value\";i:0;}i:4;a:2:{s:5:\"label\";s:5:\"Lunas\";s:5:\"value\";i:0;}i:5;a:2:{s:5:\"label\";s:17:\"Keanggotaan aktif\";s:5:\"value\";i:0;}}s:14:\"newOrdersCount\";i:0;s:18:\"newMembershipCount\";i:0;s:12:\"revenueMonth\";d:0;s:12:\"revenueTotal\";d:420000;s:8:\"security\";a:2:{s:12:\"failedLogins\";i:0;s:8:\"lockouts\";i:0;}}', 1795307432),
('dashboard.admin.summary.version', 's:36:\"50d173a0-1732-495d-9853-3057695035cd\";', 2110668930);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('dashboard.artofliving.courses', 'a:1:{i:0;a:108:{s:11:\"bundle_type\";s:17:\"course_event_info\";s:14:\"formatted_date\";s:0:\"\";s:15:\"weekday_timings\";s:19:\"10:30 AM - 12:00 PM\";s:15:\"weekend_timings\";s:18:\"9:30 AM - 11:30 AM\";s:3:\"wkt\";s:29:\"POINT (106.879852 -6.1504392)\";s:4:\"type\";s:5:\"point\";s:8:\"end_date\";s:19:\"2026-11-24 05:00:00\";s:10:\"start_date\";s:19:\"2026-11-24 03:30:00\";s:17:\"last_updated_date\";s:19:\"2026-11-17 05:08:26\";s:12:\"created_date\";s:19:\"2026-05-19 08:22:05\";s:12:\"contact_name\";s:21:\"Artofliving Indonesia\";s:13:\"contact_names\";N;s:13:\"center_emails\";N;s:11:\"coordinates\";a:2:{i:0;d:106.879852;i:1;d:-6.1504392;}s:5:\"ctype\";s:6:\"344663\";s:12:\"common_ctype\";s:0:\"\";s:6:\"mctype\";s:4:\"1006\";s:7:\"address\";s:111:\"The Art of Living Center - Jakarta,  Jl. Danau Indah Raya Blok A1 No. 2 Sunter, North Jakarta, Indonesia, 14350\";s:13:\"address_short\";s:100:\"The Art of Living Center - Jakarta,  Jl. Danau Indah Raya Blok A1 No. 2 Sunter, North Jakarta, 14350\";s:16:\"street_address_1\";s:0:\"\";s:16:\"street_address_2\";s:41:\"Jl. Danau Indah Raya Blok A1 No. 2 Sunter\";s:4:\"city\";s:13:\"North Jakarta\";s:5:\"state\";s:0:\"\";s:15:\"zip_postal_code\";s:5:\"14350\";s:7:\"country\";s:2:\"ID\";s:5:\"email\";s:19:\"info@artofliving.id\";s:2:\"id\";i:1675701;s:6:\"sao_id\";i:740;s:8:\"sao_type\";i:1;s:9:\"course_id\";s:6:\"AK0589\";s:4:\"link\";s:37:\"www.artofliving.org/id-id/program/740\";s:16:\"local_center_url\";s:37:\"www.artofliving.org/id-id/node/196046\";s:14:\"many_day_event\";b:0;s:6:\"phones\";a:1:{i:0;s:10:\"0216513123\";}s:19:\"recur_event_display\";s:68:\"RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=TU;UNTIL=20270519T235959Z;WKST=SU\";s:12:\"is_recurring\";b:1;s:12:\"register_url\";s:90:\"https://register.artofliving.org/id/civicrm/event/register?id=791&lcMessages=en_GB&reset=1\";s:8:\"teachers\";N;s:13:\"teachers_info\";N;s:23:\"assistant_teachers_info\";N;s:14:\"organizer_info\";N;s:12:\"max_capacity\";s:0:\"\";s:5:\"title\";s:39:\"Sudarshan Kriya (SKY) Follow-up Session\";s:14:\"course_expense\";i:0;s:10:\"course_fee\";i:0;s:16:\"course_extra_fee\";i:0;s:16:\"early_course_fee\";i:0;s:19:\"early_repeaters_fee\";i:0;s:17:\"early_seniors_fee\";i:0;s:18:\"early_students_fee\";i:0;s:15:\"late_course_fee\";i:0;s:18:\"late_repeaters_fee\";i:0;s:15:\"late_senior_fee\";i:0;s:16:\"late_student_fee\";i:0;s:12:\"repeater_fee\";i:0;s:10:\"senior_fee\";i:0;s:11:\"student_fee\";i:0;s:8:\"currency\";s:3:\"IDR\";s:5:\"notes\";s:37:\"<p>Wekly : Long Kriya&nbsp;</p><br/> \";s:11:\"description\";s:0:\"\";s:9:\"image_url\";s:0:\"\";s:8:\"timezone\";s:12:\"Asia/Jakarta\";s:14:\"accommodations\";N;s:15:\"complex_timings\";s:0:\"\";s:21:\"course_complex_timing\";s:0:\"\";s:17:\"is_complex_timing\";b:0;s:21:\"registration_required\";b:1;s:18:\"residential_course\";b:0;s:11:\"max_tickets\";s:1:\"1\";s:23:\"info_for_group_required\";s:1:\"1\";s:16:\"center_image_url\";s:0:\"\";s:11:\"center_type\";s:0:\"\";s:16:\"field_is_country\";i:0;s:14:\"field_is_state\";i:0;s:11:\"center_name\";s:21:\"Art of Living Jakarta\";s:37:\"is_accommodation_fee_pay_mode_offline\";b:0;s:31:\"is_expense_fee_pay_mode_offline\";b:0;s:7:\"is_paid\";b:1;s:9:\"is_parent\";i:1;s:22:\"is_event_capacity_full\";s:1:\"0\";s:14:\"has_early_bird\";s:0:\"\";s:19:\"early_birt_end_date\";s:0:\"\";s:15:\"is_online_event\";i:0;s:15:\"course_language\";a:0:{}s:19:\"translate_languages\";a:0:{}s:14:\"event_locality\";s:0:\"\";s:10:\"is_private\";i:0;s:15:\"shankara_course\";b:0;s:11:\"week_number\";i:0;s:8:\"campaign\";s:0:\"\";s:26:\"field_eligibility_criteria\";i:0;s:11:\"event_dates\";a:4:{i:0;a:3:{s:10:\"start_date\";s:19:\"2026-11-24T12:00:00\";s:8:\"end_date\";s:19:\"2026-11-24T12:00:00\";s:6:\"r_rule\";s:68:\"RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=TU;UNTIL=20270519T235959Z;WKST=SU\";}i:1;a:3:{s:10:\"start_date\";s:19:\"2026-12-01T12:00:00\";s:8:\"end_date\";s:19:\"2026-12-01T12:00:00\";s:6:\"r_rule\";s:68:\"RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=TU;UNTIL=20270519T235959Z;WKST=SU\";}i:2;a:3:{s:10:\"start_date\";s:19:\"2026-12-08T12:00:00\";s:8:\"end_date\";s:19:\"2026-12-08T12:00:00\";s:6:\"r_rule\";s:68:\"RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=TU;UNTIL=20270519T235959Z;WKST=SU\";}i:3;a:3:{s:10:\"start_date\";s:19:\"2026-12-15T12:00:00\";s:8:\"end_date\";s:19:\"2026-12-15T12:00:00\";s:6:\"r_rule\";s:68:\"RRULE:FREQ=WEEKLY;INTERVAL=1;BYDAY=TU;UNTIL=20270519T235959Z;WKST=SU\";}}s:16:\"facilitator_name\";s:0:\"\";s:17:\"bx_course_country\";s:0:\"\";s:23:\"sponsoring_organization\";s:0:\"\";s:17:\"bx_course_timings\";N;s:15:\"course_subtitle\";N;s:22:\"field_bx_accommodation\";a:3:{s:12:\"accomadation\";a:1:{s:7:\"options\";N;}s:14:\"early_end_date\";s:0:\"\";s:8:\"is_early\";b:0;}s:28:\"field_bx_event_accommodation\";a:3:{s:12:\"accomadation\";N;s:14:\"early_end_date\";s:0:\"\";s:8:\"is_early\";i:0;}s:13:\"course_source\";s:2:\"HX\";s:15:\"field_time_zone\";s:0:\"\";s:32:\"bx_registration_closed_date_time\";s:0:\"\";s:22:\"is_registration_closed\";b:0;s:10:\"price_sets\";N;s:14:\"register_count\";i:0;s:13:\"shopamine_url\";s:38:\"https://www.artofliving.org/id-en/i_23\";s:24:\"is_registration_required\";b:0;s:26:\"course_group_shopmine_urls\";N;}}', 1795323591),
('route.permission.auth.me', 'N;', 1795316627),
('route.permission.banner.index', 'O:33:\"App\\Models\\ManagementAccess\\Route\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:43;s:5:\"route\";s:12:\"banner.index\";s:15:\"permission_name\";s:12:\"banner.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2024-09-30 19:06:55\";s:10:\"updated_at\";s:19:\"2024-10-14 05:36:39\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:43;s:5:\"route\";s:12:\"banner.index\";s:15:\"permission_name\";s:12:\"banner.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2024-09-30 19:06:55\";s:10:\"updated_at\";s:19:\"2024-10-14 05:36:39\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:6:\"status\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"route\";i:1;s:15:\"permission_name\";i:2;s:6:\"status\";i:3;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1795316676),
('route.permission.banner.store', 'O:33:\"App\\Models\\ManagementAccess\\Route\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:44;s:5:\"route\";s:12:\"banner.store\";s:15:\"permission_name\";s:12:\"banner.store\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2024-09-30 19:07:34\";s:10:\"updated_at\";s:19:\"2024-10-14 05:37:03\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:44;s:5:\"route\";s:12:\"banner.store\";s:15:\"permission_name\";s:12:\"banner.store\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2024-09-30 19:07:34\";s:10:\"updated_at\";s:19:\"2024-10-14 05:37:03\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:6:\"status\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"route\";i:1;s:15:\"permission_name\";i:2;s:6:\"status\";i:3;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1795316524),
('route.permission.dashboard.index', 'O:33:\"App\\Models\\ManagementAccess\\Route\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:1;s:5:\"route\";s:15:\"dashboard.index\";s:15:\"permission_name\";s:15:\"dashboard.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-20 20:20:44\";s:10:\"updated_at\";s:19:\"2026-11-20 20:20:44\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:1;s:5:\"route\";s:15:\"dashboard.index\";s:15:\"permission_name\";s:15:\"dashboard.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-20 20:20:44\";s:10:\"updated_at\";s:19:\"2026-11-20 20:20:44\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:6:\"status\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"route\";i:1;s:15:\"permission_name\";i:2;s:6:\"status\";i:3;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1795318528),
('route.permission.dashboard.recommendations', 'N;', 1795317263),
('route.permission.home', 'N;', 1795318171),
('route.permission.konsultasi.index', 'O:33:\"App\\Models\\ManagementAccess\\Route\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:237;s:5:\"route\";s:16:\"konsultasi.index\";s:15:\"permission_name\";s:16:\"konsultasi.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-20 20:20:44\";s:10:\"updated_at\";s:19:\"2026-11-20 20:20:44\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:237;s:5:\"route\";s:16:\"konsultasi.index\";s:15:\"permission_name\";s:16:\"konsultasi.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-20 20:20:44\";s:10:\"updated_at\";s:19:\"2026-11-20 20:20:44\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:6:\"status\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"route\";i:1;s:15:\"permission_name\";i:2;s:6:\"status\";i:3;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1795316875),
('route.permission.locale', 'N;', 1795318189),
('route.permission.login', 'N;', 1795316490),
('route.permission.memberships.show', 'O:33:\"App\\Models\\ManagementAccess\\Route\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:225;s:5:\"route\";s:16:\"memberships.show\";s:15:\"permission_name\";s:16:\"memberships.show\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-10-04 09:13:11\";s:10:\"updated_at\";s:19:\"2026-10-04 09:13:11\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:225;s:5:\"route\";s:16:\"memberships.show\";s:15:\"permission_name\";s:16:\"memberships.show\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-10-04 09:13:11\";s:10:\"updated_at\";s:19:\"2026-10-04 09:13:11\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:6:\"status\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"route\";i:1;s:15:\"permission_name\";i:2;s:6:\"status\";i:3;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1795316493),
('route.permission.orders.index', 'O:33:\"App\\Models\\ManagementAccess\\Route\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:194;s:5:\"route\";s:12:\"orders.index\";s:15:\"permission_name\";s:12:\"orders.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-09-13 20:24:29\";s:10:\"updated_at\";s:19:\"2026-09-13 20:24:29\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:194;s:5:\"route\";s:12:\"orders.index\";s:15:\"permission_name\";s:12:\"orders.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-09-13 20:24:29\";s:10:\"updated_at\";s:19:\"2026-09-13 20:24:29\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:6:\"status\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"route\";i:1;s:15:\"permission_name\";i:2;s:6:\"status\";i:3;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1795318175),
('route.permission.orders.payments.proof', 'N;', 1795316562),
('route.permission.packages.destroy', 'O:33:\"App\\Models\\ManagementAccess\\Route\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:167;s:5:\"route\";s:16:\"packages.destroy\";s:15:\"permission_name\";s:15:\"package.destroy\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-09-02 11:55:42\";s:10:\"updated_at\";s:19:\"2026-09-02 11:55:42\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:167;s:5:\"route\";s:16:\"packages.destroy\";s:15:\"permission_name\";s:15:\"package.destroy\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-09-02 11:55:42\";s:10:\"updated_at\";s:19:\"2026-09-02 11:55:42\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:6:\"status\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"route\";i:1;s:15:\"permission_name\";i:2;s:6:\"status\";i:3;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1795317453),
('route.permission.packages.index', 'O:33:\"App\\Models\\ManagementAccess\\Route\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:168;s:5:\"route\";s:14:\"packages.index\";s:15:\"permission_name\";s:13:\"package.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-09-02 11:56:09\";s:10:\"updated_at\";s:19:\"2026-09-02 11:56:09\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:168;s:5:\"route\";s:14:\"packages.index\";s:15:\"permission_name\";s:13:\"package.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-09-02 11:56:09\";s:10:\"updated_at\";s:19:\"2026-09-02 11:56:09\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:6:\"status\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"route\";i:1;s:15:\"permission_name\";i:2;s:6:\"status\";i:3;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1795317446),
('route.permission.packages.member', 'N;', 1795318172),
('route.permission.profile.edit', 'N;', 1795318189),
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:136:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"menu.main-menu\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:20:\"menu.role-permission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:22:\"menu.access-management\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:15:\"dashboard.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"user.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:10:\"user.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:11:\"user.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"user.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:16:\"menu-group.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"menu-group.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:17:\"menu-group.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:18:\"menu-group.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:15:\"menu-item.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:15:\"menu-item.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:16:\"menu-item.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:17:\"menu-item.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:11:\"route.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:11:\"route.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:12:\"route.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:13:\"route.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:10:\"role.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:10:\"role.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:11:\"role.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"role.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:16:\"permission.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:16:\"permission.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:17:\"permission.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:18:\"permission.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:11:\"faq.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:9:\"faq.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:9:\"faq.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:10:\"faq.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:20:\"menu.main-portofolio\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:33;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:13:\"company.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:13:\"company.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:14:\"company.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:18:\"categories.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:37;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:16:\"categories.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:38;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:16:\"categories.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:39;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:17:\"categories.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:40;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:14:\"banner.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:41;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:12:\"banner.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:42;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:12:\"banner.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:43;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:13:\"banner.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:44;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:16:\"articles.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:45;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:15:\"articles.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:46;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:14:\"articles.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:47;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:14:\"articles.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:48;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:15:\"articles.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:49;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:14:\"dashboard.form\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:50;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:12:\"poll.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:51;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:10:\"poll.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:52;a:4:{s:1:\"a\";i:70;s:1:\"b\";s:10:\"poll.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:53;a:4:{s:1:\"a\";i:75;s:1:\"b\";s:11:\"poll.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:54;a:4:{s:1:\"a\";i:85;s:1:\"b\";s:13:\"account.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:55;a:4:{s:1:\"a\";i:90;s:1:\"b\";s:14:\"account.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:56;a:3:{s:1:\"a\";i:125;s:1:\"b\";s:20:\"filedownload.destroy\";s:1:\"c\";s:3:\"web\";}i:57;a:4:{s:1:\"a\";i:130;s:1:\"b\";s:18:\"filedownload.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:5;}}i:58;a:3:{s:1:\"a\";i:135;s:1:\"b\";s:18:\"filedownload.store\";s:1:\"c\";s:3:\"web\";}i:59;a:3:{s:1:\"a\";i:140;s:1:\"b\";s:19:\"filedownload.update\";s:1:\"c\";s:3:\"web\";}i:60;a:4:{s:1:\"a\";i:146;s:1:\"b\";s:12:\"vendor.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:61;a:4:{s:1:\"a\";i:147;s:1:\"b\";s:12:\"pages.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:62;a:4:{s:1:\"a\";i:148;s:1:\"b\";s:13:\"pages.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:63;a:4:{s:1:\"a\";i:149;s:1:\"b\";s:11:\"pages.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:64;a:4:{s:1:\"a\";i:150;s:1:\"b\";s:11:\"pages.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:65;a:4:{s:1:\"a\";i:151;s:1:\"b\";s:12:\"pages.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:66;a:4:{s:1:\"a\";i:152;s:1:\"b\";s:14:\"vendor.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:67;a:4:{s:1:\"a\";i:153;s:1:\"b\";s:12:\"vendor.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:68;a:4:{s:1:\"a\";i:154;s:1:\"b\";s:13:\"vendor.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:69;a:4:{s:1:\"a\";i:155;s:1:\"b\";s:19:\"testimonial.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:70;a:4:{s:1:\"a\";i:156;s:1:\"b\";s:17:\"testimonial.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:71;a:4:{s:1:\"a\";i:157;s:1:\"b\";s:18:\"testimonial.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:72;a:4:{s:1:\"a\";i:158;s:1:\"b\";s:17:\"testimonial.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:73;a:4:{s:1:\"a\";i:159;s:1:\"b\";s:12:\"events.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:74;a:4:{s:1:\"a\";i:160;s:1:\"b\";s:14:\"events.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:75;a:4:{s:1:\"a\";i:161;s:1:\"b\";s:12:\"events.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:76;a:4:{s:1:\"a\";i:162;s:1:\"b\";s:13:\"events.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:77;a:4:{s:1:\"a\";i:163;s:1:\"b\";s:24:\"vendors_categories.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:78;a:4:{s:1:\"a\";i:164;s:1:\"b\";s:26:\"vendors_categories.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:79;a:4:{s:1:\"a\";i:165;s:1:\"b\";s:25:\"vendors_categories.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:80;a:4:{s:1:\"a\";i:166;s:1:\"b\";s:24:\"vendors_categories.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:81;a:4:{s:1:\"a\";i:167;s:1:\"b\";s:15:\"package.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:82;a:4:{s:1:\"a\";i:168;s:1:\"b\";s:13:\"package.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:83;a:4:{s:1:\"a\";i:169;s:1:\"b\";s:14:\"package.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:84;a:4:{s:1:\"a\";i:170;s:1:\"b\";s:13:\"package.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:85;a:4:{s:1:\"a\";i:171;s:1:\"b\";s:14:\"package.member\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:86;a:4:{s:1:\"a\";i:176;s:1:\"b\";s:22:\"dashboard.submitSumber\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:87;a:4:{s:1:\"a\";i:185;s:1:\"b\";s:14:\"pengguna.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:88;a:4:{s:1:\"a\";i:186;s:1:\"b\";s:15:\"pengguna.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:89;a:4:{s:1:\"a\";i:187;s:1:\"b\";s:12:\"venues.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:90;a:4:{s:1:\"a\";i:188;s:1:\"b\";s:14:\"venues.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:91;a:4:{s:1:\"a\";i:189;s:1:\"b\";s:13:\"venues.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:92;a:4:{s:1:\"a\";i:190;s:1:\"b\";s:12:\"venues.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:93;a:4:{s:1:\"a\";i:191;s:1:\"b\";s:13:\"venues.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:94;a:4:{s:1:\"a\";i:192;s:1:\"b\";s:13:\"vendor.mobile\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:95;a:4:{s:1:\"a\";i:193;s:1:\"b\";s:16:\"checkout.package\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:96;a:4:{s:1:\"a\";i:194;s:1:\"b\";s:12:\"orders.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:97;a:4:{s:1:\"a\";i:195;s:1:\"b\";s:11:\"orders.show\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:98;a:4:{s:1:\"a\";i:196;s:1:\"b\";s:12:\"orders.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:99;a:4:{s:1:\"a\";i:197;s:1:\"b\";s:13:\"menu.keamanan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:100;a:4:{s:1:\"a\";i:198;s:1:\"b\";s:20:\"login-activity.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:101;a:4:{s:1:\"a\";i:199;s:1:\"b\";s:22:\"login-activity.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:102;a:4:{s:1:\"a\";i:200;s:1:\"b\";s:19:\"login-lockout.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:103;a:4:{s:1:\"a\";i:201;s:1:\"b\";s:21:\"login-lockout.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:104;a:4:{s:1:\"a\";i:202;s:1:\"b\";s:15:\"audit-log.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:105;a:4:{s:1:\"a\";i:203;s:1:\"b\";s:17:\"audit-log.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:106;a:4:{s:1:\"a\";i:204;s:1:\"b\";s:18:\"failed-login.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:107;a:4:{s:1:\"a\";i:205;s:1:\"b\";s:20:\"failed-login.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:108;a:4:{s:1:\"a\";i:206;s:1:\"b\";s:22:\"website-identity.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:109;a:4:{s:1:\"a\";i:207;s:1:\"b\";s:23:\"website-identity.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:110;a:4:{s:1:\"a\";i:208;s:1:\"b\";s:17:\"memberships.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:111;a:4:{s:1:\"a\";i:209;s:1:\"b\";s:16:\"memberships.show\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:112;a:4:{s:1:\"a\";i:214;s:1:\"b\";s:14:\"orders.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:113;a:4:{s:1:\"a\";i:215;s:1:\"b\";s:13:\"orders.reject\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:114;a:4:{s:1:\"a\";i:216;s:1:\"b\";s:12:\"orders.proof\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:115;a:4:{s:1:\"a\";i:217;s:1:\"b\";s:16:\"konsultasi.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:116;a:4:{s:1:\"a\";i:218;s:1:\"b\";s:15:\"konsultasi.bulk\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:117;a:4:{s:1:\"a\";i:219;s:1:\"b\";s:17:\"konsultasi.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:118;a:4:{s:1:\"a\";i:220;s:1:\"b\";s:17:\"konsultasi.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:119;a:4:{s:1:\"a\";i:221;s:1:\"b\";s:16:\"konsultasi.merge\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:120;a:4:{s:1:\"a\";i:222;s:1:\"b\";s:17:\"konsultasi.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:121;a:4:{s:1:\"a\";i:223;s:1:\"b\";s:19:\"email-setting.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:122;a:4:{s:1:\"a\";i:224;s:1:\"b\";s:20:\"email-setting.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:123;a:4:{s:1:\"a\";i:225;s:1:\"b\";s:18:\"email-setting.test\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:124;a:4:{s:1:\"a\";i:226;s:1:\"b\";s:11:\"orders.bulk\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:125;a:4:{s:1:\"a\";i:227;s:1:\"b\";s:16:\"orders.exportCsv\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:126;a:4:{s:1:\"a\";i:228;s:1:\"b\";s:12:\"orders.audit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:127;a:4:{s:1:\"a\";i:229;s:1:\"b\";s:15:\"orders.whatsapp\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:128;a:4:{s:1:\"a\";i:230;s:1:\"b\";s:13:\"orders.assign\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:129;a:4:{s:1:\"a\";i:231;s:1:\"b\";s:19:\"orders.savedFilters\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:130;a:4:{s:1:\"a\";i:232;s:1:\"b\";s:15:\"orders.bulkUndo\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:131;a:4:{s:1:\"a\";i:233;s:1:\"b\";s:16:\"orders.quickView\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:132;a:4:{s:1:\"a\";i:234;s:1:\"b\";s:19:\"package.promo.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:133;a:4:{s:1:\"a\";i:235;s:1:\"b\";s:19:\"package.promo.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:134;a:4:{s:1:\"a\";i:236;s:1:\"b\";s:20:\"package.promo.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:135;a:4:{s:1:\"a\";i:237;s:1:\"b\";s:21:\"package.promo.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super-admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:4:\"user\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:6:\"vendor\";s:1:\"c\";s:3:\"web\";}}}', 1795356942),
('website-identity:public', 'a:19:{s:9:\"site_name\";s:14:\"Sagita Wedding\";s:10:\"site_title\";s:14:\"Sagita Wedding\";s:7:\"tagline\";s:15:\"Wedding Planner\";s:17:\"short_description\";s:84:\"Wedding planning, concept, and celebration services designed with elegance and care.\";s:5:\"email\";N;s:5:\"phone\";N;s:7:\"address\";N;s:12:\"facebook_url\";N;s:13:\"instagram_url\";N;s:11:\"youtube_url\";N;s:10:\"tiktok_url\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:13:\"meta_keywords\";N;s:19:\"google_analytics_id\";N;s:24:\"google_site_verification\";N;s:8:\"logo_url\";N;s:11:\"favicon_url\";N;s:12:\"og_image_url\";N;}', 1795317588);

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
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `uuid` char(36) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `whatsapp` varchar(20) NOT NULL,
  `whatsapp_e164` varchar(20) DEFAULT NULL,
  `whatsapp_dedupe_key` varchar(255) DEFAULT NULL,
  `email_dedupe_key` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_normalized` varchar(255) DEFAULT NULL,
  `wedding_date` date DEFAULT NULL,
  `guest_estimate` enum('Di bawah 200 tamu','200??????500 tamu','500??????1.000 tamu','Di atas 1.000 tamu') DEFAULT NULL,
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
  `reminder_sent_at` timestamp NULL DEFAULT NULL,
  `lead_status` enum('New','Contacted','Interested','Negotiation','Converted','Lost') NOT NULL DEFAULT 'New',
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `source` varchar(50) NOT NULL DEFAULT 'website',
  `merged_into_uuid` varchar(36) DEFAULT NULL,
  `merged_at` timestamp NULL DEFAULT NULL,
  `merged_by` varchar(36) DEFAULT NULL,
  `converted_to_booking_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `consultations`
--

INSERT INTO `consultations` (`uuid`, `full_name`, `whatsapp`, `whatsapp_e164`, `whatsapp_dedupe_key`, `email_dedupe_key`, `email`, `email_normalized`, `wedding_date`, `guest_estimate`, `venue`, `package_id`, `wedding_concept`, `consultation_date`, `consultation_time`, `consultation_method`, `consultation_status`, `admin_notes`, `handled_by`, `follow_up_at`, `reminder_sent_at`, `lead_status`, `sort_order`, `source`, `merged_into_uuid`, `merged_at`, `merged_by`, `converted_to_booking_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
('01b1f7d3-e121-7dba-b36f-69539d1cb839', 'Rani & Dimas', '081234567890', '6281234567890', '6281234567890', 'rani@example.test', 'rani@example.test', 'rani@example.test', '2026-12-20', 'Di bawah 200 tamu', 'Graha Krida, Jakarta', NULL, 'Tema garden minimalis warna hijau dan ivory.', '2026-11-25', '10:00:00', 'Offline', 'Confirmed', 'Calon serius, sudah memilih venue.', NULL, '2026-11-21 00:00:00', NULL, 'New', 0, 'website', NULL, NULL, NULL, NULL, '2026-11-20 09:59:17', '2026-11-20 13:54:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `email_settings`
--

CREATE TABLE `email_settings` (
  `uuid` char(36) NOT NULL,
  `mail_driver` varchar(255) NOT NULL DEFAULT 'smtp',
  `mail_host` varchar(255) DEFAULT NULL,
  `mail_port` smallint(5) UNSIGNED DEFAULT NULL,
  `mail_encryption` varchar(255) DEFAULT NULL,
  `mail_username` varchar(255) DEFAULT NULL,
  `mail_password` text DEFAULT NULL,
  `mail_timeout` smallint(5) UNSIGNED DEFAULT 60,
  `from_address` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_settings`
--

INSERT INTO `email_settings` (`uuid`, `mail_driver`, `mail_host`, `mail_port`, `mail_encryption`, `mail_username`, `mail_password`, `mail_timeout`, `from_address`, `from_name`, `is_active`, `created_at`, `updated_at`) VALUES
('206ab35b-cb99-4581-9521-e4556fb135af', 'log', '127.0.0.1', 2525, NULL, NULL, NULL, 60, 'hello@example.com', 'Sagita Wedding', 1, '2026-11-21 13:21:41', '2026-11-21 13:21:41');

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
('02471b9c-9346-409c-b9b0-5331d91456ed', 'Growing Together in Nature', 'growing-together-in-nature', '<p><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">A special morning created for mama-to-be.</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Move gently, breathe deeply, connect with your little one, and enjoy a peaceful morning in nature. ?????????</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">????????? Sunday, 13 September 2026</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">??????? 07.30??????10.00 AM</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">????????? Kebun Raya Bogor ?????? Taman Melchior</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">????????? 150K / pax</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Includes Prenatal Yoga, Pregnancy Talk, Mini MCU, Garden Picnic, Goodies &amp; Giveaways! ??????</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">????????????????? Register Now:</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">https://bit.ly/PrenatalGentleYogaatKebunRaya</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Come grow, breathe &amp; connect with us. ??????????</span></p>', 'events/NLg7WQHCoKb9lC1eYoe4r8Ca1tUW2sDfOdFrdj3l.jpg', '2026-09-13', '07:30:00', '10:00:00', 'Kebun Raya Bogor ?????? Taman Melchior', 35, 'published', '2026-08-31 18:39:08', '2026-09-01 03:02:52'),
('3191142d-9185-421c-be1d-791c843f6161', 'Yoga Night at the Museum is calling', 'yoga-night-at-the-museum-is-calling', '<p><div class=\"html-div x14z9mp x1lziwak xexx8yu xyri2b x18d9i69 x1c1uobl x9f619 xjbqb8w x78zum5 x15mokao x1ga7v0g x16uus16 xbiv7yw x1xmf6yo x12nagc x1n2onr6 x1plvlek xryxfnj x1c4vz4f x2lah0s xdt5ytf xqjyukv x1qjc9v5 x1oa3qoh x1nhvcw1\" style=\"margin-inline: 0px; border-start-start-radius: 0px; border-end-end-radius: 0px; padding-inline: 0px; flex-grow: 0; border-start-end-radius: 0px; position: relative; place-content: stretch flex-start; align-self: auto; align-items: stretch; flex-shrink: 0; display: flex; box-sizing: border-box; border-end-start-radius: 0px; flex-direction: column; background-color: rgb(255, 255, 255); margin-bottom: 4px; padding-bottom: 0px; overflow: visible; margin-top: 8px; padding-top: 0px; color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"></div></p><div class=\"xt0psk2\" style=\"display: inline; color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: 400; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; white-space: normal; background-color: rgb(255, 255, 255); text-decoration-thickness: initial; text-decoration-style: initial; text-decoration-color: initial;\"><h1 class=\"_ap3a _aaco _aacu _aacx _aad7 _aade\" dir=\"auto\" style=\"color: rgb(12, 16, 20); font-size: 14px; font-weight: 400; margin: 0px !important; padding: 0px; font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; line-height: 18px; display: inline !important;\">Rasakan sensasi ketenangan malam dan keseimbangan jiwa dalam perayaan Anniversary Yoga Roots yang ke 3 tahun??????????<br><br>????????? Jumat, 18 September 2026<br>??????? 18.30-19.30 WIB<br>????????? Taman Arca, Museum Nasional Indonesia<br>????????????????? IDR 150K ?????? sudah termasuk tiket masuk museum<br><br>Jangan lupa bawa yoga mat &amp; botol minum pribadi.<br><br>Yuk, luangkan waktu untuk move, breathe, and reconnect di malam yang spesial?????????</h1></div>', 'events/7k4NwTxmHUtn6jbXllpKeB3m6jxe35KGp4shy3Zn.jpg', '2026-09-18', '18:30:00', '19:30:00', 'Taman Arca, Museum Nasional Indonesia', 50, 'published', '2026-09-11 06:50:04', '2026-09-11 07:28:46'),
('64e369a4-ed0f-4009-996e-362f879a681e', 'Sunset Serenity', 'sunset-serenity', '<p><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Take a moment to slow down, breathe deeply, and reconnect with yourself as the sunsets.</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Join us for a relaxing Sunset Yoga Session with Coach Mega at Balcony Skechers, Senayan Park.</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">????????? 20 September 2026</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">????????? Balcony Skechers, Senayan Park</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">????????? 4:30 PM</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">????????????????????????????? Beginner Friendly</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">????????? Only IDR 65,000</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">????????? Includes Goodie Bag</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Come as you are, flow at your own pace, and end the day feeling refreshed.</span><br style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\"><span style=\"color: rgb(12, 16, 20); font-family: -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, Helvetica, Arial, sans-serif; font-size: 14px; letter-spacing: normal;\">Save your spot and let the sunset be your reset.</span></p>', 'events/duaDypZpAvPRHnf5Wdry1rNUMXK7S4vZPbxxInbq.webp', '2026-09-20', '16:30:00', '16:30:00', 'Balcony Skechers, Senayan Park', 50, 'published', '2026-09-11 07:28:06', '2026-09-11 07:28:06');

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
('4b9411a3-ccc5-44fd-bc24-e8dc7065773c', 'When should we start planning our wedding?', 'Perencanaan Pernikahan', 'Ideally, start 6??????12 months before the big day. Setting the date, venue, and budget early gives you access to a wider range of options.', 1, 'active', '2026-11-20 02:25:31', '2026-11-20 02:25:31'),
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
-- Table structure for table `konsultasi_saved_filters`
--

CREATE TABLE `konsultasi_saved_filters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `filters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`filters`)),
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
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
(507, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-21 13:14:27', '2026-11-21 13:14:27', '2026-11-21 13:14:27'),
(508, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-21 13:14:27', '2026-11-21 13:14:27', '2026-11-21 13:14:27'),
(509, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-21 13:31:57', '2026-11-21 13:31:57', '2026-11-21 13:31:57'),
(510, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-21 13:31:57', '2026-11-21 13:31:57', '2026-11-21 13:31:57'),
(511, '6704d63a-f8f0-4829-8a33-44e69e90d022', 'QA Design', 'qa.design@example.test', '127.0.0.1', 'Symfony', 'web', '2026-11-21 13:32:59', '2026-11-21 13:32:59', '2026-11-21 13:32:59'),
(512, '6704d63a-f8f0-4829-8a33-44e69e90d022', 'QA Design', 'qa.design@example.test', '127.0.0.1', 'Symfony', 'web', '2026-11-21 13:32:59', '2026-11-21 13:32:59', '2026-11-21 13:32:59'),
(513, '6704d63a-f8f0-4829-8a33-44e69e90d022', 'QA Design', 'qa.design@example.test', '127.0.0.1', 'Symfony', 'web', '2026-11-21 13:33:31', '2026-11-21 13:33:31', '2026-11-21 13:33:31'),
(514, '6704d63a-f8f0-4829-8a33-44e69e90d022', 'QA Design', 'qa.design@example.test', '127.0.0.1', 'Symfony', 'web', '2026-11-21 13:33:31', '2026-11-21 13:33:31', '2026-11-21 13:33:31'),
(515, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-21 14:18:05', '2026-11-21 14:18:05', '2026-11-21 14:18:05'),
(516, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-21 14:18:05', '2026-11-21 14:18:05', '2026-11-21 14:18:05'),
(517, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-21 22:43:55', '2026-11-21 22:43:55', '2026-11-21 22:43:55'),
(518, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-21 22:43:55', '2026-11-21 22:43:55', '2026-11-21 22:43:55'),
(519, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-21 22:59:49', '2026-11-21 22:59:49', '2026-11-21 22:59:49'),
(520, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', 'esproproperty.bekasi@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-21 22:59:49', '2026-11-21 22:59:49', '2026-11-21 22:59:49'),
(521, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.145.0 Chrome/150.0.7871.250 Electron/43.8.3 Safari/537.36', 'web', '2026-11-21 23:03:52', '2026-11-21 23:03:52', '2026-11-21 23:03:52'),
(522, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.145.0 Chrome/150.0.7871.250 Electron/43.8.3 Safari/537.36', 'web', '2026-11-21 23:03:52', '2026-11-21 23:03:52', '2026-11-21 23:03:52'),
(523, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-21 23:09:22', '2026-11-21 23:09:22', '2026-11-21 23:09:22'),
(524, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-21 23:09:22', '2026-11-21 23:09:22', '2026-11-21 23:09:22'),
(525, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Symfony', 'web', '2026-11-21 23:14:36', '2026-11-21 23:14:36', '2026-11-21 23:14:36'),
(526, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Symfony', 'web', '2026-11-21 23:14:36', '2026-11-21 23:14:36', '2026-11-21 23:14:36'),
(527, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Symfony', 'web', '2026-11-21 23:14:51', '2026-11-21 23:14:51', '2026-11-21 23:14:51'),
(528, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Symfony', 'web', '2026-11-21 23:14:51', '2026-11-21 23:14:51', '2026-11-21 23:14:51'),
(529, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'web', '2026-11-21 23:17:39', '2026-11-21 23:17:39', '2026-11-21 23:17:39'),
(530, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'web', '2026-11-21 23:17:39', '2026-11-21 23:17:39', '2026-11-21 23:17:39'),
(531, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'web', '2026-11-21 23:19:44', '2026-11-21 23:19:44', '2026-11-21 23:19:44'),
(532, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'web', '2026-11-21 23:19:44', '2026-11-21 23:19:44', '2026-11-21 23:19:44'),
(533, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'web', '2026-11-21 23:27:17', '2026-11-21 23:27:17', '2026-11-21 23:27:17'),
(534, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'web', '2026-11-21 23:27:17', '2026-11-21 23:27:17', '2026-11-21 23:27:17'),
(535, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'web', '2026-11-21 23:27:52', '2026-11-21 23:27:52', '2026-11-21 23:27:52'),
(536, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'web', '2026-11-21 23:27:52', '2026-11-21 23:27:52', '2026-11-21 23:27:52'),
(537, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'web', '2026-11-21 23:29:37', '2026-11-21 23:29:37', '2026-11-21 23:29:37'),
(538, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'web', '2026-11-21 23:29:37', '2026-11-21 23:29:37', '2026-11-21 23:29:37'),
(539, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'web', '2026-11-21 23:32:03', '2026-11-21 23:32:03', '2026-11-21 23:32:03'),
(540, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-US) WindowsPowerShell/5.1.26100.9711', 'web', '2026-11-21 23:32:03', '2026-11-21 23:32:03', '2026-11-21 23:32:03'),
(541, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.145.0 Chrome/150.0.7871.250 Electron/43.8.3 Safari/537.36', 'web', '2026-11-21 23:38:35', '2026-11-21 23:38:35', '2026-11-21 23:38:35'),
(542, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.145.0 Chrome/150.0.7871.250 Electron/43.8.3 Safari/537.36', 'web', '2026-11-21 23:38:35', '2026-11-21 23:38:35', '2026-11-21 23:38:35'),
(543, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 00:25:32', '2026-11-22 00:25:32', '2026-11-22 00:25:32'),
(544, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 00:25:32', '2026-11-22 00:25:32', '2026-11-22 00:25:32'),
(545, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', 'web', '2026-11-22 00:27:12', '2026-11-22 00:27:12', '2026-11-22 00:27:12'),
(546, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', 'web', '2026-11-22 00:27:12', '2026-11-22 00:27:12', '2026-11-22 00:27:12'),
(547, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 03:00:33', '2026-11-22 03:00:33', '2026-11-22 03:00:33'),
(548, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 03:00:33', '2026-11-22 03:00:33', '2026-11-22 03:00:33');

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
(3, 'Pengaturan Sistem', 1, 'menu-item.index', 'bx-cog', 12, '2024-09-26 23:27:05', '2026-11-21 13:21:41'),
(4, 'Riwayat Pemesanan', 1, 'orders.index', 'bx-receipt', 3, '2024-09-26 23:35:30', '2026-11-21 13:29:21'),
(7, 'Publikasi', 1, 'articles.index', 'bxs-file', 9, '2024-09-29 12:37:06', '2026-11-20 04:14:09'),
(8, 'Portfolio & Galeri', 1, 'banner.index', 'bx-camera', 5, '2024-10-13 22:33:06', '2026-11-20 04:54:02'),
(9, 'Master Data', 1, 'testimonial.index', 'bx-folder-open', 10, '2025-07-21 17:56:19', '2026-08-30 19:07:27'),
(12, 'Paket & Layanan', 1, 'package.member', 'bx-package', 1, '2025-09-14 12:05:35', '2026-11-20 08:54:25'),
(23, 'Vendor & Mitra', 1, 'venues.index', 'bx-camera-home', 6, '2026-09-08 06:44:24', '2026-11-20 03:14:58'),
(24, 'Keamanan', 1, 'menu.keamanan', 'bx-shield-alt-2', 13, '2026-10-02 00:30:47', '2026-11-20 14:24:03'),
(26, 'Pembayaran & Invoice', 1, 'memberships.index', 'bx-id-card', 4, '2026-10-04 02:13:11', '2026-11-22 00:38:46'),
(28, 'Data Prospek', 1, 'konsultasi.index', 'bx-user', 2, '2026-11-20 04:55:03', '2026-11-20 13:48:57');

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
(44, 'Data Paket', NULL, 'packages.index', 1, 'package.index', 12, 1, '2026-09-02 04:59:49', '2026-11-20 08:56:53'),
(45, 'Paket Organizer', NULL, 'packages.member', 1, 'package.member', 12, 2, '2026-09-02 05:52:23', '2026-11-20 08:56:58'),
(50, 'Testimoni', NULL, 'testimonial.index', 1, 'testimonial.index', 9, 4, '2026-09-07 23:00:45', '2026-11-20 02:34:08'),
(51, 'Faq & Answer', NULL, 'faq.index', 1, 'faq.index', 9, 5, '2026-09-07 23:04:55', '2026-09-07 23:04:55'),
(52, 'Data Venue', NULL, 'venues.index', 1, 'venues.index', 23, 1, '2026-09-08 07:14:52', '2026-11-20 03:15:33'),
(53, 'Data Vendor', NULL, 'vendor.index', 1, 'vendor.index', 23, 2, '2026-09-08 07:15:14', '2026-11-20 03:40:22'),
(55, 'Pesanan Saya', NULL, 'orders.index', 1, 'orders.index', 4, 2, '2026-09-13 14:27:33', '2026-11-21 13:30:10'),
(56, 'Aktivitas Login', NULL, 'security.login-activity.index', 1, 'login-activity.index', 24, 1, '2026-10-02 00:30:47', '2026-11-20 14:24:24'),
(57, 'Log Audit', NULL, 'security.audit-log.index', 1, 'audit-log.index', 24, 2, '2026-10-02 00:30:47', '2026-11-20 14:24:43'),
(58, 'Login Gagal', NULL, 'security.failed-login.index', 1, 'failed-login.index', 24, 3, '2026-10-02 00:30:47', '2026-11-20 14:25:03'),
(59, 'Blokir Login', NULL, 'security.login-lockout.index', 1, 'login-lockout.index', 24, 4, '2026-10-02 00:30:47', '2026-11-20 14:25:23'),
(60, 'Identitas Website', NULL, 'website-identity.index', 1, 'website-identity.index', 3, 99, '2026-10-03 12:18:10', '2026-11-20 14:26:27'),
(61, 'Riwayat Pembayaran', NULL, 'memberships.index', 1, 'memberships.index', 26, 1, '2026-10-04 02:13:11', '2026-11-22 00:39:01'),
(63, 'All Members', NULL, 'memberships.index', 1, 'memberships.index', 27, 1, '2026-10-04 02:31:55', '2026-10-04 02:31:55'),
(65, 'Kategori Vendor', NULL, 'vendors_categories.index', 1, 'vendors_categories.index', 23, 3, '2026-11-20 03:17:09', '2026-11-20 03:17:09'),
(66, 'Arsip Vendor', 'bx-archive', 'vendor.archive', 1, 'vendor.index', 10, 3, '2026-11-20 04:08:23', '2026-11-20 04:08:23'),
(67, 'Konsultasi Masuk', NULL, 'konsultasi.index', 1, 'konsultasi.index', 28, 1, '2026-11-20 05:27:33', '2026-11-20 11:50:24'),
(68, 'Calon Pelanggan', NULL, 'pengguna.index', 1, 'pengguna.index', 28, 2, '2026-11-20 05:30:17', '2026-11-20 05:30:17'),
(69, 'Pengaturan Email', NULL, 'email-setting.index', 1, 'email-setting.index', 3, 1, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(70, 'Promo', NULL, 'package-promos.index', 1, 'package.promo.index', 12, 3, '2026-11-21 14:02:21', '2026-11-21 14:02:21');

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
(38, '2025_11_11_070942_create_programs_table', 9),
(39, '2026_08_28_033008_create_specializations_table', 10),
(40, '2026_08_31_120827_create_events_table', 11),
(41, '2026_08_31_125113_create_user_specialization_table', 12),
(42, '2026_09_02_073504_create_packages_table', 13),
(43, '2026_09_02_073857_create_package_features_table', 13),
(46, '2026_09_02_074643_add_membership_fields_to_users_table', 13),
(47, '2026_09_13_074803_create_orders_table', 13),
(48, '2026_09_02_074832_create_payments_table', 13),
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
(65, '2026_10_04_120000_add_proof_to_orders_table', 29),
(69, '2026_10_07_000001_fix_testimonials_is_active', 32),
(70, '2026_10_07_000002_add_orders_fk_to_payments_and_packages', 32),
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
(88, '2026_11_20_000010_add_prospect_status_to_users_table', 47),
(89, '2026_11_20_000011_add_email_to_consultations_table', 48),
(90, '2026_11_20_000012_add_konsultasi_update_lead_route', 49),
(91, '2026_11_20_000013_add_konsultasi_pdf_routes', 50),
(92, '2026_11_20_000014_normalize_consultation_source', 50),
(94, '2026_11_20_000015_enhance_konsultasi_pipeline', 51),
(95, '2026_11_20_000016_harden_konsultasi_integrity', 52),
(96, '2026_11_20_000017_konsultasi_granular_permissions', 53),
(97, '2026_11_20_000019_fix_konsultasi_import_permission', 54),
(98, '2026_11_20_000020_register_konsultasi_feature_routes', 55),
(99, '2026_11_20_000021_fix_saved_filter_user_uuid', 56),
(100, '2026_11_20_000022_register_konsultasi_reorder_route', 57),
(101, '2026_11_20_000023_release_konsultasi_dedupe_keys_on_delete', 58),
(102, '2026_11_20_000024_register_konsultasi_index_route_updates', 59),
(103, '2026_11_20_000025_label_consultation_menu', 60),
(104, '2026_11_20_000026_register_dashboard_insights_routes', 61),
(105, '2026_11_20_000027_translate_notification_payloads', 62),
(106, '2026_11_20_000031_translate_website_identity_labels', 63),
(107, '2026_11_20_000032_translate_security_labels', 63),
(108, '2026_11_20_000033_normalize_security_menu_labels', 64),
(109, '2026_11_20_000034_create_email_settings_table', 65),
(110, '2026_11_20_000035_create_email_setting_module', 65),
(111, '2026_11_20_000036_add_order_admin_features', 65),
(112, '2026_11_21_000037_add_order_search_and_pending_indexes', 65),
(113, '2026_11_21_000038_create_order_saved_filters_table', 65),
(114, '2026_11_21_000039_add_order_ops_columns', 65),
(115, '2026_11_21_000040_add_order_granular_permissions', 65),
(116, '2026_11_21_000041_repair_order_pending_keys', 66),
(117, '2026_11_21_000042_add_order_saved_filter_pin_and_sort', 66),
(118, '2026_11_21_000043_add_wedding_package_details', 66),
(119, '2026_11_21_100000_add_package_operations', 66),
(120, '2026_11_21_182039_add_dp_payment_columns_to_orders_table', 66),
(121, '2026_11_21_182120_add_installment_columns_to_payments_table', 66),
(123, '2026_11_21_192344_add_installment_fields_to_payments_and_orders', 66),
(124, '2026_11_21_185135_remove_class_module_tables', 67),
(125, '2026_11_21_230000_add_package_promo_menu_and_permissions', 67),
(126, '2026_11_22_000001_create_wedding_plans_table', 68),
(127, '2026_11_22_000002_drop_wedding_plans_table', 69);

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
(1, 'App\\Models\\User', '000d5e68-9b01-4629-9acd-92dd74b163ba'),
(1, 'App\\Models\\User', '3099cb1c-6c37-4e5a-bbb5-d11615a1b0ff'),
(1, 'App\\Models\\User', '5e21baa9-0ca0-4c94-9939-85068e363f57'),
(1, 'App\\Models\\User', '6704d63a-f8f0-4829-8a33-44e69e90d022'),
(1, 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2'),
(1, 'App\\Models\\User', '98285022-71b5-4120-b6c0-b3cc3670279c'),
(1, 'App\\Models\\User', 'ee59b786-82c4-438f-822f-feeb118d709e'),
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
('170abde0-6d7b-48a2-b78f-8cc232e48461', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-BNEJIR8QFB ?????? Wiku Pramesthi Bagaswara memesan First Timer Package ?????? First Class Experience (Rp 210.000)\",\"path\":\"/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"icon\":\"bi-receipt\"}', '2026-10-06 14:25:26', '2026-10-06 10:12:11', '2026-10-06 14:25:26'),
('1ef127f7-4089-43aa-a1f2-c4f2688a8280', 'App\\Notifications\\ProofUploadedNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Bukti transfer diunggah\",\"message\":\"ORD-I5HKUWFTWJ ?????? ESPRO Property mengunggah bukti transfer (Rp 210.000). Silakan verifikasi.\",\"path\":\"/backend/orders/01a1049e-afda-7188-b2f9-9fa3b404f6d3\",\"icon\":\"bi-file-earmark-check\"}', '2026-10-04 02:49:21', '2026-10-04 02:48:56', '2026-10-04 02:49:21'),
('288c5468-e17d-4cc4-b7c3-5f022e4a7e96', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Login tidak biasa terdeteksi\",\"message\":\"super-admin (super@admin.com) masuk dari 127.0.0.1 ?????? jam tidak biasa\",\"path\":\"/backend/security/login-activity\",\"icon\":\"bi-shield-exclamation\"}', NULL, '2026-11-18 20:49:48', '2026-11-18 20:49:48'),
('30266322-5b40-4c84-8ee6-4b689ed46102', 'App\\Notifications\\MemberCheckedInNotification', 'App\\Models\\User', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', '{\"judul_kegiatan\":\"Check-in berhasil\",\"message\":\"Admin telah melakukan check-in untukmu di Yin Yoga (Tuesday 19:00). Sisa kuota: 48.\",\"path\":\"/backend/class-bookings\",\"icon\":\"bi-check2-circle\"}', NULL, '2026-10-06 11:44:43', '2026-10-06 11:44:43'),
('3042b54f-0eed-4e61-ad42-2def83dd9b3c', 'App\\Notifications\\OrderExpiredNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Pesanan kedaluwarsa\",\"message\":\"ORD-FG2TCGJ55W otomatis dibatalkan setelah 24 jam tanpa pembayaran. Silakan buat pesanan baru jika tetap ingin membership.\",\"path\":\"/backend/packages/members\",\"icon\":\"bi-hourglass-bottom\"}', '2026-10-06 14:07:14', '2026-10-04 02:48:58', '2026-10-06 14:07:14'),
('32e1f1c5-9072-4825-83cd-1018cb73d62f', 'App\\Notifications\\ProgramStatusChanged', 'App\\Models\\User', 'b5a9eb64-a547-4464-a576-e261ede0d4db', '{\"program_id\":null,\"judul_kegiatan\":null,\"old_status\":\"pending\",\"new_status\":\"hadir\",\"message\":\"Status program \'\' berubah dari \'pending\' menjadi \'hadir\'.\"}', '2026-06-17 19:03:03', '2026-06-17 19:02:26', '2026-06-17 19:03:03'),
('3a0644a0-403a-407c-ba4c-74b6c363f693', 'App\\Notifications\\MemberActiveNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Membership diaktifkan\",\"message\":\"Pembayaran untuk ORD-BNEJIR8QFB telah diverifikasi. Membership kamu sekarang aktif. Aktif hingga 13 Oct 2026.\",\"path\":\"/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"icon\":\"bi-check-circle\"}', '2026-10-06 14:07:14', '2026-10-06 10:13:14', '2026-10-06 14:07:14'),
('4ee83ebe-638e-45cb-aa71-02cf87721984', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-DHVCIG8ZQL ?????? Wiku Pramesthi Bagaswara memesan First Timer Package ?????? First Class Experience (Rp 210.000)\",\"path\":\"/backend/orders/01a11182-8463-7057-a0f4-8cf88279c6c2\",\"icon\":\"bi-receipt\"}', NULL, '2026-10-06 13:58:48', '2026-10-06 13:58:48'),
('54fbcbf6-8ebc-4f5e-a62f-7b184c3a8c55', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-BNEJIR8QFB ?????? Wiku Pramesthi Bagaswara memesan First Timer Package ?????? First Class Experience (Rp 210.000)\",\"path\":\"/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"icon\":\"bi-receipt\"}', NULL, '2026-10-06 10:12:11', '2026-10-06 10:12:11'),
('5e0c56a7-c2b0-4fe2-9a1b-dbe081f039d2', 'App\\Notifications\\OrderExpiredNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Pesanan kedaluwarsa\",\"message\":\"ORD-QCQL0MKBRP otomatis dibatalkan setelah 24 jam tanpa pembayaran. Silakan buat pesanan baru jika tetap ingin membership.\",\"path\":\"/backend/packages/members\",\"icon\":\"bi-hourglass-bottom\"}', '2026-10-06 14:07:14', '2026-10-04 02:48:58', '2026-10-06 14:07:14'),
('62c848cd-a1d4-46ff-b0fe-d6593f138caa', 'App\\Notifications\\ProofUploadedNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Bukti transfer diunggah\",\"message\":\"ORD-BNEJIR8QFB ?????? Wiku Pramesthi Bagaswara mengunggah bukti transfer (Rp 210.000). Silakan verifikasi.\",\"path\":\"/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"icon\":\"bi-file-earmark-check\"}', '2026-10-06 14:25:26', '2026-10-06 10:12:24', '2026-10-06 14:25:26'),
('6f95fb80-2e8d-441d-850a-741dcdc7d1bc', 'App\\Notifications\\ProgramStatusChanged', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"program_id\":null,\"judul_kegiatan\":null,\"old_status\":\"pending\",\"new_status\":\"hadir\",\"message\":\"Status program \'\' berubah dari \'pending\' menjadi \'hadir\'.\"}', '2026-09-02 06:50:51', '2026-05-24 21:21:26', '2026-09-02 06:50:51'),
('7040f077-8fe0-4701-b733-3e66a7bca72c', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Login tidak biasa terdeteksi\",\"message\":\"Wiku Pramesthi Bagaswara (wikupb@gmail.com) masuk dari 127.0.0.1 ?????? jam tidak biasa\",\"path\":\"/backend/security/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-04 21:51:30', '2026-10-04 21:49:14', '2026-10-04 21:51:30'),
('7630f926-083f-4c4a-a189-ff681e4ce6b9', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Login tidak biasa terdeteksi\",\"message\":\"ESPRO Property (esproproperty.bekasi@gmail.com) masuk dari 127.0.0.1 ?????? jam tidak biasa\",\"path\":\"/backend/security/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-04 21:51:30', '2026-10-04 21:42:47', '2026-10-04 21:51:30'),
('89cd6964-dea1-44b8-9c8c-ff7886de4786', 'App\\Notifications\\OrderExpiredNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Pesanan kedaluwarsa\",\"message\":\"ORD-9UJ1QLPEAW otomatis dibatalkan setelah 24 jam tanpa pembayaran. Silakan buat pesanan baru jika tetap ingin membership.\",\"path\":\"/backend/packages/members\",\"icon\":\"bi-hourglass-bottom\"}', '2026-10-06 14:07:14', '2026-10-04 02:48:58', '2026-10-06 14:07:14'),
('a2e5d335-3192-4c81-851a-c8b6ea33dabf', 'App\\Notifications\\MemberActiveNotification', 'App\\Models\\User', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', '{\"judul_kegiatan\":\"Membership diaktifkan\",\"message\":\"Pembayaran untuk ORD-I5HKUWFTWJ telah diverifikasi. Membership kamu sekarang aktif. Aktif hingga 11 Oct 2026.\",\"path\":\"/backend/orders/01a1049e-afda-7188-b2f9-9fa3b404f6d3\",\"icon\":\"bi-check-circle\"}', '2026-10-04 10:01:41', '2026-10-04 02:55:55', '2026-10-04 10:01:41'),
('a480fee2-6ed8-4579-88f0-fc1f2968b895', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-DHVCIG8ZQL ?????? Wiku Pramesthi Bagaswara memesan First Timer Package ?????? First Class Experience (Rp 210.000)\",\"path\":\"/backend/orders/01a11182-8463-7057-a0f4-8cf88279c6c2\",\"icon\":\"bi-receipt\"}', '2026-10-06 14:25:26', '2026-10-06 13:58:48', '2026-10-06 14:25:26'),
('a4cbf43e-868b-4970-9d5d-55f2f5e8df60', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-E5WX8OSWPO ?????? Wiku Pramesthi Bagaswara memesan First Timer Package ?????? First Class Experience (Rp 210.000)\",\"path\":\"/backend/orders/01a110cc-d5ec-7387-a3c5-87cde025d404\",\"icon\":\"bi-receipt\"}', NULL, '2026-10-06 10:40:22', '2026-10-06 10:40:22'),
('ac5b2d37-85ab-4267-b572-70b998039556', 'App\\Notifications\\ProgramStatusChanged', 'App\\Models\\User', 'dfd391fa-c167-4547-ad17-27d52d9ffc28', '{\"program_id\":null,\"judul_kegiatan\":null,\"old_status\":\"pending\",\"new_status\":\"hadir\",\"message\":\"Status program \'\' berubah dari \'pending\' menjadi \'hadir\'.\"}', '2026-05-24 21:51:12', '2026-05-24 21:48:19', '2026-05-24 21:51:12'),
('b0274b03-ffaa-462e-9098-423fe6c82b13', 'App\\Notifications\\OrderExpiredNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Pesanan kedaluwarsa\",\"message\":\"ORD-G07KVIJM1Y otomatis dibatalkan setelah 24 jam tanpa pembayaran. Silakan buat pesanan baru jika tetap ingin membership.\",\"path\":\"/backend/packages/members\",\"icon\":\"bi-hourglass-bottom\"}', '2026-10-06 14:07:14', '2026-10-04 02:48:58', '2026-10-06 14:07:14'),
('b041d44e-604a-472c-9899-f54f15ee3eff', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Login tidak biasa terdeteksi\",\"message\":\"Wiku Pramesthi Bagaswara (wikupb@gmail.com) masuk dari 127.0.0.1 ?????? jam tidak biasa\",\"path\":\"/backend/security/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-06 14:25:26', '2026-10-04 21:49:14', '2026-10-06 14:25:26'),
('b4638c57-0813-4336-9c41-433770e4a340', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-E5WX8OSWPO ?????? Wiku Pramesthi Bagaswara memesan First Timer Package ?????? First Class Experience (Rp 210.000)\",\"path\":\"/backend/orders/01a110cc-d5ec-7387-a3c5-87cde025d404\",\"icon\":\"bi-receipt\"}', '2026-10-06 14:25:26', '2026-10-06 10:40:22', '2026-10-06 14:25:26'),
('c0706708-ee37-4619-85c7-e8e3d71918e0', 'App\\Notifications\\OrderExpiredNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Pesanan kedaluwarsa\",\"message\":\"ORD-C4LCIYGBRX otomatis dibatalkan setelah 24 jam tanpa pembayaran. Silakan buat pesanan baru jika tetap ingin membership.\",\"path\":\"/backend/packages/members\",\"icon\":\"bi-hourglass-bottom\"}', '2026-10-06 14:07:14', '2026-10-04 02:48:58', '2026-10-06 14:07:14'),
('c10a4720-561e-421e-acec-ff4bc5e00b21', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Login tidak biasa terdeteksi\",\"message\":\"Wiku Pramesthi Bagaswara (wikupb@gmail.com) masuk dari 127.0.0.1 ?????? jam tidak biasa\",\"path\":\"/backend/security/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-04 21:51:30', '2026-10-04 21:49:14', '2026-10-04 21:51:30'),
('c964ebfc-7e12-407e-8e10-52ad87801ba1', 'App\\Notifications\\ProofUploadedNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Bukti transfer diunggah\",\"message\":\"ORD-BNEJIR8QFB ?????? Wiku Pramesthi Bagaswara mengunggah bukti transfer (Rp 210.000). Silakan verifikasi.\",\"path\":\"/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"icon\":\"bi-file-earmark-check\"}', NULL, '2026-10-06 10:12:24', '2026-10-06 10:12:24'),
('d93aa418-cae7-47c0-8af5-479119c52593', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Login tidak biasa terdeteksi\",\"message\":\"ESPRO Property (esproproperty.bekasi@gmail.com) masuk dari 127.0.0.1 ?????? jam tidak biasa\",\"path\":\"/backend/security/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-04 21:51:30', '2026-10-04 21:42:47', '2026-10-04 21:51:30'),
('e53d8181-76ed-4616-9627-e150cee6c956', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Login tidak biasa terdeteksi\",\"message\":\"ESPRO Property (esproproperty.bekasi@gmail.com) masuk dari 127.0.0.1 ?????? jam tidak biasa\",\"path\":\"/backend/security/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-06 14:25:26', '2026-10-04 21:42:47', '2026-10-06 14:25:26'),
('e81dc652-ce3a-4b7e-a695-27b508d02e19', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Login tidak biasa terdeteksi\",\"message\":\"ESPRO Property (esproproperty.bekasi@gmail.com) masuk dari 127.0.0.1 ?????? jam tidak biasa\",\"path\":\"/backend/security/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-06 14:25:26', '2026-10-04 21:42:47', '2026-10-06 14:25:26'),
('e86f6aba-11c6-44bc-ab7c-a47af541ccdb', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Login tidak biasa terdeteksi\",\"message\":\"Wiku Pramesthi Bagaswara (wikupb@gmail.com) masuk dari 127.0.0.1 ?????? jam tidak biasa\",\"path\":\"/backend/security/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-06 14:25:26', '2026-10-04 21:49:14', '2026-10-06 14:25:26'),
('ea6cf512-c215-4936-93bf-1e7f744796fc', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Login tidak biasa terdeteksi\",\"message\":\"super-admin (super@admin.com) masuk dari 127.0.0.1 ?????? jam tidak biasa\",\"path\":\"/backend/security/login-activity\",\"icon\":\"bi-shield-exclamation\"}', NULL, '2026-11-18 20:49:48', '2026-11-18 20:49:48'),
('f071f5b7-d4aa-44ba-9449-cbd36724f7c8', 'App\\Notifications\\ProgramStatusChanged', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"program_id\":null,\"judul_kegiatan\":null,\"old_status\":\"pending\",\"new_status\":\"hadir\",\"message\":\"Status program \'\' berubah dari \'pending\' menjadi \'hadir\'.\"}', '2026-05-25 20:36:48', '2026-05-24 21:41:40', '2026-05-25 20:36:48'),
('f0a45da5-5b08-453c-bbda-f036f84dfe44', 'App\\Notifications\\MemberCheckedInNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Check-in berhasil\",\"message\":\"Kamu telah check-in di Yin Yoga (Tuesday 19:00). Sisa kuota: 49.\",\"path\":\"/backend/class-bookings\",\"icon\":\"bi-check2-circle\"}', '2026-10-06 10:29:05', '2026-10-06 10:13:48', '2026-10-06 10:29:05'),
('fbcf4c2e-ea84-4629-be0d-47925e4fc0ca', 'App\\Notifications\\MemberCheckedInNotification', 'App\\Models\\User', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', '{\"judul_kegiatan\":\"Check-in berhasil\",\"message\":\"Admin telah melakukan check-in untukmu di Yin Yoga (Monday 14:00). Sisa kuota: 49.\",\"path\":\"/backend/class-bookings\",\"icon\":\"bi-check2-circle\"}', NULL, '2026-10-04 21:59:26', '2026-10-04 21:59:26');

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
  `event_date` date DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_scheme` varchar(20) NOT NULL DEFAULT 'full',
  `total_amount` decimal(15,2) DEFAULT NULL,
  `amount_paid` decimal(15,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance_due_at` timestamp NULL DEFAULT NULL,
  `balance_reminder_sent_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','paid','partially_paid','failed','expired','cancelled') NOT NULL DEFAULT 'pending',
  `pending_key` varchar(255) DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `assigned_admin_uuid` char(36) DEFAULT NULL,
  `proof_image_path` varchar(255) DEFAULT NULL,
  `proof_uploaded_at` timestamp NULL DEFAULT NULL,
  `sla_reminded_at` timestamp NULL DEFAULT NULL,
  `approval_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`approval_snapshot`)),
  `addons` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`addons`)),
  `promo_code` varchar(255) DEFAULT NULL,
  `promo_discount` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `promo_snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`promo_snapshot`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`uuid`, `user_uuid`, `order_number`, `type`, `package_uuid`, `package_option_uuid`, `event_date`, `amount`, `payment_scheme`, `total_amount`, `amount_paid`, `remaining_amount`, `balance_due_at`, `balance_reminder_sent_at`, `status`, `pending_key`, `expired_at`, `paid_at`, `admin_note`, `assigned_admin_uuid`, `proof_image_path`, `proof_uploaded_at`, `sla_reminded_at`, `approval_snapshot`, `addons`, `promo_code`, `promo_discount`, `promo_snapshot`, `created_at`, `updated_at`) VALUES
('01a09b0a-4dd3-7318-9203-f08140b94b97', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-G07KVIJM1Y', 'package', NULL, NULL, NULL, 878000.00, 'full', 878000.00, 0.00, 878000.00, NULL, NULL, 'expired', NULL, '2026-09-14 13:52:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-09-13 13:52:18', '2026-10-04 02:48:58'),
('01a09b11-520c-7314-bc73-86d595a54f57', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-FG2TCGJ55W', 'package', NULL, NULL, NULL, 230000.00, 'full', 230000.00, 0.00, 230000.00, NULL, NULL, 'expired', NULL, '2026-09-14 13:59:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-09-13 13:59:58', '2026-10-04 02:48:58'),
('01a09b16-5931-73fb-866d-5e723294a477', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-C4LCIYGBRX', 'package', NULL, NULL, NULL, 230000.00, 'full', 230000.00, 0.00, 230000.00, NULL, NULL, 'expired', NULL, '2026-09-14 14:05:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-09-13 14:05:28', '2026-10-04 02:48:58'),
('01a09b1e-7b00-7391-bb4a-a50a77fde0e4', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-9UJ1QLPEAW', 'package', NULL, NULL, NULL, 230000.00, 'full', 230000.00, 0.00, 230000.00, NULL, NULL, 'expired', NULL, '2026-09-14 14:14:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-09-13 14:14:21', '2026-10-04 02:48:58'),
('01a09b1e-c44e-70ae-98b1-d19e19c7b4fe', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-QCQL0MKBRP', 'package', NULL, NULL, NULL, 230000.00, 'full', 230000.00, 0.00, 230000.00, NULL, NULL, 'expired', NULL, '2026-09-14 14:14:39', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-09-13 14:14:39', '2026-10-04 02:48:58'),
('01a1049e-afda-7188-b2f9-9fa3b404f6d3', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ORD-I5HKUWFTWJ', 'package', '01a095f2-d286-7241-b566-8027529b8196', '01a09966-2965-7201-ab57-9f085e23bbc7', NULL, 210000.00, 'full', 210000.00, 210000.00, 0.00, NULL, NULL, 'paid', NULL, '2026-10-05 01:54:31', '2026-10-04 02:55:55', NULL, NULL, 'proofs/lTa9j1FbqtOaGW4LDEw95vVjQ9JDz24MwX2UnFMi.png', '2026-10-04 02:48:54', NULL, NULL, NULL, NULL, 0, NULL, '2026-10-04 01:54:31', '2026-10-04 02:55:55'),
('01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-BNEJIR8QFB', 'package', '01a095f2-d286-7241-b566-8027529b8196', '01a09966-2965-7201-ab57-9f085e23bbc7', NULL, 210000.00, 'full', 210000.00, 210000.00, 0.00, NULL, NULL, 'paid', NULL, '2026-10-07 10:12:11', '2026-10-06 10:13:14', NULL, NULL, 'proofs/WrmyZ098hpkUmpUTw9LmcOqDkUqtGOeLdNeyBjqM.png', '2026-10-06 10:12:24', NULL, NULL, NULL, NULL, 0, NULL, '2026-10-06 10:12:11', '2026-10-06 10:13:14'),
('01a110cc-d5ec-7387-a3c5-87cde025d404', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-E5WX8OSWPO', 'package', '01a095f2-d286-7241-b566-8027529b8196', '01a09966-2965-7201-ab57-9f085e23bbc7', NULL, 210000.00, 'full', 210000.00, 0.00, 210000.00, NULL, NULL, 'pending', NULL, '2026-10-07 10:40:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-10-06 10:40:21', '2026-10-06 10:40:21'),
('01a11182-8463-7057-a0f4-8cf88279c6c2', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-DHVCIG8ZQL', 'package', '01a095f2-d286-7241-b566-8027529b8196', '01a09966-2965-7201-ab57-9f085e23bbc7', NULL, 210000.00, 'full', 210000.00, 0.00, 210000.00, NULL, NULL, 'pending', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5|01a09966-2965-7201-ab57-9f085e23bbc7', '2026-10-07 13:58:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-10-06 13:58:48', '2026-10-06 13:58:48');

-- --------------------------------------------------------

--
-- Table structure for table `order_saved_filters`
--

CREATE TABLE `order_saved_filters` (
  `id` char(36) NOT NULL,
  `user_uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `filters` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`filters`)),
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `pinned_at` timestamp NULL DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `guest_capacity` int(10) UNSIGNED DEFAULT NULL,
  `event_duration_hours` int(10) UNSIGNED DEFAULT NULL,
  `venue_note` text DEFAULT NULL,
  `cover_url` varchar(255) DEFAULT NULL,
  `is_popular` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `lead_time_days` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `price` decimal(15,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `uuid`, `name`, `slug`, `description`, `guest_capacity`, `event_duration_hours`, `venue_note`, `cover_url`, `is_popular`, `is_active`, `is_published`, `published_at`, `lead_time_days`, `created_at`, `updated_at`, `price`) VALUES
(2, '01a095f2-d286-7241-b566-8027529b8196', 'First Timer Package', 'first-timer-package', 'A welcoming first step into your yoga journey at Yogaroots. Enjoy a guided studio session designed to help you feel comfortable, refreshed, and ready to move.', NULL, NULL, NULL, NULL, 1, 'active', 1, NULL, 0, '2026-09-12 14:08:33', '2026-09-13 06:13:24', 0.00),
(3, '01a09615-73d8-7345-a20f-2d5eeb6b1d1a', 'Unlimited Package', 'unlimited-package', 'Practice freely with unlimited access to Yogaroots classes. Move at your own pace, explore different styles, and make yoga part of your everyday routine.', NULL, NULL, NULL, NULL, 0, 'active', 1, NULL, 0, '2026-09-12 14:46:23', '2026-09-12 14:46:23', 0.00),
(4, '01a098c6-e509-7154-869f-8d336d44ebc4', 'Sharing Package', 'sharing-package', 'A flexible membership designed to be shared with someone special. Enjoy Yogaroots classes together and make your yoga journey a shared experience.', NULL, NULL, NULL, NULL, 0, 'active', 1, NULL, 0, '2026-09-13 03:19:26', '2026-09-13 06:13:12', 0.00),
(6, '01a098d2-a02c-72ee-a8eb-b9dc6786761d', '1 On 1 Private Class', '1-on-1-private-class', 'A personalized yoga experience designed around you. Practice one-on-one with our instructor, receive focused guidance, and build a practice that supports your individual goals.', NULL, NULL, NULL, NULL, 0, 'active', 1, NULL, 0, '2026-09-13 03:32:15', '2026-09-13 03:32:15', 0.00),
(7, '01a098e0-878e-7296-b1c0-a550bf0327b0', 'Couple\'s Private Class', 'couples-private-class', 'A private yoga experience to enjoy together. Practice side by side with personalized guidance from our instructor, deepen your connection, and make every session a meaningful shared experience.', NULL, NULL, NULL, NULL, 0, 'active', 1, NULL, 0, '2026-09-13 03:47:26', '2026-09-13 03:47:26', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `package_addons`
--

CREATE TABLE `package_addons` (
  `uuid` char(36) NOT NULL,
  `package_uuid` char(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` bigint(20) UNSIGNED NOT NULL,
  `discount_price` bigint(20) UNSIGNED DEFAULT NULL,
  `min_quantity` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `max_quantity` smallint(5) UNSIGNED DEFAULT NULL,
  `requires_addon_uuid` char(36) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_blackout_dates`
--

CREATE TABLE `package_blackout_dates` (
  `uuid` char(36) NOT NULL,
  `package_uuid` char(36) NOT NULL,
  `date` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('01a09966-296e-71e6-a0f5-c78ca89e5199', '01a095f2-d286-7241-b566-8027529b8196', 'Guidance from Our Instructor', 4, '2026-09-13 06:13:24', '2026-09-13 06:13:24');

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
('01a098de-ec50-73bf-b928-c37f31d37568', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', 'Single drop in', 1, 1000000, NULL, 1, 'week', 0, 1, '2026-09-13 03:45:41', '2026-09-13 03:45:41'),
('01a098de-ec7d-7352-9076-67a2defe3fde', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', '5 Private Classes', 1, 4500000, NULL, 5, 'week', 1, 1, '2026-09-13 03:45:41', '2026-09-13 03:45:41'),
('01a098de-ec7f-7275-a7f8-be27d7caa4ff', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', '10 Private Classes', 1, 8100000, NULL, 10, 'week', 2, 1, '2026-09-13 03:45:41', '2026-09-13 03:45:41'),
('01a098e0-8795-71b4-84a4-de9c8a0b517e', '01a098e0-878e-7296-b1c0-a550bf0327b0', 'Single drop in', 2, 1200000, NULL, 1, 'week', 0, 1, '2026-09-13 03:47:26', '2026-09-13 03:47:26'),
('01a098e0-8797-7309-954d-6caac6a8f4ee', '01a098e0-878e-7296-b1c0-a550bf0327b0', '5 Private Classes', 2, 5400000, NULL, 5, 'week', 1, 1, '2026-09-13 03:47:26', '2026-09-13 03:47:26'),
('01a098e0-8798-707b-b260-c1379231361e', '01a098e0-878e-7296-b1c0-a550bf0327b0', '10 Private Classes', 2, 9720000, NULL, 10, 'week', 2, 1, '2026-09-13 03:47:26', '2026-09-13 03:47:26'),
('01a09965-fbf0-7307-ba83-dfa1382cf270', '01a098c6-e509-7154-869f-8d336d44ebc4', '25 Classes', 50, 6999000, 5599000, 1, 'month', 0, 1, '2026-09-13 06:13:12', '2026-09-13 06:13:12'),
('01a09966-2965-7201-ab57-9f085e23bbc7', '01a095f2-d286-7241-b566-8027529b8196', 'First Class Experience', 50, 210000, NULL, 1, 'week', 0, 1, '2026-09-13 06:13:24', '2026-09-13 06:13:24');

-- --------------------------------------------------------

--
-- Table structure for table `package_promos`
--

CREATE TABLE `package_promos` (
  `uuid` char(36) NOT NULL,
  `package_uuid` char(36) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'percent',
  `value` bigint(20) UNSIGNED NOT NULL,
  `min_purchase` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `max_discount` bigint(20) UNSIGNED DEFAULT NULL,
  `max_uses` int(10) UNSIGNED DEFAULT NULL,
  `used_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_wishlists`
--

CREATE TABLE `package_wishlists` (
  `uuid` char(36) NOT NULL,
  `user_uuid` char(36) NOT NULL,
  `package_uuid` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `kind` varchar(30) NOT NULL DEFAULT 'full',
  `payment_gateway` varchar(255) NOT NULL DEFAULT 'midtrans',
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `gross_amount` decimal(15,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `verification_status` varchar(30) NOT NULL DEFAULT 'pending',
  `verified_by` char(36) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `raw_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_response`)),
  `proof_image_path` varchar(255) DEFAULT NULL,
  `proof_uploaded_at` timestamp NULL DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`uuid`, `order_uuid`, `kind`, `payment_gateway`, `transaction_id`, `payment_type`, `amount`, `gross_amount`, `status`, `verification_status`, `verified_by`, `verified_at`, `paid_at`, `raw_response`, `proof_image_path`, `proof_uploaded_at`, `note`, `created_at`, `updated_at`) VALUES
('01a104d6-e859-7018-b76c-578ce8b292b2', '01a1049e-afda-7188-b2f9-9fa3b404f6d3', 'full', 'manual_transfer', NULL, 'Bank Transfer', 210000.00, 210000.00, 'paid', 'verified', NULL, '2026-10-04 02:55:55', '2026-10-04 02:55:55', NULL, 'proofs/lTa9j1FbqtOaGW4LDEw95vVjQ9JDz24MwX2UnFMi.png', '2026-10-04 02:48:54', NULL, '2026-10-04 02:55:55', '2026-10-04 02:55:55'),
('01a110b4-000d-7377-9dab-512dd7adc04c', '01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', 'full', 'manual_transfer', NULL, 'Bank Transfer', 210000.00, 210000.00, 'paid', 'verified', NULL, '2026-10-06 10:13:14', '2026-10-06 10:13:14', NULL, 'proofs/WrmyZ098hpkUmpUTw9LmcOqDkUqtGOeLdNeyBjqM.png', '2026-10-06 10:12:24', NULL, '2026-10-06 10:13:14', '2026-10-06 10:13:14');

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
(176, 'dashboard.submitSumber', 'web', '2026-09-04 07:15:06', '2026-09-04 07:15:06'),
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
(214, 'orders.approve', 'web', '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(215, 'orders.reject', 'web', '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(216, 'orders.proof', 'web', '2026-10-04 02:31:55', '2026-10-04 02:31:55'),
(217, 'konsultasi.index', 'web', '2026-11-20 05:26:55', '2026-11-20 05:26:55'),
(218, 'konsultasi.bulk', 'web', '2026-11-20 09:14:59', '2026-11-20 09:14:59'),
(219, 'konsultasi.delete', 'web', '2026-11-20 09:14:59', '2026-11-20 09:14:59'),
(220, 'konsultasi.export', 'web', '2026-11-20 09:14:59', '2026-11-20 09:14:59'),
(221, 'konsultasi.merge', 'web', '2026-11-20 09:14:59', '2026-11-20 09:14:59'),
(222, 'konsultasi.import', 'web', '2026-11-20 09:38:08', '2026-11-20 09:38:08'),
(223, 'email-setting.index', 'web', '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(224, 'email-setting.update', 'web', '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(225, 'email-setting.test', 'web', '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(226, 'orders.bulk', 'web', '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(227, 'orders.exportCsv', 'web', '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(228, 'orders.audit', 'web', '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(229, 'orders.whatsapp', 'web', '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(230, 'orders.assign', 'web', '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(231, 'orders.savedFilters', 'web', '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(232, 'orders.bulkUndo', 'web', '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(233, 'orders.quickView', 'web', '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(234, 'package.promo.index', 'web', '2026-11-21 14:02:21', '2026-11-21 14:02:21'),
(235, 'package.promo.store', 'web', '2026-11-21 14:02:21', '2026-11-21 14:02:21'),
(236, 'package.promo.update', 'web', '2026-11-21 14:02:21', '2026-11-21 14:02:21'),
(237, 'package.promo.destroy', 'web', '2026-11-21 14:02:21', '2026-11-21 14:02:21');

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
(176, 1),
(176, 2),
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
(214, 1),
(214, 3),
(215, 1),
(215, 3),
(216, 1),
(216, 2),
(216, 3),
(217, 1),
(217, 3),
(218, 1),
(218, 3),
(219, 1),
(219, 3),
(220, 1),
(220, 3),
(221, 1),
(221, 3),
(222, 1),
(222, 3),
(223, 1),
(223, 3),
(224, 1),
(224, 3),
(225, 1),
(225, 3),
(226, 1),
(226, 3),
(227, 1),
(227, 3),
(228, 1),
(228, 3),
(229, 1),
(229, 3),
(230, 1),
(230, 3),
(231, 1),
(231, 3),
(232, 1),
(232, 3),
(233, 1),
(233, 3),
(234, 1),
(234, 3),
(235, 1),
(235, 3),
(236, 1),
(236, 3),
(237, 1),
(237, 3);

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
(1, 'dashboard.index', 'dashboard.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(2, 'user.index', 'user.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(3, 'user.store', 'user.store', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(4, 'user.update', 'user.update', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(5, 'user.destroy', 'user.destroy', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(6, 'menu.index', 'menu-group.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(7, 'menu.store', 'menu-group.store', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(8, 'menu.update', 'menu-group.update', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(9, 'menu.destroy', 'menu-group.destroy', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(10, 'menu.item.index', 'menu-item.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(11, 'menu.item.store', 'menu-item.store', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(12, 'menu.item.update', 'menu-item.update', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(13, 'menu.item.destroy', 'menu-item.destroy', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(14, 'route.index', 'route.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(15, 'route.store', 'route.store', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(16, 'route.update', 'route.update', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(17, 'route.destroy', 'route.destroy', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(18, 'role.index', 'role.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(19, 'role.store', 'role.store', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(20, 'role.update', 'role.update', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(21, 'role.destroy', 'role.destroy', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(22, 'permission.index', 'permission.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(23, 'permission.store', 'permission.store', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(24, 'permission.update', 'permission.update', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(25, 'permission.destroy', 'permission.destroy', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
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
(176, 'dashboard.submitSumber', 'dashboard.submitSumber', 1, NULL, '2026-09-04 07:15:41', '2026-09-04 07:15:41'),
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
(231, 'orders.approve', 'orders.approve', 1, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(232, 'orders.reject', 'orders.reject', 1, NULL, '2026-10-04 02:13:11', '2026-10-04 02:13:11'),
(233, 'orders.proof', 'orders.proof', 1, NULL, '2026-10-04 02:31:55', '2026-10-04 02:31:55'),
(234, 'orders.proof.show', 'orders.proof', 1, NULL, '2026-10-04 02:55:13', '2026-10-04 02:55:13'),
(235, 'vendor.archive', 'vendor.index', 1, NULL, '2026-11-20 04:08:23', '2026-11-20 04:08:23'),
(236, 'vendor.restore', 'vendor.update', 1, NULL, '2026-11-20 04:08:23', '2026-11-20 04:08:23'),
(237, 'konsultasi.index', 'konsultasi.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(238, 'konsultasi.create', 'konsultasi.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(239, 'konsultasi.store', 'konsultasi.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(240, 'konsultasi.show', 'konsultasi.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(241, 'konsultasi.edit', 'konsultasi.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(242, 'konsultasi.update', 'konsultasi.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(243, 'konsultasi.destroy', 'konsultasi.delete', 1, 'Akses konsultasi.destroy', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(244, 'pengguna.update-status', 'pengguna.index', 1, NULL, '2026-11-20 05:38:38', '2026-11-20 05:38:38'),
(245, 'konsultasi.update-lead', 'konsultasi.index', 1, NULL, '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(246, 'konsultasi.pdf-preview', 'konsultasi.export', 1, 'Akses konsultasi.pdf-preview', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(247, 'konsultasi.pdf-stream', 'konsultasi.export', 1, 'Akses konsultasi.pdf-stream', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(248, 'konsultasi.pdf-download', 'konsultasi.export', 1, 'Akses konsultasi.pdf-download', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(256, 'konsultasi.bulk', 'konsultasi.bulk', 1, 'Akses konsultasi.bulk', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(257, 'konsultasi.export', 'konsultasi.export', 1, 'Akses konsultasi.export', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(258, 'konsultasi.kanban', 'konsultasi.index', 1, 'Papan kanban konsultasi', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(259, 'konsultasi.calendar', 'konsultasi.index', 1, 'Kalender jadwal konsultasi', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(260, 'konsultasi.duplicates', 'konsultasi.merge', 1, 'Akses konsultasi.duplicates', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(261, 'konsultasi.merge', 'konsultasi.merge', 1, 'Akses konsultasi.merge', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(262, 'konsultasi.remind', 'konsultasi.index', 1, 'Kirim pengingat konsultasi', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(263, 'konsultasi.import', 'konsultasi.import', 1, 'Akses konsultasi.import', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(264, 'konsultasi.saved-filters', 'konsultasi.index', 1, 'Akses konsultasi.saved-filters', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(265, 'konsultasi.whatsapp', 'konsultasi.index', 1, 'Akses konsultasi.whatsapp', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(266, 'konsultasi.calendar-ics', 'konsultasi.index', 1, 'Akses konsultasi.calendar-ics', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(267, 'konsultasi.import.template', 'konsultasi.import', 1, 'Akses konsultasi.import.template', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(268, 'konsultasi.import.preview', 'konsultasi.import', 1, 'Akses konsultasi.import.preview', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(269, 'konsultasi.import.commit', 'konsultasi.import', 1, 'Akses konsultasi.import.commit', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(270, 'konsultasi.saved-filters.store', 'konsultasi.index', 1, 'Akses konsultasi.saved-filters.store', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(271, 'konsultasi.saved-filters.apply', 'konsultasi.index', 1, 'Akses konsultasi.saved-filters.apply', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(272, 'konsultasi.saved-filters.destroy', 'konsultasi.index', 1, 'Akses konsultasi.saved-filters.destroy', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(273, 'konsultasi.reorder', 'konsultasi.index', 1, 'Akses konsultasi.reorder', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(274, 'konsultasi.bulk-undo', 'konsultasi.bulk', 1, 'Akses konsultasi.bulk-undo', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(275, 'konsultasi.saved-filters.default', 'konsultasi.index', 1, 'Akses konsultasi.saved-filters.default', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(276, 'dashboard.refresh', 'dashboard.index', 1, 'Akses dashboard.refresh', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(277, 'dashboard.export', 'dashboard.index', 1, 'Akses dashboard.export', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(278, 'dashboard.export-pdf', 'dashboard.index', 1, 'Akses dashboard.export-pdf', '2026-11-20 13:20:44', '2026-11-20 13:20:44'),
(279, 'email-setting.index', 'email-setting.index', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(280, 'email-setting.update', 'email-setting.update', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(281, 'email-setting.test', 'email-setting.test', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(282, 'orders.bulk', 'orders.bulk', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(283, 'orders.exportCsv', 'orders.exportCsv', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(284, 'orders.report', 'orders.exportCsv', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(285, 'orders.exportPdf', 'orders.exportCsv', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(286, 'orders.audit', 'orders.audit', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(287, 'orders.whatsapp', 'orders.whatsapp', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(288, 'orders.saved-filters', 'orders.savedFilters', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(289, 'orders.saved-filters.store', 'orders.savedFilters', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(290, 'orders.saved-filters.apply', 'orders.savedFilters', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(291, 'orders.saved-filters.default', 'orders.savedFilters', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(292, 'orders.saved-filters.destroy', 'orders.savedFilters', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(293, 'orders.bulk-undo', 'orders.bulkUndo', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(294, 'orders.claim', 'orders.assign', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(295, 'orders.quick-view', 'orders.quickView', 1, NULL, '2026-11-21 13:21:41', '2026-11-21 13:21:41'),
(296, 'package-promos.index', 'package.promo.index', 1, NULL, '2026-11-21 14:02:21', '2026-11-21 14:02:21'),
(297, 'package-promos.store', 'package.promo.store', 1, NULL, '2026-11-21 14:02:21', '2026-11-21 14:02:21'),
(298, 'package-promos.update', 'package.promo.update', 1, NULL, '2026-11-21 14:02:21', '2026-11-21 14:02:21'),
(299, 'package-promos.destroy', 'package.promo.destroy', 1, NULL, '2026-11-21 14:02:21', '2026-11-21 14:02:21');

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
('hmjnMAnoMW118qjDC3y3vJXBtPGj6x2CKPdX0B5E', '787b72ea-59d0-4d54-848b-c200bddafdd2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiVFhld0dzRG1oZ21iNU5vZUo0dDd2dkthRWQzUkIwMXJucEVsTUM4NCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYmFja2VuZC9wYWNrYWdlcyI7czo1OiJyb3V0ZSI7czoxNDoicGFja2FnZXMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7czozNjoiNzg3YjcyZWEtNTlkMC00ZDU0LTg0OGItYzIwMGJkZGFmZGQyIjtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2NDoiY2ZjNWJiMDE4ZGJlYTJlZmU1Y2U0OGJhYmM1ZTgxMjU1OTQwZjBhMjg2ZDc3NzJhNWM1ZTgzY2ViMmZjMmJjOSI7czoxMzoibGFzdF9hY3Rpdml0eSI7aToxNzk1MzE3NDA2O30=', 1795317406),
('qh3jbsdEydu8PgWMCK01pRr2eQi24zUYBH6C6MhQ', NULL, '127.0.0.1', 'node', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMmpuYzhYQUN1a1Nja1QwZG4zZHBjRnU5c0NPUzk1eWQwQzRROWp3ciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hdXRoL21lIjtzOjU6InJvdXRlIjtzOjc6ImF1dGgubWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1795316411),
('yJCvYKNnWg06m1JRFPiIquopWlXicC5bTHd4hduR', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoicHJ3Q3VPd0h4WkluNGVYRklJQlplU1I3TU8xM3p5SnBBOGxzYUVGbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9iYWNrZW5kL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoxNToiZGFzaGJvYXJkLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO3M6MzY6IjcwZTE1YjlmLTUzNWEtNDJhMi04ZWEwLTlhMjZhZDc5NTJlNSI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImRhNzg5ZWU0ZDcyNzliOTljNmIxMGMyNmU5MGNmZGYwYjdlYzY2MjFkYTc3NmZkZTQ2YjY2NjJkNTAyMjI0Y2UiO3M6MTM6Imxhc3RfYWN0aXZpdHkiO2k6MTc5NTMxODQ2ODtzOjExOiJtZW1iZXJfdmlldyI7czo2OiJtb2JpbGUiO3M6NjoibG9jYWxlIjtzOjI6ImlkIjt9', 1795318469);

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
(59, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 05:20:27', '2026-11-20 05:20:27', '2026-11-20 05:20:27'),
(60, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 09:08:08', '2026-11-20 09:08:08', '2026-11-20 09:08:08'),
(61, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:12:45', '2026-11-20 09:12:45', '2026-11-20 09:12:45'),
(62, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:13:06', '2026-11-20 09:13:06', '2026-11-20 09:13:06'),
(63, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/contact', 'desktop', '2026-11-20 09:13:37', '2026-11-20 09:13:37', '2026-11-20 09:13:37'),
(64, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/contact', 'desktop', '2026-11-20 09:15:02', '2026-11-20 09:15:02', '2026-11-20 09:15:02'),
(65, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:15:09', '2026-11-20 09:15:09', '2026-11-20 09:15:09'),
(66, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/contact', 'desktop', '2026-11-20 09:15:13', '2026-11-20 09:15:13', '2026-11-20 09:15:13'),
(67, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/contact', 'desktop', '2026-11-20 09:15:45', '2026-11-20 09:15:45', '2026-11-20 09:15:45'),
(68, '7ffc76da5f1b7f3aab21b414e816ef7a35d43ea48a4e9457db66f4fffec8949e', '/contact', 'desktop', '2026-11-20 09:15:59', '2026-11-20 09:15:59', '2026-11-20 09:15:59'),
(69, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:16:40', '2026-11-20 09:16:40', '2026-11-20 09:16:40'),
(70, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:18:13', '2026-11-20 09:18:13', '2026-11-20 09:18:13'),
(71, '8cc06b2055cdcdae2e97bf9832c133268a344e18e535bc3a864ce5c1a5934aa5', '/faq', 'desktop', '2026-11-20 09:18:27', '2026-11-20 09:18:27', '2026-11-20 09:18:27'),
(72, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:18:32', '2026-11-20 09:18:32', '2026-11-20 09:18:32'),
(73, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:19:41', '2026-11-20 09:19:41', '2026-11-20 09:19:41'),
(74, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:22:02', '2026-11-20 09:22:02', '2026-11-20 09:22:02'),
(75, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:22:13', '2026-11-20 09:22:13', '2026-11-20 09:22:13'),
(76, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/art-of-living', 'desktop', '2026-11-20 09:23:03', '2026-11-20 09:23:03', '2026-11-20 09:23:03'),
(77, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 09:23:18', '2026-11-20 09:23:18', '2026-11-20 09:23:18'),
(78, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/art-of-living', 'desktop', '2026-11-20 09:24:18', '2026-11-20 09:24:18', '2026-11-20 09:24:18'),
(79, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/art-of-living', 'desktop', '2026-11-20 09:25:32', '2026-11-20 09:25:32', '2026-11-20 09:25:32'),
(80, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 09:26:08', '2026-11-20 09:26:08', '2026-11-20 09:26:08'),
(81, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 09:26:09', '2026-11-20 09:26:09', '2026-11-20 09:26:09'),
(82, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:26:12', '2026-11-20 09:26:12', '2026-11-20 09:26:12'),
(83, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 09:26:21', '2026-11-20 09:26:21', '2026-11-20 09:26:21'),
(84, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 09:27:20', '2026-11-20 09:27:20', '2026-11-20 09:27:20'),
(85, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:27:41', '2026-11-20 09:27:41', '2026-11-20 09:27:41'),
(86, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:28:51', '2026-11-20 09:28:51', '2026-11-20 09:28:51'),
(87, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 09:28:58', '2026-11-20 09:28:58', '2026-11-20 09:28:58'),
(88, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 09:29:33', '2026-11-20 09:29:33', '2026-11-20 09:29:33'),
(89, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 09:30:29', '2026-11-20 09:30:29', '2026-11-20 09:30:29'),
(90, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 09:30:43', '2026-11-20 09:30:43', '2026-11-20 09:30:43'),
(91, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:30:53', '2026-11-20 09:30:53', '2026-11-20 09:30:53'),
(92, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 09:30:57', '2026-11-20 09:30:57', '2026-11-20 09:30:57'),
(93, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/pages/privacy-policy', 'desktop', '2026-11-20 09:31:57', '2026-11-20 09:31:57', '2026-11-20 09:31:57'),
(94, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:32:05', '2026-11-20 09:32:05', '2026-11-20 09:32:05'),
(95, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 09:33:37', '2026-11-20 09:33:37', '2026-11-20 09:33:37'),
(96, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:33:58', '2026-11-20 09:33:58', '2026-11-20 09:33:58'),
(97, '1b3f6aa77dbd74f1ac90751ec6a556bc69e9e6741856ad7f654da098356c2941', '/', 'desktop', '2026-11-20 09:36:13', '2026-11-20 09:36:13', '2026-11-20 09:36:13'),
(98, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 09:36:55', '2026-11-20 09:36:55', '2026-11-20 09:36:55'),
(99, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 09:47:56', '2026-11-20 09:47:56', '2026-11-20 09:47:56'),
(100, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:48:03', '2026-11-20 09:48:03', '2026-11-20 09:48:03'),
(101, '660cde3289052ff3102919993d6cd5bfa36d253500b3bac6444bba8509355550', '/', 'desktop', '2026-11-20 09:48:16', '2026-11-20 09:48:16', '2026-11-20 09:48:16'),
(102, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:48:18', '2026-11-20 09:48:18', '2026-11-20 09:48:18'),
(103, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:48:19', '2026-11-20 09:48:19', '2026-11-20 09:48:19'),
(104, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:48:24', '2026-11-20 09:48:24', '2026-11-20 09:48:24'),
(105, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:48:25', '2026-11-20 09:48:25', '2026-11-20 09:48:25'),
(106, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:48:53', '2026-11-20 09:48:53', '2026-11-20 09:48:53'),
(107, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:48:54', '2026-11-20 09:48:54', '2026-11-20 09:48:54'),
(108, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:48:55', '2026-11-20 09:48:55', '2026-11-20 09:48:55'),
(109, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:48:55', '2026-11-20 09:48:55', '2026-11-20 09:48:55'),
(110, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:48:56', '2026-11-20 09:48:56', '2026-11-20 09:48:56'),
(111, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:48:57', '2026-11-20 09:48:57', '2026-11-20 09:48:57'),
(112, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:49:00', '2026-11-20 09:49:00', '2026-11-20 09:49:00'),
(113, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:49:03', '2026-11-20 09:49:03', '2026-11-20 09:49:03'),
(114, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:49:05', '2026-11-20 09:49:05', '2026-11-20 09:49:05'),
(115, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:49:06', '2026-11-20 09:49:06', '2026-11-20 09:49:06'),
(116, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:49:07', '2026-11-20 09:49:07', '2026-11-20 09:49:07'),
(117, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:49:07', '2026-11-20 09:49:07', '2026-11-20 09:49:07'),
(118, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:49:08', '2026-11-20 09:49:08', '2026-11-20 09:49:08'),
(119, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:49:13', '2026-11-20 09:49:13', '2026-11-20 09:49:13'),
(120, '0f18f06be7560094a249fdd8c0f91013f563efbd2fafae0dd7f32468a05331af', '/', 'desktop', '2026-11-20 09:49:28', '2026-11-20 09:49:28', '2026-11-20 09:49:28'),
(121, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:49:42', '2026-11-20 09:49:42', '2026-11-20 09:49:42'),
(122, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 09:49:52', '2026-11-20 09:49:52', '2026-11-20 09:49:52'),
(123, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:50:39', '2026-11-20 09:50:39', '2026-11-20 09:50:39'),
(124, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:50:40', '2026-11-20 09:50:40', '2026-11-20 09:50:40'),
(125, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:50:52', '2026-11-20 09:50:52', '2026-11-20 09:50:52'),
(126, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:50:53', '2026-11-20 09:50:53', '2026-11-20 09:50:53'),
(127, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:50:54', '2026-11-20 09:50:54', '2026-11-20 09:50:54'),
(128, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:50:54', '2026-11-20 09:50:54', '2026-11-20 09:50:54'),
(129, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:50:54', '2026-11-20 09:50:54', '2026-11-20 09:50:54'),
(130, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:50:55', '2026-11-20 09:50:55', '2026-11-20 09:50:55'),
(131, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:50:55', '2026-11-20 09:50:55', '2026-11-20 09:50:55'),
(132, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:51:03', '2026-11-20 09:51:03', '2026-11-20 09:51:03'),
(133, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:51:06', '2026-11-20 09:51:06', '2026-11-20 09:51:06'),
(134, 'ad778cc0d4bb7fcd7e18bc327b16edd040958bc84f9a7e04caba41ba3fc6bb06', '/', 'desktop', '2026-11-20 09:51:37', '2026-11-20 09:51:37', '2026-11-20 09:51:37'),
(135, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:51:41', '2026-11-20 09:51:41', '2026-11-20 09:51:41'),
(136, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:51:42', '2026-11-20 09:51:42', '2026-11-20 09:51:42'),
(137, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:51:51', '2026-11-20 09:51:51', '2026-11-20 09:51:51'),
(138, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:51:52', '2026-11-20 09:51:52', '2026-11-20 09:51:52'),
(139, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:52:14', '2026-11-20 09:52:14', '2026-11-20 09:52:14'),
(140, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 09:53:20', '2026-11-20 09:53:20', '2026-11-20 09:53:20'),
(141, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:53:27', '2026-11-20 09:53:27', '2026-11-20 09:53:27'),
(142, 'd65f1bc3ca3879bdb9ed5ae6319534c49ab2c6b469cdccd1b9bbf583591c8968', '/', 'desktop', '2026-11-20 09:53:59', '2026-11-20 09:53:59', '2026-11-20 09:53:59'),
(143, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 09:54:11', '2026-11-20 09:54:11', '2026-11-20 09:54:11'),
(144, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:54:15', '2026-11-20 09:54:15', '2026-11-20 09:54:15'),
(145, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 09:54:19', '2026-11-20 09:54:19', '2026-11-20 09:54:19'),
(146, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:54:26', '2026-11-20 09:54:26', '2026-11-20 09:54:26'),
(147, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 09:54:53', '2026-11-20 09:54:53', '2026-11-20 09:54:53'),
(148, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:54:57', '2026-11-20 09:54:57', '2026-11-20 09:54:57'),
(149, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 09:55:10', '2026-11-20 09:55:10', '2026-11-20 09:55:10'),
(150, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:55:17', '2026-11-20 09:55:17', '2026-11-20 09:55:17'),
(151, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 09:55:37', '2026-11-20 09:55:37', '2026-11-20 09:55:37'),
(152, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:55:39', '2026-11-20 09:55:39', '2026-11-20 09:55:39'),
(153, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 09:55:44', '2026-11-20 09:55:44', '2026-11-20 09:55:44'),
(154, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:55:46', '2026-11-20 09:55:46', '2026-11-20 09:55:46'),
(155, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:55:49', '2026-11-20 09:55:49', '2026-11-20 09:55:49'),
(156, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 09:56:40', '2026-11-20 09:56:40', '2026-11-20 09:56:40'),
(157, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/packages', 'desktop', '2026-11-20 09:56:46', '2026-11-20 09:56:46', '2026-11-20 09:56:46'),
(158, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:56:47', '2026-11-20 09:56:47', '2026-11-20 09:56:47'),
(159, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 09:57:04', '2026-11-20 09:57:04', '2026-11-20 09:57:04'),
(160, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:57:07', '2026-11-20 09:57:07', '2026-11-20 09:57:07'),
(161, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 09:57:59', '2026-11-20 09:57:59', '2026-11-20 09:57:59'),
(162, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:58:02', '2026-11-20 09:58:02', '2026-11-20 09:58:02'),
(163, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 09:58:03', '2026-11-20 09:58:03', '2026-11-20 09:58:03'),
(164, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:58:10', '2026-11-20 09:58:10', '2026-11-20 09:58:10'),
(165, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:58:39', '2026-11-20 09:58:39', '2026-11-20 09:58:39'),
(166, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 09:58:46', '2026-11-20 09:58:46', '2026-11-20 09:58:46'),
(167, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:58:49', '2026-11-20 09:58:49', '2026-11-20 09:58:49'),
(168, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:59:07', '2026-11-20 09:59:07', '2026-11-20 09:59:07'),
(169, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 09:59:19', '2026-11-20 09:59:19', '2026-11-20 09:59:19'),
(170, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 09:59:23', '2026-11-20 09:59:23', '2026-11-20 09:59:23'),
(171, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 09:59:57', '2026-11-20 09:59:57', '2026-11-20 09:59:57'),
(172, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 10:00:11', '2026-11-20 10:00:11', '2026-11-20 10:00:11'),
(173, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 10:00:22', '2026-11-20 10:00:22', '2026-11-20 10:00:22'),
(174, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 10:05:55', '2026-11-20 10:05:55', '2026-11-20 10:05:55'),
(175, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:06:16', '2026-11-20 10:06:16', '2026-11-20 10:06:16'),
(176, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 10:06:26', '2026-11-20 10:06:26', '2026-11-20 10:06:26'),
(177, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 10:06:32', '2026-11-20 10:06:32', '2026-11-20 10:06:32'),
(178, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 10:08:19', '2026-11-20 10:08:19', '2026-11-20 10:08:19'),
(179, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 10:08:35', '2026-11-20 10:08:35', '2026-11-20 10:08:35'),
(180, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 10:10:04', '2026-11-20 10:10:04', '2026-11-20 10:10:04'),
(181, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:10:17', '2026-11-20 10:10:17', '2026-11-20 10:10:17'),
(182, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:10:59', '2026-11-20 10:10:59', '2026-11-20 10:10:59'),
(183, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:11:15', '2026-11-20 10:11:15', '2026-11-20 10:11:15'),
(184, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:37:22', '2026-11-20 10:37:22', '2026-11-20 10:37:22'),
(185, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:37:35', '2026-11-20 10:37:35', '2026-11-20 10:37:35'),
(186, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 10:37:39', '2026-11-20 10:37:39', '2026-11-20 10:37:39'),
(187, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 10:37:42', '2026-11-20 10:37:42', '2026-11-20 10:37:42'),
(188, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:37:45', '2026-11-20 10:37:45', '2026-11-20 10:37:45'),
(189, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:45:45', '2026-11-20 10:45:45', '2026-11-20 10:45:45'),
(190, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:46:10', '2026-11-20 10:46:10', '2026-11-20 10:46:10'),
(191, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:46:10', '2026-11-20 10:46:10', '2026-11-20 10:46:10'),
(192, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:46:11', '2026-11-20 10:46:11', '2026-11-20 10:46:11'),
(193, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:46:12', '2026-11-20 10:46:12', '2026-11-20 10:46:12'),
(194, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:54:03', '2026-11-20 10:54:03', '2026-11-20 10:54:03'),
(195, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 10:54:17', '2026-11-20 10:54:17', '2026-11-20 10:54:17'),
(196, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/faq', 'desktop', '2026-11-20 10:54:29', '2026-11-20 10:54:29', '2026-11-20 10:54:29'),
(197, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'desktop', '2026-11-20 10:54:33', '2026-11-20 10:54:33', '2026-11-20 10:54:33'),
(198, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 10:54:36', '2026-11-20 10:54:36', '2026-11-20 10:54:36'),
(199, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:56:58', '2026-11-20 10:56:58', '2026-11-20 10:56:58'),
(200, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:57:02', '2026-11-20 10:57:02', '2026-11-20 10:57:02'),
(201, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 10:57:45', '2026-11-20 10:57:45', '2026-11-20 10:57:45'),
(202, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 10:57:56', '2026-11-20 10:57:56', '2026-11-20 10:57:56'),
(203, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 10:57:59', '2026-11-20 10:57:59', '2026-11-20 10:57:59'),
(204, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:58:03', '2026-11-20 10:58:03', '2026-11-20 10:58:03'),
(205, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:58:08', '2026-11-20 10:58:08', '2026-11-20 10:58:08'),
(206, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:58:25', '2026-11-20 10:58:25', '2026-11-20 10:58:25'),
(207, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:58:51', '2026-11-20 10:58:51', '2026-11-20 10:58:51'),
(208, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/schedules', 'desktop', '2026-11-20 10:59:20', '2026-11-20 10:59:20', '2026-11-20 10:59:20'),
(209, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 10:59:22', '2026-11-20 10:59:22', '2026-11-20 10:59:22'),
(210, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 10:59:27', '2026-11-20 10:59:27', '2026-11-20 10:59:27'),
(211, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 10:59:41', '2026-11-20 10:59:41', '2026-11-20 10:59:41'),
(212, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 10:59:46', '2026-11-20 10:59:46', '2026-11-20 10:59:46'),
(213, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 11:00:09', '2026-11-20 11:00:09', '2026-11-20 11:00:09'),
(214, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 11:00:13', '2026-11-20 11:00:13', '2026-11-20 11:00:13'),
(215, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 11:00:42', '2026-11-20 11:00:42', '2026-11-20 11:00:42'),
(216, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 11:00:56', '2026-11-20 11:00:56', '2026-11-20 11:00:56'),
(217, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 11:03:50', '2026-11-20 11:03:50', '2026-11-20 11:03:50'),
(218, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 11:04:02', '2026-11-20 11:04:02', '2026-11-20 11:04:02'),
(219, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'desktop', '2026-11-20 11:04:58', '2026-11-20 11:04:58', '2026-11-20 11:04:58'),
(220, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/faq', 'desktop', '2026-11-20 11:05:00', '2026-11-20 11:05:00', '2026-11-20 11:05:00'),
(221, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/faq', 'desktop', '2026-11-20 11:05:02', '2026-11-20 11:05:02', '2026-11-20 11:05:02'),
(222, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 11:05:06', '2026-11-20 11:05:06', '2026-11-20 11:05:06'),
(223, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 11:05:16', '2026-11-20 11:05:16', '2026-11-20 11:05:16'),
(224, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 11:07:34', '2026-11-20 11:07:34', '2026-11-20 11:07:34'),
(225, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 11:07:44', '2026-11-20 11:07:44', '2026-11-20 11:07:44'),
(226, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 11:09:06', '2026-11-20 11:09:06', '2026-11-20 11:09:06'),
(227, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 11:09:11', '2026-11-20 11:09:11', '2026-11-20 11:09:11'),
(228, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 11:09:25', '2026-11-20 11:09:25', '2026-11-20 11:09:25'),
(229, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 11:12:12', '2026-11-20 11:12:12', '2026-11-20 11:12:12'),
(230, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 11:13:05', '2026-11-20 11:13:05', '2026-11-20 11:13:05'),
(231, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 11:14:13', '2026-11-20 11:14:13', '2026-11-20 11:14:13'),
(232, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 11:15:46', '2026-11-20 11:15:46', '2026-11-20 11:15:46'),
(233, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 11:17:07', '2026-11-20 11:17:07', '2026-11-20 11:17:07'),
(234, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 11:21:33', '2026-11-20 11:21:33', '2026-11-20 11:21:33'),
(235, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 11:21:39', '2026-11-20 11:21:39', '2026-11-20 11:21:39'),
(236, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 11:21:51', '2026-11-20 11:21:51', '2026-11-20 11:21:51'),
(237, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 11:22:15', '2026-11-20 11:22:15', '2026-11-20 11:22:15'),
(238, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:25:13', '2026-11-20 11:25:13', '2026-11-20 11:25:13'),
(239, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:25:32', '2026-11-20 11:25:32', '2026-11-20 11:25:32'),
(240, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:27:18', '2026-11-20 11:27:18', '2026-11-20 11:27:18'),
(241, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:27:20', '2026-11-20 11:27:20', '2026-11-20 11:27:20'),
(242, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:27:21', '2026-11-20 11:27:21', '2026-11-20 11:27:21'),
(243, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:27:21', '2026-11-20 11:27:21', '2026-11-20 11:27:21'),
(244, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:27:46', '2026-11-20 11:27:46', '2026-11-20 11:27:46'),
(245, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:27:46', '2026-11-20 11:27:46', '2026-11-20 11:27:46'),
(246, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:29:23', '2026-11-20 11:29:23', '2026-11-20 11:29:23'),
(247, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/formulir-konsultasi', 'desktop', '2026-11-20 11:29:43', '2026-11-20 11:29:43', '2026-11-20 11:29:43'),
(248, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/formulir-konsultasi', 'mobile', '2026-11-20 11:31:37', '2026-11-20 11:31:37', '2026-11-20 11:31:37'),
(249, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/formulir-konsultasi', 'mobile', '2026-11-20 11:31:38', '2026-11-20 11:31:38', '2026-11-20 11:31:38'),
(250, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/formulir-konsultasi', 'desktop', '2026-11-20 11:32:32', '2026-11-20 11:32:32', '2026-11-20 11:32:32'),
(251, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:32:46', '2026-11-20 11:32:46', '2026-11-20 11:32:46'),
(252, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 11:33:15', '2026-11-20 11:33:15', '2026-11-20 11:33:15'),
(253, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/classes', 'desktop', '2026-11-20 11:33:24', '2026-11-20 11:33:24', '2026-11-20 11:33:24'),
(254, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 11:34:15', '2026-11-20 11:34:15', '2026-11-20 11:34:15'),
(255, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:34:20', '2026-11-20 11:34:20', '2026-11-20 11:34:20'),
(256, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:34:21', '2026-11-20 11:34:21', '2026-11-20 11:34:21'),
(257, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:34:21', '2026-11-20 11:34:21', '2026-11-20 11:34:21'),
(258, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/formulir-konsultasi', 'desktop', '2026-11-20 11:39:07', '2026-11-20 11:39:07', '2026-11-20 11:39:07'),
(259, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/formulir-konsultasi', 'mobile', '2026-11-20 11:39:10', '2026-11-20 11:39:10', '2026-11-20 11:39:10'),
(260, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/formulir-konsultasi', 'mobile', '2026-11-20 11:39:10', '2026-11-20 11:39:10', '2026-11-20 11:39:10'),
(261, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:43:53', '2026-11-20 11:43:53', '2026-11-20 11:43:53'),
(262, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:44:29', '2026-11-20 11:44:29', '2026-11-20 11:44:29'),
(263, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 11:45:47', '2026-11-20 11:45:47', '2026-11-20 11:45:47'),
(264, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 12:20:12', '2026-11-20 12:20:12', '2026-11-20 12:20:12'),
(265, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 12:21:07', '2026-11-20 12:21:07', '2026-11-20 12:21:07'),
(266, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 12:26:10', '2026-11-20 12:26:10', '2026-11-20 12:26:10'),
(267, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 12:29:17', '2026-11-20 12:29:17', '2026-11-20 12:29:17'),
(268, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 12:29:28', '2026-11-20 12:29:28', '2026-11-20 12:29:28'),
(269, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/formulir-konsultasi', 'desktop', '2026-11-20 12:31:20', '2026-11-20 12:31:20', '2026-11-20 12:31:20'),
(270, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 12:31:22', '2026-11-20 12:31:22', '2026-11-20 12:31:22'),
(271, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 12:31:25', '2026-11-20 12:31:25', '2026-11-20 12:31:25'),
(272, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 12:31:34', '2026-11-20 12:31:34', '2026-11-20 12:31:34'),
(273, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 12:32:17', '2026-11-20 12:32:17', '2026-11-20 12:32:17'),
(274, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 12:32:25', '2026-11-20 12:32:25', '2026-11-20 12:32:25'),
(275, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:32:28', '2026-11-20 12:32:28', '2026-11-20 12:32:28'),
(276, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 12:32:37', '2026-11-20 12:32:37', '2026-11-20 12:32:37'),
(277, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 12:32:42', '2026-11-20 12:32:42', '2026-11-20 12:32:42'),
(278, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:32:44', '2026-11-20 12:32:44', '2026-11-20 12:32:44'),
(279, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:37:18', '2026-11-20 12:37:18', '2026-11-20 12:37:18'),
(280, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:37:55', '2026-11-20 12:37:55', '2026-11-20 12:37:55'),
(281, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 12:37:56', '2026-11-20 12:37:56', '2026-11-20 12:37:56'),
(282, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:37:58', '2026-11-20 12:37:58', '2026-11-20 12:37:58'),
(283, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/packages', 'desktop', '2026-11-20 12:38:01', '2026-11-20 12:38:01', '2026-11-20 12:38:01'),
(284, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/schedules', 'desktop', '2026-11-20 12:38:10', '2026-11-20 12:38:10', '2026-11-20 12:38:10'),
(285, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/classes', 'desktop', '2026-11-20 12:38:13', '2026-11-20 12:38:13', '2026-11-20 12:38:13'),
(286, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 12:38:17', '2026-11-20 12:38:17', '2026-11-20 12:38:17'),
(287, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:38:24', '2026-11-20 12:38:24', '2026-11-20 12:38:24'),
(288, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/schedules', 'desktop', '2026-11-20 12:38:27', '2026-11-20 12:38:27', '2026-11-20 12:38:27'),
(289, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/classes', 'desktop', '2026-11-20 12:38:28', '2026-11-20 12:38:28', '2026-11-20 12:38:28'),
(290, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 12:39:02', '2026-11-20 12:39:02', '2026-11-20 12:39:02'),
(291, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 12:39:15', '2026-11-20 12:39:15', '2026-11-20 12:39:15'),
(292, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 12:39:31', '2026-11-20 12:39:31', '2026-11-20 12:39:31'),
(293, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:39:33', '2026-11-20 12:39:33', '2026-11-20 12:39:33'),
(294, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:41:05', '2026-11-20 12:41:05', '2026-11-20 12:41:05'),
(295, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:41:27', '2026-11-20 12:41:27', '2026-11-20 12:41:27'),
(296, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:42:05', '2026-11-20 12:42:05', '2026-11-20 12:42:05'),
(297, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:43:10', '2026-11-20 12:43:10', '2026-11-20 12:43:10'),
(298, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 12:44:33', '2026-11-20 12:44:33', '2026-11-20 12:44:33'),
(299, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 12:44:46', '2026-11-20 12:44:46', '2026-11-20 12:44:46'),
(300, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/formulir-konsultasi', 'desktop', '2026-11-20 12:45:10', '2026-11-20 12:45:10', '2026-11-20 12:45:10'),
(301, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'mobile', '2026-11-20 12:45:28', '2026-11-20 12:45:28', '2026-11-20 12:45:28'),
(302, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'mobile', '2026-11-20 12:45:29', '2026-11-20 12:45:29', '2026-11-20 12:45:29'),
(303, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 12:45:35', '2026-11-20 12:45:35', '2026-11-20 12:45:35'),
(304, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:46:27', '2026-11-20 12:46:27', '2026-11-20 12:46:27'),
(305, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:47:33', '2026-11-20 12:47:33', '2026-11-20 12:47:33');
INSERT INTO `site_visits` (`id`, `visitor_hash`, `path`, `device`, `visited_at`, `created_at`, `updated_at`) VALUES
(306, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:47:37', '2026-11-20 12:47:37', '2026-11-20 12:47:37'),
(307, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:49:12', '2026-11-20 12:49:12', '2026-11-20 12:49:12'),
(308, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 12:49:17', '2026-11-20 12:49:17', '2026-11-20 12:49:17'),
(309, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:49:49', '2026-11-20 12:49:49', '2026-11-20 12:49:49'),
(310, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:59:38', '2026-11-20 12:59:38', '2026-11-20 12:59:38'),
(311, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 12:59:50', '2026-11-20 12:59:50', '2026-11-20 12:59:50'),
(312, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:00:26', '2026-11-20 13:00:26', '2026-11-20 13:00:26'),
(313, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:00:50', '2026-11-20 13:00:50', '2026-11-20 13:00:50'),
(314, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:00:50', '2026-11-20 13:00:50', '2026-11-20 13:00:50'),
(315, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:01:36', '2026-11-20 13:01:36', '2026-11-20 13:01:36'),
(316, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:01:37', '2026-11-20 13:01:37', '2026-11-20 13:01:37'),
(317, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:02:02', '2026-11-20 13:02:02', '2026-11-20 13:02:02'),
(318, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:03:33', '2026-11-20 13:03:33', '2026-11-20 13:03:33'),
(319, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:03:34', '2026-11-20 13:03:34', '2026-11-20 13:03:34'),
(320, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:03:35', '2026-11-20 13:03:35', '2026-11-20 13:03:35'),
(321, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:03:35', '2026-11-20 13:03:35', '2026-11-20 13:03:35'),
(322, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:03:35', '2026-11-20 13:03:35', '2026-11-20 13:03:35'),
(323, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:03:36', '2026-11-20 13:03:36', '2026-11-20 13:03:36'),
(324, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:03:44', '2026-11-20 13:03:44', '2026-11-20 13:03:44'),
(325, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:04:54', '2026-11-20 13:04:54', '2026-11-20 13:04:54'),
(326, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:05:12', '2026-11-20 13:05:12', '2026-11-20 13:05:12'),
(327, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:05:54', '2026-11-20 13:05:54', '2026-11-20 13:05:54'),
(328, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:06:12', '2026-11-20 13:06:12', '2026-11-20 13:06:12'),
(329, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:06:13', '2026-11-20 13:06:13', '2026-11-20 13:06:13'),
(330, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:06:14', '2026-11-20 13:06:14', '2026-11-20 13:06:14'),
(331, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:06:37', '2026-11-20 13:06:37', '2026-11-20 13:06:37'),
(332, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:06:49', '2026-11-20 13:06:49', '2026-11-20 13:06:49'),
(333, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:07:17', '2026-11-20 13:07:17', '2026-11-20 13:07:17'),
(334, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:07:49', '2026-11-20 13:07:49', '2026-11-20 13:07:49'),
(335, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:08:27', '2026-11-20 13:08:27', '2026-11-20 13:08:27'),
(336, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:09:28', '2026-11-20 13:09:28', '2026-11-20 13:09:28'),
(337, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:10:18', '2026-11-20 13:10:18', '2026-11-20 13:10:18'),
(338, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:11:30', '2026-11-20 13:11:30', '2026-11-20 13:11:30'),
(339, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:13:00', '2026-11-20 13:13:00', '2026-11-20 13:13:00'),
(340, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:13:03', '2026-11-20 13:13:03', '2026-11-20 13:13:03'),
(341, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:13:03', '2026-11-20 13:13:03', '2026-11-20 13:13:03'),
(342, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:13:04', '2026-11-20 13:13:04', '2026-11-20 13:13:04'),
(343, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:13:04', '2026-11-20 13:13:04', '2026-11-20 13:13:04'),
(344, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:13:05', '2026-11-20 13:13:05', '2026-11-20 13:13:05'),
(345, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:13:05', '2026-11-20 13:13:05', '2026-11-20 13:13:05'),
(346, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:13:05', '2026-11-20 13:13:05', '2026-11-20 13:13:05'),
(347, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:13:06', '2026-11-20 13:13:06', '2026-11-20 13:13:06'),
(348, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:13:06', '2026-11-20 13:13:06', '2026-11-20 13:13:06'),
(349, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:13:06', '2026-11-20 13:13:06', '2026-11-20 13:13:06'),
(350, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:13:38', '2026-11-20 13:13:38', '2026-11-20 13:13:38'),
(351, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:13:55', '2026-11-20 13:13:55', '2026-11-20 13:13:55'),
(352, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:14:38', '2026-11-20 13:14:38', '2026-11-20 13:14:38'),
(353, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:15:13', '2026-11-20 13:15:13', '2026-11-20 13:15:13'),
(354, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:15:33', '2026-11-20 13:15:33', '2026-11-20 13:15:33'),
(355, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:17:37', '2026-11-20 13:17:37', '2026-11-20 13:17:37'),
(356, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:17:41', '2026-11-20 13:17:41', '2026-11-20 13:17:41'),
(357, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:18:33', '2026-11-20 13:18:33', '2026-11-20 13:18:33'),
(358, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:18:36', '2026-11-20 13:18:36', '2026-11-20 13:18:36'),
(359, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:18:49', '2026-11-20 13:18:49', '2026-11-20 13:18:49'),
(360, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:18:54', '2026-11-20 13:18:54', '2026-11-20 13:18:54'),
(361, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:20:55', '2026-11-20 13:20:55', '2026-11-20 13:20:55'),
(362, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:21:41', '2026-11-20 13:21:41', '2026-11-20 13:21:41'),
(363, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:22:00', '2026-11-20 13:22:00', '2026-11-20 13:22:00'),
(364, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:22:01', '2026-11-20 13:22:01', '2026-11-20 13:22:01'),
(365, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:22:03', '2026-11-20 13:22:03', '2026-11-20 13:22:03'),
(366, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:22:05', '2026-11-20 13:22:05', '2026-11-20 13:22:05'),
(367, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:24:24', '2026-11-20 13:24:24', '2026-11-20 13:24:24'),
(368, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:24:27', '2026-11-20 13:24:27', '2026-11-20 13:24:27'),
(369, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-20 13:24:41', '2026-11-20 13:24:41', '2026-11-20 13:24:41'),
(370, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:24:45', '2026-11-20 13:24:45', '2026-11-20 13:24:45'),
(371, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:24:56', '2026-11-20 13:24:56', '2026-11-20 13:24:56'),
(372, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:25:01', '2026-11-20 13:25:01', '2026-11-20 13:25:01'),
(373, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:25:03', '2026-11-20 13:25:03', '2026-11-20 13:25:03'),
(374, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:25:04', '2026-11-20 13:25:04', '2026-11-20 13:25:04'),
(375, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:25:04', '2026-11-20 13:25:04', '2026-11-20 13:25:04'),
(376, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'desktop', '2026-11-20 13:25:10', '2026-11-20 13:25:10', '2026-11-20 13:25:10'),
(377, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/faq', 'desktop', '2026-11-20 13:25:12', '2026-11-20 13:25:12', '2026-11-20 13:25:12'),
(378, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:25:13', '2026-11-20 13:25:13', '2026-11-20 13:25:13'),
(379, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:25:53', '2026-11-20 13:25:53', '2026-11-20 13:25:53'),
(380, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:25:55', '2026-11-20 13:25:55', '2026-11-20 13:25:55'),
(381, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'desktop', '2026-11-20 13:26:05', '2026-11-20 13:26:05', '2026-11-20 13:26:05'),
(382, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/faq', 'desktop', '2026-11-20 13:26:06', '2026-11-20 13:26:06', '2026-11-20 13:26:06'),
(383, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:26:07', '2026-11-20 13:26:07', '2026-11-20 13:26:07'),
(384, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:26:45', '2026-11-20 13:26:45', '2026-11-20 13:26:45'),
(385, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:26:49', '2026-11-20 13:26:49', '2026-11-20 13:26:49'),
(386, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:27:08', '2026-11-20 13:27:08', '2026-11-20 13:27:08'),
(387, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/packages', 'desktop', '2026-11-20 13:27:15', '2026-11-20 13:27:15', '2026-11-20 13:27:15'),
(388, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:27:18', '2026-11-20 13:27:18', '2026-11-20 13:27:18'),
(389, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'desktop', '2026-11-20 13:27:19', '2026-11-20 13:27:19', '2026-11-20 13:27:19'),
(390, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:27:20', '2026-11-20 13:27:20', '2026-11-20 13:27:20'),
(391, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:27:48', '2026-11-20 13:27:48', '2026-11-20 13:27:48'),
(392, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/classes', 'desktop', '2026-11-20 13:28:11', '2026-11-20 13:28:11', '2026-11-20 13:28:11'),
(393, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 13:28:15', '2026-11-20 13:28:15', '2026-11-20 13:28:15'),
(394, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:28:19', '2026-11-20 13:28:19', '2026-11-20 13:28:19'),
(395, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:29:30', '2026-11-20 13:29:30', '2026-11-20 13:29:30'),
(396, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:29:31', '2026-11-20 13:29:31', '2026-11-20 13:29:31'),
(397, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:30:57', '2026-11-20 13:30:57', '2026-11-20 13:30:57'),
(398, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:30:58', '2026-11-20 13:30:58', '2026-11-20 13:30:58'),
(399, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:31:02', '2026-11-20 13:31:02', '2026-11-20 13:31:02'),
(400, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 13:31:08', '2026-11-20 13:31:08', '2026-11-20 13:31:08'),
(401, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:31:13', '2026-11-20 13:31:13', '2026-11-20 13:31:13'),
(402, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:31:14', '2026-11-20 13:31:14', '2026-11-20 13:31:14'),
(403, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:31:22', '2026-11-20 13:31:22', '2026-11-20 13:31:22'),
(404, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:32:46', '2026-11-20 13:32:46', '2026-11-20 13:32:46'),
(405, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/formulir-konsultasi', 'desktop', '2026-11-20 13:33:52', '2026-11-20 13:33:52', '2026-11-20 13:33:52'),
(406, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 13:33:56', '2026-11-20 13:33:56', '2026-11-20 13:33:56'),
(407, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:34:02', '2026-11-20 13:34:02', '2026-11-20 13:34:02'),
(408, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:34:13', '2026-11-20 13:34:13', '2026-11-20 13:34:13'),
(409, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:34:19', '2026-11-20 13:34:19', '2026-11-20 13:34:19'),
(410, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 13:34:35', '2026-11-20 13:34:35', '2026-11-20 13:34:35'),
(411, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 13:34:39', '2026-11-20 13:34:39', '2026-11-20 13:34:39'),
(412, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 13:34:42', '2026-11-20 13:34:42', '2026-11-20 13:34:42'),
(413, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:34:43', '2026-11-20 13:34:43', '2026-11-20 13:34:43'),
(414, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:34:55', '2026-11-20 13:34:55', '2026-11-20 13:34:55'),
(415, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:34:56', '2026-11-20 13:34:56', '2026-11-20 13:34:56'),
(416, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:36:00', '2026-11-20 13:36:00', '2026-11-20 13:36:00'),
(417, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 13:36:06', '2026-11-20 13:36:06', '2026-11-20 13:36:06'),
(418, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/', 'desktop', '2026-11-20 13:36:09', '2026-11-20 13:36:09', '2026-11-20 13:36:09'),
(419, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 13:36:23', '2026-11-20 13:36:23', '2026-11-20 13:36:23'),
(420, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/about', 'desktop', '2026-11-20 13:36:32', '2026-11-20 13:36:32', '2026-11-20 13:36:32'),
(421, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/faq', 'desktop', '2026-11-20 13:36:36', '2026-11-20 13:36:36', '2026-11-20 13:36:36'),
(422, '20a3085aea332fdb8857158448e725aaed1b7f84819c7b460b2fe02e9be9eae3', '/vendors', 'desktop', '2026-11-20 13:36:38', '2026-11-20 13:36:38', '2026-11-20 13:36:38'),
(423, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-20 13:40:28', '2026-11-20 13:40:28', '2026-11-20 13:40:28'),
(424, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 02:19:56', '2026-11-22 02:19:56', '2026-11-22 02:19:56'),
(425, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 03:00:11', '2026-11-22 03:00:11', '2026-11-22 03:00:11'),
(426, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 03:00:17', '2026-11-22 03:00:17', '2026-11-22 03:00:17'),
(427, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 03:01:23', '2026-11-22 03:01:23', '2026-11-22 03:01:23'),
(428, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 03:02:15', '2026-11-22 03:02:15', '2026-11-22 03:02:15'),
(429, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'desktop', '2026-11-22 03:02:39', '2026-11-22 03:02:39', '2026-11-22 03:02:39'),
(430, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/packages', 'desktop', '2026-11-22 03:02:42', '2026-11-22 03:02:42', '2026-11-22 03:02:42'),
(431, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-22 03:02:47', '2026-11-22 03:02:47', '2026-11-22 03:02:47'),
(432, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/venues', 'desktop', '2026-11-22 03:02:48', '2026-11-22 03:02:48', '2026-11-22 03:02:48'),
(433, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 03:02:52', '2026-11-22 03:02:52', '2026-11-22 03:02:52');

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
('dcfd5ae2-e3a0-4c4b-a3c4-937af0a086d7', 'Melda Auditia', 'Member Since 2025', 'Amazing experience. Great instructor. Space is quiet, beautiful & clean, with nice shower room for yoga before work. Would definitely come back again???????????????????', 'testimoni/B4JRMECCVIhAeVGY9yo8duoZzS6l4njN4IknQ7Mu.jpg', 3, 'active', '2026-09-11 00:22:56', '2026-09-11 00:23:09');

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
(1, '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', 'Stella', '6281321221189', NULL, NULL, NULL, 'avatars/9PmebLAmtzYHiguvCksa3jhkQXhIDZwzeOzA8Lkv.jpg', 'stella@yogaroots.id', '2026-11-20 07:23:55', '$2y$12$XTedTKKKf5hI8GCp6n9KQ.rMl9po5GSegd8GVucZUkef3lXRUHG1y', NULL, '2026-09-10 19:16:05', '2026-11-20 07:23:55', NULL, NULL, 'dasd', '2026-09-11', 'P', 'Lainnya', '5 years', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'With a practice spanning nearly three decades, Stella brings depth, versatility, and a deeply inclusive spirit to her teaching. Her Vinyasa classes flow with clarity and purpose, while her extensive training allows her to thoughtfully adapt yoga for different bodies, ages, and needs??????from beginners and children to seniors, prenatal students, and those working with specific conditions. She believes yoga is ultimately about more than the body; it is a practice that can create meaningful and lasting change from within. Having first stepped onto a yoga mat in Jakarta in 1998, Stella has witnessed the practice evolve??????and continues to share it with the same curiosity and dedication.', NULL, NULL, 'new_registered', NULL),
(2, '4a85c36a-caa6-4cc9-9ab6-47f77c0a9a67', 'Andrea', '6281380760095', NULL, NULL, NULL, 'avatars/NVMVFyR5dLWAggqVdWc1fMVclwK346qZrgu14qMo.png', 'andrea@yogaroots.id', '2026-09-10 19:18:50', '$2y$12$VOU976RIB.YtryiO5x/FLOUT3t4Y1F.lj157lKfmsfniGJdEuTrYq', NULL, '2026-09-10 19:18:50', '2026-09-10 19:20:27', NULL, NULL, 'dsad', '2026-09-11', 'P', 'Lainnya', '4 years', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Andrea??????s yoga journey began as a personal quest to navigate life??????s everyday pressures in her career as a researcher. She has experienced firsthand how mindfulness can nurture inner peace and a more compassionate, purpose-driven life??????both on and off the mat.', NULL, NULL, 'new_registered', NULL),
(3, '57621d3c-c299-4cd2-b96a-9b887752cb73', 'Yogaroots.id', '85691333321', NULL, NULL, NULL, NULL, 'tapayoga@yogaroots.id', '2026-10-04 12:08:07', '$2y$12$vgbZekTedJzp2ClDwMQrTOv4QFSMZuQBWukJ23jHyIwSfHx47jbNS', NULL, '2026-09-06 04:46:09', '2026-10-04 12:08:07', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sosmed', 'new_registered', NULL),
(18, '6704d63a-f8f0-4829-8a33-44e69e90d022', 'QA Design', NULL, NULL, NULL, NULL, NULL, 'qa.design@example.test', '2026-11-20 09:59:17', '$2y$12$.RbyVormx2IbURuiw6.G9.e2EIoTIXlZPdp0Yc/msDHlKfZcxdeAi', NULL, '2026-11-20 09:59:17', '2026-11-20 09:59:17', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'website', 'new_registered', NULL),
(4, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', '085691333321', 'jalan gandaria selatan', NULL, NULL, 'avatars/r5YzPXmlkmO6PaSdbPUjr1dL5J9LoYUYVu5eWW9v.jpg', 'wikupb@gmail.com', '2026-09-04 07:49:02', '$2y$12$zXAvvf5TAYZg1O7CxzG6xOcUeWCEvbNLVndvd6Ja5ZD.ZN/.D3Bwu', NULL, '2025-11-12 03:39:36', '2026-11-21 23:19:20', '112161865449406232159', NULL, 'Pati', '1992-03-30', 'L', 'Islam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'website', 'new_registered', NULL),
(5, '755e0eaa-50e4-46db-9003-7c1fc6674876', 'Margaretta', NULL, NULL, NULL, NULL, 'avatars/5qVq34VWSWBHYl4FgxzeSZqIEYRFotuSvA6qswLA.jpg', 'margaretta@yogaroots.id', '2026-09-10 19:09:33', '$2y$12$QxXHeDYBv0huosR6U1kUEe2Hq4uDAsdqJlo98wJhBbMiYRpAeZqKu', NULL, '2026-09-10 19:09:33', '2026-09-10 19:21:22', NULL, NULL, 'dasdas', '2026-09-11', 'P', 'Lainnya', '5 years', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'With a practice grounded in Hatha and Ashtanga, and refined through the precision of Iyengar alignment, Margaretta brings a thoughtful and therapeutic approach to teaching. Her work is especially focused on supporting the body through injury, recovery, and the transformative journey of pregnancy. She believes yoga is not about performance, but about creating a deeper relationship with the body, breath, and mind??????small practices that can quietly transform the way we move through everyday life. Beyond the mat, she continues to explore holistic approaches to wellbeing through Access Bars and Qi Gong.', NULL, NULL, 'new_registered', NULL),
(6, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', NULL, 'Jl. Gandaria', 4, 17, 'avatars/u5KMRU9jG95SYcdq0vhxnksQ0EFatee9WxrPxrtH.jpg', 'super@admin.com', '2025-09-16 00:23:37', '$2y$12$PRZJcd.nlREU6NRq3jvIVemYmlwxVPTb7En4URyNgyQSfKjWUzwDi', 'EDNrzJDWOdgx9xJuTTL6x8lKNWq4gUBVAId2YCwk6XEn4Nkcpedh2DjPvuEm', '2025-09-16 00:23:37', '2026-09-06 23:43:32', NULL, NULL, 'dasda', '2025-09-24', 'P', 'Lainnya', '10 tahun', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sdsddsad', NULL, NULL, 'new_registered', NULL),
(7, '95294c43-b3d8-4b46-b938-e4eea1f3a359', 'Soni', '8534324202', 'sdcsddfs', NULL, NULL, 'avatars/ImiYBm1Cjs7Sw0eCNBO1Dz6w5XDpNYBWnxoPy5Yc.jpg', 'soni@yogaroots.id', '2026-09-03 18:34:37', '$2y$12$jj/33jNGDxqzXGloYFeLMO5mozoP0v2atkzwf/S7ogXydL8n1hf4G', NULL, '2026-09-03 18:34:37', '2026-09-06 23:44:15', NULL, NULL, 'dasdas', '2026-09-04', 'L', 'Kristen', '20 years', '2026-09-04', NULL, NULL, NULL, NULL, NULL, NULL, 'Soni is a certified RIMYI Introductory Level 2 yoga teacher with over 20 years of experience. His athletic background includes sepak takraw and college hockey, which inspired his path to becoming a sports teacher. His extensive experience allows him to skillfully guide and mentor his students, nurturing them into better practitioners. Join him on a transformative yoga journey, where his years of experience and unwavering dedication are at your service, helping you thrive as a yoga student', NULL, NULL, 'new_registered', NULL),
(14, '98285022-71b5-4120-b6c0-b3cc3670279c', 'QA Konsultasi', NULL, NULL, NULL, NULL, NULL, 'qa.konsultasi@example.test', '2026-11-20 07:06:36', '$2y$12$IFLmMNANfUgTE..jGcqGh.f7849gC1Cc.gKTuug4vN9NzZY0voygq', NULL, '2026-11-20 07:06:36', '2026-11-20 07:23:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-11-20 07:23:40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'new_registered', NULL),
(8, 'b497082d-dd9f-4d1c-a611-9e1331eb5393', 'Ophellia', '43242342', NULL, NULL, NULL, 'avatars/76SUexkx0DwjJYj03iz6y3EBob9jrQWldE5I1GXU.jpg', 'ophellia@yogaroots.id', '2026-09-10 19:24:15', '$2y$12$EYxioqiLzM1FKBBE0XsTzeEX41St.5ocxnaLflhUHOHd0JMAY.yX.', NULL, '2026-09-10 19:24:15', '2026-11-20 12:44:20', NULL, NULL, 'dsad', '2026-09-24', 'L', 'Lainnya', '4 years', '2026-11-20', NULL, NULL, NULL, NULL, NULL, NULL, 'Rooted in mindful movement and conscious alignment, her teaching invites students to cultivate greater awareness, presence, and ease in their practice. With over 800 hours of yoga education and teacher training, she has studied Yoga Fundamentals at Iyengar Yoga Institute Indonesia. Since April 2024, she has been part of the Yoga Roots teaching community, offering a grounded and attentive approach to every practice.', NULL, NULL, 'new_registered', NULL),
(9, 'b55eb675-019c-4fa5-936e-579e2835527d', 'Yuniar', NULL, NULL, NULL, NULL, 'avatars/XjtPl3c2Q5k9OB5ukHIJRmwX2o6vdiPX465T0uZy.jpg', 'yuniar@yogaroots.id', '2026-09-10 19:07:05', '$2y$12$10Z.D3afA7m8Dlt16WfINORNIOzHJadrDcJCx.RChxCnRvlm.oikO', NULL, '2026-09-10 19:07:05', '2026-09-10 19:07:05', NULL, NULL, 'dsad', '2026-09-11', 'P', 'Islam', '20 years', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'With a practice rooted in Iyengar Yoga, Yuniar brings warmth, clarity, and a natural sense of ease to her teaching. She began practising and teaching in 2007, and since then has dedicated her practice to helping students feel at home on the mat. With a particular strength in alignment and beginner guidance, her classes offer a welcoming space to begin??????or begin again??????with curiosity and confidence. Her teaching is natural, friendly, and joyful, inviting each student to discover that yoga can be both precise and deeply personal.', NULL, NULL, 'new_registered', NULL),
(10, 'beaaf326-874c-48c2-b6b8-6bbe655c4df2', 'Sita Manwani', NULL, NULL, NULL, NULL, 'avatars/5xS7NpjOu3vPmgLh75j4rPpNSRWDr2JDAxYD2v1c.jpg', 'sita@yogaroots.id', '2026-09-10 19:13:42', '$2y$12$D7l1Tg6O9KnxTkHWNKOsouAYcxoTVuqDxqg3Zi9LDtFibOdlO1lw6', NULL, '2026-09-10 19:13:42', '2026-09-10 23:58:20', NULL, NULL, 'dasdas', '2026-09-11', 'P', 'Lainnya', '10 years', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Sita is a dedicated and certified yoga teacher with a journey that began at the Art of Living Foundation in 2006. There, she completed the Sri Sri Yoga Teachers Training Course and subsequently became an Art of Living teacher, where she learned how to teach Sudarshan Kriya breathwork. She holds accreditation from the Iyengar Yoga Institute and certifications from both the Sri Sri School of Yoga and the Indian Yoga Association. Her strong belief in yoga\'s potential to unlock the meditative mind\'s power and enhance life drives her passion. As the senior instructor at Yoga Roots, she leads the beginner yoga classes and the SKY breath work classes.\r\n\r\nSita has just completed 100hour Advanced Yoga Teacher Training including Traditional Chinese Medicine Principles, 5 Elements Theory, applied Meridian Sequences for Mandala Vinyasa System and Osteo - Thai Massage.', NULL, NULL, 'new_registered', NULL),
(11, 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ESPRO Property', '43243277777', NULL, NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocLitzhONjAN_zPOcC2rM16BHekNE3M2zU7C-e0d53-6M47R-PE=s96-c', 'esproproperty.bekasi@gmail.com', '2026-10-02 07:44:00', '$2y$12$qyrn4UD8fK4o9BJnKrDoxOH6BkCyZkyHhhlcU1/1rvohjf7NAUygW', NULL, '2026-10-02 07:44:01', '2026-11-21 13:31:57', '100949998292022765140', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'google', 'new_registered', '2026-11-20 05:41:19'),
(12, 'f8dbf191-7c45-4d71-8cfd-1e290747ade8', 'Madu Sarifa Official', '08112312313', NULL, NULL, NULL, 'https://lh3.googleusercontent.com/a/ACg8ocKQSJX_sGFgxp5u06OvpTbaVaP7GtF_lcy8UmifKhRIkUKTwQ8=s96-c', 'madusarifaofficial@gmail.com', '2026-11-20 05:52:43', '$2y$12$vYIOTaCZjtxEPuN5wn23YuXiLMbOBiP0WcMUIQ0pLcWT91DX8cYdq', NULL, '2026-11-20 05:52:43', '2026-11-20 05:53:02', '106420227434740471376', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'friend', 'new_registered', NULL);

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
('425eacb6-457f-4cd2-b750-66c41f571e79', 'b497082d-dd9f-4d1c-a611-9e1331eb5393', '11e863ef-4739-4bcf-b7d1-d599b336bb09', NULL, NULL),
('566affb7-3d73-4746-bf50-c4d4f22471e8', '755e0eaa-50e4-46db-9003-7c1fc6674876', '50b72628-5cd5-4f8d-937a-a41852efc90d', NULL, NULL),
('73bdbe77-22b2-406c-9a95-d2e23ba60294', '95294c43-b3d8-4b46-b938-e4eea1f3a359', '11e863ef-4739-4bcf-b7d1-d599b336bb09', NULL, NULL),
('8e72d2bf-17c2-4bdf-b23c-92b0b4513a2f', '787b72ea-59d0-4d54-848b-c200bddafdd2', '50b72628-5cd5-4f8d-937a-a41852efc90d', NULL, NULL),
('90cdacd4-a8a3-430b-9635-ae84f965a508', '4a85c36a-caa6-4cc9-9ab6-47f77c0a9a67', '28b37aad-a252-4bbf-9284-d25c63047b19', NULL, NULL),
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
('363f49dc-b34f-4ac2-ad18-95b140c6335e', 'Yoga Roots Studio', 'yoga-roots-studio', NULL, 'At Yoga Roots Studio, we see yoga as a journey of connection ?????? between the body, mind, and breath. Through thoughtful guidance, you??????ll deepen your practice, refine your understanding of each posture, and cultivate greater clarity, balance, and inner peace.', 'Roots Prasasta Building, Jl. Kawi Raya No.37, RT.6/RW.2, Guntur, Setiabudi, South Jakarta City, Jakarta 12980', '0813-2122-1270', 'info@yogaroots.id', NULL, 'studios/Pf3qptHBGxeJgryUNs5MdFVtEKADalPo5BdeNtqy.webp', 'active', '2026-09-08 18:30:24', '2026-09-08 18:30:24');

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
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `cons_email_dedupe_uniq` (`email_dedupe_key`),
  ADD UNIQUE KEY `cons_wa_dedupe_uniq` (`whatsapp_dedupe_key`),
  ADD KEY `cons_created_at_idx` (`created_at`),
  ADD KEY `cons_status_created_idx` (`consultation_status`,`created_at`),
  ADD KEY `cons_lead_created_idx` (`lead_status`,`created_at`),
  ADD KEY `cons_source_created_idx` (`source`,`created_at`),
  ADD KEY `cons_followup_lead_idx` (`follow_up_at`,`lead_status`),
  ADD KEY `cons_handler_lead_idx` (`handled_by`,`lead_status`),
  ADD KEY `cons_package_idx` (`package_id`),
  ADD KEY `cons_converted_idx` (`converted_to_booking_at`),
  ADD KEY `cons_email_norm_idx` (`email_normalized`),
  ADD KEY `cons_wa_e164_idx` (`whatsapp_e164`),
  ADD KEY `cons_merged_into_idx` (`merged_into_uuid`);

--
-- Indexes for table `email_settings`
--
ALTER TABLE `email_settings`
  ADD PRIMARY KEY (`uuid`);

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
-- Indexes for table `konsultasi_saved_filters`
--
ALTER TABLE `konsultasi_saved_filters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `konsultasi_saved_filters_user_name_unique` (`user_id`,`name`),
  ADD KEY `konsultasi_saved_filters_user_default_idx` (`user_id`,`is_default`);

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
  ADD UNIQUE KEY `orders_pending_key_unique` (`pending_key`),
  ADD KEY `orders_user_uuid_status_index` (`user_uuid`,`status`),
  ADD KEY `orders_package_uuid_index` (`package_uuid`),
  ADD KEY `orders_package_option_uuid_index` (`package_option_uuid`) USING BTREE,
  ADD KEY `orders_order_number_index` (`order_number`),
  ADD KEY `orders_user_option_status_index` (`user_uuid`,`package_option_uuid`,`status`),
  ADD KEY `orders_admin_status_index` (`assigned_admin_uuid`,`status`);

--
-- Indexes for table `order_saved_filters`
--
ALTER TABLE `order_saved_filters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_saved_filters_user_name_unique` (`user_uuid`,`name`),
  ADD KEY `order_saved_filters_user_default_index` (`user_uuid`,`is_default`),
  ADD KEY `order_saved_filters_user_pin_order_index` (`user_uuid`,`pinned_at`,`sort_order`);

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
-- Indexes for table `package_addons`
--
ALTER TABLE `package_addons`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `package_addons_package_uuid_index` (`package_uuid`),
  ADD KEY `package_addons_requires_addon_uuid_foreign` (`requires_addon_uuid`);

--
-- Indexes for table `package_blackout_dates`
--
ALTER TABLE `package_blackout_dates`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `package_blackout_dates_package_uuid_date_unique` (`package_uuid`,`date`);

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
-- Indexes for table `package_promos`
--
ALTER TABLE `package_promos`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `package_promos_code_unique` (`code`),
  ADD KEY `package_promos_package_uuid_is_active_index` (`package_uuid`,`is_active`);

--
-- Indexes for table `package_wishlists`
--
ALTER TABLE `package_wishlists`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `package_wishlists_user_uuid_package_uuid_unique` (`user_uuid`,`package_uuid`),
  ADD KEY `package_wishlists_package_uuid_foreign` (`package_uuid`);

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
  ADD KEY `users_membership_status_index` (`sumber_informasi`),
  ADD KEY `users_name_email_index` (`name`,`email`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=281;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_logins`
--
ALTER TABLE `failed_logins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- AUTO_INCREMENT for table `konsultasi_saved_filters`
--
ALTER TABLE `konsultasi_saved_filters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_activities`
--
ALTER TABLE `login_activities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=549;

--
-- AUTO_INCREMENT for table `login_lockouts`
--
ALTER TABLE `login_lockouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `menu_groups`
--
ALTER TABLE `menu_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=238;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=300;

--
-- AUTO_INCREMENT for table `seo`
--
ALTER TABLE `seo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `site_visits`
--
ALTER TABLE `site_visits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=434;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
-- Constraints for table `kelurahans`
--
ALTER TABLE `kelurahans`
  ADD CONSTRAINT `kelurahans_kecamatan_id_foreign` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `konsultasi_saved_filters`
--
ALTER TABLE `konsultasi_saved_filters`
  ADD CONSTRAINT `konsultasi_saved_filters_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`uuid`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `orders_package_option_uuid_foreign` FOREIGN KEY (`package_option_uuid`) REFERENCES `package_options` (`uuid`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_package_uuid_foreign` FOREIGN KEY (`package_uuid`) REFERENCES `packages` (`uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_uuid_foreign` FOREIGN KEY (`user_uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `order_saved_filters`
--
ALTER TABLE `order_saved_filters`
  ADD CONSTRAINT `order_saved_filters_user_uuid_foreign` FOREIGN KEY (`user_uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `package_addons`
--
ALTER TABLE `package_addons`
  ADD CONSTRAINT `package_addons_package_uuid_foreign` FOREIGN KEY (`package_uuid`) REFERENCES `packages` (`uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_addons_requires_addon_uuid_foreign` FOREIGN KEY (`requires_addon_uuid`) REFERENCES `package_addons` (`uuid`) ON DELETE SET NULL;

--
-- Constraints for table `package_blackout_dates`
--
ALTER TABLE `package_blackout_dates`
  ADD CONSTRAINT `package_blackout_dates_package_uuid_foreign` FOREIGN KEY (`package_uuid`) REFERENCES `packages` (`uuid`) ON DELETE CASCADE;

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
-- Constraints for table `package_promos`
--
ALTER TABLE `package_promos`
  ADD CONSTRAINT `package_promos_package_uuid_foreign` FOREIGN KEY (`package_uuid`) REFERENCES `packages` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `package_wishlists`
--
ALTER TABLE `package_wishlists`
  ADD CONSTRAINT `package_wishlists_package_uuid_foreign` FOREIGN KEY (`package_uuid`) REFERENCES `packages` (`uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_wishlists_user_uuid_foreign` FOREIGN KEY (`user_uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE;

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
