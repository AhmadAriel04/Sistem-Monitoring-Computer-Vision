# 🏦 Sistem Monitoring Kepuasan Nasabah Berbasis Computer Vision

<p align="center">
  <b>Intelligent Customer Satisfaction Monitoring System</b><br>
  Computer Vision · Deep Learning · YOLO · Laravel
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Python-3776AB?style=for-the-badge&logo=python&logoColor=white" alt="Python">
  <img src="https://img.shields.io/badge/YOLO-111F68?style=for-the-badge&logoColor=white" alt="YOLO">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

## 📌 Tentang Project

**Sistem Monitoring Kepuasan Nasabah Berbasis Computer Vision** merupakan project pengembangan sistem monitoring yang memanfaatkan teknologi Computer Vision dan Deep Learning untuk mengidentifikasi ekspresi wajah serta mendukung pemantauan kepuasan nasabah secara otomatis.

Sistem ini dirancang untuk membantu proses pemantauan interaksi pelayanan nasabah di lingkungan perbankan, khususnya pada Bank Syariah Indonesia (BSI) KCP Balaraja.

Dengan mengintegrasikan model YOLO dan aplikasi web berbasis Laravel, sistem ini dirancang untuk mengolah hasil klasifikasi ekspresi wajah dan menyajikan informasi monitoring melalui dashboard yang mudah dipahami.

## 🎯 Tujuan Project

- Mengembangkan rancangan sistem monitoring kepuasan nasabah berbasis Computer Vision.
- Memanfaatkan model YOLO untuk mendeteksi wajah dan mengklasifikasikan ekspresi wajah.
- Menyajikan hasil monitoring dalam dashboard berbasis web.
- Mendukung evaluasi kualitas pelayanan berdasarkan informasi monitoring yang tersedia.
- Mengintegrasikan pemrosesan Computer Vision dengan sistem informasi berbasis web.

## ⚙️ Teknologi yang Digunakan

| Teknologi | Fungsi |
|---|---|
| Python | Bahasa pemrograman untuk pemrosesan Computer Vision |
| YOLO11 | Rencana deteksi wajah |
| YOLOv8 Classification | Rencana klasifikasi ekspresi wajah |
| OpenCV | Pemrosesan frame video dan citra |
| Laravel | Backend dan dashboard monitoring |
| MySQL | Penyimpanan data monitoring |
| HTML, CSS, JavaScript | Antarmuka aplikasi web |

*Catatan: Teknologi dan pembagian fungsi di atas menggambarkan rancangan sistem. Sesuaikan dengan implementasi aktual project.*

## 🧠 Kategori Ekspresi Wajah

Sistem dirancang untuk mengklasifikasikan ekspresi wajah ke dalam empat kategori:

- 😊 **Happy** — ekspresi senang.
- 😐 **Neutral** — ekspresi netral.
- 😢 **Sad** — ekspresi sedih.
- 😠 **Angry** — ekspresi marah.

Klasifikasi ekspresi wajah merupakan indikator visual, bukan pengukuran pasti atas perasaan atau tingkat kepuasan seseorang. Hasilnya perlu ditafsirkan secara hati-hati dan tidak digunakan sebagai satu-satunya dasar penilaian pelayanan.

## 🔄 Gambaran Alur Sistem

```text
Kamera
   │
   ▼
YOLO11 - Deteksi Wajah
   │
   ▼
Tracking dan Face Selection
   │
   ▼
YOLOv8 - Klasifikasi Ekspresi
   │
   ▼
Pengolahan dan Agregasi Skor
   │
   ▼
Session Manager
   │
   ▼
API Integrasi
   │
   ▼
Laravel Monitoring Dashboard
   │
   ▼
Database MySQL
```

Diagram tersebut merupakan gambaran arsitektur yang direncanakan. Urutan dan komponen dapat disesuaikan dengan implementasi sistem yang sebenarnya.

## 📊 Fitur Utama

### 1. Face Detection
Mendeteksi keberadaan wajah pada frame kamera menggunakan model deteksi yang direncanakan.

### 2. Facial Expression Classification
Mengklasifikasikan ekspresi wajah berdasarkan kategori yang telah ditentukan.

### 3. Monitoring Dashboard
Menyediakan antarmuka untuk menampilkan dan memantau data hasil pemrosesan.

### 4. Data Management
Mengelola data hasil monitoring melalui backend dan database.

### 5. System Integration
Menghubungkan modul Computer Vision dengan aplikasi monitoring berbasis Laravel melalui API.

## 🔐 Privasi dan Keamanan Data

Privasi merupakan salah satu aspek penting dalam pengembangan sistem ini.

Rancangan sistem mengutamakan pengolahan data secara terbatas dengan prinsip berikut:

- Tidak menyimpan gambar wajah sebagai data permanen.
- Mengutamakan penyimpanan hasil klasifikasi dan informasi numerik yang diperlukan.
- Membatasi akses terhadap dashboard sesuai hak akses pengguna.
- Melindungi kredensial dan konfigurasi aplikasi melalui environment variables.
- Mempertimbangkan persetujuan, transparansi, dan perlindungan data pribadi dalam penggunaan kamera.

Prinsip tersebut merupakan target desain yang perlu diverifikasi dalam implementasi aktual.

## 🛠️ Persyaratan Sistem

Persyaratan awal untuk pengembangan project:

- Python dan pip.
- PHP dan Composer.
- Laravel.
- MySQL.
- Node.js dan npm.
- Model YOLO serta dependensi Computer Vision yang sesuai.
- Git.

Versi setiap dependensi perlu disesuaikan dengan konfigurasi project.

## 🚀 Instalasi dan Menjalankan Aplikasi

### 1. Clone Repository

```bash
git clone https://github.com/AhmadAriel04/Sistem-Monitoring-Computer-Vision.git
cd Sistem-Monitoring-Computer-Vision
```

### 2. Instalasi Backend Laravel

Jika aplikasi Laravel berada di direktori ini:

```bash
composer install
```

Salin konfigurasi environment:

```bash
cp .env.example .env
```

Untuk Windows, kamu juga dapat menggunakan:

```powershell
Copy-Item .env.example .env
```

Buat application key:

```bash
php artisan key:generate
```

Sesuaikan konfigurasi database pada `.env`, kemudian jalankan migrasi jika struktur database project sudah tersedia:

```bash
php artisan migrate
```

Jalankan server lokal:

```bash
php artisan serve
```

### 3. Instalasi Modul Computer Vision

Jika modul Python tersedia pada folder terpisah, masuk ke direktori modul tersebut terlebih dahulu.

Aktifkan virtual environment sesuai sistem operasi, kemudian instal dependensi dari file `requirements.txt` apabila tersedia:

```bash
pip install -r requirements.txt
```

Jalankan script utama sesuai nama file yang digunakan dalam project.

**Catatan:** Perintah instalasi ini merupakan panduan umum. Sesuaikan struktur direktori, file konfigurasi, dependensi, dan perintah eksekusi dengan isi repository aktual.

## 🗺️ Rencana Pengembangan

- [ ] Penyempurnaan modul deteksi wajah.
- [ ] Pengembangan klasifikasi ekspresi wajah.
- [ ] Integrasi modul Computer Vision dengan Laravel.
- [ ] Pengembangan dashboard monitoring.
- [ ] Pengelolaan data dan hak akses pengguna.
- [ ] Pengujian integrasi dan evaluasi sistem.
- [ ] Evaluasi privasi, keamanan, dan keandalan hasil klasifikasi.

## 👨‍💻 Author

**Ahmad Ariel Rafai**

Technology Information Student | Computer Vision & Software Development

- GitHub: [@AhmadAriel04](https://github.com/AhmadAriel04)
- Project Repository: [Sistem Monitoring Computer Vision](https://github.com/AhmadAriel04/Sistem-Monitoring-Computer-Vision)

---

<p align="center">
  <i>Developing intelligent systems to support better service monitoring through Computer Vision.</i>
</p>
