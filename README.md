# 🦷 Klinik Winardi - Sistem Manajemen Protesa & Rekam Medis Cerdas

Aplikasi berbasis web yang dirancang khusus untuk merevolusi pengelolaan rekam medis dan pesanan protesa gigi pada Klinik Winardi menjadi lebih modern, aman, dan terintegrasi. Sistem ini mendigitalisasi pencatatan riwayat medis manual untuk meminimalkan risiko kehilangan data serta mempercepat operasional klinik.

---

## 🚀 Fitur Utama

* **Widget Statistik Dinamis:** Memantau indikator bisnis klinik secara *real-time* (Total Pasien, Pasien Baru Bulan Ini, dan Kunjungan Hari Ini).
* **Pencarian Cerdas & Filter Multi-Kolom:** Pencarian data pasien dan pemeriksaan super cepat yang dilengkapi filter periode dinamis (Hari Ini, Minggu Ini, Bulan Ini, Tahun Ini) untuk efisiensi kueri basis data.
* **Global Smart Tooltip:** Fitur pembacaan catatan klinis interaktif memanfaatkan reaktivitas *client-side* yang fleksibel mengikuti posisi kursor tanpa terpotong batas tabel (*clipping boundary*).
* **Sistem Approval Berlapis (Keamanan Data):** Mekanisme pengajuan penghapusan data (*Soft Delete*) oleh Admin Staff yang memerlukan persetujuan absolut (Approve/Reject) dari Direktur Utama sebelum benar-benar dieksekusi.
* **Tombol Aksi Reaktif:** Tombol *Edit* dan *Hapus* otomatis berubah status menjadi *disabled* ("Menunggu Hapus") jika data tersebut sedang dalam proses pengajuan persetujuan ke Direktur.
* **Manajemen Akun Terkendali:** Hak akses multi-role (Admin, Marketing, Direktur) dengan pembatasan hak akses ketat demi menjaga privasi rekam medis pasien.

---

## 🛠️ Tech Stack

Aplikasi ini dibangun menggunakan arsitektur monolitik modern dengan performa tinggi:

* **Backend Framework:** Laravel (PHP)
* **Frontend Styling:** Tailwind CSS
* **Frontend Reactivity:** Alpine.js
* **Database:** MySQL
* **Icon Pack:** FontAwesome 6

---

## ⚙️ Panduan Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan lokal Anda:

### 1. Prasyarat
Pastikan perangkat Anda sudah terinstal:
* PHP (>= 8.1)
* Composer
* MySQL / XAMPP

### 2. Kloning Repositori
Jika repositori diatur dengan visibilitas **Private**, pastikan Anda sudah melakukan konfigurasi SSH Key atau menggunakan Personal Access Token (PAT) saat melakukan kloning:
```bash
git clone [https://github.com/username/klinik-gigi-app.git](https://github.com/username/klinik-gigi-app.git)
cd klinik-gigi-app
```
### 3. Instalasi Dependensi
Instal semua pustaka PHP yang diperlukan melalui Composer:
```bash
composer install
```
### 4. Konfigurasi Environment
Salin file .env.example menjadi .env dan sesuaikan kredensial basis data Anda:
```bash
cp .env.example .env
```
Buka file .env dan atur bagian berikut:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=klinik_winardi
DB_USERNAME=root
DB_PASSWORD=
```
### 5. Generate Application Key & Pembersihan Cache
``` bash
php artisan key:generate
php artisan optimize:clear
```
### 6. Migrasi & Seeding Database
```bash
php artisan migrate --seed
```
### 7. Jalankan Server Lokal
```bash
php artisan serve
```
## 🔑 Akun Demo Pengujian

Anda dapat menguji fungsi multi-role dan sistem *approval* menggunakan akun bawaan berikut:

| Role | Email | Password | Hak Akses Utama |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@klinik.com` | `password` | Mengelola data master, input pasien, transaksi, & mengajukan hapus data. |
| **Direktur** | `direktur@klinik.com` | `password` | *Approval* pengajuan hapus data & manajemen akun karyawan. |
| **Marketing** | `marketing@klinik.com` | `password` | Memantau infografis dashboard & daftar pesanan (Dilarang melihat Rekam Medis). |

## 📂 Struktur Folder Inti

```text
klinik-gigi-app/
├── app/
│   ├── Http/
│   │   └── Controllers/Web/      <-- Logika kontroler (Pasien, Pemeriksaan, Pemesanan)
│   └── Models/                   <-- Model Eloquent & Relasi Database
├── database/
│   ├── migrations/               <-- Skema struktur tabel database
│   └── seeders/                  <-- Data awal untuk pengujian/demo
├── resources/
│   └── views/
│       ├── layouts/              <-- Template utama layouting aplikasi
│       ├── pasien/               <-- View modul manajemen pasien
│       ├── pemeriksaan/          <-- View modul rekam medis & smart tooltip
│       └── vendor/pagination/    <-- Kustomisasi desain hijau tema kustom pagination
└── routes/
    └── web.php                   <-- Manajemen rute aplikasi & proteksi role
```

### Anggota Kelompok 2
Proyek ini dikembangkan secara kolaboratif oleh:
* **Galang Bagus Erkamta:** - Backend developer
* **Inandiar Sharfina Fauzi:** - Frontend developer
* **Yossy Fira Rosdiana:** - Backend developer
* **Moch Firman Triswanda:** - Frontend developer
