<?php
/**
 * ============================================
 * FILE: admin/edit.php
 * DESKRIPSI: Proses pembaruan data mahasiswa
 * METHOD: POST dari formEdit di admin/index.php
 * CATATAN: Field NIM tidak dapat diubah (read-only)
 * ============================================
 */

// Include file konfigurasi dan functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

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

    // Validasi data
    if (empty($data['NIM']) || empty($data['NAMA']) || empty($data['ALAMAT']) || 
        empty($data['TANGGAL_LAHIR']) || empty($data['GENDER'])) {
        
        $response = [
            'status' => 'error',
            'message' => 'Semua field harus diisi!'
        ];
    } else {
        // Panggil function updateMahasiswa dari functions.php
        $result = updateMahasiswa($conn, $data);
        
        // Response JSON
        $response = [
            'status' => $result['status'],
            'message' => $result['message']
        ];
    }

    // Tutup koneksi
    mysqli_close($conn);

} else {
    // Jika bukan POST, berikan error
    $response = [
        'status' => 'error',
        'message' => 'Request method tidak valid!'
    ];
}

// Kirim response sebagai JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;

?>
