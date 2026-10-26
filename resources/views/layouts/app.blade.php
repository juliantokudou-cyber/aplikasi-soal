<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi CBT')</title>

    <!-- Bootstrap 5 & DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Bootstrap Icons (Sangat direkomendasikan untuk ikon navbar & form) -->
    <!-- <link rel="stylesheet" href="https://jsdelivr.net"> -->
    
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
     
     
/* Tombol aksi akun peserta */
.btn-aksi-akun {
    width: 40px;
    height: 40px;
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    padding: 0 !important;
    border: none;
    border-radius: 10px;
    font-size: 1.15rem;
    line-height: 1;
    flex-shrink: 0;
}

.btn-aksi-akun i {
    display: inline-block !important;
    font-family: "bootstrap-icons" !important;
    font-style: normal !important;
    font-weight: normal !important;
    line-height: 1;
    visibility: visible !important;
    opacity: 1 !important;
}

.btn-aksi-akun.btn-warning {
    color: #212529;
}

.btn-aksi-akun.btn-danger {
    color: #fff;
}

.btn-aksi-akun:hover {
    transform: translateY(-1px);
    filter: brightness(0.95);
}

.btn-aksi-akun:focus-visible {
    outline: 3px solid rgba(13, 110, 253, 0.3);
    outline-offset: 2px;
}


/* ===== MODAL EDIT CBT ===== */

.cbt-edit-modal .modal-dialog {
    max-width: 560px;
}

.cbt-edit-modal .modal-content {
    border: 0;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(15, 23, 42, 0.22);
}

/* Header */
.cbt-modal-header {
    padding: 22px 24px;
    color: #fff;
    background: linear-gradient(135deg, #172554, #2563eb);
    border-bottom: 0;
}

.cbt-modal-header .modal-title {
    font-size: 1.15rem;
    letter-spacing: -0.3px;
}

.cbt-modal-header p {
    color: rgba(255, 255, 255, 0.78);
}

.cbt-modal-icon {
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 14px;
    background: rgba(255, 255, 255, 0.15);
    font-size: 1.5rem;
}

/* Body */
.cbt-modal-body {
    padding: 24px;
    background: #fff;
}

.cbt-section-heading {
    display: flex;
    align-items: center;
    gap: 9px;
    margin: 3px 0 18px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e8edf5;
    color: #1e3a8a;
    font-size: 0.95rem;
    font-weight: 700;
}

.cbt-section-heading i {
    font-size: 1.1rem;
}

.cbt-edit-modal .form-label {
    margin-bottom: 7px;
    color: #334155;
    font-size: 0.88rem;
    font-weight: 600;
}

.cbt-input-group {
    border-radius: 10px;
    overflow: hidden;
}

.cbt-input-group .input-group-text {
    min-width: 44px;
    justify-content: center;
    color: #64748b;
    background: #f8fafc;
    border-color: #dbe3ef;
}

.cbt-input-group .form-control {
    min-height: 46px;
    border-color: #dbe3ef;
    font-size: 0.94rem;
    box-shadow: none;
}

.cbt-input-group .form-control:focus {
    position: relative;
    z-index: 2;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
}

.cbt-input-group:focus-within .input-group-text {
    border-color: #3b82f6;
    color: #2563eb;
}

.cbt-edit-modal .form-text {
    margin-top: 7px;
    color: #64748b;
    font-size: 0.79rem;
    line-height: 1.5;
}

/* Info keamanan */
.cbt-password-info {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-top: 20px;
    padding: 15px;
    color: #1e40af;
    background: #eff6ff;
    border: 1px solid #dbeafe;
    border-radius: 12px;
}

.cbt-password-info > i {
    margin-top: 2px;
    font-size: 1.15rem;
}

.cbt-password-info .small {
    color: #475569;
    line-height: 1.6;
}

.cbt-required-note {
    margin-top: 15px;
    color: #64748b;
    font-size: 0.78rem;
}

/* Footer */
.cbt-modal-footer {
    position: sticky;
    bottom: 0;
    z-index: 5;
    display: flex;
    gap: 10px;
    padding: 15px 24px;
    background: #fff;
    border-top: 1px solid #e8edf5;
    box-shadow: 0 -5px 15px rgba(15, 23, 42, 0.035);
}

.cbt-modal-footer .btn {
    min-height: 44px;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 600;
}

.cbt-btn-cancel {
    min-width: 100px;
    border: 1px solid #dbe3ef;
    color: #475569;
}

.cbt-btn-save {
    flex: 1;
    background: #2563eb;
    border-color: #2563eb;
}

.cbt-btn-save:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
}

/* Tampilan HP */
@media (max-width: 576px) {
    .cbt-edit-modal .modal-dialog {
        margin: 10px;
        max-width: none;
        min-height: calc(100% - 20px);
    }

    .cbt-edit-modal .modal-content {
        max-height: calc(100dvh - 20px);
        border-radius: 16px;
    }

    .cbt-modal-header {
        padding: 17px 16px;
    }

    .cbt-modal-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        font-size: 1.3rem;
    }

    .cbt-modal-header .modal-title {
        font-size: 1rem;
    }

    .cbt-modal-body {
        padding: 18px 16px;
        overflow-y: auto;
        overscroll-behavior: contain;
    }

    .cbt-modal-footer {
        padding: 12px 16px;
        gap: 8px;
    }

    .cbt-modal-footer .btn {
        padding: 10px 12px;
        font-size: 0.82rem;
    }

    .cbt-btn-cancel {
        min-width: 85px;
    }

    .cbt-input-group .form-control {
        min-height: 44px;
        font-size: 0.9rem;
    }
}

    </style>

    @stack('styles')
</head>
<body class="@yield('body-class')">

    <!-- Konten Dinamis Halaman Anak Akan Muncul Di Sini -->
    @yield('content')

    
{{-- NOTIFIKASI SESSION GLOBAL --}}
@if(session()->has('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'warning',
                title: 'Paket Soal Belum Tersedia',
                text: @json(session('error')),
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#dc3545'
            });
        });
    </script>
@endif

@if(session()->has('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                confirmButtonText: 'OK',
                confirmButtonColor: '#198754'
            });
        });
    </script>
@endif


    <!-- ============================================================= -->
    <!-- URUTAN SCRIPT LOKAL / CDN YANG BENAR (JANGAN DIUBAH POSISINYA) -->
    <!-- ============================================================= -->
    
    <!-- 1. JQuery Jajib Berada di Paling Atas Sebelum Script Lain -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- 2. Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- 3. SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- 4. DataTables Core & Bootstrap 5 Integration -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- 5. Script Tambahan Dinamis dari Halaman Anak -->
    @stack('scripts')
</body>
</html>

