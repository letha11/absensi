# Presensi GPS - Sistem Absensi Karyawan Berbasis GPS

## Tentang Proyek

Presensi GPS adalah sebuah sistem informasi absensi karyawan yang dikembangkan menggunakan framework Laravel. Aplikasi ini memungkinkan pencatatan kehadiran karyawan secara digital dengan memanfaatkan teknologi GPS untuk validasi lokasi.

## Prasyarat

Pastikan perangkat Anda telah terinstal perangkat lunak berikut:

- PHP (Versi 8.1 atau lebih tinggi direkomendasikan)
- Composer (Manajer dependensi PHP)
- Web Server (Contoh: Apache, Nginx)
- Database Server (Contoh: MySQL, PostgreSQL, MariaDB)

Jika Anda menggunakan Laragon / XAMPP (Not tested), sebagian besar prasyarat sudah terpenuhi secara otomatis, sehingga bisa langsung ke proses selanjutnya.

## Panduan Instalasi Langkah Demi Langkah

Berikut adalah langkah-langkah untuk menginstal dan menjalankan proyek ini dari awal:

1.  **Clone Repository**
    ```bash
    git clone https://github.com/letha11/absensi.git presensigps
    ```

2.  **Masuk ke Direktori Proyek**
    ```bash
    cd presensigps
    ```

3.  **Install Dependensi Composer**
    ```bash
    composer install
    ```
    Perintah ini akan mengunduh dan menginstal semua pustaka PHP yang dibutuhkan oleh proyek.

4.  **Salin File Konfigurasi Lingkungan**
    Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```

5.  **Generate Kunci Aplikasi**
    ```bash
    php artisan key:generate
    ```
    Kunci ini digunakan oleh Laravel untuk enkripsi dan keamanan.

6.  **Konfigurasi Database**
    Buka file `.env` dan sesuaikan konfigurasi database berikut dengan pengaturan server database Anda:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=user_database_anda
    DB_PASSWORD=password_database_anda
    ```
    Ganti `nama_database_anda`, `user_database_anda`, dan `password_database_anda` sesuai dengan konfigurasi Anda. Pastikan database dengan nama yang Anda tentukan sudah dibuat di server database Anda.

7.  **Jalankan Migrasi Database**
    ```bash
    php artisan migrate:fresh
    ```
    Perintah ini akan membuat semua tabel yang dibutuhkan oleh aplikasi di database Anda.

8.  **(PENTING!!!) Jalankan Seeder Database**
    Perintah ini akan membuat pengguna default untuk setiap role dalam sistem. Jalankan perintah berikut untuk mengisi data awal pengguna:
    ```bash
    php artisan db:seed --class=UserSeeder
    ```
    Pengguna default yang akan dibuat adalah sebagai berikut (semua menggunakan password default: `password`):

    | Peran                   | Email (Username)          | Password   |
    |-------------------------|---------------------------|------------|
    | Direktur                | `direktur@example.com`    | `password` |
    | Operasional Direktur    | `op_direktur@example.com` | `password` |
    | HRD                     | `hrd@example.com`         | `password` |
    | Admin                   | `admin@example.com`       | `password` |
    


9.  **Buat Symbolic Link untuk Storage**
    ```bash
    php artisan storage:link
    ```
    Perintah ini akan membuat symbolic link dari `public/storage` ke `storage/app/public`, yang memungkinkan file di storage publik dapat diakses melalui web.

10. **Jalankan Server Pengembangan Laravel**
    ```bash
    php artisan serve
    ```
    Secara default, aplikasi akan berjalan di `http://127.0.0.1:8000`.

## PENTING!

Sebelum menggunakan aplikasi, Anda HARUS melakukan langkah-langkah berikut:
1. Login ke Admin Panel melalui URL `http://127.0.0.1:8000/panel` dengan kredensial admin yang telah dibuat
2. Lakukan konfigurasi lokasi kantor terlebih dahulu
3. Setelah lokasi kantor dikonfigurasi, baru Anda dapat menggunakan fitur-fitur lain dalam aplikasi

Jika konfigurasi lokasi kantor tidak dilakukan, sistem presensi tidak akan berfungsi dengan benar.


## Mengakses Aplikasi

Setelah server pengembangan berjalan, buka browser Anda dan akses alamat `http://127.0.0.1:8000` (atau port lain jika Anda menjalankannya pada port yang berbeda).

## URL Penting

Berikut adalah contoh URL yang mungkin sering Anda gunakan. Sesuaikan path URL ini jika berbeda dengan konfigurasi routing proyek Anda:

-   **Login Karyawan:**
    -   URL: `http://127.0.0.1:8000/`
-   **Dashboard Karyawan:**
    -   URL: `http://127.0.0.1:8000/dashboard` (setelah login)

-   **Login Admin:**
    -   URL: `http://127.0.0.1:8000/panel`
-   **Dashboard Admin:**
    -   URL: `http://127.0.0.1:8000/panel/dashboardadmin` (setelah login)

## Peran Pengguna (User Roles)

Sistem ini memiliki beberapa peran pengguna dengan hak akses yang berbeda, baik untuk panel admin maupun untuk fungsionalitas karyawan:

### Panel Admin

Pengguna yang login melalui `/panel` akan memiliki salah satu role berikut, dengan akses yang diatur di panel admin:

*   **Direktur:**
    *   Memiliki akses penuh ke semua fitur di panel admin.
    *   Dapat mengelola data karyawan (tambah, edit, hapus).
    *   Melihat monitoring presensi karyawan.
    *   Mengakses dan mencetak laporan presensi.
    *   Memberikan persetujuan atau penolakan untuk pengajuan izin/sakit.
    *   Mengelola konfigurasi sistem (misalnya, lokasi kantor).

*   **Operasional Direktur:**
    *   Melihat monitoring presensi karyawan.
    *   Mengakses dan mencetak laporan presensi.
    *   Memberikan persetujuan atau penolakan untuk pengajuan izin/sakit.

*   **HRD (Human Resources Development):**
    *   Mengelola data karyawan (tambah, edit, hapus).
    *   Mengakses dan mencetak laporan presensi.
    *   Memberikan persetujuan atau penolakan untuk pengajuan izin/sakit.

*   **Admin:**
    *   Mengelola data karyawan (tambah, edit, hapus).
    *   Memberikan persetujuan atau penolakan untuk pengajuan izin/sakit.

### Pengguna Karyawan (`Karyawan`)

Pengguna yang login melalui halaman login utama (`/`) adalah karyawan, dengan fungsionalitas sebagai berikut:

*   **Melakukan Presensi:**
    *   Mencatat kehadiran (check-in) dan kepulangan (check-out) berbasis lokasi GPS.
*   **Melihat Riwayat Presensi:**
    *   Mengakses data histori presensi pribadi.
*   **Mengajukan Izin/Sakit:**
    *   Membuat dan mengirim pengajuan izin atau sakit.
*   **Mengelola Profil:**
    *   Memperbarui informasi profil pribadi.
