# 🍽️ KMS Admin - Kitchen Management System

<div align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel"/>
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-blue?style=for-the-badge&logo=tailwindcss"/>
  <img src="https://img.shields.io/badge/MySQL-8.x-orange?style=for-the-badge&logo=mysql"/>
  <img src="https://img.shields.io/badge/Alpine.js-3.x-green?style=for-the-badge&logo=alpine.js"/>
  <img src="https://img.shields.io/badge/License-MIT-yellow?style=for-the-badge"/>
</div>

<br>

**Sistem Manajemen Dapur Digital untuk Program Makan Bergizi Gratis (MBG)**  
📍 Dapur MBG Bojongsari 02

> Sistem ini dirancang khusus untuk membantu pengelolaan dapur MBG agar lebih efisien, transparan, dan akurat dalam mengelola stok bahan, produksi, hingga keuangan.

---

## ✨ Fitur Utama

### 📦 Manajemen Bahan Baku
- CRUD bahan baku dengan upload gambar
- Tracking stok real-time
- Peringatan stok menipis otomatis

### 📥 Stok Masuk & 📤 Stok Keluar
- Pencatatan stok masuk dengan harga otomatis
- Stok keluar untuk produksi
- Riwayat transaksi lengkap
- Integrasi dengan Supplier

### 🔄 Stok Opname & Stok Mengendap
- Koreksi stok fisik vs sistem
- Catat sisa bahan produksi
- Gunakan kembali bahan sisa

### 🍳 Manajemen Menu & Resep
- CRUD menu makanan
- Komposisi bahan per menu (BOM)
- Hitung HPP (Harga Pokok Produksi)

### 🏭 Produksi Harian
- Catat produksi berdasarkan menu
- Hitung kebutuhan bahan otomatis
- Kurangi stok secara real-time

### 💰 Keuangan
- Pencatatan pemasukan & pengeluaran
- Otomatis dari stok masuk/keluar
- Laporan keuangan periodik

### 📊 Laporan & Export
- Laporan stok, produksi, keuangan
- Export ke Excel & PDF

### 👥 Manajemen User
- Multi-role: Admin, Manager, Staff, Driver
- Autentikasi Laravel Breeze
- CRUD user dengan upload avatar
- Reset password & status aktif/nonaktif

### 🏢 Manajemen Supplier
- Data supplier lengkap
- Riwayat pembelian
- Tracking total transaksi & pembelian

### 🔔 Notifikasi
- Notifikasi stok habis & menipis
- Dropdown notifikasi real-time
- Tandai dibaca & semua dibaca

### 🌙 Dark Mode
- Toggle light/dark mode
- Preferensi tersimpan otomatis

### ⚙️ Pengaturan Sistem
- Profil dapur (nama, alamat, logo, telepon)
- Preferensi stok, keuangan, laporan
- Backup & clear cache

---

## 🛠️ Teknologi

| Teknologi | Kegunaan |
|-----------|----------|
| **Laravel 11** | Backend framework |
| **Tailwind CSS** | Styling & UI |
| **Alpine.js** | Interaktivitas ringan |
| **MySQL** | Database |
| **Chart.js** | Grafik dashboard |
| **Laravel Excel** | Export Excel |
| **Laravel DomPDF** | Export PDF |

---

## 📁 Struktur Database
dapurmbg/
├── bahans # Data bahan baku
├── stok_masuk # Pencatatan stok masuk
├── stok_keluar # Pencatatan stok keluar
├── stok_opname # Koreksi stok fisik
├── stok_mengendap # Sisa bahan produksi
├── menus # Data menu makanan
├── resep # Komposisi bahan per menu
├── produksi # Produksi harian
├── produksi_detail # Detail penggunaan bahan
├── transaksis # Keuangan
├── suppliers # Data supplier
├── users # Pengguna sistem
├── notifications # Notifikasi stok
└── settings # Pengaturan sistem

---

## 🚀 Cara Install

### Prasyarat
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL

### Langkah-langkah

```bash
# 1. Clone repository
git clone https://github.com/aliefwahyulianto/MBGBJS02.git
cd MBGBJS02

# 2. Install dependencies
composer install
npm install

# 3. Copy environment file
cp .env.example .env

# 4. Generate key
php artisan key:generate

# 5. Setup database di file .env
# DB_DATABASE=dapurmbg
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan migration dan seeder
php artisan migrate --seed

# 7. Buat storage link
php artisan storage:link

# 8. Build assets
npm run build

# 9. Jalankan server
php artisan serve

# 10. Jalankan command notifikasi (cron)
php artisan stok:check
