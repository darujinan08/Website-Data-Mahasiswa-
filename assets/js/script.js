/**
 * ============================================
 * FILE: assets/js/script.js
 * DESKRIPSI: JavaScript untuk validasi dan interaktif
 * ============================================
 */

/**
 * FUNGSI: Initialize
 * DESKRIPSI: Inisialisasi event listeners saat DOM loaded
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form handlers
    initializeFormHandlers();
    
    // Initialize delete button handlers
    initializeDeleteHandlers();
    
    // Initialize modal handlers
    initializeModalHandlers();
});

/**
 * FUNGSI: Initialize Form Handlers
 * DESKRIPSI: Setup event listeners untuk semua form
 */
function initializeFormHandlers() {
    // Form Tambah Data
    const formTambah = document.getElementById('formTambah');
    if (formTambah) {
        formTambah.addEventListener('submit', handleFormTambah);
    }

    // Form Edit Data
    const formEdit = document.getElementById('formEdit');
    if (formEdit) {
        formEdit.addEventListener('submit', handleFormEdit);
    }

    // Setup input validation listeners
    setupInputValidation();
}

/**
 * FUNGSI: Setup Input Validation
 * DESKRIPSI: Validasi input secara real-time
 */
function setupInputValidation() {
    // Validasi NIM - hanya alphanumeric
    const nimInputs = document.querySelectorAll('[id*="nim"]');
    nimInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Z0-9]/g, '');
        });
    });

    // Validasi Tanggal Lahir - tidak boleh di masa depan
    const dateInputs = document.querySelectorAll('[name="TANGGAL_LAHIR"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            
            if (selectedDate > today) {
                alert('Tanggal lahir tidak boleh di masa depan!');
                this.value = '';
            }
        });
    });

    // Validasi GENDER
    const genderSelects = document.querySelectorAll('[name="GENDER"]');
    genderSelects.forEach(select => {
        select.addEventListener('change', function() {
            if (this.value === '') {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
}

/**
 * FUNGSI: Handle Form Tambah
 * DESKRIPSI: Submit form tambah data via AJAX
 * EVENT: Submit pada formTambah
 */
function handleFormTambah(e) {
    e.preventDefault();

    // Ambil nilai form
    const form = this;
    const formData = new FormData(form);

    // Validasi basic
    const nim = formData.get('NIM').trim();
    const nama = formData.get('NAMA').trim();
    const alamat = formData.get('ALAMAT').trim();
    const tanggalLahir = formData.get('TANGGAL_LAHIR').trim();
    const gender = formData.get('GENDER').trim();

    if (!nim || !nama || !alamat || !tanggalLahir || !gender) {
        showAlert('error', 'Semua field harus diisi!');
        return false;
    }

    // Validasi NIM
    if (!/^[a-zA-Z0-9]{5,20}$/.test(nim)) {
        showAlert('error', 'Format NIM tidak valid! (Alphanumeric, 5-20 karakter)');
        return false;
    }

    // Validasi Nama
    if (nama.length < 1 || nama.length > 100) {
        showAlert('error', 'Nama harus 1-100 karakter!');
        return false;
    }

    // Validasi Tanggal Lahir
    const birthDate = new Date(tanggalLahir);
    const today = new Date();
    const age = Math.floor((today - birthDate) / (365.25 * 24 * 60 * 60 * 1000));

    if (age < 1) {
        showAlert('error', 'Mahasiswa harus minimal berusia 1 tahun!');
        return false;
    }

    // Kirim data via AJAX
    fetch('add.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showAlert('success', data.message);
            
            // Tutup modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambah'));
            if (modal) {
                modal.hide();
            }

            // Reset form
            form.reset();

            // Refresh halaman setelah 1.5 detik
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat menyimpan data!');
    });

    return false;
}

/**
 * FUNGSI: Handle Form Edit
 * DESKRIPSI: Submit form edit data via AJAX
 * EVENT: Submit pada formEdit
 */
function handleFormEdit(e) {
    e.preventDefault();

    // Ambil nilai form
    const form = this;
    const formData = new FormData(form);

    // Validasi basic
    const nim = formData.get('NIM').trim();
    const nama = formData.get('NAMA').trim();
    const alamat = formData.get('ALAMAT').trim();
    const tanggalLahir = formData.get('TANGGAL_LAHIR').trim();
    const gender = formData.get('GENDER').trim();

    if (!nim || !nama || !alamat || !tanggalLahir || !gender) {
        showAlert('error', 'Semua field harus diisi!');
        return false;
    }

    // Validasi Nama
    if (nama.length < 1 || nama.length > 100) {
        showAlert('error', 'Nama harus 1-100 karakter!');
        return false;
    }

    // Validasi Tanggal Lahir
    const birthDate = new Date(tanggalLahir);
    const today = new Date();
    const age = Math.floor((today - birthDate) / (365.25 * 24 * 60 * 60 * 1000));

    if (age < 1) {
        showAlert('error', 'Mahasiswa harus minimal berusia 1 tahun!');
        return false;
    }

    // Kirim data via AJAX
    fetch('edit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showAlert('success', data.message);

            // Refresh halaman setelah 1.5 detik
            setTimeout(() => {
                location.href = 'index.php';
            }, 1500);
        } else {
            showAlert('error', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Terjadi kesalahan saat memperbarui data!');
    });

    return false;
}

/**
 * FUNGSI: Initialize Delete Handlers
 * DESKRIPSI: Setup event listeners untuk tombol delete
 */
function initializeDeleteHandlers() {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const nim = this.dataset.nim;
            const nama = this.dataset.nama;

            // Konfirmasi dengan SweetAlert2 (sudah ditangani di admin/index.php)
            // Fungsi ini sebagai backup untuk handling non-AJAX delete
        });
    });
}

/**
 * FUNGSI: Initialize Modal Handlers
 * DESKRIPSI: Setup event listeners untuk modal
 */
function initializeModalHandlers() {
    // Reset form saat modal ditutup
    const modals = document.querySelectorAll('.modal');
    
    modals.forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function() {
            const form = this.querySelector('form');
            if (form) {
                form.reset();
            }
        });
    });
}

/**
 * FUNGSI: Show Alert
 * DESKRIPSI: Menampilkan notifikasi/alert
 * PARAM:
 *   - type: 'success', 'error', 'warning', 'info'
 *   - message: Pesan yang ingin ditampilkan
 */
function showAlert(type, message) {
    // Gunakan SweetAlert2 jika available, otherwise gunakan browser alert
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info',
            title: type === 'success' ? 'Berhasil!' : type === 'error' ? 'Gagal!' : type === 'warning' ? 'Peringatan!' : 'Informasi',
            text: message,
            toast: true,
            position: 'top-right',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    } else {
        alert(message);
    }
}

/**
 * FUNGSI: Format Currency
 * DESKRIPSI: Format angka ke format currency
 * PARAM: value = angka yang ingin diformat
 */
function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
}

/**
 * FUNGSI: Format Date
 * DESKRIPSI: Format date dari YYYY-MM-DD ke DD/MM/YYYY
 * PARAM: dateString = date dalam format YYYY-MM-DD
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
}

/**
 * FUNGSI: Validate Email
 * DESKRIPSI: Validasi format email
 * PARAM: email = email yang ingin divalidasi
 * RETURN: true jika valid, false jika tidak
 */
function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * FUNGSI: Validate Phone
 * DESKRIPSI: Validasi format nomor telepon
 * PARAM: phone = nomor telepon yang ingin divalidasi
 * RETURN: true jika valid, false jika tidak
 */
function validatePhone(phone) {
    const phoneRegex = /^(\+62|0)[0-9]{9,12}$/;
    return phoneRegex.test(phone);
}

/**
 * FUNGSI: Calculate Age
 * DESKRIPSI: Menghitung usia dari tanggal lahir
 * PARAM: birthDate = tanggal lahir dalam format YYYY-MM-DD atau Date object
 * RETURN: usia dalam tahun (integer)
 */
function calculateAge(birthDate) {
    if (typeof birthDate === 'string') {
        birthDate = new Date(birthDate);
    }
    
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    return age;
}

/**
 * FUNGSI: Debounce
 * DESKRIPSI: Debounce function untuk menunda eksekusi function
 * PARAM:
 *   - func: function yang ingin di-debounce
 *   - wait: waktu delay dalam milliseconds
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * FUNGSI: Throttle
 * DESKRIPSI: Throttle function untuk membatasi eksekusi function
 * PARAM:
 *   - func: function yang ingin di-throttle
 *   - limit: waktu limit dalam milliseconds
 */
function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

/**
 * FUNGSI: Get Local Storage
 * DESKRIPSI: Ambil data dari local storage
 * PARAM: key = key dari data yang ingin diambil
 * RETURN: nilai yang disimpan atau null jika tidak ada
 */
function getFromLocalStorage(key) {
    const data = localStorage.getItem(key);
    return data ? JSON.parse(data) : null;
}

/**
 * FUNGSI: Set Local Storage
 * DESKRIPSI: Simpan data ke local storage
 * PARAM: 
 *   - key: key untuk data
 *   - value: nilai yang ingin disimpan
 */
function setInLocalStorage(key, value) {
    localStorage.setItem(key, JSON.stringify(value));
}

/**
 * FUNGSI: Remove Local Storage
 * DESKRIPSI: Hapus data dari local storage
 * PARAM: key = key dari data yang ingin dihapus
 */
function removeFromLocalStorage(key) {
    localStorage.removeItem(key);
}

/**
 * FUNGSI: Clear All Local Storage
 * DESKRIPSI: Hapus semua data dari local storage
 */
function clearAllLocalStorage() {
    localStorage.clear();
}

console.log('✓ Script.js loaded successfully');
