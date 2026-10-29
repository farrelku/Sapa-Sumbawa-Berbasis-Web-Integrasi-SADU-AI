# Sapa Sumbawa - Portal Aspirasi & Pengaduan Warga Kabupaten Sumbawa

Aplikasi web modern berbasis **Laravel** dan **Tailwind CSS** untuk memfasilitasi pelaporan masyarakat (seperti jalan rusak, tumpukan sampah, lampu penerangan padam, dan pelayanan publik buruk) yang terhubung langsung dengan **Admin Pemkab Sumbawa** dan seluruh **Organisasi Perangkat Daerah (OPD)** terkait seperti Dinas PUPR, DISKOMINFO, DISHUB, DLH, SATPOL PP, DINKES, DUKCAPIL, dan lainnya.

Aplikasi ini dilengkapi dengan **Asisten AI Chatbot Pintar** (mendukung Google Gemini API / OpenAI API dan dilengkapi fallback cerdas lokal) untuk memandu warga Sumbawa menentukan dinas penanggung jawab serta tata cara melapor.

---

## 🌟 Fitur Utama

### 1. Portal Warga (Masyarakat)
- **Registrasi Khusus Warga:** Dilengkapi validasi NIK (16 digit), nomor WhatsApp aktif, pilihan 23 kecamatan di Kabupaten Sumbawa, dan alamat domisili.
- **Formulir Pengaduan Cepat:**
  - Pilihan kategori aduan (Jalan Rusak & Jembatan, Pelayanan Publik, Kebersihan & Sampah, Penerangan Jalan/PJU, Drainase, dll).
  - Pilihan lokasi kecamatan dan detail patokan lapangan.
  - Tingkat urgensi: Rendah, Sedang, hingga Mendesak.
  - Unggah foto bukti kejadian dengan pratinjau instan.
- **Dashboard Warga & Pelacakan Status:**
  - Status laporan real-time (*Menunggu Verifikasi*, *Diteruskan ke OPD*, *Sedang Ditangani*, *Selesai*, *Ditolak*).
  - Timeline riwayat tindak lanjut bertahap (kapan diverifikasi admin, kapan OPD turun ke lapangan).
  - Bukti foto dan catatan penanganan selesai dari OPD.

### 2. Pilihan Registrasi 2 Tahap (Warga vs Perangkat Daerah)
- **Langkah 1:** Memilih peran registrasi (*Warga Masyarakat* atau *Perangkat Daerah / OPD*).
- **Langkah 2 (Jika Perangkat Daerah):**
  - Muncul pilihan **Instansi / Dinas (OPD)**:
    - **Dinas Pekerjaan Umum dan Penataan Ruang (PUPR)**
    - **Dinas Komunikasi, Informatika, Statistik dan Persandian (DISKOMINFO)**
    - **Dinas Perhubungan (DISHUB)**
    - **Dinas Lingkungan Hidup (DLH)**
    - **Satuan Polisi Pamong Praja (SATPOL PP)**
    - **Dinas Kesehatan (DINKES)**
    - **Dinas Kependudukan dan Pencatatan Sipil (DUKCAPIL)**
    - **Dinas Pendidikan dan Kebudayaan (DIKBUD)**
    - **Badan Penanggulangan Bencana Daerah (BPBD)**
  - Pengisian NIP pegawai, nama lengkap, jabatan/bidang penugasan, dan email kedinasan.

### 3. Dashboard Admin Pemkab (Pusat Disposisi)
- **Verifikasi Laporan:** Admin memantau antrean laporan baru yang masuk dari seluruh warga.
- **Fitur Disposisi ke OPD Terkait:**
  - Admin memilih OPD tujuan yang berwenang (misal laporan jalan rusak langsung dialokasikan ke Dinas PUPR).
  - Admin memberikan catatan instruksi resmi / petunjuk penanganan bagi OPD.
  - Mengubah tingkat urgensi jika laporan bersifat gawat darurat.
- **Tolak Laporan:** Admin dapat menolak laporan palsu/hoaks/di luar kewenangan disertai alasan tertulis bagi pelapor.
- **Statistik & Monitoring:** Pemantauan beban kerja masing-masing OPD dan daftar seluruh akun pengguna.

### 4. Portal Perangkat Daerah (OPD)
- Masing-masing dinas (misal PUPR) hanya menerima dan mengelola laporan yang telah didisposisikan oleh Admin ke dinasnya.
- **Mulai Penanganan:** Mengubah status menjadi *Sedang Ditangani* disertai catatan progres awal.
- **Tandai Selesai:** Mengubah status menjadi *Selesai*, memasukkan uraian hasil penanganan, serta **mengunggah foto bukti penyelesaian di lapangan**.

### 5. Asisten AI Chatbot Pintar (24 Jam)
- **Floating Chatbot Widget:** Tombol melayang di pojok kanan bawah yang dapat diakses di setiap halaman web.
- **Halaman Penuh (`/tanya-ai`):** Antarmuka obrolan interaktif dengan rekomendasi pertanyaan cepat seputar fasilitas umum di Kabupaten Sumbawa.
- **Dukungan API:** Terintegrasi dengan endpoint Google Gemini API (`GEMINI_API_KEY`) atau OpenAI API (`OPENAI_API_KEY`).
- **Smart Local Fallback Engine:** Jika API Key belum diisi di `.env`, chatbot otomatis menggunakan mesin *knowledge-base* lokal khusus Kabupaten Sumbawa yang dapat menjawab dengan cerdas, santun, dan menyarankan kategori laporan serta instansi yang berwenang!

---

## 🔑 Akun Demo Siap Pakai

Halaman login (`/login`) telah dilengkapi **One-Click Quick Login Switcher** (cukup klik tombol peran yang diinginkan):

| Peran Akun | Email | Kata Sandi | Keterangan |
| :--- | :--- | :--- | :--- |
| **🛡️ Admin Pemkab** | `admin@sumbawakab.go.id` | `password` | Verifikasi & Disposisi laporan ke OPD |
| **🏗️ OPD PUPR** | `pupr@sumbawakab.go.id` | `password` | Penanganan jalan rusak & jembatan |
| **🚦 OPD DISHUB** | `dishub@sumbawakab.go.id` | `password` | Penanganan lampu PJU & rambu jalan |
| **💻 OPD DISKOMINFO** | `diskominfo@sumbawakab.go.id` | `password` | Layanan digital & komunikasi |
| **🌱 OPD DLH** | `dlh@sumbawakab.go.id` | `password` | Kebersihan & sampah liar |
| **🧑‍💼 Warga Sumbawa** | `warga@sumbawakab.go.id` | `password` | Pelapor warga (Lalu Muhammad Fikri) |

---

## 🚀 Cara Menjalankan Aplikasi

Aplikasi berada di direktori:
`C:\Users\LENOVO\.gemini\antigravity\scratch\sapa-sumbawa`

1. **Jalankan Web Server Laravel:**
   ```bash
   php artisan serve
   ```
   Buka di browser: `http://localhost:8000`

2. **Jalankan Mode Pengembangan Frontend (Opsional jika ingin mengubah CSS realtime):**
   ```bash
   npm run dev
   ```
   *(Aset produksi sudah di-build penuh di folder `public/build` sehingga aplikasi langsung berjalan normal tanpa perlu menjalankan vite dev server)*

3. **Menjalankan Automated Tests:**
   ```bash
   php artisan test
   ```

4. **Konfigurasi AI Chatbot (Opsional):**
   Buka file `.env`, lalu masukkan API Key Anda:
   ```env
   GEMINI_API_KEY=AIzaSy...
   ```
   *Jika tidak diisi, chatbot tetap berfungsi normal 100% menggunakan Asisten Cerdas Lokal Sapa Sumbawa.*
