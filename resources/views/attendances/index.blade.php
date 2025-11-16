@extends('layout')

@section('title', 'Data Absensi')

@push('styles')
<style>
    /* Style untuk tab navigasi */
    .rekap-nav .nav-link {
        color: var(--text-muted);
        font-weight: 500;
    }
    .rekap-nav .nav-link.active {
        color: var(--brand-primary);
        background-color: transparent;
        border-bottom: 2px solid var(--brand-primary);
    }
    .rekap-nav .nav-link:hover {
        color: var(--text-primary);
    }

    /* Ukuran avatar di tabel */
    .avatar-sm {
        width: 36px;
        height: 36px;
        object-fit: cover;
    }
</style>
@endpush

@section('content')
    <div class="row g-3 mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="fw-bold mb-0">@yield('title')</h4>
            <span class="text-muted">Rekap Kehadiran Pegawai @if($label) ({{ $label }}) @endif</span>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('leaves.index') }}" class="btn btn-warning me-2">
                <i class="bi bi-calendar-x-fill"></i> Daftar Karyawan Cuti
            </a>
            <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus-fill"></i> Kehadiran Baru
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="filter" class="form-label">Filter Periode</label>
                    <select name="filter" id="filter" class="form-select" onchange="this.form.submit()">
                        <option value="week" {{ request('filter', 'week') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ request('filter') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="year" {{ request('filter') == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </div>

                @if(request('filter') == 'month')
                    <div class="col-md-3">
                        <label for="month" class="form-label">Pilih Bulan</label>
                        <input type="month" id="month" name="month" class="form-control" value="{{ request('month', now()->format('Y-m')) }}">
                    </div>
                @elseif(request('filter') == 'year')
                    <div class="col-md-3">
                        <label for="year" class="form-label">Pilih Tahun</label>
                        <input type="number" id="year" name="year" class="form-control" min="2000" max="2100"
                            value="{{ request('year', now()->year) }}">
                    </div>
                @endif

                <div class="col-md-3">
                    <button class="btn btn-outline-secondary w-100" type="submit">
                        <i class="bi bi-filter"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">

        @if(request('filter', 'week') == 'week')
            <div class="card-header bg-white border-0 pb-0 pt-3 rekap-nav">
                <ul class="nav nav-tabs" id="rekapTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="ringkasan-tab" data-bs-toggle="tab"
                            data-bs-target="#tab-ringkasan" type="button" role="tab"
                            aria-selected="true">
                            <i class="bi bi-bar-chart-fill me-1"></i> Ringkasan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="harian-tab" data-bs-toggle="tab" data-bs-target="#tab-harian"
                            type="button" role="tab" aria-selected="false">
                            <i class="bi bi-calendar-week-fill me-1"></i> Detail Harian
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-0">
                <div class="tab-content" id="rekapTabContent">
                    <div class="tab-pane fade show active" id="tab-ringkasan" role="tabpanel">
                        <div class="p-3">
                            @php
                                $currentStart = request('start') ? \Carbon\Carbon::parse(request('start')) : \Carbon\Carbon::now()->startOfWeek();
                                $prevStart = $currentStart->copy()->subWeek()->format('Y-m-d');
                                $nextStart = $currentStart->copy()->addWeek()->format('Y-m-d');
                                $todayStart = \Carbon\Carbon::now()->startOfWeek();
                                $disableNext = $currentStart->greaterThanOrEqualTo($todayStart);
                            @endphp
                            <form method="GET" class="d-flex align-items-center gap-2">
                                <input type="hidden" name="filter" value="week">
                                <input type="hidden" name="start" value="{{ $prevStart }}">
                                <button class="btn btn-outline-secondary btn-sm" type="submit" title="Minggu Sebelumnya">
                                    <i class="bi bi-arrow-left"></i>
                                </button>
                            </form>
                            <span class="fw-semibold fs-5 mx-3">
                                {{ \Carbon\Carbon::parse($dates[0])->format('d M') }} - {{ \Carbon\Carbon::parse($dates[count($dates) - 1])->format('d M Y') }}
                            </span>
                            <form method="GET" class="d-inline">
                                <input type="hidden" name="filter" value="week">
                                <input type="hidden" name="start" value="{{ $nextStart }}">
                                <button class="btn btn-outline-secondary btn-sm" type="submit" title="Minggu Berikutnya" {{ $disableNext ? 'disabled' : '' }}>
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Nama Pegawai</th>
                                        <th class="text-center">Hadir</th>
                                        <th class="text-center">Izin</th>
                                        <th class="text-center">Sakit</th>
                                        <th class="text-center">Cuti</th>
                                        <th class="text-center">Tidak Hadir</th>
                                        <th class="text-center">Tidak Ada Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employees as $employee)
                                        @php
                                            $count = ['hadir' => 0, 'terlambat' => 0, 'izin' => 0, 'sakit' => 0, 'cuti' => 0, 'tidak_hadir' => 0, 'kosong' => 0];
                                            foreach ($dates as $date) {
                                                $att = $employee->attendances->firstWhere('tanggal', $date);
                                                if ($att) {
                                                    $count[$att->status] = ($count[$att->status] ?? 0) + 1;
                                                } else {
                                                    $count['kosong']++;
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->name) }}&background=0f172a&color=cbd5e1&size=36" alt="Avatar" class="rounded-circle avatar-sm">
                                                    <span class="fw-bold">{{ $employee->name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $count['hadir'] }}</td>
                                            <td class="text-center">{{ $count['izin'] }}</td>
                                            <td class="text-center">{{ $count['sakit'] }}</td>
                                            <td class="text-center">{{ $count['cuti'] }}</td>
                                            <td class="text-center">{{ $count['tidak_hadir'] }}</td>
                                            <td class="text-center">{{ $count['kosong'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted p-4">Belum ada data pegawai.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-harian" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Nama Pegawai</th>
                                        @foreach($dates as $date)
                                            <th class="text-center">
                                                {{ \Carbon\Carbon::parse($date)->format('D') }}<br>
                                                <span class="small">{{ \Carbon\Carbon::parse($date)->format('d/m') }}</span>
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employees as $employee)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->name) }}&background=0f172a&color=cbd5e1&size=36" alt="Avatar" class="rounded-circle avatar-sm">
                                                    <span class="fw-bold">{{ $employee->name }}</span>
                                                </div>
                                            </td>
                                            @foreach($dates as $date)
                                                @php $att = $employee->attendances->firstWhere('tanggal', $date); @endphp
                                                <td class="text-center">
                                                    @if($att)
                                                        @if($att->status == 'hadir')
                                                            <span class="text-success fw-bold" title="Hadir">&#10004;</span>
                                                        @elseif($att->status == 'terlambat')
                                                            <span class="text-warning fw-bold" title="Terlambat">&#9888;</span>
                                                        @elseif($att->status == 'izin')
                                                            <span class="text-info fw-bold" title="Izin">I</span>
                                                        @elseif($att->status == 'sakit')
                                                            <span class="text-danger fw-bold" title="Sakit">S</span>
                                                        @elseif($att->status == 'cuti')
                                                            <span class="text-primary fw-bold" title="Cuti">C</span>
                                                        @else
                                                            <span class="text-danger fw-bold" title="Tidak Hadir">&#10008;</span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ count($dates) + 1 }}" class="text-center text-muted p-4">Belum ada data pegawai.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        @elseif(request('filter') == 'month')
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nama Pegawai</th>
                                <th class="text-center">Hadir</th>
                                <th class="text-center">Terlambat</th>
                                <th class="text-center">Izin</th>
                                <th class="text-center">Sakit</th>
                                <th class="text-center">Cuti</th>
                                <th class="text-center">Tidak Hadir</th>
                                <th class="text-center">Tidak Ada Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $employee)
                                @php
                                    $count = ['hadir' => 0, 'terlambat' => 0, 'izin' => 0, 'sakit' => 0, 'cuti' => 0, 'tidak_hadir' => 0, 'kosong' => 0];
                                    foreach ($dates as $date) {
                                        $att = $employee->attendances->firstWhere('tanggal', $date);
                                        if ($att) {
                                            $count[$att->status] = ($count[$att->status] ?? 0) + 1;
                                        } else {
                                            $count['kosong']++;
                                        }
                                    }
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->name) }}&background=0f172a&color=cbd5e1&size=36" alt="Avatar" class="rounded-circle avatar-sm">
                                            <span class="fw-bold">{{ $employee->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $count['hadir'] }}</td>
                                    <td class="text-center">{{ $count['terlambat'] }}</td>
                                    <td class="text-center">{{ $count['izin'] }}</td>
                                    <td class="text-center">{{ $count['sakit'] }}</td>
                                    <td class="text-center">{{ $count['cuti'] }}</td>
                                    <td class="text-center">{{ $count['tidak_hadir'] }}</td>
                                    <td class="text-center">{{ $count['kosong'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted p-4">Belum ada data pegawai.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>
        @elseif(request('filter') == 'year')
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        </table>
                </div>
            </div>
        @endif
    </div>
@endsection
