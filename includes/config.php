<?php
/**
 * ============================================
 * FILE: config.php
 * DESKRIPSI: Konfigurasi koneksi database
 * ============================================
 */

// Konfigurasi Database
define('DB_HOST', 'localhost');          // Database host
define('DB_USER', 'root');               // Database user
define('DB_PASS', '');                   // Database password (kosong default XAMPP)
define('DB_NAME', 'db_mahasiswa');       // Nama database

// Timezone
date_default_timezone_set('Asia/Jakarta');

/**
 * FUNGSI: Koneksi Database
 * DESKRIPSI: Membuat koneksi ke database MySQL menggunakan MySQLi
 * RETURN: mysqli object atau FALSE jika gagal
 */
function getDBConnection()
{
    // Buat koneksi menggunakan MySQLi procedural
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Cek apakah koneksi berhasil
    if (!$conn) {
        die("❌ Koneksi Database Gagal: " . mysqli_connect_error());
    }
    
    // Set UTF-8 character set
    mysqli_set_charset($conn, "utf8mb4");
    
    return $conn;
}

/**
 * FUNGSI: Tampilkan Error
 * DESKRIPSI: Menampilkan pesan error dalam format yang user-friendly
 * PARAM: $message = pesan error yang ingin ditampilkan
 */
function showError($message)
{
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    echo '<strong>Error!</strong> ' . htmlspecialchars($message);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    echo '</div>';
}

/**
 * FUNGSI: Tampilkan Sukses
 * DESKRIPSI: Menampilkan pesan sukses dalam format yang user-friendly
 * PARAM: $message = pesan sukses yang ingin ditampilkan
 */
function showSuccess($message)
{
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo '<strong>Sukses!</strong> ' . htmlspecialchars($message);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
    echo '</div>';
}

?>
