<?php

namespace Database\Seeders;

use App\Models\Informasi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@sekolah.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Siswa SMK',
            'email' => 'siswa@sekolah.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $informasis = [
            [
                'judul' => 'Penting: Pelaksanaan Penilaian Sumatif Akhir Jenjang (PSAJ) 2026',
                'kategori' => 'Akademik',
                'isi' => "Diberitahukan kepada seluruh siswa kelas XII bahwa Penilaian Sumatif Akhir Jenjang (PSAJ) berbasis komputer akan dilaksanakan pada tanggal 23 - 28 Maret 2026.\n\nKetentuan Peserta Ujian:\n1. Hadir di ruang laboratorium 15 menit sebelum sesi dimulai.\n2. Wajib membawa kartu peserta ujian yang telah dicap resmi tata usaha.\n3. Mengenakan seragam putih abu-abu lengkap dan bersepatu hitam.\n\nHarap memastikan akun ujian masing-masing sudah terverifikasi melalui wali kelas sebelum hari H pelaksanaan.",
                'is_pinned' => true,
                'status' => 'published',
                'views' => 124,
            ],
            [
                'judul' => 'Pembekalan dan Pelepasan Praktik Kerja Lapangan (PKL) Angkatan XII',
                'kategori' => 'Pengumuman',
                'isi' => "Kegiatan pembekalan teknis dan pelepasan siswa Praktik Kerja Lapangan (PKL) program keahlian Rekayasa Perangkat Lunak dan Teknik Jaringan Komputer akan digelar pada hari Senin di aula utama sekolah.\n\nPeserta diwajibkan mengenakan seragam praktik kerja lengkap dan membawa buku jurnal kegiatan serta surat pengantar dari dunia industri mitra. Kehadiran bersifat wajib bagi seluruh siswa tingkat XI.",
                'is_pinned' => false,
                'status' => 'published',
                'views' => 88,
            ],
            [
                'judul' => 'Pendaftaran Ekstrakurikuler Kejuruan: Komunitas Cyber Security & IoT',
                'kategori' => 'Ekstrakurikuler',
                'isi' => "Pendaftaran unit kegiatan siswa bidang kejuruan teknologi informasi resmi dibuka untuk siswa kelas X dan XI. Ekstrakurikuler ini difokuskan pada penguasaan kerangka kerja modern, pemrograman backend, serta kesiapan mengikuti ajang Lomba Kompetensi Siswa (LKS).\n\nFormulir pendaftaran dapat diserahkan ke ruang laboratorium komputer 2 paling lambat akhir pekan ini.",
                'is_pinned' => false,
                'status' => 'published',
                'views' => 45,
            ],
            [
                'judul' => 'Sosialisasi Uji Sertifikasi Kompetensi (USK) Bersama Lembaga Sertifikasi Profesi',
                'kategori' => 'Akademik',
                'isi' => "Dalam rangka memastikan keselarasan kompetensi lulusan dengan standar industri, pihak sekolah menyelenggarakan sosialisasi asesmen sertifikasi profesi berlisensi BNSP.\n\nSeluruh calon asesi diharapkan melengkapi berkas portofolio proyek dan bukti kompetensi pendukung sebelum jadwal verifikasi dokumen oleh tim asesor internal.",
                'is_pinned' => false,
                'status' => 'published',
                'views' => 67,
            ],
        ];

        foreach ($informasis as $data) {
            Informasi::create([
                'user_id' => $admin->id,
                'judul' => $data['judul'],
                'kategori' => $data['kategori'],
                'isi' => $data['isi'],
                'is_pinned' => $data['is_pinned'],
                'status' => $data['status'],
                'views' => $data['views'],
            ]);
        }
    }
}
