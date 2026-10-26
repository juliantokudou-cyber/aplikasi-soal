@extends('layouts.app')

@section('title', 'Login CBT')
@section('body-class', 'd-flex align-items-center')

@push('styles')
<style>
    body { background: linear-gradient(135deg, #e2e2e2, #c9d6ff); height: 100vh; }
    .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card p-3">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-shield-lock text-success display-4"></i>
                        <h3 class="fw-bold mt-2 text-dark">Portal CBT</h3>
                        <p class="text-muted small">Masukkan kredensial ujian Anda</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger small py-2 text-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first('username') }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-secondary">Username Ujian</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-card-text"></i></span>
                                <input type="text" name="username" class="form-control bg-light text-center fw-bold" placeholder="Diberikan oleh Admin" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label small fw-semibold text-secondary">Password Anda</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-key"></i></span>
                                <input type="password" name="password" class="form-control bg-light" placeholder="******" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm rounded-3">Masuk Ujian</button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="small mb-0 text-muted">Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-primary">Registrasi Dahulu</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
