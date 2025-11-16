@extends('layout')

@section('content')
    <div class="container py-4">
        <div class="mb-3">
            <a href="{{ route('leaves.create') }}" class="btn btn-warning">
                <i class="bi bi-person-plus"></i> Pengajuan Karyawan Cuti
            </a>
        </div>
        <h5 class="fw-bold mb-3">Daftar Pengajuan Cuti Pegawai</h5>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama Pegawai</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td>{{ $leave->employee->name }}</td>
                                <td>{{ $leave->tanggal_mulai }}</td>
                                <td>{{ $leave->tanggal_selesai }}</td>
                                <td>{{ $leave->keterangan }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">Belum ada pengajuan cuti.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection