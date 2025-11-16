@extends('layout')

@section('title', 'Laporan Gaji & Kehadiran')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">@yield('title')</h4>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('report.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="filter" class="form-label">Filter Periode</label>
                    <select name="filter" id="filter" class="form-select" onchange="this.form.submit()">
                        <option value="week" {{ request('filter') == 'week' ? 'selected' : '' }}>Mingguan</option>
                        <option value="month" {{ request('filter', 'month') == 'month' ? 'selected' : '' }}>Bulanan</option>
                        <option value="year" {{ request('filter') == 'year' ? 'selected' : '' }}>Tahunan</option>
                    </select>
                </div>

                @if(request('filter') == 'month' || !request('filter'))
                    <div class="col-md-3">
                        <label class="form-label">Bulan</label>
                        <select name="month" class="form-select">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $m == request('month', now()->month) ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="year" value="{{ request('year', now()->year) }}" class="form-control">
                    </div>
                @elseif(request('filter') == 'year')
                    <div class="col-md-3">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="year" value="{{ request('year', now()->year) }}" class="form-control">
                    </div>
                @endif

                <div class="col-md-3">
                    <button type="submit" class="btn btn-outline-secondary w-100">
                         <i class="bi bi-filter"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrapper bg-primary bg-opacity-10">
                        <i class="bi bi-people-fill text-primary fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase">Total Karyawan</div>
                        <div class="fw-bold fs-3 mb-0">{{ $reportData->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrapper bg-success bg-opacity-10">
                        <i class="bi bi-cash-stack text-success fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase">Total Gaji ({{ $label }})</div>
                        <div class="fw-bold fs-4 mb-0">Rp {{ number_format($salarycost, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-wrapper bg-danger bg-opacity-10">
                        <i class="bi bi-calendar-x-fill text-danger fs-2"></i>
                    </div>
                    <div>
                        <div class="text-muted small text-uppercase">Pegawai Cuti ({{ $label }})</div>
                        <div class="fw-bold fs-3 mb-0">{{ $totalCutiPegawai }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">Detail Laporan ({{ $label }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Nama</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Izin</th>
                            <th class="text-center">Sakit</th>
                            <th class="text-center">Cuti</th>
                            <th class="text-center">Tidak Hadir</th>
                            <th class="text-end pe-4">Total Gaji</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData as $data)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($data['employee']->name) }}&background=0f172a&color=cbd5e1&size=36"
                                            alt="Avatar" class="rounded-circle">
                                        <div class="fw-bold">{{ $data['employee']->name }}</div>
                                    </div>
                                </td>
                                <td class="text-center">{{ $data['hadir'] }}</td>
                                <td class="text-center">{{ $data['izin'] }}</td>
                                <td class="text-center">{{ $data['sakit'] }}</td>
                                <td class="text-center">{{ $data['cuti'] }}</td>
                                <td class="text-center">{{ $data['tidak_hadir'] }}</td>
                                <td class="text-end pe-4">Rp {{ number_format($data['totalSalary'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
