@extends('layout')

@section('title', 'Tambah Karyawan Baru')

@push('styles')
<style>
    /* Style untuk form input dengan ikon */
    .form-group {
        position: relative;
    }
    .form-control-icon {
        position: absolute;
        top: 50%;
        left: 1rem;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 1.1rem;
    }
    .form-control {
        padding-left: 3rem; /* Padding untuk ikon */
        border-radius: 0.5rem;
        background-color: var(--brand-light);
        border: 1px solid #e2e8f0;
    }
    .form-control:focus {
        background-color: #ffffff;
        border-color: var(--brand-primary);
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
    }
</style>
@endpush

@section('content')
    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('employees.index') }}" class="btn btn-secondary" title="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="mb-0 ms-3 fw-bold">@yield('title')</h4>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-lg-4 d-none d-lg-flex align-items-center justify-content-center p-5" style="background-color: var(--brand-light); border-right: 1px solid var(--border-color);">
                    <div class="text-center">
                        <i class="bi bi-person-plus-fill" style="font-size: 8rem; color: var(--brand-primary); opacity: 0.5;"></i>
                        <h3 class="fw-bold mt-3">Karyawan Baru</h3>
                        <p class="text-muted">Masukkan data diri karyawan baru pada form di samping ini.</p>
                    </div>
                </div>

                <div class="col-lg-8 p-4 p-md-5">
                    <h4 class="fw-bold mb-4"><i class="bi bi-file-earmark-text-fill me-2"></i> Form Pendaftaran</h4>

                    <form method="POST" action="{{ route('employees.store') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <i class="bi bi-person-fill form-control-icon"></i>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Nama Lengkap" required
                                value="{{ old('name') }}">
                        </div>

                        <div class="form-group mb-3">
                            <i class="bi bi-envelope-fill form-control-icon"></i>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Alamat Email" required
                                value="{{ old('email') }}">
                        </div>

                        <div class="form-group mb-3">
                            <i class="bi bi-hash form-control-icon"></i>
                            <input type="text" class="form-control" id="nomor_pegawai" name="nomor_pegawai" placeholder="Nomor Pegawai (NIK)" required
                                value="{{ old('nomor_pegawai') }}">
                        </div>

                        <div class="form-group mb-3">
                            <i class="bi bi-geo-alt-fill form-control-icon"></i>
                            <input type="text" class="form-control" id="penempatan" name="penempatan" placeholder="Lokasi Penempatan" required
                                value="{{ old('penempatan') }}">
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-save-fill me-2"></i>
                                Simpan Karyawan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
