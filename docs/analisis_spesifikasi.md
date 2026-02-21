# ANALISIS SPESIFIKASI APLIKASI WEB MANAJEMEN DATA MAHASISWA

## 1. ANALISIS INPUT, PROSES, DAN OUTPUT

### INPUT
1. **Data Mahasiswa:**
   - NIM (Nomor Induk Mahasiswa) - String, unique, tidak dapat diubah saat edit
   - NAMA - String
   - ALAMAT - Text
   - TANGGAL LAHIR - Date
   - GENDER - Select (Laki-laki / Perempuan)
   - USIA - Integer (dihitung otomatis dari TANGGAL LAHIR)

2. **Parameter Pencarian dan Filter:**
   - Nama mahasiswa (untuk fitur search)

### PROSES
1. **Penyimpanan Data:**
   - Validasi input data
   - Insert data ke database
   - Update data (kecuali NIM)
   - Delete data

2. **Pengambilan Data:**
   - Fetch semua data mahasiswa
   - Sort descending berdasarkan NIM
   - Filter berdasarkan pencarian nama
   - Hitung statistik gender dan total mahasiswa

3. **Perhitungan USIA:**
   - Otomatis dihitung dari TANGGAL LAHIR
   - Formula: (hari ini - tanggal lahir) / 365.25

### OUTPUT
1. **Halaman HOME (index.php):**
   - Tabel data mahasiswa (NIM, NAMA, ALAMAT, TANGGAL LAHIR, GENDER, USIA)
   - Input pencarian nama
   - Statistik:
     - Total jumlah mahasiswa
     - Jumlah mahasiswa laki-laki
     - Jumlah mahasiswa perempuan

2. **Halaman ADMIN:**
   - Form tambah data mahasiswa
   - Tabel data dengan tombol Edit dan Hapus
   - Modal/form edit data (NIM disabled)
   - Konfirmasi sebelum hapus data





