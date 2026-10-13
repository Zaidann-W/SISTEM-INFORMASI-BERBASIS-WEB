# PRODUCT REQUIREMENT DOCUMENT (PRD)
## Proyek: Portal Informasi & Pengumuman Sekolah Berbasis Web
**Tingkat:** Kelas XI RPL  
**Arsitektur:** Model-View-Controller (MVC) — Laravel Framework  

---

## 1. Pendahuluan & Latar Belakang
Sistem ini dirancang sebagai sarana komunikasi digital sekolah yang memfasilitasi publikasi informasi akademik, kegiatan, dan pengumuman resmi. Sistem menggunakan arsitektur web berbasis peran (*role-based access*) untuk memisahkan kewenangan pengelolaan data oleh Admin dan konsumsi informasi oleh Siswa (User).

---

## 2. Aktor Sistem & Batasan Akses
| Aktor | Deskripsi Peran | Batasan Akses |
|---|---|---|
| **Admin** | Pengelola sistem dan penyedia konten sekolah | Memiliki hak penuh untuk melihat dashboard ringkasan statistik dan melakukan operasi CRUD (*Create, Read, Update, Delete*) data informasi. |
| **User (Siswa)** | Pembaca informasi sekolah | Hanya dapat melihat daftar pengumuman dan membaca detail pengumuman. Dilarang mengakses modul pengelolaan admin. |

---

## 3. Spesifikasi Kebutuhan Fungsional (Functional Requirements)

### 3.1. Modul Autentikasi & Otorisasi
- **FR-AUTH-01:** Sistem menyediakan satu gerbang login bersama untuk semua role melalui URL `/login`.
- **FR-AUTH-02:** Sistem melakukan validasi email dan password pengguna.
- **FR-AUTH-03:** Sistem melakukan percabangan navigasi pasca-login:
  - Role `admin` dialihkan secara otomatis ke `/admin/dashboard`.
  - Role `user` dialihkan secara otomatis ke `/informasi`.
- **FR-AUTH-04:** Sistem menyediakan tombol dan mekanisme logout yang membersihkan sesi pengguna.
- **FR-AUTH-05:** Sistem menerapkan *middleware* proteksi rute:
  - Pengguna anonim (belum login) dicegah membuka halaman internal.
  - Role `user` yang mencoba mengakses URL rute admin secara langsung diblokir (HTTP 403 Forbidden atau dialihkan kembali).

### 3.2. Modul Dashboard Admin
- **FR-DASH-01:** Menampilkan indikator statistik dasar:
  - Jumlah total informasi aktif.
  - Jumlah total pengguna terdaftar.
- **FR-DASH-02:** Menyediakan menu navigasi cepat menuju tabel pengelolaan informasi dan tombol tambah pengumuman baru.

### 3.3. Modul Pengelolaan Informasi (Admin)
- **FR-INFO-01 (Create):** Admin dapat membuat informasi baru dengan atribut judul, kategori, dan isi pengumuman lengkap.
- **FR-INFO-02 (Read):** Admin dapat melihat daftar tabel informasi sekolah yang mencakup tanggal posting, judul, serta aksi terkait.
- **FR-INFO-03 (Update):** Admin dapat mengubah data pengumuman yang sudah terbit.
- **FR-INFO-04 (Delete):** Admin dapat menghapus data pengumuman yang sudah kedaluwarsa. Data yang dihapus langsung hilang dari halaman publik siswa.

### 3.4. Modul Informasi Publik (User / Siswa)
- **FR-USER-01:** Siswa dapat melihat daftar kartu pengumuman terbaru dengan urutan waktu rilis (*descending*).
- **FR-USER-02:** Siswa dapat mengklik salah satu kartu pengumuman untuk membaca isi konten secara menyeluruh pada halaman detail.
- **FR-USER-03:** Halaman siswa bersifat murni *read-only* tanpa elemen formulir pengeditan data.

### 3.5. Modul Data Awal (Seeder)
- **FR-SEED-01:** Sistem menyediakan seeder bawaan yang siap dieksekusi untuk membuat akun uji coba:
  - Akun Admin (misal: `admin@sekolah.sch.id`).
  - Akun User (misal: `siswa@sekolah.sch.id`).
- **FR-SEED-02:** Sistem menginisialisasi minimal 3 sampai 5 data artikel pengumuman awal yang relevan dengan lingkungan sekolah.

---

## 4. Kebutuhan Non-Fungsional (Non-Functional Requirements)
- **Security:** Password pengguna wajib disimpan dalam bentuk hash terenkripsi (*Bcrypt* / standar bawaan Laravel).
- **Performance:** Waktu rendering halaman lokal tidak melebihi 2 detik.
- **Reliability:** Data terisolasi dengan baik menggunakan relasi database relational yang konsisten.
- **Maintainability:** Penamaan rute, controller, dan model mengikuti konvensi standar Laravel (PSR-12).

---

## 5. Perancangan Skema Database (Database Schema)

### Tabel: `users`
| Field | Tipe Data | Keterangan |
|---|---|---|
| `id` | BIGINT (Unsigned) | Primary Key, Auto Increment |
| `name` | VARCHAR(100) | Nama lengkap pengguna |
| `email` | VARCHAR(100) | Unique, alamat email login |
| `password` | VARCHAR(255) | Hash password |
| `role` | ENUM('admin', 'user') | Default: 'user' |
| `created_at` | TIMESTAMP | Tanggal dibuat |
| `updated_at` | TIMESTAMP | Tanggal diubah |

### Tabel: `informasi`
| Field | Tipe Data | Keterangan |
|---|---|---|
| `id` | BIGINT (Unsigned) | Primary Key, Auto Increment |
| `user_id` | BIGINT (Unsigned) | Foreign Key -> `users(id)` ON DELETE CASCADE |
| `judul` | VARCHAR(255) | Judul pengumuman |
| `kategori` | VARCHAR(50) | Misal: Akademik, Ekstrakurikuler, Libur |
| `isi` | TEXT / LONGTEXT | Uraian lengkap pengumuman |
| `created_at` | TIMESTAMP | Tanggal dibuat |
| `updated_at` | TIMESTAMP | Tanggal diubah |

---

## 6. Struktur Routing Aplikasi (`routes/web.php`)

*Catatan: Baris paling atas file rute wajib memuat komentar penanda pengerjaan mandiri.*

```php
// Dikerjakan secara mandiri

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InformasiController;
use App\Http\Controllers\User\UserInformasiController;

// Rute Publik (Login / Guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.process');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rute Terproteksi: Role User (Siswa)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/informasi', [UserInformasiController::class, 'index'])->name('user.informasi.index');
    Route::get('/informasi/{id}', [UserInformasiController::class, 'show'])->name('user.informasi.show');
});

// Rute Terproteksi: Role Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('informasi', InformasiController::class);
});
```

---

## 7. Skenario Pengujian Sistem (Acceptance Criteria)

1. **Uji Login Multi-Role:**
   - Masuk menggunakan akun admin $\rightarrow$ sistem mengarahkan ke halaman `/admin/dashboard`.
   - Masuk menggunakan akun siswa $\rightarrow$ sistem mengarahkan ke halaman `/informasi`.
2. **Uji Keamanan Akses (Bypass URL):**
   - Login sebagai akun siswa, lalu masukkan URL `/admin/dashboard` secara manual di peramban. Sistem harus menolak akses dan memunculkan error atau melempar balik ke halaman siswa.
3. **Uji Siklus CRUD Informasi:**
   - Admin menambah pengumuman baru $\rightarrow$ data muncul di tabel admin dan halaman siswa.
   - Admin mengedit pengumuman $\rightarrow$ perubahan terbarui di kedua sisi.
   - Admin menghapus pengumuman $\rightarrow$ pengumuman tersebut tidak lagi muncul di halaman siswa.