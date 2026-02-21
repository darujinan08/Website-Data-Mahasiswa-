<?php
/**
 * ============================================
 * FILE: admin/delete.php
 * DESKRIPSI: Proses penghapusan data mahasiswa
 * METHOD: GET dari konfirmasi SweetAlert2 di admin/index.php
 * ============================================
 */

// Include file konfigurasi dan functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Ambil parameter NIM dari URL
$nim = isset($_GET['nim']) ? trim($_GET['nim']) : '';

// Koneksi ke database
$conn = getDBConnection();

// Validasi NIM
if (empty($nim)) {
    $response = [
        'status' => 'error',
        'message' => 'NIM tidak ditemukan!'
    ];
} else {
    // Panggil function hapusMahasiswa dari functions.php
    $result = hapusMahasiswa($conn, $nim);
    
    // Response JSON
    $response = [
        'status' => $result['status'],
        'message' => $result['message']
    ];
}

// Tutup koneksi
mysqli_close($conn);

// Cek apakah ini AJAX request atau regular request
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    // Response sebagai JSON untuk AJAX
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Redirect ke halaman admin dengan pesan
    if ($response['status'] === 'success') {
        header('Location: index.php?success=' . urlencode($response['message']));
    } else {
        header('Location: index.php?error=' . urlencode($response['message']));
    }
}

exit;

?>
