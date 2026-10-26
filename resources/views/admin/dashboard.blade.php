
@extends('layouts.app')

@section('title', 'Panel Utama Admin CBT')
@section('body-class', 'bg-light')

@section('content')

{{-- NAVBAR --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3 shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold text-uppercase">
            <i class="bi bi-shield-shaded text-info me-2"></i>
            CBT Command Center
        </span>

        <div class="d-flex align-items-center">
            <span class="text-light small me-3">
                <i class="bi bi-person-fill text-info"></i>
                Administrator
            </span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3">
                    <i class="bi bi-box-arrow-right"></i> Keluar
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container my-5">

    {{-- NAVIGASI TAB --}}
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-pills bg-white p-2 rounded-3 shadow-sm"
                id="adminTab" role="tablist">

                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold"
                            id="manajemen-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#manajemen-pane"
                            type="button" role="tab">
                        <i class="bi bi-people-fill me-2"></i>
                        Manajemen Akun & Approval
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold"
                            id="soal-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#soal-pane"
                            type="button" role="tab">
                        <i class="bi bi-file-earmark-medical-fill me-2"></i>
                        Input Soal Ujian
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold"
                            id="setting-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#setting-pane"
                            type="button" role="tab">
                        <i class="bi bi-gear-fill me-2"></i>
                        Pengaturan Aplikasi
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="adminTabContent">

        {{-- TAB 1: MANAJEMEN AKUN --}}
        <div class="tab-pane fade show active"
             id="manajemen-pane"
             role="tabpanel"
             tabindex="0">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h4 class="fw-bold text-dark mb-1">
                        Manajemen Akun Peserta
                    </h4>
                    <p class="text-muted small mb-0">
                        Kelola data, username CBT, approval, dan akun peserta.
                    </p>
                </div>

                <span class="badge bg-primary rounded-pill px-3 py-2">
                    Total: {{ $allPeserta->count() }} peserta
                </span>
            </div>

            {{-- TABEL PESERTA --}}
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Tanggal Lahir</th>
                                <th>Email</th>
                                <th>No. WhatsApp</th>
                                <th>Username CBT</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($allPeserta as $peserta)
                                <tr>
                                    <td class="fw-bold text-dark">
                                        {{ $peserta->name }}
                                    </td>

                                    <td>
                                        {{ $peserta->birth_date
                                            ? \Carbon\Carbon::parse($peserta->birth_date)->format('d/m/Y')
                                            : '-' }}
                                    </td>

                                    <td>{{ $peserta->email }}</td>

                                    <td>
                                        @if($peserta->whatsapp)
                                            <a href="https://wa.me/{{ preg_replace('/\D/', '', $peserta->whatsapp) }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="text-success text-decoration-none">
                                                <i class="bi bi-whatsapp"></i>
                                                {{ $peserta->whatsapp }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        @if($peserta->username)
                                            <span class="badge bg-dark font-monospace px-2 py-1">
                                                {{ $peserta->username }}
                                            </span>
                                        @else
                                            <span class="text-danger">
                                                Belum dibuat
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($peserta->is_approved)
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                Menunggu approval
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-2">

                                            {{-- TOMBOL EDIT --}}
                                            <button type="button"
                                                    class="btn btn-warning btn-sm btn-aksi-akun"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $peserta->id }}"
                                                    title="Edit akun"
                                                    aria-label="Edit akun">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>

                                            {{-- TOMBOL HAPUS --}}
                                            <form action="{{ route('admin.peserta.delete', $peserta->id) }}"
                                                  method="POST"
                                                  class="form-hapus m-0">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm btn-aksi-akun"
                                                        title="Hapus akun"
                                                        aria-label="Hapus akun">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-people display-6 d-block mb-2"></i>
                                        Belum ada peserta yang mendaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- APPROVAL DAN PEMBUATAN USERNAME --}}
            <div class="card border-0 shadow-sm rounded-3 p-4 mt-4">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-person-check text-primary me-2"></i>
                    Approval dan Pembuatan Username CBT
                </h5>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Peserta</th>
                                <th>Email</th>
                                <th>Username CBT</th>
                                <th style="min-width: 270px;">Approval</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($allPeserta->where('is_approved', false) as $peserta)
                                <tr>
                                    <td>{{ $peserta->name }}</td>
                                    <td>{{ $peserta->email }}</td>
                                    <td>{{ $peserta->username ?: '-' }}</td>

                                    <td>
                                        <form action="{{ route('admin.approve', $peserta->id) }}"
                                              method="POST"
                                              class="form-approve d-flex gap-2">
                                            @csrf

                                            <input type="text"
                                                   name="username"
                                                   maxlength="6"
                                                   pattern="[A-Za-z0-9]{1,6}"
                                                   placeholder="Username"
                                                   class="form-control form-control-sm"
                                                   value="{{ $peserta->username }}"
                                                   required>

                                            <button type="submit"
                                                    class="btn btn-sm btn-primary text-nowrap">
                                                <i class="bi bi-check-lg"></i>
                                                Approve
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Semua peserta sudah diproses.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- TAB 2: INPUT SOAL --}}
        <div class="tab-pane fade" id="soal-pane" role="tabpanel" tabindex="0">
            <div class="row">
                <div class="col-md-5 mx-auto">
                    <div class="card border-0 shadow-sm rounded-3 p-4 bg-white">

                        <div class="text-center mb-4">
                            <i class="bi bi-file-earmark-excel-fill text-success display-4"></i>

                            <h5 class="fw-bold mt-2">
                                Import Bank Soal Ujian
                            </h5>

                            <p class="text-muted small">
                                Unggah berkas soal pilihan ganda dan media pendukung.
                            </p>
                        </div>

                        {{-- FORM IMPORT SOAL --}}
                        <form action="{{ route('admin.soal.import') }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="form-import-soal">

                            @csrf

                            {{-- FILE CSV --}}
                            <div class="mb-3">
                                <label for="file_soal"
                                       class="form-label small fw-bold text-secondary">
                                    1. Pilih Berkas Soal (.csv) *
                                </label>

                                <input type="file"
                                       id="file_soal"
                                       name="file_soal"
                                       class="form-control"
                                       accept=".csv,text/csv"
                                       required>

                                <div class="form-text text-muted mt-2">
                                    Simpan berkas Excel menggunakan format CSV
                                    (Comma delimited).
                                </div>
                            </div>

                            {{-- FILE MEDIA --}}
                            <div class="mb-3">
                                <label for="files_media"
                                       class="form-label small fw-bold text-secondary">
                                    2. File Media Pendukung
                                    <span class="text-muted fw-normal">(Opsional)</span>
                                </label>

                                <input type="file"
                                       id="files_media"
                                       name="files_media[]"
                                       class="form-control"
                                       accept=".jpg,.jpeg,.png,.mp3,.mp4,.m4a"
        multiple>

                                <div class="form-text text-muted mt-2">
                                    Kamu bisa memilih beberapa gambar, audio,
                                    atau video sekaligus sesuai nama file
                                    yang dicantumkan di Excel.
                                </div>
                            </div>

                            <button type="submit"
                                    class="btn btn-success w-100 fw-bold rounded-pill shadow-sm py-2">
                                <i class="bi bi-cloud-arrow-up-fill me-2"></i>
                                Mulai Unggah Paket Soal
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 3: PENGATURAN 
        <div class="tab-pane fade" id="setting-pane" role="tabpanel" tabindex="0">
            <div class="card border-0 shadow-sm rounded-3 p-5 text-center">
                <i class="bi bi-sliders text-secondary display-3 mb-3"></i>

                <h4 class="fw-bold">Pengaturan Sistem</h4>

                <p class="text-muted">
                    Konfigurasi token kelulusan, reset database peserta,
                    dan batasan masa aktif link ujian.
                </p>
            </div>
        </div> --}}

         {{-- TAB 3: PENGATURAN --}}
<div class="tab-pane fade" id="setting-pane" role="tabpanel">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card border-0 shadow-sm rounded-3 p-4 bg-white text-center">
                <i class="bi bi-exclamation-triangle-fill text-danger display-4"></i>
                <h5 class="fw-bold mt-2 text-danger">Pusat Reset Aplikasi</h5>
                <p class="text-muted small">Kosongkan bank soal saat ini untuk bersiap menerima paket soal ujian baru.</p>
                
                <hr class="opacity-25">

                <!-- Tombol Reset Menggunakan Form -->
                <form id="formResetSoal" action="{{ route('admin.soal.reset') }}" method="POST">
                    @csrf
                    <button type="button" id="btnResetSoal" class="btn btn-outline-danger fw-bold rounded-pill px-4 py-2 w-100">
                        <i class="bi bi-trash3-fill me-2"></i> Kosongkan Semua Soal Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


    </div>
</div>

{{-- MODAL EDIT AKUN --}}
@foreach($allPeserta as $peserta)
<div class="modal fade cbt-edit-modal"
     id="editModal{{ $peserta->id }}"
     tabindex="-1"
     aria-labelledby="editModalLabel{{ $peserta->id }}"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">

            <form action="{{ route('admin.peserta.update', $peserta->id) }}"
                  method="POST"
                  class="form-edit">
                @csrf
                @method('PUT')

                {{-- HEADER --}}
                <div class="modal-header cbt-modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="cbt-modal-icon">
                            <i class="bi bi-person-gear"></i>
                        </div>

                        <div>
                            <h5 class="modal-title fw-bold mb-1"
                                id="editModalLabel{{ $peserta->id }}">
                                Edit Akun Peserta
                            </h5>

                            <p class="mb-0 small">
                                Perbarui informasi peserta CBT
                            </p>
                        </div>
                    </div>

                    <button type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                </div>

                {{-- BODY --}}
                <div class="modal-body cbt-modal-body">

                    <div class="cbt-section-heading">
                        <i class="bi bi-person-vcard"></i>
                        <span>Informasi Pribadi</span>
                    </div>

                    {{-- NAMA --}}
                    <div class="mb-3">
                        <label for="name{{ $peserta->id }}" class="form-label">
                            Nama lengkap <span class="text-danger">*</span>
                        </label>

                        <div class="input-group cbt-input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>

                            <input type="text"
                                   id="name{{ $peserta->id }}"
                                   name="name"
                                   class="form-control"
                                   value="{{ $peserta->name }}"
                                   maxlength="255"
                                   required>
                        </div>
                    </div>

                    {{-- TANGGAL LAHIR --}}
                    <div class="mb-3">
                        <label for="birth{{ $peserta->id }}" class="form-label">
                            Tanggal lahir <span class="text-danger">*</span>
                        </label>

                        <div class="input-group cbt-input-group">
                            <span class="input-group-text">
                                <i class="bi bi-calendar-event"></i>
                            </span>

                            <input type="date"
                                   id="birth{{ $peserta->id }}"
                                   name="birth_date"
                                   class="form-control"
                                   value="{{ $peserta->birth_date ? \Carbon\Carbon::parse($peserta->birth_date)->format('Y-m-d') : '' }}"
                                   required>
                        </div>
                    </div>

                    {{-- EMAIL --}}
                    <div class="mb-3">
                        <label for="email{{ $peserta->id }}" class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>

                        <div class="input-group cbt-input-group">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>

                            <input type="email"
                                   id="email{{ $peserta->id }}"
                                   name="email"
                                   class="form-control"
                                   value="{{ $peserta->email }}"
                                   maxlength="255"
                                   required>
                        </div>
                    </div>

                    {{-- WHATSAPP --}}
                    <div class="mb-4">
                        <label for="whatsapp{{ $peserta->id }}" class="form-label">
                            Nomor WhatsApp <span class="text-danger">*</span>
                        </label>

                        <div class="input-group cbt-input-group">
                            <span class="input-group-text">
                                <i class="bi bi-whatsapp"></i>
                            </span>

                            <input type="text"
                                   id="whatsapp{{ $peserta->id }}"
                                   name="whatsapp"
                                   class="form-control"
                                   value="{{ $peserta->whatsapp }}"
                                   maxlength="25"
                                   required>
                        </div>
                    </div>

                    <div class="cbt-section-heading">
                        <i class="bi bi-shield-lock"></i>
                        <span>Akun CBT</span>
                    </div>

                    {{-- USERNAME --}}
                    <div class="mb-3">
                        <label for="username{{ $peserta->id }}" class="form-label">
                            Username CBT
                        </label>

                        <div class="input-group cbt-input-group">
                            <span class="input-group-text">
                                <i class="bi bi-person-badge"></i>
                            </span>

                            <input type="text"
                                   id="username{{ $peserta->id }}"
                                   name="username"
                                   class="form-control"
                                   value="{{ $peserta->username }}"
                                   maxlength="6"
                                   pattern="[A-Za-z0-9]{1,6}"
                                   title="Maksimal 6 karakter huruf atau angka."
                                   placeholder="Contoh: ABcd12">
                        </div>

                        <div class="form-text">
                            Maksimal 6 karakter huruf atau angka.
                            Kosongkan jika belum dibuat.
                        </div>
                    </div>

                    {{-- INFO PASSWORD --}}
                    <div class="cbt-password-info mb-3">
                        <i class="bi bi-info-circle-fill"></i>

                        <div>
                            <div class="fw-semibold mb-1">Keamanan akun</div>

                            <div class="small">
                                Password tidak ditampilkan dan tidak diubah
                                melalui formulir ini.
                            </div>
                        </div>
                    </div>

                    <div class="cbt-required-note">
                        <span class="text-danger">*</span> Wajib diisi
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="modal-footer cbt-modal-footer">
                    <button type="button"
                            class="btn btn-light cbt-btn-cancel"
                            data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>
                        Batal
                    </button>

                    <button type="submit"
                            class="btn btn-primary cbt-btn-save">
                        <i class="bi bi-check2-circle me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endforeach

{{-- SWEETALERT2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Notifikasi sukses
    @if(session()->has('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            confirmButtonText: 'OK',
            confirmButtonColor: '#198754'
        });
    @endif

    // Notifikasi gagal
    @if(session()->has('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: @json(session('error')),
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#dc3545'
        });
    @endif

    // Validasi Laravel
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Validasi gagal',
            html: @json(implode('<br>', $errors->all())),
            confirmButtonText: 'Periksa kembali',
            confirmButtonColor: '#0d6efd'
        });
    @endif

    // Konfirmasi hapus akun
    document.querySelectorAll('.form-hapus').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Hapus akun peserta?',
                text: 'Data akun akan dihapus. Tindakan ini tidak dapat dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Konfirmasi simpan edit
    document.querySelectorAll('.form-edit').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            if (!form.reportValidity()) {
                return;
            }

            Swal.fire({
                title: 'Simpan perubahan?',
                text: 'Data akun peserta akan diperbarui.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Konfirmasi approval
    document.querySelectorAll('.form-approve').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            if (!form.reportValidity()) {
                return;
            }

            Swal.fire({
                title: 'Approve peserta?',
                text: 'Pastikan username CBT sudah benar.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, approve',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Konfirmasi upload soal
    document.querySelectorAll('.form-import-soal').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            if (!form.reportValidity()) {
                return;
            }

            Swal.fire({
                title: 'Unggah paket soal?',
                text: 'Pastikan file CSV dan media pendukung sudah benar.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, unggah',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

      // ==========================================
    // 8. KONFIRMASI RESET SELURUH BANK SOAL
    // ==========================================
    const btnResetSoal = document.getElementById('btnResetSoal');

    if (btnResetSoal) {
        btnResetSoal.addEventListener('click', function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Kosongkan Bank Soal?',
                text: 'Tindakan ini akan menghapus SELURUH soal yang ada di database secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true
            }).then(function(result) {
                if (result.isConfirmed) {
                    const formResetSoal = document.getElementById('formResetSoal');

                    if (formResetSoal) {
                        formResetSoal.submit();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Form reset bank soal tidak ditemukan.',
                            confirmButtonText: 'Mengerti',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                }
            });
        });
    }

});
</script>

@endsection


