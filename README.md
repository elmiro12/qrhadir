
<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>

# Aplikasi Manajemen Event & Absensi QR Code

Aplikasi berbasis web yang dirancang untuk mengelola berbagai jenis event, mulai dari seminar, workshop, hingga kegiatan organisasi. Aplikasi ini menyediakan solusi lengkap dari registrasi peserta secara online, manajemen tiket & ID Card, hingga pencatatan kehadiran (absensi) menggunakan **QR Code Scanner**.

Aplikasi ini sangat cocok untuk panitia event yang membutuhkan sistem yang efisien, cepat, dan modern tanpa memerlukan perangkat keras khusus (cukup menggunakan laptop/smartphone dan webcam).

## 🚀 Fitur Utama

### 📅 Manajemen Event
- **Dashboard Admin**: Pantau statistik peserta dan kehadiran secara real-time.
- **Multi-Event**: Kelola banyak event sekaligus dalam satu sistem.
- **Kustomisasi Event**: Atur detail acara, jenis peserta (VIP, Reguler, Panitia, dll), dan pengaturan sertifikat.

### 📝 Registrasi & Tiketing
- **Formulir Registrasi Publik**: Halaman pendaftaran online yang dapat dibagikan kepada calon peserta.
- **Tiket QR Code**: Peserta otomatis mendapatkan tiket digital berbasis QR Code setelah mendaftar.
- **ID Card Generator**: Buat dan cetak ID Card peserta secara otomatis dengan template yang dapat disesuaikan. Mendukung mode cetak massal (batch).

### 📷 Absensi & Validasi
- **Scan QR Code**: Catat kehadiran peserta dengan memindai tiket atau ID Card mereka menggunakan webcam laptop atau kamera HP.
- **Mode Kiosk**: Halaman khusus untuk gatekeeper/petugas absensi melakukan scanning dengan cepat.
- **Real-time Validation**: Mencegah tiket ganda digunakan atau peserta yang belum terdaftar.

### 🏅 Sertifikat Digital
- **E-Certificate Otomatis**: Peserta dapat mencari dan mengunduh sertifikat mereka sendiri setelah acara selesai.
- **Verifikasi Sertifikat**: QR Code pada sertifikat untuk memvalidasi keaslian dokumen.
- **Tanda Tangan Digital**: Pengaturan tanda tangan panitia/pejabat pada sertifikat.

### 📊 Laporan & Data
- **Manajemen Peserta**: Database peserta yang lengkap dan mudah dicari.
- **Ekspor Data**: Unduh laporan kehadiran dan data peserta ke format **Excel**.
- **Laporan Harian/Event**: Rekapitulasi jumlah peserta yang hadir.

## 🛠️ Teknologi yang Digunakan

- **Backend**: [Laravel 12](https://laravel.com) (PHP 8.2+)
- **Frontend**: [Tailwind CSS 4](https://tailwindcss.com), [Alpine.js](https://alpinejs.dev)
- **Database**: MySQL
- **PWA Support**: Aplikasi dapat diinstal di perangkat (Android/iOS/PC) untuk akses yang lebih mudah.
- **Libraries Utama**:
  - `simplesoftwareio/simple-qrcode`: Generator QR Code.
  - `html5-qrcode`: Scanner QR Code berbasis web.
  - `intervention/image`: Manipulasi gambar untuk ID Card.
  - `maatwebsite/excel`: Ekspor laporan ke Excel.

## 📦 Panduan Instalasi

### Prasyarat
- PHP >= 8.2 dengan Imagick
- Composer
- Node.js & NPM
- MySQL Database

### Langkah-Langkah

1.  **Clone Repositori**
    ```bash
    git clone https://github.com/elmiro12/qrhadir.git
    cd qrhadir
    ```

2.  **Install Dependensi**
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Environment**
    Salin file `.env.example` ke `.env` dan sesuaikan database:
    ```bash
    cp .env.example .env
    ```
    Edit `.env`:
    ```ini
    DB_DATABASE=nama_database_event
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Generate Key & Migrasi**
    ```bash
    php artisan key:generate
    php artisan migrate
    ```

5.  **Jalankan Aplikasi**
    Buka dua terminal:
    ```bash
    # Terminal 1
    php artisan serve
    
    # Terminal 2
    npm run dev
    ```

6.  **Login Admin**
    Akses `http://localhost:8000/admin/login`
    *(Silakan buat user admin melalui database atau seeder jika belum tersedia)*

## 📄 Lisensi

Open-source software under the [MIT license](https://opensource.org/licenses/MIT).
