# EFKA Workshop - Sistem Booking & E-Commerce Bengkel

  ![image](https://github.com/user-attachments/assets/de2c92ca-bfa6-49e2-a94f-79e78ad1a774)

<p align="center">
  <em>"Your trusted partner for superior motorcycle detailing and repair solutions. We bring your ride back to life."</em>
  <br><br>
  <img src="https://img.shields.io/badge/PHP-8.0%2B-blueviolet?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-5.7%2B-orange?style=for-the-badge&logo=mysql" alt="MySQL">
  <img src="https://img.shields.io/badge/JavaScript-ES6-yellow?style=for-the-badge&logo=javascript" alt="JavaScript">
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="License">
</p>

---

**EFKA Workshop** bukan sekadar proyek tugas akhir. Ini adalah sebuah ekosistem digital lengkap yang dirancang untuk menjembatani antara bengkel motor modern dengan para pelanggannya. Dibangun dengan cinta, kopi, dan begadang ala anak TI, sistem ini mengintegrasikan alur booking servis yang dinamis dengan platform e-commerce untuk penjualan sparepart, dibungkus dalam antarmuka yang intuitif baik untuk customer maupun admin.

Proyek ini adalah manifestasi dari semangat untuk memberikan solusi nyata, di mana teknologi bertemu dengan deru mesin.

## 📸 Galeri Penampakan

<details>
<summary><strong>Klik untuk melihat penampakan aplikasi</strong></summary>
<br>
<table>
  <tr>
    <td align="center"><b>Halaman Utama (Landing Page)</b></td>
    <td align="center"><b>Halaman Sparepart Customer</b></td>
  </tr>
  <tr>
    <td><img src="https://drive.google.com/file/d/1PyCbT5BMGnmtiVfBzjXgXnpfARExQlfO/view?usp=drive_link" alt="Halaman Utama" width="400"></td>
    <td><img src="https://drive.google.com/file/d/11AO4Ns_HERPZjJbXQ3qM1FwZKpvcQrYO/view?usp=drive_link" alt="Halaman Sparepart" width="400"></td>
  </tr>
  <tr>
    <td align="center"><b>Halaman Login/Register Modern</b></td>
    <td align="center"><b>Invoice & QR Code Unik</b></td>
  </tr>
  <tr>
    <td><img src="https://i.imgur.com/S9g9sJt.jpeg" alt="Halaman Login" width="400"></td>
    <td><img src="https://i.imgur.com/z6E9A9q.png" alt="Halaman Invoice" width="400"></td>
  </tr>
   <tr>
    <td align="center"><b>Panel Admin - Dashboard Analitis</b></td>
    <td align="center"><b>Panel Admin - Manajemen Feedback</b></td>
  </tr>
  <tr>
    <td><img src="https://i.imgur.com/G9i3E7S.png" alt="Dashboard Admin" width="400"></td>
    <td><img src="https://i.imgur.com/i9o3y2J.png" alt="Manajemen Feedback" width="400"></td>
  </tr>
</table>
</details>

## ✨ Fitur-Fitur Unggulan

Proyek ini terbagi menjadi dua dunia utama: Sisi Customer dan Panel Admin.

### Sisi Customer (Pintu Depan)
- **Halaman Utama Dinamis:** Menampilkan layanan unggulan dan *social proof* berupa rata-rata rating dari semua customer.
- **Sistem Autentikasi Profesional:**
    - Registrasi dengan validasi password *real-time*.
    - Tombol "intip password" untuk UX yang lebih baik.
    - Notifikasi elegan menggunakan **SweetAlert2**.
- **Booking Servis Interaktif:** Form booking dengan AJAX, mengirim permintaan tanpa perlu me-refresh halaman.
- **E-Commerce Sparepart:**
    - Katalog produk dinamis dari database, termasuk "Hero Product".
    - Fungsi **"Tambah ke Keranjang"** instan (AJAX) dengan update indikator jumlah item secara *real-time*.
    - Halaman keranjang belanja interaktif untuk mengubah kuantitas dan menghapus item (AJAX).
- **Alur Checkout & Pembayaran Canggih:**
    - Proses checkout formal yang memindahkan `carts` ke `orders` yang sah.
    - Pembuatan **Invoice Digital** dengan **QR Code unik** untuk setiap pesanan.
    - Sistem **AJAX Polling** yang membuat halaman invoice otomatis me-redirect setelah QR Code di-scan oleh petugas.
- **Pusat Kontrol Pelanggan:**
    - **Halaman Profil** untuk update nama dan ganti password.
    - **Halaman Riwayat Terpadu** yang menampilkan pesanan aktif (menunggu scan QR) dan riwayat transaksi yang sudah selesai.
    - **Sistem Rating & Ulasan** untuk setiap transaksi yang telah selesai.

### Panel Admin (Ruang Mesin)
- **Dashboard Analitis:** Menampilkan ringkasan bisnis dan grafik (Chart.js) untuk memantau pendapatan.
- **Manajemen CRUD Penuh:** Kemampuan untuk *Create, Read, Update, Delete* (Tambah, Lihat, Edit, Arsipkan/Nonaktifkan) untuk **Layanan**, **Sparepart**, dan **User**.
- **Fitur Soft Delete:** Produk dan User tidak dihapus permanen untuk menjaga integritas data historis.
- **Alur Kerja Booking & Order Profesional:**
    - Konfirmasi/Penolakan booking servis melalui email (**PHPMailer**) dengan link konfirmasi unik.
    - Manajemen antrian servis dan pesanan sparepart yang terpisah.
    - Kemampuan untuk memproses, membatalkan, dan menyelesaikan pesanan yang langsung tercatat di `history`.
- **Manajemen Interaksi Pelanggan:**
    - **Inbox Feedback** dengan sistem tab "Unread" dan "Read" untuk membaca semua masukan pelanggan.
    - **Riwayat Transaksi Global** dengan fitur filter berdasarkan tipe transaksi.

## 🛠️ Teknologi yang Digunakan

- **Backend:** PHP 8+
- **Database:** MySQL (dijalankan dengan XAMPP/MariaDB)
- **Frontend:** HTML5, CSS3 (Flexbox & Grid), Vanilla JavaScript (ES6)
- **Manajemen Dependensi:** Composer
- **Library PHP:**
    - `phpmailer/phpmailer`: Untuk mengirim email notifikasi.
    - `vlucas/phpdotenv`: Untuk manajemen variabel rahasia (.env).
    - `bacon/bacon-qr-code`: Untuk membuat QR Code.
- **Library JavaScript:**
    - `SweetAlert2`: Untuk notifikasi popup yang modern dan interaktif.
    - `Chart.js`: Untuk menampilkan grafik di dashboard admin.
- **Ikon:** Font Awesome

## 🚀 Panduan Instalasi & Demo

Untuk menjalankan proyek ini di komputermu, ikuti langkah-langkah presisi berikut:

### 1. Prasyarat
- Pastikan kamu sudah menginstal **XAMPP** (dengan PHP versi 8 ke atas).
- Pastikan kamu sudah menginstal **Composer** secara global di komputermu.
- Pastikan kamu punya akun **GitHub** dan sudah menginstal **Git**.

### 2. Clone Repository
Buka terminal atau Git Bash, navigasi ke folder `htdocs` di dalam instalasi XAMPP-mu (biasanya di `C:\xampp\htdocs`), lalu jalankan:
```
git clone [https://github.com/](https://github.com/)[NAMA_USER_GITHUBLINK_KAMU]/EfkaWorkshop.git
cd EfkaWorkshop
```
### 3. Install dependensi PHP
Jalankan perintah ini di terminal (pastikan kamu masih berada di dalam folder EfkaWorkshop) untuk menginstal semua library yang dibutuhkan (PHPMailer, Dotenv, dll):
```
composer install
```
### 4. Setup database
- Buka phpMyAdmin dari XAMPP Control Panel atau akses http://localhost/phpmyadmin.
- Buat database baru dengan nama db_efkaworkshop.
- Pilih database yang baru dibuat, lalu klik tab "Import".
- Klik "Choose File" dan pilih file db_efkaworkshop.sql yang ada di dalam folder database/ di proyek ini.
- Klik "Go" atau "Import". Semua tabel dan beberapa data contoh akan otomatis dibuat.

### 5. Konfigurasi brankas rahasia  
- Di folder utama proyek, kamu akan menemukan file bernama ```.env.example```.
- Buat salinan dari file itu dan beri nama ```.env```.
- Buka file ```.env``` tersebut dan isi nilainya dengan kredensialmu:
```
GMAIL_USER="email-gmail-kamu@gmail.com"
GMAIL_APP_PASSWORD="passwordaplikasi16digitkamu"
```
- **PENTING**: ```GMAIL_APP_PASSWORD``` harus diisi dengan App Password dari Google, bukan password login Gmail-mu.

### 6. Jalankan proyek
- Pastikan service Apache dan MySQL di XAMPP Control Panel sudah berjalan (berwarna hijau).
- Buka browser dan akses ```http://localhost/EfkaWorkshop/```.
- Selesai! Kamu sekarang bisa menjelajahi seluruh fitur EFKA Workshop.

### 7. Akun Demo
- Akses Halaman Admin: Login dengan kredensial berikut:
    - Email: ```admin@efka.com```
    - Password: ```admin123```
- Akses sebagai Customer: Silakan buat akun baru melalui halaman registrasi.

## 👥 Tim Hebat di Balik Deru Mesin
Proyek ini tidak akan pernah menyala tanpa percikan ide dan kerja keras dari setiap anggota tim. Sebuah mahakarya yang dirakit bersama dari nol.
- Mike (Michael Babista Pakpahan) - Sang Arsitek Utama
  - Peran: Backend, Frontend, Desain Database, Desain UI/UX, Konseptor Web
  - Ibarat kepala mekanik yang merancang mesin dari sketsa sampai bisa digeber.
- Kevin Andreas Situmorang - Sang Seniman Visual
  Peran: Desainer UI/UX, Pencari Aset Gambar/Ikon
  Dia yang memastikan "cat dan striping" motor kita paling keren di tongkrongan.
- Jodi Handika Fransico Sim - Sang Penata Kabel
  - Peran: Front-end
  - Dia yang memastikan semua tombol di dashboard berfungsi, lampunya nyala, dan klaksonnya bunyi.
- Muhammad Farhan - Sang Visioner Estetika
  - Peran: Desainer UI/UX
  - Memberikan pandangan dan ide-ide segar agar tampilan website tidak hanya fungsional, tapi juga sedap dipandang.
- M. Rifky Kembaren - Sang Manajer Tim & Penjaga Gawang
  - Peran: Supportive, Pembuat Laporan
  - Dia yang memastikan tim tetap solid, bensin selalu terisi, dan semua "surat-surat" (laporan) lengkap dan rapi.

## 🤝 Kontribusi
Merasa ada yang bisa dibuat lebih baik atau nemu baut yang kendor (bug)? Jangan sungkan untuk:
1. Fork repository ini.
2. Buat branch baru (```git checkout -b fitur/NamaFiturKeren```).
3. Commit perubahanmu (```git commit -m 'Menambahkan fitur keren A'```).
4. Push ke branch-mu (```git push origin fitur/NamaFiturKeren```).
5. Buka sebuah Pull Request.

# 📄 Lisensi
Proyek ini berada di bawah Lisensi MIT. Lihat file ```LICENSE``` untuk detail lebih lanjut.
