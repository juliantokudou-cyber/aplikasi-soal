@extends('layouts.app')

@section('title', 'Menunggu Persetujuan')
@section('body-class', 'd-flex align-items-center')

@push('styles')
<style>
    body { background-color: #f8f9fa; height: 100vh; }
    .card { border: none; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card p-5 bg-white">
                <div class="card-body">
                    <div class="spinner-border text-warning mb-4" style="width: 3rem; height: 3rem;"></div>
                    <h3 class="fw-bold text-dark mb-2">Akun Belum Aktif</h3>
                    <p class="text-secondary mb-4 px-lg-4">
                        Sistem mendeteksi status pendaftaran Anda belum disetujui. Silakan hubungi Admin WhatsApp untuk mendapatkan <strong>Username Ujian</strong> resmi Anda.
                    </p>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger px-4 rounded-pill fw-semibold">
                            <i class="bi bi-arrow-left-short me-1"></i> Kembali ke Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

