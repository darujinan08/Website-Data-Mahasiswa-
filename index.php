<?php
/**
 * ============================================
 * FILE: index.php
 * HALAMAN: HOME (Tampilan Data Mahasiswa)
 * DESKRIPSI: Halaman utama yang menampilkan daftar mahasiswa
 *            dengan fitur search dan statistik
 * ============================================
 */

// Include file konfigurasi dan functions
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

// Koneksi ke database
$conn = getDBConnection();

// Inisialisasi variabel
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$mahasiswa = ambilSemuaMahasiswa($conn, $search);
$statistik = hitungStatistik($conn);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman HOME - Manajemen Data Mahasiswa</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-mortarboard"></i> MANAJEMEN DATA MAHASISWA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
                            <i class="bi bi-house"></i> HOME
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/index.php">
                            <i class="bi bi-gear"></i> ADMIN
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h2 class="text-primary fw-bold">
                    <i class="bi bi-people"></i> DATA MAHASISWA
                </h2>
                <p class="text-muted">Berikut adalah daftar seluruh mahasiswa yang terdaftar dalam sistem</p>
            </div>
        </div>

        <!-- Search Section -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form method="GET" action="index.php" class="d-flex gap-2">
                    <input 
                        type="text" 
                        class="form-control" 
                        placeholder="Cari nama mahasiswa..." 
                        name="search" 
                        value="<?php echo htmlspecialchars($search); ?>"
                    >
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="bi bi-x"></i> Reset
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="row mb-4">
            <!-- Total Mahasiswa -->
            <div class="col-md-4">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-people" style="font-size: 2.5rem;"></i>
                        <h5 class="card-title mt-3">Total Mahasiswa</h5>
                        <p class="card-text h3 fw-bold"><?php echo $statistik['total']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Laki-laki -->
            <div class="col-md-4">
                <div class="card bg-info text-white shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-person" style="font-size: 2.5rem;"></i>
                        <h5 class="card-title mt-3">Laki-laki</h5>
                        <p class="card-text h3 fw-bold"><?php echo $statistik['laki_laki']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Perempuan -->
            <div class="col-md-4">
                <div class="card bg-danger text-white shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-person-fill" style="font-size: 2.5rem;"></i>
                        <h5 class="card-title mt-3">Perempuan</h5>
                        <p class="card-text h3 fw-bold"><?php echo $statistik['perempuan']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-table"></i> 
                            Daftar Mahasiswa
                            <?php if (!empty($search)): ?>
                                <span class="badge bg-warning text-dark">Hasil pencarian: <?php echo count($mahasiswa); ?></span>
                            <?php endif; ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($mahasiswa)): ?>
                            <!-- Jika tidak ada data -->
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i>
                                <?php if (!empty($search)): ?>
                                    Tidak ada data mahasiswa dengan nama "<strong><?php echo htmlspecialchars($search); ?></strong>"
                                <?php else: ?>
                                    Belum ada data mahasiswa terdaftar.
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <!-- Tabel Data Mahasiswa -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th style="width: 10%;">NIM</th>
                                            <th style="width: 15%;">Nama</th>
                                            <th style="width: 25%;">Alamat</th>
                                            <th style="width: 12%;">Tanggal Lahir</th>
                                            <th style="width: 8%;">Gender</th>
                                            <th style="width: 8%;">Usia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($mahasiswa as $row): ?>
                                            <tr>
                                                <td><strong><?php echo htmlspecialchars($row['NIM']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($row['NAMA']); ?></td>
                                                <td><?php echo htmlspecialchars($row['ALAMAT']); ?></td>
                                                <td><?php echo formatTanggal($row['TANGGAL_LAHIR']); ?></td>
                                                <td>
                                                    <?php if ($row['GENDER'] === 'Laki-laki'): ?>
                                                        <span class="badge bg-info"><i class="bi bi-person"></i> Laki-laki</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger"><i class="bi bi-person-fill"></i> Perempuan</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary"><?php echo hitungUsia($row['TANGGAL_LAHIR']); ?> tahun</span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="row mt-5 mb-4">
            <div class="col-md-12">
                <hr>
                <p class="text-center text-muted small">
                    &copy; 2026 Sistem Manajemen Data Mahasiswa | Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> oleh Admin
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
</body>
</html>

<?php
// Tutup koneksi database
mysqli_close($conn);
?>
