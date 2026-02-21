# DOKUMENTASI KODE APLIKASI MANAJEMEN DATA MAHASISWA

---

## TABLE OF CONTENTS

1. [Struktur File](#struktur-file)
2. [File Konfigurasi](#file-konfigurasi)
3. [File Fungsi Helper](#file-fungsi-helper)
4. [Halaman HOME](#halaman-home)
5. [Halaman ADMIN](#halaman-admin)
6. [File CSS](#file-css)
7. [File JavaScript](#file-javascript)
8. [Database](#database)

---

## STRUKTUR FILE

```
Web Data Mahasiswa/
├── index.php                      # Halaman HOME
├── includes/
│   ├── config.php                 # Konfigurasi database
│   └── functions.php              # Fungsi-fungsi helper
├── admin/
│   ├── index.php                  # Halaman ADMIN
│   ├── add.php                    # Proses tambah data
│   ├── edit.php                   # Proses edit data
│   └── delete.php                 # Proses hapus data
├── assets/
│   ├── css/
│   │   └── style.css              # Stylesheet aplikasi
│   └── js/
│       └── script.js              # JavaScript
└── docs/
    ├── ANALISIS_SPESIFIKASI.md    # Analisis aplikasi
    ├── DIAGRAM_PROSES.md          # Diagram proses
    ├── MOCKUP_DESAIN.md           # Mockup design
    ├── PANDUAN_SETUP.md           # Panduan instalasi
    ├── DOKUMENTASI_KODE.md        # File ini
    └── database_init.sql          # Script database
```

---

## FILE KONFIGURASI

### File: `includes/config.php`

**Deskripsi:** File untuk konfigurasi koneksi database dan fungsi dasar.

**Fungsi-Fungsi:**

#### 1. `getDBConnection()`
```php
/**
 * Membuat koneksi ke database MySQL
 * @return mysqli object
 * @throws Exception jika koneksi gagal
 */
function getDBConnection()
```

**Penjelasan:**
- Menggunakan MySQLi procedural style
- Set charset UTF-8 untuk support karakter Indonesia
- Return koneksi database atau die jika gagal

**Cara Menggunakan:**
```php
$conn = getDBConnection();
```

#### 2. `showError($message)`
```php
/**
 * Menampilkan pesan error dengan styling Bootstrap
 * @param string $message - Pesan error
 */
function showError($message)
```

**Penjelasan:**
- Menampilkan pesan error dalam alert box Bootstrap
- Menggunakan CSS class `alert-danger`

**Cara Menggunakan:**
```php
showError("Data tidak ditemukan!");
```

#### 3. `showSuccess($message)`
```php
/**
 * Menampilkan pesan sukses dengan styling Bootstrap
 * @param string $message - Pesan sukses
 */
function showSuccess($message)
```

**Penjelasan:**
- Menampilkan pesan sukses dalam alert box Bootstrap
- Menggunakan CSS class `alert-success`

---

## FILE FUNGSI HELPER

### File: `includes/functions.php`

**Deskripsi:** File berisi fungsi-fungsi helper untuk validasi, operasi database, dan perhitungan.

### Fungsi Perhitungan

#### 1. `hitungUsia($tanggal_lahir)`
```php
/**
 * Menghitung usia dari tanggal lahir
 * @param string $tanggal_lahir - Format: YYYY-MM-DD
 * @return int - Usia dalam tahun
 */
function hitungUsia($tanggal_lahir)
```

**Penjelasan:**
- Menggunakan DateTime class untuk perhitungan
- Menghitung selisih antara tanggal lahir dan hari ini
- Return nilai integer untuk usia

**Contoh:**
```php
$usia = hitungUsia('2003-05-15');  // Return: 22 atau 23 (tergantung hari ini)
```

#### 2. `formatTanggal($tanggal)`
```php
/**
 * Mengubah format tanggal YYYY-MM-DD menjadi DD/MM/YYYY
 * @param string $tanggal - Format: YYYY-MM-DD
 * @return string - Format: DD/MM/YYYY
 */
function formatTanggal($tanggal)
```

**Penjelasan:**
- Konversi format tanggal dari database ke format lokal
- Lebih readable untuk user

**Contoh:**
```php
echo formatTanggal('2003-05-15');  // Output: 15/05/2003
```

### Fungsi Validasi

#### 3. `validasiNIM($nim)`
```php
/**
 * Validasi format NIM
 * @param string $nim - NIM yang divalidasi
 * @return bool - TRUE jika valid, FALSE jika tidak
 */
function validasiNIM($nim)
```

**Kriteria Validasi:**
- Panjang: 5-20 karakter
- Hanya alphanumeric (a-z, A-Z, 0-9)
- Tidak boleh whitespace

**Contoh:**
```php
validasiNIM('19001');      // TRUE
validasiNIM('ABC');        // FALSE (kurang dari 5 karakter)
validasiNIM('19001!');     // FALSE (berisi karakter khusus)
```

#### 4. `validasiNama($nama)`
```php
/**
 * Validasi nama mahasiswa
 * @param string $nama - Nama yang divalidasi
 * @return bool - TRUE jika valid, FALSE jika tidak
 */
function validasiNama($nama)
```

**Kriteria Validasi:**
- Minimal 1 karakter
- Maksimal 100 karakter

#### 5. `validasiAlamat($alamat)`
```php
/**
 * Validasi alamat mahasiswa
 * @param string $alamat - Alamat yang divalidasi
 * @return bool - TRUE jika valid, FALSE jika tidak
 */
function validasiAlamat($alamat)
```

**Kriteria Validasi:**
- Minimal 1 karakter

#### 6. `validasiTanggalLahir($tanggal_lahir)`
```php
/**
 * Validasi tanggal lahir
 * @param string $tanggal_lahir - Format: YYYY-MM-DD
 * @return bool - TRUE jika valid, FALSE jika tidak
 */
function validasiTanggalLahir($tanggal_lahir)
```

**Kriteria Validasi:**
- Format date: YYYY-MM-DD
- Tidak boleh di masa depan
- Minimal usia 1 tahun

#### 7. `validasiGender($gender)`
```php
/**
 * Validasi jenis kelamin
 * @param string $gender - 'Laki-laki' atau 'Perempuan'
 * @return bool - TRUE jika valid, FALSE jika tidak
 */
function validasiGender($gender)
```

**Nilai Valid:**
- "Laki-laki"
- "Perempuan"

### Fungsi Database

#### 8. `nimSudahAda($conn, $nim, $excludeNIM = null)`
```php
/**
 * Cek apakah NIM sudah terdaftar
 * @param mysqli $conn - Database connection
 * @param string $nim - NIM yang dicek
 * @param string $excludeNIM - NIM yang dikecualikan (untuk edit)
 * @return bool - TRUE jika sudah ada, FALSE jika belum
 */
function nimSudahAda($conn, $nim, $excludeNIM = null)
```

**Penjelasan:**
- Mencegah duplikat NIM
- Parameter `$excludeNIM` digunakan saat edit untuk mengabaikan NIM saat ini

**Contoh:**
```php
// Pada saat tambah data
if (nimSudahAda($conn, '19001')) {
    echo "NIM sudah terdaftar!";
}

// Pada saat edit data
if (nimSudahAda($conn, '19001', '19001')) {
    echo "NIM sudah terdaftar! (dibatalkan karena NIM dikecualikan)";
}
```

#### 9. `tambahMahasiswa($conn, $data)`
```php
/**
 * Menambahkan data mahasiswa baru ke database
 * @param mysqli $conn - Database connection
 * @param array $data - Array dengan key [NIM, NAMA, ALAMAT, TANGGAL_LAHIR, GENDER]
 * @return array - ['status' => 'success'|'error', 'message' => string]
 */
function tambahMahasiswa($conn, $data)
```

**Penjelasan:**
- Validasi semua input data
- Menggunakan prepared statement (SQL Injection safe)
- Return array dengan status dan pesan

**Contoh:**
```php
$data = [
    'NIM' => '19001',
    'NAMA' => 'Budi Santoso',
    'ALAMAT' => 'Jl. Merdeka No. 10',
    'TANGGAL_LAHIR' => '2003-05-15',
    'GENDER' => 'Laki-laki'
];

$result = tambahMahasiswa($conn, $data);

if ($result['status'] === 'success') {
    echo $result['message'];  // Data mahasiswa berhasil ditambahkan!
} else {
    echo $result['message'];  // Pesan error
}
```

#### 10. `updateMahasiswa($conn, $data)`
```php
/**
 * Memperbarui data mahasiswa
 * @param mysqli $conn - Database connection
 * @param array $data - Array dengan key [NIM, NAMA, ALAMAT, TANGGAL_LAHIR, GENDER]
 * @return array - ['status' => 'success'|'error', 'message' => string]
 */
function updateMahasiswa($conn, $data)
```

**Penjelasan:**
- Sama seperti `tambahMahasiswa` tapi untuk update
- NIM tidak bisa diubah
- Return array dengan status dan pesan

#### 11. `hapusMahasiswa($conn, $nim)`
```php
/**
 * Menghapus data mahasiswa dari database
 * @param mysqli $conn - Database connection
 * @param string $nim - NIM mahasiswa yang dihapus
 * @return array - ['status' => 'success'|'error', 'message' => string]
 */
function hapusMahasiswa($conn, $nim)
```

#### 12. `ambilSemuaMahasiswa($conn, $search = '')`
```php
/**
 * Mengambil semua data mahasiswa
 * @param mysqli $conn - Database connection
 * @param string $search - Filter pencarian nama (optional)
 * @return array - Array hasil query
 */
function ambilSemuaMahasiswa($conn, $search = '')
```

**Penjelasan:**
- Mengambil data dari database
- Jika ada parameter `$search`, filter berdasarkan nama
- Always return data terurut descending by NIM

**Contoh:**
```php
// Ambil semua data
$mahasiswa = ambilSemuaMahasiswa($conn);

// Ambil data dengan filter nama
$mahasiswa = ambilSemuaMahasiswa($conn, 'Budi');
```

#### 13. `ambilMahasiswaByNIM($conn, $nim)`
```php
/**
 * Mengambil satu data mahasiswa berdasarkan NIM
 * @param mysqli $conn - Database connection
 * @param string $nim - NIM mahasiswa
 * @return array|null - Data mahasiswa atau NULL
 */
function ambilMahasiswaByNIM($conn, $nim)
```

#### 14. `hitungStatistik($conn)`
```php
/**
 * Menghitung statistik mahasiswa
 * @param mysqli $conn - Database connection
 * @return array - ['total' => int, 'laki_laki' => int, 'perempuan' => int]
 */
function hitungStatistik($conn)
```

**Penjelasan:**
- Menghitung total dan breakdown berdasarkan gender
- Menggunakan SQL aggregate functions (COUNT, SUM)

**Contoh:**
```php
$stats = hitungStatistik($conn);
echo "Total: " . $stats['total'];           // Total mahasiswa
echo "Laki-laki: " . $stats['laki_laki'];   // Jumlah Laki-laki
echo "Perempuan: " . $stats['perempuan'];   // Jumlah Perempuan
```

---

## FUNGSI DALAM FILE JAVASCRIPT

### File: `assets/js/script.js`

**Deskripsi:** JavaScript untuk validasi form dan interaksi interaktif.

### Fungsi Inisialisasi

#### 1. `initializeFormHandlers()`
**Deskripsi:** Setup event listeners untuk form submit

#### 2. `setupInputValidation()`
**Deskripsi:** Validasi input real-time:
- NIM: Hanya alphanumeric
- Tanggal: Tidak boleh masa depan
- Gender: Must select valid option

#### 3. `initializeDeleteHandlers()`
**Deskripsi:** Setup delete button event listeners

#### 4. `initializeModalHandlers()`
**Deskripsi:** Reset form saat modal ditutup

### Fungsi Form Handling

#### 5. `handleFormTambah(e)`
**Deskripsi:** Handle form submit untuk tambah data
- Lakukan validasi
- Submit via AJAX ke add.php
- Show success/error alert
- Refresh page

**Validasi:**
- Semua field harus terisi
- NIM format valid (5-20 alphanumeric)
- Nama 1-100 karakter
- Usia minimal 1 tahun

#### 6. `handleFormEdit(e)`
**Deskripsi:** Handle form submit untuk edit data
- Lakukan validasi (sama seperti tambah)
- Submit via AJAX ke edit.php
- Redirect ke admin page

### Utility Functions

#### 7. `showAlert(type, message)`
**Parameter:**
- type: 'success', 'error', 'warning', 'info'
- message: String pesan

**Penjelasan:** Tampilkan notifikasi menggunakan SweetAlert2

#### 8. `formatCurrency(value)`
Mengformat number ke format currency IDR

#### 9. `formatDate(dateString)`
Mengformat date dari YYYY-MM-DD ke format lokal

#### 10. `validateEmail(email)`
Validasi format email

#### 11. `validatePhone(phone)`
Validasi format nomor telepon

#### 12. `calculateAge(birthDate)`
Menghitung usia dari tanggal lahir

#### 13. `debounce(func, wait)`
Debounce function untuk delay eksekusi

#### 14. `throttle(func, limit)`
Throttle function untuk membatasi frekuensi eksekusi

#### 15. Local Storage Functions
- `getFromLocalStorage(key)`
- `setInLocalStorage(key, value)`
- `removeFromLocalStorage(key)`
- `clearAllLocalStorage()`

---

