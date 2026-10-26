@extends('layouts.app')

@section('title', 'Dashboard Ujian CBT')
@section('body-class', 'bg-light')

@section('content')
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold text-primary"><i class="bi bi-journal-check me-2"></i> CBT-App</span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-secondary small d-none d-sm-inline">
                <i class="bi bi-person-circle text-primary me-1"></i> {{ auth()->user()->name }} (<span class="fw-semibold">{{ auth()->user()->username }}</span>)
            </span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-danger rounded-3 fw-medium"><i class="bi bi-power"></i> Log Out</button>
            </form>
        </div>
    </div>
</nav>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 bg-white rounded-3 shadow-sm border border-light">
                <h2 class="fw-bold text-dark">Selamat Datang di Ruang Ujian!</h2>
                <p class="text-secondary mb-0">Semua aktivitas dan waktu pengerjaan latihan soal Anda akan terekam secara otomatis oleh sistem komputer.</p>
            </div>
        </div>
    </div>

    <h5 class="fw-bold text-secondary mb-3">Daftar Paket Soal Tersedia</h5>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
            <div class="card h-100 bg-white border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-4 d-flex flex-column">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle align-self-start mb-3">Simulasi Utama</span>
                    <h5 class="card-title fw-bold text-dark">Latihan Soal Kemampuan Dasar</h5>
                    <p class="card-text text-secondary small flex-grow-1">Uji pemahaman logika matematika dasar, verbal bahasa indonesia, dan pengetahuan umum.</p>
                    <hr class="text-muted opacity-25">
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span class="small text-muted"><i class="bi bi-clock me-1"></i> 70 Menit</span>
                        <a href="{{ route('ujian.mulai') }}" class="btn btn-sm btn-primary px-3 rounded-pill fw-semibold">Mulai Tes <i class="bi bi-arrow-right-short"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

