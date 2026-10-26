@extends('layouts.app')

@section('title', 'Registrasi Peserta CBT')
@section('body-class', 'd-flex align-items-center py-5')

@push('styles')
<style>
    body { background: linear-gradient(135deg, #e0eafc, #cfdef3); min-height: 100vh; }
    .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card p-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-person-plus-fill text-primary display-4"></i>
                        <h3 class="fw-bold mt-2 text-dark">Registrasi Ujian</h3>
                        <p class="text-muted small">Silakan lengkapi data diri Anda dengan benar</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control bg-light" placeholder="Nama sesuai KTP" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Tanggal Lahir</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-calendar-event"></i></span>
                                <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="form-control bg-light" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Email Aktif</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control bg-light" placeholder="name@example.com" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Nomor WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-whatsapp"></i></span>
                                <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}" class="form-control bg-light" placeholder="08xxxxxxxxxx" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Password (6 Digit)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control bg-light" placeholder="1 Besar, 1 Kecil, Angka" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-lock-check"></i></span>
                                <input type="password" name="password_confirmation" class="form-control bg-light" placeholder="Ulangi password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-3">Daftar Akun</button>
                    </form>
                    
                    <div class="text-center mt-4">
                        <p class="small mb-0 text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Login Masuk</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- BAGIAN DI BAWAH INI YANG MEMICU POP-UP SWEETALERT --}}
@push('scripts')
@if(session('registration_success'))
<script>
    // Memastikan skrip berjalan setelah dokumen selesai dimuat penuh
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: 'Berhasil Dibuat!',
            text: 'Akun sudah dibuat, minta persetujuan admin untuk mengakses soal.',
            icon: 'success',
            confirmButtonColor: '#198754',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif
@endpush
