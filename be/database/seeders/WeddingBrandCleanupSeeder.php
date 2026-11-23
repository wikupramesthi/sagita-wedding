<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeddingBrandCleanupSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('articles')
            ->where('uuid', '63829ea9-8a32-4f82-94e1-7299120f822c')
            ->update([
                'title' => 'Wedding Organizer vs Wedding Planner: Mana yang Anda Butuhkan?',
                'slug' => 'wedding-organizer-vs-wedding-planner-mana-yang-anda-butuhkan',
                'excerpt' => 'Wedding organizer dan wedding planner sama-sama membantu mempersiapkan pernikahan. Pahami perbedaan layanan, cara kerja, dan cara memilih pendamping yang tepat untuk hari bahagia Anda.',
                'content' => '<p><strong>Wedding Organizer vs Wedding Planner: Mana yang Anda Butuhkan?</strong></p><p>Perencanaan pernikahan melibatkan banyak detail, mulai dari konsep, anggaran, vendor, hingga rundown acara. Dua peran yang sering disebut adalah wedding organizer dan wedding planner. Keduanya membantu pasangan mewujudkan pernikahan sesuai rencana, namun cakupan tugasnya dapat berbeda.</p><p><strong>Apa itu wedding planner?</strong></p><p>Wedding planner biasanya terlibat sejak awal persiapan. Perannya membantu pasangan menyusun konsep, menentukan prioritas anggaran, memilih venue, dan merancang timeline persiapan. Pendampingan ini cocok untuk pasangan yang menginginkan arahan dari tahap ide hingga detail teknis acara.</p><p><strong>Apa itu wedding organizer?</strong></p><p>Wedding organizer lebih fokus pada pelaksanaan teknis. Tim memastikan koordinasi vendor, kesiapan venue, dan berjalannya rundown sesuai jadwal. Peran ini sangat membantu ketika jumlah vendor dan tamu undangan cukup banyak.</p><p><strong>Memilih layanan yang tepat</strong></p><p>Jika Anda baru mulai merencanakan pernikahan dan membutuhkan pendampingan konsep sejak awal, wedding planner dapat menjadi pilihan. Jika konsep sudah matang dan yang dibutuhkan adalah dukungan teknis di lapangan, wedding organizer akan lebih sesuai.</p><p><strong>Pertanyaan yang perlu diajukan</strong></p><ul><li>Apakah layanan mencakup konsultasi konsep dan penyusunan anggaran?</li><li>Bagaimana proses koordinasi vendor dan pembayaran?</li><li>Apakah ada pendampingan pada hari acara?</li><li>Bagaimana penanganan perubahan jadwal mendekati pernikahan?</li></ul><p><strong>Kesimpulan</strong></p><p>Tidak ada satu pilihan yang tepat untuk semua pasangan. Sesuaikan layanan dengan kebutuhan, jumlah tamu, dan kompleksitas acara. Sagita Wedding Planner siap membantu Anda memilih paket yang sesuai, mulai dari perencanaan awal hingga pendampingan teknis pada hari pernikahan.</p>',
                'tagging' => 'wedding organizer, wedding planner, persiapan pernikahan',
            ]);

        $events = [
            '02471b9c-9346-409c-b9b0-5331d91456ed' => [
                'judul' => 'Pernikahan Garden di Kebun Raya',
                'slug' => 'pernikahan-garden-di-kebun-raya',
                'deskripsi' => '<p>Rencanakan pernikahan outdoor yang tenang dan berkesan bersama Sagita Wedding Planner. Kunjungi Kebun Raya Bogor pada Minggu, 13 September 2026 pukul 07.30 - 10.00 WIB untuk sesi pengenalan konsep garden wedding, konsultasi dekorasi, dan diskusi paket layanan kami.</p><p>Lokasi: Kebun Raya Bogor - Taman Melchior. Kuota terbatas. Daftarkan diri Anda untuk mendapatkan pendampingan perencanaan pernikahan dari tim kami.</p>',
                'lokasi' => 'Kebun Raya Bogor - Taman Melchior',
            ],
            '3191142d-9185-421c-be1d-791c843f6161' => [
                'judul' => 'Wedding Expo di Museum',
                'slug' => 'wedding-expo-di-museum',
                'deskripsi' => '<p>Sagita Wedding Planner mengundang calon pengantin untuk mengikuti Wedding Expo di Museum pada Jumat, 18 September 2026 pukul 18.30 - 19.30 WIB. Temukan inspirasi konsep pernikahan, konsultasi langsung dengan tim kami, dan pelajari pilihan paket wedding organizer yang sesuai kebutuhan Anda.</p><p>Lokasi: Taman Arca, Museum Nasional Indonesia. Silakan datang lebih awal untuk sesi konsultasi bersama tim perencana pernikahan kami.</p>',
            ],
            '64e369a4-ed0f-4009-996e-362f879a681e' => [
                'judul' => 'Sunset Prewedding Session',
                'slug' => 'sunset-prewedding-session',
                'deskripsi' => '<p>Abadikan momen menjelang pernikahan dalam Sunset Prewedding Session bersama Sagita Wedding Planner. Sesi ini berlangsung pada Minggu, 20 September 2026 pukul 16.30 di Balcony Skechers, Senayan Park.</p><p>Tim kami membantu penataan konsep, pemilihan lokasi foto, dan koordinasi dokumentasi agar hasil prewedding terasa natural dan nyaman. Kuota terbatas dan terbuka untuk pasangan yang sedang mempersiapkan pernikahan.</p>',
            ],
        ];

        foreach ($events as $uuid => $attributes) {
            DB::table('events')
                ->where('uuid', $uuid)
                ->update($attributes);
        }

        $testimonials = [
            '267d9987-ea8e-422a-a32d-a26e7e9cea0f' => 'Sagita Wedding Planner membantu kami dari awal persiapan hingga hari acara. Timnya rapi, responsif, dan selalu siap menyarankan solusi saat rencana berubah. Pernikahan kami berjalan lancar dan sesuai konsep yang kami bayangkan.',
            '8a7bbb78-eb33-4f1b-941c-cbdcd2b9c5a3' => 'Konsultasi bersama Sagita terasa menyenangkan dan terarah. Semua detail, mulai dari venue, dekorasi, sampai rundown acara, dibahas dengan jelas. Kami merasa tenang karena ada tim yang benar-benar memperhatikan setiap detail.',
            'dcfd5ae2-e3a0-4c4b-a3c4-937af0a086d7' => 'Pengalaman yang sangat memuaskan. Tim Sagita bekerja profesional, komunikatif, dan membantu kami mengoordinasikan seluruh vendor. Hasilnya, acara pernikahan kami berjalan tertib dan tamu merasa nyaman.',
        ];

        foreach ($testimonials as $uuid => $testimoni) {
            DB::table('testimonials')
                ->where('uuid', $uuid)
                ->update([
                    'jabatan' => 'Klien Sagita',
                    'isi_testimoni' => $testimoni,
                ]);
        }

        DB::table('venues')
            ->where('uuid', '363f49dc-b34f-4ac2-ad18-95b140c6335e')
            ->update([
                'name' => 'Sagita Wedding Hall',
                'slug' => 'sagita-wedding-hall',
                'description' => 'Sagita Wedding Hall adalah venue pernikahan yang nyaman untuk akad, resepsi, dan acara keluarga. Ruangannya dapat ditata sesuai konsep pernikahan, dengan dukungan tim Sagita Wedding Planner untuk koordinasi dekorasi, dokumentasi, dan rundown acara.',
                'address' => 'De Tridaya Residence 4, Tridaya Sakti, Kec. Tambun Sel., Kabupaten Bekasi, Jawa Barat 17510',
                'phone' => '+6281380760095',
                'email' => 'venue@sagita-wedding.id',
            ]);

        $users = [
            '0ba0b18a-64e7-44ce-8042-8b282ab9c5b3' => [
                'email' => 'stella@sagita-wedding.id',
                'biografi' => 'Stella telah mendampingi pasangan merencanakan pernikahan selama hampir tiga dekade. Ia membantu penyusunan konsep, pemilihan vendor, dan koordinasi teknis acara dengan pendekatan yang inklusif dan mudah diikuti. Pengalamannya mencakup pernikahan intimate maupun acara berskala besar.',
            ],
            '4a85c36a-caa6-4cc9-9ab6-47f77c0a9a67' => [
                'email' => 'andrea@sagita-wedding.id',
                'biografi' => 'Andrea memulai karier sebagai peneliti dan kini mendampingi calon pengantin sebagai wedding planner. Ia mengutamakan komunikasi yang jernih, perencanaan yang matang, dan suasana acara yang terasa personal bagi setiap pasangan.',
            ],
            '57621d3c-c299-4cd2-b96a-9b887752cb73' => [
                'name' => 'Sagita Wedding Planner',
                'email' => 'halo@sagita-wedding.id',
            ],
            '755e0eaa-50e4-46db-9003-7c1fc6674876' => [
                'email' => 'margaretta@sagita-wedding.id',
                'biografi' => 'Margaretta membawa pendekatan yang terstruktur dan penuh perhatian dalam setiap persiapan pernikahan. Ia berpengalaman membantu pasangan menyusun detail acara, mengoordinasikan vendor, dan menjaga kenyamanan keluarga selama proses persiapan.',
            ],
            '95294c43-b3d8-4b46-b938-e4eea1f3a359' => [
                'email' => 'soni@sagita-wedding.id',
                'biografi' => 'Soni memiliki pengalaman lebih dari 20 tahun dalam koordinasi acara dan pendampingan tim. Ia membantu pasangan menyusun jadwal persiapan, mengelola kebutuhan vendor, dan memastikan setiap detail teknis pada hari pernikahan berjalan sesuai rencana.',
            ],
            'b497082d-dd9f-4d1c-a611-9e1331eb5393' => [
                'email' => 'ophellia@sagita-wedding.id',
                'biografi' => 'Ophellia fokus pada konsep pernikahan yang rapi dan nyaman. Sejak 2024 ia telah membantu pasangan memilih tema, menata ruang acara, dan mengoordinasikan layanan pendukung agar hari bahagia terasa tenang dan menyenangkan.',
            ],
            'b55eb675-019c-4fa5-936e-579e2835527d' => [
                'email' => 'yuniar@sagita-wedding.id',
                'biografi' => 'Yuniar telah mendampingi pasangan sejak 2007. Ia dikenal ramah dan teliti dalam mengarahkan pemilihan konsep, susunan acara, serta detail teknis venue, sehingga pasangan dapat menikmati proses persiapan dengan percaya diri.',
            ],
            'beaaf326-874c-48c2-b6b8-6bbe655c4df2' => [
                'email' => 'sita@sagita-wedding.id',
                'biografi' => 'Sita adalah wedding planner berpengalaman yang membantu pasangan merancang pernikahan dari tahap awal hingga hari acara. Ia menguasai koordinasi vendor, pengelolaan timeline, dan penyesuaian konsep agar setiap pernikahan terasa autentik dan terencana dengan baik.',
            ],
        ];

        foreach ($users as $uuid => $attributes) {
            DB::table('users')
                ->where('uuid', $uuid)
                ->update($attributes);
        }

        $orderBaruBnejir = '{"judul_kegiatan":"Pesanan baru diterima","message":"ORD-BNEJIR8QFB - Wiku Pramesthi Bagaswara memesan Paket Rencana Pernikahan - Pendampingan Personal (Rp 210.000)","path":"/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be","icon":"bi-receipt"}';
        $buktiI5hk = '{"judul_kegiatan":"Bukti transfer diunggah","message":"ORD-I5HKUWFTWJ - ESPRO Property mengunggah bukti transfer (Rp 210.000). Silakan verifikasi.","path":"/backend/orders/01a1049e-afda-7188-b2f9-9fa3b404f6d3","icon":"bi-file-earmark-check"}';
        $loginSuperAdmin = '{"judul_kegiatan":"Login tidak biasa terdeteksi","message":"super-admin (super@admin.com) masuk dari 127.0.0.1 - jam tidak biasa","path":"/backend/security/login-activity","icon":"bi-shield-exclamation"}';
        $kehadiranSelasaAdmin = '{"judul_kegiatan":"Kehadiran dikonfirmasi","message":"Tim Sagita telah mengonfirmasi kehadiran Anda untuk konsultasi pernikahan pada Selasa pukul 19.00. Detail pesanan dapat dilihat di halaman pesanan.","path":"/backend/orders","icon":"bi-check2-circle"}';
        $kehadiranSelasa = '{"judul_kegiatan":"Kehadiran dikonfirmasi","message":"Kehadiran Anda untuk konsultasi pernikahan pada Selasa pukul 19.00 telah dikonfirmasi. Lihat detail pesanan untuk informasi lengkap.","path":"/backend/orders","icon":"bi-check2-circle"}';
        $kehadiranSenin = '{"judul_kegiatan":"Kehadiran dikonfirmasi","message":"Admin telah mengonfirmasi kehadiran Anda untuk konsultasi pernikahan pada Senin pukul 14.00. Lihat detail pesanan untuk informasi lengkap.","path":"/backend/orders","icon":"bi-check2-circle"}';
        $expired = static fn (string $order): string => '{"judul_kegiatan":"Pesanan kedaluwarsa","message":"'.$order.' otomatis dibatalkan setelah 24 jam tanpa pembayaran. Silakan buat pesanan baru jika tetap ingin memesan paket WO.","path":"/backend/packages","icon":"bi-hourglass-bottom"}';
        $aktif = static fn (string $order, string $path, string $date): string => '{"judul_kegiatan":"Pesanan dikonfirmasi","message":"Pembayaran untuk '.$order.' telah diverifikasi. Paket WO Anda sekarang aktif. Berlaku hingga '.$date.'.","path":"'.$path.'","icon":"bi-check-circle"}';
        $orderBaruDhvc = '{"judul_kegiatan":"Pesanan baru diterima","message":"ORD-DHVCIG8ZQL - Wiku Pramesthi Bagaswara memesan Paket Rencana Pernikahan - Pendampingan Personal (Rp 210.000)","path":"/backend/orders/01a11182-8463-7057-a0f4-8cf88279c6c2","icon":"bi-receipt"}';
        $orderBaruE5wx = '{"judul_kegiatan":"Pesanan baru diterima","message":"ORD-E5WX8OSWPO - Wiku Pramesthi Bagaswara memesan Paket Rencana Pernikahan - Pendampingan Personal (Rp 210.000)","path":"/backend/orders/01a110cc-d5ec-7387-a3c5-87cde025d404","icon":"bi-receipt"}';
        $buktiBnejir = '{"judul_kegiatan":"Bukti transfer diunggah","message":"ORD-BNEJIR8QFB - Wiku Pramesthi Bagaswara mengunggah bukti transfer (Rp 210.000). Silakan verifikasi.","path":"/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be","icon":"bi-file-earmark-check"}';
        $loginWiku = '{"judul_kegiatan":"Login tidak biasa terdeteksi","message":"Wiku Pramesthi Bagaswara (wikupb@gmail.com) masuk dari 127.0.0.1 - jam tidak biasa","path":"/backend/security/login-activity","icon":"bi-shield-exclamation"}';
        $loginEspro = '{"judul_kegiatan":"Login tidak biasa terdeteksi","message":"ESPRO Property (esproproperty.bekasi@gmail.com) masuk dari 127.0.0.1 - jam tidak biasa","path":"/backend/security/login-activity","icon":"bi-shield-exclamation"}';
        $orderBaruEyes = '{"judul_kegiatan":"Pesanan baru diterima","message":"ORD-EYESFM7YQI - Wiku Pramesthi Bagaswara memesan Paket Pernikahan Berdua - 25 Layanan (Rp 3.079.700)","path":"/backend/orders/01a2022b-88b4-7b60-b679-74d234cde5fe","icon":"bi-receipt"}';

        $notifications = [
            '170abde0-6d7b-48a2-b78f-8cc232e48461' => $orderBaruBnejir,
            '1ef127f7-4089-43aa-a1f2-c4f2688a8280' => $buktiI5hk,
            '288c5468-e17d-4cc4-b7c3-5f022e4a7e96' => $loginSuperAdmin,
            '30266322-5b40-4c84-8ee6-4b689ed46102' => $kehadiranSelasaAdmin,
            '3042b54f-0eed-4e61-ad42-2def83dd9b3c' => $expired('ORD-FG2TCGJ55W'),
            '3a0644a0-403a-407c-ba4c-74b6c363f693' => $aktif('ORD-BNEJIR8QFB', '/backend/orders/01a110b3-0a1d-72a0-bcf1-3eeb0e3fb2be', '13 Oct 2026'),
            '4ee83ebe-638e-45cb-aa71-02cf87721984' => $orderBaruDhvc,
            '54fbcbf6-8ebc-4f5e-a62f-7b184c3a8c55' => $orderBaruBnejir,
            '5e0c56a7-c2b0-4fe2-9a1b-dbe081f039d2' => $expired('ORD-QCQL0MKBRP'),
            '62c848cd-a1d4-46ff-b0fe-d6593f138caa' => $buktiBnejir,
            '7040f077-8fe0-4701-b733-3e66a7bca72c' => $loginWiku,
            '7630f926-083f-4c4a-a189-ff681e4ce6b9' => $loginEspro,
            '7edec836-807b-46bb-a50d-3f5475fa08bf' => $orderBaruEyes,
            '7f1ea7f4-d018-4ca5-84c8-944f04c8fb57' => $orderBaruEyes,
            '89cd6964-dea1-44b8-9c8c-ff7886de4786' => $expired('ORD-9UJ1QLPEAW'),
            'a2e5d335-3192-4c81-851a-c8b6ea33dabf' => $aktif('ORD-I5HKUWFTWJ', '/backend/orders/01a1049e-afda-7188-b2f9-9fa3b404f6d3', '11 Oct 2026'),
            'a480fee2-6ed8-4579-88f0-fc1f2968b895' => $orderBaruDhvc,
            'a4cbf43e-868b-4970-9d5d-55f2f5e8df60' => $orderBaruE5wx,
            'b0274b03-ffaa-462e-9098-423fe6c82b13' => $expired('ORD-G07KVIJM1Y'),
            'b041d44e-604a-472c-9899-f54f15ee3eff' => $loginWiku,
            'b4638c57-0813-4336-9c41-433770e4a340' => $orderBaruE5wx,
            'b5792f00-edaf-4fc4-9dc5-de91485bd877' => $orderBaruEyes,
            'bc6845e1-0998-4ec9-9209-7b07a4aac0af' => $orderBaruEyes,
            'c0706708-ee37-4619-85c7-e8e3d71918e0' => $expired('ORD-C4LCIYGBRX'),
            'c10a4720-561e-421e-acec-ff4bc5e00b21' => $loginWiku,
            'c964ebfc-7e12-407e-8e10-52ad87801ba1' => $buktiBnejir,
            'd93aa418-cae7-47c0-8af5-479119c52593' => $loginEspro,
            'e53d8181-76ed-4616-9627-e150cee6c956' => $loginEspro,
            'e81dc652-ce3a-4b7e-a695-27b508d02e19' => $loginEspro,
            'e86f6aba-11c6-44bc-ab7c-a47af541ccdb' => $loginWiku,
            'ea6cf512-c215-4936-93bf-1e7f744796fc' => $loginSuperAdmin,
            'f0a45da5-5b08-453c-bbda-f036f84dfe44' => $kehadiranSelasa,
            'fbcf4c2e-ea84-4629-be0d-47925e4fc0ca' => $kehadiranSenin,
        ];

        foreach ($notifications as $id => $data) {
            DB::table('notifications')
                ->where('id', $id)
                ->update(['data' => $data]);
        }
    }
}
