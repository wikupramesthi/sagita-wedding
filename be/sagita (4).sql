-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2026 at 04:19 PM
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
(280, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'deleted', 'App\\Models\\Package\\Package', '01a098cd-3db0-73e5-bbe6-ba63e290f1d0', 'Studio Class Pack - 90 Minutes', '{\"id\":5,\"uuid\":\"01a098cd-3db0-73e5-bbe6-ba63e290f1d0\",\"name\":\"Studio Class Pack - 90 Minutes\",\"slug\":\"studio-class-pack-90-minutes\",\"description\":\"A deeper escape from the everyday. Step into the Yogaroots studio, move with intention, breathe deeply, and give yourself more time to reconnect, recharge, and leave feeling refreshed.\",\"guest_capacity\":null,\"event_duration_hours\":null,\"venue_note\":null,\"cover_url\":null,\"is_popular\":false,\"is_active\":\"active\",\"is_published\":true,\"published_at\":null,\"lead_time_days\":0,\"price\":\"0.00\"}', NULL, 'http://localhost:8000/backend/packages/01a098cd-3db0-73e5-bbe6-ba63e290f1d0', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 03:16:46', '2026-11-22 03:16:46'),
(281, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'created', 'App\\Models\\Payment\\CheckoutSession', '01a201f9-6a56-766e-b776-475e2c83bfc9', 'CheckoutSession #01a201f9-6a56-766e-b776-475e2c83bfc9', NULL, '{\"visitor_key\":\"00bb1b28-e73a-4a74-9ad9-d177bd9714ae\",\"package_uuid\":\"01a098c6-e509-7154-869f-8d336d44ebc4\",\"status\":\"started\",\"started_at\":\"2026-11-22 13:37:32\",\"uuid\":\"01a201f9-6a56-766e-b776-475e2c83bfc9\",\"updated_at\":\"2026-11-22 13:37:32\",\"created_at\":\"2026-11-22 13:37:32\"}', 'http://localhost:8000/backend/checkout/package/01a098c6-e509-7154-869f-8d336d44ebc4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', '2026-11-22 06:37:32', '2026-11-22 06:37:32'),
(282, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'created', 'App\\Models\\Payment\\CheckoutSession', '01a2022b-5a43-7c7e-9d8b-2709b806e080', 'CheckoutSession #01a2022b-5a43-7c7e-9d8b-2709b806e080', NULL, '{\"visitor_key\":\"c99b6f60-1ab7-4c15-8494-f93f2c4d02aa\",\"package_uuid\":\"01a098c6-e509-7154-869f-8d336d44ebc4\",\"status\":\"started\",\"started_at\":\"2026-11-22 14:32:05\",\"uuid\":\"01a2022b-5a43-7c7e-9d8b-2709b806e080\",\"updated_at\":\"2026-11-22 14:32:05\",\"created_at\":\"2026-11-22 14:32:05\"}', 'http://localhost:8000/backend/checkout/package/01a098c6-e509-7154-869f-8d336d44ebc4', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:32:05', '2026-11-22 07:32:05'),
(283, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'created', 'App\\Models\\Payment\\Order', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'Order #01a2022b-88b4-7b60-b679-74d234cde5fe', NULL, '{\"user_uuid\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\",\"order_number\":\"ORD-EYESFM7YQI\",\"type\":\"package\",\"package_uuid\":\"01a098c6-e509-7154-869f-8d336d44ebc4\",\"package_option_uuid\":\"01a09965-fbf0-7307-ba83-dfa1382cf270\",\"event_date\":\"2026-11-29 00:00:00\",\"amount\":3079700,\"payment_scheme\":\"dp30\",\"total_amount\":5599000,\"amount_paid\":0,\"remaining_amount\":5599000,\"balance_due_at\":\"2026-10-30 00:00:00\",\"installment_schedule\":\"{\\\"policy\\\":\\\"equal_remaining_h90_h60_h30\\\",\\\"initial_due\\\":3079700,\\\"installments\\\":[{\\\"label\\\":\\\"Termin 1 & Termin 2 & Termin 3\\\",\\\"due_at\\\":\\\"2026-11-22\\\",\\\"amount\\\":2519300}]}\",\"installment_reminders_sent\":\"[]\",\"status\":\"pending\",\"expired_at\":\"2026-11-23 14:32:17\",\"paid_at\":null,\"addons\":\"[]\",\"promo_code\":null,\"promo_discount\":0,\"promo_snapshot\":\"{\\\"code\\\":null,\\\"type\\\":null,\\\"value\\\":null,\\\"discount\\\":0,\\\"payment_scheme\\\":\\\"dp30\\\",\\\"scheme_label\\\":\\\"DP 30%\\\",\\\"full_amount\\\":5599000,\\\"booking_fee\\\":2000000,\\\"net_amount\\\":3599000,\\\"scheme_due\\\":1079700,\\\"due_now\\\":3079700}\",\"uuid\":\"01a2022b-88b4-7b60-b679-74d234cde5fe\",\"pending_key\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5|01a09965-fbf0-7307-ba83-dfa1382cf270\",\"updated_at\":\"2026-11-22 14:32:17\",\"created_at\":\"2026-11-22 14:32:17\"}', 'http://localhost:8000/backend/orders', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:32:17', '2026-11-22 07:32:17'),
(284, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Payment\\EventDateReservation', '2026-11-29 00:00:00', 'EventDateReservation #2026-11-29 00:00:00', '{\"order_uuid\":null,\"status\":\"available\",\"expires_at\":null}', '{\"order_uuid\":\"01a2022b-88b4-7b60-b679-74d234cde5fe\",\"status\":\"held\",\"expires_at\":\"2026-11-23 14:32:17\"}', 'http://localhost:8000/backend/orders', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:32:17', '2026-11-22 07:32:17'),
(285, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'created', 'App\\Models\\Payment\\Payment', '01a2022c-79be-73c6-af98-2b72af73e0b3', 'Payment #01a2022c-79be-73c6-af98-2b72af73e0b3', NULL, '{\"order_uuid\":\"01a2022b-88b4-7b60-b679-74d234cde5fe\",\"kind\":\"dp\",\"payment_gateway\":\"manual_transfer\",\"payment_type\":\"Pembayaran Awal\",\"amount\":2000000,\"gross_amount\":2000000,\"status\":\"pending\",\"verification_status\":\"pending\",\"verified_by\":null,\"verified_at\":null,\"paid_at\":null,\"proof_image_path\":\"proofs\\/P9pu9ebhhUT9CEzNQJMnzwAd7eRE0P4LP1WgdWYl.jpg\",\"proof_uploaded_at\":\"2026-11-22 14:33:18\",\"note\":null,\"uuid\":\"01a2022c-79be-73c6-af98-2b72af73e0b3\",\"updated_at\":\"2026-11-22 14:33:18\",\"created_at\":\"2026-11-22 14:33:18\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/payments', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:33:18', '2026-11-22 07:33:18'),
(286, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Payment\\Order', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'Order #01a2022b-88b4-7b60-b679-74d234cde5fe', '{\"proof_image_path\":null,\"proof_uploaded_at\":null}', '{\"proof_image_path\":\"proofs\\/P9pu9ebhhUT9CEzNQJMnzwAd7eRE0P4LP1WgdWYl.jpg\",\"proof_uploaded_at\":\"2026-11-22 14:33:18\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/payments', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:33:18', '2026-11-22 07:33:18'),
(287, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'payment_submitted', 'App\\Models\\Payment\\Order', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'ORD-EYESFM7YQI', NULL, '{\"payment_uuid\":\"01a2022c-79be-73c6-af98-2b72af73e0b3\",\"amount\":2000000,\"kind\":\"dp\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/payments', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:33:18', '2026-11-22 07:33:18'),
(288, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Payment\\Payment', '01a2022c-79be-73c6-af98-2b72af73e0b3', 'Payment #01a2022c-79be-73c6-af98-2b72af73e0b3', '{\"status\":\"pending\",\"verification_status\":\"pending\",\"verified_by\":null,\"verified_at\":null,\"paid_at\":null}', '{\"status\":\"paid\",\"verification_status\":\"approved\",\"verified_by\":\"787b72ea-59d0-4d54-848b-c200bddafdd2\",\"verified_at\":\"2026-11-22 14:36:30\",\"paid_at\":\"2026-11-22 14:36:30\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/payments/01a2022c-79be-73c6-af98-2b72af73e0b3/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:36:30', '2026-11-22 07:36:30'),
(289, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'payment_approved', 'App\\Models\\Payment\\Order', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'ORD-EYESFM7YQI', NULL, '{\"payment_uuid\":\"01a2022c-79be-73c6-af98-2b72af73e0b3\",\"amount\":2000000}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/payments/01a2022c-79be-73c6-af98-2b72af73e0b3/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:36:30', '2026-11-22 07:36:30'),
(290, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'updated', 'App\\Models\\Payment\\Order', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'Order #01a2022b-88b4-7b60-b679-74d234cde5fe', '{\"amount_paid\":\"0.00\",\"remaining_amount\":\"5599000.00\",\"status\":\"pending\"}', '{\"amount_paid\":2000000,\"remaining_amount\":3599000,\"status\":\"partially_paid\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/payments/01a2022c-79be-73c6-af98-2b72af73e0b3/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:36:30', '2026-11-22 07:36:30'),
(291, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Payment\\OrderDocument', '01a2022f-6638-7b64-a169-61d506ec2829', 'OrderDocument #01a2022f-6638-7b64-a169-61d506ec2829', NULL, '{\"order_uuid\":\"01a2022b-88b4-7b60-b679-74d234cde5fe\",\"payment_uuid\":\"01a2022c-79be-73c6-af98-2b72af73e0b3\",\"document_key\":\"01a2022b-88b4-7b60-b679-74d234cde5fe:contract\",\"document_number\":\"CTR-ORD-EYESFM7YQI\",\"type\":\"contract\",\"snapshot\":\"{\\\"order_number\\\":\\\"ORD-EYESFM7YQI\\\",\\\"customer_name\\\":\\\"Wiku Pramesthi Bagaswara\\\",\\\"package_name\\\":\\\"Paket Pernikahan Berdua\\\",\\\"option_name\\\":\\\"25 Sesi Layanan\\\",\\\"event_date\\\":\\\"29 November 2026\\\",\\\"total_amount\\\":5599000,\\\"amount_paid\\\":2000000,\\\"remaining_amount\\\":3599000,\\\"payment_amount\\\":2000000,\\\"payment_date\\\":\\\"22 November 2026 14:36\\\",\\\"issued_at\\\":\\\"22 November 2026\\\",\\\"contract_template\\\":\\\"PERJANJIAN LAYANAN WEDDING ORGANIZER\\\\nNomor dokumen: {{document_number}}\\\\nTanggal diterbitkan: {{issued_at}}\\\\n\\\\nPenyedia layanan: Sagita Wedding Organizer\\\\nPelanggan: {{customer_name}}\\\\nNomor pesanan: {{order_number}}\\\\n\\\\nLayanan yang disepakati: {{package_name}} - {{option_name}}\\\\nTanggal acara: {{event_date}}\\\\nNilai pesanan: {{total_amount}}\\\\nPembayaran terverifikasi: {{amount_paid}}\\\\nSisa pembayaran: {{remaining_amount}}\\\\n\\\\nRincian layanan mengikuti paket dan layanan tambahan yang tercatat pada pesanan.\\\\nPerubahan tanggal atau layanan harus diajukan melalui pesanan dan disetujui admin.\\\\nKetentuan pembayaran dan pembatalan mengikuti kebijakan yang ditampilkan saat pemesanan.\\\\n\\\\nDokumen ini mencatat rincian pesanan dan belum menggantikan persetujuan\\\\\\/tanda tangan para pihak.\\\"}\",\"issued_at\":\"2026-11-22 14:36:30\",\"uuid\":\"01a2022f-6638-7b64-a169-61d506ec2829\",\"updated_at\":\"2026-11-22 14:36:30\",\"created_at\":\"2026-11-22 14:36:30\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/payments/01a2022c-79be-73c6-af98-2b72af73e0b3/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:36:30', '2026-11-22 07:36:30'),
(292, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Payment\\OrderDocument', '01a2022f-663d-74fd-bbef-569e9fe9eef3', 'OrderDocument #01a2022f-663d-74fd-bbef-569e9fe9eef3', NULL, '{\"order_uuid\":\"01a2022b-88b4-7b60-b679-74d234cde5fe\",\"payment_uuid\":\"01a2022c-79be-73c6-af98-2b72af73e0b3\",\"document_key\":\"01a2022b-88b4-7b60-b679-74d234cde5fe:invoice\",\"document_number\":\"INV-ORD-EYESFM7YQI\",\"type\":\"invoice\",\"snapshot\":\"{\\\"order_number\\\":\\\"ORD-EYESFM7YQI\\\",\\\"customer_name\\\":\\\"Wiku Pramesthi Bagaswara\\\",\\\"package_name\\\":\\\"Paket Pernikahan Berdua\\\",\\\"option_name\\\":\\\"25 Sesi Layanan\\\",\\\"event_date\\\":\\\"29 November 2026\\\",\\\"total_amount\\\":5599000,\\\"amount_paid\\\":2000000,\\\"remaining_amount\\\":3599000,\\\"payment_amount\\\":2000000,\\\"payment_date\\\":\\\"22 November 2026 14:36\\\",\\\"issued_at\\\":\\\"22 November 2026\\\"}\",\"issued_at\":\"2026-11-22 14:36:30\",\"uuid\":\"01a2022f-663d-74fd-bbef-569e9fe9eef3\",\"updated_at\":\"2026-11-22 14:36:30\",\"created_at\":\"2026-11-22 14:36:30\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/payments/01a2022c-79be-73c6-af98-2b72af73e0b3/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:36:30', '2026-11-22 07:36:30'),
(293, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Payment\\WeddingChecklistItem', '01a2022f-663f-7a31-b9a3-97d485dc1a2f', 'Konfirmasi detail acara', NULL, '{\"order_uuid\":\"01a2022b-88b4-7b60-b679-74d234cde5fe\",\"title\":\"Konfirmasi detail acara\",\"description\":\"Pastikan lokasi, waktu, dan kontak utama acara sudah benar.\",\"due_at\":\"2026-08-09 00:00:00\",\"status\":\"pending\",\"uuid\":\"01a2022f-663f-7a31-b9a3-97d485dc1a2f\",\"updated_at\":\"2026-11-22 14:36:30\",\"created_at\":\"2026-11-22 14:36:30\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/payments/01a2022c-79be-73c6-af98-2b72af73e0b3/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:36:30', '2026-11-22 07:36:30'),
(294, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Payment\\WeddingChecklistItem', '01a2022f-6640-7ddb-8100-cb64f18c87f7', 'Finalisasi jumlah tamu', NULL, '{\"order_uuid\":\"01a2022b-88b4-7b60-b679-74d234cde5fe\",\"title\":\"Finalisasi jumlah tamu\",\"description\":\"Perbarui perkiraan jumlah tamu dan kebutuhan konsumsi.\",\"due_at\":\"2026-09-06 00:00:00\",\"status\":\"pending\",\"uuid\":\"01a2022f-6640-7ddb-8100-cb64f18c87f7\",\"updated_at\":\"2026-11-22 14:36:30\",\"created_at\":\"2026-11-22 14:36:30\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/payments/01a2022c-79be-73c6-af98-2b72af73e0b3/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:36:30', '2026-11-22 07:36:30'),
(295, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Payment\\WeddingChecklistItem', '01a2022f-6641-7aac-b518-7b8eda057225', 'Konfirmasi susunan acara', NULL, '{\"order_uuid\":\"01a2022b-88b4-7b60-b679-74d234cde5fe\",\"title\":\"Konfirmasi susunan acara\",\"description\":\"Tinjau susunan acara bersama tim wedding organizer.\",\"due_at\":\"2026-10-04 00:00:00\",\"status\":\"pending\",\"uuid\":\"01a2022f-6641-7aac-b518-7b8eda057225\",\"updated_at\":\"2026-11-22 14:36:30\",\"created_at\":\"2026-11-22 14:36:30\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/payments/01a2022c-79be-73c6-af98-2b72af73e0b3/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:36:30', '2026-11-22 07:36:30'),
(296, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Payment\\WeddingChecklistItem', '01a2022f-6642-78f9-9e83-b95b1c7e290b', 'Finalisasi kebutuhan vendor', NULL, '{\"order_uuid\":\"01a2022b-88b4-7b60-b679-74d234cde5fe\",\"title\":\"Finalisasi kebutuhan vendor\",\"description\":\"Pastikan kebutuhan dekorasi, dokumentasi, dan vendor tambahan.\",\"due_at\":\"2026-11-01 00:00:00\",\"status\":\"pending\",\"uuid\":\"01a2022f-6642-78f9-9e83-b95b1c7e290b\",\"updated_at\":\"2026-11-22 14:36:30\",\"created_at\":\"2026-11-22 14:36:30\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/payments/01a2022c-79be-73c6-af98-2b72af73e0b3/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:36:30', '2026-11-22 07:36:30'),
(297, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Payment\\WeddingChecklistItem', '01a2022f-6643-76f1-b5ea-152de37d54f5', 'Konfirmasi akhir acara', NULL, '{\"order_uuid\":\"01a2022b-88b4-7b60-b679-74d234cde5fe\",\"title\":\"Konfirmasi akhir acara\",\"description\":\"Lakukan konfirmasi akhir detail dan jadwal acara.\",\"due_at\":\"2026-11-22 00:00:00\",\"status\":\"pending\",\"uuid\":\"01a2022f-6643-76f1-b5ea-152de37d54f5\",\"updated_at\":\"2026-11-22 14:36:30\",\"created_at\":\"2026-11-22 14:36:30\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/payments/01a2022c-79be-73c6-af98-2b72af73e0b3/approve', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:36:30', '2026-11-22 07:36:30'),
(298, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'created', 'App\\Models\\Payment\\OrderVendorAssignment', '01a2022f-f6aa-73ce-a44a-72c39611afa3', 'OrderVendorAssignment #01a2022f-f6aa-73ce-a44a-72c39611afa3', NULL, '{\"order_uuid\":\"01a2022b-88b4-7b60-b679-74d234cde5fe\",\"vendor_uuid\":\"4a85c36a-caa6-4cc9-9ab6-47f77c0a9a67\",\"scope\":\"dekorasi\",\"status\":\"assigned\",\"notes\":\"dasd\",\"assigned_by\":\"787b72ea-59d0-4d54-848b-c200bddafdd2\",\"uuid\":\"01a2022f-f6aa-73ce-a44a-72c39611afa3\",\"updated_at\":\"2026-11-22 14:37:07\",\"created_at\":\"2026-11-22 14:37:07\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/vendors', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:37:07', '2026-11-22 07:37:07'),
(299, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'vendor_assigned', 'App\\Models\\Payment\\Order', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'ORD-EYESFM7YQI', NULL, '{\"assignment_uuid\":\"01a2022f-f6aa-73ce-a44a-72c39611afa3\",\"vendor_uuid\":\"4a85c36a-caa6-4cc9-9ab6-47f77c0a9a67\",\"vendor_name\":\"Andrea\",\"scope\":\"dekorasi\",\"status\":\"assigned\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/vendors', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:37:07', '2026-11-22 07:37:07'),
(300, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Payment\\WeddingChecklistItem', '01a2022f-663f-7a31-b9a3-97d485dc1a2f', 'Konfirmasi detail acara', '{\"status\":\"pending\",\"completed_at\":null,\"completed_by\":null}', '{\"status\":\"completed\",\"completed_at\":\"2026-11-22 14:38:07\",\"completed_by\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/checklist/01a2022f-663f-7a31-b9a3-97d485dc1a2f', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 07:38:07', '2026-11-22 07:38:07'),
(301, NULL, NULL, NULL, 'created', 'App\\Models\\LoginLockout', '7', 'LoginLockout #7', NULL, '{\"type\":\"ip\",\"value\":\"127.0.0.1\",\"attempts\":1,\"last_attempt_at\":\"2026-11-22 15:50:56\",\"updated_at\":\"2026-11-22 15:50:56\",\"created_at\":\"2026-11-22 15:50:56\",\"id\":7}', 'http://localhost:8000/auth/login', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-11-22 08:50:56', '2026-11-22 08:50:56'),
(302, NULL, NULL, NULL, 'created', 'App\\Models\\LoginLockout', '8', 'LoginLockout #8', NULL, '{\"type\":\"email\",\"value\":\"wikuob@gmail.com\",\"attempts\":1,\"last_attempt_at\":\"2026-11-22 15:50:56\",\"updated_at\":\"2026-11-22 15:50:56\",\"created_at\":\"2026-11-22 15:50:56\",\"id\":8}', 'http://localhost:8000/auth/login', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-11-22 08:50:56', '2026-11-22 08:50:56'),
(303, NULL, NULL, NULL, 'updated', 'App\\Models\\LoginLockout', '7', 'LoginLockout #7', '{\"attempts\":1}', '{\"attempts\":2}', 'http://localhost:8000/auth/login', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-11-22 08:50:56', '2026-11-22 08:50:56'),
(304, NULL, NULL, NULL, 'updated', 'App\\Models\\LoginLockout', '8', 'LoginLockout #8', '{\"attempts\":1}', '{\"attempts\":2}', 'http://localhost:8000/auth/login', '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', '2026-11-22 08:50:56', '2026-11-22 08:50:56'),
(305, NULL, NULL, NULL, 'updated', 'App\\Models\\EmailSetting', '206ab35b-cb99-4581-9521-e4556fb135af', 'EmailSetting #206ab35b-cb99-4581-9521-e4556fb135af', '{\"mail_host\":\"127.0.0.1\",\"mail_port\":2525,\"mail_encryption\":null}', '{\"mail_host\":\"smtp-relay.brevo.com\",\"mail_port\":587,\"mail_encryption\":\"tls\"}', NULL, NULL, NULL, '2026-11-22 09:23:55', '2026-11-22 09:23:55'),
(306, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', 'whatsapp_opened', 'App\\Models\\Consultation', '01b1f7d3-e121-7dba-b36f-69539d1cb839', 'Rani & Dimas', NULL, '{\"whatsapp\":\"081234567890\"}', 'http://localhost:8000/backend/konsultasi/01b1f7d3-e121-7dba-b36f-69539d1cb839/whatsapp', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', '2026-11-22 12:27:12', '2026-11-22 12:27:12'),
(307, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Payment\\WeddingChecklistItem', '01a2022f-6640-7ddb-8100-cb64f18c87f7', 'Finalisasi jumlah tamu', '{\"status\":\"pending\",\"completed_at\":null,\"completed_by\":null}', '{\"status\":\"completed\",\"completed_at\":\"2026-11-22 20:23:32\",\"completed_by\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/checklist/01a2022f-6640-7ddb-8100-cb64f18c87f7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', '2026-11-22 13:23:32', '2026-11-22 13:23:32'),
(308, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Payment\\WeddingChecklistItem', '01a2022f-6641-7aac-b518-7b8eda057225', 'Konfirmasi susunan acara', '{\"status\":\"pending\",\"completed_at\":null,\"completed_by\":null}', '{\"status\":\"completed\",\"completed_at\":\"2026-11-22 20:23:53\",\"completed_by\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/checklist/01a2022f-6641-7aac-b518-7b8eda057225', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', '2026-11-22 13:23:53', '2026-11-22 13:23:53'),
(309, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Payment\\WeddingChecklistItem', '01a2022f-6641-7aac-b518-7b8eda057225', 'Konfirmasi susunan acara', '{\"status\":\"completed\",\"completed_at\":\"2026-11-22T13:23:53.000000Z\",\"completed_by\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\"}', '{\"status\":\"pending\",\"completed_at\":null,\"completed_by\":null}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/checklist/01a2022f-6641-7aac-b518-7b8eda057225', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', '2026-11-22 13:23:58', '2026-11-22 13:23:58'),
(310, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Payment\\WeddingChecklistItem', '01a2022f-6640-7ddb-8100-cb64f18c87f7', 'Finalisasi jumlah tamu', '{\"status\":\"completed\",\"completed_at\":\"2026-11-22T13:23:32.000000Z\",\"completed_by\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\"}', '{\"status\":\"pending\",\"completed_at\":null,\"completed_by\":null}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/checklist/01a2022f-6640-7ddb-8100-cb64f18c87f7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', '2026-11-22 13:25:35', '2026-11-22 13:25:35'),
(311, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Payment\\WeddingChecklistItem', '01a2022f-6640-7ddb-8100-cb64f18c87f7', 'Finalisasi jumlah tamu', '{\"status\":\"pending\",\"completed_at\":null,\"completed_by\":null}', '{\"status\":\"completed\",\"completed_at\":\"2026-11-22 20:27:08\",\"completed_by\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\"}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/checklist/01a2022f-6640-7ddb-8100-cb64f18c87f7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', '2026-11-22 13:27:08', '2026-11-22 13:27:08'),
(312, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', 'updated', 'App\\Models\\Payment\\WeddingChecklistItem', '01a2022f-6640-7ddb-8100-cb64f18c87f7', 'Finalisasi jumlah tamu', '{\"status\":\"completed\",\"completed_at\":\"2026-11-22T13:27:08.000000Z\",\"completed_by\":\"70e15b9f-535a-42a2-8ea0-9a26ad7952e5\"}', '{\"status\":\"pending\",\"completed_at\":null,\"completed_by\":null}', 'http://localhost:8000/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe/checklist/01a2022f-6640-7ddb-8100-cb64f18c87f7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', '2026-11-22 13:27:14', '2026-11-22 13:27:14');

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
('00cbd7043d4bafebd6ab34129b30c493', 'i:1;', 1795358997),
('00cbd7043d4bafebd6ab34129b30c493:timer', 'i:1795358997;', 1795358997),
('00eba20fd7dc4883aa59950354baa609', 'i:1;', 1795360663),
('00eba20fd7dc4883aa59950354baa609:timer', 'i:1795360663;', 1795360663),
('016beeb1bb96f78d19f679ebeba62ff0', 'i:1;', 1795355572),
('016beeb1bb96f78d19f679ebeba62ff0:timer', 'i:1795355572;', 1795355572),
('025ded1c4fb85f92a80295943917f645', 'i:1;', 1795356878),
('025ded1c4fb85f92a80295943917f645:timer', 'i:1795356878;', 1795356878),
('02ff3e9e18e48e64b5c2966c60b75a83', 'i:1;', 1795357455),
('02ff3e9e18e48e64b5c2966c60b75a83:timer', 'i:1795357455;', 1795357455),
('032bfb57835706ea2c7343eae3792eb9', 'i:1;', 1795356294),
('032bfb57835706ea2c7343eae3792eb9:timer', 'i:1795356294;', 1795356294),
('033b46fbcf76b9b8782e26fe5ae29846', 'i:1;', 1795356456),
('033b46fbcf76b9b8782e26fe5ae29846:timer', 'i:1795356456;', 1795356456),
('033e52c85b94f74f790fea932084a7c8', 'i:1;', 1795360337),
('033e52c85b94f74f790fea932084a7c8:timer', 'i:1795360337;', 1795360337),
('034a7ed0f0d61adf0ccee4fb1dd8bee2', 'i:1;', 1795355573),
('034a7ed0f0d61adf0ccee4fb1dd8bee2:timer', 'i:1795355573;', 1795355573),
('03af83a460fd871536c112d59235743c', 'i:1;', 1795360049),
('03af83a460fd871536c112d59235743c:timer', 'i:1795360049;', 1795360049),
('03cced41c6cfeb0fb6838272e7c7b084', 'i:1;', 1795360047),
('03cced41c6cfeb0fb6838272e7c7b084:timer', 'i:1795360047;', 1795360047),
('03dd7e71c711348d58fffd1d1d87603b', 'i:1;', 1795355394),
('03dd7e71c711348d58fffd1d1d87603b:timer', 'i:1795355394;', 1795355394),
('03ef5bf27b607658f9f5f2f441bd71fb', 'i:1;', 1795358859),
('03ef5bf27b607658f9f5f2f441bd71fb:timer', 'i:1795358859;', 1795358859),
('0448c4a8df0837c40e5069c6e12b6e8c', 'i:1;', 1795359870),
('0448c4a8df0837c40e5069c6e12b6e8c:timer', 'i:1795359870;', 1795359870),
('049a54303a8d46b5b483884b1d7b9b2a', 'i:1;', 1795358332),
('049a54303a8d46b5b483884b1d7b9b2a:timer', 'i:1795358332;', 1795358332),
('04b067bccfb1cca2c1437aa69122215b', 'i:1;', 1795356717),
('04b067bccfb1cca2c1437aa69122215b:timer', 'i:1795356717;', 1795356717),
('052dc4ad8bb523cebf6353741f71aa09', 'i:1;', 1795358030),
('052dc4ad8bb523cebf6353741f71aa09:timer', 'i:1795358030;', 1795358030),
('05631b40f9321d6cd68196c73d36d412', 'i:1;', 1795360337),
('05631b40f9321d6cd68196c73d36d412:timer', 'i:1795360337;', 1795360337),
('05706083b2cbc922b21524c56e01f4c9', 'i:1;', 1795357201),
('05706083b2cbc922b21524c56e01f4c9:timer', 'i:1795357201;', 1795357201),
('062bc8941813981e24fe28a06141978f', 'i:1;', 1795360336),
('062bc8941813981e24fe28a06141978f:timer', 'i:1795360336;', 1795360336),
('066504eade44f269ac9b7a3d5040a66a', 'i:1;', 1795357202),
('066504eade44f269ac9b7a3d5040a66a:timer', 'i:1795357202;', 1795357202),
('06d9f447d2364f379c5e21a41b62230a', 'i:1;', 1795355289),
('06d9f447d2364f379c5e21a41b62230a:timer', 'i:1795355289;', 1795355289),
('06e52b06515a2d4721487e51ab7a34be', 'i:1;', 1795355394),
('06e52b06515a2d4721487e51ab7a34be:timer', 'i:1795355394;', 1795355394),
('070f9ce631aa7f59e30eaf5237a05db5', 'i:1;', 1795359058),
('070f9ce631aa7f59e30eaf5237a05db5:timer', 'i:1795359058;', 1795359058),
('075ebe044d53ed2d4980fc4a54a9bb02', 'i:1;', 1795356457),
('075ebe044d53ed2d4980fc4a54a9bb02:timer', 'i:1795356457;', 1795356457),
('089cb923db108cbc66d5d53708e1991b', 'i:1;', 1795358500),
('089cb923db108cbc66d5d53708e1991b:timer', 'i:1795358500;', 1795358500),
('08e8fde7cecdf0f734259e89c7345b24', 'i:1;', 1795356878),
('08e8fde7cecdf0f734259e89c7345b24:timer', 'i:1795356878;', 1795356878),
('090cce2e5105c154b51f133f12d651aa', 'i:1;', 1795360048),
('090cce2e5105c154b51f133f12d651aa:timer', 'i:1795360048;', 1795360048),
('09167eaddab1b1c2b552f248b6ea5cbe', 'i:1;', 1795356304),
('09167eaddab1b1c2b552f248b6ea5cbe:timer', 'i:1795356304;', 1795356304),
('091f59abfdae848bfd6ea5d83a2bfd24', 'i:1;', 1795360227),
('091f59abfdae848bfd6ea5d83a2bfd24:timer', 'i:1795360227;', 1795360227),
('096ea223e2ede4c086649a4f6661f880', 'i:1;', 1795357454),
('096ea223e2ede4c086649a4f6661f880:timer', 'i:1795357454;', 1795357454),
('098018d25a291afaac2b022318dc2b5c', 'i:1;', 1795359870),
('098018d25a291afaac2b022318dc2b5c:timer', 'i:1795359870;', 1795359870),
('09a72a8e403b83d10bc2d01506463f3a', 'i:1;', 1795360227),
('09a72a8e403b83d10bc2d01506463f3a:timer', 'i:1795360227;', 1795360227),
('0a525663599d93d4b81c980a3d710f1b', 'i:1;', 1795355574),
('0a525663599d93d4b81c980a3d710f1b:timer', 'i:1795355574;', 1795355574),
('0abdbbb76ced82956647b8e13dc59034', 'i:1;', 1795356456),
('0abdbbb76ced82956647b8e13dc59034:timer', 'i:1795356456;', 1795356456),
('0ae4da5fa610b1094f929787987eb622', 'i:1;', 1795356304),
('0ae4da5fa610b1094f929787987eb622:timer', 'i:1795356304;', 1795356304),
('0b72e64d1a93b581720084860c1be623', 'i:1;', 1795359439),
('0b72e64d1a93b581720084860c1be623:timer', 'i:1795359439;', 1795359439),
('0bb64e5451198366661ffb1140a1a5a9', 'i:1;', 1795360663),
('0bb64e5451198366661ffb1140a1a5a9:timer', 'i:1795360663;', 1795360663),
('0bdc24ae954f63efcfe5885749819ba0', 'i:1;', 1795360664),
('0bdc24ae954f63efcfe5885749819ba0:timer', 'i:1795360664;', 1795360664),
('0c4955c3d8850edf348efea15246d67a', 'i:1;', 1795356457),
('0c4955c3d8850edf348efea15246d67a:timer', 'i:1795356457;', 1795356457),
('0c732b71dad3925a55ecb98417dbcb9b', 'i:1;', 1795359059),
('0c732b71dad3925a55ecb98417dbcb9b:timer', 'i:1795359059;', 1795359059),
('0c8ea741ca0b3d30c7c1ea3ecca60aad', 'i:1;', 1795360488),
('0c8ea741ca0b3d30c7c1ea3ecca60aad:timer', 'i:1795360488;', 1795360488),
('0d42c55f670784bbad7f2900a1644438', 'i:1;', 1795358030),
('0d42c55f670784bbad7f2900a1644438:timer', 'i:1795358030;', 1795358030),
('0d6d3a8ad625102db03adfec8f1bf03d', 'i:1;', 1795359440),
('0d6d3a8ad625102db03adfec8f1bf03d:timer', 'i:1795359440;', 1795359440),
('0da3a225224b94bb22539f5c7255a338', 'i:1;', 1795356786),
('0da3a225224b94bb22539f5c7255a338:timer', 'i:1795356786;', 1795356786),
('0dcca8f00ebed43659430f3e82aa2e36', 'i:1;', 1795357455),
('0dcca8f00ebed43659430f3e82aa2e36:timer', 'i:1795357455;', 1795357455),
('0e4182274904bcea6ba854fbfabdca0a', 'i:1;', 1795356717),
('0e4182274904bcea6ba854fbfabdca0a:timer', 'i:1795356717;', 1795356717),
('0fd603af472c5143320c39ce3b9274eb', 'i:1;', 1795359441),
('0fd603af472c5143320c39ce3b9274eb:timer', 'i:1795359441;', 1795359441),
('0feccc7f3ae960843cd9a7f043525b3c', 'i:1;', 1795359058),
('0feccc7f3ae960843cd9a7f043525b3c:timer', 'i:1795359058;', 1795359058),
('1038cf37083b02dba297b1f5dcc70a8f', 'i:1;', 1795358030),
('1038cf37083b02dba297b1f5dcc70a8f:timer', 'i:1795358030;', 1795358030),
('118036f16270e9f90fd9c6451e2591d3', 'i:1;', 1795358332),
('118036f16270e9f90fd9c6451e2591d3:timer', 'i:1795358332;', 1795358332),
('11852c5e4ec81a1036a24c4de592af5f', 'i:1;', 1795358857),
('11852c5e4ec81a1036a24c4de592af5f:timer', 'i:1795358857;', 1795358857),
('11d229e8bd3b66ca8b26f65c12f3bdb0', 'i:1;', 1795356787),
('11d229e8bd3b66ca8b26f65c12f3bdb0:timer', 'i:1795356787;', 1795356787),
('1266237ac5c89912023729fce84eef25', 'i:1;', 1795360227),
('1266237ac5c89912023729fce84eef25:timer', 'i:1795360227;', 1795360227),
('12778f7b86673c162cae3e799d7b027b', 'i:1;', 1795356456),
('12778f7b86673c162cae3e799d7b027b:timer', 'i:1795356456;', 1795356456),
('127e50f847f632ae58e5a5749be132a3', 'i:1;', 1795360663),
('127e50f847f632ae58e5a5749be132a3:timer', 'i:1795360663;', 1795360663),
('133532a2d3948d53c368c14bff9a051e', 'i:1;', 1795357203),
('133532a2d3948d53c368c14bff9a051e:timer', 'i:1795357203;', 1795357203),
('133fdf75c37d82f9df0cee7536bd18e2', 'i:1;', 1795356786),
('133fdf75c37d82f9df0cee7536bd18e2:timer', 'i:1795356786;', 1795356786),
('1340a70d5b590408618cd52fef908d7f', 'i:1;', 1795358030),
('1340a70d5b590408618cd52fef908d7f:timer', 'i:1795358030;', 1795358030),
('13ded021bdea9674bbabd3e528049437', 'i:1;', 1795358031),
('13ded021bdea9674bbabd3e528049437:timer', 'i:1795358031;', 1795358031),
('141fd5e3781de894deef252a63e61236', 'i:1;', 1795359441),
('141fd5e3781de894deef252a63e61236:timer', 'i:1795359441;', 1795359441),
('144195f6c43de7cb35cf8240ec5e951c', 'i:1;', 1795355288),
('144195f6c43de7cb35cf8240ec5e951c:timer', 'i:1795355288;', 1795355288),
('147181287f9ec45710768cd1637c3301', 'i:1;', 1795359439),
('147181287f9ec45710768cd1637c3301:timer', 'i:1795359439;', 1795359439),
('1552efd8af37780d57391bf752a231a2', 'i:1;', 1795359440),
('1552efd8af37780d57391bf752a231a2:timer', 'i:1795359440;', 1795359440),
('160ee49ca4bc6f4eee20e42a3e6d0652', 'i:1;', 1795360662),
('160ee49ca4bc6f4eee20e42a3e6d0652:timer', 'i:1795360662;', 1795360662),
('166034a447b83126788b3a6b203876c8', 'i:1;', 1795356005),
('166034a447b83126788b3a6b203876c8:timer', 'i:1795356005;', 1795356005),
('16732f4064be5577a29dc140230b9ff7', 'i:1;', 1795357456),
('16732f4064be5577a29dc140230b9ff7:timer', 'i:1795357456;', 1795357456),
('168e50c29ef8b4cd5e4b73c6c8cc88ca', 'i:1;', 1795356005),
('168e50c29ef8b4cd5e4b73c6c8cc88ca:timer', 'i:1795356005;', 1795356005),
('16d75b91b63bd25f4ac2f2549e61991c', 'i:1;', 1795355574),
('16d75b91b63bd25f4ac2f2549e61991c:timer', 'i:1795355574;', 1795355574),
('16e7629645d951ac70818b2f2746a477', 'i:1;', 1795357454),
('16e7629645d951ac70818b2f2746a477:timer', 'i:1795357454;', 1795357454),
('17bd87c7a50d71f69106b0d60c7c29e6', 'i:1;', 1795357805),
('17bd87c7a50d71f69106b0d60c7c29e6:timer', 'i:1795357805;', 1795357805),
('17bdd3868b1a586d3129768ba8d97521', 'i:1;', 1795356786),
('17bdd3868b1a586d3129768ba8d97521:timer', 'i:1795356786;', 1795356786),
('1828a34ab65d7ab0001a0a26939815ef', 'i:1;', 1795360227),
('1828a34ab65d7ab0001a0a26939815ef:timer', 'i:1795360227;', 1795360227),
('18ea24db5ae6c5fd3919992f0f23ed06', 'i:1;', 1795359872),
('18ea24db5ae6c5fd3919992f0f23ed06:timer', 'i:1795359872;', 1795359872),
('192255931ab49f13c5e606762430254c', 'i:1;', 1795356293),
('192255931ab49f13c5e606762430254c:timer', 'i:1795356293;', 1795356293),
('197987a04f4c32e3340ff3b0995eb59d', 'i:1;', 1795359060),
('197987a04f4c32e3340ff3b0995eb59d:timer', 'i:1795359060;', 1795359060),
('197bfb8d5d47f0674f29be6a972f29e0', 'i:1;', 1795356364),
('197bfb8d5d47f0674f29be6a972f29e0:timer', 'i:1795356364;', 1795356364),
('19aa2db04a92352d4192d63abac4f14f', 'i:1;', 1795358029),
('19aa2db04a92352d4192d63abac4f14f:timer', 'i:1795358029;', 1795358029),
('1a2fd786fce4161fecce071a19ad140c', 'i:1;', 1795357806),
('1a2fd786fce4161fecce071a19ad140c:timer', 'i:1795357806;', 1795357806),
('1a697adb8b8fde825fba38905550a1e4', 'i:1;', 1795356457),
('1a697adb8b8fde825fba38905550a1e4:timer', 'i:1795356457;', 1795356457),
('1ad577084fb2543b41284f8bc6358a29', 'i:1;', 1795355393),
('1ad577084fb2543b41284f8bc6358a29:timer', 'i:1795355393;', 1795355393),
('1ae0ae78f86ba0b2ff1bc31528c718a1', 'i:1;', 1795356879),
('1ae0ae78f86ba0b2ff1bc31528c718a1:timer', 'i:1795356879;', 1795356879),
('1ae957c9354e92fdee41cc7883edf057', 'i:1;', 1795358499),
('1ae957c9354e92fdee41cc7883edf057:timer', 'i:1795358499;', 1795358499),
('1b49760173aade5683e2f0a88eb1a54b', 'i:1;', 1795356785),
('1b49760173aade5683e2f0a88eb1a54b:timer', 'i:1795356785;', 1795356785),
('1b80edc54b2e785135514598d4182478', 'i:1;', 1795357806),
('1b80edc54b2e785135514598d4182478:timer', 'i:1795357806;', 1795357806),
('1bcc332dfb25440a0b5236d7e0bc509a', 'i:1;', 1795359059),
('1bcc332dfb25440a0b5236d7e0bc509a:timer', 'i:1795359059;', 1795359059),
('1beb4d23eee19af107c48a4c1f29c84d', 'i:1;', 1795356456),
('1beb4d23eee19af107c48a4c1f29c84d:timer', 'i:1795356456;', 1795356456),
('1bff289e81596c1777c87f593776333d', 'i:1;', 1795358861),
('1bff289e81596c1777c87f593776333d:timer', 'i:1795358861;', 1795358861),
('1c8ee7b07f7ef9049a3a647c200cf3b4', 'i:1;', 1795360228),
('1c8ee7b07f7ef9049a3a647c200cf3b4:timer', 'i:1795360228;', 1795360228),
('1d1abd270316f0eb3e44ec3f0bce274f', 'i:1;', 1795360663),
('1d1abd270316f0eb3e44ec3f0bce274f:timer', 'i:1795360663;', 1795360663),
('1d89b5c5e8144784f9e2ca6e3ddea650', 'i:1;', 1795356879),
('1d89b5c5e8144784f9e2ca6e3ddea650:timer', 'i:1795356879;', 1795356879),
('1d8a8cbffbe7e553e09f45ca009b539f', 'i:1;', 1795359439),
('1d8a8cbffbe7e553e09f45ca009b539f:timer', 'i:1795359439;', 1795359439),
('1e8f410e48fe03be674575ef73dd4eaa', 'i:1;', 1795355395),
('1e8f410e48fe03be674575ef73dd4eaa:timer', 'i:1795355395;', 1795355395),
('1e9af8e2a9bac9f3004b8fe4c18bfbc6', 'i:1;', 1795358996),
('1e9af8e2a9bac9f3004b8fe4c18bfbc6:timer', 'i:1795358996;', 1795358996),
('1f4aa66404fdb1ce9ce5eb7b682e2097', 'i:1;', 1795358997),
('1f4aa66404fdb1ce9ce5eb7b682e2097:timer', 'i:1795358997;', 1795358997),
('201c403517c0b2144c11a575969e4b09', 'i:1;', 1795360227),
('201c403517c0b2144c11a575969e4b09:timer', 'i:1795360227;', 1795360227),
('20485adecbd81035f7a32b4ca9f6a32a', 'i:1;', 1795358996),
('20485adecbd81035f7a32b4ca9f6a32a:timer', 'i:1795358996;', 1795358996),
('20582bbbaf04787c51fcee4f1350f47e', 'i:1;', 1795359061),
('20582bbbaf04787c51fcee4f1350f47e:timer', 'i:1795359061;', 1795359061),
('206ce645bc2dbd90884be701719eb391', 'i:1;', 1795356293),
('206ce645bc2dbd90884be701719eb391:timer', 'i:1795356293;', 1795356293),
('20bc193d7d9bf4e68786f328c5ab9548', 'i:1;', 1795359870),
('20bc193d7d9bf4e68786f328c5ab9548:timer', 'i:1795359870;', 1795359870),
('210bb26af00efab4073a865870bcfad4', 'i:1;', 1795356383),
('210bb26af00efab4073a865870bcfad4:timer', 'i:1795356383;', 1795356383),
('21af41c7ce5334ff242fa376b544dc8a', 'i:1;', 1795360229),
('21af41c7ce5334ff242fa376b544dc8a:timer', 'i:1795360229;', 1795360229),
('223bd0e51603386aade7a14ad921677e', 'i:1;', 1795358332),
('223bd0e51603386aade7a14ad921677e:timer', 'i:1795358332;', 1795358332),
('224d2dace5ef7079e349129d3f3fa6d6', 'i:1;', 1795356717),
('224d2dace5ef7079e349129d3f3fa6d6:timer', 'i:1795356717;', 1795356717),
('22f3a0d6063f777aa3325b3e46fa5ba5', 'i:1;', 1795356005),
('22f3a0d6063f777aa3325b3e46fa5ba5:timer', 'i:1795356005;', 1795356005),
('2316d7b03634d89442ce331c878b4d14', 'i:1;', 1795360336),
('2316d7b03634d89442ce331c878b4d14:timer', 'i:1795360336;', 1795360336),
('23c3fe8843e7034767c6326df8d0d2e2', 'i:1;', 1795357807),
('23c3fe8843e7034767c6326df8d0d2e2:timer', 'i:1795357807;', 1795357807),
('23ee7d9deb5890f581593176788bdcb0', 'i:1;', 1795355288),
('23ee7d9deb5890f581593176788bdcb0:timer', 'i:1795355288;', 1795355288),
('25ce2245f247154748f612221ad2fd48', 'i:1;', 1795356304),
('25ce2245f247154748f612221ad2fd48:timer', 'i:1795356304;', 1795356304),
('26579ccac3c7bc2e94180163ceee4265', 'i:1;', 1795355395),
('26579ccac3c7bc2e94180163ceee4265:timer', 'i:1795355395;', 1795355395),
('27a533cd2795aa3093de3e54a054d07f', 'i:1;', 1795359439),
('27a533cd2795aa3093de3e54a054d07f:timer', 'i:1795359439;', 1795359439),
('27e2c0aaadccb472816d72586f8b0534', 'i:1;', 1795357202),
('27e2c0aaadccb472816d72586f8b0534:timer', 'i:1795357202;', 1795357202),
('289e497ce8059f7fe9d407751e7c7345', 'i:1;', 1795358999),
('289e497ce8059f7fe9d407751e7c7345:timer', 'i:1795358999;', 1795358999),
('28acbf217f6c37e99b0661a270011801', 'i:1;', 1795360663),
('28acbf217f6c37e99b0661a270011801:timer', 'i:1795360663;', 1795360663),
('28d9f2e8f182c491a782b95b9b2f218b', 'i:1;', 1795356365),
('28d9f2e8f182c491a782b95b9b2f218b:timer', 'i:1795356365;', 1795356365),
('28e82f0d2bd092081c1003cfd202ef40', 'i:1;', 1795358996),
('28e82f0d2bd092081c1003cfd202ef40:timer', 'i:1795358996;', 1795358996),
('28fc6d4a872022c687bc375e08a95ebd', 'i:1;', 1795359439),
('28fc6d4a872022c687bc375e08a95ebd:timer', 'i:1795359439;', 1795359439),
('290a901f0b8cbde3c810e54ef6e4becb', 'i:1;', 1795356365),
('290a901f0b8cbde3c810e54ef6e4becb:timer', 'i:1795356365;', 1795356365),
('29d44de8241c887c69a8e56e2fbc0c71', 'i:1;', 1795360336),
('29d44de8241c887c69a8e56e2fbc0c71:timer', 'i:1795360336;', 1795360336),
('2a3842f5279b83d5de4248e4650e75d5', 'i:1;', 1795355290),
('2a3842f5279b83d5de4248e4650e75d5:timer', 'i:1795355290;', 1795355290),
('2ae9efffb718a2b3015591300be0aab1', 'i:1;', 1795360227),
('2ae9efffb718a2b3015591300be0aab1:timer', 'i:1795360227;', 1795360227),
('2b566ef5d1fcfc7505a7e7eeeed0abdb', 'i:1;', 1795360336),
('2b566ef5d1fcfc7505a7e7eeeed0abdb:timer', 'i:1795360336;', 1795360336),
('2bb511aadbaacb0b3ba8e06ec7e6ac37', 'i:1;', 1795358997),
('2bb511aadbaacb0b3ba8e06ec7e6ac37:timer', 'i:1795358997;', 1795358997),
('2d686d5e4d0e129d583a678c34a1e498', 'i:1;', 1795358499),
('2d686d5e4d0e129d583a678c34a1e498:timer', 'i:1795358499;', 1795358499),
('2d6ef8c50130ecab005e07025c398577', 'i:1;', 1795360048),
('2d6ef8c50130ecab005e07025c398577:timer', 'i:1795360048;', 1795360048),
('2dfbc8f3db53459b30747574329aed91', 'i:1;', 1795356294),
('2dfbc8f3db53459b30747574329aed91:timer', 'i:1795356294;', 1795356294),
('2e158394eb3d25344e91de02c8fd40e5', 'i:1;', 1795359059),
('2e158394eb3d25344e91de02c8fd40e5:timer', 'i:1795359059;', 1795359059),
('2eb1d6bbf9df80a5643c55ffe7d53cfe', 'i:1;', 1795355288),
('2eb1d6bbf9df80a5643c55ffe7d53cfe:timer', 'i:1795355288;', 1795355288),
('2f412efb536cc463dd3cd233d7de99e4', 'i:1;', 1795358499),
('2f412efb536cc463dd3cd233d7de99e4:timer', 'i:1795358499;', 1795358499),
('2f4801131dfbb24b5c1bcb25912ae91d', 'i:1;', 1795360049),
('2f4801131dfbb24b5c1bcb25912ae91d:timer', 'i:1795360049;', 1795360049),
('2f6c628729af2755aad776a7c1ca90f2', 'i:1;', 1795356350),
('2f6c628729af2755aad776a7c1ca90f2:timer', 'i:1795356350;', 1795356350),
('2f9eaa70fe019f410f37fe81a634e5f5', 'i:1;', 1795358332),
('2f9eaa70fe019f410f37fe81a634e5f5:timer', 'i:1795358332;', 1795358332),
('302e2e291f4b92983064c419ee946062', 'i:1;', 1795356878),
('302e2e291f4b92983064c419ee946062:timer', 'i:1795356878;', 1795356878),
('303323c4eb2d5c341e24e0529de6bb1b', 'i:1;', 1795356457),
('303323c4eb2d5c341e24e0529de6bb1b:timer', 'i:1795356457;', 1795356457),
('304627dbe9d2136bb1fb76f9260bbbbb', 'i:1;', 1795360229),
('304627dbe9d2136bb1fb76f9260bbbbb:timer', 'i:1795360229;', 1795360229),
('306e2abdccc9d84b17441aedf078f381', 'i:1;', 1795358332),
('306e2abdccc9d84b17441aedf078f381:timer', 'i:1795358332;', 1795358332),
('3086d98c0dc055bd13e761301b618101', 'i:1;', 1795356293),
('3086d98c0dc055bd13e761301b618101:timer', 'i:1795356293;', 1795356293),
('30c10bf37d10cfbaf5e69bfbf6f1b368', 'i:1;', 1795360228),
('30c10bf37d10cfbaf5e69bfbf6f1b368:timer', 'i:1795360228;', 1795360228),
('30e941bf5f26c78e0efd19a91de71b15', 'i:1;', 1795360663),
('30e941bf5f26c78e0efd19a91de71b15:timer', 'i:1795360663;', 1795360663),
('317dd126cf9e515d0848405c27ab208b', 'i:1;', 1795359059),
('317dd126cf9e515d0848405c27ab208b:timer', 'i:1795359059;', 1795359059),
('318e66f4528839d4cf80d3eb3985b8ad', 'i:1;', 1795357806),
('318e66f4528839d4cf80d3eb3985b8ad:timer', 'i:1795357806;', 1795357806),
('3234e8cd0311da4922f7dc3d02277ffb', 'i:1;', 1795357806),
('3234e8cd0311da4922f7dc3d02277ffb:timer', 'i:1795357806;', 1795357806),
('332a653de62123c467747515e2a04def', 'i:1;', 1795358332),
('332a653de62123c467747515e2a04def:timer', 'i:1795358332;', 1795358332),
('3335bbb966935b88b1c0e990513b1701', 'i:1;', 1795359871),
('3335bbb966935b88b1c0e990513b1701:timer', 'i:1795359871;', 1795359871),
('334785f70f58d9f5646246eb1d0cb2c1', 'i:1;', 1795355574),
('334785f70f58d9f5646246eb1d0cb2c1:timer', 'i:1795355574;', 1795355574),
('33906d4e6ef10b03eaab55978fb37c12', 'i:1;', 1795359870),
('33906d4e6ef10b03eaab55978fb37c12:timer', 'i:1795359870;', 1795359870),
('33bb21c2f0aad01da518e5bfe10ac255', 'i:1;', 1795358334),
('33bb21c2f0aad01da518e5bfe10ac255:timer', 'i:1795358334;', 1795358334),
('33e0947401c2db7d734f3dd17f1d9bfa', 'i:1;', 1795359872),
('33e0947401c2db7d734f3dd17f1d9bfa:timer', 'i:1795359872;', 1795359872),
('33fe5135e71df58f26ae5ae3fc5ad644', 'i:1;', 1795358858),
('33fe5135e71df58f26ae5ae3fc5ad644:timer', 'i:1795358858;', 1795358858),
('346d547ffe4c8e0f46389ef1fcad6421', 'i:1;', 1795360227),
('346d547ffe4c8e0f46389ef1fcad6421:timer', 'i:1795360227;', 1795360227),
('347d26c96c5473dd1f13dc7d729e2334', 'i:1;', 1795358333),
('347d26c96c5473dd1f13dc7d729e2334:timer', 'i:1795358333;', 1795358333),
('34e0418d3abe66495bc843103a2938ac', 'i:1;', 1795357805),
('34e0418d3abe66495bc843103a2938ac:timer', 'i:1795357805;', 1795357805),
('35404fa6af3eaf12d9e2d906d5c34772', 'i:1;', 1795357454),
('35404fa6af3eaf12d9e2d906d5c34772:timer', 'i:1795357454;', 1795357454),
('35d10391308a103f3a4f2650e4d0052e', 'i:1;', 1795356349),
('35d10391308a103f3a4f2650e4d0052e:timer', 'i:1795356349;', 1795356349),
('36369a792475c9d50c21aaa8c610d528', 'i:1;', 1795359440),
('36369a792475c9d50c21aaa8c610d528:timer', 'i:1795359440;', 1795359440),
('36669b6860c285610d9563d0434916fe', 'i:1;', 1795357805),
('36669b6860c285610d9563d0434916fe:timer', 'i:1795357805;', 1795357805),
('3682bd381aceecf9c168f834fd93e5b6', 'i:1;', 1795358857),
('3682bd381aceecf9c168f834fd93e5b6:timer', 'i:1795358857;', 1795358857),
('369da70796d1158a4c228c988eb28f88', 'i:1;', 1795355393),
('369da70796d1158a4c228c988eb28f88:timer', 'i:1795355393;', 1795355393),
('37345dd5c60e502a350a3c979b6d88c8', 'i:1;', 1795359799),
('37345dd5c60e502a350a3c979b6d88c8:timer', 'i:1795359799;', 1795359799),
('377437fa78aaa6f146be5001ff23a4ef', 'i:1;', 1795357203),
('377437fa78aaa6f146be5001ff23a4ef:timer', 'i:1795357203;', 1795357203),
('377ad9a572f5484210d744db22c4fbef', 'i:1;', 1795357201),
('377ad9a572f5484210d744db22c4fbef:timer', 'i:1795357201;', 1795357201),
('392e6590f5dd6b432b4d1bf4e0eda4e2', 'i:1;', 1795358333),
('392e6590f5dd6b432b4d1bf4e0eda4e2:timer', 'i:1795358333;', 1795358333),
('392fc7f193a04bb9f541297933c1958b', 'i:1;', 1795358333),
('392fc7f193a04bb9f541297933c1958b:timer', 'i:1795358333;', 1795358333),
('3943af2039562807ef42c9cd2c2d0516', 'i:1;', 1795358499),
('3943af2039562807ef42c9cd2c2d0516:timer', 'i:1795358499;', 1795358499),
('3ba85b603b1122e63119b1c7cbd22e84', 'i:1;', 1795355393),
('3ba85b603b1122e63119b1c7cbd22e84:timer', 'i:1795355393;', 1795355393),
('3bd40362b067fcf1135267aa04fe3212', 'i:1;', 1795358997),
('3bd40362b067fcf1135267aa04fe3212:timer', 'i:1795358997;', 1795358997),
('3c0b84573742398d8fde61c9b9fc8374', 'i:1;', 1795359871),
('3c0b84573742398d8fde61c9b9fc8374:timer', 'i:1795359871;', 1795359871),
('3cc1cbc2ae8ec025d421942bbd92c561', 'i:1;', 1795357454),
('3cc1cbc2ae8ec025d421942bbd92c561:timer', 'i:1795357454;', 1795357454),
('3ccb862c865e4b512309d773127ed9ee', 'i:1;', 1795360336),
('3ccb862c865e4b512309d773127ed9ee:timer', 'i:1795360336;', 1795360336),
('3dec269719e0bcf44347dc62cac0bbe6', 'i:1;', 1795360049),
('3dec269719e0bcf44347dc62cac0bbe6:timer', 'i:1795360049;', 1795360049),
('3ebf3949dabceb7fa857bf9fad7896fd', 'i:1;', 1795358030),
('3ebf3949dabceb7fa857bf9fad7896fd:timer', 'i:1795358030;', 1795358030),
('3f2ee1da4808f84c7f30552e85d91292', 'i:1;', 1795355394),
('3f2ee1da4808f84c7f30552e85d91292:timer', 'i:1795355394;', 1795355394),
('409df42396efc579410a589af4845ffd', 'i:1;', 1795356350),
('409df42396efc579410a589af4845ffd:timer', 'i:1795356350;', 1795356350),
('40a06db3e396ed92bd8c24a65275c8c9', 'i:1;', 1795360227),
('40a06db3e396ed92bd8c24a65275c8c9:timer', 'i:1795360227;', 1795360227),
('413c493d42f8d224934948014702d7ff', 'i:1;', 1795359439),
('413c493d42f8d224934948014702d7ff:timer', 'i:1795359439;', 1795359439),
('419aeaf7bfc424ddd0395e8044548daa', 'i:1;', 1795359059),
('419aeaf7bfc424ddd0395e8044548daa:timer', 'i:1795359059;', 1795359059),
('41a87df7794df60d733bef76fd977c18', 'i:1;', 1795360336),
('41a87df7794df60d733bef76fd977c18:timer', 'i:1795360336;', 1795360336),
('41f97cd74ae9f0bf9f07b4257425407d', 'i:1;', 1795358333),
('41f97cd74ae9f0bf9f07b4257425407d:timer', 'i:1795358333;', 1795358333),
('42144360e4f50295b35e8cd0da7c055a', 'i:1;', 1795356879),
('42144360e4f50295b35e8cd0da7c055a:timer', 'i:1795356879;', 1795356879),
('424291798dc2a0ebb4f2365b8a99531a', 'i:1;', 1795356293),
('424291798dc2a0ebb4f2365b8a99531a:timer', 'i:1795356293;', 1795356293),
('429cfe733ce834ef3a5282a7873dec30', 'i:1;', 1795360229),
('429cfe733ce834ef3a5282a7873dec30:timer', 'i:1795360229;', 1795360229),
('42a076b90f69b18ca99dfc368e3ce59d', 'i:1;', 1795360049),
('42a076b90f69b18ca99dfc368e3ce59d:timer', 'i:1795360049;', 1795360049),
('42d1bc073442b6413a61fec0042f6da1', 'i:1;', 1795358030),
('42d1bc073442b6413a61fec0042f6da1:timer', 'i:1795358030;', 1795358030),
('4331197256173aba0c4d4afa2573695b', 'i:1;', 1795360663),
('4331197256173aba0c4d4afa2573695b:timer', 'i:1795360663;', 1795360663),
('4368a116cf269bce3cb861b24b14c4bd', 'i:1;', 1795356005),
('4368a116cf269bce3cb861b24b14c4bd:timer', 'i:1795356005;', 1795356005),
('436d12e2b57e129327fc1b9d86b7c146', 'i:1;', 1795357202),
('436d12e2b57e129327fc1b9d86b7c146:timer', 'i:1795357202;', 1795357202),
('437871b3ff8a31dd5c788cf981644a62', 'i:1;', 1795356785),
('437871b3ff8a31dd5c788cf981644a62:timer', 'i:1795356785;', 1795356785),
('43d6b54b18cfb790cfdd267d268c0b33', 'i:1;', 1795357454),
('43d6b54b18cfb790cfdd267d268c0b33:timer', 'i:1795357454;', 1795357454),
('44376c199b0b76b410ac828502c4c1d9', 'i:1;', 1795358997),
('44376c199b0b76b410ac828502c4c1d9:timer', 'i:1795358997;', 1795358997),
('446023dde383beadc1868a4f095950cc', 'i:1;', 1795356878),
('446023dde383beadc1868a4f095950cc:timer', 'i:1795356878;', 1795356878),
('44781f942a38e5d44a99c09bb96b3300', 'i:1;', 1795355289),
('44781f942a38e5d44a99c09bb96b3300:timer', 'i:1795355289;', 1795355289),
('44ef816989286da8b8fe77ba65d6de6f', 'i:1;', 1795360227),
('44ef816989286da8b8fe77ba65d6de6f:timer', 'i:1795360227;', 1795360227),
('45189de871d730ddb248b5884436364f', 'i:1;', 1795360336),
('45189de871d730ddb248b5884436364f:timer', 'i:1795360336;', 1795360336),
('453313fc7ac03cbd40f6684e295feda9', 'i:1;', 1795355291),
('453313fc7ac03cbd40f6684e295feda9:timer', 'i:1795355291;', 1795355291),
('45d552320c48a52f93340df03743d845', 'i:1;', 1795359059),
('45d552320c48a52f93340df03743d845:timer', 'i:1795359059;', 1795359059),
('460d3e20aecbbc29e047ab3787c61db4', 'i:1;', 1795358500),
('460d3e20aecbbc29e047ab3787c61db4:timer', 'i:1795358500;', 1795358500),
('4616374cb4c649140f980e0ac783d4cf', 'i:1;', 1795358997),
('4616374cb4c649140f980e0ac783d4cf:timer', 'i:1795358997;', 1795358997),
('46b2b9ef825da3127e799355798a349a', 'i:1;', 1795356005),
('46b2b9ef825da3127e799355798a349a:timer', 'i:1795356005;', 1795356005),
('46d8521afda6a748c7de1df235b81017', 'i:1;', 1795357806),
('46d8521afda6a748c7de1df235b81017:timer', 'i:1795357806;', 1795357806),
('47122d671cc794ed82a9fc0b93ca0ec3', 'i:1;', 1795356293),
('47122d671cc794ed82a9fc0b93ca0ec3:timer', 'i:1795356293;', 1795356293),
('47303498ce5d6d818725500d78b5f604', 'i:1;', 1795356294),
('47303498ce5d6d818725500d78b5f604:timer', 'i:1795356294;', 1795356294),
('48fbddf74ebdf560e0002ef3dffea629', 'i:1;', 1795356457),
('48fbddf74ebdf560e0002ef3dffea629:timer', 'i:1795356457;', 1795356457),
('49072c2c03c2aa842410eecf5bb89791', 'i:1;', 1795360664),
('49072c2c03c2aa842410eecf5bb89791:timer', 'i:1795360664;', 1795360664),
('495779ee8a609f461714f01ffc32858c', 'i:1;', 1795358997),
('495779ee8a609f461714f01ffc32858c:timer', 'i:1795358997;', 1795358997),
('49801a87b11513d38a40f26c89350abf', 'i:1;', 1795355290),
('49801a87b11513d38a40f26c89350abf:timer', 'i:1795355290;', 1795355290),
('49bb09324cdeed769c938322b9609d7a', 'i:1;', 1795357454),
('49bb09324cdeed769c938322b9609d7a:timer', 'i:1795357454;', 1795357454),
('4a273738922c8b2383ffb6edb418c4e7', 'i:1;', 1795355392),
('4a273738922c8b2383ffb6edb418c4e7:timer', 'i:1795355392;', 1795355392),
('4aa08606bad2aa119b06df7f6f695619', 'i:1;', 1795360048),
('4aa08606bad2aa119b06df7f6f695619:timer', 'i:1795360048;', 1795360048),
('4aa8e81995326241afb7e554ca40dcfd', 'i:1;', 1795358998),
('4aa8e81995326241afb7e554ca40dcfd:timer', 'i:1795358998;', 1795358998),
('4b1fd1a13b7bc8b740d7420294e26cb9', 'i:1;', 1795358499),
('4b1fd1a13b7bc8b740d7420294e26cb9:timer', 'i:1795358499;', 1795358499),
('4b8bc81d7eaa098c63ff5d6cbff12254', 'i:1;', 1795360228),
('4b8bc81d7eaa098c63ff5d6cbff12254:timer', 'i:1795360228;', 1795360228),
('4d69bdb552910f43fc947ddf4d351f37', 'i:1;', 1795356879),
('4d69bdb552910f43fc947ddf4d351f37:timer', 'i:1795356879;', 1795356879),
('4dacc6d2c19f72957d69ceeb565f471f', 'i:1;', 1795358998),
('4dacc6d2c19f72957d69ceeb565f471f:timer', 'i:1795358998;', 1795358998),
('4e266c9e196440409e97db2a0672146e', 'i:1;', 1795356349),
('4e266c9e196440409e97db2a0672146e:timer', 'i:1795356349;', 1795356349),
('4e8b7587e8123233da4eab3e85ec9822', 'i:1;', 1795358332),
('4e8b7587e8123233da4eab3e85ec9822:timer', 'i:1795358332;', 1795358332),
('4f17523eacf6d7ac0f642b1e1eed090f', 'i:1;', 1795356259),
('4f17523eacf6d7ac0f642b1e1eed090f:timer', 'i:1795356259;', 1795356259),
('4f8b30fb18a041fca201ad973b76c35b', 'i:1;', 1795356457),
('4f8b30fb18a041fca201ad973b76c35b:timer', 'i:1795356457;', 1795356457),
('4fb000a0c98cf39e1ddc41cd84283c21', 'i:1;', 1795360664),
('4fb000a0c98cf39e1ddc41cd84283c21:timer', 'i:1795360664;', 1795360664),
('50216ea05d17942b39b3bd084a764ca1', 'i:1;', 1795355291),
('50216ea05d17942b39b3bd084a764ca1:timer', 'i:1795355291;', 1795355291),
('503a3a9eadeb3855aebe0f4a551eef4b', 'i:1;', 1795359871),
('503a3a9eadeb3855aebe0f4a551eef4b:timer', 'i:1795359871;', 1795359871),
('5075f3b81ced40144a9d765297a37ea0', 'i:1;', 1795356304),
('5075f3b81ced40144a9d765297a37ea0:timer', 'i:1795356304;', 1795356304),
('507d5e6bb9a9bf6b658d47659bca38d7', 'i:1;', 1795355395),
('507d5e6bb9a9bf6b658d47659bca38d7:timer', 'i:1795355395;', 1795355395),
('50dbcebacc1b9c28212c3df3d99888c5', 'i:1;', 1795359872),
('50dbcebacc1b9c28212c3df3d99888c5:timer', 'i:1795359872;', 1795359872),
('50ff888a8ec61be741ade12bc8470493', 'i:1;', 1795355572),
('50ff888a8ec61be741ade12bc8470493:timer', 'i:1795355572;', 1795355572),
('510a4d0f25039e89c1ac817cafe3cb2a', 'i:1;', 1795358334),
('510a4d0f25039e89c1ac817cafe3cb2a:timer', 'i:1795358334;', 1795358334),
('516db97c8c2830d7f5b30d669c2945c7', 'i:1;', 1795359058),
('516db97c8c2830d7f5b30d669c2945c7:timer', 'i:1795359058;', 1795359058),
('51cc9cda68c931defd6538852210d7d7', 'i:1;', 1795359439),
('51cc9cda68c931defd6538852210d7d7:timer', 'i:1795359439;', 1795359439),
('520801e9f2bd99cd9ec04f7c84a2f6c1', 'i:1;', 1795356786),
('520801e9f2bd99cd9ec04f7c84a2f6c1:timer', 'i:1795356786;', 1795356786),
('523a74c1199fb8d4b3a65538975caa83', 'i:1;', 1795358858),
('523a74c1199fb8d4b3a65538975caa83:timer', 'i:1795358858;', 1795358858),
('5277b693db746232e2e0ebfced141c5a', 'i:1;', 1795356304),
('5277b693db746232e2e0ebfced141c5a:timer', 'i:1795356304;', 1795356304),
('5282f1b916bad404b245f826a229e3f2', 'i:1;', 1795358029),
('5282f1b916bad404b245f826a229e3f2:timer', 'i:1795358029;', 1795358029),
('5297b7b9a3b527acba4ccde4b5ccc519', 'i:1;', 1795358498),
('5297b7b9a3b527acba4ccde4b5ccc519:timer', 'i:1795358498;', 1795358498),
('539275d33783c8b323101e51aafc570a', 'i:1;', 1795360228),
('539275d33783c8b323101e51aafc570a:timer', 'i:1795360228;', 1795360228),
('539b5ca0753b27c82abb3c8ffe46b5c5', 'i:1;', 1795360047),
('539b5ca0753b27c82abb3c8ffe46b5c5:timer', 'i:1795360047;', 1795360047),
('53da42e135ddb1f42c179b557c4bb0c1', 'i:1;', 1795358499),
('53da42e135ddb1f42c179b557c4bb0c1:timer', 'i:1795358499;', 1795358499),
('54bab17d2a67b4e80ee44c6228261a2d', 'i:1;', 1795358857),
('54bab17d2a67b4e80ee44c6228261a2d:timer', 'i:1795358857;', 1795358857),
('551cdb6604aa224cdbfb7bd4b637814e', 'i:1;', 1795356785),
('551cdb6604aa224cdbfb7bd4b637814e:timer', 'i:1795356785;', 1795356785),
('55d74cb3075c1e03461748b35f06c8fd', 'i:1;', 1795357201),
('55d74cb3075c1e03461748b35f06c8fd:timer', 'i:1795357201;', 1795357201),
('564f98d0e9d37fe7ff52f3512f607f54', 'i:1;', 1795358333),
('564f98d0e9d37fe7ff52f3512f607f54:timer', 'i:1795358333;', 1795358333),
('565f31596c37d4452bbbb49c1832c6e4', 'i:1;', 1795358857),
('565f31596c37d4452bbbb49c1832c6e4:timer', 'i:1795358857;', 1795358857),
('56eca0b87bb202d6ac199d3afc46cc52', 'i:1;', 1795355394),
('56eca0b87bb202d6ac199d3afc46cc52:timer', 'i:1795355394;', 1795355394),
('56fd716846b32bd4cf82c8f92165c5ed', 'i:1;', 1795360663),
('56fd716846b32bd4cf82c8f92165c5ed:timer', 'i:1795360663;', 1795360663),
('57b03a7c9c4f0b0f5c6521062896452c', 'i:1;', 1795360229),
('57b03a7c9c4f0b0f5c6521062896452c:timer', 'i:1795360229;', 1795360229),
('5860c6e9b76a2a5617fa21152d748312', 'i:1;', 1795359870),
('5860c6e9b76a2a5617fa21152d748312:timer', 'i:1795359870;', 1795359870),
('5880dcb1d3517f40aefa1fbae51131ed', 'i:1;', 1795357202),
('5880dcb1d3517f40aefa1fbae51131ed:timer', 'i:1795357202;', 1795357202),
('589fdc5f6a9a0cb41b318e7f5277e983', 'i:1;', 1795355289),
('589fdc5f6a9a0cb41b318e7f5277e983:timer', 'i:1795355289;', 1795355289),
('58be539f0b4ad9dd96b012e528a41d1b', 'i:1;', 1795359871),
('58be539f0b4ad9dd96b012e528a41d1b:timer', 'i:1795359871;', 1795359871),
('58d0dc4f82cdafd0eb95dce9ff53d4b1', 'i:1;', 1795356364),
('58d0dc4f82cdafd0eb95dce9ff53d4b1:timer', 'i:1795356364;', 1795356364),
('58f0923659834b718b3bfda5f60823dc', 'i:1;', 1795356294),
('58f0923659834b718b3bfda5f60823dc:timer', 'i:1795356294;', 1795356294),
('5943d129b164d9048721bcf545bcd0f9', 'i:1;', 1795356717),
('5943d129b164d9048721bcf545bcd0f9:timer', 'i:1795356717;', 1795356717),
('598c70d818009433866f5910e0850f73', 'i:1;', 1795356878),
('598c70d818009433866f5910e0850f73:timer', 'i:1795356878;', 1795356878),
('599b9f478902816585dac00b3e00e714', 'i:1;', 1795356787),
('599b9f478902816585dac00b3e00e714:timer', 'i:1795356787;', 1795356787),
('5a843bc2d68412821b0af55f119d9b86', 'i:1;', 1795356717),
('5a843bc2d68412821b0af55f119d9b86:timer', 'i:1795356717;', 1795356717),
('5aa788a4a94785c1ba3e3bdc05bd428a', 'i:1;', 1795356877),
('5aa788a4a94785c1ba3e3bdc05bd428a:timer', 'i:1795356877;', 1795356877),
('5aed062c10127b6292a98cfa74b8ab5f', 'i:1;', 1795356785),
('5aed062c10127b6292a98cfa74b8ab5f:timer', 'i:1795356785;', 1795356785),
('5b6aaf19cf20b036dbe8103929fa8007', 'i:1;', 1795360047),
('5b6aaf19cf20b036dbe8103929fa8007:timer', 'i:1795360047;', 1795360047),
('5b734d3b6a2509a52884bae3795c3163', 'i:1;', 1795358498),
('5b734d3b6a2509a52884bae3795c3163:timer', 'i:1795358498;', 1795358498),
('5c0a88217341168be5f484e52e3e8f3a', 'i:1;', 1795355291),
('5c0a88217341168be5f484e52e3e8f3a:timer', 'i:1795355291;', 1795355291),
('5c0cb08927d055fe8bf409a4039916e8', 'i:1;', 1795356364),
('5c0cb08927d055fe8bf409a4039916e8:timer', 'i:1795356364;', 1795356364),
('5c54abe8c8aed72e63bc087870eb6e3b', 'i:1;', 1795358860),
('5c54abe8c8aed72e63bc087870eb6e3b:timer', 'i:1795358860;', 1795358860),
('5c62b8d3917629a3ba04e17a0d8536af', 'i:1;', 1795358499),
('5c62b8d3917629a3ba04e17a0d8536af:timer', 'i:1795358499;', 1795358499),
('5ca0d583c9ec25ce9cc940e7c73906a7', 'i:1;', 1795359440),
('5ca0d583c9ec25ce9cc940e7c73906a7:timer', 'i:1795359440;', 1795359440),
('5cb2df8f428883a27a78c4ee615062a7', 'i:1;', 1795356364),
('5cb2df8f428883a27a78c4ee615062a7:timer', 'i:1795356364;', 1795356364),
('5d36974462a4199fc9b1aa53798a46cc', 'i:1;', 1795358031),
('5d36974462a4199fc9b1aa53798a46cc:timer', 'i:1795358031;', 1795358031),
('5d839440c59be3f75aadf68543408274', 'i:1;', 1795355289),
('5d839440c59be3f75aadf68543408274:timer', 'i:1795355289;', 1795355289),
('5df38131d162bb080a60f45ccd278550', 'i:1;', 1795356457),
('5df38131d162bb080a60f45ccd278550:timer', 'i:1795356457;', 1795356457),
('5e05c9ea9dc8eb61a87bd72bef333eab', 'i:1;', 1795360047),
('5e05c9ea9dc8eb61a87bd72bef333eab:timer', 'i:1795360047;', 1795360047),
('5e3de100dce77b18c6adad748aec2e5e', 'i:1;', 1795360227),
('5e3de100dce77b18c6adad748aec2e5e:timer', 'i:1795360227;', 1795360227),
('5e9492d4a7e726285ae6f26d41348549', 'i:1;', 1795360227),
('5e9492d4a7e726285ae6f26d41348549:timer', 'i:1795360227;', 1795360227),
('5eba4a98346c095169dc6d42b28a6412', 'i:1;', 1795356717),
('5eba4a98346c095169dc6d42b28a6412:timer', 'i:1795356717;', 1795356717),
('5f1e9d96850548dd865ae5983bc7501e', 'i:1;', 1795360336),
('5f1e9d96850548dd865ae5983bc7501e:timer', 'i:1795360336;', 1795360336),
('5f23d8d35368a06a2361528bfe1cbb7a', 'i:1;', 1795355573),
('5f23d8d35368a06a2361528bfe1cbb7a:timer', 'i:1795355573;', 1795355573),
('5f7e01dab3f604e17bc87dfd06b00554', 'i:1;', 1795360663),
('5f7e01dab3f604e17bc87dfd06b00554:timer', 'i:1795360663;', 1795360663),
('60acd11335d8de20e048b17bd086f18b', 'i:1;', 1795356785),
('60acd11335d8de20e048b17bd086f18b:timer', 'i:1795356785;', 1795356785),
('60ae338411c23f1dfece257c399c7eb3', 'i:1;', 1795355291),
('60ae338411c23f1dfece257c399c7eb3:timer', 'i:1795355291;', 1795355291),
('60fa48f7bda1f896dd93e48efd1d8085', 'i:1;', 1795360049),
('60fa48f7bda1f896dd93e48efd1d8085:timer', 'i:1795360049;', 1795360049),
('616fdf484737be03417d5b3c184cdebf', 'i:1;', 1795356461),
('616fdf484737be03417d5b3c184cdebf:timer', 'i:1795356461;', 1795356461),
('6176795e61a68c234329d5d6463da7be', 'i:1;', 1795355393),
('6176795e61a68c234329d5d6463da7be:timer', 'i:1795355393;', 1795355393),
('619da23b141f1ff226dd6df5390bf9ff', 'i:1;', 1795355393),
('619da23b141f1ff226dd6df5390bf9ff:timer', 'i:1795355393;', 1795355393),
('61c6ae91f4a7bf78f3802b1f23e6e841', 'i:1;', 1795359060),
('61c6ae91f4a7bf78f3802b1f23e6e841:timer', 'i:1795359060;', 1795359060),
('61f946b6f7fe5bee42e7769752c82864', 'i:1;', 1795356877),
('61f946b6f7fe5bee42e7769752c82864:timer', 'i:1795356877;', 1795356877),
('63323da8a0a47713c873869a3e0b141d', 'i:1;', 1795360336),
('63323da8a0a47713c873869a3e0b141d:timer', 'i:1795360336;', 1795360336),
('6352f0d967c518f4d9c50f24df914610', 'i:1;', 1795360663),
('6352f0d967c518f4d9c50f24df914610:timer', 'i:1795360663;', 1795360663),
('63db3699ef82f324ea1dffc208688e9a', 'i:1;', 1795357805),
('63db3699ef82f324ea1dffc208688e9a:timer', 'i:1795357805;', 1795357805),
('6423d86027267e47c05a8bd3e1e9be03', 'i:1;', 1795359059),
('6423d86027267e47c05a8bd3e1e9be03:timer', 'i:1795359059;', 1795359059),
('6424b685d38a24c9aafa42d1da495ff2', 'i:1;', 1795357807),
('6424b685d38a24c9aafa42d1da495ff2:timer', 'i:1795357807;', 1795357807),
('6436d02f7e9e0db5c344342c71050289', 'i:1;', 1795355573),
('6436d02f7e9e0db5c344342c71050289:timer', 'i:1795355573;', 1795355573),
('64b945aebb6ea57ff466fb26d0ccd286', 'i:1;', 1795359060),
('64b945aebb6ea57ff466fb26d0ccd286:timer', 'i:1795359060;', 1795359060),
('65d114e5ab06f2e830ceac034976e6ab', 'i:1;', 1795360337),
('65d114e5ab06f2e830ceac034976e6ab:timer', 'i:1795360337;', 1795360337),
('65ebccb68e3531eda00fbb65660c5e3b', 'i:1;', 1795359871),
('65ebccb68e3531eda00fbb65660c5e3b:timer', 'i:1795359871;', 1795359871),
('6651d74b4b4c921e268642bab41372bb', 'i:1;', 1795359441),
('6651d74b4b4c921e268642bab41372bb:timer', 'i:1795359441;', 1795359441),
('665db490f2452e3647bfe4054e1643f0', 'i:1;', 1795356294),
('665db490f2452e3647bfe4054e1643f0:timer', 'i:1795356294;', 1795356294),
('68776236db49908ef04986d7ceb88fd3', 'i:1;', 1795359440),
('68776236db49908ef04986d7ceb88fd3:timer', 'i:1795359440;', 1795359440),
('687f8322099e6f1bb8ad81199493ec90', 'i:1;', 1795359058),
('687f8322099e6f1bb8ad81199493ec90:timer', 'i:1795359058;', 1795359058),
('68e77c974673b9a3a75d6eb91ad996b3', 'i:1;', 1795360646),
('68e77c974673b9a3a75d6eb91ad996b3:timer', 'i:1795360646;', 1795360646),
('696718fdde25dcc7fc2dc6303aa937fc', 'i:1;', 1795356461),
('696718fdde25dcc7fc2dc6303aa937fc:timer', 'i:1795356461;', 1795356461),
('696bf5d8a428aa7b83fe8c543002c592', 'i:1;', 1795358333),
('696bf5d8a428aa7b83fe8c543002c592:timer', 'i:1795358333;', 1795358333),
('69bf24a2b47db935a649068e7e340e8d', 'i:1;', 1795355572),
('69bf24a2b47db935a649068e7e340e8d:timer', 'i:1795355572;', 1795355572),
('6a2db989eb101d6d01af53dcf8ff99aa', 'i:1;', 1795357202),
('6a2db989eb101d6d01af53dcf8ff99aa:timer', 'i:1795357202;', 1795357202),
('6b6fa1df5e689ba2418e0d1d82b455ae', 'i:1;', 1795358499),
('6b6fa1df5e689ba2418e0d1d82b455ae:timer', 'i:1795358499;', 1795358499),
('6b8575ccc1e6cdb4f3c3a0a8b3247fe3', 'i:1;', 1795359871),
('6b8575ccc1e6cdb4f3c3a0a8b3247fe3:timer', 'i:1795359871;', 1795359871),
('6b9086c18970745a24e64e92d08cd77d', 'i:1;', 1795358031),
('6b9086c18970745a24e64e92d08cd77d:timer', 'i:1795358031;', 1795358031),
('6b9f51cee9f712907447ed90e5279fb4', 'i:1;', 1795356304),
('6b9f51cee9f712907447ed90e5279fb4:timer', 'i:1795356304;', 1795356304),
('6c6d3c89257a3056b1d9c0741c224d43', 'i:1;', 1795359439),
('6c6d3c89257a3056b1d9c0741c224d43:timer', 'i:1795359439;', 1795359439),
('6cf50fe67a9c710a4b1694a85ab26d94', 'i:1;', 1795358997),
('6cf50fe67a9c710a4b1694a85ab26d94:timer', 'i:1795358997;', 1795358997),
('6cfc81ae5e2a07bbddf41e51c9e6d967', 'i:1;', 1795356294),
('6cfc81ae5e2a07bbddf41e51c9e6d967:timer', 'i:1795356294;', 1795356294),
('6d360f54c2c4a8c1b89b83763d53da16', 'i:1;', 1795358499),
('6d360f54c2c4a8c1b89b83763d53da16:timer', 'i:1795358499;', 1795358499),
('6d72f70ca5557c5c0c0a294b320fb810', 'i:1;', 1795356365),
('6d72f70ca5557c5c0c0a294b320fb810:timer', 'i:1795356365;', 1795356365),
('6e0cd5d44aa6ca2e78e518bd392abf36', 'i:1;', 1795360049),
('6e0cd5d44aa6ca2e78e518bd392abf36:timer', 'i:1795360049;', 1795360049),
('6e292b123c896e2e7cb9983be8de2ba7', 'i:1;', 1795357454),
('6e292b123c896e2e7cb9983be8de2ba7:timer', 'i:1795357454;', 1795357454),
('6e70fb1ba30dfd61a97b13745c5f9d43', 'i:1;', 1795355291),
('6e70fb1ba30dfd61a97b13745c5f9d43:timer', 'i:1795355291;', 1795355291),
('6e788c83c7f1da329fece0f575f655d5', 'i:1;', 1795356879),
('6e788c83c7f1da329fece0f575f655d5:timer', 'i:1795356879;', 1795356879),
('6ec7e58790ebfa98293c409c4c769ddc', 'i:1;', 1795355572),
('6ec7e58790ebfa98293c409c4c769ddc:timer', 'i:1795355572;', 1795355572),
('6edc1a998cec7d30dbc00d89c2527ffd', 'i:1;', 1795360663),
('6edc1a998cec7d30dbc00d89c2527ffd:timer', 'i:1795360663;', 1795360663),
('6ef62624b243c1449fc7fbb02139393a', 'i:1;', 1795360047),
('6ef62624b243c1449fc7fbb02139393a:timer', 'i:1795360047;', 1795360047),
('6ffb68489802754114db924a22ebecfc', 'i:1;', 1795356877),
('6ffb68489802754114db924a22ebecfc:timer', 'i:1795356877;', 1795356877),
('70b54f3fadb79504a2ce0aac7804ce08', 'i:1;', 1795360339),
('70b54f3fadb79504a2ce0aac7804ce08:timer', 'i:1795360339;', 1795360339),
('70d9c21893910452e89692e6d4c0d090', 'i:1;', 1795358997),
('70d9c21893910452e89692e6d4c0d090:timer', 'i:1795358997;', 1795358997),
('70ef636b118f4134bb8b7981cd75cb12', 'i:1;', 1795360228),
('70ef636b118f4134bb8b7981cd75cb12:timer', 'i:1795360228;', 1795360228),
('7114b73760cdc5b03bfd3e84916a6868', 'i:1;', 1795355291),
('7114b73760cdc5b03bfd3e84916a6868:timer', 'i:1795355291;', 1795355291),
('7129ad57323474f348875cd7ca19f69f', 'i:1;', 1795360336),
('7129ad57323474f348875cd7ca19f69f:timer', 'i:1795360336;', 1795360336),
('71889865b532ddc8b5a2d30d0c73cac9', 'i:1;', 1795357201),
('71889865b532ddc8b5a2d30d0c73cac9:timer', 'i:1795357201;', 1795357201),
('71cb867ef3d47ffffd8342eb3d9c0235', 'i:1;', 1795359059),
('71cb867ef3d47ffffd8342eb3d9c0235:timer', 'i:1795359059;', 1795359059),
('72b2700c8a18543416b4c01674062ead', 'i:1;', 1795359440),
('72b2700c8a18543416b4c01674062ead:timer', 'i:1795359440;', 1795359440),
('72ddf5bb6eaca235f6ac2d52789091df', 'i:1;', 1795360338),
('72ddf5bb6eaca235f6ac2d52789091df:timer', 'i:1795360338;', 1795360338),
('72e395303b313eba06a46c22c11c48fe', 'i:1;', 1795357805),
('72e395303b313eba06a46c22c11c48fe:timer', 'i:1795357805;', 1795357805),
('730040e6d2f6fff03a6d226193b8a244', 'i:1;', 1795356365),
('730040e6d2f6fff03a6d226193b8a244:timer', 'i:1795356365;', 1795356365),
('732320c19cedbb9b66d8d7f941154265', 'i:1;', 1795356785),
('732320c19cedbb9b66d8d7f941154265:timer', 'i:1795356785;', 1795356785),
('742e39cabec4fe0bc2c5701e1657a3e3', 'i:1;', 1795359440),
('742e39cabec4fe0bc2c5701e1657a3e3:timer', 'i:1795359440;', 1795359440),
('74a5645446d25191337b7a0d73c97921', 'i:1;', 1795356717),
('74a5645446d25191337b7a0d73c97921:timer', 'i:1795356717;', 1795356717),
('74dab4b229e006193832d41432db40ff', 'i:1;', 1795356785),
('74dab4b229e006193832d41432db40ff:timer', 'i:1795356785;', 1795356785),
('762064f2260f29fc164ea20ac55e8236', 'i:1;', 1795359439),
('762064f2260f29fc164ea20ac55e8236:timer', 'i:1795359439;', 1795359439),
('763bda69b60f0d86b35d684359fc08d4', 'i:1;', 1795359440),
('763bda69b60f0d86b35d684359fc08d4:timer', 'i:1795359440;', 1795359440),
('763f136bb079bf316bf71594de4aba04', 'i:1;', 1795356457),
('763f136bb079bf316bf71594de4aba04:timer', 'i:1795356457;', 1795356457),
('7643dadb0f4a8578ded2d2f275333810', 'i:1;', 1795358030),
('7643dadb0f4a8578ded2d2f275333810:timer', 'i:1795358030;', 1795358030),
('7686a6547bb23f0cae31c6c9a308f686', 'i:1;', 1795360228),
('7686a6547bb23f0cae31c6c9a308f686:timer', 'i:1795360228;', 1795360228),
('76dbb279f079385c6fb61a4bbb2c120e', 'i:1;', 1795360337),
('76dbb279f079385c6fb61a4bbb2c120e:timer', 'i:1795360337;', 1795360337),
('775b2b61f1e55ed38e68af0438735284', 'i:1;', 1795360048),
('775b2b61f1e55ed38e68af0438735284:timer', 'i:1795360048;', 1795360048),
('7775c288a5d46663567388ab1ceeb4e4', 'i:1;', 1795355573),
('7775c288a5d46663567388ab1ceeb4e4:timer', 'i:1795355573;', 1795355573),
('7783f1afd82a18722e8be21244090bda', 'i:1;', 1795358500),
('7783f1afd82a18722e8be21244090bda:timer', 'i:1795358500;', 1795358500),
('77e3a518c1ad7521c9ebc5270d694b24', 'i:1;', 1795359439),
('77e3a518c1ad7521c9ebc5270d694b24:timer', 'i:1795359439;', 1795359439),
('7876b98b391940b78cdf5a6b54315bf6', 'i:1;', 1795356878),
('7876b98b391940b78cdf5a6b54315bf6:timer', 'i:1795356878;', 1795356878),
('78807491f73afeb445b159b1b9acf1df', 'i:1;', 1795356785),
('78807491f73afeb445b159b1b9acf1df:timer', 'i:1795356785;', 1795356785),
('7904afecdabbcefebe9575f44309ffff', 'i:1;', 1795356785),
('7904afecdabbcefebe9575f44309ffff:timer', 'i:1795356785;', 1795356785),
('793725c1272001c7894e796a22d3db27', 'i:1;', 1795355823),
('793725c1272001c7894e796a22d3db27:timer', 'i:1795355823;', 1795355823),
('7945fe8f2cbcd388a3ea2574f4de7ed3', 'i:1;', 1795357805),
('7945fe8f2cbcd388a3ea2574f4de7ed3:timer', 'i:1795357805;', 1795357805),
('7971a88dc5e6a21188d065c101f3b400', 'i:1;', 1795360047),
('7971a88dc5e6a21188d065c101f3b400:timer', 'i:1795360047;', 1795360047),
('79f49d5e1906660cd69393a872f65764', 'i:1;', 1795358501),
('79f49d5e1906660cd69393a872f65764:timer', 'i:1795358501;', 1795358501),
('7a2ccd2998ca9c2762086ff6bd98395d', 'i:1;', 1795355573),
('7a2ccd2998ca9c2762086ff6bd98395d:timer', 'i:1795355573;', 1795355573),
('7a5f737bba680e78987e7f7c46330e8d', 'i:1;', 1795356293),
('7a5f737bba680e78987e7f7c46330e8d:timer', 'i:1795356293;', 1795356293),
('7aca768e17bac05933606bd9c47e17e3', 'i:1;', 1795358996),
('7aca768e17bac05933606bd9c47e17e3:timer', 'i:1795358996;', 1795358996),
('7aea02744da167f52b05fb526bde23d2', 'i:1;', 1795358859),
('7aea02744da167f52b05fb526bde23d2:timer', 'i:1795358859;', 1795358859),
('7aec50f2ab895a0c53fc9487828617d4', 'i:1;', 1795359872),
('7aec50f2ab895a0c53fc9487828617d4:timer', 'i:1795359872;', 1795359872),
('7afaa193f54f0e2ecd0fd2cf86ff38de', 'i:1;', 1795360662),
('7afaa193f54f0e2ecd0fd2cf86ff38de:timer', 'i:1795360662;', 1795360662),
('7b3e82d56d4b4f1bb830fbe297ece70c', 'i:1;', 1795356786),
('7b3e82d56d4b4f1bb830fbe297ece70c:timer', 'i:1795356786;', 1795356786),
('7bffd7fcd9cd54b41ab8dcbc836ec07b', 'i:1;', 1795358997),
('7bffd7fcd9cd54b41ab8dcbc836ec07b:timer', 'i:1795358997;', 1795358997),
('7c58b06850ac2c6065959c061a4894bb', 'i:1;', 1795355572),
('7c58b06850ac2c6065959c061a4894bb:timer', 'i:1795355572;', 1795355572),
('7c81bac57703207bd09806cf87abbaa0', 'i:1;', 1795360337),
('7c81bac57703207bd09806cf87abbaa0:timer', 'i:1795360337;', 1795360337),
('7db0966fd944c79b7bccdb601090ff21', 'i:1;', 1795360227),
('7db0966fd944c79b7bccdb601090ff21:timer', 'i:1795360227;', 1795360227),
('7dcb384f4d2067eb08be23229ea84df1', 'i:1;', 1795359870),
('7dcb384f4d2067eb08be23229ea84df1:timer', 'i:1795359870;', 1795359870),
('7e62c1ba76184bb3791181dd372519f4', 'i:1;', 1795357805),
('7e62c1ba76184bb3791181dd372519f4:timer', 'i:1795357805;', 1795357805),
('7e79b3335309eaddb3c415e74039a1a0', 'i:1;', 1795358499),
('7e79b3335309eaddb3c415e74039a1a0:timer', 'i:1795358499;', 1795358499),
('7f917d5497136f42f5399dc6d6945597', 'i:1;', 1795355289),
('7f917d5497136f42f5399dc6d6945597:timer', 'i:1795355289;', 1795355289),
('7fc0ed6c608efc1cc69487a8286f7a4f', 'i:1;', 1795355394),
('7fc0ed6c608efc1cc69487a8286f7a4f:timer', 'i:1795355394;', 1795355394),
('7fc1db68e3cf3eee657e097f3921c2d1', 'i:1;', 1795358332),
('7fc1db68e3cf3eee657e097f3921c2d1:timer', 'i:1795358332;', 1795358332),
('7fc6b2605c57dfaf56faab7320f90d29', 'i:1;', 1795357805),
('7fc6b2605c57dfaf56faab7320f90d29:timer', 'i:1795357805;', 1795357805),
('7fd64a77c56c48da1c637399214724d5', 'i:1;', 1795359059),
('7fd64a77c56c48da1c637399214724d5:timer', 'i:1795359059;', 1795359059),
('801bd1ba3e3749ab55c9a34d05fa539f', 'i:1;', 1795358030),
('801bd1ba3e3749ab55c9a34d05fa539f:timer', 'i:1795358030;', 1795358030),
('80551743c1d02ca165fafb2954023179', 'i:1;', 1795360337),
('80551743c1d02ca165fafb2954023179:timer', 'i:1795360337;', 1795360337),
('8067f4a12019845794fea51e73217544', 'i:1;', 1795358029),
('8067f4a12019845794fea51e73217544:timer', 'i:1795358029;', 1795358029),
('80adc5aef70bf988e67df2cdff4ce908', 'i:1;', 1795358499),
('80adc5aef70bf988e67df2cdff4ce908:timer', 'i:1795358499;', 1795358499),
('80e96138243afc3eb7de3ddd864e8097', 'i:1;', 1795359059),
('80e96138243afc3eb7de3ddd864e8097:timer', 'i:1795359059;', 1795359059),
('813266a551ac3b52b210f0851f42f184', 'i:30;', 1795360646),
('813266a551ac3b52b210f0851f42f184:timer', 'i:1795360646;', 1795360646),
('817612599a8d653bc995816b89e1b8a0', 'i:1;', 1795358857),
('817612599a8d653bc995816b89e1b8a0:timer', 'i:1795358857;', 1795358857),
('81d480353d6307ae89052763600f6235', 'i:1;', 1795355572),
('81d480353d6307ae89052763600f6235:timer', 'i:1795355572;', 1795355572),
('820d8d7c720e89eb50fbbc689e6f567f', 'i:1;', 1795359870),
('820d8d7c720e89eb50fbbc689e6f567f:timer', 'i:1795359870;', 1795359870),
('8244f068041cbc55551e07c86327fedb', 'i:1;', 1795358858),
('8244f068041cbc55551e07c86327fedb:timer', 'i:1795358858;', 1795358858),
('831b93269ae9d1475facb45423363449', 'i:1;', 1795358029),
('831b93269ae9d1475facb45423363449:timer', 'i:1795358029;', 1795358029),
('8410ad2234abbb5d13bc41c517f9a94d', 'i:1;', 1795358858),
('8410ad2234abbb5d13bc41c517f9a94d:timer', 'i:1795358858;', 1795358858),
('8416eb6d1951f6bcd920f2febf15fb47', 'i:1;', 1795357807),
('8416eb6d1951f6bcd920f2febf15fb47:timer', 'i:1795357807;', 1795357807),
('84a8231f8bc2b47b239a4dc5821a8dca', 'i:1;', 1795359061),
('84a8231f8bc2b47b239a4dc5821a8dca:timer', 'i:1795359061;', 1795359061),
('84ae7bd893e58fbc6a0bbf7e772edf0b', 'i:1;', 1795356293),
('84ae7bd893e58fbc6a0bbf7e772edf0b:timer', 'i:1795356293;', 1795356293),
('84e004a960be3a3d1902f28a58d0c578', 'i:1;', 1795360047),
('84e004a960be3a3d1902f28a58d0c578:timer', 'i:1795360047;', 1795360047),
('851d3593e07c8cbdd8a097264ebc0407', 'i:1;', 1795358332),
('851d3593e07c8cbdd8a097264ebc0407:timer', 'i:1795358332;', 1795358332),
('859260c50e644922c9f7350ee2056229', 'i:1;', 1795356457),
('859260c50e644922c9f7350ee2056229:timer', 'i:1795356457;', 1795356457),
('85f830788e2e2e3fb956b55b49abc957', 'i:1;', 1795358499),
('85f830788e2e2e3fb956b55b49abc957:timer', 'i:1795358499;', 1795358499),
('863e75294ef643ce81461bddc93e04db', 'i:1;', 1795359439),
('863e75294ef643ce81461bddc93e04db:timer', 'i:1795359439;', 1795359439),
('86609ba49e5b96dc348d3c66d5ac1d9d', 'i:1;', 1795357454),
('86609ba49e5b96dc348d3c66d5ac1d9d:timer', 'i:1795357454;', 1795357454),
('86c20b50025b9ea56e68783d096f19e4', 'i:1;', 1795359440),
('86c20b50025b9ea56e68783d096f19e4:timer', 'i:1795359440;', 1795359440),
('87018bd73e15effe4374636a94ad9181', 'i:1;', 1795358498),
('87018bd73e15effe4374636a94ad9181:timer', 'i:1795358498;', 1795358498),
('8705524169f6de313a0ebe2705b53bd2', 'i:1;', 1795359872),
('8705524169f6de313a0ebe2705b53bd2:timer', 'i:1795359872;', 1795359872),
('873939ec7774747cc948e7220a407b5f', 'i:1;', 1795356005),
('873939ec7774747cc948e7220a407b5f:timer', 'i:1795356005;', 1795356005),
('8761cd7090497b5709377f435a53cda1', 'i:1;', 1795360664),
('8761cd7090497b5709377f435a53cda1:timer', 'i:1795360664;', 1795360664);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('8791b03f02bbf844b5c7e69946314c84', 'i:1;', 1795356785),
('8791b03f02bbf844b5c7e69946314c84:timer', 'i:1795356785;', 1795356785),
('87e0a3f067714c3a32b5374db187a24c', 'i:1;', 1795356877),
('87e0a3f067714c3a32b5374db187a24c:timer', 'i:1795356877;', 1795356877),
('88e134714e761941fd17f450249d6e59', 'i:1;', 1795360662),
('88e134714e761941fd17f450249d6e59:timer', 'i:1795360662;', 1795360662),
('890feed67c5b94c06514ba59d274ae82', 'i:1;', 1795358998),
('890feed67c5b94c06514ba59d274ae82:timer', 'i:1795358998;', 1795358998),
('8af49e3639064c0548243f6e7f4f1dfd', 'i:1;', 1795357805),
('8af49e3639064c0548243f6e7f4f1dfd:timer', 'i:1795357805;', 1795357805),
('8b2107bf55a6e0b614cad48e25577749', 'i:1;', 1795358998),
('8b2107bf55a6e0b614cad48e25577749:timer', 'i:1795358998;', 1795358998),
('8c315c8f1e8d246ffcf117e20b1c1305', 'i:1;', 1795360047),
('8c315c8f1e8d246ffcf117e20b1c1305:timer', 'i:1795360047;', 1795360047),
('8cdd4e7f9900ab1e2921f699bf37296e', 'i:1;', 1795357805),
('8cdd4e7f9900ab1e2921f699bf37296e:timer', 'i:1795357805;', 1795357805),
('8da3b3fa40064a35f96511924933563d', 'i:1;', 1795355393),
('8da3b3fa40064a35f96511924933563d:timer', 'i:1795355393;', 1795355393),
('8e10b25b7985a972b41a9d702a32fe67', 'i:1;', 1795359441),
('8e10b25b7985a972b41a9d702a32fe67:timer', 'i:1795359441;', 1795359441),
('8e984767571d6071bffeb1a34821bb2b', 'i:1;', 1795360665),
('8e984767571d6071bffeb1a34821bb2b:timer', 'i:1795360665;', 1795360665),
('8ed7bf6fa98b31f07f375a64f8c36732', 'i:1;', 1795357454),
('8ed7bf6fa98b31f07f375a64f8c36732:timer', 'i:1795357454;', 1795357454),
('8f23ade38426b15e94f61ef124fb0042', 'i:1;', 1795356877),
('8f23ade38426b15e94f61ef124fb0042:timer', 'i:1795356877;', 1795356877),
('8fde5af49e1e4079c1cd4fb6baa99dff', 'i:1;', 1795358333),
('8fde5af49e1e4079c1cd4fb6baa99dff:timer', 'i:1795358333;', 1795358333),
('90a5dbd0145905a586ce8f12a5607c3b', 'i:1;', 1795355572),
('90a5dbd0145905a586ce8f12a5607c3b:timer', 'i:1795355572;', 1795355572),
('90ad712326d554bc3bff0a01423d3c60', 'i:1;', 1795358499),
('90ad712326d554bc3bff0a01423d3c60:timer', 'i:1795358499;', 1795358499),
('9147ee0e1825014e0fdca61c30b8f1a2', 'i:1;', 1795358029),
('9147ee0e1825014e0fdca61c30b8f1a2:timer', 'i:1795358029;', 1795358029),
('91b2ccb8c11ae4713f53b88dab8c033e', 'i:1;', 1795358499),
('91b2ccb8c11ae4713f53b88dab8c033e:timer', 'i:1795358499;', 1795358499),
('923398ec8357bef7880d6395e666c8f7', 'i:1;', 1795358858),
('923398ec8357bef7880d6395e666c8f7:timer', 'i:1795358858;', 1795358858),
('926714d4cd14c9ea35f89ba6719cb2da', 'i:1;', 1795357202),
('926714d4cd14c9ea35f89ba6719cb2da:timer', 'i:1795357202;', 1795357202),
('928425e7dc7d46dc56a1a7df436e2ce2', 'i:1;', 1795357454),
('928425e7dc7d46dc56a1a7df436e2ce2:timer', 'i:1795357454;', 1795357454),
('92bb0abb638abe1ecd612a7bf9d0b67b', 'i:1;', 1795358857),
('92bb0abb638abe1ecd612a7bf9d0b67b:timer', 'i:1795358857;', 1795358857),
('9336f2ced014ddc78489acd2645dab4b', 'i:1;', 1795358334),
('9336f2ced014ddc78489acd2645dab4b:timer', 'i:1795358334;', 1795358334),
('950fa488a07893c36a81a608f654e567', 'i:1;', 1795359871),
('950fa488a07893c36a81a608f654e567:timer', 'i:1795359871;', 1795359871),
('9529fc1e72f10dba931a7c375ac55857', 'i:1;', 1795356456),
('9529fc1e72f10dba931a7c375ac55857:timer', 'i:1795356456;', 1795356456),
('9750f750052655dc64b9633166f673b6', 'i:1;', 1795359059),
('9750f750052655dc64b9633166f673b6:timer', 'i:1795359059;', 1795359059),
('97dd2c233504573df1a274aa4b106a6c', 'i:1;', 1795358332),
('97dd2c233504573df1a274aa4b106a6c:timer', 'i:1795358332;', 1795358332),
('97dd577efb58e834721438c7cd85ebaf', 'i:1;', 1795358332),
('97dd577efb58e834721438c7cd85ebaf:timer', 'i:1795358332;', 1795358332),
('9864362242d9cd529efb43d55ee262e0', 'i:1;', 1795359060),
('9864362242d9cd529efb43d55ee262e0:timer', 'i:1795359060;', 1795359060),
('997a34ad390aa7a1e06d56198584cc10', 'i:1;', 1795359871),
('997a34ad390aa7a1e06d56198584cc10:timer', 'i:1795359871;', 1795359871),
('9a0233b1a038f67294a46ac7bcf86ef6', 'i:1;', 1795357454),
('9a0233b1a038f67294a46ac7bcf86ef6:timer', 'i:1795357454;', 1795357454),
('9a4710afa7ed0b77d2af9901be8eeef2', 'i:1;', 1795359871),
('9a4710afa7ed0b77d2af9901be8eeef2:timer', 'i:1795359871;', 1795359871),
('9a52e08a7ec233a6d465a31f786b47cb', 'i:1;', 1795359059),
('9a52e08a7ec233a6d465a31f786b47cb:timer', 'i:1795359059;', 1795359059),
('9a65bf11325dd4d8fa3607931a218be8', 'i:1;', 1795356785),
('9a65bf11325dd4d8fa3607931a218be8:timer', 'i:1795356785;', 1795356785),
('9add3cdc532c9833f6f9fdc638da1b80', 'i:1;', 1795357806),
('9add3cdc532c9833f6f9fdc638da1b80:timer', 'i:1795357806;', 1795357806),
('9b3b5604774b95fcf0492571e50f5e6c', 'i:1;', 1795356786),
('9b3b5604774b95fcf0492571e50f5e6c:timer', 'i:1795356786;', 1795356786),
('9b6ca9a07dd3b4786eefb90ff561e00a', 'i:1;', 1795356350),
('9b6ca9a07dd3b4786eefb90ff561e00a:timer', 'i:1795356350;', 1795356350),
('9bb2acb7e1647c569cdacdd4c2289d0d', 'i:1;', 1795360047),
('9bb2acb7e1647c569cdacdd4c2289d0d:timer', 'i:1795360047;', 1795360047),
('9c23d7b3efedb96a3f7b0ac0c309662b', 'i:1;', 1795358334),
('9c23d7b3efedb96a3f7b0ac0c309662b:timer', 'i:1795358334;', 1795358334),
('9c54ae17c58d1c9e649e30805cc445e6', 'i:1;', 1795357202),
('9c54ae17c58d1c9e649e30805cc445e6:timer', 'i:1795357202;', 1795357202),
('9d2047ae5a26310b6dff5249a78aa30b', 'i:1;', 1795356005),
('9d2047ae5a26310b6dff5249a78aa30b:timer', 'i:1795356005;', 1795356005),
('9d9d81701cca948db3465672456a81c3', 'i:1;', 1795356878),
('9d9d81701cca948db3465672456a81c3:timer', 'i:1795356878;', 1795356878),
('9dbb3683a2d285bf4803f0295f8685ed', 'i:1;', 1795360337),
('9dbb3683a2d285bf4803f0295f8685ed:timer', 'i:1795360337;', 1795360337),
('9dce1abdb8c6d5fb985b340e62979f76', 'i:1;', 1795358999),
('9dce1abdb8c6d5fb985b340e62979f76:timer', 'i:1795358999;', 1795358999),
('9e60b6b11218a805a209f0e52b48f617', 'i:1;', 1795355393),
('9e60b6b11218a805a209f0e52b48f617:timer', 'i:1795355393;', 1795355393),
('9f964a55e38125cc2469e5cc4be78d0d', 'i:1;', 1795360337),
('9f964a55e38125cc2469e5cc4be78d0d:timer', 'i:1795360337;', 1795360337),
('a2a9a79990df9f0826d9570f150802a8', 'i:1;', 1795357454),
('a2a9a79990df9f0826d9570f150802a8:timer', 'i:1795357454;', 1795357454),
('a2dcf157ee0d0ce25412c05d7fc10fdf', 'i:1;', 1795355290),
('a2dcf157ee0d0ce25412c05d7fc10fdf:timer', 'i:1795355290;', 1795355290),
('a2e0f48c68e499982431591932995d75', 'i:1;', 1795356717),
('a2e0f48c68e499982431591932995d75:timer', 'i:1795356717;', 1795356717),
('a36c9f93bb86ea30e87552d3f329d760', 'i:1;', 1795355395),
('a36c9f93bb86ea30e87552d3f329d760:timer', 'i:1795355395;', 1795355395),
('a3bb277f649624a863063de9c6d9a332', 'i:1;', 1795356879),
('a3bb277f649624a863063de9c6d9a332:timer', 'i:1795356879;', 1795356879),
('a4159df2abeae9770e6fdbf1c6f9905f', 'i:1;', 1795359441),
('a4159df2abeae9770e6fdbf1c6f9905f:timer', 'i:1795359441;', 1795359441),
('a4235ce1b32a4c6ad3ed55b6d0bb1637', 'i:1;', 1795357456),
('a4235ce1b32a4c6ad3ed55b6d0bb1637:timer', 'i:1795357456;', 1795357456),
('a494e7c0dc8d942b5048a56210be1556', 'i:1;', 1795360664),
('a494e7c0dc8d942b5048a56210be1556:timer', 'i:1795360664;', 1795360664),
('a54094891a5ef8ca7fe9b9b42341b1d1', 'i:1;', 1795358858),
('a54094891a5ef8ca7fe9b9b42341b1d1:timer', 'i:1795358858;', 1795358858),
('a56aae4b68231147d1acbb4730a63b2a', 'i:1;', 1795356786),
('a56aae4b68231147d1acbb4730a63b2a:timer', 'i:1795356786;', 1795356786),
('a5c599833d0e35d955734420ad9cda9f', 'i:1;', 1795358499),
('a5c599833d0e35d955734420ad9cda9f:timer', 'i:1795358499;', 1795358499),
('a5d209243e8d366f86ab06988294e74d', 'i:1;', 1795358998),
('a5d209243e8d366f86ab06988294e74d:timer', 'i:1795358998;', 1795358998),
('a75f3f172bfb296f2e10cbfc6dfc1883', 'i:160;', 1795360646),
('a75f3f172bfb296f2e10cbfc6dfc1883:timer', 'i:1795360646;', 1795360646),
('a772dea121bcc99ab7c35e552a6917fc', 'i:1;', 1795355573),
('a772dea121bcc99ab7c35e552a6917fc:timer', 'i:1795355573;', 1795355573),
('a78ead094a23a8de4320d136b36c4082', 'i:1;', 1795356304),
('a78ead094a23a8de4320d136b36c4082:timer', 'i:1795356304;', 1795356304),
('a79c579d3bbaaba67c3ceb856aa8cbc5', 'i:1;', 1795358859),
('a79c579d3bbaaba67c3ceb856aa8cbc5:timer', 'i:1795358859;', 1795358859),
('a7c1608b32dfa1affb0b54c2f264a616', 'i:1;', 1795358858),
('a7c1608b32dfa1affb0b54c2f264a616:timer', 'i:1795358858;', 1795358858),
('a87d80629e7c9ec0ef12ae42a8ddb648', 'i:1;', 1795357806),
('a87d80629e7c9ec0ef12ae42a8ddb648:timer', 'i:1795357806;', 1795357806),
('a8b5fe2d78fc9a22c08156f062eb57d9', 'i:1;', 1795355573),
('a8b5fe2d78fc9a22c08156f062eb57d9:timer', 'i:1795355573;', 1795355573),
('a91cf8328482540ec38b179650fc9c62', 'i:1;', 1795359870),
('a91cf8328482540ec38b179650fc9c62:timer', 'i:1795359870;', 1795359870),
('a94359d9ac5c30b448dd725c3ba13766', 'i:1;', 1795358030),
('a94359d9ac5c30b448dd725c3ba13766:timer', 'i:1795358030;', 1795358030),
('a985b82483d825b96417d70d16abaeda', 'i:1;', 1795358998),
('a985b82483d825b96417d70d16abaeda:timer', 'i:1795358998;', 1795358998),
('a9a05e679db4590c79fd4d3f01f1f538', 'i:1;', 1795360047),
('a9a05e679db4590c79fd4d3f01f1f538:timer', 'i:1795360047;', 1795360047),
('aa3fa365b11efdb8e1f2e329b24409bd', 'i:1;', 1795360047),
('aa3fa365b11efdb8e1f2e329b24409bd:timer', 'i:1795360047;', 1795360047),
('aafad92977646f8514ecd624ed060b1a', 'i:1;', 1795358997),
('aafad92977646f8514ecd624ed060b1a:timer', 'i:1795358997;', 1795358997),
('ab3cdc7300ad5b1964753409f8a3ff22', 'i:1;', 1795356785),
('ab3cdc7300ad5b1964753409f8a3ff22:timer', 'i:1795356785;', 1795356785),
('abffbefbd33cc5a0b4b950d46b281f68', 'i:1;', 1795358030),
('abffbefbd33cc5a0b4b950d46b281f68:timer', 'i:1795358030;', 1795358030),
('ac41514fb75bee7d03702c865d9f7a0a', 'i:1;', 1795356785),
('ac41514fb75bee7d03702c865d9f7a0a:timer', 'i:1795356785;', 1795356785),
('ac7e8f1957694920afaf6dc4af9e129d', 'i:1;', 1795359439),
('ac7e8f1957694920afaf6dc4af9e129d:timer', 'i:1795359439;', 1795359439),
('accb2f0658b7cf85818a4f544d9393a1', 'i:1;', 1795357456),
('accb2f0658b7cf85818a4f544d9393a1:timer', 'i:1795357456;', 1795357456),
('ad28acefc91c53a216fcf8a1b961ebe4', 'i:1;', 1795358030),
('ad28acefc91c53a216fcf8a1b961ebe4:timer', 'i:1795358030;', 1795358030),
('ad44345d55bc6b8adbf6cee63869e2c9', 'i:1;', 1795357201),
('ad44345d55bc6b8adbf6cee63869e2c9:timer', 'i:1795357201;', 1795357201),
('ad6135fcb626005cfb9492f00e162f30', 'i:1;', 1795356259),
('ad6135fcb626005cfb9492f00e162f30:timer', 'i:1795356259;', 1795356259),
('ad802498d526427ca9526ee0fdb82cce', 'i:1;', 1795358860),
('ad802498d526427ca9526ee0fdb82cce:timer', 'i:1795358860;', 1795358860),
('adb7366f14fe3333b60b41239deefe4c', 'i:1;', 1795355393),
('adb7366f14fe3333b60b41239deefe4c:timer', 'i:1795355393;', 1795355393),
('ae70cdb995ec426235f8c651b2be6fc7', 'i:1;', 1795356786),
('ae70cdb995ec426235f8c651b2be6fc7:timer', 'i:1795356786;', 1795356786),
('b002c84e745b59f113ab4130bc6db122', 'i:1;', 1795358999),
('b002c84e745b59f113ab4130bc6db122:timer', 'i:1795358999;', 1795358999),
('b012a45585f47b0dd2a001e57af21468', 'i:1;', 1795355292),
('b012a45585f47b0dd2a001e57af21468:timer', 'i:1795355291;', 1795355291),
('b0a63db74efed1dd7a7fb17345716bdd', 'i:1;', 1795357454),
('b0a63db74efed1dd7a7fb17345716bdd:timer', 'i:1795357454;', 1795357454),
('b18c39af376f80bceb5d85dfeb3313c8', 'i:1;', 1795359872),
('b18c39af376f80bceb5d85dfeb3313c8:timer', 'i:1795359872;', 1795359872),
('b1ce4e3b9624503ffefb92863e7ae8cf', 'i:1;', 1795355572),
('b1ce4e3b9624503ffefb92863e7ae8cf:timer', 'i:1795355572;', 1795355572),
('b1f25d4cefdceed2122148c86db18462', 'i:1;', 1795360228),
('b1f25d4cefdceed2122148c86db18462:timer', 'i:1795360228;', 1795360228),
('b23ba31dc7c57226ee11b88a9e25841b', 'i:1;', 1795359870),
('b23ba31dc7c57226ee11b88a9e25841b:timer', 'i:1795359870;', 1795359870),
('b25a6d047f8acfa53590a3da3f1f6ce4', 'i:1;', 1795356717),
('b25a6d047f8acfa53590a3da3f1f6ce4:timer', 'i:1795356717;', 1795356717),
('b2cee6ce72a5975cb3027c91a81d8bcd', 'i:1;', 1795359060),
('b2cee6ce72a5975cb3027c91a81d8bcd:timer', 'i:1795359060;', 1795359060),
('b38caf77bbfd61f25df48b175bcef0b2', 'i:1;', 1795357805),
('b38caf77bbfd61f25df48b175bcef0b2:timer', 'i:1795357805;', 1795357805),
('b42f9d522e48bdb145b373fe413cb21d', 'i:1;', 1795357454),
('b42f9d522e48bdb145b373fe413cb21d:timer', 'i:1795357454;', 1795357454),
('b44680c3f3288a5bf45d5f618219c7b6', 'i:1;', 1795360336),
('b44680c3f3288a5bf45d5f618219c7b6:timer', 'i:1795360336;', 1795360336),
('b473d3ea3857f83c6045a100827ae1ef', 'i:1;', 1795355289),
('b473d3ea3857f83c6045a100827ae1ef:timer', 'i:1795355289;', 1795355289),
('b48714921ec14d8c9baee1d8d680496f', 'i:1;', 1795358859),
('b48714921ec14d8c9baee1d8d680496f:timer', 'i:1795358859;', 1795358859),
('b5c0c29bf3c04cc985fa7dbff3cd23f0', 'i:1;', 1795359058),
('b5c0c29bf3c04cc985fa7dbff3cd23f0:timer', 'i:1795359058;', 1795359058),
('b6142f4a49c6206c99117efa8fa5e6bf', 'i:1;', 1795358332),
('b6142f4a49c6206c99117efa8fa5e6bf:timer', 'i:1795358332;', 1795358332),
('b63809b0a4ef682b603be7691385b959', 'i:1;', 1795356457),
('b63809b0a4ef682b603be7691385b959:timer', 'i:1795356457;', 1795356457),
('b69bdd659b87a4eb05c890bb6cdc77f0', 'i:1;', 1795357201),
('b69bdd659b87a4eb05c890bb6cdc77f0:timer', 'i:1795357201;', 1795357201),
('b6ca2fd41046a41ce640b52bf4321b5b', 'i:1;', 1795355573),
('b6ca2fd41046a41ce640b52bf4321b5b:timer', 'i:1795355573;', 1795355573),
('b6d602cee438f4380a782ffe5caccb5a', 'i:1;', 1795358029),
('b6d602cee438f4380a782ffe5caccb5a:timer', 'i:1795358029;', 1795358029),
('b75ec8e9670be116b7b25332d034233e', 'i:1;', 1795357806),
('b75ec8e9670be116b7b25332d034233e:timer', 'i:1795357806;', 1795357806),
('b89f715367629337d90f5ddf17c744cd', 'i:1;', 1795360664),
('b89f715367629337d90f5ddf17c744cd:timer', 'i:1795360664;', 1795360664),
('ba5e5f4fc78d5b1031da99e6a573c3d4', 'i:1;', 1795355392),
('ba5e5f4fc78d5b1031da99e6a573c3d4:timer', 'i:1795355392;', 1795355392),
('ba6fd0992f04db6aaa22e224a23195c8', 'i:1;', 1795355393),
('ba6fd0992f04db6aaa22e224a23195c8:timer', 'i:1795355393;', 1795355393),
('bb12d1e94151eb00477fa6658fd4675d', 'i:1;', 1795358333),
('bb12d1e94151eb00477fa6658fd4675d:timer', 'i:1795358333;', 1795358333),
('bb2ef772666e584e568eb7c09db65ed8', 'i:1;', 1795359872),
('bb2ef772666e584e568eb7c09db65ed8:timer', 'i:1795359872;', 1795359872),
('bb590cdd02a569d6d12764bdb9a50f2c', 'i:1;', 1795356785),
('bb590cdd02a569d6d12764bdb9a50f2c:timer', 'i:1795356785;', 1795356785),
('bbf547aa23f92839e792f1dba2629f9b', 'i:1;', 1795358997),
('bbf547aa23f92839e792f1dba2629f9b:timer', 'i:1795358997;', 1795358997),
('bc8d0be61d6b6abe5d74ab4c4a303c67', 'i:1;', 1795355394),
('bc8d0be61d6b6abe5d74ab4c4a303c67:timer', 'i:1795355394;', 1795355394),
('bc9dbfc6bda1bc170534c394f0aae112', 'i:1;', 1795360663),
('bc9dbfc6bda1bc170534c394f0aae112:timer', 'i:1795360663;', 1795360663),
('bcb9b0054307d5610876f13b3ae6facd', 'i:1;', 1795355289),
('bcb9b0054307d5610876f13b3ae6facd:timer', 'i:1795355289;', 1795355289),
('bcf45f9c5a6ae0638ef2a4ae91a4811e', 'i:1;', 1795359871),
('bcf45f9c5a6ae0638ef2a4ae91a4811e:timer', 'i:1795359871;', 1795359871),
('bcf6a3bc03ae620a98addf8f644150b2', 'i:1;', 1795358997),
('bcf6a3bc03ae620a98addf8f644150b2:timer', 'i:1795358997;', 1795358997),
('bd82102522ea9bf57b20b9653ae5b3e9', 'i:1;', 1795355291),
('bd82102522ea9bf57b20b9653ae5b3e9:timer', 'i:1795355291;', 1795355291),
('bdceabf03c5e7543708129af519a84fc', 'i:1;', 1795356293),
('bdceabf03c5e7543708129af519a84fc:timer', 'i:1795356293;', 1795356293),
('be45702d1b2d34f92a481ccfdea7a2e9', 'i:1;', 1795356717),
('be45702d1b2d34f92a481ccfdea7a2e9:timer', 'i:1795356717;', 1795356717),
('be4c403cecebe089741ffda1cb693031', 'i:1;', 1795356293),
('be4c403cecebe089741ffda1cb693031:timer', 'i:1795356293;', 1795356293),
('be50616d88d0adfbd30e6269e826b09c', 'i:1;', 1795358029),
('be50616d88d0adfbd30e6269e826b09c:timer', 'i:1795358029;', 1795358029),
('be8cc3d5cd051755cf71dae44be231f0', 'i:1;', 1795360048),
('be8cc3d5cd051755cf71dae44be231f0:timer', 'i:1795360048;', 1795360048),
('bf833e2645d92938fbd0ddc5cc1429b7', 'i:1;', 1795357203),
('bf833e2645d92938fbd0ddc5cc1429b7:timer', 'i:1795357203;', 1795357203),
('bfd256b9aa7035601a7e75042b31ae06', 'i:1;', 1795356303),
('bfd256b9aa7035601a7e75042b31ae06:timer', 'i:1795356303;', 1795356303),
('c0056d56c0df690101d600e486a06b46', 'i:1;', 1795356878),
('c0056d56c0df690101d600e486a06b46:timer', 'i:1795356878;', 1795356878),
('c088d5d4b09b8ced106280142e53e160', 'i:1;', 1795356717),
('c088d5d4b09b8ced106280142e53e160:timer', 'i:1795356717;', 1795356717),
('c1ca85707e34fcd5d93168236ee89844', 'i:1;', 1795357202),
('c1ca85707e34fcd5d93168236ee89844:timer', 'i:1795357202;', 1795357202),
('c335a20e75c9b10c67fd54fa485dd27d', 'i:1;', 1795355291),
('c335a20e75c9b10c67fd54fa485dd27d:timer', 'i:1795355291;', 1795355291),
('c33be54c67c4ae3fe8c4f56f1999d4c6', 'i:1;', 1795357202),
('c33be54c67c4ae3fe8c4f56f1999d4c6:timer', 'i:1795357202;', 1795357202),
('c33f3ffee54825e5996beed79f640ebf', 'i:1;', 1795358029),
('c33f3ffee54825e5996beed79f640ebf:timer', 'i:1795358029;', 1795358029),
('c35ec68f4f8ab46c2285edf91421e8d6', 'i:1;', 1795359872),
('c35ec68f4f8ab46c2285edf91421e8d6:timer', 'i:1795359872;', 1795359872),
('c3c79bf95a093d1eadecebb08c35ebc3', 'i:1;', 1795358500),
('c3c79bf95a093d1eadecebb08c35ebc3:timer', 'i:1795358500;', 1795358500),
('c41eae19c34f6b60b8c7982a055b42af', 'i:1;', 1795356456),
('c41eae19c34f6b60b8c7982a055b42af:timer', 'i:1795356456;', 1795356456),
('c494ce882218db0db0aa93c018f59d56', 'i:1;', 1795359439),
('c494ce882218db0db0aa93c018f59d56:timer', 'i:1795359439;', 1795359439),
('c4b04ba66be8b9494f97a0225cd4f6a1', 'i:1;', 1795355393),
('c4b04ba66be8b9494f97a0225cd4f6a1:timer', 'i:1795355392;', 1795355392),
('c4b13edc90256164bd77a81b7a15c8d2', 'i:1;', 1795360664),
('c4b13edc90256164bd77a81b7a15c8d2:timer', 'i:1795360664;', 1795360664),
('c4ef8cb0fa5a700c1885ffcae8d520db', 'i:1;', 1795357454),
('c4ef8cb0fa5a700c1885ffcae8d520db:timer', 'i:1795357454;', 1795357454),
('c5ef3ecc75a023e64b6a91c032ef20a8', 'i:1;', 1795358030),
('c5ef3ecc75a023e64b6a91c032ef20a8:timer', 'i:1795358030;', 1795358030),
('c620b1cae85353a2b6891282db7ac896', 'i:1;', 1795356293),
('c620b1cae85353a2b6891282db7ac896:timer', 'i:1795356293;', 1795356293),
('c719c2be446c9c6d6cf4a82e04877203', 'i:1;', 1795360663),
('c719c2be446c9c6d6cf4a82e04877203:timer', 'i:1795360663;', 1795360663),
('c754d7f82159432d3b22f10705f83693', 'i:1;', 1795357201),
('c754d7f82159432d3b22f10705f83693:timer', 'i:1795357201;', 1795357201),
('c7c6aa06434081808b5a4a5a8ea914ef', 'i:1;', 1795356005),
('c7c6aa06434081808b5a4a5a8ea914ef:timer', 'i:1795356005;', 1795356005),
('c7f29ea7342da00c72be3bb5e5c041e0', 'i:1;', 1795357805),
('c7f29ea7342da00c72be3bb5e5c041e0:timer', 'i:1795357805;', 1795357805),
('c8092f19a1dc3d448db46358aa32ef35', 'i:1;', 1795360049),
('c8092f19a1dc3d448db46358aa32ef35:timer', 'i:1795360049;', 1795360049),
('c83cafb1662fc698661e7929230a8418', 'i:1;', 1795359870),
('c83cafb1662fc698661e7929230a8418:timer', 'i:1795359870;', 1795359870),
('c8d475bc8364ec0dbd1ecbb6edd8014b', 'i:1;', 1795357805),
('c8d475bc8364ec0dbd1ecbb6edd8014b:timer', 'i:1795357805;', 1795357805),
('c8dc2fb361194a08603529f4291c9c9c', 'i:1;', 1795357805),
('c8dc2fb361194a08603529f4291c9c9c:timer', 'i:1795357805;', 1795357805),
('c903e4d729d1d83d819c9b52aa0667e1', 'i:1;', 1795358499),
('c903e4d729d1d83d819c9b52aa0667e1:timer', 'i:1795358499;', 1795358499),
('c90b7ebd08c379ced131409a57d3ab28', 'i:1;', 1795356717),
('c90b7ebd08c379ced131409a57d3ab28:timer', 'i:1795356717;', 1795356717),
('c90f8a67244e497fe20292fe9fb2f4d3', 'i:1;', 1795355392),
('c90f8a67244e497fe20292fe9fb2f4d3:timer', 'i:1795355392;', 1795355392),
('c9821035d72748fd2e99db16abe2a8a6', 'i:1;', 1795360336),
('c9821035d72748fd2e99db16abe2a8a6:timer', 'i:1795360336;', 1795360336),
('c9c98d57c0715d28c3fa98a67bcc759b', 'i:1;', 1795355393),
('c9c98d57c0715d28c3fa98a67bcc759b:timer', 'i:1795355393;', 1795355393),
('c9f3775c2ce265f29d47cc0b4cd3fa4a', 'i:1;', 1795356005),
('c9f3775c2ce265f29d47cc0b4cd3fa4a:timer', 'i:1795356005;', 1795356005),
('cb0a865dfb2281711b77ca24837ab75b', 'i:1;', 1795358500),
('cb0a865dfb2281711b77ca24837ab75b:timer', 'i:1795358500;', 1795358500),
('cb10a3f8e85c05ae5b06e6f2b24571c1', 'i:1;', 1795355395),
('cb10a3f8e85c05ae5b06e6f2b24571c1:timer', 'i:1795355395;', 1795355395),
('cb2992d233ef86ef2f57e8291fdc161f', 'i:1;', 1795360047),
('cb2992d233ef86ef2f57e8291fdc161f:timer', 'i:1795360047;', 1795360047),
('cb3f476bff78c497d9ec5ff9df3c78eb', 'i:1;', 1795360227),
('cb3f476bff78c497d9ec5ff9df3c78eb:timer', 'i:1795360227;', 1795360227),
('cb864d48aa78a56749b666c6466405ed', 'i:1;', 1795355393),
('cb864d48aa78a56749b666c6466405ed:timer', 'i:1795355393;', 1795355393),
('cbbf9796171683819c32ae00f0d6a289', 'i:1;', 1795358858),
('cbbf9796171683819c32ae00f0d6a289:timer', 'i:1795358858;', 1795358858),
('cbf6f478e8eb75bcfd29471888d42669', 'i:1;', 1795358499),
('cbf6f478e8eb75bcfd29471888d42669:timer', 'i:1795358499;', 1795358499),
('cc0e18673f72a2eb8550981363933365', 'i:1;', 1795356293),
('cc0e18673f72a2eb8550981363933365:timer', 'i:1795356293;', 1795356293),
('cccd389fccad5c81d06116e8cc383fec', 'i:1;', 1795358030),
('cccd389fccad5c81d06116e8cc383fec:timer', 'i:1795358030;', 1795358030),
('ccd6b1ec625e26504de7d458672f0e0f', 'i:1;', 1795356785),
('ccd6b1ec625e26504de7d458672f0e0f:timer', 'i:1795356785;', 1795356785),
('cd556d08a8bcfdf59fe1331fbbe81bf8', 'i:1;', 1795355573),
('cd556d08a8bcfdf59fe1331fbbe81bf8:timer', 'i:1795355573;', 1795355573),
('cd9885f7417cc9df56a998e5990a7089', 'i:1;', 1795356461),
('cd9885f7417cc9df56a998e5990a7089:timer', 'i:1795356461;', 1795356461),
('cddf639b03486e8cf10c9f9f2805f6fb', 'i:1;', 1795359058),
('cddf639b03486e8cf10c9f9f2805f6fb:timer', 'i:1795359058;', 1795359058),
('ce12a987d5bda9a05d82de0c6a97ad61', 'i:1;', 1795357202),
('ce12a987d5bda9a05d82de0c6a97ad61:timer', 'i:1795357202;', 1795357202),
('ce64044545136486faed2342ae3ee7c2', 'i:1;', 1795357455),
('ce64044545136486faed2342ae3ee7c2:timer', 'i:1795357455;', 1795357455),
('cec3735302da43c42a63e3976d571a21', 'i:1;', 1795358332),
('cec3735302da43c42a63e3976d571a21:timer', 'i:1795358332;', 1795358332),
('cf17266b1abcbbb9ee761345e5acf6c2', 'i:1;', 1795358029),
('cf17266b1abcbbb9ee761345e5acf6c2:timer', 'i:1795358029;', 1795358029),
('cf2dd7e13c6129c001dd0410563954ed', 'i:1;', 1795356717),
('cf2dd7e13c6129c001dd0410563954ed:timer', 'i:1795356717;', 1795356717),
('cf6a591f36016ba729a286785de6e477', 'i:1;', 1795360228),
('cf6a591f36016ba729a286785de6e477:timer', 'i:1795360228;', 1795360228),
('cf845c7cddce1cf65f6da5f9cf8b3f66', 'i:1;', 1795356785),
('cf845c7cddce1cf65f6da5f9cf8b3f66:timer', 'i:1795356785;', 1795356785),
('cf9c5822cc74cd52663f5957711d7635', 'i:1;', 1795356293),
('cf9c5822cc74cd52663f5957711d7635:timer', 'i:1795356293;', 1795356293),
('contact_captcha_1960d9aa-8ae5-412a-bde5-9c3d31123fcb', 'a:1:{s:6:\"answer\";s:4:\"9363\";}', 1795356799),
('contact_captcha_2cc1bb1c-1571-4b39-8bb8-234e94ccd120', 'a:1:{s:6:\"answer\";s:4:\"0703\";}', 1795356296),
('contact_captcha_eab091b6-595f-4e68-b88a-f43f8204293d', 'a:1:{s:6:\"answer\";s:4:\"3622\";}', 1795356923),
('contact_captcha_f2a22ed6-90a1-4736-b37d-bd8839f14dd8', 'a:1:{s:6:\"answer\";s:4:\"5786\";}', 1795356799),
('d0b79e848f6ebcc9c0ca5d4067205e1e', 'i:1;', 1795359441),
('d0b79e848f6ebcc9c0ca5d4067205e1e:timer', 'i:1795359441;', 1795359441),
('d104930b76532f909616217b12e81735', 'i:1;', 1795358858),
('d104930b76532f909616217b12e81735:timer', 'i:1795358858;', 1795358858),
('d1849bbff8cdc264f0136f4b78a3817a', 'i:1;', 1795355574),
('d1849bbff8cdc264f0136f4b78a3817a:timer', 'i:1795355574;', 1795355574),
('d2de4d1c5d1606493661efc8a172a6b0', 'i:1;', 1795359058),
('d2de4d1c5d1606493661efc8a172a6b0:timer', 'i:1795359058;', 1795359058),
('d32046aa6b8babfc1579d2e2fe1e262a', 'i:1;', 1795358030),
('d32046aa6b8babfc1579d2e2fe1e262a:timer', 'i:1795358030;', 1795358030),
('d328a495972ed9b5734a5f8cd0f35006', 'i:1;', 1795355289),
('d328a495972ed9b5734a5f8cd0f35006:timer', 'i:1795355289;', 1795355289),
('d332ccd5118853aa0f652e05665c03d4', 'i:1;', 1795355289),
('d332ccd5118853aa0f652e05665c03d4:timer', 'i:1795355289;', 1795355289),
('d35be4c2c50b264c7a5fb7f1c0e17cea', 'i:1;', 1795356787),
('d35be4c2c50b264c7a5fb7f1c0e17cea:timer', 'i:1795356787;', 1795356787),
('d45abc105d62292acb5012fb3b8801cc', 'i:1;', 1795356786),
('d45abc105d62292acb5012fb3b8801cc:timer', 'i:1795356786;', 1795356786),
('d47cce3b2866dd382143fb52eae2c544', 'i:1;', 1795357457),
('d47cce3b2866dd382143fb52eae2c544:timer', 'i:1795357457;', 1795357457),
('d4f5181eb73f5d2394c62877084303e1', 'i:1;', 1795356879),
('d4f5181eb73f5d2394c62877084303e1:timer', 'i:1795356879;', 1795356879),
('d5a840782a4ce6b3e97ffc4a919e7707', 'i:1;', 1795355393),
('d5a840782a4ce6b3e97ffc4a919e7707:timer', 'i:1795355393;', 1795355393),
('d654a85bf83ffe99a8e7ead4f0df530f', 'i:1;', 1795357456),
('d654a85bf83ffe99a8e7ead4f0df530f:timer', 'i:1795357456;', 1795357456),
('d66d489645c6388657e760bfa972ec58', 'i:1;', 1795360337),
('d66d489645c6388657e760bfa972ec58:timer', 'i:1795360337;', 1795360337),
('d6bb399d8cddd74fd4f2bea76b2b74ba', 'i:1;', 1795355289),
('d6bb399d8cddd74fd4f2bea76b2b74ba:timer', 'i:1795355289;', 1795355289),
('d7cfe50c881b7a07e5b39c0a8c92245e', 'i:1;', 1795357203),
('d7cfe50c881b7a07e5b39c0a8c92245e:timer', 'i:1795357203;', 1795357203),
('d80fc71aee4f59c0157879a94cbe06aa', 'i:1;', 1795359058),
('d80fc71aee4f59c0157879a94cbe06aa:timer', 'i:1795359058;', 1795359058),
('d95ef832e1a86b5fa92de774bb783c0e', 'i:1;', 1795355395),
('d95ef832e1a86b5fa92de774bb783c0e:timer', 'i:1795355395;', 1795355395),
('d97bdf4c853b62a3940bb3b6bfe569bc', 'i:1;', 1795357806),
('d97bdf4c853b62a3940bb3b6bfe569bc:timer', 'i:1795357806;', 1795357806),
('d9b395a6c5185bc632f486b39a34d077', 'i:1;', 1795360336),
('d9b395a6c5185bc632f486b39a34d077:timer', 'i:1795360336;', 1795360336),
('da188dde385eff10bef5f161e367aa47', 'i:1;', 1795358333),
('da188dde385eff10bef5f161e367aa47:timer', 'i:1795358333;', 1795358333),
('db61db56ad53d39c922d140c321d43d5', 'i:1;', 1795355289),
('db61db56ad53d39c922d140c321d43d5:timer', 'i:1795355289;', 1795355289),
('db7b02a5b7e25ebadd51e27df0ca0033', 'i:1;', 1795357202),
('db7b02a5b7e25ebadd51e27df0ca0033:timer', 'i:1795357202;', 1795357202),
('db80c01f053787aaf6530f6a48bf3c60', 'i:1;', 1795358858),
('db80c01f053787aaf6530f6a48bf3c60:timer', 'i:1795358858;', 1795358858),
('db91002b39e804af3d79888cf9986447', 'i:1;', 1795358860),
('db91002b39e804af3d79888cf9986447:timer', 'i:1795358860;', 1795358860),
('db92a9ae2f34c194002f0f49a45c47c7', 'i:1;', 1795357453),
('db92a9ae2f34c194002f0f49a45c47c7:timer', 'i:1795357453;', 1795357453),
('dca59f7dd05d9838e80fd9c35768e3a7', 'i:1;', 1795360048),
('dca59f7dd05d9838e80fd9c35768e3a7:timer', 'i:1795360048;', 1795360048),
('dd45df24704a3f6c268fb1382a82a249', 'i:1;', 1795357203),
('dd45df24704a3f6c268fb1382a82a249:timer', 'i:1795357203;', 1795357203),
('de33afc9e3d579a3ea6e7eeb724c3dfd', 'i:1;', 1795358860),
('de33afc9e3d579a3ea6e7eeb724c3dfd:timer', 'i:1795358860;', 1795358860),
('de52b5fdcdea6686a9593a48c633e8a1', 'i:1;', 1795358500),
('de52b5fdcdea6686a9593a48c633e8a1:timer', 'i:1795358500;', 1795358500),
('de7caa600f72cb877a4a2e8b9e51fc26', 'i:1;', 1795355572),
('de7caa600f72cb877a4a2e8b9e51fc26:timer', 'i:1795355572;', 1795355572),
('deea13522c8884178d818612604281f5', 'i:1;', 1795358859),
('deea13522c8884178d818612604281f5:timer', 'i:1795358859;', 1795358859),
('dfb40d141029e1c85fef62139e81a274', 'i:1;', 1795360048),
('dfb40d141029e1c85fef62139e81a274:timer', 'i:1795360048;', 1795360048),
('e037e12120d38e20384bbde92d5e332e', 'i:1;', 1795357456),
('e037e12120d38e20384bbde92d5e332e:timer', 'i:1795357456;', 1795357456),
('e1c5aa74e4476ecbeab96a1a1ff4fefa', 'i:1;', 1795356304),
('e1c5aa74e4476ecbeab96a1a1ff4fefa:timer', 'i:1795356304;', 1795356304),
('e1c812ef0cef6dabdb4352d605aec1f9', 'i:1;', 1795360047),
('e1c812ef0cef6dabdb4352d605aec1f9:timer', 'i:1795360047;', 1795360047),
('e1cda200d76c94be9483d26e41b589f7', 'i:1;', 1795359871),
('e1cda200d76c94be9483d26e41b589f7:timer', 'i:1795359871;', 1795359871),
('e1eee6bb135d0043416b3f51e5fabdbc', 'i:1;', 1795355572),
('e1eee6bb135d0043416b3f51e5fabdbc:timer', 'i:1795355572;', 1795355572),
('e1f110ed0d0cc4e33f457ffa471c3461', 'i:1;', 1795359061),
('e1f110ed0d0cc4e33f457ffa471c3461:timer', 'i:1795359061;', 1795359061),
('e24a6038495032bb602c77a620aef601', 'i:1;', 1795360337),
('e24a6038495032bb602c77a620aef601:timer', 'i:1795360337;', 1795360337),
('e25b8eb4ccfab51e0b730478936cbb6f', 'i:1;', 1795356259),
('e25b8eb4ccfab51e0b730478936cbb6f:timer', 'i:1795356259;', 1795356259),
('e280f59d923edf363b7b591282b2c8d2', 'i:1;', 1795356365),
('e280f59d923edf363b7b591282b2c8d2:timer', 'i:1795356365;', 1795356365),
('e288b510c159a5f8341b44dc07b821ea', 'i:1;', 1795360662),
('e288b510c159a5f8341b44dc07b821ea:timer', 'i:1795360662;', 1795360662),
('e4a57c600df8154ff45a23a6120f6c50', 'i:1;', 1795357805),
('e4a57c600df8154ff45a23a6120f6c50:timer', 'i:1795357805;', 1795357805),
('e4db78df6584cd9277138380e97cad8c', 'i:1;', 1795360048),
('e4db78df6584cd9277138380e97cad8c:timer', 'i:1795360048;', 1795360048),
('e521c585822b70703f0ef5eb014a7246', 'i:1;', 1795358860),
('e521c585822b70703f0ef5eb014a7246:timer', 'i:1795358860;', 1795358860),
('e529a7d61d3823c8fad4d29d5ecd29be', 'i:1;', 1795360336),
('e529a7d61d3823c8fad4d29d5ecd29be:timer', 'i:1795360336;', 1795360336),
('e5fcac33111613dc921c5b98f2b25a87', 'i:1;', 1795358029),
('e5fcac33111613dc921c5b98f2b25a87:timer', 'i:1795358029;', 1795358029),
('e60f0cfb529d4a754f72e725883766db', 'i:1;', 1795356878),
('e60f0cfb529d4a754f72e725883766db:timer', 'i:1795356878;', 1795356878),
('e6bf9fe42ffc14e76fe4ef6d386f125c', 'i:1;', 1795357202),
('e6bf9fe42ffc14e76fe4ef6d386f125c:timer', 'i:1795357202;', 1795357202),
('e6e0b8dacab6a49fe8946e002452b6b1', 'i:1;', 1795358996),
('e6e0b8dacab6a49fe8946e002452b6b1:timer', 'i:1795358996;', 1795358996),
('e72f521df2045c7fe6f4f5857c55496c', 'i:1;', 1795355572),
('e72f521df2045c7fe6f4f5857c55496c:timer', 'i:1795355572;', 1795355572),
('e7375cc8fd63b3b90149d7ccb985dee2', 'i:1;', 1795358499),
('e7375cc8fd63b3b90149d7ccb985dee2:timer', 'i:1795358499;', 1795358499),
('e767503707c484b5e92aa280f7cf6c20', 'i:1;', 1795357454),
('e767503707c484b5e92aa280f7cf6c20:timer', 'i:1795357454;', 1795357454),
('e7767311cbc5e5351d2df9840e03d3b7', 'i:1;', 1795358859),
('e7767311cbc5e5351d2df9840e03d3b7:timer', 'i:1795358859;', 1795358859),
('e7a7fa71fff17b9e216aeea75b2ffb0b', 'i:1;', 1795357202),
('e7a7fa71fff17b9e216aeea75b2ffb0b:timer', 'i:1795357202;', 1795357202),
('e892ec085da996423ce7d443f4cdec0b', 'i:1;', 1795356364),
('e892ec085da996423ce7d443f4cdec0b:timer', 'i:1795356364;', 1795356364),
('e9127ecc13dd8527cf71273ca5184e34', 'i:1;', 1795357202),
('e9127ecc13dd8527cf71273ca5184e34:timer', 'i:1795357202;', 1795357202),
('e937b4e84f43df0cca532b743d6be036', 'i:1;', 1795357805),
('e937b4e84f43df0cca532b743d6be036:timer', 'i:1795357805;', 1795357805),
('e9459325ae9ff40c728fc16af8fec2ee', 'i:1;', 1795356877),
('e9459325ae9ff40c728fc16af8fec2ee:timer', 'i:1795356877;', 1795356877),
('e9ccc16d547d051a2b0cf017e31cac67', 'i:1;', 1795359060),
('e9ccc16d547d051a2b0cf017e31cac67:timer', 'i:1795359060;', 1795359060),
('e9cfac45c701f76ab23aecb55fb95cfc', 'i:1;', 1795359059),
('e9cfac45c701f76ab23aecb55fb95cfc:timer', 'i:1795359059;', 1795359059),
('e9dbd674d9cbfefc3412437f7c0f5b89', 'i:1;', 1795360228),
('e9dbd674d9cbfefc3412437f7c0f5b89:timer', 'i:1795360228;', 1795360228),
('e9e610176689c025f6fddeba32a256b1', 'i:1;', 1795360228),
('e9e610176689c025f6fddeba32a256b1:timer', 'i:1795360228;', 1795360228),
('ea21676896f92db26ee65a4c0bc30eb9', 'i:1;', 1795357455),
('ea21676896f92db26ee65a4c0bc30eb9:timer', 'i:1795357455;', 1795357455),
('ea6fd96bae6bb9963bc0ead4aabd3daf', 'i:1;', 1795358999),
('ea6fd96bae6bb9963bc0ead4aabd3daf:timer', 'i:1795358999;', 1795358999),
('ea873858868cd8dcb1ee884d3be636fc', 'i:1;', 1795358030),
('ea873858868cd8dcb1ee884d3be636fc:timer', 'i:1795358030;', 1795358030),
('eb06c7555472367db49e855ed6a48a4f', 'i:1;', 1795360663),
('eb06c7555472367db49e855ed6a48a4f:timer', 'i:1795360663;', 1795360663),
('eb7aedc7f223ad11382e8b66b96a62d7', 'i:1;', 1795356787),
('eb7aedc7f223ad11382e8b66b96a62d7:timer', 'i:1795356787;', 1795356787),
('eb9e213907390f32dd56b8881221b6b5', 'i:1;', 1795359870),
('eb9e213907390f32dd56b8881221b6b5:timer', 'i:1795359870;', 1795359870),
('ebfe8aea53398866d23caf6da23de06d', 'i:1;', 1795356878),
('ebfe8aea53398866d23caf6da23de06d:timer', 'i:1795356878;', 1795356878),
('eca66f521fb2dfc5093b55cc17571e5f', 'i:1;', 1795360047),
('eca66f521fb2dfc5093b55cc17571e5f:timer', 'i:1795360047;', 1795360047),
('ecc1b82dfc5d124ba713688fe6467fb3', 'i:1;', 1795358332),
('ecc1b82dfc5d124ba713688fe6467fb3:timer', 'i:1795358332;', 1795358332),
('ecc2a5c01235a9555b857e0f29f07501', 'i:1;', 1795356877),
('ecc2a5c01235a9555b857e0f29f07501:timer', 'i:1795356877;', 1795356877),
('ed14ee1a5d90358c9b059292a63bd864', 'i:1;', 1795355573),
('ed14ee1a5d90358c9b059292a63bd864:timer', 'i:1795355573;', 1795355573),
('ed7d6e027af0c6b50c68472bcea29153', 'i:1;', 1795358031),
('ed7d6e027af0c6b50c68472bcea29153:timer', 'i:1795358031;', 1795358031),
('edb45b4a92ad59b9e61cada2d330e1c4', 'i:1;', 1795356877),
('edb45b4a92ad59b9e61cada2d330e1c4:timer', 'i:1795356877;', 1795356877),
('ee593f371b01d779b8909a7ae9f12867', 'i:1;', 1795360338),
('ee593f371b01d779b8909a7ae9f12867:timer', 'i:1795360338;', 1795360338),
('ee946b5cc86b6871cb2086583f8baeb8', 'i:1;', 1795358858),
('ee946b5cc86b6871cb2086583f8baeb8:timer', 'i:1795358858;', 1795358858),
('ee9b5bf66c3757db1359c644d2a65cf5', 'i:1;', 1795355574),
('ee9b5bf66c3757db1359c644d2a65cf5:timer', 'i:1795355574;', 1795355574),
('eeedfb86e177a6202c4a192af7e360f9', 'i:1;', 1795360229),
('eeedfb86e177a6202c4a192af7e360f9:timer', 'i:1795360229;', 1795360229),
('eefb65e5c38ed50f1221233c830a1536', 'i:1;', 1795355573),
('eefb65e5c38ed50f1221233c830a1536:timer', 'i:1795355573;', 1795355573),
('eefd93dde7b69fbd9b767a0bd4baa9f4', 'i:1;', 1795356457),
('eefd93dde7b69fbd9b767a0bd4baa9f4:timer', 'i:1795356457;', 1795356457),
('ef466ef57c60b36c30de00b5475840a4', 'i:1;', 1795355572),
('ef466ef57c60b36c30de00b5475840a4:timer', 'i:1795355572;', 1795355572),
('ef4bdfac81ae93c34eb6c6526d711120', 'i:1;', 1795358998),
('ef4bdfac81ae93c34eb6c6526d711120:timer', 'i:1795358998;', 1795358998),
('f011246f7ed575877b73f670cf0b292a', 'i:1;', 1795356364),
('f011246f7ed575877b73f670cf0b292a:timer', 'i:1795356364;', 1795356364),
('f0ce895b1963141f4539a78dd816b565', 'i:1;', 1795355395),
('f0ce895b1963141f4539a78dd816b565:timer', 'i:1795355395;', 1795355395),
('f0d9cff20a5cca09717b0c66ffbf6032', 'i:1;', 1795359440),
('f0d9cff20a5cca09717b0c66ffbf6032:timer', 'i:1795359440;', 1795359440),
('f0dc61712957399b420ab428ce7efef7', 'i:1;', 1795360662),
('f0dc61712957399b420ab428ce7efef7:timer', 'i:1795360662;', 1795360662),
('f0e3bd9e9b9f45fee9efe6fbf2ad981a', 'i:1;', 1795357202),
('f0e3bd9e9b9f45fee9efe6fbf2ad981a:timer', 'i:1795357202;', 1795357202),
('f12419fe9593225136df850463261cde', 'i:1;', 1795355290),
('f12419fe9593225136df850463261cde:timer', 'i:1795355290;', 1795355290),
('f1a0867e625c85021d0be0eaa0a5c823', 'i:1;', 1795358860),
('f1a0867e625c85021d0be0eaa0a5c823:timer', 'i:1795358860;', 1795358860),
('f2312edfbb5e50ca5c4c5503dc15da37', 'i:1;', 1795355572),
('f2312edfbb5e50ca5c4c5503dc15da37:timer', 'i:1795355572;', 1795355572),
('f28c3de24a367128e20042da04c997af', 'i:1;', 1795357202),
('f28c3de24a367128e20042da04c997af:timer', 'i:1795357202;', 1795357202),
('f2c8e1e8e2791e485f6925ec0a371484', 'i:1;', 1795357455),
('f2c8e1e8e2791e485f6925ec0a371484:timer', 'i:1795357455;', 1795357455),
('f40a196b8f43724aa712e6cd7aa7bff2', 'i:1;', 1795356461),
('f40a196b8f43724aa712e6cd7aa7bff2:timer', 'i:1795356461;', 1795356461),
('f48ec31696d7b103239bbc64df8014d6', 'i:1;', 1795355288),
('f48ec31696d7b103239bbc64df8014d6:timer', 'i:1795355288;', 1795355288),
('f5f5cb94caa604138dd0d8ab6a69f339', 'i:1;', 1795356365),
('f5f5cb94caa604138dd0d8ab6a69f339:timer', 'i:1795356365;', 1795356365),
('f6801fa6030212f82cd8b00eadaa15b2', 'i:1;', 1795359441),
('f6801fa6030212f82cd8b00eadaa15b2:timer', 'i:1795359441;', 1795359441),
('f6fcda194680d7f46909cea6d49008e5', 'i:1;', 1795358031),
('f6fcda194680d7f46909cea6d49008e5:timer', 'i:1795358031;', 1795358031),
('f7183fdac514095fe8b48fb7bf4a7f56', 'i:1;', 1795360047),
('f7183fdac514095fe8b48fb7bf4a7f56:timer', 'i:1795360047;', 1795360047),
('f74ddbeb52a12d10378e44b6a1ec8f94', 'i:1;', 1795360336),
('f74ddbeb52a12d10378e44b6a1ec8f94:timer', 'i:1795360336;', 1795360336),
('f7b1018ba4b5676b252b6ad15de2cb96', 'i:1;', 1795357454),
('f7b1018ba4b5676b252b6ad15de2cb96:timer', 'i:1795357454;', 1795357454),
('f8023f09055c6cd0d9e65596d36d50fc', 'i:1;', 1795356304),
('f8023f09055c6cd0d9e65596d36d50fc:timer', 'i:1795356304;', 1795356304),
('f847a6d53d0cf53facefdc268cc33e5b', 'i:1;', 1795356717),
('f847a6d53d0cf53facefdc268cc33e5b:timer', 'i:1795356717;', 1795356717),
('f938f95558901924577b12521e2ec234', 'i:1;', 1795356878),
('f938f95558901924577b12521e2ec234:timer', 'i:1795356878;', 1795356878),
('f96d17198c385404bb230d50380656d7', 'i:1;', 1795360336),
('f96d17198c385404bb230d50380656d7:timer', 'i:1795360336;', 1795360336),
('f97dcea4023e2c67fc778151ae588db4', 'i:1;', 1795355288),
('f97dcea4023e2c67fc778151ae588db4:timer', 'i:1795355288;', 1795355288),
('f98a1480e21a0e1f7ee91629eafed798', 'i:1;', 1795358334),
('f98a1480e21a0e1f7ee91629eafed798:timer', 'i:1795358334;', 1795358334),
('f9e7d8976c8031eb2258eff776eac832', 'i:1;', 1795358333),
('f9e7d8976c8031eb2258eff776eac832:timer', 'i:1795358333;', 1795358333),
('f9e816f7c3799f1d84675f8db1f40f6b', 'i:1;', 1795355291),
('f9e816f7c3799f1d84675f8db1f40f6b:timer', 'i:1795355291;', 1795355291),
('fa48285c4d435f1d479d0426b4658978', 'i:1;', 1795357453),
('fa48285c4d435f1d479d0426b4658978:timer', 'i:1795357453;', 1795357453),
('fa4ac6ae7e2a04a6de23ed09000b92bc', 'i:1;', 1795355395),
('fa4ac6ae7e2a04a6de23ed09000b92bc:timer', 'i:1795355395;', 1795355395),
('fb4e700113a7335a30e1787b107e704e', 'i:1;', 1795358500),
('fb4e700113a7335a30e1787b107e704e:timer', 'i:1795358500;', 1795358500),
('fc0246335284b6c802b97c1ac05d3980', 'i:1;', 1795360337),
('fc0246335284b6c802b97c1ac05d3980:timer', 'i:1795360337;', 1795360337),
('fd5ab656761b58908c9bac424368c4ba', 'i:1;', 1795356878),
('fd5ab656761b58908c9bac424368c4ba:timer', 'i:1795356878;', 1795356878),
('fd68e8e506e9d448c9ea24261da0504b', 'i:1;', 1795360663),
('fd68e8e506e9d448c9ea24261da0504b:timer', 'i:1795360663;', 1795360663),
('fdca78ce3803fc10171db2e2ca0ead87', 'i:1;', 1795356878),
('fdca78ce3803fc10171db2e2ca0ead87:timer', 'i:1795356878;', 1795356878),
('fe55f265817b45b28946d87fd40d7e94', 'i:1;', 1795360663),
('fe55f265817b45b28946d87fd40d7e94:timer', 'i:1795360663;', 1795360663),
('fee78dc8189c774b76d5f4928786eb1a', 'i:1;', 1795356717),
('fee78dc8189c774b76d5f4928786eb1a:timer', 'i:1795356717;', 1795356717),
('ff258ae921c2aa2f3d7d94e6668c19cf', 'i:1;', 1795356879),
('ff258ae921c2aa2f3d7d94e6668c19cf:timer', 'i:1795356879;', 1795356879),
('ffbc2a2ddbabe04b0e27d6026fcf34d0', 'i:1;', 1795356785),
('ffbc2a2ddbabe04b0e27d6026fcf34d0:timer', 'i:1795356785;', 1795356785),
('route.permission.banner.index', 'O:33:\"App\\Models\\ManagementAccess\\Route\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:43;s:5:\"route\";s:12:\"banner.index\";s:15:\"permission_name\";s:12:\"banner.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-22 20:23:14\";s:10:\"updated_at\";s:19:\"2026-11-22 20:23:14\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:43;s:5:\"route\";s:12:\"banner.index\";s:15:\"permission_name\";s:12:\"banner.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-22 20:23:14\";s:10:\"updated_at\";s:19:\"2026-11-22 20:23:14\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:6:\"status\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"route\";i:1;s:15:\"permission_name\";i:2;s:6:\"status\";i:3;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1795358906),
('route.permission.dashboard.index', 'O:33:\"App\\Models\\ManagementAccess\\Route\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:1;s:5:\"route\";s:15:\"dashboard.index\";s:15:\"permission_name\";s:15:\"dashboard.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-22 20:23:14\";s:10:\"updated_at\";s:19:\"2026-11-22 20:23:14\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:1;s:5:\"route\";s:15:\"dashboard.index\";s:15:\"permission_name\";s:15:\"dashboard.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-22 20:23:14\";s:10:\"updated_at\";s:19:\"2026-11-22 20:23:14\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:6:\"status\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"route\";i:1;s:15:\"permission_name\";i:2;s:6:\"status\";i:3;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1795354865),
('route.permission.orders.checklist.update', 'O:33:\"App\\Models\\ManagementAccess\\Route\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:355;s:5:\"route\";s:23:\"orders.checklist.update\";s:15:\"permission_name\";s:11:\"orders.show\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-22 20:23:14\";s:10:\"updated_at\";s:19:\"2026-11-22 20:23:14\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:355;s:5:\"route\";s:23:\"orders.checklist.update\";s:15:\"permission_name\";s:11:\"orders.show\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-22 20:23:14\";s:10:\"updated_at\";s:19:\"2026-11-22 20:23:14\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:6:\"status\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"route\";i:1;s:15:\"permission_name\";i:2;s:6:\"status\";i:3;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1795354088),
('route.permission.orders.index', 'O:33:\"App\\Models\\ManagementAccess\\Route\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:194;s:5:\"route\";s:12:\"orders.index\";s:15:\"permission_name\";s:12:\"orders.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-22 20:23:14\";s:10:\"updated_at\";s:19:\"2026-11-22 20:23:14\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:194;s:5:\"route\";s:12:\"orders.index\";s:15:\"permission_name\";s:12:\"orders.index\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-22 20:23:14\";s:10:\"updated_at\";s:19:\"2026-11-22 20:23:14\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:6:\"status\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"route\";i:1;s:15:\"permission_name\";i:2;s:6:\"status\";i:3;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1795354450),
('route.permission.packages.member', 'O:33:\"App\\Models\\ManagementAccess\\Route\":34:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:6:\"routes\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:12:\"\0*\0refreshes\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";i:327;s:5:\"route\";s:15:\"packages.member\";s:15:\"permission_name\";s:14:\"package.member\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-22 20:23:14\";s:10:\"updated_at\";s:19:\"2026-11-22 20:23:14\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";i:327;s:5:\"route\";s:15:\"packages.member\";s:15:\"permission_name\";s:14:\"package.member\";s:6:\"status\";i:1;s:11:\"description\";N;s:10:\"created_at\";s:19:\"2026-11-22 20:23:14\";s:10:\"updated_at\";s:19:\"2026-11-22 20:23:14\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:6:\"status\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:5:\"route\";i:1;s:15:\"permission_name\";i:2;s:6:\"status\";i:3;s:11:\"description\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1795356071);
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:135:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:14:\"menu.main-menu\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:20:\"menu.role-permission\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:22:\"menu.access-management\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:15:\"dashboard.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"user.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:10:\"user.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:11:\"user.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"user.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:16:\"menu-group.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:16:\"menu-group.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:17:\"menu-group.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:18:\"menu-group.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:15:\"menu-item.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:15:\"menu-item.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:16:\"menu-item.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:17:\"menu-item.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:11:\"route.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:11:\"route.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:12:\"route.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:13:\"route.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:10:\"role.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:10:\"role.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:11:\"role.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:12:\"role.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:16:\"permission.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:16:\"permission.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:17:\"permission.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:18:\"permission.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:11:\"faq.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:9:\"faq.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:9:\"faq.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:10:\"faq.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:20:\"menu.main-portofolio\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:33;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:13:\"company.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:34;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:13:\"company.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:35;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:14:\"company.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:36;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:18:\"categories.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:37;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:16:\"categories.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:38;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:16:\"categories.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:39;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:17:\"categories.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:40;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:14:\"banner.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:41;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:12:\"banner.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:42;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:12:\"banner.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:43;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:13:\"banner.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:44;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:16:\"articles.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:45;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:15:\"articles.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:46;a:4:{s:1:\"a\";i:52;s:1:\"b\";s:14:\"articles.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:47;a:4:{s:1:\"a\";i:53;s:1:\"b\";s:14:\"articles.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:48;a:4:{s:1:\"a\";i:54;s:1:\"b\";s:15:\"articles.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:3;i:2;i:5;}}i:49;a:4:{s:1:\"a\";i:55;s:1:\"b\";s:14:\"dashboard.form\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:50;a:4:{s:1:\"a\";i:60;s:1:\"b\";s:12:\"poll.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:51;a:4:{s:1:\"a\";i:65;s:1:\"b\";s:10:\"poll.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:52;a:4:{s:1:\"a\";i:70;s:1:\"b\";s:10:\"poll.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:53;a:4:{s:1:\"a\";i:75;s:1:\"b\";s:11:\"poll.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:54;a:4:{s:1:\"a\";i:85;s:1:\"b\";s:13:\"account.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:55;a:4:{s:1:\"a\";i:90;s:1:\"b\";s:14:\"account.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:4:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:5;}}i:56;a:3:{s:1:\"a\";i:125;s:1:\"b\";s:20:\"filedownload.destroy\";s:1:\"c\";s:3:\"web\";}i:57;a:4:{s:1:\"a\";i:130;s:1:\"b\";s:18:\"filedownload.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:2;i:1;i:5;}}i:58;a:3:{s:1:\"a\";i:135;s:1:\"b\";s:18:\"filedownload.store\";s:1:\"c\";s:3:\"web\";}i:59;a:3:{s:1:\"a\";i:140;s:1:\"b\";s:19:\"filedownload.update\";s:1:\"c\";s:3:\"web\";}i:60;a:4:{s:1:\"a\";i:146;s:1:\"b\";s:12:\"vendor.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:61;a:4:{s:1:\"a\";i:147;s:1:\"b\";s:12:\"pages.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:62;a:4:{s:1:\"a\";i:148;s:1:\"b\";s:13:\"pages.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:63;a:4:{s:1:\"a\";i:149;s:1:\"b\";s:11:\"pages.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:64;a:4:{s:1:\"a\";i:150;s:1:\"b\";s:11:\"pages.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:65;a:4:{s:1:\"a\";i:151;s:1:\"b\";s:12:\"pages.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:66;a:4:{s:1:\"a\";i:152;s:1:\"b\";s:14:\"vendor.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:67;a:4:{s:1:\"a\";i:153;s:1:\"b\";s:12:\"vendor.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:68;a:4:{s:1:\"a\";i:154;s:1:\"b\";s:13:\"vendor.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:69;a:4:{s:1:\"a\";i:155;s:1:\"b\";s:19:\"testimonial.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:70;a:4:{s:1:\"a\";i:156;s:1:\"b\";s:17:\"testimonial.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:71;a:4:{s:1:\"a\";i:157;s:1:\"b\";s:18:\"testimonial.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:72;a:4:{s:1:\"a\";i:158;s:1:\"b\";s:17:\"testimonial.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:73;a:4:{s:1:\"a\";i:159;s:1:\"b\";s:12:\"events.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:74;a:4:{s:1:\"a\";i:160;s:1:\"b\";s:14:\"events.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:75;a:4:{s:1:\"a\";i:161;s:1:\"b\";s:12:\"events.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:76;a:4:{s:1:\"a\";i:162;s:1:\"b\";s:13:\"events.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:77;a:4:{s:1:\"a\";i:163;s:1:\"b\";s:24:\"vendors_categories.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:78;a:4:{s:1:\"a\";i:164;s:1:\"b\";s:26:\"vendors_categories.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:79;a:4:{s:1:\"a\";i:165;s:1:\"b\";s:25:\"vendors_categories.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:80;a:4:{s:1:\"a\";i:166;s:1:\"b\";s:24:\"vendors_categories.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:81;a:4:{s:1:\"a\";i:167;s:1:\"b\";s:15:\"package.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:82;a:4:{s:1:\"a\";i:168;s:1:\"b\";s:13:\"package.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:83;a:4:{s:1:\"a\";i:169;s:1:\"b\";s:14:\"package.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:84;a:4:{s:1:\"a\";i:170;s:1:\"b\";s:13:\"package.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:85;a:4:{s:1:\"a\";i:171;s:1:\"b\";s:14:\"package.member\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:86;a:4:{s:1:\"a\";i:176;s:1:\"b\";s:22:\"dashboard.submitSumber\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:87;a:4:{s:1:\"a\";i:185;s:1:\"b\";s:14:\"pengguna.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:88;a:4:{s:1:\"a\";i:186;s:1:\"b\";s:15:\"pengguna.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:89;a:4:{s:1:\"a\";i:187;s:1:\"b\";s:12:\"venues.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:90;a:4:{s:1:\"a\";i:188;s:1:\"b\";s:14:\"venues.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:91;a:4:{s:1:\"a\";i:189;s:1:\"b\";s:13:\"venues.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:92;a:4:{s:1:\"a\";i:190;s:1:\"b\";s:12:\"venues.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:93;a:4:{s:1:\"a\";i:191;s:1:\"b\";s:13:\"venues.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:94;a:4:{s:1:\"a\";i:192;s:1:\"b\";s:13:\"vendor.mobile\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:95;a:4:{s:1:\"a\";i:193;s:1:\"b\";s:16:\"checkout.package\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:96;a:4:{s:1:\"a\";i:194;s:1:\"b\";s:12:\"orders.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:97;a:4:{s:1:\"a\";i:195;s:1:\"b\";s:11:\"orders.show\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:98;a:4:{s:1:\"a\";i:196;s:1:\"b\";s:12:\"orders.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:99;a:4:{s:1:\"a\";i:197;s:1:\"b\";s:13:\"menu.keamanan\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:100;a:4:{s:1:\"a\";i:198;s:1:\"b\";s:20:\"login-activity.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:101;a:4:{s:1:\"a\";i:199;s:1:\"b\";s:22:\"login-activity.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:102;a:4:{s:1:\"a\";i:200;s:1:\"b\";s:19:\"login-lockout.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:103;a:4:{s:1:\"a\";i:201;s:1:\"b\";s:21:\"login-lockout.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:104;a:4:{s:1:\"a\";i:202;s:1:\"b\";s:15:\"audit-log.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:105;a:4:{s:1:\"a\";i:203;s:1:\"b\";s:17:\"audit-log.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:106;a:4:{s:1:\"a\";i:204;s:1:\"b\";s:18:\"failed-login.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:107;a:4:{s:1:\"a\";i:205;s:1:\"b\";s:20:\"failed-login.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:108;a:4:{s:1:\"a\";i:206;s:1:\"b\";s:22:\"website-identity.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:109;a:4:{s:1:\"a\";i:207;s:1:\"b\";s:23:\"website-identity.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:110;a:4:{s:1:\"a\";i:208;s:1:\"b\";s:17:\"memberships.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:111;a:4:{s:1:\"a\";i:209;s:1:\"b\";s:16:\"memberships.show\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:112;a:4:{s:1:\"a\";i:214;s:1:\"b\";s:14:\"orders.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:113;a:4:{s:1:\"a\";i:215;s:1:\"b\";s:13:\"orders.reject\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:114;a:4:{s:1:\"a\";i:216;s:1:\"b\";s:12:\"orders.proof\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}}i:115;a:4:{s:1:\"a\";i:217;s:1:\"b\";s:16:\"konsultasi.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:116;a:4:{s:1:\"a\";i:218;s:1:\"b\";s:15:\"konsultasi.bulk\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:117;a:4:{s:1:\"a\";i:219;s:1:\"b\";s:17:\"konsultasi.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:118;a:4:{s:1:\"a\";i:220;s:1:\"b\";s:17:\"konsultasi.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:119;a:4:{s:1:\"a\";i:221;s:1:\"b\";s:16:\"konsultasi.merge\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:120;a:4:{s:1:\"a\";i:222;s:1:\"b\";s:17:\"konsultasi.import\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:121;a:4:{s:1:\"a\";i:223;s:1:\"b\";s:19:\"email-setting.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:122;a:4:{s:1:\"a\";i:224;s:1:\"b\";s:20:\"email-setting.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:123;a:4:{s:1:\"a\";i:226;s:1:\"b\";s:11:\"orders.bulk\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:124;a:4:{s:1:\"a\";i:227;s:1:\"b\";s:16:\"orders.exportCsv\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:125;a:4:{s:1:\"a\";i:228;s:1:\"b\";s:12:\"orders.audit\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:126;a:4:{s:1:\"a\";i:229;s:1:\"b\";s:15:\"orders.whatsapp\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:127;a:4:{s:1:\"a\";i:230;s:1:\"b\";s:13:\"orders.assign\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:128;a:4:{s:1:\"a\";i:231;s:1:\"b\";s:19:\"orders.savedFilters\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:129;a:4:{s:1:\"a\";i:232;s:1:\"b\";s:15:\"orders.bulkUndo\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:130;a:4:{s:1:\"a\";i:233;s:1:\"b\";s:16:\"orders.quickView\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:131;a:4:{s:1:\"a\";i:234;s:1:\"b\";s:19:\"package.promo.index\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:132;a:4:{s:1:\"a\";i:235;s:1:\"b\";s:19:\"package.promo.store\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:133;a:4:{s:1:\"a\";i:236;s:1:\"b\";s:20:\"package.promo.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:134;a:4:{s:1:\"a\";i:237;s:1:\"b\";s:21:\"package.promo.destroy\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:11:\"super-admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:4:\"user\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:5;s:1:\"b\";s:6:\"vendor\";s:1:\"c\";s:3:\"web\";}}}', 1795440212),
('website-identity:public', 'a:19:{s:9:\"site_name\";s:14:\"Sagita Wedding\";s:10:\"site_title\";s:14:\"Sagita Wedding\";s:7:\"tagline\";s:15:\"Wedding Planner\";s:17:\"short_description\";s:84:\"Wedding planning, concept, and celebration services designed with elegance and care.\";s:5:\"email\";N;s:5:\"phone\";N;s:7:\"address\";N;s:12:\"facebook_url\";N;s:13:\"instagram_url\";N;s:11:\"youtube_url\";N;s:10:\"tiktok_url\";N;s:10:\"meta_title\";N;s:16:\"meta_description\";N;s:13:\"meta_keywords\";N;s:19:\"google_analytics_id\";N;s:24:\"google_site_verification\";N;s:8:\"logo_url\";N;s:11:\"favicon_url\";N;s:12:\"og_image_url\";N;}', 1795362427);

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
-- Table structure for table `checkout_sessions`
--

CREATE TABLE `checkout_sessions` (
  `uuid` char(36) NOT NULL,
  `visitor_key` char(36) NOT NULL,
  `package_uuid` char(36) NOT NULL,
  `order_uuid` char(36) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'started',
  `started_at` timestamp NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `checkout_sessions`
--

INSERT INTO `checkout_sessions` (`uuid`, `visitor_key`, `package_uuid`, `order_uuid`, `status`, `started_at`, `completed_at`, `created_at`, `updated_at`) VALUES
('01a201f9-6a56-766e-b776-475e2c83bfc9', '00bb1b28-e73a-4a74-9ad9-d177bd9714ae', '01a098c6-e509-7154-869f-8d336d44ebc4', NULL, 'started', '2026-11-22 06:37:32', NULL, '2026-11-22 06:37:32', '2026-11-22 06:37:32'),
('01a2022b-5a43-7c7e-9d8b-2709b806e080', 'c99b6f60-1ab7-4c15-8494-f93f2c4d02aa', '01a098c6-e509-7154-869f-8d336d44ebc4', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'completed', '2026-11-22 07:32:05', '2026-11-22 07:32:17', '2026-11-22 07:32:05', '2026-11-22 07:32:17');

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
('206ab35b-cb99-4581-9521-e4556fb135af', 'log', 'smtp-relay.brevo.com', 587, 'tls', NULL, NULL, 60, 'hello@example.com', 'Sagita Wedding', 1, '2026-11-21 13:21:41', '2026-11-22 09:23:55');

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
-- Table structure for table `event_date_reservations`
--

CREATE TABLE `event_date_reservations` (
  `event_date` date NOT NULL,
  `order_uuid` char(36) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'available',
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_date_reservations`
--

INSERT INTO `event_date_reservations` (`event_date`, `order_uuid`, `status`, `expires_at`, `created_at`, `updated_at`) VALUES
('2026-11-29', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'confirmed', NULL, '2026-11-22 07:32:17', '2026-11-22 07:36:30');

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
(6, 'wikuob@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', 'credentials', '2026-11-22 08:50:56', '2026-11-22 08:50:56', '2026-11-22 08:50:56'),
(7, 'wikuob@gmail.com', NULL, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0 Mobile/15E148 Safari/604.1', 'credentials', '2026-11-22 08:50:56', '2026-11-22 08:50:56', '2026-11-22 08:50:56');

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
(548, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 03:00:33', '2026-11-22 03:00:33', '2026-11-22 03:00:33'),
(549, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', 'web', '2026-11-22 04:16:12', '2026-11-22 04:16:12', '2026-11-22 04:16:12'),
(550, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', 'web', '2026-11-22 04:16:12', '2026-11-22 04:16:12', '2026-11-22 04:16:12'),
(551, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 06:45:19', '2026-11-22 06:45:19', '2026-11-22 06:45:19'),
(552, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 06:45:19', '2026-11-22 06:45:19', '2026-11-22 06:45:19'),
(553, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 07:33:51', '2026-11-22 07:33:51', '2026-11-22 07:33:51'),
(554, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 07:33:51', '2026-11-22 07:33:51', '2026-11-22 07:33:51'),
(555, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 08:51:15', '2026-11-22 08:51:15', '2026-11-22 08:51:15'),
(556, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 08:51:15', '2026-11-22 08:51:15', '2026-11-22 08:51:15'),
(557, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 09:07:42', '2026-11-22 09:07:42', '2026-11-22 09:07:42'),
(558, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 09:07:42', '2026-11-22 09:07:42', '2026-11-22 09:07:42'),
(559, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 09:07:54', '2026-11-22 09:07:54', '2026-11-22 09:07:54'),
(560, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 09:07:54', '2026-11-22 09:07:54', '2026-11-22 09:07:54'),
(561, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 09:10:11', '2026-11-22 09:10:11', '2026-11-22 09:10:11'),
(562, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 09:10:11', '2026-11-22 09:10:11', '2026-11-22 09:10:11'),
(563, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 09:52:09', '2026-11-22 09:52:09', '2026-11-22 09:52:09'),
(564, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 09:52:09', '2026-11-22 09:52:09', '2026-11-22 09:52:09'),
(565, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', 'web', '2026-11-22 10:41:30', '2026-11-22 10:41:30', '2026-11-22 10:41:30'),
(566, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', 'web', '2026-11-22 10:41:30', '2026-11-22 10:41:30', '2026-11-22 10:41:30'),
(567, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', 'web', '2026-11-22 10:44:38', '2026-11-22 10:44:38', '2026-11-22 10:44:38'),
(568, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', 'web', '2026-11-22 10:44:38', '2026-11-22 10:44:38', '2026-11-22 10:44:38'),
(569, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', 'web', '2026-11-22 10:53:44', '2026-11-22 10:53:44', '2026-11-22 10:53:44'),
(570, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', 'web', '2026-11-22 10:53:44', '2026-11-22 10:53:44', '2026-11-22 10:53:44'),
(571, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 11:14:16', '2026-11-22 11:14:16', '2026-11-22 11:14:16'),
(572, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 11:14:16', '2026-11-22 11:14:16', '2026-11-22 11:14:16'),
(573, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 11:21:22', '2026-11-22 11:21:22', '2026-11-22 11:21:22'),
(574, '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'Wiku Pramesthi Bagaswara', 'wikupb@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 11:21:22', '2026-11-22 11:21:22', '2026-11-22 11:21:22'),
(575, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 11:23:38', '2026-11-22 11:23:38', '2026-11-22 11:23:38'),
(576, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 11:23:38', '2026-11-22 11:23:38', '2026-11-22 11:23:38'),
(577, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 13:22:16', '2026-11-22 13:22:16', '2026-11-22 13:22:16'),
(578, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', 'super@admin.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'web', '2026-11-22 13:22:16', '2026-11-22 13:22:16', '2026-11-22 13:22:16');

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

--
-- Dumping data for table `login_lockouts`
--

INSERT INTO `login_lockouts` (`id`, `type`, `value`, `attempts`, `reason`, `blocked_until`, `last_attempt_at`, `created_at`, `updated_at`) VALUES
(8, 'email', 'wikuob@gmail.com', 2, NULL, NULL, '2026-11-22 08:50:56', '2026-11-22 08:50:56', '2026-11-22 08:50:56');

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
(127, '2026_11_22_000002_drop_wedding_plans_table', 69),
(128, '2026_11_22_130000_add_installment_schedule_to_orders_table', 70),
(129, '2026_11_22_140000_create_event_date_reservations_table', 71),
(130, '2026_11_22_140100_create_order_workflow_settings_and_documents_tables', 72),
(131, '2026_11_22_140200_create_wedding_checklist_items_table', 73),
(132, '2026_11_22_140300_create_order_change_requests_table', 74),
(133, '2026_11_22_140400_create_checkout_sessions_table', 75),
(134, '2026_11_22_000001_cleanup_legacy_wedding_copy', 76),
(135, '2026_11_22_000003_make_banner_link_nullable', 76),
(136, '2026_11_22_000004_add_video_to_articles_table', 76),
(137, '2026_11_22_000005_add_user_profile_tracking_fields', 76),
(138, '2026_11_22_150000_add_reminder_columns_to_wedding_checklist_items_table', 76),
(139, '2026_11_22_160000_create_order_vendor_assignments_table', 76),
(140, '2026_11_22_160100_remove_email_test_access', 77);

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
('4ee83ebe-638e-45cb-aa71-02cf87721984', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-DHVCIG8ZQL ?????? Wiku Pramesthi Bagaswara memesan First Timer Package ?????? First Class Experience (Rp 210.000)\",\"path\":\"/backend/orders/01a11182-8463-7057-a0f4-8cf88279c6c2\",\"icon\":\"bi-receipt\"}', '2026-11-22 12:26:02', '2026-10-06 13:58:48', '2026-11-22 12:26:02'),
('54fbcbf6-8ebc-4f5e-a62f-7b184c3a8c55', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-BNEJIR8QFB ?????? Wiku Pramesthi Bagaswara memesan First Timer Package ?????? First Class Experience (Rp 210.000)\",\"path\":\"/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"icon\":\"bi-receipt\"}', NULL, '2026-10-06 10:12:11', '2026-10-06 10:12:11'),
('5e0c56a7-c2b0-4fe2-9a1b-dbe081f039d2', 'App\\Notifications\\OrderExpiredNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Pesanan kedaluwarsa\",\"message\":\"ORD-QCQL0MKBRP otomatis dibatalkan setelah 24 jam tanpa pembayaran. Silakan buat pesanan baru jika tetap ingin membership.\",\"path\":\"/backend/packages/members\",\"icon\":\"bi-hourglass-bottom\"}', '2026-10-06 14:07:14', '2026-10-04 02:48:58', '2026-10-06 14:07:14'),
('62c848cd-a1d4-46ff-b0fe-d6593f138caa', 'App\\Notifications\\ProofUploadedNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Bukti transfer diunggah\",\"message\":\"ORD-BNEJIR8QFB ?????? Wiku Pramesthi Bagaswara mengunggah bukti transfer (Rp 210.000). Silakan verifikasi.\",\"path\":\"/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be\",\"icon\":\"bi-file-earmark-check\"}', '2026-10-06 14:25:26', '2026-10-06 10:12:24', '2026-10-06 14:25:26'),
('6f95fb80-2e8d-441d-850a-741dcdc7d1bc', 'App\\Notifications\\ProgramStatusChanged', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"program_id\":null,\"judul_kegiatan\":null,\"old_status\":\"pending\",\"new_status\":\"hadir\",\"message\":\"Status program \'\' berubah dari \'pending\' menjadi \'hadir\'.\"}', '2026-09-02 06:50:51', '2026-05-24 21:21:26', '2026-09-02 06:50:51'),
('7040f077-8fe0-4701-b733-3e66a7bca72c', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Login tidak biasa terdeteksi\",\"message\":\"Wiku Pramesthi Bagaswara (wikupb@gmail.com) masuk dari 127.0.0.1 ?????? jam tidak biasa\",\"path\":\"/backend/security/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-04 21:51:30', '2026-10-04 21:49:14', '2026-10-04 21:51:30'),
('7630f926-083f-4c4a-a189-ff681e4ce6b9', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Login tidak biasa terdeteksi\",\"message\":\"ESPRO Property (esproproperty.bekasi@gmail.com) masuk dari 127.0.0.1 ?????? jam tidak biasa\",\"path\":\"/backend/security/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-04 21:51:30', '2026-10-04 21:42:47', '2026-10-04 21:51:30'),
('7edec836-807b-46bb-a50d-3f5475fa08bf', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-EYESFM7YQI \\u2014 Wiku Pramesthi Bagaswara memesan Paket Pernikahan Berdua \\u00e2\\u20ac\\u201d 25 Sesi Layanan (Rp 3.079.700)\",\"path\":\"\\/backend\\/orders\\/01a2022b-88b4-7b60-b679-74d234cde5fe\",\"icon\":\"bi-receipt\"}', '2026-11-22 07:34:10', '2026-11-22 07:32:17', '2026-11-22 07:34:10'),
('7f1ea7f4-d018-4ca5-84c8-944f04c8fb57', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '6704d63a-f8f0-4829-8a33-44e69e90d022', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-EYESFM7YQI \\u2014 Wiku Pramesthi Bagaswara memesan Paket Pernikahan Berdua \\u00e2\\u20ac\\u201d 25 Sesi Layanan (Rp 3.079.700)\",\"path\":\"\\/backend\\/orders\\/01a2022b-88b4-7b60-b679-74d234cde5fe\",\"icon\":\"bi-receipt\"}', NULL, '2026-11-22 07:32:17', '2026-11-22 07:32:17'),
('89cd6964-dea1-44b8-9c8c-ff7886de4786', 'App\\Notifications\\OrderExpiredNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Pesanan kedaluwarsa\",\"message\":\"ORD-9UJ1QLPEAW otomatis dibatalkan setelah 24 jam tanpa pembayaran. Silakan buat pesanan baru jika tetap ingin membership.\",\"path\":\"/backend/packages/members\",\"icon\":\"bi-hourglass-bottom\"}', '2026-10-06 14:07:14', '2026-10-04 02:48:58', '2026-10-06 14:07:14'),
('a2e5d335-3192-4c81-851a-c8b6ea33dabf', 'App\\Notifications\\MemberActiveNotification', 'App\\Models\\User', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', '{\"judul_kegiatan\":\"Membership diaktifkan\",\"message\":\"Pembayaran untuk ORD-I5HKUWFTWJ telah diverifikasi. Membership kamu sekarang aktif. Aktif hingga 11 Oct 2026.\",\"path\":\"/backend/orders/01a1049e-afda-7188-b2f9-9fa3b404f6d3\",\"icon\":\"bi-check-circle\"}', '2026-10-04 10:01:41', '2026-10-04 02:55:55', '2026-10-04 10:01:41'),
('a480fee2-6ed8-4579-88f0-fc1f2968b895', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-DHVCIG8ZQL ?????? Wiku Pramesthi Bagaswara memesan First Timer Package ?????? First Class Experience (Rp 210.000)\",\"path\":\"/backend/orders/01a11182-8463-7057-a0f4-8cf88279c6c2\",\"icon\":\"bi-receipt\"}', '2026-10-06 14:25:26', '2026-10-06 13:58:48', '2026-10-06 14:25:26'),
('a4cbf43e-868b-4970-9d5d-55f2f5e8df60', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '787b72ea-59d0-4d54-848b-c200bddafdd2', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-E5WX8OSWPO ?????? Wiku Pramesthi Bagaswara memesan First Timer Package ?????? First Class Experience (Rp 210.000)\",\"path\":\"/backend/orders/01a110cc-d5ec-7387-a3c5-87cde025d404\",\"icon\":\"bi-receipt\"}', NULL, '2026-10-06 10:40:22', '2026-10-06 10:40:22'),
('ac5b2d37-85ab-4267-b572-70b998039556', 'App\\Notifications\\ProgramStatusChanged', 'App\\Models\\User', 'dfd391fa-c167-4547-ad17-27d52d9ffc28', '{\"program_id\":null,\"judul_kegiatan\":null,\"old_status\":\"pending\",\"new_status\":\"hadir\",\"message\":\"Status program \'\' berubah dari \'pending\' menjadi \'hadir\'.\"}', '2026-05-24 21:51:12', '2026-05-24 21:48:19', '2026-05-24 21:51:12'),
('b0274b03-ffaa-462e-9098-423fe6c82b13', 'App\\Notifications\\OrderExpiredNotification', 'App\\Models\\User', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '{\"judul_kegiatan\":\"Pesanan kedaluwarsa\",\"message\":\"ORD-G07KVIJM1Y otomatis dibatalkan setelah 24 jam tanpa pembayaran. Silakan buat pesanan baru jika tetap ingin membership.\",\"path\":\"/backend/packages/members\",\"icon\":\"bi-hourglass-bottom\"}', '2026-10-06 14:07:14', '2026-10-04 02:48:58', '2026-10-06 14:07:14'),
('b041d44e-604a-472c-9899-f54f15ee3eff', 'App\\Notifications\\LoginAnehNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Login tidak biasa terdeteksi\",\"message\":\"Wiku Pramesthi Bagaswara (wikupb@gmail.com) masuk dari 127.0.0.1 ?????? jam tidak biasa\",\"path\":\"/backend/security/login-activity\",\"icon\":\"bi-shield-exclamation\"}', '2026-10-06 14:25:26', '2026-10-04 21:49:14', '2026-10-06 14:25:26'),
('b4638c57-0813-4336-9c41-433770e4a340', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-E5WX8OSWPO ?????? Wiku Pramesthi Bagaswara memesan First Timer Package ?????? First Class Experience (Rp 210.000)\",\"path\":\"/backend/orders/01a110cc-d5ec-7387-a3c5-87cde025d404\",\"icon\":\"bi-receipt\"}', '2026-10-06 14:25:26', '2026-10-06 10:40:22', '2026-10-06 14:25:26'),
('b5792f00-edaf-4fc4-9dc5-de91485bd877', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-EYESFM7YQI \\u2014 Wiku Pramesthi Bagaswara memesan Paket Pernikahan Berdua \\u00e2\\u20ac\\u201d 25 Sesi Layanan (Rp 3.079.700)\",\"path\":\"\\/backend\\/orders\\/01a2022b-88b4-7b60-b679-74d234cde5fe\",\"icon\":\"bi-receipt\"}', NULL, '2026-11-22 07:32:17', '2026-11-22 07:32:17'),
('bc6845e1-0998-4ec9-9209-7b07a4aac0af', 'App\\Notifications\\OrderBaruNotification', 'App\\Models\\User', '57621d3c-c299-4cd2-b96a-9b887752cb73', '{\"judul_kegiatan\":\"Pesanan baru diterima\",\"message\":\"ORD-EYESFM7YQI \\u2014 Wiku Pramesthi Bagaswara memesan Paket Pernikahan Berdua \\u00e2\\u20ac\\u201d 25 Sesi Layanan (Rp 3.079.700)\",\"path\":\"\\/backend\\/orders\\/01a2022b-88b4-7b60-b679-74d234cde5fe\",\"icon\":\"bi-receipt\"}', NULL, '2026-11-22 07:32:17', '2026-11-22 07:32:17'),
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
  `installment_schedule` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`installment_schedule`)),
  `installment_reminders_sent` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`installment_reminders_sent`)),
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

INSERT INTO `orders` (`uuid`, `user_uuid`, `order_number`, `type`, `package_uuid`, `package_option_uuid`, `event_date`, `amount`, `payment_scheme`, `total_amount`, `amount_paid`, `remaining_amount`, `balance_due_at`, `installment_schedule`, `installment_reminders_sent`, `balance_reminder_sent_at`, `status`, `pending_key`, `expired_at`, `paid_at`, `admin_note`, `assigned_admin_uuid`, `proof_image_path`, `proof_uploaded_at`, `sla_reminded_at`, `approval_snapshot`, `addons`, `promo_code`, `promo_discount`, `promo_snapshot`, `created_at`, `updated_at`) VALUES
('01a09b0a-4dd3-7318-9203-f08140b94b97', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-G07KVIJM1Y', 'package', NULL, NULL, NULL, 878000.00, 'full', 878000.00, 0.00, 878000.00, NULL, NULL, NULL, NULL, 'expired', NULL, '2026-09-14 13:52:18', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-09-13 13:52:18', '2026-10-04 02:48:58'),
('01a09b11-520c-7314-bc73-86d595a54f57', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-FG2TCGJ55W', 'package', NULL, NULL, NULL, 230000.00, 'full', 230000.00, 0.00, 230000.00, NULL, NULL, NULL, NULL, 'expired', NULL, '2026-09-14 13:59:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-09-13 13:59:58', '2026-10-04 02:48:58'),
('01a09b16-5931-73fb-866d-5e723294a477', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-C4LCIYGBRX', 'package', NULL, NULL, NULL, 230000.00, 'full', 230000.00, 0.00, 230000.00, NULL, NULL, NULL, NULL, 'expired', NULL, '2026-09-14 14:05:28', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-09-13 14:05:28', '2026-10-04 02:48:58'),
('01a09b1e-7b00-7391-bb4a-a50a77fde0e4', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-9UJ1QLPEAW', 'package', NULL, NULL, NULL, 230000.00, 'full', 230000.00, 0.00, 230000.00, NULL, NULL, NULL, NULL, 'expired', NULL, '2026-09-14 14:14:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-09-13 14:14:21', '2026-10-04 02:48:58'),
('01a09b1e-c44e-70ae-98b1-d19e19c7b4fe', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-QCQL0MKBRP', 'package', NULL, NULL, NULL, 230000.00, 'full', 230000.00, 0.00, 230000.00, NULL, NULL, NULL, NULL, 'expired', NULL, '2026-09-14 14:14:39', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-09-13 14:14:39', '2026-10-04 02:48:58'),
('01a1049e-afda-7188-b2f9-9fa3b404f6d3', 'c2187aff-68d0-40b9-a6f1-52738cee4ea4', 'ORD-I5HKUWFTWJ', 'package', '01a095f2-d286-7241-b566-8027529b8196', '01a09966-2965-7201-ab57-9f085e23bbc7', NULL, 210000.00, 'full', 210000.00, 210000.00, 0.00, NULL, NULL, NULL, NULL, 'paid', NULL, '2026-10-05 01:54:31', '2026-10-04 02:55:55', NULL, NULL, 'proofs/lTa9j1FbqtOaGW4LDEw95vVjQ9JDz24MwX2UnFMi.png', '2026-10-04 02:48:54', NULL, NULL, NULL, NULL, 0, NULL, '2026-10-04 01:54:31', '2026-10-04 02:55:55'),
('01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-BNEJIR8QFB', 'package', '01a095f2-d286-7241-b566-8027529b8196', '01a09966-2965-7201-ab57-9f085e23bbc7', NULL, 210000.00, 'full', 210000.00, 210000.00, 0.00, NULL, NULL, NULL, NULL, 'paid', NULL, '2026-10-07 10:12:11', '2026-10-06 10:13:14', NULL, NULL, 'proofs/WrmyZ098hpkUmpUTw9LmcOqDkUqtGOeLdNeyBjqM.png', '2026-10-06 10:12:24', NULL, NULL, NULL, NULL, 0, NULL, '2026-10-06 10:12:11', '2026-10-06 10:13:14'),
('01a110cc-d5ec-7387-a3c5-87cde025d404', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-E5WX8OSWPO', 'package', '01a095f2-d286-7241-b566-8027529b8196', '01a09966-2965-7201-ab57-9f085e23bbc7', NULL, 210000.00, 'full', 210000.00, 0.00, 210000.00, NULL, NULL, NULL, NULL, 'pending', NULL, '2026-10-07 10:40:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-10-06 10:40:21', '2026-10-06 10:40:21'),
('01a11182-8463-7057-a0f4-8cf88279c6c2', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-DHVCIG8ZQL', 'package', '01a095f2-d286-7241-b566-8027529b8196', '01a09966-2965-7201-ab57-9f085e23bbc7', NULL, 210000.00, 'full', 210000.00, 0.00, 210000.00, NULL, NULL, NULL, NULL, 'pending', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5|01a09966-2965-7201-ab57-9f085e23bbc7', '2026-10-07 13:58:48', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-10-06 13:58:48', '2026-10-06 13:58:48'),
('01a2022b-88b4-7b60-b679-74d234cde5fe', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', 'ORD-EYESFM7YQI', 'package', '01a098c6-e509-7154-869f-8d336d44ebc4', '01a09965-fbf0-7307-ba83-dfa1382cf270', '2026-11-29', 3079700.00, 'dp30', 5599000.00, 2000000.00, 3599000.00, '2026-10-29 17:00:00', '{\"policy\":\"equal_remaining_h90_h60_h30\",\"initial_due\":3079700,\"installments\":[{\"label\":\"Termin 1 & Termin 2 & Termin 3\",\"due_at\":\"2026-11-22\",\"amount\":2519300}]}', '[]', NULL, 'partially_paid', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5|01a09965-fbf0-7307-ba83-dfa1382cf270', '2026-11-23 07:32:17', NULL, NULL, NULL, 'proofs/P9pu9ebhhUT9CEzNQJMnzwAd7eRE0P4LP1WgdWYl.jpg', '2026-11-22 07:33:18', NULL, NULL, '[]', NULL, 0, '{\"code\":null,\"type\":null,\"value\":null,\"discount\":0,\"payment_scheme\":\"dp30\",\"scheme_label\":\"DP 30%\",\"full_amount\":5599000,\"booking_fee\":2000000,\"net_amount\":3599000,\"scheme_due\":1079700,\"due_now\":3079700}', '2026-11-22 07:32:17', '2026-11-22 07:36:30');

-- --------------------------------------------------------

--
-- Table structure for table `order_change_requests`
--

CREATE TABLE `order_change_requests` (
  `uuid` char(36) NOT NULL,
  `order_uuid` char(36) NOT NULL,
  `requested_by_uuid` char(36) NOT NULL,
  `change_type` varchar(30) NOT NULL,
  `requested_event_date` date DEFAULT NULL,
  `addon_uuid` char(36) DEFAULT NULL,
  `addon_quantity` smallint(5) UNSIGNED DEFAULT NULL,
  `requested_price` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `customer_note` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `admin_note` text DEFAULT NULL,
  `reviewed_by_uuid` char(36) DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_documents`
--

CREATE TABLE `order_documents` (
  `uuid` char(36) NOT NULL,
  `order_uuid` char(36) NOT NULL,
  `payment_uuid` char(36) DEFAULT NULL,
  `document_key` varchar(255) NOT NULL,
  `document_number` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `snapshot` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`snapshot`)),
  `issued_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_documents`
--

INSERT INTO `order_documents` (`uuid`, `order_uuid`, `payment_uuid`, `document_key`, `document_number`, `type`, `snapshot`, `issued_at`, `created_at`, `updated_at`) VALUES
('01a2022f-6638-7b64-a169-61d506ec2829', '01a2022b-88b4-7b60-b679-74d234cde5fe', '01a2022c-79be-73c6-af98-2b72af73e0b3', '01a2022b-88b4-7b60-b679-74d234cde5fe:contract', 'CTR-ORD-EYESFM7YQI', 'contract', '{\"order_number\":\"ORD-EYESFM7YQI\",\"customer_name\":\"Wiku Pramesthi Bagaswara\",\"package_name\":\"Paket Pernikahan Berdua\",\"option_name\":\"25 Sesi Layanan\",\"event_date\":\"29 November 2026\",\"total_amount\":5599000,\"amount_paid\":2000000,\"remaining_amount\":3599000,\"payment_amount\":2000000,\"payment_date\":\"22 November 2026 14:36\",\"issued_at\":\"22 November 2026\",\"contract_template\":\"PERJANJIAN LAYANAN WEDDING ORGANIZER\\nNomor dokumen: {{document_number}}\\nTanggal diterbitkan: {{issued_at}}\\n\\nPenyedia layanan: Sagita Wedding Organizer\\nPelanggan: {{customer_name}}\\nNomor pesanan: {{order_number}}\\n\\nLayanan yang disepakati: {{package_name}} - {{option_name}}\\nTanggal acara: {{event_date}}\\nNilai pesanan: {{total_amount}}\\nPembayaran terverifikasi: {{amount_paid}}\\nSisa pembayaran: {{remaining_amount}}\\n\\nRincian layanan mengikuti paket dan layanan tambahan yang tercatat pada pesanan.\\nPerubahan tanggal atau layanan harus diajukan melalui pesanan dan disetujui admin.\\nKetentuan pembayaran dan pembatalan mengikuti kebijakan yang ditampilkan saat pemesanan.\\n\\nDokumen ini mencatat rincian pesanan dan belum menggantikan persetujuan\\/tanda tangan para pihak.\"}', '2026-11-22 07:36:30', '2026-11-22 07:36:30', '2026-11-22 07:36:30'),
('01a2022f-663d-74fd-bbef-569e9fe9eef3', '01a2022b-88b4-7b60-b679-74d234cde5fe', '01a2022c-79be-73c6-af98-2b72af73e0b3', '01a2022b-88b4-7b60-b679-74d234cde5fe:invoice', 'INV-ORD-EYESFM7YQI', 'invoice', '{\"order_number\":\"ORD-EYESFM7YQI\",\"customer_name\":\"Wiku Pramesthi Bagaswara\",\"package_name\":\"Paket Pernikahan Berdua\",\"option_name\":\"25 Sesi Layanan\",\"event_date\":\"29 November 2026\",\"total_amount\":5599000,\"amount_paid\":2000000,\"remaining_amount\":3599000,\"payment_amount\":2000000,\"payment_date\":\"22 November 2026 14:36\",\"issued_at\":\"22 November 2026\"}', '2026-11-22 07:36:30', '2026-11-22 07:36:30', '2026-11-22 07:36:30');

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
-- Table structure for table `order_vendor_assignments`
--

CREATE TABLE `order_vendor_assignments` (
  `uuid` char(36) NOT NULL,
  `order_uuid` char(36) NOT NULL,
  `vendor_uuid` char(36) NOT NULL,
  `scope` varchar(80) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'assigned',
  `notes` text DEFAULT NULL,
  `assigned_by` char(36) DEFAULT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_vendor_assignments`
--

INSERT INTO `order_vendor_assignments` (`uuid`, `order_uuid`, `vendor_uuid`, `scope`, `status`, `notes`, `assigned_by`, `accepted_at`, `completed_at`, `created_at`, `updated_at`) VALUES
('01a2022f-f6aa-73ce-a44a-72c39611afa3', '01a2022b-88b4-7b60-b679-74d234cde5fe', '4a85c36a-caa6-4cc9-9ab6-47f77c0a9a67', 'dekorasi', 'assigned', 'dasd', '787b72ea-59d0-4d54-848b-c200bddafdd2', NULL, NULL, '2026-11-22 07:37:07', '2026-11-22 07:37:07');

-- --------------------------------------------------------

--
-- Table structure for table `order_workflow_settings`
--

CREATE TABLE `order_workflow_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contract_template` longtext NOT NULL,
  `checklist_template` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`checklist_template`)),
  `updated_by` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_workflow_settings`
--

INSERT INTO `order_workflow_settings` (`id`, `contract_template`, `checklist_template`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'PERJANJIAN LAYANAN WEDDING ORGANIZER\nNomor dokumen: {{document_number}}\nTanggal diterbitkan: {{issued_at}}\n\nPenyedia layanan: Sagita Wedding Organizer\nPelanggan: {{customer_name}}\nNomor pesanan: {{order_number}}\n\nLayanan yang disepakati: {{package_name}} - {{option_name}}\nTanggal acara: {{event_date}}\nNilai pesanan: {{total_amount}}\nPembayaran terverifikasi: {{amount_paid}}\nSisa pembayaran: {{remaining_amount}}\n\nRincian layanan mengikuti paket dan layanan tambahan yang tercatat pada pesanan.\nPerubahan tanggal atau layanan harus diajukan melalui pesanan dan disetujui admin.\nKetentuan pembayaran dan pembatalan mengikuti kebijakan yang ditampilkan saat pemesanan.\n\nDokumen ini mencatat rincian pesanan dan belum menggantikan persetujuan/tanda tangan para pihak.', '[{\"title\":\"Konfirmasi detail acara\",\"description\":\"Pastikan lokasi, waktu, dan kontak utama acara sudah benar.\",\"weeks_before_event\":16},{\"title\":\"Finalisasi jumlah tamu\",\"description\":\"Perbarui perkiraan jumlah tamu dan kebutuhan konsumsi.\",\"weeks_before_event\":12},{\"title\":\"Konfirmasi susunan acara\",\"description\":\"Tinjau susunan acara bersama tim wedding organizer.\",\"weeks_before_event\":8},{\"title\":\"Finalisasi kebutuhan vendor\",\"description\":\"Pastikan kebutuhan dekorasi, dokumentasi, dan vendor tambahan.\",\"weeks_before_event\":4},{\"title\":\"Konfirmasi akhir acara\",\"description\":\"Lakukan konfirmasi akhir detail dan jadwal acara.\",\"weeks_before_event\":1}]', NULL, '2026-11-22 06:32:36', '2026-11-22 06:32:36');

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
(2, '01a095f2-d286-7241-b566-8027529b8196', 'Paket Rencana Pernikahan', 'first-timer-package', 'A welcoming first step into your yoga journey at Yogaroots. Enjoy a guided studio session designed to help you feel comfortable, refreshed, and ready to move.', NULL, NULL, NULL, NULL, 1, 'active', 1, NULL, 0, '2026-09-12 14:08:33', '2026-11-22 07:30:14', 0.00),
(3, '01a09615-73d8-7345-a20f-2d5eeb6b1d1a', 'Paket Pernikahan Lengkap', 'unlimited-package', 'Practice freely with unlimited access to Yogaroots classes. Move at your own pace, explore different styles, and make yoga part of your everyday routine.', NULL, NULL, NULL, NULL, 0, 'active', 1, NULL, 0, '2026-09-12 14:46:23', '2026-11-22 07:30:14', 0.00),
(4, '01a098c6-e509-7154-869f-8d336d44ebc4', 'Paket Pernikahan Berdua', 'sharing-package', 'A flexible membership designed to be shared with someone special. Enjoy Yogaroots classes together and make your yoga journey a shared experience.', NULL, NULL, NULL, NULL, 0, 'active', 1, NULL, 0, '2026-09-13 03:19:26', '2026-11-22 07:30:14', 0.00),
(6, '01a098d2-a02c-72ee-a8eb-b9dc6786761d', 'Konsultasi Pernikahan Personal', '1-on-1-private-class', 'A personalized yoga experience designed around you. Practice one-on-one with our instructor, receive focused guidance, and build a practice that supports your individual goals.', NULL, NULL, NULL, NULL, 0, 'active', 1, NULL, 0, '2026-09-13 03:32:15', '2026-11-22 07:30:14', 0.00),
(7, '01a098e0-878e-7296-b1c0-a550bf0327b0', 'Konsultasi Pernikahan Pasangan', 'couples-private-class', 'A private yoga experience to enjoy together. Practice side by side with personalized guidance from our instructor, deepen your connection, and make every session a meaningful shared experience.', NULL, NULL, NULL, NULL, 0, 'active', 1, NULL, 0, '2026-09-13 03:47:26', '2026-11-22 07:30:14', 0.00);

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
('01a09615-73f9-72ee-85e5-f2be4ef4c502', '01a09615-73d8-7345-a20f-2d5eeb6b1d1a', 'Seluruh Layanan', 50, 3000000, 2400000, 1, 'month', 0, 1, '2026-09-12 14:46:23', '2026-11-22 07:30:14'),
('01a098de-ec50-73bf-b928-c37f31d37568', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', 'Satu Kali Konsultasi', 1, 1000000, NULL, 1, 'week', 0, 1, '2026-09-13 03:45:41', '2026-11-22 07:30:14'),
('01a098de-ec7d-7352-9076-67a2defe3fde', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', '5 Kali Konsultasi', 1, 4500000, NULL, 5, 'week', 1, 1, '2026-09-13 03:45:41', '2026-11-22 07:30:14'),
('01a098de-ec7f-7275-a7f8-be27d7caa4ff', '01a098d2-a02c-72ee-a8eb-b9dc6786761d', '10 Kali Konsultasi', 1, 8100000, NULL, 10, 'week', 2, 1, '2026-09-13 03:45:41', '2026-11-22 07:30:14'),
('01a098e0-8795-71b4-84a4-de9c8a0b517e', '01a098e0-878e-7296-b1c0-a550bf0327b0', 'Satu Kali Konsultasi', 2, 1200000, NULL, 1, 'week', 0, 1, '2026-09-13 03:47:26', '2026-11-22 07:30:14'),
('01a098e0-8797-7309-954d-6caac6a8f4ee', '01a098e0-878e-7296-b1c0-a550bf0327b0', '5 Kali Konsultasi', 2, 5400000, NULL, 5, 'week', 1, 1, '2026-09-13 03:47:26', '2026-11-22 07:30:14'),
('01a098e0-8798-707b-b260-c1379231361e', '01a098e0-878e-7296-b1c0-a550bf0327b0', '10 Kali Konsultasi', 2, 9720000, NULL, 10, 'week', 2, 1, '2026-09-13 03:47:26', '2026-11-22 07:30:14'),
('01a09965-fbf0-7307-ba83-dfa1382cf270', '01a098c6-e509-7154-869f-8d336d44ebc4', '25 Sesi Layanan', 50, 6999000, 5599000, 1, 'month', 0, 1, '2026-09-13 06:13:12', '2026-11-22 07:30:14'),
('01a09966-2965-7201-ab57-9f085e23bbc7', '01a095f2-d286-7241-b566-8027529b8196', 'Pengalaman Pertama', 50, 210000, NULL, 1, 'week', 0, 1, '2026-09-13 06:13:24', '2026-11-22 07:30:14');

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
('01a110b4-000d-7377-9dab-512dd7adc04c', '01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', 'full', 'manual_transfer', NULL, 'Bank Transfer', 210000.00, 210000.00, 'paid', 'verified', NULL, '2026-10-06 10:13:14', '2026-10-06 10:13:14', NULL, 'proofs/WrmyZ098hpkUmpUTw9LmcOqDkUqtGOeLdNeyBjqM.png', '2026-10-06 10:12:24', NULL, '2026-10-06 10:13:14', '2026-10-06 10:13:14'),
('01a2022c-79be-73c6-af98-2b72af73e0b3', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'dp', 'manual_transfer', NULL, 'Pembayaran Awal', 2000000.00, 2000000.00, 'paid', 'approved', '787b72ea-59d0-4d54-848b-c200bddafdd2', '2026-11-22 07:36:30', '2026-11-22 07:36:30', NULL, 'proofs/P9pu9ebhhUT9CEzNQJMnzwAd7eRE0P4LP1WgdWYl.jpg', '2026-11-22 07:33:18', NULL, '2026-11-22 07:33:18', '2026-11-22 07:36:30');

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
(1, 'dashboard.index', 'dashboard.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(2, 'user.index', 'user.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(3, 'user.store', 'user.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(4, 'user.update', 'user.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(5, 'user.destroy', 'user.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(6, 'menu.index', 'menu-group.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(7, 'menu.store', 'menu-group.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(8, 'menu.update', 'menu-group.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(9, 'menu.destroy', 'menu-group.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(10, 'menu.item.index', 'menu-item.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(11, 'menu.item.store', 'menu-item.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(12, 'menu.item.update', 'menu-item.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(13, 'menu.item.destroy', 'menu-item.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(14, 'route.index', 'route.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(15, 'route.store', 'route.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(16, 'route.update', 'route.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(17, 'route.destroy', 'route.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(18, 'role.index', 'role.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(19, 'role.store', 'role.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(20, 'role.update', 'role.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(21, 'role.destroy', 'role.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(22, 'permission.index', 'permission.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(23, 'permission.store', 'permission.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(24, 'permission.update', 'permission.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(25, 'permission.destroy', 'permission.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(26, 'faq.index', 'faq.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(27, 'faq.store', 'faq.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(28, 'faq.update', 'faq.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(29, 'faq.destroy', 'faq.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(34, 'categories.destroy', 'categories.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(35, 'categories.index', 'categories.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(36, 'categories.store', 'categories.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(37, 'categories.update', 'categories.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(42, 'banner.destroy', 'banner.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(43, 'banner.index', 'banner.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(44, 'banner.store', 'banner.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(45, 'banner.update', 'banner.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(46, 'articles.create', 'articles.create', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(47, 'articles.destroy', 'articles.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(48, 'articles.index', 'articles.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(49, 'articles.store', 'articles.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(50, 'articles.update', 'articles.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(55, 'dashboard.submitSumber', 'dashboard.submitSumber', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(85, 'account.index', 'account.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(90, 'account.update', 'account.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(120, 'filedownload.index', 'filedownload.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(125, 'filedownload.destroy', 'filedownload.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(130, 'filedownload.store', 'filedownload.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(135, 'filedownload.update', 'filedownload.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(146, 'vendor.destroy', 'vendor.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(147, 'pages.create', 'pages.create', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(148, 'pages.index', 'pages.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(149, 'pages.store', 'pages.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(150, 'pages.destroy', 'pages.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(151, 'pages.update', 'pages.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(152, 'vendor.index', 'vendor.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(153, 'vendor.store', 'vendor.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(154, 'vendor.update', 'vendor.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(155, 'testimonial.destroy', 'testimonial.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(156, 'testimonial.index', 'testimonial.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(157, 'testimonial.store', 'testimonial.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(158, 'testimonial.update', 'testimonial.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(159, 'events.destroy', 'events.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(160, 'events.index', 'events.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(161, 'events.store', 'events.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(162, 'events.update', 'events.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(163, 'vendors_categories.destroy', 'vendors_categories.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(164, 'vendors_categories.index', 'vendors_categories.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(165, 'vendors_categories.store', 'vendors_categories.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(166, 'vendors_categories.update', 'vendors_categories.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(167, 'packages.destroy', 'package.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(168, 'packages.index', 'package.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(169, 'packages.store', 'package.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(170, 'packages.update', 'package.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(185, 'pengguna.index', 'pengguna.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(186, 'pengguna.export', 'pengguna.export', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(187, 'venues.create', 'venues.create', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(188, 'venues.index', 'venues.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(189, 'venues.destroy', 'venues.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(190, 'venues.update', 'venues.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(191, 'venues.store', 'venues.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(192, 'vendor.mobile', 'vendor.mobile', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(193, 'checkout.package', 'checkout.package', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(194, 'orders.index', 'orders.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(195, 'orders.store', 'orders.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(196, 'orders.show', 'orders.show', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(197, 'security.login-activity.index', 'login-activity.index', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(198, 'security.login-activity.bulkDestroy', 'login-activity.destroy', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(199, 'security.login-activity.clear', 'login-activity.destroy', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(200, 'security.login-activity.destroy', 'login-activity.destroy', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(201, 'security.login-lockout.index', 'login-lockout.index', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(202, 'security.login-lockout.bulkDestroy', 'login-lockout.destroy', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(203, 'security.login-lockout.destroy', 'login-lockout.destroy', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(204, 'security.audit-log.index', 'audit-log.index', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(205, 'security.audit-log.exportPdf', 'audit-log.index', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(206, 'security.audit-log.bulkDestroy', 'audit-log.destroy', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(207, 'security.audit-log.clear', 'audit-log.destroy', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(208, 'security.audit-log.destroy', 'audit-log.destroy', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(209, 'security.failed-login.index', 'failed-login.index', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(210, 'security.failed-login.bulkDestroy', 'failed-login.destroy', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(211, 'security.failed-login.clear', 'failed-login.destroy', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(212, 'security.failed-login.destroy', 'failed-login.destroy', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(213, 'website-identity.index', 'website-identity.index', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(214, 'website-identity.update', 'website-identity.update', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(216, 'banner.storeAlbum', 'banner.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(217, 'banner.loadMore', 'banner.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(218, 'banner.fotoPicker', 'banner.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(219, 'banner.albumFotos', 'banner.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(220, 'banner.modal', 'banner.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(221, 'banner.updateAlbum', 'banner.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(222, 'banner.destroyAlbum', 'banner.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(223, 'articles.bulkDestroy', 'articles.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(224, 'memberships.index', 'memberships.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(225, 'memberships.show', 'memberships.show', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(231, 'orders.approve', 'orders.approve', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(232, 'orders.reject', 'orders.reject', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(233, 'orders.proof', 'orders.proof', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(234, 'orders.proof.show', 'orders.proof', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(235, 'vendor.archive', 'vendor.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(236, 'vendor.restore', 'vendor.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(237, 'konsultasi.index', 'konsultasi.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(238, 'konsultasi.create', 'konsultasi.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(239, 'konsultasi.store', 'konsultasi.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(240, 'konsultasi.show', 'konsultasi.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(241, 'konsultasi.edit', 'konsultasi.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(242, 'konsultasi.update', 'konsultasi.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(243, 'konsultasi.destroy', 'konsultasi.delete', 1, 'Akses konsultasi.destroy', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(244, 'pengguna.update-status', 'pengguna.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(245, 'konsultasi.update-lead', 'konsultasi.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(246, 'konsultasi.pdf-preview', 'konsultasi.export', 1, 'Akses konsultasi.pdf-preview', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(247, 'konsultasi.pdf-stream', 'konsultasi.export', 1, 'Akses konsultasi.pdf-stream', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(248, 'konsultasi.pdf-download', 'konsultasi.export', 1, 'Akses konsultasi.pdf-download', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(256, 'konsultasi.bulk', 'konsultasi.bulk', 1, 'Akses konsultasi.bulk', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(257, 'konsultasi.export', 'konsultasi.export', 1, 'Akses konsultasi.export', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(258, 'konsultasi.kanban', 'konsultasi.index', 1, 'Papan kanban konsultasi', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(259, 'konsultasi.calendar', 'konsultasi.index', 1, 'Kalender jadwal konsultasi', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(260, 'konsultasi.duplicates', 'konsultasi.merge', 1, 'Akses konsultasi.duplicates', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(261, 'konsultasi.merge', 'konsultasi.merge', 1, 'Akses konsultasi.merge', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(262, 'konsultasi.remind', 'konsultasi.index', 1, 'Kirim pengingat konsultasi', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(263, 'konsultasi.import', 'konsultasi.import', 1, 'Akses konsultasi.import', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(264, 'konsultasi.saved-filters', 'konsultasi.index', 1, 'Akses konsultasi.saved-filters', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(265, 'konsultasi.whatsapp', 'konsultasi.index', 1, 'Akses konsultasi.whatsapp', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(266, 'konsultasi.calendar-ics', 'konsultasi.index', 1, 'Akses konsultasi.calendar-ics', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(267, 'konsultasi.import.template', 'konsultasi.import', 1, 'Akses konsultasi.import.template', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(268, 'konsultasi.import.preview', 'konsultasi.import', 1, 'Akses konsultasi.import.preview', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(269, 'konsultasi.import.commit', 'konsultasi.import', 1, 'Akses konsultasi.import.commit', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(270, 'konsultasi.saved-filters.store', 'konsultasi.index', 1, 'Akses konsultasi.saved-filters.store', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(271, 'konsultasi.saved-filters.apply', 'konsultasi.index', 1, 'Akses konsultasi.saved-filters.apply', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(272, 'konsultasi.saved-filters.destroy', 'konsultasi.index', 1, 'Akses konsultasi.saved-filters.destroy', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(273, 'konsultasi.reorder', 'konsultasi.index', 1, 'Akses konsultasi.reorder', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(274, 'konsultasi.bulk-undo', 'konsultasi.bulk', 1, 'Akses konsultasi.bulk-undo', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(275, 'konsultasi.saved-filters.default', 'konsultasi.index', 1, 'Akses konsultasi.saved-filters.default', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(276, 'dashboard.refresh', 'dashboard.index', 1, 'Akses dashboard.refresh', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(277, 'dashboard.export', 'dashboard.index', 1, 'Akses dashboard.export', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(278, 'dashboard.export-pdf', 'dashboard.index', 1, 'Akses dashboard.export-pdf', '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(279, 'email-setting.index', 'email-setting.index', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(280, 'email-setting.update', 'email-setting.update', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(282, 'orders.bulk', 'orders.bulk', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(283, 'orders.exportCsv', 'orders.exportCsv', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(284, 'orders.report', 'orders.exportCsv', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(285, 'orders.exportPdf', 'orders.exportCsv', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(286, 'orders.audit', 'orders.audit', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(287, 'orders.whatsapp', 'orders.whatsapp', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(288, 'orders.saved-filters', 'orders.savedFilters', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(289, 'orders.saved-filters.store', 'orders.savedFilters', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(290, 'orders.saved-filters.apply', 'orders.savedFilters', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(291, 'orders.saved-filters.default', 'orders.savedFilters', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(292, 'orders.saved-filters.destroy', 'orders.savedFilters', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(293, 'orders.bulk-undo', 'orders.bulkUndo', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(294, 'orders.claim', 'orders.assign', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(295, 'orders.quick-view', 'orders.quickView', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(296, 'package-promos.index', 'package.promo.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(297, 'package-promos.store', 'package.promo.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(298, 'package-promos.update', 'package.promo.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(299, 'package-promos.destroy', 'package.promo.destroy', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(300, 'dashboard.recommendations', 'dashboard.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(301, 'search', 'dashboard.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(302, 'account.create', 'account.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(303, 'account.store', 'account.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(304, 'account.edit', 'account.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(305, 'account.show', 'account.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(306, 'account.destroy', 'account.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(307, 'faq.create', 'faq.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(308, 'faq.show', 'faq.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(309, 'faq.edit', 'faq.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(310, 'categories.create', 'categories.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(311, 'categories.show', 'categories.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(312, 'categories.edit', 'categories.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(313, 'banner.create', 'banner.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(314, 'banner.show', 'banner.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(315, 'banner.edit', 'banner.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(316, 'articles.edit', 'articles.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(317, 'filedownload.download', 'filedownload.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(318, 'packages.create', 'package.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(319, 'packages.edit', 'package.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(320, 'packages.show', 'package.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(321, 'packages.duplicate', 'package.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(322, 'packages.bulk', 'package.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(323, 'packages.export', 'package.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(324, 'packages.preview', 'package.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(325, 'packages.reorder', 'package.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(326, 'packages.toggle-status', 'package.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(327, 'packages.member', 'package.member', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(328, 'packages.quotation', 'package.member', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(329, 'packages.quotation.preview', 'package.member', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(330, 'packages.quotation.download', 'package.member', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(331, 'packages.compare.index', 'package.member', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(332, 'packages.compare.add', 'package.member', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(333, 'packages.compare.remove', 'package.member', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(334, 'packages.compare.clear', 'package.member', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(335, 'packages.wishlist.index', 'package.member', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(336, 'packages.wishlist.toggle', 'package.member', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(337, 'pages.show', 'pages.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(338, 'pages.edit', 'pages.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(339, 'pages.updateSidebar', 'pages.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(340, 'testimonial.create', 'testimonial.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(341, 'testimonial.show', 'testimonial.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(342, 'testimonial.edit', 'testimonial.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(343, 'events.create', 'events.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(344, 'events.show', 'events.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(345, 'events.edit', 'events.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(346, 'venues.edit', 'venues.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(347, 'vendor.create', 'vendor.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(348, 'vendor.show', 'vendor.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(349, 'vendor.edit', 'vendor.update', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(350, 'memberships.export', 'memberships.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(351, 'orders.analytics', 'orders.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(352, 'orders.change-requests.index', 'orders.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(353, 'orders.change-requests.review', 'orders.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(354, 'orders.change-requests.store', 'orders.store', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(355, 'orders.checklist.update', 'orders.show', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(356, 'orders.documents.preview', 'orders.show', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(357, 'orders.documents.download', 'orders.show', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(358, 'orders.workflow-settings.edit', 'orders.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(359, 'orders.workflow-settings.update', 'orders.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(360, 'orders.saved-filters.pin', 'orders.savedFilters', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(361, 'orders.saved-filters.move', 'orders.savedFilters', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(362, 'orders.remind-sla', 'orders.audit', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(363, 'orders.activity', 'orders.audit', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(364, 'orders.reorder', 'orders.index', 1, NULL, '2026-11-22 13:23:14', '2026-11-22 13:23:14'),
(365, 'orders.payments.store', 'orders.store', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(366, 'orders.payments.approve', 'orders.approve', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(367, 'orders.payments.reject', 'orders.reject', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(368, 'orders.payments.proof', 'orders.proof', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(369, 'orders.vendors.assign', 'orders.assign', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(370, 'orders.vendors.destroy', 'orders.assign', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15'),
(371, 'orders.vendors.status', 'orders.assign', 1, NULL, '2026-11-22 13:23:15', '2026-11-22 13:23:15');

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
('K2ueKHT6lXaX74lfH2QyvuRW2s15rgMQc89Qu2N0', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUUxxVUVHUXRLVWpibWh4N2tiRkd2N0R2OEZzSnU4ZlR5blI3YmxmdiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovL2xvY2FsaG9zdDo4MDAwL2JhY2tlbmQvZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hdXRoL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9fQ==', 1795360320),
('te0UpMs20o5PifJu60kbhsBTfoVnfYxFAA9Twq4B', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.147.0 Chrome/150.0.7871.250 Electron/43.8.9 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoicmZSZUJCNXVNZkpqdDNJRnEzRjZyejVsWUdMVGtQeUpWREVQTm9DSyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9iYWNrZW5kL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoxNToiZGFzaGJvYXJkLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO3M6MzY6IjcwZTE1YjlmLTUzNWEtNDJhMi04ZWEwLTlhMjZhZDc5NTJlNSI7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImRhNzg5ZWU0ZDcyNzliOTljNmIxMGMyNmU5MGNmZGYwYjdlYzY2MjFkYTc3NmZkZTQ2YjY2NjJkNTAyMjI0Y2UiO3M6MTM6Imxhc3RfYWN0aXZpdHkiO2k6MTc5NTM1NDgwNTtzOjExOiJtZW1iZXJfdmlldyI7czo2OiJtb2JpbGUiO30=', 1795354805),
('vp0pUOYyHSmIgLS4RVHeDmfQOyzi5I7aCHX2JQ1P', '787b72ea-59d0-4d54-848b-c200bddafdd2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoiMDJQQ1FUVERKTDlCa1B0ZUFnNDBPYnpwUHRVdFlTTTRIanRaeVBLZiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM5OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYmFja2VuZC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImRhc2hib2FyZC5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiI3ODdiNzJlYS01OWQwLTRkNTQtODQ4Yi1jMjAwYmRkYWZkZDIiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjZmM1YmIwMThkYmVhMmVmZTVjZTQ4YmFiYzVlODEyNTU5NDBmMGEyODZkNzc3MmE1YzVlODNjZWIyZmMyYmM5IjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUzNTAyMDc7fQ==', 1795350207),
('wBlBj2XOLrhlVz8RtuVNGqZ1oHAktwzsfI2y2NHn', '787b72ea-59d0-4d54-848b-c200bddafdd2', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo3OntzOjY6Il90b2tlbiI7czo0MDoidXJGbWFoMXg1aEY4cUZldVJVZURPdFZlYUg4dWtBRVlvSkptQzVnRiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYmFja2VuZC9iYW5uZXIiO3M6NToicm91dGUiO3M6MTI6ImJhbm5lci5pbmRleCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtzOjM2OiI3ODdiNzJlYS01OWQwLTRkNTQtODQ4Yi1jMjAwYmRkYWZkZDIiO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJjZmM1YmIwMThkYmVhMmVmZTVjZTQ4YmFiYzVlODEyNTU5NDBmMGEyODZkNzc3MmE1YzVlODNjZWIyZmMyYmM5IjtzOjEzOiJsYXN0X2FjdGl2aXR5IjtpOjE3OTUzNTg4NDY7fQ==', 1795358847);

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
(433, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 03:02:52', '2026-11-22 03:02:52', '2026-11-22 03:02:52'),
(434, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 03:40:29', '2026-11-22 03:40:29', '2026-11-22 03:40:29'),
(435, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/formulir-konsultasi', 'desktop', '2026-11-22 03:40:31', '2026-11-22 03:40:31', '2026-11-22 03:40:31'),
(436, '2d917a76ca6f1c986bb47a6b0417724d4b9ee44876c78cb1b2988258a485f975', '/formulir-konsultasi', 'desktop', '2026-11-22 03:46:21', '2026-11-22 03:46:21', '2026-11-22 03:46:21'),
(437, '2d917a76ca6f1c986bb47a6b0417724d4b9ee44876c78cb1b2988258a485f975', '/', 'desktop', '2026-11-22 03:46:28', '2026-11-22 03:46:28', '2026-11-22 03:46:28'),
(438, '2d917a76ca6f1c986bb47a6b0417724d4b9ee44876c78cb1b2988258a485f975', '/', 'desktop', '2026-11-22 03:47:14', '2026-11-22 03:47:14', '2026-11-22 03:47:14'),
(439, '2d917a76ca6f1c986bb47a6b0417724d4b9ee44876c78cb1b2988258a485f975', '/', 'desktop', '2026-11-22 03:47:28', '2026-11-22 03:47:28', '2026-11-22 03:47:28'),
(440, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 04:10:59', '2026-11-22 04:10:59', '2026-11-22 04:10:59'),
(441, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 06:15:28', '2026-11-22 06:15:28', '2026-11-22 06:15:28'),
(442, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'desktop', '2026-11-22 06:15:40', '2026-11-22 06:15:40', '2026-11-22 06:15:40'),
(443, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/packages', 'desktop', '2026-11-22 06:15:41', '2026-11-22 06:15:41', '2026-11-22 06:15:41'),
(444, '6c9718f5436ef6f52e6d128b4805df991dfda600d63a74010f1b4c9a8c689258', '/packages', 'desktop', '2026-11-22 06:21:08', '2026-11-22 06:21:08', '2026-11-22 06:21:08'),
(445, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/packages', 'desktop', '2026-11-22 06:28:41', '2026-11-22 06:28:41', '2026-11-22 06:28:41'),
(446, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/packages', 'desktop', '2026-11-22 06:29:53', '2026-11-22 06:29:53', '2026-11-22 06:29:53'),
(447, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/packages', 'desktop', '2026-11-22 06:30:57', '2026-11-22 06:30:57', '2026-11-22 06:30:57'),
(448, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/packages', 'desktop', '2026-11-22 06:32:25', '2026-11-22 06:32:25', '2026-11-22 06:32:25'),
(449, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/packages', 'desktop', '2026-11-22 06:36:51', '2026-11-22 06:36:51', '2026-11-22 06:36:51'),
(450, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'desktop', '2026-11-22 06:36:54', '2026-11-22 06:36:54', '2026-11-22 06:36:54'),
(451, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 06:36:59', '2026-11-22 06:36:59', '2026-11-22 06:36:59'),
(452, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/faq', 'desktop', '2026-11-22 06:37:01', '2026-11-22 06:37:01', '2026-11-22 06:37:01'),
(453, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/venues', 'desktop', '2026-11-22 06:37:04', '2026-11-22 06:37:04', '2026-11-22 06:37:04'),
(454, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-22 06:37:07', '2026-11-22 06:37:07', '2026-11-22 06:37:07'),
(455, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-22 06:39:06', '2026-11-22 06:39:06', '2026-11-22 06:39:06'),
(456, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/vendors', 'desktop', '2026-11-22 07:24:38', '2026-11-22 07:24:38', '2026-11-22 07:24:38'),
(457, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 07:24:39', '2026-11-22 07:24:39', '2026-11-22 07:24:39'),
(458, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/event', 'desktop', '2026-11-22 07:24:47', '2026-11-22 07:24:47', '2026-11-22 07:24:47'),
(459, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 07:43:37', '2026-11-22 07:43:37', '2026-11-22 07:43:37'),
(460, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/packages', 'desktop', '2026-11-22 07:43:50', '2026-11-22 07:43:50', '2026-11-22 07:43:50'),
(461, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/packages/1-on-1-private-class', 'desktop', '2026-11-22 07:44:01', '2026-11-22 07:44:01', '2026-11-22 07:44:01'),
(462, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 11:46:14', '2026-11-22 11:46:14', '2026-11-22 11:46:14'),
(463, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 13:40:34', '2026-11-22 13:40:34', '2026-11-22 13:40:34'),
(464, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/packages', 'desktop', '2026-11-22 13:50:02', '2026-11-22 13:50:02', '2026-11-22 13:50:02'),
(465, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 13:50:51', '2026-11-22 13:50:51', '2026-11-22 13:50:51'),
(466, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/packages', 'desktop', '2026-11-22 13:51:48', '2026-11-22 13:51:48', '2026-11-22 13:51:48'),
(467, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/formulir-konsultasi', 'desktop', '2026-11-22 13:54:56', '2026-11-22 13:54:56', '2026-11-22 13:54:56'),
(468, '13d4fe4c83e5e6f4696586b70dd352af049f4379390e3d9f7019861b14af9a9e', '/packages', 'desktop', '2026-11-22 13:56:03', '2026-11-22 13:56:03', '2026-11-22 13:56:03'),
(469, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'desktop', '2026-11-22 13:57:18', '2026-11-22 13:57:18', '2026-11-22 13:57:18'),
(470, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/about', 'desktop', '2026-11-22 13:59:45', '2026-11-22 13:59:45', '2026-11-22 13:59:45'),
(471, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/about', 'desktop', '2026-11-22 14:00:59', '2026-11-22 14:00:59', '2026-11-22 14:00:59'),
(472, 'f7127a4a3644635c4277abd00dfde26d22d2f30d1669ade0816be7647992f28c', '/api/booking/captcha', 'desktop', '2026-11-22 14:03:19', '2026-11-22 14:03:19', '2026-11-22 14:03:19'),
(473, 'ec093e77a0d5097b6cb4deb03f7c0b1c4c5d2aa9adf3606469fcf24ae4c110f7', '/api/contact/captcha', 'desktop', '2026-11-22 14:03:19', '2026-11-22 14:03:19', '2026-11-22 14:03:19'),
(474, '301a33b22a85f4e32735b16c39826a7c6efba7d04ffbc3b98f7f8a86c7d2295c', '/', 'desktop', '2026-11-22 14:04:03', '2026-11-22 14:04:03', '2026-11-22 14:04:03'),
(475, '5c5842d6ec791051d7b59952a3ffa2b2497d35d02f881e94c37c222bbbc4e33d', '/', 'desktop', '2026-11-22 14:04:04', '2026-11-22 14:04:04', '2026-11-22 14:04:04'),
(476, '048d52680d2acedc9f5b1dacdc5b9b7289772f29db195fc815bf263fab78a1f2', '/', 'desktop', '2026-11-22 14:04:04', '2026-11-22 14:04:04', '2026-11-22 14:04:04'),
(477, 'b0520764b330e2136453dffe3b617bc6349aa86c9ffb87fa8a5fa84eb78ee361', '/about', 'desktop', '2026-11-22 14:04:04', '2026-11-22 14:04:04', '2026-11-22 14:04:04'),
(478, '18a0c5836532754d5139b4fedec6a23a6301bc60f82c1ab143b4cf37947f03c9', '/packages', 'desktop', '2026-11-22 14:04:04', '2026-11-22 14:04:04', '2026-11-22 14:04:04'),
(479, '13071b50c6761f6f6d139da993b68f34c34027c4ae742a33f7ec6c5f894ca520', '/vendors', 'desktop', '2026-11-22 14:04:04', '2026-11-22 14:04:04', '2026-11-22 14:04:04'),
(480, 'fde9bac0576b932986bf79830f5316be81f2ffcebf1cde563a2ef0c754d86c6a', '/venues', 'desktop', '2026-11-22 14:04:04', '2026-11-22 14:04:04', '2026-11-22 14:04:04'),
(481, 'f62fe757de13f9fa18bb1f1240537f393c17d27ea9a9a99d274da169c713dbb8', '/faq', 'desktop', '2026-11-22 14:04:04', '2026-11-22 14:04:04', '2026-11-22 14:04:04'),
(482, '8ce68949d6c5c486cc7794108a73eab673e64d363102d773950979c2a940ebf2', '/', 'desktop', '2026-11-22 14:04:04', '2026-11-22 14:04:04', '2026-11-22 14:04:04'),
(483, '2e26a0c72796052dcbc943bb191f4524ce8ac99e7a29de3d67f967e3c92befa6', '/formulir-konsultasi', 'desktop', '2026-11-22 14:04:04', '2026-11-22 14:04:04', '2026-11-22 14:04:04'),
(484, 'd487ba83193ca85cbf7485e6315b2cfc5cef9f6e95ff949b92b691756e6fafb8', '/', 'desktop', '2026-11-22 14:04:49', '2026-11-22 14:04:49', '2026-11-22 14:04:49'),
(485, 'bf6f5718a2d8f6d8527ef6bebcbe32ff4f48f2b557e928a5ef1585a81b7e2d59', '/packages', 'desktop', '2026-11-22 14:04:49', '2026-11-22 14:04:49', '2026-11-22 14:04:49'),
(486, 'b3cc8a8729b3eb0639620fd1cbb0f8101bac46050aad17a0d8b9064ba5223576', '/venues', 'desktop', '2026-11-22 14:04:50', '2026-11-22 14:04:50', '2026-11-22 14:04:50'),
(487, '5b7396282cb92f2c21be6d96bb6ef2a32c24b98f2cad39356e20f69bca98c8ae', '/formulir-konsultasi', 'desktop', '2026-11-22 14:04:50', '2026-11-22 14:04:50', '2026-11-22 14:04:50'),
(488, '5c834998c3da8e259e533c12c44af6aee07a2017ca734662b40af8ef72d9d8fe', '/faq', 'desktop', '2026-11-22 14:04:50', '2026-11-22 14:04:50', '2026-11-22 14:04:50'),
(489, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/packages', 'desktop', '2026-11-22 14:04:53', '2026-11-22 14:04:53', '2026-11-22 14:04:53'),
(490, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/faq', 'desktop', '2026-11-22 14:04:56', '2026-11-22 14:04:56', '2026-11-22 14:04:56'),
(491, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/venues', 'desktop', '2026-11-22 14:04:57', '2026-11-22 14:04:57', '2026-11-22 14:04:57'),
(492, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'desktop', '2026-11-22 14:04:59', '2026-11-22 14:04:59', '2026-11-22 14:04:59'),
(493, '56e013cc42b730d046d98a46177c54757717f345e115dec19d901c38f9eb7386', '/', 'desktop', '2026-11-22 14:05:04', '2026-11-22 14:05:04', '2026-11-22 14:05:04'),
(494, 'b2a327b6cd4b9cc8bc391788348086758134a665dd0d7eabca21f8d9186991a3', '/packages', 'desktop', '2026-11-22 14:05:04', '2026-11-22 14:05:04', '2026-11-22 14:05:04'),
(495, '4354e1cf1998cc10c23b0d0e294193a42979443e32c63cce6889eca4c34a500e', '/venues', 'desktop', '2026-11-22 14:05:04', '2026-11-22 14:05:04', '2026-11-22 14:05:04'),
(496, '979ea8cf90854da0ccff86941c8fe915230982686f33f06aafa42222804f1e9f', '/formulir-konsultasi', 'desktop', '2026-11-22 14:05:04', '2026-11-22 14:05:04', '2026-11-22 14:05:04'),
(497, 'e3998ce4d5ef387d777040cad3c51c6c69e2c34b7833fcaf8be0eb78e737c718', '/about', 'desktop', '2026-11-22 14:05:04', '2026-11-22 14:05:04', '2026-11-22 14:05:04'),
(498, '42fc68a867d9d812b46e0b958a878c0f79083597c7851b0bf538b667416f7411', '/about', 'desktop', '2026-11-22 14:05:04', '2026-11-22 14:05:04', '2026-11-22 14:05:04'),
(499, 'ef54266f980ed202ba660f18f3cb0e4d37944f6afe07d6124e6de3e7b83dbe7d', '/venues', 'desktop', '2026-11-22 14:05:05', '2026-11-22 14:05:05', '2026-11-22 14:05:05'),
(500, '8d28b98db00d18bb33133cde8de9f834709aa771dbf3f505de3cbacb17c35dfd', '/faq', 'desktop', '2026-11-22 14:05:05', '2026-11-22 14:05:05', '2026-11-22 14:05:05'),
(501, 'b2ec45c5926779330c602e864ca51d1a5ca16a36b7d69fdf6653552ff17b2bdd', '/blog', 'desktop', '2026-11-22 14:05:05', '2026-11-22 14:05:05', '2026-11-22 14:05:05'),
(502, '5ca9ed20aee9325df617e07ae6a52d88af2bb8c1c8aee968a31b86ce9729ed9c', '/event', 'desktop', '2026-11-22 14:05:05', '2026-11-22 14:05:05', '2026-11-22 14:05:05'),
(503, 'c038a172feb045ce8e1a5b85d71c3a4dea574fdbdd33a7bababd6574cbe5f7cd', '/gallery', 'desktop', '2026-11-22 14:05:05', '2026-11-22 14:05:05', '2026-11-22 14:05:05'),
(504, '17c8c6861324dda8096cf91c36f139d3cd342d513696459ca86f4ec0d41b7961', '/vendors', 'desktop', '2026-11-22 14:05:05', '2026-11-22 14:05:05', '2026-11-22 14:05:05'),
(505, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/formulir-konsultasi', 'desktop', '2026-11-22 14:05:23', '2026-11-22 14:05:23', '2026-11-22 14:05:23'),
(506, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'mobile', '2026-11-22 14:05:54', '2026-11-22 14:05:54', '2026-11-22 14:05:54'),
(507, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'mobile', '2026-11-22 14:05:58', '2026-11-22 14:05:58', '2026-11-22 14:05:58'),
(508, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/faq', 'desktop', '2026-11-22 14:06:15', '2026-11-22 14:06:15', '2026-11-22 14:06:15'),
(509, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/venues', 'desktop', '2026-11-22 14:06:17', '2026-11-22 14:06:17', '2026-11-22 14:06:17'),
(510, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/vendors', 'desktop', '2026-11-22 14:06:19', '2026-11-22 14:06:19', '2026-11-22 14:06:19'),
(511, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:06:25', '2026-11-22 14:06:25', '2026-11-22 14:06:25'),
(512, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/about', 'desktop', '2026-11-22 14:06:25', '2026-11-22 14:06:25', '2026-11-22 14:06:25'),
(513, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/about', 'desktop', '2026-11-22 14:11:24', '2026-11-22 14:11:24', '2026-11-22 14:11:24'),
(514, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/about', 'desktop', '2026-11-22 14:13:28', '2026-11-22 14:13:28', '2026-11-22 14:13:28'),
(515, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/about', 'desktop', '2026-11-22 14:17:25', '2026-11-22 14:17:25', '2026-11-22 14:17:25'),
(516, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 14:17:26', '2026-11-22 14:17:26', '2026-11-22 14:17:26'),
(517, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/about', 'desktop', '2026-11-22 14:18:01', '2026-11-22 14:18:01', '2026-11-22 14:18:01'),
(518, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 14:18:35', '2026-11-22 14:18:35', '2026-11-22 14:18:35'),
(519, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 14:20:59', '2026-11-22 14:20:59', '2026-11-22 14:20:59'),
(520, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/faq', 'desktop', '2026-11-22 14:21:51', '2026-11-22 14:21:51', '2026-11-22 14:21:51'),
(521, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:21:59', '2026-11-22 14:21:59', '2026-11-22 14:21:59'),
(522, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 14:22:13', '2026-11-22 14:22:13', '2026-11-22 14:22:13'),
(523, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 14:27:17', '2026-11-22 14:27:17', '2026-11-22 14:27:17'),
(524, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:28:05', '2026-11-22 14:28:05', '2026-11-22 14:28:05'),
(525, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 14:31:10', '2026-11-22 14:31:10', '2026-11-22 14:31:10'),
(526, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:31:29', '2026-11-22 14:31:29', '2026-11-22 14:31:29'),
(527, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 14:33:59', '2026-11-22 14:33:59', '2026-11-22 14:33:59'),
(528, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:34:11', '2026-11-22 14:34:11', '2026-11-22 14:34:11'),
(529, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/faq', 'desktop', '2026-11-22 14:35:55', '2026-11-22 14:35:55', '2026-11-22 14:35:55'),
(530, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:35:56', '2026-11-22 14:35:56', '2026-11-22 14:35:56'),
(531, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:37:46', '2026-11-22 14:37:46', '2026-11-22 14:37:46'),
(532, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:39:06', '2026-11-22 14:39:06', '2026-11-22 14:39:06'),
(533, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:40:23', '2026-11-22 14:40:23', '2026-11-22 14:40:23'),
(534, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/about', 'desktop', '2026-11-22 14:42:26', '2026-11-22 14:42:26', '2026-11-22 14:42:26'),
(535, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:42:32', '2026-11-22 14:42:32', '2026-11-22 14:42:32'),
(536, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:48:06', '2026-11-22 14:48:06', '2026-11-22 14:48:06'),
(537, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:51:32', '2026-11-22 14:51:32', '2026-11-22 14:51:32'),
(538, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/faq', 'desktop', '2026-11-22 14:51:47', '2026-11-22 14:51:47', '2026-11-22 14:51:47'),
(539, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 14:52:12', '2026-11-22 14:52:12', '2026-11-22 14:52:12'),
(540, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/venues', 'desktop', '2026-11-22 14:52:56', '2026-11-22 14:52:56', '2026-11-22 14:52:56'),
(541, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/vendors', 'desktop', '2026-11-22 14:52:59', '2026-11-22 14:52:59', '2026-11-22 14:52:59'),
(542, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/packages', 'desktop', '2026-11-22 14:53:03', '2026-11-22 14:53:03', '2026-11-22 14:53:03'),
(543, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/about', 'desktop', '2026-11-22 14:53:04', '2026-11-22 14:53:04', '2026-11-22 14:53:04'),
(544, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:53:05', '2026-11-22 14:53:05', '2026-11-22 14:53:05'),
(545, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:54:32', '2026-11-22 14:54:32', '2026-11-22 14:54:32'),
(546, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 14:59:10', '2026-11-22 14:59:10', '2026-11-22 14:59:10'),
(547, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 15:05:26', '2026-11-22 15:05:26', '2026-11-22 15:05:26'),
(548, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 15:07:56', '2026-11-22 15:07:56', '2026-11-22 15:07:56'),
(549, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 15:09:17', '2026-11-22 15:09:17', '2026-11-22 15:09:17'),
(550, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 15:10:19', '2026-11-22 15:10:19', '2026-11-22 15:10:19'),
(551, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 15:11:26', '2026-11-22 15:11:26', '2026-11-22 15:11:26'),
(552, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 15:11:47', '2026-11-22 15:11:47', '2026-11-22 15:11:47'),
(553, '1fdb0e7f05b8c99df58ec953939be929aae09def1b2a5503ad7b60ace8227a1c', '/', 'desktop', '2026-11-22 15:13:48', '2026-11-22 15:13:48', '2026-11-22 15:13:48'),
(554, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 15:15:17', '2026-11-22 15:15:17', '2026-11-22 15:15:17'),
(555, '666747287dee3b1cd2d88b12b268239efbbb9180412de5d5959b580381a2ba6c', '/', 'desktop', '2026-11-22 15:16:26', '2026-11-22 15:16:26', '2026-11-22 15:16:26');

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
(6, '787b72ea-59d0-4d54-848b-c200bddafdd2', 'super-admin', NULL, 'Jl. Gandaria', 4, 17, 'avatars/u5KMRU9jG95SYcdq0vhxnksQ0EFatee9WxrPxrtH.jpg', 'super@admin.com', '2025-09-16 00:23:37', '$2y$12$PRZJcd.nlREU6NRq3jvIVemYmlwxVPTb7En4URyNgyQSfKjWUzwDi', 'dpT7eK13sGv8C4yLn7gZZs3eMquEcHnwBUCwf3dMOw1F7GI9E0t1EEU5ROrD', '2025-09-16 00:23:37', '2026-09-06 23:43:32', NULL, NULL, 'dasda', '2025-09-24', 'P', 'Lainnya', '10 tahun', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sdsddsad', NULL, NULL, 'new_registered', NULL),
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

-- --------------------------------------------------------

--
-- Table structure for table `wedding_checklist_items`
--

CREATE TABLE `wedding_checklist_items` (
  `uuid` char(36) NOT NULL,
  `order_uuid` char(36) NOT NULL,
  `title` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `due_at` date DEFAULT NULL,
  `reminder_sent_at` timestamp NULL DEFAULT NULL,
  `escalation_sent_at` timestamp NULL DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `completed_at` timestamp NULL DEFAULT NULL,
  `completed_by` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wedding_checklist_items`
--

INSERT INTO `wedding_checklist_items` (`uuid`, `order_uuid`, `title`, `description`, `due_at`, `reminder_sent_at`, `escalation_sent_at`, `status`, `completed_at`, `completed_by`, `created_at`, `updated_at`) VALUES
('01a2022f-663f-7a31-b9a3-97d485dc1a2f', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'Konfirmasi detail acara', 'Pastikan lokasi, waktu, dan kontak utama acara sudah benar.', '2026-08-09', NULL, NULL, 'completed', '2026-11-22 07:38:07', '70e15b9f-535a-42a2-8ea0-9a26ad7952e5', '2026-11-22 07:36:30', '2026-11-22 07:38:07'),
('01a2022f-6640-7ddb-8100-cb64f18c87f7', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'Finalisasi jumlah tamu', 'Perbarui perkiraan jumlah tamu dan kebutuhan konsumsi.', '2026-09-06', NULL, NULL, 'pending', NULL, NULL, '2026-11-22 07:36:30', '2026-11-22 13:27:14'),
('01a2022f-6641-7aac-b518-7b8eda057225', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'Konfirmasi susunan acara', 'Tinjau susunan acara bersama tim wedding organizer.', '2026-10-04', NULL, NULL, 'pending', NULL, NULL, '2026-11-22 07:36:30', '2026-11-22 13:23:58'),
('01a2022f-6642-78f9-9e83-b95b1c7e290b', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'Finalisasi kebutuhan vendor', 'Pastikan kebutuhan dekorasi, dokumentasi, dan vendor tambahan.', '2026-11-01', NULL, NULL, 'pending', NULL, NULL, '2026-11-22 07:36:30', '2026-11-22 07:36:30'),
('01a2022f-6643-76f1-b5ea-152de37d54f5', '01a2022b-88b4-7b60-b679-74d234cde5fe', 'Konfirmasi akhir acara', 'Lakukan konfirmasi akhir detail dan jadwal acara.', '2026-11-22', NULL, NULL, 'pending', NULL, NULL, '2026-11-22 07:36:30', '2026-11-22 07:36:30');

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
-- Indexes for table `checkout_sessions`
--
ALTER TABLE `checkout_sessions`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `checkout_sessions_visitor_key_unique` (`visitor_key`),
  ADD KEY `checkout_sessions_order_uuid_foreign` (`order_uuid`),
  ADD KEY `checkout_sessions_status_started_at_index` (`status`,`started_at`),
  ADD KEY `checkout_sessions_package_uuid_started_at_index` (`package_uuid`,`started_at`);

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
-- Indexes for table `event_date_reservations`
--
ALTER TABLE `event_date_reservations`
  ADD PRIMARY KEY (`event_date`),
  ADD UNIQUE KEY `event_date_reservations_order_uuid_unique` (`order_uuid`);

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
-- Indexes for table `order_change_requests`
--
ALTER TABLE `order_change_requests`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `order_change_requests_requested_by_uuid_foreign` (`requested_by_uuid`),
  ADD KEY `order_change_requests_addon_uuid_foreign` (`addon_uuid`),
  ADD KEY `order_change_requests_reviewed_by_uuid_foreign` (`reviewed_by_uuid`),
  ADD KEY `order_change_requests_status_created_at_index` (`status`,`created_at`),
  ADD KEY `order_change_requests_order_uuid_status_index` (`order_uuid`,`status`);

--
-- Indexes for table `order_documents`
--
ALTER TABLE `order_documents`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `order_documents_document_key_unique` (`document_key`),
  ADD UNIQUE KEY `order_documents_document_number_unique` (`document_number`),
  ADD KEY `order_documents_payment_uuid_foreign` (`payment_uuid`),
  ADD KEY `order_documents_order_uuid_type_index` (`order_uuid`,`type`);

--
-- Indexes for table `order_saved_filters`
--
ALTER TABLE `order_saved_filters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_saved_filters_user_name_unique` (`user_uuid`,`name`),
  ADD KEY `order_saved_filters_user_default_index` (`user_uuid`,`is_default`),
  ADD KEY `order_saved_filters_user_pin_order_index` (`user_uuid`,`pinned_at`,`sort_order`);

--
-- Indexes for table `order_vendor_assignments`
--
ALTER TABLE `order_vendor_assignments`
  ADD PRIMARY KEY (`uuid`),
  ADD UNIQUE KEY `order_vendor_scope_unique` (`order_uuid`,`vendor_uuid`,`scope`),
  ADD KEY `order_vendor_assignments_assigned_by_foreign` (`assigned_by`),
  ADD KEY `order_vendor_assignments_order_uuid_status_index` (`order_uuid`,`status`),
  ADD KEY `order_vendor_assignments_vendor_uuid_index` (`vendor_uuid`);

--
-- Indexes for table `order_workflow_settings`
--
ALTER TABLE `order_workflow_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_workflow_settings_updated_by_foreign` (`updated_by`);

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
-- Indexes for table `wedding_checklist_items`
--
ALTER TABLE `wedding_checklist_items`
  ADD PRIMARY KEY (`uuid`),
  ADD KEY `wedding_checklist_items_completed_by_foreign` (`completed_by`),
  ADD KEY `wedding_checklist_items_order_uuid_status_due_at_index` (`order_uuid`,`status`,`due_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=313;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_logins`
--
ALTER TABLE `failed_logins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=579;

--
-- AUTO_INCREMENT for table `login_lockouts`
--
ALTER TABLE `login_lockouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `order_workflow_settings`
--
ALTER TABLE `order_workflow_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=372;

--
-- AUTO_INCREMENT for table `seo`
--
ALTER TABLE `seo`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `site_visits`
--
ALTER TABLE `site_visits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=556;

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
-- Constraints for table `checkout_sessions`
--
ALTER TABLE `checkout_sessions`
  ADD CONSTRAINT `checkout_sessions_order_uuid_foreign` FOREIGN KEY (`order_uuid`) REFERENCES `orders` (`uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `checkout_sessions_package_uuid_foreign` FOREIGN KEY (`package_uuid`) REFERENCES `packages` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `event_date_reservations`
--
ALTER TABLE `event_date_reservations`
  ADD CONSTRAINT `event_date_reservations_order_uuid_foreign` FOREIGN KEY (`order_uuid`) REFERENCES `orders` (`uuid`) ON DELETE SET NULL;

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
-- Constraints for table `order_change_requests`
--
ALTER TABLE `order_change_requests`
  ADD CONSTRAINT `order_change_requests_addon_uuid_foreign` FOREIGN KEY (`addon_uuid`) REFERENCES `package_addons` (`uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_change_requests_order_uuid_foreign` FOREIGN KEY (`order_uuid`) REFERENCES `orders` (`uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_change_requests_requested_by_uuid_foreign` FOREIGN KEY (`requested_by_uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_change_requests_reviewed_by_uuid_foreign` FOREIGN KEY (`reviewed_by_uuid`) REFERENCES `users` (`uuid`) ON DELETE SET NULL;

--
-- Constraints for table `order_documents`
--
ALTER TABLE `order_documents`
  ADD CONSTRAINT `order_documents_order_uuid_foreign` FOREIGN KEY (`order_uuid`) REFERENCES `orders` (`uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_documents_payment_uuid_foreign` FOREIGN KEY (`payment_uuid`) REFERENCES `payments` (`uuid`) ON DELETE SET NULL;

--
-- Constraints for table `order_saved_filters`
--
ALTER TABLE `order_saved_filters`
  ADD CONSTRAINT `order_saved_filters_user_uuid_foreign` FOREIGN KEY (`user_uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `order_vendor_assignments`
--
ALTER TABLE `order_vendor_assignments`
  ADD CONSTRAINT `order_vendor_assignments_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `order_vendor_assignments_order_uuid_foreign` FOREIGN KEY (`order_uuid`) REFERENCES `orders` (`uuid`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_vendor_assignments_vendor_uuid_foreign` FOREIGN KEY (`vendor_uuid`) REFERENCES `users` (`uuid`) ON DELETE CASCADE;

--
-- Constraints for table `order_workflow_settings`
--
ALTER TABLE `order_workflow_settings`
  ADD CONSTRAINT `order_workflow_settings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`uuid`) ON DELETE SET NULL;

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

--
-- Constraints for table `wedding_checklist_items`
--
ALTER TABLE `wedding_checklist_items`
  ADD CONSTRAINT `wedding_checklist_items_completed_by_foreign` FOREIGN KEY (`completed_by`) REFERENCES `users` (`uuid`) ON DELETE SET NULL,
  ADD CONSTRAINT `wedding_checklist_items_order_uuid_foreign` FOREIGN KEY (`order_uuid`) REFERENCES `orders` (`uuid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
