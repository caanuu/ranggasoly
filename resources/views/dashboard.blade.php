@extends('layout')

@section('title', 'Dashboard')

@push('styles')
    <style>
        /*
              =================================
              STYLE KHUSUS DASHBOARD
              =================================
            */

        /* Kartu Selamat Datang (BARU) */
        .welcome-card {
            background: linear-gradient(90deg, var(--brand-primary), #3d5afe);
            border: none;
            color: #ffffff;
        }

        /* Kartu Statistik */
        .stat-card {
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.07);
        }

        .stat-card .icon-wrapper {
            width: 60px;
            /* Sedikit lebih besar */
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* Aksi Cepat (Desain Baru) */
        .quick-action-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            color: var(--text-primary);
            background-color: #ffffff;
            /* Ubah dari brand-light agar lebih menonjol */
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
            height: 100%;
        }

        .quick-action-card:hover {
            background-color: var(--brand-primary);
            color: #ffffff;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2);
            border-color: var(--brand-primary);
        }

        .quick-action-card i {
            font-size: 2.25rem;
            /* Sedikit lebih besar */
            margin-bottom: 0.75rem;
        }

        .quick-action-card span {
            font-weight: 600;
            font-size: 0.95rem;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-card">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->name }}!</h4>
                    <p class="mb-0" style="font-size: 1.05rem;">Ringkasan aktivitas dan penggajian ada di bawah ini.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrapper bg-primary bg-opacity-10">
                        <i class="bi bi-people-fill text-primary fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase">Total Karyawan</div>
                        <div class="fw-bold fs-3 mb-0">{{ $totalEmployees }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrapper bg-success bg-opacity-10">
                        <i class="bi bi-calendar-check-fill text-success fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase">Hadir Hari Ini</div>
                        <div class="fw-bold fs-3 mb-0">{{ $hadirTepatWaktu }}</div>
                    </div>
                    <div class="ms-auto text-success small fw-semibold">{{ $attendancePercentage }}%</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrapper bg-warning bg-opacity-10">
                        <i class="bi bi-cash-stack text-warning fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase">Total Gaji (Bln Ini)</div>
                        <div class="fw-bold fs-4 mb-0">Rp {{ number_format($totalSalary, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrapper bg-danger bg-opacity-10">
                        <i class="bi bi-calendar-x-fill text-danger fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase">Karyawan Cuti</div>
                        <div class="fw-bold fs-3 mb-0">{{ $karyawanCuti }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-2 px-3">Nama</th>
                                    <th class="py-2 px-3">Aktivitas</th>
                                    <th class="py-2 px-3">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td class="px-3">{{ $log->employee->name }}</td>
                                        <td class="px-3">{{ $log->activity }}</td>
                                        <td class="px-3 text-muted small">{{ $log->created_at->format('d M, H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted p-4">
                                            Belum ada aktivitas terbaru.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($logs->count() > 0)
                    <div class="card-footer bg-white text-center py-3">
                        <a href="{{ route('activity.index') }}" class="text-decoration-none fw-semibold text-primary">
                            Lihat Semua Aktivitas <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card h-100" style="background-color: var(--brand-light); border:none; box-shadow:none;">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0 fw-bold">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <a href="{{ route('employees.index') }}" class="quick-action-card">
                                <i class="bi bi-people-fill text-primary"></i>
                                <span>Daftar Karyawan</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('attendances.index') }}" class="quick-action-card">
                                <i class="bi bi-calendar-check-fill text-success"></i>
                                <span>Cek Absensi</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('salary.index') }}" class="quick-action-card">
                                <i class="bi bi-cash-stack text-warning"></i>
                                <span>Proses Gaji</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('report.index') }}" class="quick-action-card">
                                <i class="bi bi-clipboard-data-fill text-info"></i>
                                <span>Lihat Laporan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
