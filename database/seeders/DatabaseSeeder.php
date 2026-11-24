<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Default (Admin & User)
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'email' => 'admin@sispak.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['username' => 'user'],
            [
                'name' => 'User Pengguna',
                'email' => 'user@sispak.id',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        // 2. Data 10 Kerusakan Laptop (Tabel 1 Jurnal TIKomSiN 2025)
        $kerusakan = [
            [
                'id_kerusakan' => 1,
                'kode_kerusakan' => 'K01',
                'nama_kerusakan' => 'Kerusakan pada RAM',
                'deskripsi' => 'Gangguan pada modul memori utama (Random Access Memory).',
                'solusi' => 'Bersihkan pin kuningan RAM dengan penghapus karet lembut, coba pindahkan ke slot RAM lain, atau ganti modul RAM baru jika tetap bermasalah.',
            ],
            [
                'id_kerusakan' => 2,
                'kode_kerusakan' => 'K02',
                'nama_kerusakan' => 'Kerusakan pada Layar',
                'deskripsi' => 'Kerusakan pada panel LCD/LED, kabel fleksibel layar, atau inverter backlight.',
                'solusi' => 'Hubungkan laptop ke monitor eksternal untuk memastikan kerusakan panel, periksa kabel fleksibel LCD, atau lakukan penggantian panel layar.',
            ],
            [
                'id_kerusakan' => 3,
                'kode_kerusakan' => 'K03',
                'nama_kerusakan' => 'Kerusakan pada Harddisk',
                'deskripsi' => 'Terjadi bad sector atau kegagalan mekanis pada media penyimpanan (HDD/SSD).',
                'solusi' => 'Segera lakukan backup data penting, jalankan pemeriksaan disk (CHKDSK), atau ganti media penyimpanan ke SSD baru.',
            ],
            [
                'id_kerusakan' => 4,
                'kode_kerusakan' => 'K04',
                'nama_kerusakan' => 'Kerusakan pada Keyboard',
                'deskripsi' => 'Kerusakan pada jalur matriks tombol atau fleksibel keyboard.',
                'solusi' => 'Bersihkan sela tombol dari debu, uji menggunakan keyboard eksternal USB, dan ganti modul keyboard internal jika kerusakan permanen.',
            ],
            [
                'id_kerusakan' => 5,
                'kode_kerusakan' => 'K05',
                'nama_kerusakan' => 'Kerusakan pada Webcam',
                'deskripsi' => 'Gangguan pada modul kamera internal atau kabel fleksibel kamera.',
                'solusi' => 'Periksa pengaturan privasi kamera dan perbarui driver webcam. Jika tidak terdeteksi secara fisik, periksa konektor atau ganti modul webcam.',
            ],
            [
                'id_kerusakan' => 6,
                'kode_kerusakan' => 'K06',
                'nama_kerusakan' => 'Kerusakan pada Baterai',
                'deskripsi' => 'Penurunan kapasitas sel baterai (wear level tinggi) atau baterai menggelembung.',
                'solusi' => 'Lakukan kalibrasi baterai. Jika baterai menggelembung atau daya tidak tertampung, segera lepas dan ganti dengan baterai original baru.',
            ],
            [
                'id_kerusakan' => 7,
                'kode_kerusakan' => 'K07',
                'nama_kerusakan' => 'Kerusakan pada Sistem Operasi',
                'deskripsi' => 'Korupsi file sistem, infeksi malware, atau konflik driver pada OS.',
                'solusi' => 'Lakukan pemindaian antivirus, jalankan System File Checker (sfc /scannow), gunakan fitur System Restore, atau lakukan instalasi ulang OS.',
            ],
            [
                'id_kerusakan' => 8,
                'kode_kerusakan' => 'K08',
                'nama_kerusakan' => 'Kerusakan pada VGA',
                'deskripsi' => 'Gangguan pada unit pengolah grafis (GPU) atau VRAM.',
                'solusi' => 'Perbarui atau instal ulang driver grafis secara bersih (clean install). Jika muncul artifacting parah, lakukan pengecekan chip VGA oleh teknisi.',
            ],
            [
                'id_kerusakan' => 9,
                'kode_kerusakan' => 'K09',
                'nama_kerusakan' => 'Kerusakan pada Chip Prosesor',
                'deskripsi' => 'Overheating ekstrem atau kegagalan fungsi pada CPU.',
                'solusi' => 'Bersihkan kipas pendingin (fan) dan heatsink dari debu, ganti thermal paste prosesor, serta pastikan sirkulasi udara laptop lancar.',
            ],
            [
                'id_kerusakan' => 10,
                'kode_kerusakan' => 'K10',
                'nama_kerusakan' => 'Kerusakan pada Komponen Motherboard',
                'deskripsi' => 'Korsleting (short) atau kerusakan komponen IC/kapasitor pada papan induk.',
                'solusi' => 'Lepaskan baterai dan adaptor segera untuk mencegah kerusakan meluas, lalu bawa ke teknisi ahli untuk pengecekan jalur tegangan motherboard.',
            ],
        ];

        foreach ($kerusakan as $k) {
            DB::table('kerusakan')->updateOrInsert(
                ['kode_kerusakan' => $k['kode_kerusakan']],
                [
                    'id_kerusakan' => $k['id_kerusakan'],
                    'nama_kerusakan' => $k['nama_kerusakan'],
                    'deskripsi' => $k['deskripsi'],
                    'solusi' => $k['solusi'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 3. Data 40 Gejala Laptop (Tabel 2 Jurnal TIKomSiN 2025)
        $gejala = [
            ['id_gejala' => 1, 'kode_gejala' => 'G01', 'nama_gejala' => 'Layar blue screen dengan laporan "dumping physical memory to disk"'],
            ['id_gejala' => 2, 'kode_gejala' => 'G02', 'nama_gejala' => 'Muncul bunyi beep panjang atau pendek ketika laptop dihidupkan'],
            ['id_gejala' => 3, 'kode_gejala' => 'G03', 'nama_gejala' => 'Layar blank hitam ketika laptop dihidupkan'],
            ['id_gejala' => 4, 'kode_gejala' => 'G04', 'nama_gejala' => 'Laptop menjadi crash atau system freeze'],
            ['id_gejala' => 5, 'kode_gejala' => 'G05', 'nama_gejala' => 'Layar laptop bergaris vertical maupun horizontal'],
            ['id_gejala' => 6, 'kode_gejala' => 'G06', 'nama_gejala' => 'Layar redup'],
            ['id_gejala' => 7, 'kode_gejala' => 'G07', 'nama_gejala' => 'Layar berkedip - kedip'],
            ['id_gejala' => 8, 'kode_gejala' => 'G08', 'nama_gejala' => 'Harddisk tidak terdeteksi oleh sistem'],
            ['id_gejala' => 9, 'kode_gejala' => 'G09', 'nama_gejala' => 'Data mengalami kerusakan atau hilang'],
            ['id_gejala' => 10, 'kode_gejala' => 'G10', 'nama_gejala' => 'Kinerja laptop lambat'],
            ['id_gejala' => 11, 'kode_gejala' => 'G11', 'nama_gejala' => 'Timbul suara aneh pada harddisk'],
            ['id_gejala' => 12, 'kode_gejala' => 'G12', 'nama_gejala' => 'Proses booting menjadi lambat'],
            ['id_gejala' => 13, 'kode_gejala' => 'G13', 'nama_gejala' => 'Sebagian atau semua tombol keyboard tidak berfungsi'],
            ['id_gejala' => 14, 'kode_gejala' => 'G14', 'nama_gejala' => 'Layar flicker atau bergoyang'],
            ['id_gejala' => 15, 'kode_gejala' => 'G15', 'nama_gejala' => 'Keyboard mengetik sendiri'],
            ['id_gejala' => 16, 'kode_gejala' => 'G16', 'nama_gejala' => 'Ketika tombol tertentu ditekan hasilnya tidak sesuai atau mengacak'],
            ['id_gejala' => 17, 'kode_gejala' => 'G17', 'nama_gejala' => 'Laptop tidak bisa dinyalakan jika tombol power berada di keyboard'],
            ['id_gejala' => 18, 'kode_gejala' => 'G18', 'nama_gejala' => 'Aplikasi webcam tidak dapat dibuka'],
            ['id_gejala' => 19, 'kode_gejala' => 'G19', 'nama_gejala' => 'Kamera tidak terdeteksi'],
            ['id_gejala' => 20, 'kode_gejala' => 'G20', 'nama_gejala' => 'Hasil gambar kamera buram atau jelek'],
            ['id_gejala' => 21, 'kode_gejala' => 'G21', 'nama_gejala' => 'Hasil video tidak mengeluarkan suara'],
            ['id_gejala' => 22, 'kode_gejala' => 'G22', 'nama_gejala' => 'Baterai cepat terkuras habis'],
            ['id_gejala' => 23, 'kode_gejala' => 'G23', 'nama_gejala' => 'Baterai tidak dapat terisi penuh 100%'],
            ['id_gejala' => 24, 'kode_gejala' => 'G24', 'nama_gejala' => 'Perubahan fisik pada baterai (menggelembung)'],
            ['id_gejala' => 25, 'kode_gejala' => 'G25', 'nama_gejala' => 'Baterai tidak dapat menampung daya'],
            ['id_gejala' => 26, 'kode_gejala' => 'G26', 'nama_gejala' => 'Baterai tidak terdeteksi'],
            ['id_gejala' => 27, 'kode_gejala' => 'G27', 'nama_gejala' => 'Sering macet saat digunakan'],
            ['id_gejala' => 28, 'kode_gejala' => 'G28', 'nama_gejala' => 'Tidak bisa membuka aplikasi'],
            ['id_gejala' => 29, 'kode_gejala' => 'G29', 'nama_gejala' => 'Aplikasi sering keluar sendiri'],
            ['id_gejala' => 30, 'kode_gejala' => 'G30', 'nama_gejala' => 'Terjadi Bluescreen'],
            ['id_gejala' => 31, 'kode_gejala' => 'G31', 'nama_gejala' => 'Tampilan visual menjadi patah - patah'],
            ['id_gejala' => 32, 'kode_gejala' => 'G32', 'nama_gejala' => 'Muncul gambar atau garis aneh bertekstur (artifacting)'],
            ['id_gejala' => 33, 'kode_gejala' => 'G33', 'nama_gejala' => 'Tampilan no display'],
            ['id_gejala' => 34, 'kode_gejala' => 'G34', 'nama_gejala' => 'Laptop tiba – tiba mati saat digunakan'],
            ['id_gejala' => 35, 'kode_gejala' => 'G35', 'nama_gejala' => 'Sistem hanya menyala sementara atau sering restart berulang'],
            ['id_gejala' => 36, 'kode_gejala' => 'G36', 'nama_gejala' => 'Laptop tidak bisa hidup sama sekali'],
            ['id_gejala' => 37, 'kode_gejala' => 'G37', 'nama_gejala' => 'Laptop sering mengalami overheating'],
            ['id_gejala' => 38, 'kode_gejala' => 'G38', 'nama_gejala' => 'Ada komponen fisik yang rusak atau terbakar pada motherboard'],
            ['id_gejala' => 39, 'kode_gejala' => 'G39', 'nama_gejala' => 'Laptop sulit dihidupkan'],
            ['id_gejala' => 40, 'kode_gejala' => 'G40', 'nama_gejala' => 'Indikator charging nyala tapi ketika dihidupkan tidak bisa'],
        ];

        foreach ($gejala as $g) {
            DB::table('gejala')->updateOrInsert(
                ['kode_gejala' => $g['kode_gejala']],
                [
                    'id_gejala' => $g['id_gejala'],
                    'nama_gejala' => $g['nama_gejala'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // 4. Data Basis Pengetahuan / Kaidah Produksi CF (Tabel 5 Jurnal TIKomSiN 2025)
        $rules = [
            // K01 - Kerusakan pada RAM
            ['id_kerusakan' => 1, 'id_gejala' => 1,  'mb' => 1.0, 'md' => 0.0, 'cf_pakar' => 1.00],
            ['id_kerusakan' => 1, 'id_gejala' => 2,  'mb' => 0.4, 'md' => 0.0, 'cf_pakar' => 0.40],
            ['id_kerusakan' => 1, 'id_gejala' => 3,  'mb' => 0.4, 'md' => 0.0, 'cf_pakar' => 0.40],
            ['id_kerusakan' => 1, 'id_gejala' => 4,  'mb' => 0.2, 'md' => 0.0, 'cf_pakar' => 0.20],

            // K02 - Kerusakan pada Layar
            ['id_kerusakan' => 2, 'id_gejala' => 5,  'mb' => 0.8, 'md' => 0.0, 'cf_pakar' => 0.80],
            ['id_kerusakan' => 2, 'id_gejala' => 6,  'mb' => 0.6, 'md' => 0.0, 'cf_pakar' => 0.60],
            ['id_kerusakan' => 2, 'id_gejala' => 14, 'mb' => 0.4, 'md' => 0.0, 'cf_pakar' => 0.40],
            ['id_kerusakan' => 2, 'id_gejala' => 7,  'mb' => 0.4, 'md' => 0.0, 'cf_pakar' => 0.40],

            // K03 - Kerusakan pada Harddisk
            ['id_kerusakan' => 3, 'id_gejala' => 8,  'mb' => 0.6, 'md' => 0.0, 'cf_pakar' => 0.60],
            ['id_kerusakan' => 3, 'id_gejala' => 9,  'mb' => 0.4, 'md' => 0.0, 'cf_pakar' => 0.40],
            ['id_kerusakan' => 3, 'id_gejala' => 10, 'mb' => 0.4, 'md' => 0.0, 'cf_pakar' => 0.40],
            ['id_kerusakan' => 3, 'id_gejala' => 11, 'mb' => 0.8, 'md' => 0.0, 'cf_pakar' => 0.80],
            ['id_kerusakan' => 3, 'id_gejala' => 12, 'mb' => 0.2, 'md' => 0.0, 'cf_pakar' => 0.20],

            // K04 - Kerusakan pada Keyboard
            ['id_kerusakan' => 4, 'id_gejala' => 13, 'mb' => 0.8, 'md' => 0.0, 'cf_pakar' => 0.80],
            ['id_kerusakan' => 4, 'id_gejala' => 15, 'mb' => 0.8, 'md' => 0.0, 'cf_pakar' => 0.80],
            ['id_kerusakan' => 4, 'id_gejala' => 16, 'mb' => 0.6, 'md' => 0.0, 'cf_pakar' => 0.60],
            ['id_kerusakan' => 4, 'id_gejala' => 17, 'mb' => 0.2, 'md' => 0.0, 'cf_pakar' => 0.20],

            // K05 - Kerusakan pada Webcam
            ['id_kerusakan' => 5, 'id_gejala' => 18, 'mb' => 0.6, 'md' => 0.0, 'cf_pakar' => 0.60],
            ['id_kerusakan' => 5, 'id_gejala' => 19, 'mb' => 0.6, 'md' => 0.0, 'cf_pakar' => 0.60],
            ['id_kerusakan' => 5, 'id_gejala' => 20, 'mb' => 0.8, 'md' => 0.0, 'cf_pakar' => 0.80],
            ['id_kerusakan' => 5, 'id_gejala' => 21, 'mb' => 0.2, 'md' => 0.0, 'cf_pakar' => 0.20],

            // K06 - Kerusakan pada Baterai
            ['id_kerusakan' => 6, 'id_gejala' => 22, 'mb' => 0.8, 'md' => 0.0, 'cf_pakar' => 0.80],
            ['id_kerusakan' => 6, 'id_gejala' => 23, 'mb' => 0.8, 'md' => 0.0, 'cf_pakar' => 0.80],
            ['id_kerusakan' => 6, 'id_gejala' => 24, 'mb' => 0.8, 'md' => 0.0, 'cf_pakar' => 0.80],
            ['id_kerusakan' => 6, 'id_gejala' => 25, 'mb' => 0.4, 'md' => 0.0, 'cf_pakar' => 0.40],
            ['id_kerusakan' => 6, 'id_gejala' => 26, 'mb' => 0.2, 'md' => 0.0, 'cf_pakar' => 0.20],

            // K07 - Kerusakan pada Sistem Operasi
            ['id_kerusakan' => 7, 'id_gejala' => 10, 'mb' => 0.2, 'md' => 0.0, 'cf_pakar' => 0.20],
            ['id_kerusakan' => 7, 'id_gejala' => 27, 'mb' => 0.4, 'md' => 0.0, 'cf_pakar' => 0.40],
            ['id_kerusakan' => 7, 'id_gejala' => 28, 'mb' => 0.6, 'md' => 0.0, 'cf_pakar' => 0.60],
            ['id_kerusakan' => 7, 'id_gejala' => 29, 'mb' => 0.2, 'md' => 0.0, 'cf_pakar' => 0.20],
            ['id_kerusakan' => 7, 'id_gejala' => 30, 'mb' => 0.6, 'md' => 0.0, 'cf_pakar' => 0.60],

            // K08 - Kerusakan pada VGA
            ['id_kerusakan' => 8, 'id_gejala' => 7,  'mb' => 0.2, 'md' => 0.0, 'cf_pakar' => 0.20],
            ['id_kerusakan' => 8, 'id_gejala' => 31, 'mb' => 0.6, 'md' => 0.0, 'cf_pakar' => 0.60],
            ['id_kerusakan' => 8, 'id_gejala' => 32, 'mb' => 0.8, 'md' => 0.0, 'cf_pakar' => 0.80],
            ['id_kerusakan' => 8, 'id_gejala' => 33, 'mb' => 0.4, 'md' => 0.0, 'cf_pakar' => 0.40],

            // K09 - Kerusakan pada Chip Prosesor
            ['id_kerusakan' => 9, 'id_gejala' => 34, 'mb' => 0.6, 'md' => 0.0, 'cf_pakar' => 0.60],
            ['id_kerusakan' => 9, 'id_gejala' => 35, 'mb' => 0.4, 'md' => 0.0, 'cf_pakar' => 0.40],
            ['id_kerusakan' => 9, 'id_gejala' => 36, 'mb' => 0.2, 'md' => 0.0, 'cf_pakar' => 0.20],
            ['id_kerusakan' => 9, 'id_gejala' => 37, 'mb' => 0.4, 'md' => 0.0, 'cf_pakar' => 0.40],

            // K10 - Kerusakan pada Komponen Motherboard
            ['id_kerusakan' => 10, 'id_gejala' => 36, 'mb' => 0.2, 'md' => 0.0, 'cf_pakar' => 0.20],
            ['id_kerusakan' => 10, 'id_gejala' => 38, 'mb' => 0.8, 'md' => 0.0, 'cf_pakar' => 0.80],
            ['id_kerusakan' => 10, 'id_gejala' => 39, 'mb' => 0.4, 'md' => 0.0, 'cf_pakar' => 0.40],
            ['id_kerusakan' => 10, 'id_gejala' => 40, 'mb' => 0.4, 'md' => 0.0, 'cf_pakar' => 0.40],
        ];

        foreach ($rules as $r) {
            DB::table('basis_pengetahuan')->updateOrInsert(
                [
                    'id_kerusakan' => $r['id_kerusakan'],
                    'id_gejala' => $r['id_gejala'],
                ],
                [
                    'mb' => $r['mb'],
                    'md' => $r['md'],
                    'cf_pakar' => $r['cf_pakar'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
