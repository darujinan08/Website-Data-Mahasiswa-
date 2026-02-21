<?php
/**
 * ============================================
 * FILE: functions.php
 * DESKRIPSI: Fungsi-fungsi helper untuk aplikasi
 * ============================================
 */

// Include konfigurasi database
require_once __DIR__ . '/config.php';

/**
 * FUNGSI: Hitung Usia
 * DESKRIPSI: Menghitung usia berdasarkan tanggal lahir
 * PARAM: $tanggal_lahir = tanggal lahir dalam format YYYY-MM-DD
 * RETURN: Integer usia dalam tahun
 */
function hitungUsia($tanggal_lahir)
{
    // Membuat DateTime object dari tanggal lahir
    $birthDate = new DateTime($tanggal_lahir);
    
    // Membuat DateTime object untuk hari ini
    $today = new DateTime('today');
    
    // Menghitung interval (selisih) antara kedua tanggal
    $age = $birthDate->diff($today)->y;
    
    return $age;
}

/**
 * FUNGSI: Format Tanggal
 * DESKRIPSI: Mengubah format tanggal dari YYYY-MM-DD menjadi DD/MM/YYYY
 * PARAM: $tanggal = tanggal dalam format YYYY-MM-DD
 * RETURN: String tanggal dalam format DD/MM/YYYY
 */
function formatTanggal($tanggal)
{
    return date('d/m/Y', strtotime($tanggal));
}

/**
 * FUNGSI: Validasi NIM
 * DESKRIPSI: Validasi format NIM (alphanumeric, panjang 5-20)
 * PARAM: $nim = NIM yang ingin divalidasi
 * RETURN: Boolean TRUE jika valid, FALSE jika tidak
 */
function validasiNIM($nim)
{
    // Hapus whitespace
    $nim = trim($nim);
    
    // Cek panjang NIM (antara 5-20 karakter)
    if (strlen($nim) < 5 || strlen($nim) > 20) {
        return false;
    }
    
    // Cek hanya alphanumeric
    if (!preg_match('/^[a-zA-Z0-9]+$/', $nim)) {
        return false;
    }
    
    return true;
}

/**
 * FUNGSI: Validasi Nama
 * DESKRIPSI: Validasi nama mahasiswa
 * PARAM: $nama = nama yang ingin divalidasi
 * RETURN: Boolean TRUE jika valid, FALSE jika tidak
 */
function validasiNama($nama)
{
    // Hapus whitespace
    $nama = trim($nama);
    
    // Cek panjang nama (1-100 karakter)
    if (strlen($nama) < 1 || strlen($nama) > 100) {
        return false;
    }
    
    return true;
}

/**
 * FUNGSI: Validasi Alamat
 * DESKRIPSI: Validasi alamat mahasiswa
 * PARAM: $alamat = alamat yang ingin divalidasi
 * RETURN: Boolean TRUE jika valid, FALSE jika tidak
 */
function validasiAlamat($alamat)
{
    // Hapus whitespace di awal dan akhir
    $alamat = trim($alamat);
    
    // Cek minimal 1 karakter
    if (strlen($alamat) < 1) {
        return false;
    }
    
    return true;
}

/**
 * FUNGSI: Validasi Tanggal Lahir
 * DESKRIPSI: Validasi tanggal lahir
 * PARAM: $tanggal_lahir = tanggal lahir dalam format YYYY-MM-DD
 * RETURN: Boolean TRUE jika valid, FALSE jika tidak
 */
function validasiTanggalLahir($tanggal_lahir)
{
    // Cek format tanggal
    $d = DateTime::createFromFormat('Y-m-d', $tanggal_lahir);
    if (!$d || $d->format('Y-m-d') != $tanggal_lahir) {
        return false;
    }
    
    // Cek tidak boleh di masa depan
    if (strtotime($tanggal_lahir) > strtotime('today')) {
        return false;
    }
    
    // Cek minimal usia 1 tahun
    $usia = hitungUsia($tanggal_lahir);
    if ($usia < 1) {
        return false;
    }
    
    return true;
}

/**
 * FUNGSI: Validasi Gender
 * DESKRIPSI: Validasi gender mahasiswa
 * PARAM: $gender = gender yang ingin divalidasi
 * RETURN: Boolean TRUE jika valid, FALSE jika tidak
 */
function validasiGender($gender)
{
    // Gender hanya boleh: Laki-laki atau Perempuan
    $genderValid = ['Laki-laki', 'Perempuan'];
    
    return in_array($gender, $genderValid);
}

/**
 * FUNGSI: Cek NIM Sudah Ada
 * DESKRIPSI: Mengecek apakah NIM sudah terdaftar di database
 * PARAM: $conn = database connection, $nim = NIM yang dicek, $excludeNIM = NIM yang dikecualikan (untuk edit)
 * RETURN: Boolean TRUE jika ada, FALSE jika tidak ada
 */
function nimSudahAda($conn, $nim, $excludeNIM = null)
{
    if (!$excludeNIM) {
        $query = "SELECT NIM FROM mahasiswa WHERE NIM = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $nim);
    } else {
        // Untuk edit, abaikan NIM yang sedang diedit
        $query = "SELECT NIM FROM mahasiswa WHERE NIM = ? AND NIM != ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $nim, $excludeNIM);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    return mysqli_num_rows($result) > 0;
}

/**
 * FUNGSI: Tambah Data Mahasiswa
 * DESKRIPSI: Menambahkan data mahasiswa ke database
 * PARAM: $conn = database connection, $data = array dengan key [NIM, NAMA, ALAMAT, TANGGAL_LAHIR, GENDER]
 * RETURN: Array ['status' => 'success'|'error', 'message' => string]
 */
function tambahMahasiswa($conn, $data)
{
    // Validasi input
    if (!validasiNIM($data['NIM'])) {
        return ['status' => 'error', 'message' => 'Format NIM tidak valid!'];
    }
    
    if (nimSudahAda($conn, $data['NIM'])) {
        return ['status' => 'error', 'message' => 'NIM sudah terdaftar!'];
    }
    
    if (!validasiNama($data['NAMA'])) {
        return ['status' => 'error', 'message' => 'Nama tidak valid!'];
    }
    
    if (!validasiAlamat($data['ALAMAT'])) {
        return ['status' => 'error', 'message' => 'Alamat tidak valid!'];
    }
    
    if (!validasiTanggalLahir($data['TANGGAL_LAHIR'])) {
        return ['status' => 'error', 'message' => 'Tanggal lahir tidak valid!'];
    }
    
    if (!validasiGender($data['GENDER'])) {
        return ['status' => 'error', 'message' => 'Gender tidak valid!'];
    }
    
    // Prepare statement untuk mencegah SQL Injection
    $query = "INSERT INTO mahasiswa (NIM, NAMA, ALAMAT, TANGGAL_LAHIR, GENDER) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        return ['status' => 'error', 'message' => 'Erro prepare statement: ' . mysqli_error($conn)];
    }
    
    mysqli_stmt_bind_param($stmt, "sssss", $data['NIM'], $data['NAMA'], $data['ALAMAT'], $data['TANGGAL_LAHIR'], $data['GENDER']);
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return ['status' => 'success', 'message' => 'Data mahasiswa berhasil ditambahkan!'];
    } else {
        return ['status' => 'error', 'message' => 'Gagal menambahkan data: ' . mysqli_error($conn)];
    }
}

/**
 * FUNGSI: Update Data Mahasiswa
 * DESKRIPSI: Memperbarui data mahasiswa di database
 * PARAM: $conn = database connection, $data = array dengan key [NIM, NAMA, ALAMAT, TANGGAL_LAHIR, GENDER]
 * RETURN: Array ['status' => 'success'|'error', 'message' => string]
 */
function updateMahasiswa($conn, $data)
{
    // Validasi input
    if (!validasiNIM($data['NIM'])) {
        return ['status' => 'error', 'message' => 'Format NIM tidak valid!'];
    }
    
    if (!validasiNama($data['NAMA'])) {
        return ['status' => 'error', 'message' => 'Nama tidak valid!'];
    }
    
    if (!validasiAlamat($data['ALAMAT'])) {
        return ['status' => 'error', 'message' => 'Alamat tidak valid!'];
    }
    
    if (!validasiTanggalLahir($data['TANGGAL_LAHIR'])) {
        return ['status' => 'error', 'message' => 'Tanggal lahir tidak valid!'];
    }
    
    if (!validasiGender($data['GENDER'])) {
        return ['status' => 'error', 'message' => 'Gender tidak valid!'];
    }
    
    // Prepare statement
    $query = "UPDATE mahasiswa SET NAMA=?, ALAMAT=?, TANGGAL_LAHIR=?, GENDER=? WHERE NIM=?";
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        return ['status' => 'error', 'message' => 'Error prepare statement: ' . mysqli_error($conn)];
    }
    
    mysqli_stmt_bind_param($stmt, "sssss", $data['NAMA'], $data['ALAMAT'], $data['TANGGAL_LAHIR'], $data['GENDER'], $data['NIM']);
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return ['status' => 'success', 'message' => 'Data mahasiswa berhasil diperbarui!'];
    } else {
        return ['status' => 'error', 'message' => 'Gagal memperbarui data: ' . mysqli_error($conn)];
    }
}

/**
 * FUNGSI: Hapus Data Mahasiswa
 * DESKRIPSI: Menghapus data mahasiswa dari database
 * PARAM: $conn = database connection, $nim = NIM mahasiswa yang dihapus
 * RETURN: Array ['status' => 'success'|'error', 'message' => string]
 */
function hapusMahasiswa($conn, $nim)
{
    // Validasi NIM
    if (!validasiNIM($nim)) {
        return ['status' => 'error', 'message' => 'NIM tidak valid!'];
    }
    
    // Prepare statement
    $query = "DELETE FROM mahasiswa WHERE NIM = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if (!$stmt) {
        return ['status' => 'error', 'message' => 'Error prepare statement: ' . mysqli_error($conn)];
    }
    
    mysqli_stmt_bind_param($stmt, "s", $nim);
    
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return ['status' => 'success', 'message' => 'Data mahasiswa berhasil dihapus!'];
    } else {
        return ['status' => 'error', 'message' => 'Gagal menghapus data: ' . mysqli_error($conn)];
    }
}

/**
 * FUNGSI: Ambil Semua Data Mahasiswa
 * DESKRIPSI: Mengambil semua data mahasiswa dari database
 * PARAM: $conn = database connection, $search = pencarian berdasarkan nama (optional)
 * RETURN: Array hasil query
 */
function ambilSemuaMahasiswa($conn, $search = '')
{
    if (!empty($search)) {
        // Jika ada pencarian, filter berdasarkan nama
        $search = '%' . $search . '%';
        $query = "SELECT * FROM mahasiswa WHERE NAMA LIKE ? ORDER BY NIM DESC";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $search);
    } else {
        // Jika tidak ada pencarian, tampilkan semua
        $query = "SELECT * FROM mahasiswa ORDER BY NIM DESC";
        $stmt = mysqli_prepare($conn, $query);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    mysqli_stmt_close($stmt);
    
    return $data;
}

/**
 * FUNGSI: Ambil Data Mahasiswa Berdasarkan NIM
 * DESKRIPSI: Mengambil data satu mahasiswa dari database
 * PARAM: $conn = database connection, $nim = NIM mahasiswa
 * RETURN: Array data mahasiswa atau NULL jika tidak ditemukan
 */
function ambilMahasiswaByNIM($conn, $nim)
{
    $query = "SELECT * FROM mahasiswa WHERE NIM = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $nim);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    return $data;
}

/**
 * FUNGSI: Hitung Statistik Mahasiswa
 * DESKRIPSI: Menghitung statistik mahasiswa berdasarkan gender
 * PARAM: $conn = database connection
 * RETURN: Array dengan key [total, laki_laki, perempuan]
 */
function hitungStatistik($conn)
{
    $query = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN GENDER='Laki-laki' THEN 1 ELSE 0 END) as laki_laki,
                SUM(CASE WHEN GENDER='Perempuan' THEN 1 ELSE 0 END) as perempuan
              FROM mahasiswa";
    
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($result);
    
    return [
        'total' => $data['total'] ?? 0,
        'laki_laki' => $data['laki_laki'] ?? 0,
        'perempuan' => $data['perempuan'] ?? 0
    ];
}

?>
