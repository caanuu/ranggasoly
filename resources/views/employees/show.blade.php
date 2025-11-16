@extends('layout')

@section('title', 'Detail Karyawan')

@push('styles')
    <style>
        /* Style untuk tab navigasi */
        .profile-nav .nav-link {
            color: var(--text-muted);
            font-weight: 500;
        }

        .profile-nav .nav-link.active {
            color: var(--brand-primary);
            background-color: transparent;
            border-bottom: 2px solid var(--brand-primary);
        }

        .profile-nav .nav-link:hover {
            color: var(--text-primary);
        }

        /* Badge rekap kustom */
        .rekap-badge {
            font-size: 0.9rem;
            padding: 0.5em 0.75em;
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

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4 p-md-5">
            <div class="row g-4 align-items-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-3">{{ $employee->name }}</h2>
                    <dl class="row">
                        <dt class="col-sm-3 text-muted">Nomor Pegawai</dt>
                        <dd class="col-sm-9">{{ $employee->nomor_pegawai }}</dd>

                        <dt class="col-sm-3 text-muted">Email</dt>
                        <dd class="col-sm-9">{{ $employee->email }}</dd>

                        <dt class="col-sm-3 text-muted">Penempatan</dt>
                        <dd class="col-sm-9">{{ $employee->penempatan ?? '-' }}</dd>
                    </dl>
                </div>
                <div class="col-lg-4">
                    @php
                        $rekap = [
                            'hadir' => 0,
                            'izin' => 0,
                            'sakit' => 0,
                            'cuti' => 0,
                            'terlambat' => 0,
                            'tidak_hadir' => 0,
                            'kosong' => 0,
                        ];
                        foreach ($attendances as $att) {
                            if (isset($rekap[$att->status])) {
                                $rekap[$att->status]++;
                            }
                        }
                    @endphp
                    <h5 class="fw-bold mb-3">Rekap Absensi (Semua)</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-success rekap-badge">Hadir: {{ $rekap['hadir'] }}</span>
                        <span class="badge bg-warning text-dark rekap-badge">Terlambat: {{ $rekap['terlambat'] }}</span>
                        <span class="badge bg-danger rekap-badge">Sakit: {{ $rekap['sakit'] }}</span>
                        <span class="badge bg-primary rekap-badge">Cuti: {{ $rekap['cuti'] }}</span>
                        <span class="badge bg-info text-dark rekap-badge">Izin: {{ $rekap['izin'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-0 pb-0 pt-3 profile-nav">
            <ul class="nav nav-tabs" id="historyTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="kehadiran-tab" data-bs-toggle="tab" data-bs-target="#tab-kehadiran"
                        type="button" role="tab" aria-controls="tab-kehadiran" aria-selected="true">
                        <i class="bi bi-calendar-check-fill me-1"></i> Riwayat Kehadiran
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="cuti-tab" data-bs-toggle="tab" data-bs-target="#tab-cuti" type="button"
                        role="tab" aria-controls="tab-cuti" aria-selected="false">
                        <i class="bi bi-calendar-x-fill me-1"></i> Riwayat Cuti
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body p-0">
            <div class="tab-content" id="historyTabContent">

                <div class="tab-pane fade show active" id="tab-kehadiran" role="tabpanel" aria-labelledby="kehadiran-tab">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Foto Bukti</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $att)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($att->tanggal)->format('d F Y') }}</td>
                                        <td>
                                            @if ($att->status == 'hadir')
                                                <span class="badge bg-success">Hadir</span>
                                            @elseif($att->status == 'terlambat')
                                                <span class="badge bg-warning text-dark">Terlambat</span>
                                            @elseif($att->status == 'izin')
                                                <span class="badge bg-info text-dark">Izin</span>
                                            @elseif($att->status == 'sakit')
                                                <span class="badge bg-danger">Sakit</span>
                                            @elseif($att->status == 'cuti')
                                                <span class="badge bg-primary text-light">Cuti</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Hadir</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($att->foto_bukti)
                                                <a href="{{ asset('storage/' . $att->foto_bukti) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $att->foto_bukti) }}" alt="Bukti"
                                                        width="50" height="50" class="img-thumbnail"
                                                        style="object-fit: cover;">
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted p-4">Belum ada data kehadiran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-cuti" role="tabpanel" aria-labelledby="cuti-tab">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($leaves as $leave)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($leave->tanggal_mulai)->format('d F Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($leave->tanggal_selesai)->format('d F Y') }}</td>
                                        <td>{{ $leave->keterangan ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted p-4">Belum ada data pengajuan cuti.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
