<?php

namespace Database\Seeders;

use App\Models\Opd;
use App\Models\Report;
use App\Models\ReportTimeline;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Master OPD Kabupaten Sumbawa
        $opdsData = [
            [
                'name' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
                'short_name' => 'Dinas PUPR',
                'code' => 'PUPR',
                'description' => 'Bertanggung jawab atas pembangunan dan pemeliharaan jalan, jembatan, drainase, irigasi, dan tata ruang wilayah Kabupaten Sumbawa.',
                'email' => 'pupr@sumbawakab.go.id',
                'phone' => '(0371) 21456',
                'address' => 'Jl. Garuda No. 102, Sumbawa Besar',
                'icon' => 'hammer',
                'responsible_categories' => ['Jalan Rusak & Jembatan', 'Drainase & Saluran Air', 'Fasilitas Umum & Gedung'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Komunikasi, Informatika, Statistik dan Persandian',
                'short_name' => 'DISKOMINFO',
                'code' => 'DISKOMINFO',
                'description' => 'Mengelola infrastruktur telekomunikasi daerah, internet publik, penyebaran informasi resmi, dan layanan digital pemerintah daerah.',
                'email' => 'diskominfo@sumbawakab.go.id',
                'phone' => '(0371) 22345',
                'address' => 'Jl. Hasanuddin No. 34, Sumbawa Besar',
                'icon' => 'wifi',
                'responsible_categories' => ['Layanan Digital & Internet', 'Informasi Publik & Hoaks', 'Aplikasi Daerah'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Perhubungan',
                'short_name' => 'DISHUB',
                'code' => 'DISHUB',
                'description' => 'Mengatur kelancaran lalu lintas, marka dan rambu jalan, lampu penerangan jalan umum (PJU), dan ketertiban parkir daerah.',
                'email' => 'dishub@sumbawakab.go.id',
                'phone' => '(0371) 21789',
                'address' => 'Jl. Pelabuhan Badas KM 4, Sumbawa Besar',
                'icon' => 'truck',
                'responsible_categories' => ['Penerangan Jalan (PJU)', 'Rambu & Marka Jalan', 'Kemacetan & Parkir Liar'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Lingkungan Hidup',
                'short_name' => 'DLH',
                'code' => 'DLH',
                'description' => 'Mengelola kebersihan lingkungan, persampahan, taman kota, ruang terbuka hijau, dan pengendalian pencemaran.',
                'email' => 'dlh@sumbawakab.go.id',
                'phone' => '(0371) 23112',
                'address' => 'Jl. Diponegoro No. 18, Sumbawa Besar',
                'icon' => 'trash-2',
                'responsible_categories' => ['Kebersihan & Sampah', 'Pencemaran Lingkungan', 'Taman & RTH'],
                'is_active' => true,
            ],
            [
                'name' => 'Satuan Polisi Pamong Praja',
                'short_name' => 'SATPOL PP',
                'code' => 'SATPOLPP',
                'description' => 'Penegakan Peraturan Daerah, pemeliharaan ketertiban umum, dan perlindungan masyarakat Kabupaten Sumbawa.',
                'email' => 'satpolpp@sumbawakab.go.id',
                'phone' => '(0371) 21113',
                'address' => 'Komplek Kantor Bupati Sumbawa',
                'icon' => 'shield-alert',
                'responsible_categories' => ['Ketertiban Umum', 'Penertiban PKL/Bangunan Liar', 'Gangguan Ketentraman'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Kesehatan',
                'short_name' => 'DINKES',
                'code' => 'DINKES',
                'description' => 'Penyelenggaraan pelayanan kesehatan masyarakat, puskesmas, fasilitas kesehatan, dan penanganan wabah.',
                'email' => 'dinkes@sumbawakab.go.id',
                'phone' => '(0371) 21334',
                'address' => 'Jl. Garuda No. 88, Sumbawa Besar',
                'icon' => 'heart-pulse',
                'responsible_categories' => ['Pelayanan Kesehatan & Puskesmas', 'Ketersediaan Obat', 'Wabah & Sanitasi'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Kependudukan dan Pencatatan Sipil',
                'short_name' => 'DUKCAPIL',
                'code' => 'DUKCAPIL',
                'description' => 'Pelayanan administrasi kependudukan (KTP-el, KK, Akta Kelahiran/Kematian, Pindah Datang, dan IKD).',
                'email' => 'dukcapil@sumbawakab.go.id',
                'phone' => '(0371) 21990',
                'address' => 'Jl. Mawar No. 12, Sumbawa Besar',
                'icon' => 'id-card',
                'responsible_categories' => ['Pelayanan Adminduk (KTP/KK)', 'Pencatatan Sipil'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Pendidikan dan Kebudayaan',
                'short_name' => 'DIKBUD',
                'code' => 'DIKBUD',
                'description' => 'Pengelolaan sarana prasarana sekolah, mutu pendidikan dasar, dan pelestarian kebudayaan Samawa.',
                'email' => 'dikbud@sumbawakab.go.id',
                'phone' => '(0371) 21678',
                'address' => 'Jl. Dr. Sutomo No. 5, Sumbawa Besar',
                'icon' => 'graduation-cap',
                'responsible_categories' => ['Gedung Sekolah Rusak', 'Fasilitas Pendidikan', 'Layanan Sekolah'],
                'is_active' => true,
            ],
            [
                'name' => 'Badan Penanggulangan Bencana Daerah',
                'short_name' => 'BPBD',
                'code' => 'BPBD',
                'description' => 'Kesiapsiagaan, mitigasi, dan penanganan tanggap darurat bencana alam di wilayah Kabupaten Sumbawa.',
                'email' => 'bpbd@sumbawakab.go.id',
                'phone' => '(0371) 22119',
                'address' => 'Jl. Bypass Sering, Sumbawa Besar',
                'icon' => 'flame',
                'responsible_categories' => ['Banjir & Longsor', 'Pohon Tumbang', 'Bencana Alam & Kebakaran'],
                'is_active' => true,
            ],
        ];

        $createdOpds = [];
        foreach ($opdsData as $data) {
            $createdOpds[$data['code']] = Opd::create($data);
        }

        // 2. Seed Default Users
        // Admin
        $admin = User::create([
            'name' => 'Admin Pemkab Sumbawa',
            'email' => 'admin@sumbawakab.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Sekretariat Daerah Kab. Sumbawa, Jl. Garuda No. 1',
            'status' => 'active',
        ]);

        // Perangkat Daerah PUPR
        $puprUser = User::create([
            'name' => 'Ir. Hendra Saputra',
            'email' => 'pupr@sumbawakab.go.id',
            'password' => Hash::make('password'),
            'role' => 'opd',
            'opd_id' => $createdOpds['PUPR']->id,
            'nip' => '19800512 200501 1 008',
            'position' => 'Kepala Bidang Bina Marga',
            'phone' => '081398765432',
            'address' => 'Dinas PUPR Kab. Sumbawa',
            'status' => 'active',
        ]);

        // Perangkat Daerah DISKOMINFO
        $diskominfoUser = User::create([
            'name' => 'Ahmad Rizki, S.Kom',
            'email' => 'diskominfo@sumbawakab.go.id',
            'password' => Hash::make('password'),
            'role' => 'opd',
            'opd_id' => $createdOpds['DISKOMINFO']->id,
            'nip' => '19850314 200801 1 003',
            'position' => 'Pranata Komputer Ahli Muda',
            'phone' => '081377889900',
            'address' => 'Dinas Kominfo Kab. Sumbawa',
            'status' => 'active',
        ]);

        // Perangkat Daerah DLH
        $dlhUser = User::create([
            'name' => 'Siti Rahmawati, S.T.',
            'email' => 'dlh@sumbawakab.go.id',
            'password' => Hash::make('password'),
            'role' => 'opd',
            'opd_id' => $createdOpds['DLH']->id,
            'nip' => '19870921 201101 2 004',
            'position' => 'Kasi Pengelolaan Persampahan',
            'phone' => '081266554433',
            'address' => 'Dinas Lingkungan Hidup Kab. Sumbawa',
            'status' => 'active',
        ]);

        // Perangkat Daerah DISHUB
        $dishubUser = User::create([
            'name' => 'Bambang Sudirman, S.IP',
            'email' => 'dishub@sumbawakab.go.id',
            'password' => Hash::make('password'),
            'role' => 'opd',
            'opd_id' => $createdOpds['DISHUB']->id,
            'nip' => '19820719 200701 1 005',
            'position' => 'Kasi Keselamatan & Lalu Lintas',
            'phone' => '081211223344',
            'address' => 'Dinas Perhubungan Kab. Sumbawa',
            'status' => 'active',
        ]);

        // Warga Demo
        $warga = User::create([
            'name' => 'Lalu Muhammad Fikri',
            'email' => 'warga@sumbawakab.go.id',
            'password' => Hash::make('password'),
            'role' => 'warga',
            'nik' => '5204011204950001',
            'phone' => '081987654321',
            'subdistrict' => 'Sumbawa',
            'address' => 'Jl. Garuda No. 45, Kelurahan Brang Biji, Kec. Sumbawa',
            'status' => 'active',
        ]);

        $warga2 = User::create([
            'name' => 'Nurul Hidayati',
            'email' => 'nurul@sumbawakab.go.id',
            'password' => Hash::make('password'),
            'role' => 'warga',
            'nik' => '5204025508980003',
            'phone' => '087812345678',
            'subdistrict' => 'Labuhan Badas',
            'address' => 'Desa Karang Dima, Kec. Labuhan Badas',
            'status' => 'active',
        ]);

        // 3. Seed Realistic Reports
        // Laporan 1: Jalan Rusak di Jl. Garuda / Labuhan Badas -> Didisposisikan ke PUPR
        $report1 = Report::create([
            'ticket_number' => 'SBW-202610-0001',
            'user_id' => $warga->id,
            'opd_id' => $createdOpds['PUPR']->id,
            'assigned_by_id' => $admin->id,
            'title' => 'Jalan Berlubang Dalam di Dekat Jembatan Brang Biji',
            'category' => 'Jalan Rusak & Jembatan',
            'subdistrict' => 'Sumbawa',
            'location_detail' => 'Jl. Garuda, sekitar 50 meter sebelum jembatan Brang Biji arah barat',
            'description' => 'Terdapat lubang berdiameter sekitar 80 cm dengan kedalaman lebih dari 15 cm di badan jalan utama. Sudah ada 2 pengendara motor yang terjatuh saat hujan deras karena tergenang air.',
            'urgency' => 'mendesak',
            'status' => 'didisposisikan',
            'admin_notes' => 'Tolong Dinas PUPR bidang Bina Marga segera mengecek lokasi dan lakukan penambalan sementara / patching darurat guna mencegah korban kecelakaan lalu lintas.',
            'dispatched_at' => now()->subHours(5),
            'created_at' => now()->subHours(8),
        ]);

        ReportTimeline::create([
            'report_id' => $report1->id,
            'user_id' => $warga->id,
            'status' => 'menunggu_verifikasi',
            'title' => 'Laporan Diajukan oleh Warga',
            'notes' => 'Laporan berhasil dibuat dan masuk ke sistem antrean verifikasi Admin Pemkab.',
            'actor_type' => 'warga',
            'created_at' => now()->subHours(8),
        ]);

        ReportTimeline::create([
            'report_id' => $report1->id,
            'user_id' => $admin->id,
            'status' => 'didisposisikan',
            'title' => 'Laporan Diteruskan ke Dinas PUPR',
            'notes' => 'Admin Pemkab telah memverifikasi laporan dan mendisposisikannya ke Dinas PUPR dengan catatan: "Tolong Dinas PUPR bidang Bina Marga segera survei dan tangani."',
            'actor_type' => 'admin',
            'created_at' => now()->subHours(5),
        ]);

        // Laporan 2: Tumpukan Sampah Liar -> Selesai oleh DLH
        $report2 = Report::create([
            'ticket_number' => 'SBW-202610-0002',
            'user_id' => $warga2->id,
            'opd_id' => $createdOpds['DLH']->id,
            'assigned_by_id' => $admin->id,
            'title' => 'Tumpukan Sampah Liar di Pinggir Jalan Raya Karang Dima',
            'category' => 'Kebersihan & Sampah',
            'subdistrict' => 'Labuhan Badas',
            'location_detail' => 'Jl. Raya Labuhan Badas, dekat perbatasan Desa Karang Dima',
            'description' => 'Ada pembuangan sampah liar yang menumpuk selama hampir 2 minggu. Menimbulkan bau menyengat dan lalat yang mengganggu warga sekitar serta pengguna jalan.',
            'urgency' => 'sedang',
            'status' => 'selesai',
            'admin_notes' => 'Teruskan ke Dinas Lingkungan Hidup untuk pengangkutan dan pembersihan.',
            'dispatched_at' => now()->subDays(2),
            'opd_notes' => 'Tim armada truk DLH telah mengangkut 2 rit sampah di lokasi dan melakukan penyemprotan disinfektan. Kami juga telah memasang plang larangan buang sampah di titik tersebut.',
            'resolved_at' => now()->subHours(10),
            'created_at' => now()->subDays(3),
        ]);

        ReportTimeline::create([
            'report_id' => $report2->id,
            'user_id' => $warga2->id,
            'status' => 'menunggu_verifikasi',
            'title' => 'Laporan Dibuat Warga',
            'notes' => 'Warga melaporkan tumpukan sampah liar di Karang Dima.',
            'actor_type' => 'warga',
            'created_at' => now()->subDays(3),
        ]);

        ReportTimeline::create([
            'report_id' => $report2->id,
            'user_id' => $admin->id,
            'status' => 'didisposisikan',
            'title' => 'Disposisi ke Dinas Lingkungan Hidup',
            'notes' => 'Admin meneruskan laporan ke DLH.',
            'actor_type' => 'admin',
            'created_at' => now()->subDays(2),
        ]);

        ReportTimeline::create([
            'report_id' => $report2->id,
            'user_id' => $dlhUser->id,
            'status' => 'diproses',
            'title' => 'Penanganan oleh Petugas Kebersihan DLH',
            'notes' => 'Jadwal armada truk sampah ditugaskan meluncur ke lokasi.',
            'actor_type' => 'opd',
            'created_at' => now()->subDay(),
        ]);

        ReportTimeline::create([
            'report_id' => $report2->id,
            'user_id' => $dlhUser->id,
            'status' => 'selesai',
            'title' => 'Penanganan Selesai',
            'notes' => 'Sampah telah bersih terangkut dan area disterilkan.',
            'actor_type' => 'opd',
            'created_at' => now()->subHours(10),
        ]);

        // Laporan 3: Lampu Jalan PJU Mati -> Diproses Dishub
        $report3 = Report::create([
            'ticket_number' => 'SBW-202610-0003',
            'user_id' => $warga->id,
            'opd_id' => $createdOpds['DISHUB']->id,
            'assigned_by_id' => $admin->id,
            'title' => 'Lampu PJU Mati Total Sepanjang Jalur Kerato Menuju Unter Iwes',
            'category' => 'Penerangan Jalan (PJU)',
            'subdistrict' => 'Unter Iwes',
            'location_detail' => 'Jalur utama Kerato simpang tiga menuju Unter Iwes',
            'description' => 'Ada sekitar 6 titik lampu penerangan jalan yang padam sejak seminggu lalu. Jalan menjadi gelap gulita di malam hari dan rawan tindak kejahatan serta kecelakaan.',
            'urgency' => 'sedang',
            'status' => 'diproses',
            'admin_notes' => 'Diteruskan ke Dishub untuk perbaikan instalasi PJU.',
            'dispatched_at' => now()->subDays(1),
            'opd_notes' => 'Petugas teknisi PJU Dishub sedang melakukan pemeriksaan panel dan pergantian bohlam serta kabel.',
            'created_at' => now()->subDays(1)->subHours(3),
        ]);

        ReportTimeline::create([
            'report_id' => $report3->id,
            'user_id' => $warga->id,
            'status' => 'menunggu_verifikasi',
            'title' => 'Laporan Diajukan',
            'notes' => 'Laporan lampu penerangan jalan padam.',
            'actor_type' => 'warga',
            'created_at' => now()->subDays(1)->subHours(3),
        ]);

        ReportTimeline::create([
            'report_id' => $report3->id,
            'user_id' => $admin->id,
            'status' => 'didisposisikan',
            'title' => 'Disposisi ke Dinas Perhubungan',
            'notes' => 'Admin menugaskan Dinas Perhubungan untuk perbaikan lampu jalan.',
            'actor_type' => 'admin',
            'created_at' => now()->subDays(1),
        ]);

        ReportTimeline::create([
            'report_id' => $report3->id,
            'user_id' => $dishubUser->id,
            'status' => 'diproses',
            'title' => 'Teknisi Sedang Memperbaiki',
            'notes' => 'Teknisi lapangan Dishub telah turun ke lokasi perbaikan PJU.',
            'actor_type' => 'opd',
            'created_at' => now()->subHours(4),
        ]);

        // Laporan 4: Laporan Baru Menunggu Verifikasi Admin
        $report4 = Report::create([
            'ticket_number' => 'SBW-202610-0004',
            'user_id' => $warga->id,
            'opd_id' => null,
            'assigned_by_id' => null,
            'title' => 'Drainase Tersumbat Menyebabkan Genangan di Simpang Pahlawan',
            'category' => 'Drainase & Saluran Air',
            'subdistrict' => 'Sumbawa',
            'location_detail' => 'Simpang Jalan Pahlawan dekat pertokoan',
            'description' => 'Saluran drainase tertutup sedimen tanah dan sampah plastik sehingga setiap kali hujan air meluap ke badan jalan dan toko warga.',
            'urgency' => 'sedang',
            'status' => 'menunggu_verifikasi',
            'created_at' => now()->subMinutes(45),
        ]);

        ReportTimeline::create([
            'report_id' => $report4->id,
            'user_id' => $warga->id,
            'status' => 'menunggu_verifikasi',
            'title' => 'Laporan Diterima Sistem',
            'notes' => 'Menunggu verifikasi admin untuk penentuan OPD terkait.',
            'actor_type' => 'warga',
            'created_at' => now()->subMinutes(45),
        ]);
    }
}
