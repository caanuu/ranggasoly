@extends('layout')

@section('title', 'Pengaturan Tarif Gaji')

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
        <a href="{{ route('salary.index') }}" class="btn btn-secondary" title="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h4 class="mb-0 ms-3 fw-bold">@yield('title')</h4>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-lg-4 d-none d-lg-flex align-items-center justify-content-center p-5" style="background-color: var(--brand-light); border-right: 1px solid var(--border-color);">
                    <div class="text-center">
                        <i class="bi bi-sliders" style="font-size: 8rem; color: var(--brand-primary); opacity: 0.5;"></i>
                        <h3 class="fw-bold mt-3">Tarif Gaji</h3>
                        <p class="text-muted">Atur nominal standar untuk perhitungan gaji karyawan. Tarif tidak hadir bisa diisi minus.</p>
                    </div>
                </div>

                <div class="col-lg-8 p-4 p-md-5">
                    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-fill me-2"></i> Form Pengaturan</h4>

                    <form method="POST" action="{{ route('salary.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <i class="bi bi-check-circle-fill form-control-icon"></i>
                            <label for="present_rate" class="form-label">Tarif Hadir</label>
                            <input type="number" class="form-control" id="present_rate" name="present_rate"
                                value="{{ old('present_rate', $setting->present_rate ?? 100000) }}">
                        </div>

                        <div class="form-group mb-3">
                            <i class="bi bi-bandaid-fill form-control-icon"></i>
                            <label for="sick_rate" class="form-label">Tarif Sakit</label>
                            <input type="number" class="form-control" id="sick_rate" name="sick_rate"
                                value="{{ old('sick_rate', $setting->sick_rate ?? 0) }}">
                        </div>

                        <div class="form-group mb-3">
                            <i class="bi bi-x-circle-fill form-control-icon"></i>
                            <label for="absent_rate" class="form-label">Tarif Tidak Hadir (Izin/Cuti/Alpha)</label>
                            <input type="number" class="form-control" id="absent_rate" name="absent_rate"
                                value="{{ old('absent_rate', $setting->absent_rate ?? -100000) }}">
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-save-fill me-2"></i>
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
