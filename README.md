# Kuiz Digital — Bank Soal & Ujian Online UNSADA

Kuiz Digital adalah aplikasi **bank soal dan ujian online** untuk Universitas Darma Persada. Dosen menyusun soal dan menjadwalkan ujian, mahasiswa mengerjakannya langsung dari browser (HP maupun laptop), lalu nilai tersaji otomatis.

Dibangun dengan **Laravel 12**, **Filament 4** (panel admin), **Livewire 3**, dan **Tailwind CSS 4**.

## Fitur Utama

| Peran | Yang bisa dilakukan |
| --- | --- |
| **Kaprodi** | Mengelola Program Studi, Mata Kuliah, Dosen, Mahasiswa; mengatur tahun akademik serta jenis & tipe soal; memantau semua bank soal dan ujian. |
| **Dosen** | Menyusun bank soal (pilihan ganda / esai), menjadwalkan ujian, melihat hasil mahasiswa, dan menilai jawaban esai. |
| **Mahasiswa** | Mengerjakan ujian yang sedang dibuka untuk mata kuliahnya, lalu melihat nilai, jawaban, dan kunci di Riwayat Ujian. |

- Timer ujian dihitung di server: me-refresh halaman tidak mengulang waktu, dan ujian terkumpul otomatis saat waktu habis.
- Jawaban tersimpan otomatis; ujian bisa dilanjutkan jika tab tertutup (selama waktu masih ada).
- Pilihan ganda dinilai otomatis; esai dinilai dosen lengkap dengan catatan.
- Kunci jawaban baru tampil setelah jadwal ujian berakhir.
- Login dengan **kode OTP via email** (aktif setelah Gmail dikonfigurasi, lihat [Mengaktifkan OTP Email](#mengaktifkan-otp-email-gmail)).

---

## Kebutuhan Sistem

| Perangkat | Versi | Catatan |
| --- | --- | --- |
| PHP | 8.2 atau lebih baru | Ekstensi wajib: `pdo_sqlite` (atau `pdo_mysql`), `intl`, `mbstring`, `fileinfo`, `openssl`, `zip` |
| Composer | 2.x | https://getcomposer.org |
| Node.js & npm | Node 20 atau lebih baru | https://nodejs.org |
| Git | terbaru | https://git-scm.com |
| Database | SQLite (bawaan) **atau** MySQL/MariaDB | Pengguna XAMPP bisa memakai MySQL dari XAMPP |

> **Pengguna XAMPP (Windows):** pastikan baris `extension=intl`, `extension=zip`, dan `extension=pdo_sqlite` di `C:\xampp\php\php.ini` **tidak** diawali tanda `;`. Cek dengan perintah `php -m`.

---

## Instalasi (dari awal sampai bisa diakses)

Semua perintah dijalankan di terminal (Git Bash, PowerShell, atau Terminal).

### 1. Clone repository

```bash
git clone https://github.com/muhammamdsurya/Quizies.git
cd Quizies
```

### 2. Install dependency PHP (Composer)

```bash
composer install
```

### 3. Buat file `.env`

```bash
cp .env.example .env
```

> Di PowerShell / CMD Windows gunakan: `copy .env.example .env`

### 4. Buat application key

```bash
php artisan key:generate
```

### 5. Siapkan database

Pilih **salah satu**.

**Opsi A: SQLite (paling mudah, tanpa server database)**

Pengaturan bawaan `.env` sudah memakai SQLite (`DB_CONNECTION=sqlite`). Cukup buat file databasenya:

```bash
touch database/database.sqlite
```

> Di PowerShell: `New-Item database/database.sqlite -ItemType File`

**Opsi B: MySQL / MariaDB (misalnya dari XAMPP)**

1. Jalankan MySQL (XAMPP Control Panel → Start MySQL).
2. Buat database kosong, misalnya `kuiz_digital`, lewat phpMyAdmin (`http://localhost/phpmyadmin`).
3. Ubah bagian database di `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kuiz_digital
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 6. (Opsional) Atur email akun Kaprodi

Jika nanti OTP email akan diaktifkan, isi email asli Anda di `.env` **sebelum** menjalankan seeder, agar akun Kaprodi bisa menerima kode OTP:

```env
SEED_KAPRODI_EMAIL=emailanda@gmail.com
```

### 7. Jalankan migration & seeder

Perintah ini membuat semua tabel lalu mengisi data contoh (prodi, mata kuliah, dosen, mahasiswa):

```bash
php artisan migrate --seed
```

> Untuk mengulang dari nol (**semua data akan dihapus**): `php artisan migrate:fresh --seed`

### 8. Install dependency frontend & build aset

```bash
npm install
npm run build
```

### 9. Jalankan aplikasi

```bash
php artisan serve
```

Buka di browser:

- Halaman utama: **http://127.0.0.1:8000**
- Halaman login (semua peran): **http://127.0.0.1:8000/admin/login**

Selesai! 🎉

### Akun demo (hasil seeder)

| Peran | Email | Password |
| --- | --- | --- |
| Kaprodi | `kaprodi@kuiz.test` (atau isi `SEED_KAPRODI_EMAIL`) | `password123` |
| Dosen | `dosen@kuiz.test` | `password123` |
| Mahasiswa | `mahasiswa@kuiz.test` | `password123` |

Seeder juga membuat 20 akun dosen/mahasiswa acak dengan password `password`.

> ⚠️ Segera ganti password akun demo sebelum aplikasi dipakai sungguhan (menu **Profil** di pojok kanan atas).
>
> Akun berakhiran `.test` tidak bisa menerima email. Setelah OTP diaktifkan, akun tersebut tidak bisa login sampai emailnya diganti dengan email asli (Kaprodi bisa mengubahnya lewat menu **Data Dosen** / **Data Mahasiswa**).

---

## Mengaktifkan OTP Email (Gmail)

Setelah diaktifkan, setiap login meminta **kode 6 digit** yang dikirim ke email akun. Kode berlaku 10 menit dan bisa dikirim ulang dari halaman login.

1. Buka https://myaccount.google.com/security lalu aktifkan **Verifikasi 2 Langkah**.
2. Buka https://myaccount.google.com/apppasswords, buat App Password baru (misalnya bernama "Kuiz Digital"), lalu salin 16 karakternya.
3. Isi di file `.env` (App Password ditulis **tanpa spasi**):

   ```env
   MAIL_USERNAME=emailanda@gmail.com
   MAIL_PASSWORD=abcdefghijklmnop
   ```

   Pengaturan lain (`MAIL_MAILER=smtp`, `MAIL_HOST=smtp.gmail.com`, `MAIL_PORT=587`) sudah tersedia di `.env.example`.

4. Bersihkan cache konfigurasi:

   ```bash
   php artisan config:clear
   ```

5. Pastikan akun yang akan login memakai **email asli** (lihat [langkah 6](#6-opsional-atur-email-akun-kaprodi)), lalu coba login.

Cara kerjanya:

- Selama `MAIL_PASSWORD` **kosong**, OTP **nonaktif** dan semua email (misalnya reset password) hanya ditulis ke `storage/logs/laravel.log`.
- Begitu `MAIL_PASSWORD` **diisi**, OTP otomatis **wajib** untuk semua akun.
- Untuk mematikan OTP sementara (misalnya terkunci karena email salah), kosongkan lagi `MAIL_PASSWORD` lalu jalankan `php artisan config:clear`.

---

## Alur Penggunaan Singkat

1. **Kaprodi** membuat *Pengaturan Soal* (tahun akademik, jenis & tipe soal yang diizinkan), lalu melengkapi data Prodi, Mata Kuliah, Dosen, dan Mahasiswa (termasuk mata kuliah yang diambil mahasiswa).
2. **Dosen** membuat paket soal di menu *Bank Soal*, lalu menjadwalkannya di menu *Buat Ujian* (waktu mulai, waktu selesai, durasi).
3. **Mahasiswa** membuka *Ujian Tersedia* saat jadwal berlangsung, klik **Mulai Ujian**, kerjakan, lalu **Kumpulkan**.
4. **Dosen** membuka detail ujian → tabel *Hasil Mahasiswa* → **Nilai Esai** (khusus ujian esai).
5. **Mahasiswa** melihat nilai dan pembahasan di *Riwayat Ujian*.

---

## Pengembangan

Jalankan dua terminal saat mengembangkan tampilan, agar perubahan CSS langsung terlihat:

```bash
php artisan serve
```

```bash
npm run dev
```

> `composer run dev` memakai Laravel Pail yang butuh ekstensi `pcntl`, sehingga **tidak berjalan di Windows**. Gunakan dua perintah di atas.

Menjalankan test:

```bash
php artisan test
```

Merapikan format kode (Laravel Pint):

```bash
vendor/bin/pint
```

---

## Troubleshooting

| Masalah | Solusi |
| --- | --- |
| `Vite manifest not found` | Jalankan `npm run build` (atau `npm run dev` saat pengembangan). |
| `could not find driver` | Aktifkan `extension=pdo_sqlite` (atau `pdo_mysql`) di `php.ini`, lalu buka ulang terminal. |
| `No application encryption key has been specified` | Jalankan `php artisan key:generate`. |
| `Database file at path [...] does not exist` | Buat file `database/database.sqlite` (langkah 5 Opsi A). |
| Kode OTP tidak masuk | Cek folder Spam; pastikan `MAIL_USERNAME`/`MAIL_PASSWORD` benar lalu `php artisan config:clear`. Error `535 Authentication failed` berarti App Password salah. |
| Perubahan `.env` tidak terbaca | Jalankan `php artisan config:clear`. |
| Tampilan tidak berubah setelah edit view | Jalankan `php artisan view:clear` dan muat ulang browser (Ctrl+F5). |
| Port 8000 sudah dipakai | Jalankan `php artisan serve --port=8080` lalu buka `http://127.0.0.1:8080`. |
