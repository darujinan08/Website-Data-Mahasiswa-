<?php
/**
 * ============================================
 * FILE: admin/index.php
 * HALAMAN: ADMIN (Kelola Data Mahasiswa)
 * DESKRIPSI: Halaman admin untuk CRUD (Create, Read, Update, Delete)
 *            data mahasiswa
 * ============================================
 */

// Include file konfigurasi dan functions
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Koneksi ke database
$conn = getDBConnection();

// Inisialisasi variabel
$mahasiswa = ambilSemuaMahasiswa($conn);
$statistik = hitungStatistik($conn);
$editData = null;
$showEditModal = false;

// Cek jika ada request untuk edit
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['nim'])) {
    $editData = ambilMahasiswaByNIM($conn, $_GET['nim']);
    if ($editData) {
        $showEditModal = true;
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman ADMIN - Manajemen Data Mahasiswa</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="../index.php">
                <i class="bi bi-mortarboard"></i> MANAJEMEN DATA MAHASISWA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">
                            <i class="bi bi-house"></i> HOME
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">
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
                    <i class="bi bi-gear"></i> KELOLA DATA MAHASISWA
                </h2>
                <p class="text-muted">Panel administrasi untuk mengelola data mahasiswa</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-md-12">
                <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-circle"></i> TAMBAH DATA
                </button>
                <a href="index.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-arrow-clockwise"></i> REFRESH
                </a>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="row mb-4">
            <!-- Total Mahasiswa -->
            <div class="col-md-3">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                        <h6 class="card-title mt-2">Total Mahasiswa</h6>
                        <p class="card-text h4 fw-bold"><?php echo $statistik['total']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Laki-laki -->
            <div class="col-md-3">
                <div class="card bg-info text-white shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-person" style="font-size: 2rem;"></i>
                        <h6 class="card-title mt-2">Laki-laki</h6>
                        <p class="card-text h4 fw-bold"><?php echo $statistik['laki_laki']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Perempuan -->
            <div class="col-md-3">
                <div class="card bg-danger text-white shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-person-fill" style="font-size: 2rem;"></i>
                        <h6 class="card-title mt-2">Perempuan</h6>
                        <p class="card-text h4 fw-bold"><?php echo $statistik['perempuan']; ?></p>
                    </div>
                </div>
            </div>

            <!-- Persentase -->
            <div class="col-md-3">
                <div class="card bg-warning text-dark shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-pie-chart" style="font-size: 2rem;"></i>
                        <h6 class="card-title mt-2">Statistik</h6>
                        <p class="card-text small">
                            L: <?php echo $statistik['total'] > 0 ? round(($statistik['laki_laki'] / $statistik['total']) * 100) : 0; ?>% | 
                            P: <?php echo $statistik['total'] > 0 ? round(($statistik['perempuan'] / $statistik['total']) * 100) : 0; ?>%
                        </p>
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
                            Daftar Mahasiswa (<?php echo count($mahasiswa); ?> data)
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($mahasiswa)): ?>
                            <!-- Jika tidak ada data -->
                            <div class="alert alert-info" role="alert">
                                <i class="bi bi-info-circle"></i>
                                Belum ada data mahasiswa. Silakan klik tombol "TAMBAH DATA" untuk menambahkan data baru.
                            </div>
                        <?php else: ?>
                            <!-- Tabel Data Mahasiswa -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th style="width: 8%;">NIM</th>
                                            <th style="width: 18%;">Nama</th>
                                            <th style="width: 22%;">Alamat</th>
                                            <th style="width: 12%;">Tanggal Lahir</th>
                                            <th style="width: 10%;">Gender</th>
                                            <th style="width: 8%; text-align: center;">Usia</th>
                                            <th style="width: 12%; text-align: center;">Aksi</th>
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
                                                <td style="text-align: center;">
                                                    <strong><?php echo hitungUsia($row['TANGGAL_LAHIR']); ?> tahun</strong>
                                                </td>
                                                <td style="text-align: center;">
                                                    <!-- Tombol Edit -->
                                                    <a href="?action=edit&nim=<?php echo urlencode($row['NIM']); ?>" 
                                                       class="btn btn-sm btn-warning btn-edit" 
                                                       title="Edit Data">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <!-- Tombol Hapus -->
                                                    <button class="btn btn-sm btn-danger btn-delete" 
                                                            data-nim="<?php echo htmlspecialchars($row['NIM']); ?>"
                                                            data-nama="<?php echo htmlspecialchars($row['NAMA']); ?>"
                                                            title="Hapus Data">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
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
                    &copy; 2026 Sistem Manajemen Data Mahasiswa | Panel Admin
                </p>
            </div>
        </div>
    </div>

    <!-- ===== MODAL TAMBAH DATA ===== -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="modalTambahLabel">
                        <i class="bi bi-plus-circle"></i> TAMBAH DATA MAHASISWA
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formTambah" method="POST" action="add.php">
                    <div class="modal-body">
                        <!-- NIM -->
                        <div class="mb-3">
                            <label for="nimTambah" class="form-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nimTambah" name="NIM" 
                                   placeholder="Contoh: 19001" required>
                            <small class="form-text text-muted">Format: Alphanumeric, 5-20 karakter</small>
                        </div>

                        <!-- NAMA -->
                        <div class="mb-3">
                            <label for="namaTambah" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="namaTambah" name="NAMA" 
                                   placeholder="Masukkan nama mahasiswa" required>
                        </div>

                        <!-- ALAMAT -->
                        <div class="mb-3">
                            <label for="alamatTambah" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamatTambah" name="ALAMAT" rows="3" 
                                      placeholder="Masukkan alamat lengkap" required></textarea>
                        </div>

                        <!-- TANGGAL LAHIR -->
                        <div class="mb-3">
                            <label for="tglLahirTambah" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tglLahirTambah" name="TANGGAL_LAHIR" required>
                        </div>

                        <!-- GENDER -->
                        <div class="mb-3">
                            <label for="genderTambah" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" id="genderTambah" name="GENDER" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <!-- USIA (AUTO CALCULATED) -->
                        <div class="mb-3">
                            <label for="usiaTambah" class="form-label">Usia <span class="text-secondary">(Otomatis)</span></label>
                            <input type="text" class="form-control" id="usiaTambah" 
                                   value="Pilih tanggal lahir" disabled>
                            <small class="form-text text-muted">Usia akan dihitung otomatis dari tanggal lahir</small>
                        </div>

                        <hr>
                        <p class="text-muted small">
                            <span class="text-danger">*</span> = Kolom wajib diisi
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== MODAL EDIT DATA ===== -->
    <?php if ($showEditModal && $editData): ?>
    <div class="modal fade show" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="false" style="display: block; background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="modalEditLabel">
                        <i class="bi bi-pencil"></i> EDIT DATA MAHASISWA
                    </h5>
                    <button type="button" class="btn-close" id="closeEditModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEdit" method="POST" action="edit.php">
                    <div class="modal-body">
                        <!-- NIM (DISABLED) -->
                        <div class="mb-3">
                            <label for="nimEdit" class="form-label">NIM <span class="text-danger">*</span> (Tidak Dapat Diubah)</label>
                            <input type="text" class="form-control" id="nimEdit" 
                                   value="<?php echo htmlspecialchars($editData['NIM']); ?>" disabled>
                            <input type="hidden" name="NIM" value="<?php echo htmlspecialchars($editData['NIM']); ?>">
                            <small class="form-text text-muted">NIM tidak dapat diubah untuk menjaga integritas data</small>
                        </div>

                        <!-- NAMA -->
                        <div class="mb-3">
                            <label for="namaEdit" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="namaEdit" name="NAMA" 
                                   value="<?php echo htmlspecialchars($editData['NAMA']); ?>" required>
                        </div>

                        <!-- ALAMAT -->
                        <div class="mb-3">
                            <label for="alamatEdit" class="form-label">Alamat <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="alamatEdit" name="ALAMAT" rows="3" required><?php echo htmlspecialchars($editData['ALAMAT']); ?></textarea>
                        </div>

                        <!-- TANGGAL LAHIR -->
                        <div class="mb-3">
                            <label for="tglLahirEdit" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="tglLahirEdit" name="TANGGAL_LAHIR" 
                                   value="<?php echo htmlspecialchars($editData['TANGGAL_LAHIR']); ?>" required>
                        </div>

                        <!-- GENDER -->
                        <div class="mb-3">
                            <label for="genderEdit" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                            <select class="form-select" id="genderEdit" name="GENDER" required>
                                <option value="Laki-laki" <?php echo $editData['GENDER'] === 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="Perempuan" <?php echo $editData['GENDER'] === 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>

                        <!-- USIA (AUTO CALCULATED) -->
                        <div class="mb-3">
                            <label for="usiaEdit" class="form-label">Usia <span class="text-secondary">(Otomatis)</span></label>
                            <input type="text" class="form-control" id="usiaEdit" 
                                   value="<?php echo hitungUsia($editData['TANGGAL_LAHIR']); ?> tahun" disabled>
                            <small class="form-text text-muted">Usia dihitung otomatis dari tanggal lahir</small>
                        </div>

                        <hr>
                        <p class="text-muted small">
                            <span class="text-danger">*</span> = Kolom wajib diisi
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check"></i> Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js"></script>
    
    <!-- Custom JS -->
    <script src="../assets/js/script.js"></script>

    <script>
        /**
         * SCRIPT: Konfirmasi Hapus Data
         * EVENT: Click pada tombol delete
         * AKSI: Tampilkan konfirmasi SweetAlert2 sebelum menghapus
         */
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const nim = this.dataset.nim;
                const nama = this.dataset.nama;
                
                Swal.fire({
                    title: 'Konfirmasi Penghapusan',
                    html: `<p>Apakah Anda yakin ingin menghapus data?</p>
                           <strong>NIM:</strong> ${nim}<br>
                           <strong>Nama:</strong> ${nama}<br>
                           <p class="text-danger mt-3">Tindakan ini tidak dapat dibatalkan!</p>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Arahkan ke halaman delete dengan NIM
                        window.location.href = `delete.php?nim=${encodeURIComponent(nim)}`;
                    }
                });
            });
        });

        /**
         * SCRIPT: Tutup Modal Edit Ketika Batal
         * TUJUAN: Redirect ke index.php saat user klik batal pada modal edit
         */
        <?php if ($showEditModal): ?>
            document.getElementById('closeEditModal')?.addEventListener('click', function() {
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 100);
            });
            
            // Juga handle button batal
            document.querySelectorAll('#modalEdit .btn-secondary').forEach(button => {
                button.addEventListener('click', function() {
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 100);
                });
            });
        <?php endif; ?>

        /**
         * SCRIPT: Hitung Usia Real-time
         * FUNGSI: Menghitung usia otomatis saat user mengubah tanggal lahir
         */
        function hitungUsiaRealtime(tanggalLahir) {
            if (!tanggalLahir) return 'Pilih tanggal lahir';
            
            const today = new Date();
            const birthDate = new Date(tanggalLahir);
            
            if (isNaN(birthDate.getTime())) return 'Tanggal tidak valid';
            if (birthDate > today) return 'Tanggal tidak valid (masa depan)';
            
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            return age < 0 ? 'Tanggal tidak valid' : age + ' tahun';
        }

        // Event listener untuk modal tambah
        const tglLahirTambah = document.getElementById('tglLahirTambah');
        const usiaTambah = document.getElementById('usiaTambah');
        if (tglLahirTambah && usiaTambah) {
            tglLahirTambah.addEventListener('change', function() {
                usiaTambah.value = hitungUsiaRealtime(this.value);
            });
        }

        // Event listener untuk modal edit
        const tglLahirEdit = document.getElementById('tglLahirEdit');
        const usiaEdit = document.getElementById('usiaEdit');
        if (tglLahirEdit && usiaEdit) {
            tglLahirEdit.addEventListener('change', function() {
                usiaEdit.value = hitungUsiaRealtime(this.value);
            });
        }
    </script>
</body>
</html>

<?php
// Tutup koneksi database
mysqli_close($conn);
?>
