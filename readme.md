# Aplikasi Manajemen Data Mahasiswa

Ini adalah aplikasi web sederhana untuk mengelola data mahasiswa. Dibuat dengan PHP, MySQL, HTML, CSS, dan JavaScript. Bisa digunakan untuk tracking informasi mahasiswa seperti NIM, nama, alamat, tanggal lahir, jenis kelamin, dan usia.

Aplikasi ini punya dua halaman utama:
- **HOME**: Untuk melihat data mahasiswa dan cari berdasarkan nama
- **ADMIN**: Untuk tambah, edit, atau hapus data mahasiswa

## Cara Instalasi

Pertama, pastikan sudah punya XAMPP terinstall. Kalau belum, download dari [sini](https://www.apachefriends.org/).

### Langkah-langkah:

1. **Extract folder ini ke:** `C:\xampp\htdocs\Web Data Mahasiswa\`

2. **Buka XAMPP Control Panel** dan jalankan Apache dan MySQL

3. **Setup database:**
   - Buka browser: `http://localhost/phpmyadmin`
   - Login dengan username: `root` (passwordnya kosong)
   - Klik Import, pilih file `docs/database_init.sql`
   - Klik Go

4. **Buka aplikasi:**
   - Ketik di browser: `http://localhost/Web%20Data%20Mahasiswa/`

Kalau berhasil, seharusnya bisa lihat halaman HOME dengan data mahasiswa.

## Fitur-Fitur

**Di Halaman HOME:**
- Lihat semua data mahasiswa (terurut dari NIM terbesar)
- Cari data berdasarkan nama
- Lihat statistik jumlah mahasiswa berdasarkan jenis kelamin
- Usia otomatis dihitung dari tanggal lahir

**Di Halaman ADMIN:**
- Tambah data mahasiswa baru
- Edit data (tapi NIM tidak bisa diubah)
- Hapus data dengan konfirmasi
- Lihat tabel semua data dengan kolom usia
- Usia otomatis dihitung real-time saat input tanggal lahir

## Data yang Disimpan

Setiap mahasiswa punya data:
- **NIM** - Nomor induk (unik, tidak bisa sama)
- **Nama** - Nama lengkap
- **Alamat** - Alamat tinggal
- **Tanggal Lahir** - Untuk hitung usia
- **Jenis Kelamin** - Laki-laki atau Perempuan
- **Usia** - Otomatis dihitung, tidak perlu diinput

## Struktur Folder

```
Web Data Mahasiswa/
├── index.php              (halaman HOME)
├── admin/
│   ├── index.php          (halaman ADMIN)
│   ├── add.php            (proses tambah)
│   ├── edit.php           (proses edit)
│   └── delete.php         (proses hapus)
├── includes/
│   ├── config.php         (setting database)
│   └── functions.php      (fungsi-fungsi)
├── assets/
│   ├── css/style.css      (styling)
│   └── js/script.js       (validasi & interaksi)
└── docs/
    ├── analisis_spesifikasi.md
    ├── catatan_function.md
    └── database_init.sql
    └── Dokumentasi Penggunaan Website Data Mahasiswa.pdf
```

## Cara Menggunakan

**Lihat Data:**
1. Buka halaman HOME
2. Data sudah tampil otomatis
3. Kalo mau cari, ketik nama di kolom cari



**Tambah Data:**
1. Buka halaman ADMIN
2. Klik tombol "TAMBAH DATA"
3. Isi form dengan data mahasiswa
4. Saat mengisi tanggal lahir, usia otomatis muncul di field bawahnya
5. Setiap field wajib diisi
6. Klik tombol Simpan
7. Kalau berhasil, akan ada notifikasi sukses


**Edit Data:**
1. Di ADMIN, cari data yang mau diedit
2. Klik tombol Edit (kuning)
3. Form akan muncul dengan data lama
4. Ubah data yang ingin diubah (tapi NIM tidak bisa dirubah)
5. Field usia akan otomatis update jika tanggal lahir diubah
6. Klik tombol Perbarui
7. Selesai!

**Hapus Data:**
1. Di ADMIN, cari data yang mau dihapus
2. Klik tombol Hapus (merah)
3. Ada popup untuk konfirmasi
4. Klik "Ya, Hapus" untuk benar-benar menghapus
5. Data akan hilang dari sistem

## Teknologi yang Dipakai

- **Frontend:** HTML5, CSS3, JavaScript
- **Framework:** Bootstrap 5 (buat styling)
- **Icons:** Bootstrap Icons
- **Backend:** PHP 7.4+
- **Database:** MySQL 5.7+
- **Library Tambahan:** SweetAlert2 (buat konfirmasi hapus)

Semua library diambil dari CDN, jadi tidak perlu install apapun selain XAMPP.


## Dokumentasi Detail

Detail dokumentasi lengkap di folder `docs/`:

- `analisis_spesifikasi.md` - Penjelasan tentang aplikasi
- `catatan_function.md` - Penjelasan setiap function
- `Dokumentasi Penggunaan Website Data Mahasiswa.pdf` - Penjelasan penggunaan website disertai screenshot gambar.


## Lisensi

Aplikasi ini bebas digunakan oleh semua pengguna
---
