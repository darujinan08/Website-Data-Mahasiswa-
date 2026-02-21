<?php
/**
 * ============================================
 * FILE: admin/add.php
 * DESKRIPSI: Proses penambahan data mahasiswa baru
 * METHOD: POST dari formTambah di admin/index.php
 * ============================================
 */

// Include file konfigurasi dan functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Variabel untuk pesan
$message = '';
$messageType = '';

// Cek method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $data = [
        'NIM' => isset($_POST['NIM']) ? trim($_POST['NIM']) : '',
        'NAMA' => isset($_POST['NAMA']) ? trim($_POST['NAMA']) : '',
        'ALAMAT' => isset($_POST['ALAMAT']) ? trim($_POST['ALAMAT']) : '',
        'TANGGAL_LAHIR' => isset($_POST['TANGGAL_LAHIR']) ? trim($_POST['TANGGAL_LAHIR']) : '',
        'GENDER' => isset($_POST['GENDER']) ? trim($_POST['GENDER']) : ''
    ];

    // Koneksi ke database
    $conn = getDBConnection();

    // Validasi data menggunakan function dari functions.php
    if (empty($data['NIM']) || empty($data['NAMA']) || empty($data['ALAMAT']) || 
        empty($data['TANGGAL_LAHIR']) || empty($data['GENDER'])) {
        
        // Jika ada field yang kosong
        $response = [
            'status' => 'error',
            'message' => 'Semua field harus diisi!',
            'type' => 'validation'
        ];
    } else {
        // Panggil function tambahMahasiswa dari functions.php
        $result = tambahMahasiswa($conn, $data);
        
        // Response JSON
        $response = [
            'status' => $result['status'],
            'message' => $result['message'],
            'type' => 'process'
        ];
    }

    // Tutup koneksi
    mysqli_close($conn);

} else {
    // Jika bukan POST, berikan error
    $response = [
        'status' => 'error',
        'message' => 'Request method tidak valid!',
        'type' => 'error'
    ];
}

// Kirim response sebagai JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;

?>
